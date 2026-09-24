<?php

require_once __DIR__ . '/armazenamento.php';
require_once __DIR__ . '/livro.php';
require_once __DIR__ . '/leitor.php';

const LIMITE_EMPRESTIMOS_POR_LEITOR = 3;
const PRAZO_MAXIMO_DIAS = 30;

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

function diasDeAtraso(array $emprestimo): int
{
    $prevista = new DateTime($emprestimo['data_prevista']);
    $fim = new DateTime($emprestimo['data_devolucao'] ?? 'today');

    if ($fim <= $prevista) {
        return 0;
    }
    return $prevista->diff($fim)->days;
}

function listarEmprestimos(string $filtro = 'todos'): array
{
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

    usort($lista, function($a, $b) {
    if ($a['id'] == $b['id']) {
        return 0;
    }
    return ($a['id'] > $b['id']) ? -1 : 1;
});

    return $lista;
}


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

