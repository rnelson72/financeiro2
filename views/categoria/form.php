<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= isset($registro) ? 'Editar Categoria' : 'Nova Categoria' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4"><?= isset($registro) ? 'Editar Categoria' : 'Nova Categoria' ?></h2>

<form method="POST" action="?path=categoria_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Conta:</label>
        <input type="text" name="conta" maxlength="20" class="form-control" value="<?= $registro['conta'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição:</label>
        <input type="text" name="descricao" maxlength="100" class="form-control" value="<?= $registro['descricao'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo:</label>
        <select name="tipo" class="form-select" required>
            <option value="">Selecione...</option>
            <?php
            $tipos = ['Despesa', 'Receita', 'SubTotal', 'Resumo', 'Saldos', 'Outros'];
            foreach ($tipos as $tipo) {
                $selected = ($registro['tipo'] ?? '') === $tipo ? 'selected' : '';
                echo "<option value=\"$tipo\" $selected>$tipo</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=categoria" class="btn btn-secondary ms-2">Cancelar</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
