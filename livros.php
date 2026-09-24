<?php
/*
 * livros.php (Controller de Livros)
 *
 * GET  livros.php                   → lista de livros + formulário de cadastro
 * GET  livros.php?acao=editar&id=N  → lista + formulário preenchido para edição
 * POST livros.php?acao=salvar       → valida e cadastra/atualiza o livro
 * POST livros.php?acao=excluir      → exclui o livro
 */
require_once 'includes/auth.php';
require_once 'includes/funcoes.php';
require_once 'models/livro.php';

/**
 * Valida os dados do formulário de livro.
 * Retorna os erros no formato ['campo' => 'mensagem']; array vazio = tudo certo.
 */
function validarLivro(array $form): array
{
    $erros = [];

    if ($form['titulo'] === '') {
        $erros['titulo'] = 'Informe o título do livro.';
    } elseif (tamanhoTexto($form['titulo']) > 150) {
        $erros['titulo'] = 'O título pode ter no máximo 150 caracteres.';
    }

    if ($form['autor'] === '') {
        $erros['autor'] = 'Informe o autor.';
    } elseif (tamanhoTexto($form['autor']) < 3) {
        $erros['autor'] = 'O nome do autor deve ter pelo menos 3 caracteres.';
    } elseif (tamanhoTexto($form['autor']) > 100) {
        $erros['autor'] = 'O nome do autor pode ter no máximo 100 caracteres.';
    }

    // O ano precisa ser um número inteiro entre 1450 (invenção da prensa) e o ano atual
    $anoAtual = (int) date('Y');
    $opcoesAno = ['options' => ['min_range' => 1450, 'max_range' => $anoAtual]];

    if ($form['ano'] === '') {
        $erros['ano'] = 'Informe o ano de publicação.';
    } elseif (filter_var($form['ano'], FILTER_VALIDATE_INT, $opcoesAno) === false) {
        $erros['ano'] = "O ano deve ser um número entre 1450 e $anoAtual.";
    }

    return $erros;
}

$titulo = 'Gerenciamento de Livros';
$menuAtivo = 'livros';
$acao = $_GET['acao'] ?? '';
$erros = [];

// Valores mostrados no formulário (vazios = cadastro de um livro novo)
$form = ['id' => '', 'titulo' => '', 'autor' => '', 'ano' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'salvar') {
    $form = [
        'id'     => campoPost('id'),
        'titulo' => campoPost('titulo'),
        'autor'  => campoPost('autor'),
        'ano'    => campoPost('ano'),
    ];

    $erros = validarLivro($form);

    if (empty($erros)) {
        try {
            salvarLivro($form);
            definirMensagem('sucesso', $form['id'] === '' ? 'Livro cadastrado com sucesso!' : 'Livro atualizado com sucesso!');
            redirecionar('livros.php');
        } catch (Exception $e) {
            $erros['geral'] = $e->getMessage();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'excluir') {
    try {
        excluirLivro((int) campoPost('id'));
        definirMensagem('sucesso', 'Livro excluído com sucesso!');
    } catch (Exception $e) {
        definirMensagem('erro', $e->getMessage());
    }
    redirecionar('livros.php');
} elseif ($acao === 'editar') {
    $livro = buscarLivro((int) ($_GET['id'] ?? 0));

    if ($livro === null) {
        definirMensagem('erro', 'Livro não encontrado.');
        redirecionar('livros.php');
    }
    $form = $livro;
}

$editando = !empty($form['id']);
$livros = listarLivros();
$mensagem = pegarMensagem();

require 'views/cabecalho.view.php';
require 'views/livros_cadastro.view.php';
require 'views/rodape.view.php';
