<h2 class="mb-4"><?= isset($registro['id']) ? 'Editar Grupo de Menu' : 'Novo Grupo de Menu' ?></h2>

<form method="POST" action="?path=menu_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Nome do Grupo:</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($registro['nome'] ?? '') ?>" required autofocus>
    </div>

    <div class="mb-3">
        <label class="form-label">Ícone:</label>
        <input type="text" name="icone" class="form-control" value="<?= htmlspecialchars($registro['icone'] ?? '') ?>">
        <small class="form-text text-muted">Classe do ícone (exemplo: <code>bi bi-menu-app</code>).</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Ordem:</label>
        <input type="number" name="ordem" class="form-control" value="<?= isset($registro['ordem']) ? $registro['ordem'] : 0 ?>">
        <small class="form-text text-muted">Defina a ordem de exibição no menu.</small>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=menu" class="btn btn-secondary ms-2">Cancelar</a>
</form>