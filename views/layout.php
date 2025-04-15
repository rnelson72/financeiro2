<!-- views/layout.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $titulo ?? 'Sistema' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/financeiro2/public/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="?path=controles">Financeiro2</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="?path=controles">Controles</a></li>
        <li class="nav-item"><a class="nav-link" href="?path=controle_novo">Novo Controle</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <?php include $conteudo; ?>
</div>

<footer class="bg-light text-center p-3 mt-5 border-top">
  <p class="mb-0">&copy; <?= date('Y') ?> - Sistema Financeiro | Isabel Cristina C. Gonçalves</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
