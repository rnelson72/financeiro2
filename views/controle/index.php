<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Controles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2>Consulta de Controles</h2>
<?php foreach ($grupos as $grupo): ?>
<div class='card mb-3'>
<div class='card-header'>
<strong><?= htmlspecialchars($grupo['nome']) ?></strong>
</div>
<div class='card-body'>
<ul class='list-group'>
<?php foreach ($controles as $ctrl): if ($ctrl['grupo_id'] == $grupo['id']): ?>
<li class='list-group-item d-flex justify-content-between align-items-center'>
<?= htmlspecialchars($ctrl['descricao']) ?>
<span class='badge bg-primary rounded-pill'>R$ <?= number_format($ctrl['saldo'], 2, ',', '.') ?></span>
</li>
<?php endif; endforeach; ?>
</ul>
</div>
</div>
<?php endforeach; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
