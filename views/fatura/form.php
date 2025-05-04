<h2 class="mb-4"><?= isset($fatura) ? 'Editar Fatura' : 'Nova Fatura' ?></h2>

<form method="POST" action="?path=fatura_salvar">
    <input type="hidden" name="id" value="<?= $fatura['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Cartão:</label>
        <select name="cartao_id" class="form-select" required>
            <option value="">Selecione...</option>
            <?php foreach ($cartoes as $cartao): ?>
                <option value="<?= $cartao['id'] ?>" <?= (isset($fatura['cartao_id']) && $fatura['cartao_id'] == $cartao['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cartao['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Data de Fechamento:</label>
        <input type="date" name="data_fechamento" class="form-control" value="<?= isset($fatura['data_fechamento']) ? htmlspecialchars($fatura['data_fechamento']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Data de Vencimento:</label>
        <input type="date" name="data_vencimento" class="form-control" value="<?= isset($fatura['data_vencimento']) ? htmlspecialchars($fatura['data_vencimento']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Valor Total (informado na fatura do banco):</label>
        <input type="number" name="valor_total" step="0.01" class="form-control" value="<?= isset($fatura['valor_total']) ? htmlspecialchars($fatura['valor_total']) : '' ?>" required>
    </div>

    <!-- Valor Pago apenas na edição e visível se for positivo ou zero -->
    <?php if (isset($fatura)) : ?>
        <?php 
            $valor_pago = floatval($fatura['valor_pago'] ?? 0);
        ?>
        <?php if ($valor_pago >= 0) : ?>
            <div class="mb-3">
                <label class="form-label">Valor Pago:</label>
                <input
                    type="number"
                    name="valor_pago"
                    step="0.01"
                    class="form-control"
                    value="<?= htmlspecialchars($valor_pago) ?>"
                    <?= ($valor_pago > 0) ? '' : 'readonly' ?>
                >
                <small class="form-text text-muted">Preencher apenas após o pagamento.</small>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Campo status como hidden -->
    <?php if (!isset($fatura)) : ?>
        <!-- Inclusão: status padrão 'Aberta' -->
        <input type="hidden" name="status" value="Aberta">
    <?php else: ?>
        <!-- Edição: não há valor a forçar, mantém o status atual -->
        <input type="hidden" name="status" value="<?= htmlspecialchars($fatura['status']) ?>">
    <?php endif; ?>

    <!-- Campo movimentacao_id somente leitura na edição -->
    <?php if (isset($fatura)) : ?>
        <div class="mb-3">
            <label class="form-label">Movimentação:</label>
            <input type="number" name="movimentacao_id" class="form-control" 
                value="<?= htmlspecialchars($fatura['movimentacao_id'] ?? '') ?>"
                readonly>
            <small class="form-text text-muted">Preenchido automaticamente quando a fatura é marcada como paga.</small>
        </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=faturas" class="btn btn-secondary ms-2">Cancelar</a>
</form>