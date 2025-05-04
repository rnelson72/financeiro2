<h2 class="mb-4">Grupos de Menu</h2>

<a href='?path=menu_novo' class='btn btn-primary mb-3'>
    <i class="bi bi-plus-circle"></i> Novo Grupo
</a>

<table id="tabela-menu" class='table datatable table-striped table-hover'>
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ícone</th>
            <th>Ordem</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['nome']) ?></td>
            <td>
                <?php if (!empty($item['icone'])): ?>
                    <i class="<?= $item['icone'] ?>"></i>
                    <?= htmlspecialchars($item['icone']) ?>
                <?php endif; ?>
            </td>
            <td><?= $item['ordem'] ?></td>
            <td>
                <?= !empty($item['ativo']) ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?>
            </td>
            <td>
                <a href='?path=menu_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'><i class="bi bi-pencil-square"></i></a>
                <a href='?path=menu_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>