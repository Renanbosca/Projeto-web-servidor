<?php

require_once 'includes/auth.php';
require_once 'includes/funcoes.php';
require_once 'models/leitor.php';

function validarLeitor(array $form): array
{
    $erros = [];
    if ($form['nome'] === '') {
        $erros['nome'] = 'Informe o nome do leitor.';
    } elseif (tamanhoTexto($form['nome']) < 3) {
        $erros['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (tamanhoTexto($form['nome']) > 100) {
        $erros['nome'] = 'O nome pode ter no máximo 100 caracteres.';
    } elseif (!preg_match("/^[\p{L} .'-]+$/u", $form['nome'])) {
        $erros['nome'] = 'O nome deve conter apenas letras e espaços.';
    }

    if ($form['email'] === '') {
        $erros['email'] = 'Informe o e-mail.';
    } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido (ex.: nome@dominio.com).';
    } else {
        $outro = buscarLeitorPorEmail($form['email']);
        if ($outro !== null && $outro['id'] !== (int) $form['id']) {
            $erros['email'] = 'Já existe um leitor cadastrado com este e-mail.';
        }
    }

    $digitos = str_replace(['(', ')', '-', ' '], '', $form['telefone']);;
    if ($form['telefone'] === '') {
        $erros['telefone'] = 'Informe o telefone.';
    } elseif (strlen($digitos) < 10 || strlen($digitos) > 11) {
        $erros['telefone'] = 'O telefone deve ter DDD + número (10 ou 11 dígitos).';
    }

    return $erros;
}

$titulo = 'Gerenciamento de Leitores';
$menuAtivo = 'leitores';
$acao = $_GET['acao'] ?? '';
$erros = [];

$form = ['id' => '', 'nome' => '', 'email' => '', 'telefone' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'salvar') {
    $form = [
        'id'       => campoPost('id'),
        'nome'     => campoPost('nome'),
        'email'    => campoPost('email'),
        'telefone' => campoPost('telefone'),
    ];

    $erros = validarLeitor($form);
    if (empty($erros)) {
        try {
            salvarLeitor($form);
            definirMensagem('sucesso', $form['id'] === '' ? 'Leitor cadastrado com sucesso!' : 'Leitor atualizado com sucesso!');
            redirecionar('leitores.php');
        } catch (Exception $e) {
            $erros['geral'] = $e->getMessage();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'excluir') {
    try {
        excluirLeitor((int) campoPost('id'));
        definirMensagem('sucesso', 'Leitor excluído com sucesso!');
    } catch (Exception $e) {
        definirMensagem('erro', $e->getMessage());
    }
    redirecionar('leitores.php');
} elseif ($acao === 'editar') {
    $leitor = buscarLeitor((int) ($_GET['id'] ?? 0));

    if ($leitor === null) {
        definirMensagem('erro', 'Leitor não encontrado.');
        redirecionar('leitores.php');
    }
    $form = $leitor;
    $form['telefone'] = formatarTelefone($leitor['telefone']);
}

$editando = !empty($form['id']);
$leitores = listarLeitores();
$mensagem = pegarMensagem();

require 'views/cabecalho.view.php';
require 'views/leitores_cadastro.view.php';
require 'views/rodape.view.php';
