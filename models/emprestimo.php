<?php
/*
 * emprestimo.php (Model de Empréstimos)
 *
 * Um empréstimo liga um livro a um leitor.
 * Campos: id, livro_id, leitor_id, data_emprestimo, data_prevista, data_devolucao.
 * As datas são guardadas no formato AAAA-MM-DD. data_devolucao fica null
 * enquanto o livro não é devolvido.
 */
require_once __DIR__ . '/armazenamento.php';
require_once __DIR__ . '/livro.php';
require_once __DIR__ . '/leitor.php';

// Regras de negócio da biblioteca
const LIMITE_EMPRESTIMOS_POR_LEITOR = 3;
const PRAZO_MAXIMO_DIAS = 30;

/**
 * Situação do empréstimo: 'devolvido', 'atrasado' ou 'em_dia'.
 */
function statusEmprestimo(array $emprestimo): string
{
    if ($emprestimo['data_devolucao'] !== null) {
        return 'devolvido';
    }
    if ($emprestimo['data_prevista'] < date('Y-m-d')) {
        return 'atrasado';
    }
    return 'em_dia';
}

/**
 * Quantos dias o empréstimo passou (ou está passando) da data prevista.
 * Para empréstimos em aberto, compara com hoje; para devolvidos, com a data da devolução.
 */
function diasDeAtraso(array $emprestimo): int
{
    $prevista = new DateTime($emprestimo['data_prevista']);
    $fim = new DateTime($emprestimo['data_devolucao'] ?? 'today');

    if ($fim <= $prevista) {
        return 0;
    }
    return $prevista->diff($fim)->days;
}

/**
 * Lista os empréstimos (mais recentes primeiro), já com o título do livro,
 * o nome e telefone do leitor, a situação e os dias de atraso.
 *
 * $filtro: 'ativos', 'atrasados', 'devolvidos' ou 'todos'.
 */
function listarEmprestimos(string $filtro = 'todos'): array
{
    // array_column(..., null, 'id') cria um array indexado pelo id,
    // para achar o livro/leitor de cada empréstimo sem percorrer a lista toda
    $livros = array_column(lerRegistros('livros'), null, 'id');
    $leitores = array_column(lerRegistros('leitores'), null, 'id');

    $lista = [];
    foreach (lerRegistros('emprestimos') as $emprestimo) {
        $status = statusEmprestimo($emprestimo);

        if ($filtro === 'ativos' && $status === 'devolvido') continue;
        if ($filtro === 'atrasados' && $status !== 'atrasado') continue;
        if ($filtro === 'devolvidos' && $status !== 'devolvido') continue;

        $livro = $livros[$emprestimo['livro_id']] ?? null;
        $leitor = $leitores[$emprestimo['leitor_id']] ?? null;

        $emprestimo['livro_titulo'] = $livro['titulo'] ?? '(livro excluído)';
        $emprestimo['leitor_nome'] = $leitor['nome'] ?? '(leitor excluído)';
        $emprestimo['leitor_telefone'] = $leitor['telefone'] ?? '';
        $emprestimo['status'] = $status;
        $emprestimo['dias_atraso'] = diasDeAtraso($emprestimo);

        $lista[] = $emprestimo;
    }

    // Ordena do mais novo para o mais antigo (<=> compara dois valores: -1, 0 ou 1)
    usort($lista, fn($a, $b) => $b['id'] <=> $a['id']);

    return $lista;
}

/**
 * Registra um novo empréstimo, aplicando as regras de negócio:
 *  - o livro precisa existir e estar disponível;
 *  - o leitor precisa existir, não pode ter empréstimo atrasado
 *    e pode ter no máximo LIMITE_EMPRESTIMOS_POR_LEITOR livros ao mesmo tempo.
 * Se alguma regra falhar, lança uma Exception com a explicação.
 */
function registrarEmprestimo(int $livroId, int $leitorId, DateTime $dataPrevista): array
{
    $livro = buscarLivro($livroId);
    if ($livro === null) {
        throw new Exception('Livro não encontrado.');
    }
    if (livroEstaEmprestado($livroId)) {
        throw new Exception("O livro \"{$livro['titulo']}\" já está emprestado.");
    }

    $leitor = buscarLeitor($leitorId);
    if ($leitor === null) {
        throw new Exception('Leitor não encontrado.');
    }
    if (leitorTemAtraso($leitorId)) {
        throw new Exception("{$leitor['nome']} tem empréstimo em atraso e só pode pegar outro livro depois de devolvê-lo.");
    }
    if (contarEmprestimosAtivos($leitorId) >= LIMITE_EMPRESTIMOS_POR_LEITOR) {
        throw new Exception("{$leitor['nome']} já está com " . LIMITE_EMPRESTIMOS_POR_LEITOR . ' livros emprestados, que é o limite.');
    }

    return salvarRegistro('emprestimos', [
        'id'              => null,
        'livro_id'        => $livroId,
        'leitor_id'       => $leitorId,
        'data_emprestimo' => date('Y-m-d'),
        'data_prevista'   => $dataPrevista->format('Y-m-d'),
        'data_devolucao'  => null,
    ]);
}

/**
 * Registra a devolução de um empréstimo (data de devolução = hoje).
 * Retorna o empréstimo atualizado.
 */
function registrarDevolucao(int $id): array
{
    $emprestimo = buscarPorId(lerRegistros('emprestimos'), $id);

    if ($emprestimo === null) {
        throw new Exception('Empréstimo não encontrado.');
    }
    if ($emprestimo['data_devolucao'] !== null) {
        throw new Exception('A devolução deste empréstimo já foi registrada.');
    }

    $emprestimo['data_devolucao'] = date('Y-m-d');
    return salvarRegistro('emprestimos', $emprestimo);
}
