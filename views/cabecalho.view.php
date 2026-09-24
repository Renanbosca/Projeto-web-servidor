<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Sistema de Biblioteca') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="menu">
        <a href="painel.php" class="<?= $menuAtivo === 'painel' ? 'ativo' : '' ?>">Início</a>
        <a href="livros.php" class="<?= $menuAtivo === 'livros' ? 'ativo' : '' ?>">Livros</a>
        <a href="leitores.php" class="<?= $menuAtivo === 'leitores' ? 'ativo' : '' ?>">Leitores</a>
        <a href="emprestimos.php" class="<?= $menuAtivo === 'emprestimos' ? 'ativo' : '' ?>">Empréstimos</a>

        <span class="usuario-logado">Olá, <?= e($usuarioLogado) ?></span>
        <a href="logout.php">Sair</a>
    </nav>
    <div class="container">
        <?php if (!empty($mensagem)): ?>
            <div class="<?= $mensagem['tipo'] === 'sucesso' ? 'sucesso' : 'erro' ?>"><?= e($mensagem['texto']) ?></div>
        <?php endif; ?>
