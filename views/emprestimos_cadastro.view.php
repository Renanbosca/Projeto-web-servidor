
<h2>Registrar Novo Empréstimo</h2>
<form action="emprestimos.php?acao=salvar" method="POST">
    <div class="form-group">
        <label>Livro:</label>
        <select name="livro_id" style="width: 100%; padding: 8px;" required>
            <option value="">Selecione um livro...</option>
            <!--  criar um array $livrosDisponiveis no Controller -->
            <?php if(!empty($livrosDisponiveis)): ?>
                <?php foreach($livrosDisponiveis as $livro): ?>
                    <option value="<?= $livro['id'] ?>"><?= $livro['titulo'] ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Leitor:</label>
        <select name="leitor_id" style="width: 100%; padding: 8px;" required>
            <option value="">Selecione um leitor...</option>
            <?php if(!empty($leitoresDisponiveis)): ?>
                <?php foreach($leitoresDisponiveis as $leitor): ?>
                    <option value="<?= $leitor['id'] ?>"><?= $leitor['nome'] ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Data de Devolução (DD/MM/AAAA):</label>
        <input type="text" name="data_devolucao" placeholder="Ex: DD/MM/AAAA" maxlength="10" required>
    </div>
    <button type="submit">Registrar Empréstimo</button>
</form>

<hr>
<h2>Empréstimos Ativos</h2>
<table>
    <thead>
        <tr>
            <th>Livro</th>
            <th>Leitor</th>
            <th>Data de Devolução</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($emprestimos)): ?>
            <?php foreach($emprestimos as $emprestimo): ?>
                <tr>
                    <td><?= $emprestimo['livro_titulo'] ?></td>
                    <td><?= $emprestimo['leitor_nome'] ?></td>
                    <td><?= $emprestimo['data_devolucao'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Nenhum empréstimo ativo no momento.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require 'rodape.view.php'; ?>