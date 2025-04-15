<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= isset($registro) ? 'Editar Banco' : 'Novo Banco' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4"><?= isset($registro) ? 'Editar Banco' : 'Novo Banco' ?></h2>

<form method="POST" action="?path=banco_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Descrição:</label>
        <input type="text" name="descricao" class="form-control" value="<?= $registro['descricao'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Número:</label>
        <input type="text" name="numero" class="form-control" value="<?= $registro['numero'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Conta:</label>
        <input type="text" name="conta" class="form-control" value="<?= $registro['conta'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Titular:</label>
        <input type="text" name="titular" class="form-control" value="<?= $registro['titular'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Chave PIX:</label>
        <input type="text" name="pix" class="form-control" value="<?= $registro['pix'] ?? '' ?>">
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="ativo">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=bancos" class="btn btn-secondary ms-2">Cancelar</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
