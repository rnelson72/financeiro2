<?php
$path = $_GET['path'] ?? '';
session_start();

switch ($path) {
    case 'usuarios_novo':
        include '../views/usuarios/form.php';
        break;
    case 'usuarios_salvar':
        require_once '../controllers/UsuariosController.php';
        salvar_usuarios($pdo);
        break;
    case 'bancos_novo':
        include '../views/bancos/form.php';
        break;
    case 'bancos_salvar':
        require_once '../controllers/BancosController.php';
        salvar_bancos($pdo);
        break;
    case 'contas_contabeis_novo':
        include '../views/contas_contabeis/form.php';
        break;
    case 'contas_contabeis_salvar':
        require_once '../controllers/Contas_contabeisController.php';
        salvar_contas_contabeis($pdo);
        break;
    case 'cartoes_credito_novo':
        include '../views/cartoes_credito/form.php';
        break;
    case 'cartoes_credito_salvar':
        require_once '../controllers/Cartoes_creditoController.php';
        salvar_cartoes_credito($pdo);
        break;
    case 'usuarios':
        require_once '../controllers/UsuariosController.php';
        listar_usuarios($pdo);
        break;
    case 'bancos':
        require_once '../controllers/BancosController.php';
        listar_bancos($pdo);
        break;
    case 'contas_contabeis':
        require_once '../controllers/Contas_contabeisController.php';
        listar_contas_contabeis($pdo);
        break;
    case 'cartoes_credito':
        require_once '../controllers/Cartoes_creditoController.php';
        listar_cartoes_credito($pdo);
        break;
    default:
        echo '<h1>Sistema PHP em construção...</h1>';
        break;
}
?>