<?php
require_once '../vendor/autoload.php';

use App\Controllers\UsuarioController;
use App\Controllers\BancoController;
use App\Controllers\ContaContabilController;
use App\Controllers\CartaoCreditoController;
use App\Controllers\ControleController;
use App\Controllers\GrupoControleController;
use App\Controllers\LancamentoController;

$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'Usuario';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$database = new \App\Database();
$db = $database->getConnection();

switch ($controllerName) {
    case 'Usuario':
        $controller = new UsuarioController($db);
        break;
    case 'Banco':
        $controller = new BancoController($db);
        break;
    case 'ContaContabil':
        $controller = new ContaContabilController($db);
        break;
    case 'CartaoCredito':
        $controller = new CartaoCreditoController($db);
        break;
    case 'Controle':
        $controller = new ControleController($db);
        break;
    case 'GrupoControle':
        $controller = new GrupoControleController($db);
        break;
    case 'Lancamento':
        $controller = new LancamentoController($db);
        break;
    default:
        $controller = new UsuarioController($db);
        break;
}

ob_start();
$controller->index();
$content = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="/path/to/your/styles.css">
</head>
<body>
   1>
        <nav>
            <ul>
                <li><a href="/index.php?controller=Usuario">Usuários</a></li>
                <li><a href="/index.php?controller=Banco">Bancos</a></li>
                <li><a href="/index.php?controller=ContaContabil">Contas Contábeis</a></li>
                <li><a href="/index.php?controller=CartaoCredito">Cartões de Crédito</a></li>
                <li><a href="/index.php?controller=Controle">Controles</a></li>
                <li><a href="/index.php?controller=GrupoControle">Grupos de Controle</a></li>
                <li><a href="/index.php?controller=Lancamento">Lançamentos</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer>
        <p>&copy; 2025 Sistema Financeiro</p>
    </footer>
</body>
</html>
