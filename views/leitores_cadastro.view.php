

<h2>Cadastro de Leitor</h2>
<form action="leitores.php?acao=salvar" method="POST">
    <div class="form-group">
        <label>Nome Completo:</label>
        <input type="text" name="nome" required>
    </div>
    <div class="form-group">
        <label>E-mail:</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Telefone:</label>
        <input type="text" name="telefone" placeholder="Ex: (xx) xxxxx-xxxx" maxlength="10" required>
    </div>
    <button type="submit">Salvar Leitor</button>
</form>

<hr>
<h2>Leitores Cadastrados</h2>
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($leitores)): ?>
            <?php foreach($leitores as $leitor): ?>
                <tr>
                    <td><?= $leitor['nome'] ?></td>
                    <td><?= $leitor['email'] ?></td>
                    <td><?= $leitor['telefone'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Nenhum leitor cadastrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require 'rodape.view.php'; ?>