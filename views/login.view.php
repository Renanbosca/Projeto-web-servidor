<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body-login">
    <div class="login-box">
        <h2>Acesso ao Sistema</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="<?= $mensagem['tipo'] === 'sucesso' ? 'sucesso' : 'erro' ?>"><?= e($mensagem['texto']) ?></div>
        <?php endif; ?>

        <?php if (isset($erros['geral'])): ?>
            <div class="erro"><?= e($erros['geral']) ?></div>
        <?php endif; ?>

        <form action="index.php" method="POST" novalidate>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= e($email) ?>" class="<?= classeErro($erros, 'email') ?>">
                <?= mensagemErro($erros, 'email') ?>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="<?= classeErro($erros, 'senha') ?>">
                <?= mensagemErro($erros, 'senha') ?>
            </div>

            <button type="submit">Entrar</button>
        </form>

        <p class="link-rodape">
            Ainda não tem conta? <a href="cadastro_usuario.php">Cadastre-se</a>
        </p>

