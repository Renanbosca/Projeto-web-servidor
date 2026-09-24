<h2><?= $editando ? 'Editar Leitor' : 'Cadastro de Leitor' ?></h2>

<?php if (!empty($erros)): ?>
    <div class="erro"><?= e($erros['geral'] ?? 'Não foi possível salvar. Corrija os campos destacados.') ?></div>
<?php endif; ?>

<form action="leitores.php?acao=salvar" method="POST" novalidate>
    <input type="hidden" name="id" value="<?= e($form['id']) ?>">

    <div class="form-group">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" value="<?= e($form['nome']) ?>" class="<?= classeErro($erros, 'nome') ?>">
        <?= mensagemErro($erros, 'nome') ?>
    </div>
    <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" value="<?= e($form['email']) ?>" class="<?= classeErro($erros, 'email') ?>">
        <?= mensagemErro($erros, 'email') ?>
    </div>
    <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?= e($form['telefone']) ?>" placeholder="Ex: (42) 99999-8888" class="<?= classeErro($erros, 'telefone') ?>">
        <?= mensagemErro($erros, 'telefone') ?>
    </div>

    <button type="submit"><?= $editando ? 'Salvar Alterações' : 'Salvar Leitor' ?></button>
    <?php if ($editando): ?>
        <a href="leitores.php" class="botao-secundario">Cancelar</a>
    <?php endif; ?>
</form>

<hr>
<h2>Leitores Cadastrados</h2>
<div class="tabela-container">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Livros com ele</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($leitores)): ?>
                <?php foreach ($leitores as $leitor): ?>
                    <tr>
                        <td><?= e($leitor['nome']) ?></td>
                        <td><?= e($leitor['email']) ?></td>
                        <td><?= e(formatarTelefone($leitor['telefone'])) ?></td>
                        <td><?= $leitor['emprestimos_ativos'] ?></td>
                        <td class="acoes">
                            <a href="leitores.php?acao=editar&id=<?= $leitor['id'] ?>" class="botao-pequeno">Editar</a>
                            <form action="leitores.php?acao=excluir" method="POST" onsubmit="return confirm('Deseja excluir este leitor?');">
                                <input type="hidden" name="id" value="<?= $leitor['id'] ?>">
                                <button type="submit" class="botao-pequeno perigo">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum leitor cadastrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
