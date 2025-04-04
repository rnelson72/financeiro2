<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2>Bancos</h2>
<a href='?path=bancos_novo' class='btn btn-primary mb-3'>Novo</a>
<table class='table table-striped'><thead><tr><th>ID</th><th>Descrição</th><th>Ações</th></tr></thead><tbody>
<?php foreach ($bancos as $item): ?>
<tr><td><?= $item['id'] ?></td><td><?= htmlspecialchars($item['descricao'] ?? $item['nome'] ?? '') ?></td><td><a href='?path=bancos_editar&id=<?= $item['id'] ?>' class='btn btn-warning btn-sm'>Editar</a> <a href='?path=bancos_excluir&id=<?= $item['id'] ?>' class='btn btn-danger btn-sm' onclick='return confirm("Confirma exclusão?")'>Excluir</a></td></tr><?php endforeach; ?>
</tbody></table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
