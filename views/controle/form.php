<h2 class="mb-4">
  <?= empty($registro['id']) ? 'Novo Controle' : 'Editar Controle' ?>
</h2>

<form method="POST" action="?path=controle_salvar">
  <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

  <div class="mb-3">
    <label for="descricao" class="form-label">Descrição</label>
    <input type="text" name="descricao" class="form-control" required
           value="<?= htmlspecialchars($registro['descricao'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label for="grupo_id" class="form-label">Grupo</label>
    <select name="grupo_id" class="form-select">
      <option value="">Selecione...</option>
      <?php foreach ($grupos as $g): ?>
        <option value="<?= $g['id'] ?>"
            <?= isset($registro['grupo_id']) && $registro['grupo_id'] == $g['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($g['descricao'] ?? '') ?>
        </option>
      <?php endforeach; ?>
    </select>
    <small class="text-muted">Ou digite um novo grupo abaixo</small>
  </div>

  <div class="mb-3">
    <label for="novo_grupo" class="form-label">Novo Grupo (opcional)</label>
    <input type="text" name="novo_grupo" class="form-control">
  </div>

  <div class="d-flex justify-content-between">
    <a href="?path=controles" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <button type="submit" class="btn btn-success">
      <i class="bi bi-check-circle"></i> Salvar
    </button>
  </div>
</form>
