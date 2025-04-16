<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= isset($registro) ? 'Editar Cartão' : 'Novo Cartão' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4"><?= isset($registro) ? 'Editar Cartão' : 'Novo Cartão' ?></h2>

<form method="POST" action="?path=cartao_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Descrição:</label>
        <input type="text" name="descricao" class="form-control" value="<?= $registro['descricao'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Bandeira:</label>
        <input type="text" name="bandeira" class="form-control" value="<?= $registro['bandeira'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Dia de Fechamento:</label>
        <input type="number" name="dia_fechamento" class="form-control" value="<?= $registro['dia_fechamento'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Dia de Vencimento:</label>
        <input type="number" name="dia_vencimento" class="form-control" value="<?= $registro['dia_vencimento'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Limite:</label>
        <input type="number" step="0.01" name="linha_credito" class="form-control" value="<?= $registro['linha_credito'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Banco ID:</label>
        <input type="text" name="banco_id" class="form-control" value="<?= $registro['banco_id'] ?? '' ?>">
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=cartao" class="btn btn-secondary ms-2">Cancelar</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
