<?php

require_once 'includes/auth.php';
require_once 'includes/funcoes.php';
require_once 'models/emprestimo.php'; 

function validarEmprestimo(array $form): array
{
    $erros = [];

    if ($form['livro_id'] === '') {
        $erros['livro_id'] = 'Selecione um livro.';
    } elseif (filter_var($form['livro_id'], FILTER_VALIDATE_INT) === false || buscarLivro((int) $form['livro_id']) === null) {
        $erros['livro_id'] = 'Livro inválido.';
    }

    if ($form['leitor_id'] === '') {
        $erros['leitor_id'] = 'Selecione um leitor.';
    } elseif (filter_var($form['leitor_id'], FILTER_VALIDATE_INT) === false || buscarLeitor((int) $form['leitor_id']) === null) {
        $erros['leitor_id'] = 'Leitor inválido.';
    }

    $data = converterData($form['data_prevista']);
    $hoje = new DateTime('today');
    $limite = new DateTime('today +' . PRAZO_MAXIMO_DIAS . ' days');

    if ($form['data_prevista'] === '') {
        $erros['data_prevista'] = 'Informe a data prevista de devolução.';
    } elseif ($data === null) {
        $erros['data_prevista'] = 'Data inválida.Use o formato DD/MM/AAAA (ex: 05/10/2026)';
    } elseif ($data > $limite) {
        $erros['data_prevista'] = 'O prazo máximo é de ' .PRAZO_MAXIMO_DIAS . ' dias (até ' . $limite->format('d/m/Y') .').';
    }

    return $erros;
}

$titulo = 'Gerenciamento de Empréstimos';
$menuAtivo = 'emprestimos';
$acao = $_GET['acao'] ?? '';
$erros = [];

$filtros = ['ativos' => 'Ativos', 'atrasados' => 'Atrasados', 'devolvidos' => 'Devolvidos', 'todos' => 'Todos'];
$filtro = $_GET['filtro'] ?? 'ativos';
if (!is_string($filtro) || !array_key_exists($filtro, $filtros)) {
    $filtro = 'ativos';
}

$form = ['livro_id' => '', 'leitor_id' => '', 'data_prevista' => date('d/m/Y', strtotime('+14 days'))];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'salvar') {
    $form = [
        'livro_id'      => campoPost('livro_id'),
        'leitor_id'     => campoPost('leitor_id'),
        'data_prevista' => campoPost('data_prevista'),
    ];

    $erros = validarEmprestimo($form);

    if (empty($erros)) {
        try {
            registrarEmprestimo((int) $form['livro_id'], (int) $form['leitor_id'], converterData($form['data_prevista']));
            definirMensagem('sucesso', 'Empréstimo registrado com sucesso!');
            redirecionar('emprestimos.php');
        } catch (Exception $e) {
            
            $erros['geral'] = $e->getMessage();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao === 'devolver') {
    try {
        $emprestimo = registrarDevolucao((int) campoPost('id'));
        $atraso = diasDeAtraso($emprestimo);

        if ($atraso > 0) {
            definirMensagem('sucesso', "Devolução registrada, com $atraso dia(s) de atraso.");
        } else {
            definirMensagem('sucesso', 'Devolução registrada. O livro está disponível novamente.');
        }
    } catch (Exception $e) {
        definirMensagem('erro', $e->getMessage());
    }
    redirecionar("emprestimos.php?filtro=$filtro");
}

$livrosDisponiveis = listarLivrosDisponiveis();
$leitores = listarLeitores();
$emprestimos = listarEmprestimos($filtro);
$mensagem = pegarMensagem();

require 'views/cabecalho.view.php';
require 'views/emprestimos_cadastro.view.php';
require 'views/rodape.view.php';
