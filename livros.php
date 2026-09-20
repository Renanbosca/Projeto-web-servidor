<?php
// livros.php (Controlador de Livros)
$titulo = "Gerenciamento de Livros";

// Variável vazia para simular a tabela por enquanto (sua dupla vai puxar do banco depois)
$livros = []; 

require 'views/cabecalho.view.php';
require 'views/livros_cadastro.view.php';
require 'views/rodape.view.php';