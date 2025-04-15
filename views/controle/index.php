<h2 class="mb-4">Consulta de Controles</h2>

<?php
// Separar controles ativos e desativados
$ativos = array_filter($controles, fn($c) => $c['ativo'] == 1);
$desativados = array_filter($controles, fn($c) => $c['ativo'] == 0);

// Agrupar controles ativos por grupo
$controles_por_grupo = [];
$desagrupados = [];

foreach ($ativos as $ctrl) {
    if (!empty($ctrl['grupo_id'])) {
        $controles_por_grupo[$ctrl['grupo_id']][] = $ctrl;
    } else {
        $desagrupados[] = $ctrl;
    }
}
?>

<!-- ACCORDION DE GRUPOS -->
<div class="accordion" id="accordionControles">
  <?php foreach ($grupos as $grupo): ?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="grupo-<?= $grupo['id'] ?>">
      <button class="accordion-button <?= empty($controles_por_grupo[$grupo['id']]) ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $grupo['id'] ?>">
        <?= htmlspecialchars($grupo['descricao']) ?>
        <?php if (empty($controles_por_grupo[$grupo['id']])): ?>
          <a href="?path=grupo_excluir&id=<?= $grupo['id'] ?>" class="btn btn-sm btn-outline-danger ms-3" title="Excluir grupo vazio" onclick="return confirm('Deseja excluir este grupo?')">
            <i class="bi bi-trash"></i>
          </a>
        <?php endif; ?>
      </button>
    </h2>
    <div id="collapse-<?= $grupo['id'] ?>" class="accordion-collapse collapse <?= !empty($controles_por_grupo[$grupo['id']]) ? 'show' : '' ?>" data-bs-parent="#accordionControles">
      <div class="accordion-body p-0">
        <?php if (!empty($controles_por_grupo[$grupo['id']])): ?>
        <ul class='list-group list-group-flush'>
          <?php foreach ($controles_por_grupo[$grupo['id']] as $ctrl): ?>
          <li class='list-group-item d-flex justify-content-between align-items-center'>
            <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
            <div class="d-flex align-items-center gap-2">
              <span class='badge bg-success'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
              <a href="?path=controle_lancamentos&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver Lançamentos">
                <i class="bi bi-list-ul"></i>
              </a>
              <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar Controle">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="?path=controle_excluir&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir Controle" onclick="return confirm('Tem certeza que deseja excluir este controle?');">
                <i class="bi bi-trash"></i>
              </a>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="p-3 text-muted">Nenhum controle neste grupo.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- CONTROLES DESAGRUPADOS -->
<?php if (!empty($desagrupados)): ?>
<div class="card mt-4">
  <div class="card-header bg-secondary text-white">
    <strong>Controles Desagrupados</strong>
  </div>
  <div class="card-body p-0">
    <ul class='list-group list-group-flush'>
      <?php foreach ($desagrupados as $ctrl): ?>
      <li class='list-group-item d-flex justify-content-between align-items-center'>
        <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
        <div class="d-flex align-items-center gap-2">
          <span class='badge bg-success'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
          <a href="?path=controle_lancamentos&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver Lançamentos">
            <i class="bi bi-list-ul"></i>
          </a>
          <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar Controle">
            <i class="bi bi-pencil-square"></i>
          </a>
          <a href="?path=controle_excluir&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir Controle" onclick="return confirm('Tem certeza que deseja excluir este controle?');">
            <i class="bi bi-trash"></i>
          </a>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<!-- CONTROLES DESATIVADOS -->
<?php if (!empty($desativados)): ?>
<div class="card mt-5">
  <div class="card-header bg-dark text-white">
    <strong>Controles Desativados</strong>
  </div>
  <div class="card-body p-0">
    <ul class='list-group list-group-flush'>
      <?php foreach ($desativados as $ctrl): ?>
      <li class='list-group-item d-flex justify-content-between align-items-center'>
        <div><?= htmlspecialchars($ctrl['descricao']) ?></div>
        <div class="d-flex align-items-center gap-2">
          <span class='badge bg-secondary'><?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
          <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar Controle">
            <i class="bi bi-pencil-square"></i>
          </a>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>
