<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bancos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
</head>
<body class="container mt-4">

<h2 class="mb-4">Cadastro de Bancos</h2>

<a href='?path=banco_novo' class='btn btn-primary mb-3'>Novo Banco</a>

<table id="tabela-bancos" class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Número</th>
            <th>Conta</th>
            <th>Titular</th>
            <th>PIX</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bancos as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['descricao']) ?></td>
                <td><?= htmlspecialchars($item['numero']) ?></td>
                <td><?= htmlspecialchars($item['conta']) ?></td>
                <td><?= htmlspecialchars($item['titular']) ?></td>
                <td><?= htmlspecialchars($item['pix']) ?></td>
                <td><?= $item['ativo'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href='?path=banco_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'>
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href='?path=banco_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'>
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- jQuery e DataTables JS -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

        <!-- Inicialização -->
        <script>
        $(document).ready(function() {
            $('#tabela-bancos').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
            });
        });
        </script>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
