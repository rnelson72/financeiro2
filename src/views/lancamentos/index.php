<!-- Lista de lançamentos -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Controle</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lancamentos as $lancamento): ?>
            <tr>
                <td><?= $lancamento->id ?></td>
                <td><?= $lancamento->controle_id ?></td>
                <td><?= $lancamento->valor ?></td>
                <td><?= $lancamento->data ?></td>
                <td>
                    <a href="/index.php?controller=Lancamento&action=edit&id=<?= $lancamento->id ?>">Editar</a>
                    <a href="/index.php?controller=Lancamento&action=delete&id=<?= $lancamento->id ?>">Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/index.php?controller=Lancamento&action=create">Adicionar Novo Lançamento</a>