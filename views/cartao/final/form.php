<h2><?= \$titulo ?></h2>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?path=cartao">Cartões</a></li>
    <li class="breadcrumb-item"><a href="?path=final_cartao_lista&cartao_id=<?= \$cartao['id'] ?>">Finais</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?= \$registro['id'] ? 'Editar' : 'Novo' ?></li>
  </ol>
</nav>

<form method="post" action="?path=final_cartao_salvar" class="row g-3">
  <input type="hidden" name="id_final" value="<?= htmlspecialchars(\$registro['id']) ?>">
  <input type="hidden" name="cartao_id" value="<?= htmlspecialchars(\$cartao['id']) ?>">

  <div class="col-md-2">
    <label for="final" class="form-label">Final</label>
    <input type="text" class="form-control" id="final" name="final" maxlength="4" required value="<?= htmlspecialchars(\$registro['final']) ?>">
  </div>

  <div class="col-md-3">
    <label for="titular" class="form-label">Titular</label>
    <input type="text" class="form-control" id="titular" name="titular" value="<?= htmlspecialchars(\$registro['titular']) ?>">
  </div>

  <div class="col-md-2 align-self-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="is_virtual" name="is_virtual" value="1" <?= \$registro['is_virtual'] ? 'checked' : '' ?>>
      <label class="form-check-label" for="is_virtual">Virtual</label>
    </div>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
    <a href="?path=final_cartao_lista&cartao_id=<?= \$cartao['id'] ?>" class="btn btn-secondary">Cancelar</a>
  </div>
</form>
