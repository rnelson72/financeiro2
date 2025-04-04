<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Lista de Bancos</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Lista de Bancos</h1>
        <table class="table table-striped">
            <thead>
                    <th>Nome</th>
                    <th>Número da Agência</th>
                    <th>Número da Conta Corrente</th>
                    <th>Saldo Inicial</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bancos as $banco): ?>
                    <tr>
                        <td><?php echo $banco->id; ?></td>
                        <td><?php echo $banco->nome; ?></td>
                        <td><?php echo $banco->numero_agencia; ?></td>
                        <td><?php echo $banco->numero_conta_corrente; ?></td>
                        <td><?php echo $banco->saldo_inicial; ?></td>
                        <td>
                            <a href="/bancos/edit/<?php echo $banco->id; ?>" class="btn btn-warning">Editar</a>
                            <a href="/bancos/delete/<?php echo $banco->id; ?>" class="btn btn-danger">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>