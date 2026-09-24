<?php

session_start();
require_once 'includes/funcoes.php';
require_once 'models/usuario.php';

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    redirecionar('painel.php');
}

$titulo = 'Login - Biblioteca';
$email = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = campoPost('email');
    $senha = campoPost('senha', false);

    if ($email === '') {
        $erros['email'] = 'Informe o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }

    if ($senha === '') {
        $erros['senha'] = 'Informe a senha.';
    }

    if (empty($erros)) {
        try {
            $usuario = autenticarUsuario($email, $senha);

            if ($usuario !== null) {
                session_regenerate_id(true);

                $_SESSION['logado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                redirecionar('painel.php');
            }

            $erros['geral'] = 'E-mail ou senha incorretos.';
        } catch (Exception $e) {
            $erros['geral'] = $e->getMessage();
        }
    }
}

$mensagem = pegarMensagem();
if (isset($_GET['saiu'])) {
    $mensagem = ['tipo' => 'sucesso', 'texto' => 'Você saiu do sistema.'];
}

require 'views/login.view.php';
