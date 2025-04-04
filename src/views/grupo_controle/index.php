<!-- Lista de grupos de controle -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grupos as $grupo): ?>
            <tr>
                <td><?= $grupo->id ?></td>
                <td><?= $grupo->nome ?></td>
                <td>
                    <a href="/index.php?controller=GrupoControle&action=edit&id=<?= $grupo->id ?>">Editar</a>
                    <a href="/index.php?controller=GrupoControle&action=delete&id=<?= $grupo->id ?>">Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/index.php?controller=GrupoControle&action=create">Adicionar Novo Grupo de Controle</a>