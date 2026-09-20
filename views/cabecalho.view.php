<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Sistema de Biblioteca' ?></title>
    <!-- Referência ao arquivo CSS externo -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="menu">

        <a href="livros.php">Livros</a>
        <a href="leitores.php">Leitores</a>
        <a href="emprestimos.php">Empréstimos</a>
        <a href="index.php" style="margin-left: auto;">Sair</a>
    
    </nav>
    <div class="container">
        <?php if(isset($mensagemErro)): ?>
            <div class="erro"><?= $mensagemErro ?></div>
        <?php endif; ?>