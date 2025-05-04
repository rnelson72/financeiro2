<h2 class="mb-4">Faturas de Cartão de Crédito</h2>

<a href='?path=fatura_nova' class='btn btn-primary mb-3'>
    <i class="bi bi-plus-circle"></i> Nova Fatura
</a>

<form method="get" class="row g-2 mb-3">
    <input type="hidden" name="path" value="faturas">
    <div class="col-auto">
        <select name="cartao_id" class="form-select">
            <option value="">Todos os Cartões</option>
            <?php foreach ($cartoes as $cartao): ?>
                <option value="<?= $cartao['id'] ?>" <?= isset($_GET['cartao_id']) && $_GET['cartao_id'] == $cartao['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cartao['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-search"></i> Filtrar
        </button>
    </div>
</form>

<table id="tabela-faturas" class='table datatable table-striped table-hover'>
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Cartão</th>
            <th>Fechamento</th>
            <th>Vencimento</th>
            <th>Valor Total</th>
            <th>Valor Pago</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($faturas as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td>
                <?php
                    // Se já tiver carregado o nome do cartão junto nas faturas (via JOIN), use $item['cartao_nome']
                    // Se não, use o id apenas
                    echo isset($item['cartao_nome']) 
                            ? htmlspecialchars($item['cartao_nome']) 
                            : (isset($cartaoMap[$item['cartao_id']])
                                ? htmlspecialchars($cartaoMap[$item['cartao_id']])
                                : $item['cartao_id']);
                ?>
            </td>
            <td><?= date('d/m/Y', strtotime($item['data_fechamento'])) ?></td>
            <td><?= date('d/m/Y', strtotime($item['data_vencimento'])) ?></td>
            <td><?= number_format($item['valor_total'], 2, ',', '.') ?></td>
            <td><?= is_null($item['valor_pago']) ? '-' : number_format($item['valor_pago'], 2, ',', '.') ?></td>
            <td>
                <?php
                    $badge = 'secondary';
                    if ($item['status'] == 'Fechada') $badge = 'warning';
                    if ($item['status'] == 'Paga') $badge = 'success';
                    if ($item['status'] == 'Aberta') $badge = 'primary';
                ?>
                <span class="badge bg-<?= $badge ?>">
                    <?= htmlspecialchars($item['status']) ?>
                </span>
            </td>
            <td>
                <a href='?path=fatura_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'><i class="bi bi-pencil-square"></i></a>
                <a href='?path=fatura_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'><i class="bi bi-trash"></i></a>
                <a href='?path=fatura_compras&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-secondary' title='Detalhe (Compras)'>
                    <i class="bi bi-list-check"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>