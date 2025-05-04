<?php
// link de volta para a listagem com contexto preservado
$linkVoltar = '?path=movimentacao&' . $contextoUrl;
?>

<h2 class="mb-4"><?= $titulo ?></h2>

<form method="POST" action="?path=movimentacao_salvar&<?= $contextoUrl ?>" class="row g-3">

    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="col-md-3">
        <label for="data" class="form-label">Data</label>
        <input type="date" name="data" id="data" class="form-control" required
               value="<?= $registro['data'] ?? date('Y-m-d') ?>">
    </div>

    <div class="col-md-6">
        <label for="descricao" class="form-label">Descrição</label>
        <input type="text" name="descricao" id="descricao" class="form-control" required
               value="<?= htmlspecialchars($registro['descricao'] ?? '') ?>">
    </div>

    <div class="col-md-3">
        <label for="valor" class="form-label">Valor</label>
        <input type="number" name="valor" id="valor" class="form-control" step="0.01" required
               value="<?= $registro['valor'] ?? '' ?>">
    </div>

    <div class="col-md-3">
        <label for="categoria_id" class="form-label">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-select">
            <option value="">Selecione...</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($registro['categoria_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label for="banco_id" class="form-label">Conta</label>
        <select name="banco_id" id="banco_id" class="form-select">
            <option value="">Selecione...</option>
            <?php foreach ($bancos as $bb): ?>
                <option value="<?= $bb['id'] ?>" <?= ($registro['banco_id'] ?? '') == $bb['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($bb['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label for="codigo_pagamento" class="form-label">Código Pagamento</label>
        <input type="number" name="codigo_pagamento" id="codigo_pagamento" class="form-control"
               value="<?= $registro['codigo_pagamento'] ?? '' ?>">
    </div>

    <div class="col-12 d-flex justify-content-between mt-4">
        <a href="<?= $linkVoltar ?>" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>

</form>
