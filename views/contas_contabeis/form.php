<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2><?= isset($registro) ? 'Editar' : 'Novo' ?> Contas Contabeis</h2>
<form method='POST' action='?path=contas_contabeis_salvar'>
  <input type='hidden' name='id' value='<?= $registro['id'] ?? '' ?>'>
<div class='mb-3'><label class='form-label'>Descricao:</label><input type='text' name='descricao' class='form-control' value='<?= $registro['descricao'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Tipo:</label><input type='text' name='tipo' class='form-control' value='<?= $registro['tipo'] ?? '' ?>' required></div>
<button type='submit' class='btn btn-success'>Salvar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
