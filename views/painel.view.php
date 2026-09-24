<h2>Painel da Biblioteca</h2>

<div class="cartoes">
    <a class="cartao" href="livros.php">
        <span class="numero"><?= $totalLivros ?></span>
        <span class="rotulo">Livros cadastrados</span>
        <span class="detalhe"><?= $totalDisponiveis ?> disponíveis</span>
    </a>
    <a class="cartao" href="leitores.php">
        <span class="numero"><?= $totalLeitores ?></span>
        <span class="rotulo">Leitores</span>
    </a>
    <a class="cartao" href="emprestimos.php?filtro=ativos">
        <span class="numero"><?= $totalAtivos ?></span>
        <span class="rotulo">Empréstimos ativos</span>
    </a>
    <a class="cartao <?= count($atrasados) > 0 ? 'cartao-alerta' : '' ?>" href="emprestimos.php?filtro=atrasados">
        <span class="numero"><?= count($atrasados) ?></span>
        <span class="rotulo">Em atraso</span>
    </a>
</div>

<h2>Empréstimos em Atraso</h2>
<div class="tabela-container">
    <table>
        <thead>
            <tr>
                <th>Livro</th>
                <th>Leitor</th>
                <th>Telefone</th>
                <th>Devolução prevista</th>
                <th>Atraso</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($atrasados)): ?>
                <?php foreach ($atrasados as $emprestimo): ?>
                    <tr>
                        <td><?= e($emprestimo['livro_titulo']) ?></td>
                        <td><?= e($emprestimo['leitor_nome']) ?></td>
                        <td><?= e(formatarTelefone($emprestimo['leitor_telefone'])) ?></td>
                        <td><?= formatarData($emprestimo['data_prevista']) ?></td>
                        <td><span class="etiqueta atrasado"><?= $emprestimo['dias_atraso'] ?> dia(s)</span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum empréstimo em atraso.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
