<?php
/*
 * painel.php (Controller do Painel)
 * Página inicial depois do login: resumo da biblioteca e empréstimos em atraso.
 */
require_once 'includes/auth.php';
require_once 'includes/funcoes.php';
require_once 'models/emprestimo.php';   // já inclui os models de livro e leitor

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
