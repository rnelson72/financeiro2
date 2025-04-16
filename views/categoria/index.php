<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2 class="mb-4">Categorias</h2>
<a href="?path=categoria_novo" class="btn btn-primary mb-3">Nova Categoria</a>
<table id="tabela-categorias" class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Conta</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['conta']) ?></td>
                <td><?= htmlspecialchars($item['descricao']) ?></td>
                <td><?= $item['tipo'] ?></td>
                <td><?= $item['ativo'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href="?path=categoria_editar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="?path=categoria_excluir&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tabela-categorias').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });
    });
</script>
</body>
</html>