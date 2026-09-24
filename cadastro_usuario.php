<?php

session_start();
require_once 'includes/funcoes.php';
require_once 'models/usuario.php';

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    redirecionar('painel.php');
}

$titulo = 'Criar Conta - Biblioteca';
$form = ['nome' => '', 'email' => ''];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['nome'] = campoPost('nome');
    $form['email'] = campoPost('email');
    $senha = campoPost('senha', false);
    $confirmacao = campoPost('confirmar_senha', false);

    if ($form['nome'] === '') {
        $erros['nome'] = 'Informe seu nome.';
    } elseif (tamanhoTexto($form['nome']) < 3) {
        $erros['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (tamanhoTexto($form['nome']) > 100) {
        $erros['nome'] = 'O nome pode ter no máximo 100 caracteres.';
    }

    if ($form['email'] === '') {
        $erros['email'] = 'Informe o e-mail.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido (ex.: nome@dominio.com).';
    } elseif (buscarUsuarioPorEmail($form['email']) !== null) {
        $erros['email'] = 'Já existe uma conta com este e-mail.';
    }

    if ($senha === '') {
        $erros['senha'] = 'Informe uma senha.';
    } elseif (strlen($senha) < 6) {
        $erros['senha'] = 'A senha deve ter pelo menos 6 caracteres.';
    } 

    if ($confirmacao !== $senha) {
        $erros['confirmar_senha'] = 'As senhas não conferem.';
    }

    if (empty($erros)) {
        try {
            cadastrarUsuario($form['nome'], $form['email'], $senha);
            definirMensagem('sucesso', 'Conta criada com sucesso! Faça login para continuar.');
            redirecionar('index.php');
        } catch (Exception $e) {
            $erros['geral'] = $e->getMessage();
        }
    }
}

require 'views/usuario_cadastro.view.php';
