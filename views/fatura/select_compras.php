<h2 class="mb-4">Selecionar Compras para Fatura</h2>

<div class="mb-3">
    <strong>Total Selecionado: R$ <span id="total-selecionado">0,00</span></strong>
</div>

<form method="POST" action="?path=fatura_fechar">
    <input type="hidden" name="fatura_id" value="<?= $fatura['id'] ?>">
    <div class="mb-3 d-flex gap-2">
        <button type="submit" class="btn btn-success">Fechar Fatura</button>
        <a href="?path=compras_nova&fatura_id=<?= $fatura['id'].'|fatura_select_compras' ?>" class="btn btn-primary">Novo</a>
        <a href="?path=fatura_excluir&id=<?= $fatura['id'] ?>" class="btn btn-danger"
            onclick="return confirm('Tem certeza que deseja cancelar a fatura?');">Cancelar Fatura</a>
    </div>
    <table id="tabela-compras" class="table datatable table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>
                    <input type="checkbox" id="check-all" title="Marcar/desmarcar todos">
                </th>
                <th>Data</th>
                <th>Parcela</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Categoria</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($comprasPendentes as $item): ?>
                <tr>
                    <td>
                        <input
                            type="checkbox"
                            name="compra_ids[]"
                            value="<?= $item['id'] ?>"
                            class="compra-checkbox"
                            data-valor="<?= floatval($item['valor']) ?>"
                        >
                    </td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($item['data']))) ?></td>
                    <td> <?= ($item['parcelas'] > 1) ? $item['parcela_atual'] . '/' . $item['parcelas'] : '-' ?></td>
                    <td><?= htmlspecialchars($item['descricao']) ?></td>
                    <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($item['categoria_nome']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($comprasPendentes)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Nenhuma compra disponível para seleção neste cartão.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</form>

<script>
    function atualizaTotal() {
        let total = 0;
        document.querySelectorAll('.compra-checkbox:checked').forEach(function(cb) {
            total += parseFloat(cb.getAttribute('data-valor'));
        });
        document.getElementById('total-selecionado').textContent =
            total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Checkbox master
    document.getElementById('check-all').addEventListener('change', function() {
        const all = this.checked;
        document.querySelectorAll('.compra-checkbox').forEach(function(cb) {
            cb.checked = all;
        });
        atualizaTotal();
    });

    // Qualquer checkbox individual
    document.querySelectorAll('.compra-checkbox').forEach(function(cb) {
        cb.addEventListener('change', function() {
            atualizaTotal();
        });
    });
</script>