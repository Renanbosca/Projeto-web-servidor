<h2>Registrar Novo Empréstimo</h2>

<?php if (!empty($erros)): ?>
    <div class="erro"><?= e($erros['geral'] ?? 'Não foi possível registrar. Corrija os campos destacados.') ?></div>
<?php endif; ?>

<form action="emprestimos.controllers.php?acao=salvar" method="POST" novalidate>
    <div class="form-group">
        <label for="livro_id">Livro:</label>
        <select name="livro_id" id="livro_id" required>
            <option value="">Selecione um livro...</option>
            <?php foreach ($livrosDisponiveis as $livro): ?>
                <option value="<?= $livro->getId() ?>"><?= e($livro->getTitulo()) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($livrosDisponiveis)): ?>
            <p class="nota">Nenhum livro disponível no momento. <a href="livros.controllers.php">Cadastrar livro</a></p>
        <?php endif; ?>
        <?= mensagemErro($erros, 'livro_id') ?>
    </div>
    
    <div class="form-group">
        <label for="leitor_id">Leitor:</label>
        <select name="leitor_id" id="leitor_id" required>
            <option value="">Selecione um leitor...</option>
            <?php foreach ($leitores as $leitor): ?>
                <option value="<?= $leitor->getId() ?>"><?= e($leitor->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($leitores)): ?>
            <p class="nota">Nenhum leitor cadastrado. <a href="leitores.controllers.php">Cadastrar leitor</a></p>
        <?php endif; ?>
        <?= mensagemErro($erros, 'leitor_id') ?>
    </div>
    
    <div class="form-group">
        <label for="data_prevista">Data Prevista de Devolução (DD/MM/AAAA):</label>
        <input type="text" id="data_prevista" name="data_prevista" value="<?= e($form['data_prevista']) ?>" placeholder="Ex: 05/10/2026" class="<?= classeErro($erros, 'data_prevista') ?>">
        <span class="dica">
            Prazo máximo de <?= Emprestimo::PRAZO_MAXIMO_DIAS ?> dias. Cada leitor pode ficar com até <?= Emprestimo::LIMITE_EMPRESTIMOS_POR_LEITOR ?> livros.
        </span>
        <?= mensagemErro($erros, 'data_prevista') ?>
    </div>
    <button type="submit">Registrar Empréstimo</button>
</form>

<hr>
<h2>Empréstimos</h2>

<nav class="filtros">
    <?php foreach ($filtros as $chave => $nome): ?>
        <a href="emprestimos.controllers.php?filtro=<?= $chave ?>" class="<?= $chave === $filtro ? 'ativo' : '' ?>"><?= $nome ?></a>
    <?php endforeach; ?>
</nav>

<div class="tabela-container">
    <table>
        <thead>
            <tr>
                <th>Livro</th>
                <th>Leitor</th>
                <th>Emprestado em</th>
                <th>Devolução prevista</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($emprestimos)): ?>
                <?php foreach ($emprestimos as $emprestimo): ?>
                    <tr>
                        <td><?= e($emprestimo->getLivroTitulo()) ?></td>
                        <td>
                            <?= e($emprestimo->getLeitorNome()) ?><br>
                            <small><?= e(formatarTelefone($emprestimo->getLeitorTelefone())) ?></small>
                        </td>
                        <td><?= formatarData($emprestimo->getDataEmprestimo()) ?></td>
                        <td><?= formatarData($emprestimo->getDataPrevista()) ?></td>
                        <td>
                            <?php $status = $emprestimo->getStatus(); ?>
                            <?php if ($status === 'atrasado'): ?>
                                <span class="etiqueta atrasado">Em atraso (<?= $emprestimo->getDiasAtraso() ?> dias)</span>
                            <?php elseif ($status === 'devolvido'): ?>
                                <span class="etiqueta disponivel">Devolvido em <?= formatarData($emprestimo->getDataDevolucao()) ?></span>
                            <?php else: ?>
                                <span class="etiqueta emprestado">Em dia</span>
                            <?php endif; ?>
                        </td>
                        <td class="acoes">
                            <?php if ($status !== 'devolvido'): ?>
                                <form action="emprestimos.controllers.php?acao=devolver&filtro=<?= e($filtro) ?>" method="POST" onsubmit="return confirm('Confirmar devolução do livro?');">
                                    <input type="hidden" name="id" value="<?= $emprestimo->getId() ?>">
                                    <button type="submit" class="botao-pequeno">Registrar Devolução</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Nenhum empréstimo encontrado neste filtro.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>