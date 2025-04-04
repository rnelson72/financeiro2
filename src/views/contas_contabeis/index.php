<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Tipo de Conta</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contas as $conta): ?>
            <tr>
                <td><?php echo $conta->id; ?></td>
                <td><?php echo $conta->descricao; ?></td>
                <td><?php echo $conta->tipo_conta_id; ?></td>
                <td>
                    <a href="/contas_contabeis/edit/<?php echo $conta->id; ?>">Editar</a>
                    <a href="/contas_contabeis/delete/<?php echo $conta->id; ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>