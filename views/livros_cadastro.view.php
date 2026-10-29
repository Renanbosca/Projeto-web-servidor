<h2><?= $editando ? 'Editar Livro' : 'Cadastro de Livro' ?></h2>

<?php if (!empty($erros)): ?>
    <div class="erro"><?= e($erros['geral'] ?? 'Não foi possível salvar. Corrija os campos destacados.') ?></div>
<?php endif; ?>


<form action="livros.controllers.php?acao=salvar" method="POST" novalidate>
    <input type="hidden" name="id" value="<?= e($form['id']) ?>">

    <div class="form-group">
        <label for="titulo">Título do Livro:</label>
        <input type="text" id="titulo" name="titulo" value="<?= e($form['titulo']) ?>" class="<?= classeErro($erros, 'titulo') ?>">
        <?= mensagemErro($erros, 'titulo') ?>
    </div>
    <div class="form-group">
        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" value="<?= e($form['autor']) ?>" class="<?= classeErro($erros, 'autor') ?>">
        <?= mensagemErro($erros, 'autor') ?>
    </div>
    <div class="form-group">
        <label for="ano">Ano de Publicação:</label>
        <input type="number" id="ano" name="ano" value="<?= e($form['ano']) ?>" class="<?= classeErro($erros, 'ano') ?>">
        <?= mensagemErro($erros, 'ano') ?>
    </div>
    <button type="submit"><?= $editando ? 'Salvar Alterações' : 'Salvar Livro' ?></button>
    <?php if ($editando): ?>
        <a href="livros.controllers.php" class="botao-secundario">Cancelar</a>
    <?php endif; ?>
</form>

<hr>
<h2>Livros Cadastrados</h2>
<div class="tabela-container">
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Ano</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
<tbody>
        <?php if (!empty($livros)): ?>
            <?php foreach ($livros as $livro): ?>
                <tr>
                    <td><?= e($livro->getTitulo()) ?></td>
                    <td><?= e($livro->getAutor()) ?></td>
                    <td><?= e($livro->getAno()) ?></td>
                    
                    <td>
                        <?php if ($livro->estaDisponivel()): ?>
                            <span class="etiqueta disponivel">Disponível</span>
                        <?php else: ?>
                            <span class="etiqueta emprestado">Emprestado</span>
                        <?php endif; ?>
                    </td>
                    
                    <td class="acoes">
                        <a href="livros.controllers.php?acao=editar&id=<?= $livro->getId() ?>" class="botao-pequeno">Editar</a>
                        <form action="livros.controllers.php?acao=excluir" method="POST" onsubmit="return confirm('Deseja excluir este livro?');">
                            <input type="hidden" name="id" value="<?= $livro->getId() ?>">
                            <button type="submit" class="botao-pequeno perigo">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum livro cadastrado.</td></tr>
        <?php endif; ?>
</tbody>
    </table>
</div>
