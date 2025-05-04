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
    <title><?= $titulo ?? 'Sistema Financeiro' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap e ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Scripts HEAD adicionais -->
    <?php renderScripts($scriptsHead ?? []); ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="?path=dashboard">Financeiro</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav me-auto">
        <?php
        $menu_agrupado = $_SESSION['menu_agrupado'] ?? [];
        echo '<!-- DEBUG do menu_agrupado: ' . print_r($menu_agrupado, true) . ' -->';
        foreach ($menu_agrupado as $grupo => $itens): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="#"
               id="<?= strtolower(preg_replace('/\s+/', '', $grupo)) ?>Dropdown"
               role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($grupo) ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="<?= strtolower(preg_replace('/\s+/', '', $grupo)) ?>Dropdown">
              <?php foreach ($itens as $item): ?>
                <li>
                  <a class="dropdown-item" href="?path=<?= htmlspecialchars($item['rota']) ?>">
                    <?php if (!empty($item['icone'])): ?>
                      <i class="<?= htmlspecialchars($item['icone']) ?>"></i>
                    <?php endif ?>
                    <?= htmlspecialchars($item['nome']) ?>
                  </a>
                </li>
              <?php endforeach ?>
            </ul>
          </li>
        <?php endforeach ?>
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
<?php if (isset($_SESSION['mensagem_erro'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $_SESSION['mensagem_erro']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php // Não faz unset aqui! ?>
<?php endif; ?>
<?php if (isset($_SESSION['mensagem_sucesso'])): ?>
  <div class="alert alert-success"><?= $_SESSION['mensagem_sucesso']; unset($_SESSION['mensagem_sucesso']); ?></div>
<?php endif; ?>
<div class="container">
  <?php include $conteudo; ?>
</div>

<footer class="bg-light text-center p-3 mt-5 border-top">
  <p class="mb-0">&copy; <?= date('Y') ?> - Sistema Financeiro | Ricardo Nelson Gonçalves</p>
</footer>

<!-- Scripts base -->
<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts BODY adicionais -->
<?php renderScripts($scriptsBody ?? []); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const btn = document.getElementById('fechar-erro');
  if(btn) {
    btn.addEventListener('click', function() {
      fetch('limpar_erro.php').then(() => {
        document.getElementById('mensagem-erro').style.display = 'none';
      });
    });
  }
});
</script>
</body>
</html>
