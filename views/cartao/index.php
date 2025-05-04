<h2 class="mb-4">Cartões de Crédito</h2>

<a href='?path=cartao_novo' class='btn btn-primary mb-3'><i class="bi bi-plus-circle"></i> Novo Cartão</a>

<table id="tabela-cartoes" class='table datatable table-striped table-hover'>
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Venc.</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cartoes as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['descricao']) ?></td>
            <td><?= $item['dia_vencimento'] ?></td>
            <td>
                <a href='?path=cartao_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'><i class="bi bi-pencil-square"></i></a>
                <a href='?path=cartao_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'><i class="bi bi-trash"></i></a>
                <a href='?path=final_cartao_listar&cartao_id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-secondary' title='Finais'>
                    <i class="bi bi-credit-card"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>