<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/funcoes.php';


$titulo = 'Painel - Biblioteca';
$menuAtivo = 'painel';

$todosLivros = Livro::listarTodos();
$totalLivros = count($todosLivros);

$totalDisponiveis = 0;
foreach ($todosLivros as $livro) {
    if ($livro->estaDisponivel()) {
        $totalDisponiveis++;
    }
}


$totalLeitores = count(Leitor::listarTodos());
$totalAtivos = count(Emprestimo::listarEmprestimos('ativos'));
$atrasados = Emprestimo::listarEmprestimos('atrasados');

$mensagem = pegarMensagem();

require __DIR__ . '/../views/cabecalho.view.php';
require __DIR__ . '/../views/painel.view.php';
require __DIR__ . '/../views/rodape.view.php';