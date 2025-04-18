<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$rotaAtual = $_GET['path'] ?? '';

$rotas_livres = ['login', 'autenticar', 'esqueci_senha', 'esqueci_senha_post', 'redefinir_senha', 'salvar_nova_senha'];

if (!isset($_SESSION['usuario_id']) && !in_array($rotaAtual, $rotas_livres)) {
    header('Location: ?path=login');
    exit;
}

function renderScripts($scripts) {
    if (is_array($scripts)) {
        foreach ($scripts as $script) {
            if (str_ends_with($script, '.css')) {
                echo "<link rel=\"stylesheet\" href=\"$script\">\n";
            } else {
                echo "<script src=\"$script\"></script>\n";
            }
        }
    } elseif (!empty($scripts)) {
        if (str_ends_with($scripts, '.css')) {
            echo "<link rel=\"stylesheet\" href=\"$scripts\">\n";
        } else {
            echo "<script src=\"$scripts\"></script>\n";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Sistema' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap e ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/financeiro2/public/assets/css/style.css" rel="stylesheet">

    <!-- Scripts HEAD adicionais -->
    <?php renderScripts($scriptsHead ?? []); ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="?path=menu">Financeiro2</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav me-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="cadastrosDropdown" role="button" data-bs-toggle="dropdown">Cadastros</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?path=banco">Bancos</a></li>
            <li><a class="dropdown-item" href="?path=categoria">Categorias</a></li>
            <li><a class="dropdown-item" href="?path=cartao">Cartões</a></li>
            <li><a class="dropdown-item" href="?path=controle">Controles</a></li>
          </ul>
        </li>

        <!-- views/layout.php ou menu.php -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Administração
          </a>
          <ul class="dropdown-menu" aria-labelledby="adminDropdown">
            <li><a class="dropdown-item" href="?path=migrar_cartao">Migrar Cartões</a></li>
            <li><a class="dropdown-item" href="?path=migrar_categoria">Migrar Categorias</a></li>
            <li><a class="dropdown-item" href="?path=migrar_banco">Migrar Bancos</a></li>
            <li><a class="dropdown-item" href="?path=migrar_controle">Migrar Controles</a></li>
          </ul>
        </li>
      </ul>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item me-3 text-white">
          <span><i class="bi bi-person-circle"></i> <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?></span>
        </li>
        <li class="nav-item">
          <a class="nav-link text-warning" href="?path=logout"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </li>
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

<!-- Scripts base -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Scripts BODY adicionais -->
<?php renderScripts($scriptsBody ?? []); ?>
</body>
</html>
