<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body-login">
    <div class="login-box login-box-largo">
        <h2>Criar Nova Conta</h2>

        <?php if (isset($erros['geral'])): ?>
            <div class="erro"><?= e($erros['geral']) ?></div>
        <?php endif; ?>

        <form action="cadastro_usuario.php" method="POST" novalidate>
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?= e($form['nome']) ?>" class="<?= classeErro($erros, 'nome') ?>">
                <?= mensagemErro($erros, 'nome') ?>
            </div>

            <div class="form-group">
                <label for="email">E-mail (usado no login):</label>
                <input type="email" id="email" name="email" value="<?= e($form['email']) ?>" class="<?= classeErro($erros, 'email') ?>">
                <?= mensagemErro($erros, 'email') ?>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="<?= classeErro($erros, 'senha') ?>">
                <span class="dica">Mínimo de 6 caracteres.</span>
                <?= mensagemErro($erros, 'senha') ?>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" class="<?= classeErro($erros, 'confirmar_senha') ?>">
                <?= mensagemErro($erros, 'confirmar_senha') ?>
            </div>

            <button type="submit">Cadastrar</button>
        </form>

        <p class="link-rodape">
            Já tem uma conta? <a href="index.php">Fazer Login</a>
        </p>

