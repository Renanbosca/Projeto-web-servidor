<?php
/*
 * auth.php
 * Incluído no topo de todas as páginas protegidas (painel, livros, leitores, empréstimos).
 * Inicia a sessão e, se o usuário não estiver logado, manda de volta para o login.
 */
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => 'Faça login para acessar o sistema.'];
    header('Location: index.php');
    exit;
}

// Nome do usuário logado, exibido no menu (views/cabecalho.view.php)
$usuarioLogado = $_SESSION['usuario_nome'];
