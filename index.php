<?php
// index.php (Ponto de entrada principal - Tela de Login)

// A sua dupla irá adicionar session_start() e a lógica de validação do POST aqui posteriormente


// Redireciona provisoriamente para a tela de livros após "entrar"
header("Location: livros.php");
exit;

// Carrega diretamente a view do formulário de login
require 'views/login.view.php';