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
          <?= htmlspecialchars($ctrl['descricao']) ?>
          <span class='badge bg-success'>R$ <?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
        </li>
      <?php endif; endforeach; ?>
    </ul>
  </div>
</div>
<?php endforeach; ?>
