<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2><?= isset($registro) ? 'Editar' : 'Novo' ?> Cartoes Credito</h2>
<form method='POST' action='?path=cartoes_credito_salvar'>
  <input type='hidden' name='id' value='<?= $registro['id'] ?? '' ?>'>
<div class='mb-3'><label class='form-label'>Descricao:</label><input type='text' name='descricao' class='form-control' value='<?= $registro['descricao'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Fechamento:</label><input type='text' name='fechamento' class='form-control' value='<?= $registro['fechamento'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Vencimento:</label><input type='text' name='vencimento' class='form-control' value='<?= $registro['vencimento'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Limite:</label><input type='text' name='limite' class='form-control' value='<?= $registro['limite'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Banco_Id:</label><input type='text' name='banco_id' class='form-control' value='<?= $registro['banco_id'] ?? '' ?>' required></div>
<button type='submit' class='btn btn-success'>Salvar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
