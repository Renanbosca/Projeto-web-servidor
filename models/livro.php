<?php
/*
 * livro.php (Model de Livros)
 *
 * Campos: id, titulo, autor, ano.
 * Cada livro representa um exemplar: ou está disponível, ou está emprestado.
 */
require_once __DIR__ . '/armazenamento.php';

/**
 * Ids dos livros que estão emprestados agora (empréstimos ainda sem data de devolução).
 */
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

/**
 * Lista todos os livros em ordem alfabética de título.
 * Cada livro volta com o campo extra 'disponivel' (true/false).
 */
function listarLivros(): array
{
    $livros = lerRegistros('livros');
    $emprestados = idsLivrosEmprestados();

    foreach ($livros as $indice => $livro) {
        $livros[$indice]['disponivel'] = !in_array($livro['id'], $emprestados, true);
    }

    // Ordena pelo título (strcasecmp compara textos ignorando maiúsculas/minúsculas)
    usort($livros, fn($a, $b) => strcasecmp($a['titulo'], $b['titulo']));

    return $livros;
}

/**
 * Lista apenas os livros que podem ser emprestados agora.
 */
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

/**
 * Cadastra (id vazio) ou atualiza (id preenchido) um livro.
 * Os dados já devem ter sido validados pelo controller.
 */
function salvarLivro(array $dados): array
{
    return salvarRegistro('livros', [
        'id'     => (int) $dados['id'],
        'titulo' => $dados['titulo'],
        'autor'  => $dados['autor'],
        'ano'    => (int) $dados['ano'],
    ]);
}

/**
 * Exclui um livro.
 * Regra de negócio: um livro emprestado não pode ser excluído.
 */
function excluirLivro(int $id): void
{
    if (livroEstaEmprestado($id)) {
        throw new Exception('Este livro está emprestado e não pode ser excluído. Registre a devolução primeiro.');
    }
    excluirRegistro('livros', $id);
}
