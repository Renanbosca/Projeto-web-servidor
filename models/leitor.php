<?php
/*
 * leitor.php (Model de Leitores)
 *
 * Leitores são as pessoas que pegam livros emprestados.
 * Campos: id, nome, email, telefone (somente dígitos, ex.: 42999998888).
 */
require_once __DIR__ . '/armazenamento.php';

/**
 * Lista todos os leitores em ordem alfabética.
 * Cada leitor volta com o campo extra 'emprestimos_ativos' (quantos livros está com ele agora).
 */
function listarLeitores(): array
{
    $leitores = lerRegistros('leitores');

    foreach ($leitores as $indice => $leitor) {
        $leitores[$indice]['emprestimos_ativos'] = contarEmprestimosAtivos($leitor['id']);
    }

    usort($leitores, fn($a, $b) => strcasecmp($a['nome'], $b['nome']));

    return $leitores;
}

function buscarLeitor(int $id): ?array
{
    return buscarPorId(lerRegistros('leitores'), $id);
}

function buscarLeitorPorEmail(string $email): ?array
{
    foreach (lerRegistros('leitores') as $leitor) {
        if ($leitor['email'] === strtolower($email)) {
            return $leitor;
        }
    }
    return null;
}

/**
 * Quantos empréstimos o leitor tem sem devolução.
 */
function contarEmprestimosAtivos(int $leitorId): int
{
    $total = 0;
    foreach (lerRegistros('emprestimos') as $emprestimo) {
        if ($emprestimo['leitor_id'] === $leitorId && $emprestimo['data_devolucao'] === null) {
            $total++;
        }
    }
    return $total;
}

/**
 * Verifica se o leitor tem algum empréstimo vencido e ainda não devolvido.
 */
function leitorTemAtraso(int $leitorId): bool
{
    $hoje = date('Y-m-d');

    foreach (lerRegistros('emprestimos') as $emprestimo) {
        // Datas no formato AAAA-MM-DD podem ser comparadas como texto
        if ($emprestimo['leitor_id'] === $leitorId
            && $emprestimo['data_devolucao'] === null
            && $emprestimo['data_prevista'] < $hoje) {
            return true;
        }
    }
    return false;
}

/**
 * Cadastra (id vazio) ou atualiza (id preenchido) um leitor.
 * Os dados já devem ter sido validados pelo controller.
 */
function salvarLeitor(array $dados): array
{
    return salvarRegistro('leitores', [
        'id'       => (int) $dados['id'],
        'nome'     => $dados['nome'],
        'email'    => strtolower($dados['email']),
        // Guarda só os números; a máscara (xx) xxxxx-xxxx é aplicada na exibição
        'telefone' => preg_replace('/\D/', '', $dados['telefone']),
    ]);
}

/**
 * Exclui um leitor.
 * Regra de negócio: leitor com livro emprestado não pode ser excluído.
 */
function excluirLeitor(int $id): void
{
    if (contarEmprestimosAtivos($id) > 0) {
        throw new Exception('Este leitor está com livros emprestados e não pode ser excluído.');
    }
    excluirRegistro('leitores', $id);
}
