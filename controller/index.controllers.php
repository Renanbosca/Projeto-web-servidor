<?php

session_start();
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../models/usuario.models.php';

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    redirecionar('painel.controllers.php');
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
            $usuario = Usuario::autenticarUsuario($email, $senha); 

            if ($usuario !== null) {
                session_regenerate_id(true);
                $_SESSION['logado'] = true;
                $_SESSION['usuario_id'] = $usuario->getId(); 
                $_SESSION['usuario_nome'] = $usuario->getNome(); 
                redirecionar('painel.controllers.php');
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

require __DIR__ . '/../views/login.view.php';
