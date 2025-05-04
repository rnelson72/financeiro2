<h2 class="mb-4">Usuários</h2>
<a href="?path=usuario_novo" class="btn btn-primary mb-3">Novo Usuário</a>
<table id="tabela-usuarios" class="table datatable table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $usu): ?>
        <tr>
            <td><?= $usu['id'] ?></td>
            <td><?= htmlspecialchars($usu['nome']) ?></td>
            <td><?= htmlspecialchars($usu['email']) ?></td>
            <td>
                <a href="?path=usuario_editar&id=<?= $usu['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                <a href="?path=usuario_excluir&id=<?= $usu['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
