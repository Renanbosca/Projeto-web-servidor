

<h2>Cadastro de Livro</h2>
<form action="livros.php?acao=salvar" method="POST">
    <div class="form-group">
        <label>Título do Livro:</label>
        <input type="text" name="titulo" required>
    </div>
    <div class="form-group">
        <label>Autor:</label>
        <input type="text" name="autor" required>
    </div>
    <div class="form-group">
        <label>Ano de Publicação:</label>
        <input type="number" name="ano" required>
    </div>
    <button type="submit">Salvar Livro</button>
</form>

<hr>
<h2>Livros Cadastrados</h2>
<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Ano</th>
        </tr>
    </thead>
    <tbody>
        <!-- O Controller irá preencher a variável $livros e enviar para esta View -->
        <?php if(!empty($livros)): ?>
            <?php foreach($livros as $livro): ?>
                <tr>
                    <td><?= $livro['titulo'] ?></td>
                    <td><?= $livro['autor'] ?></td>
                    <td><?= $livro['ano'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Nenhum livro cadastrado.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require 'rodape.view.php'; ?>