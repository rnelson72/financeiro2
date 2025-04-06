<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Controle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2><?= isset($registro) ? 'Editar' : 'Novo' ?> Controle</h2>

<form method="POST" action="?path=controle_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Descrição:</label>
        <input type="text" name="descricao" class="form-control" required value="<?= $registro['descricao'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Grupo existente:</label>
        <select name="grupo_id" class="form-select">
            <option value="">-- Nenhum --</option>
            <?php foreach ($grupos as $g): ?>
                <option value="<?= $g['id'] ?>" <?= ($registro['grupo_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Ou criar novo grupo:</label>
        <input type="text" name="novo_grupo" class="form-control" placeholder="Novo grupo (opcional)">
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>