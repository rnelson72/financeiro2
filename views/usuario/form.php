<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= isset($registro) ? 'Editar Usuário' : 'Novo Usuário' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4"><?= isset($registro) ? 'Editar Usuário' : 'Novo Usuário' ?></h2>

<form method="POST" action="?path=usuario_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Nome:</label>
        <input type="text" name="nome" class="form-control" value="<?= $registro['nome'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" value="<?= $registro['email'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha:</label>
        <input type="password" name="senha" class="form-control" <?= isset($registro) ? '' : 'required' ?>>
        <?php if (isset($registro)) echo '<small class="text-muted">Deixe em branco para manter a senha atual.</small>'; ?>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=usuario" class="btn btn-secondary ms-2">Cancelar</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>