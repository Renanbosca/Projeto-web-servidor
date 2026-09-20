<?php
// emprestimos.php (Controlador de Empréstimos)
$titulo = "Gerenciamento de Empréstimos";

$emprestimos = [];
$livrosDisponiveis = [];
$leitoresDisponiveis = [];

require 'views/cabecalho.view.php';
require 'views/emprestimos_cadastro.view.php';
require 'views/rodape.view.php';