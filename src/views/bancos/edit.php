<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Banco</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Editar Banco</h1>
        <form action="/bancos/update/<?php echo $banco->id; ?>" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $banco->nome; ?>" required>
            <div class="form-group">
                <label for="numero_agencia">Número da Agência:</label>
                <input type="text" id="numero_agencia" name="numero_agencia" class="form-control" value="<?php echo $banco->numero_agencia; ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_conta_corrente">Número da Conta Corrente:</label>
                <input type="text" id="numero_conta_corrente" name="numero_conta_corrente" class="form-control" value="<?php echo $banco->numero_conta_corrente; ?>" required>
            </div>
            <div class="form-group">
                <label for="saldo_inicial">Saldo Inicial:</label>
                <input type="number" id="saldo_inicial" name="saldo_inicial" class="form-control" value="<?php echo $banco->saldo_inicial; ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>
</html>