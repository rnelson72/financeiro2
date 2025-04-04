<!-- Lista de controles -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Grupo de Controle</th>
            <th>Saldo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($controles as $controle): ?>
            <tr>
                <td><?= $controle->id ?></td>
                <td><?= $controle->nome ?></td>
                <td><?= $controle->grupo_controle_id ?></td>
                <td><?= $controle->calcularSaldo() ?></td>
                <td>
                    <a href="/index.php?controller=Controle&action=edit&id=<?= $controle->id ?>">Editar</a>
                    <a href="/index.php?controller=Controle&action=delete&id=<?= $controle->id ?>">Deletar</a>
                    <a href="/index.php?controller=Lancamento&action=findByControle&controle_id=<?= $controle->id ?>">Lançamentos</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/index.php?controller=Controle&action=create">Adicionar Novo Controle</a>