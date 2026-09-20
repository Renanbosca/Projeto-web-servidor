<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Biblioteca</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body-login">
    <div class="login-box" style="width: 350px;">
        <h2>Criar Nova Conta</h2>
        
        <form action="cadastro_usuario.php" method="POST">
            <div class="form-group">
                <label>Nome Completo:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="form-group">
                <label>E-mail / Usuário:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit">Cadastrar</button>
        </form>

        <!-- Link para voltar ao login -->
        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Já tem uma conta? <a href="index.php" style="color: #0055a4; text-decoration: none;">Fazer Login</a>
        </p>
    </div>
</body>
</html>