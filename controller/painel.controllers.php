<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../models/emprestimo.models.php';


$titulo = 'Painel - Biblioteca';
$menuAtivo = 'painel';

$totalLivros = count(listarLivros());
$totalDisponiveis = count(listarLivrosDisponiveis());
$totalLeitores = count(listarLeitores());
$totalAtivos = count(listarEmprestimos('ativos'));
$atrasados = listarEmprestimos('atrasados');

$mensagem = pegarMensagem();

require __DIR__ . '/../views/cabecalho.view.php';
require __DIR__ . '/../views/painel.view.php';
require __DIR__ . '/../views/rodape.view.php';
