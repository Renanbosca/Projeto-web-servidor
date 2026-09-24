<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => 'Faça login para acessar o sistema.'];
    header('Location: index.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario_nome'];
