<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h4 class="mb-4 text-center">Acesso ao Sistema</h4>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="?path=autenticar">
        <div class="mb-3">
            <label class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Senha:</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

