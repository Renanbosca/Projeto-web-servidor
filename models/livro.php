<?php
require_once __DIR__ . '/armazenamento.php';

function idsLivrosEmprestados(): array
{
    $ids = [];
    foreach (lerRegistros('emprestimos') as $emprestimo) {
        if ($emprestimo['data_devolucao'] === null) {
            $ids[] = $emprestimo['livro_id'];
        }
    }
    return $ids;
}

function listarLivros(): array
{
    $livros = lerRegistros('livros');
    $emprestados = idsLivrosEmprestados();

    foreach ($livros as $indice => $livro) {
        $livros[$indice]['disponivel'] = !in_array($livro['id'], $emprestados, true);
    }

    usort($livros, function($a, $b) {
    if ($a['titulo'] == $b['titulo']) {
        return 0;
    }
    return ($a['titulo'] < $b['titulo']) ? -1 : 1;
    });

    return $livros;
}


function listarLivrosDisponiveis(): array
{
    $disponiveis = [];
    foreach (listarLivros() as $livro) {
        if ($livro['disponivel']) {
            $disponiveis[] = $livro;
        }
    }
    return $disponiveis;
}

function buscarLivro(int $id): ?array
{
    return buscarPorId(lerRegistros('livros'), $id);
}

function livroEstaEmprestado(int $id): bool
{
    return in_array($id, idsLivrosEmprestados(), true);
}

function salvarLivro(array $dados): array
{
    return salvarRegistro('livros', [
        'id'     => (int) $dados['id'],
        'titulo' => $dados['titulo'],
        'autor'  => $dados['autor'],
        'ano'    => (int) $dados['ano'],
    ]);
}

function excluirLivro(int $id): void
{
    if (livroEstaEmprestado($id)) {
        throw new Exception('Este livro está emprestado e não pode ser excluído. Registre a devolução primeiro.');
    }
    excluirRegistro('livros', $id);
}
