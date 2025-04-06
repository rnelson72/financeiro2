<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lançamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Lançamentos de: <?= htmlspecialchars($controle['descricao']) ?></h2>
<a href="?path=controle_novo_lancamento&id=<?= $controle['id'] ?>" class="btn btn-primary mb-3">Novo Lançamento</a>

<table class="table table-bordered table-striped">
    <thead>
        <tr><th>Data</th><th>Descrição</th><th>Valor</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php $total = 0; foreach ($lancamentos as $l): $total += $l['valor']; ?>
        <tr>
            <td><?= date('d/m/Y', strtotime($l['data'])) ?></td>
            <td><?= htmlspecialchars($l['descricao']) ?></td>
            <td>R$ <?= number_format($l['valor'], 2, ',', '.') ?></td>
            <td>
                <a href="?path=controle_editar_lancamento&id=<?= $l['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="?path=controle_excluir_lancamento&id=<?= $l['id'] ?>&ctrl=<?= $controle['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr><th colspan="2">Total</th><th colspan="2">R$ <?= number_format($total, 2, ',', '.') ?></th></tr>
    </tfoot>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>