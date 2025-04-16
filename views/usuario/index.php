<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
<h2 class="mb-4">Usuários</h2>
<a href="?path=usuario_novo" class="btn btn-primary mb-3">Novo Usuário</a>
<table id="tabela-usuarios" class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td>
                <a href="?path=usuario_editar&id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                <a href="?path=usuario_excluir&id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
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
        $('#tabela-usuarios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });
    });
</script>
</body>
</html>