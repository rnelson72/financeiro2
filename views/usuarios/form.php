<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2><?= isset($registro) ? 'Editar' : 'Novo' ?> Usuarios</h2>
<form method='POST' action='?path=usuarios_salvar'>
  <input type='hidden' name='id' value='<?= $registro['id'] ?? '' ?>'>
<div class='mb-3'><label class='form-label'>Nome:</label><input type='text' name='nome' class='form-control' value='<?= $registro['nome'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Email:</label><input type='text' name='email' class='form-control' value='<?= $registro['email'] ?? '' ?>' required></div>
<div class='mb-3'><label class='form-label'>Senha:</label><input type='password' name='senha' class='form-control' value='<?= $registro['senha'] ?? '' ?>' required></div>
<button type='submit' class='btn btn-success'>Salvar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
