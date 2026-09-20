<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Login - Biblioteca' ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body-login">
    <div class="login-box">
        <h2>Acesso ao Sistema</h2>
        
        <?php if(isset($mensagemErro)): ?>
            <div class="erro"><?= $mensagemErro ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="usuario" required>
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit">Entrar</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Ainda não tem conta? <a href="cadastro_usuario.php" style="color: #0055a4; text-decoration: none;">Cadastre-se</a>
        </p>
    </div>
</body>
</html>