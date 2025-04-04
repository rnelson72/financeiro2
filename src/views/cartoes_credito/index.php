<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Limite de Crédito</th>
            <th>Vencimento da Fatura</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cartoes as $cartao): ?>
            <tr>
                <td><?php echo $cartao->id; ?></td>
                <td><?php echo $cartao->descricao; ?></td>
                <td><?php echo $cartao->limite_credito; ?></td>
                <td><?php echo $cartao->vencimento_fatura; ?></td>
                <td><?php echo $cartao->ativo ? 'Sim' : 'Não'; ?></td>
                <td>
                    <a href="/cartoes_credito/edit/<?php echo $cartao->id; ?>">Editar</a>
                    <a href="/cartoes_credito/delete/<?php echo $cartao->id; ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>