<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lançamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2><?= isset($registro) ? 'Editar' : 'Novo' ?> Lançamento</h2>

<form method="POST" action="?path=controle_salvar_lancamento">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">
    <input type="hidden" name="controle_id" value="<?= $_GET['id'] ?? $registro['controle_id'] ?>">

    <div class="mb-3">
        <label class="form-label">Data:</label>
        <input type="date" name="data" class="form-control" value="<?= $registro['data'] ?? date('Y-m-d') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição:</label>
        <input type="text" name="descricao" class="form-control" value="<?= $registro['descricao'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Valor:</label>
        <input type="number" step="0.01" name="valor" class="form-control" value="<?= $registro['valor'] ?? '' ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>