<h2 class="mb-4">Categorias</h2>
<a href="?path=categoria_novo" class="btn btn-primary mb-3">Nova Categoria</a>
<table id="tabela-categorias" class="table datatable table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['descricao']) ?></td>
                <td>
                    <a href="?path=categoria_editar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="?path=categoria_excluir&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
