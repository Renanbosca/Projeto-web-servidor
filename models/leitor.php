<?php
require_once __DIR__ . '/armazenamento.php';

function listarLeitores(): array
{
    $leitores = lerRegistros('leitores');
    foreach ($leitores as $indice => $leitor) {
        $leitores[$indice]['emprestimos_ativos'] = contarEmprestimosAtivos($leitor['id']);
    }

    usort($leitores, function($a, $b) {
    if ($a['nome'] == $b['nome']) {
        return 0;
    }
    return ($a['nome'] < $b['nome']) ? -1 : 1;
    });
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

function leitorTemAtraso(int $leitorId): bool
{
    $hoje = date('Y-m-d');
    foreach (lerRegistros('emprestimos') as $emprestimo) {
        if ($emprestimo['leitor_id'] === $leitorId
            && $emprestimo['data_devolucao'] === null
            && $emprestimo['data_prevista'] < $hoje) {
            return true;
        }
    }
    return false;
}

function salvarLeitor(array $dados): array
{
    return salvarRegistro('leitores', [
        'id'       => (int) $dados['id'],
        'nome'     => $dados['nome'],
        'email'    => strtolower($dados['email']),
        'telefone' => str_replace(['(', ')', '-', ' '], '', $dados['telefone']),
    ]);
}

function excluirLeitor(int $id): void
{
    if (contarEmprestimosAtivos($id) > 0) {
        throw new Exception('Este leitor está com livros emprestados e não pode ser excluído.');
    }
    excluirRegistro('leitores', $id);
}
