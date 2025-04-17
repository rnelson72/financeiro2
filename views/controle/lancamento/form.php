<h2><?= $titulo ?></h2>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?path=controles">Controles</a></li>
    <li class="breadcrumb-item"><a href="?path=controle_lancamentos&id=<?= $registro['controle_id'] ?? $_GET['controle_id'] ?>">Lançamentos</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?= $registro['id'] ? 'Editar' : 'Novo' ?></li>
  </ol>
</nav>

<form method="POST" action="?path=controle_salvar_lancamento" class="row g-3">
  <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id'] ?? '') ?>">
  <input type="hidden" name="controle_id" value="<?= htmlspecialchars($_GET['controle_id'] ?? $registro['controle_id'] ?? '') ?>">

  <div class="col-md-3">
    <label for="data" class="form-label">Data</label>
    <input type="date" class="form-control" name="data" required value="<?= htmlspecialchars($registro['data'] ?? date('Y-m-d')) ?>">
  </div>

  <div class="col-md-5">
    <label for="descricao" class="form-label">Descrição</label>
    <input type="text" class="form-control" name="descricao" required value="<?= htmlspecialchars($registro['descricao'] ?? '') ?>">
  </div>

  <div class="col-md-4">
    <label for="valor" class="form-label">Valor</label>
    <input type="number" step="0.01" class="form-control" name="valor" required value="<?= htmlspecialchars($registro['valor'] ?? '') ?>">
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Salvar</button>
    <a href="?path=controle_lancamentos&id=<?= htmlspecialchars($_GET['controle_id'] ?? $registro['controle_id']) ?>" class="btn btn-secondary">Cancelar</a>
  </div>
</form>
