<h2 class="mb-4">Consulta de Controles</h2>

<?php
$controles_por_grupo = [];
$desagrupados = [];
$desativados = [];

foreach ($controles as $ctrl) {
    if (($ctrl['ativo'] == 0) or ($ctrl['ativo'] == '0')) {
        $desativados[] = $ctrl;
        continue;
    }

    if (empty($ctrl['grupo_id'])) {
      $desagrupados[] = $ctrl;
    } else {
      $controles_por_grupo[$ctrl['grupo_id']][] = $ctrl;
    }
}
?>

<div class="accordion" id="accordionControles">

  <!-- DESAGRUPADOS -->
  <?php if (!empty($desagrupados)): ?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="grupo-desagrupados">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-desagrupados">
        <span class="fs-5 fw-bold"><i class="bi bi-layers me-2"></i>Controles Desagrupados</span>
      </button>
    </h2>
    <div id="collapse-desagrupados" class="accordion-collapse collapse" data-bs-parent="#accordionControles">
      <div class="accordion-body p-0">
        <ul class='list-group list-group-flush'>
          <?php foreach ($desagrupados as $ctrl): ?>
          <li class='list-group-item d-flex justify-content-between align-items-center'>
            <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
            <div class="d-flex align-items-center gap-2">
              <span class='badge bg-success'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
              <a href="?path=controle_lancamentos&controle_id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul"></i></a>
              <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
              <a href="?path=controle_excluir&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este controle?')"><i class="bi bi-trash"></i></a>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- GRUPOS COM CONTROLES -->
  <?php foreach ($grupos as $grupo): ?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="grupo-<?= $grupo['id'] ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $grupo['id'] ?>">
        <span class="fs-5 fw-bold"><i class="bi bi-folder me-2"></i><?= htmlspecialchars($grupo['descricao']) ?></span>
        <?php if (empty($controles_por_grupo[$grupo['id']])): ?>
          <a href="?path=grupo_excluir&id=<?= $grupo['id'] ?>" class="btn btn-sm btn-outline-danger ms-3" title="Excluir grupo vazio" onclick="return confirm('Deseja excluir este grupo?')">
            <i class="bi bi-trash"></i>
          </a>
        <?php endif; ?>
      </button>
    </h2>
    <div id="collapse-<?= $grupo['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordionControles">
      <div class="accordion-body p-0">
        <?php if (!empty($controles_por_grupo[$grupo['id']])): ?>
        <ul class='list-group list-group-flush'>
          <?php foreach ($controles_por_grupo[$grupo['id']] as $ctrl): ?>
          <li class='list-group-item d-flex justify-content-between align-items-center'>
            <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
            <div class="d-flex align-items-center gap-2">
              <span class='badge bg-success'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
              <a href="?path=controle_lancamentos&controle_id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul"></i></a>
              <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
              <a href="?path=controle_excluir&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este controle?')"><i class="bi bi-trash"></i></a>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="p-3 text-muted d-flex justify-content-between align-items-center">
          <span><i class="bi bi-exclamation-circle me-2"></i>Este grupo está vazio.</span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <!-- DESATIVADOS -->
  <?php if (!empty($desativados)): ?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="grupo-desativados">
      <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-desativados">
        <span class="fs-5 fw-bold"><i class="bi bi-eye-slash me-2"></i>Controles Desativados</span>
      </button>
    </h2>
    <div id="collapse-desativados" class="accordion-collapse collapse" data-bs-parent="#accordionControles">
      <div class="accordion-body p-0">
        <ul class='list-group list-group-flush'>
          <?php foreach ($desativados as $ctrl): ?>
          <li class='list-group-item bg-light d-flex justify-content-between align-items-center'>
            <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
            <div class="d-flex align-items-center gap-2">
              <span class='badge bg-secondary'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
              <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
