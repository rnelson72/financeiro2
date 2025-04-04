<!-- Lista de usuários -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario->id ?></td>
                <td><?= $usuario->nome ?></td>
                ?></td>
                <td>
                    <a href="/index.php?controller=Usuario&action=edit&id=<?= $usuario->id ?>">Editar</a>
                    <a href="/index.php?controller=Usuario&action=delete&id=<?= $usuario->id ?>">Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/index.php?controller=Usuario&action=create">Adicionar Novo Usuário</a>