<?php
// link de volta para a listagem com contexto preservado
$linkVoltar = '?' . http_build_query(array_merge(['path' => 'movimentacao'], capturar_contexto_para_url()));
?>

<h2 class="mb-4"><?= $titulo ?></h2>

<form method="POST" action="?path=movimentacao_salvar&<?= http_build_query(capturar_contexto_para_url()) ?>" class="row g-3">

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
        <input type="number" name="categoria_id" id="categoria_id" class="form-control"
               value="<?= $registro['categoria_id'] ?? '' ?>">
    </div>

    <div class="col-md-3">
        <label for="conta_id" class="form-label">Conta</label>
        <input type="number" name="conta_id" id="conta_id" class="form-control"
               value="<?= $registro['conta_id'] ?? '' ?>">
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
