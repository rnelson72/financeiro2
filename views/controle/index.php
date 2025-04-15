<h2 class="mb-4">Consulta de Controles</h2>

<?php foreach ($grupos as $grupo): ?>
<div class='card mb-3 shadow-sm'>
  <div class='card-header bg-primary text-white'>
    <strong><?= htmlspecialchars($grupo['descricao']) ?></strong>
  </div>
  <div class='card-body p-0'>
    <ul class='list-group list-group-flush'>
      <?php foreach ($controles as $ctrl): if ($ctrl['grupo_id'] == $grupo['id']): ?>
        <li class='list-group-item d-flex justify-content-between align-items-center'>
          <div>
            <?= htmlspecialchars($ctrl['descricao']) ?>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class='badge bg-success'>R$ <?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
            <a href="?path=controle_lancamentos&id=<?= $ctrl['id'] ?>" 
              class="btn btn-sm btn-outline-secondary" 
              title="Ver Lançamentos">
              <i class="bi bi-list-ul"></i>
            </a>

            <a href="?path=controle_editar&id=<?= $ctrl['id'] ?>" 
              class="btn btn-sm btn-outline-primary" 
              title="Editar Controle">
              <i class="bi bi-pencil-square"></i>
            </a>

            <a href="?path=controle_excluir&id=<?= $ctrl['id'] ?>" 
              class="btn btn-sm btn-outline-danger" 
              title="Excluir Controle"
              onclick="return confirm('Tem certeza que deseja excluir este controle?');">
              <i class="bi bi-trash"></i>
            </a>
          </div>
        </li>
      <?php endif; endforeach; ?>
    </ul>
  </div>
</div>
<?php endforeach; ?>
