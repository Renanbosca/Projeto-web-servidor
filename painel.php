<?php

require_once 'includes/auth.php';
require_once 'includes/funcoes.php';
require_once 'models/emprestimo.php'; 

$titulo = 'Painel - Biblioteca';
$menuAtivo = 'painel';

$totalLivros = count(listarLivros());
$totalDisponiveis = count(listarLivrosDisponiveis());
$totalLeitores = count(listarLeitores());
$totalAtivos = count(listarEmprestimos('ativos'));
$atrasados = listarEmprestimos('atrasados');

$mensagem = pegarMensagem();

require 'views/cabecalho.view.php';
require 'views/painel.view.php';
require 'views/rodape.view.php';
