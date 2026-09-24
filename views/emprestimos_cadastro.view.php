<h2>Registrar Novo Empréstimo</h2>

<?php if (!empty($erros)): ?>
    <div class="erro"><?= e($erros['geral'] ?? 'Não foi possível registrar. Corrija os campos destacados.') ?></div>
<?php endif; ?>

<form action="emprestimos.php?acao=salvar" method="POST" novalidate>
    <div class="form-group">
        <label for="livro_id">Livro:</label>
        <select id="livro_id" name="livro_id" class="<?= classeErro($erros, 'livro_id') ?>">
            <option value="">Selecione um livro...</option>
            <?php foreach ($livrosDisponiveis as $livro): ?>
                <option value="<?= $livro['id'] ?>" <?= (string) $livro['id'] === $form['livro_id'] ? 'selected' : '' ?>>
                    <?= e($livro['titulo']) ?> (<?= e($livro['autor']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($livrosDisponiveis)): ?>
            <span class="dica">Nenhum livro disponível no momento. <a href="livros.php">Cadastrar livro</a></span>
        <?php endif; ?>
        <?= mensagemErro($erros, 'livro_id') ?>
    </div>
    
    <div class="form-group">
        <label for="leitor_id">Leitor:</label>
        <select id="leitor_id" name="leitor_id" class="<?= classeErro($erros, 'leitor_id') ?>">
            <option value="">Selecione um leitor...</option>
            <?php foreach ($leitores as $leitor): ?>
                <option value="<?= $leitor['id'] ?>" <?= (string) $leitor['id'] === $form['leitor_id'] ? 'selected' : '' ?>>
                    <?= e($leitor['nome']) ?> (<?= $leitor['emprestimos_ativos'] ?> livro(s) com ele)
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($leitores)): ?>
            <span class="dica">Nenhum leitor cadastrado. <a href="leitores.php">Cadastrar leitor</a></span>
        <?php endif; ?>
        <?= mensagemErro($erros, 'leitor_id') ?>
    </div>
    
    <div class="form-group">
        <label for="data_prevista">Data Prevista de Devolução (DD/MM/AAAA):</label>
        <input type="text" id="data_prevista" name="data_prevista" value="<?= e($form['data_prevista']) ?>" placeholder="Ex: 05/10/2026" class="<?= classeErro($erros, 'data_prevista') ?>">
        <span class="dica">
            Prazo máximo de <?= PRAZO_MAXIMO_DIAS ?> dias. Cada leitor pode ficar com até <?= LIMITE_EMPRESTIMOS_POR_LEITOR ?> livros.
        </span>
        <?= mensagemErro($erros, 'data_prevista') ?>
    </div>
    <button type="submit">Registrar Empréstimo</button>
</form>

<hr>
<h2>Empréstimos</h2>

<nav class="filtros">
    <?php foreach ($filtros as $chave => $nome): ?>
        <a href="emprestimos.php?filtro=<?= $chave ?>" class="<?= $chave === $filtro ? 'ativo' : '' ?>"><?= $nome ?></a>
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
                        <td><?= e($emprestimo['livro_titulo']) ?></td>
                        <td><?= e($emprestimo['leitor_nome']) ?></td>
                        <td><?= formatarData($emprestimo['data_emprestimo']) ?></td>
                        <td><?= formatarData($emprestimo['data_prevista']) ?></td>
                        <td>
                            <?php if ($emprestimo['status'] === 'devolvido'): ?>
                                <span class="etiqueta devolvido">Devolvido em <?= formatarData($emprestimo['data_devolucao']) ?></span>
                            <?php elseif ($emprestimo['status'] === 'atrasado'): ?>
                                <span class="etiqueta atrasado">Atrasado (<?= $emprestimo['dias_atraso'] ?> dia(s))</span>
                            <?php else: ?>
                                <span class="etiqueta disponivel">Em dia</span>
                            <?php endif; ?>
                        </td>
                        <td class="acoes">
                            <?php if ($emprestimo['status'] !== 'devolvido'): ?>
                                <form action="emprestimos.php?acao=devolver&filtro=<?= $filtro ?>" method="POST" onsubmit="return confirm('Confirmar a devolução deste livro?');">
                                    <input type="hidden" name="id" value="<?= $emprestimo['id'] ?>">
                                    <button type="submit" class="botao-pequeno">Registrar devolução</button>
                                </form>
                            <?php else: ?>
                                -
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
