<?php
/*
 * index.php (Controller do Login)
 * Ponto de entrada do sistema: exibe o formulário de login e confere as credenciais.
 */
session_start();
require_once 'includes/funcoes.php';
require_once 'models/usuario.php';

// Quem já está logado não precisa ver o login de novo
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    redirecionar('painel.php');
}

$titulo = 'Login - Biblioteca';
$email = '';
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = campoPost('email');
    $senha = campoPost('senha', false);

    // Validação dos campos no servidor
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
                // Gera um novo id de sessão após o login (evita reaproveitar um id antigo)
                session_regenerate_id(true);

                $_SESSION['logado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                redirecionar('painel.php');
            }

            // Mensagem genérica de propósito: não revela se o e-mail está cadastrado
            $erros['geral'] = 'E-mail ou senha incorretos.';
        } catch (Exception $e) {
            $erros['geral'] = $e->getMessage();
        }
    }
}

// Aviso vindo de outra página (conta criada, sessão expirada) ou do logout
$mensagem = pegarMensagem();
if (isset($_GET['saiu'])) {
    $mensagem = ['tipo' => 'sucesso', 'texto' => 'Você saiu do sistema.'];
}

require 'views/login.view.php';
