<?php
$path = $_GET['path'] ?? '';
require_once '../config/database.php';

switch ($path) {
    case 'controles':                     require_once '../controllers/ControleController.php';         listar_controles($pdo); break;
    case 'controle_novo':                 require_once '../controllers/ControleController.php';         novo_controle($pdo); break;
    case 'controle_editar':               require_once '../controllers/ControleController.php';         editar_controle($pdo); break;
    case 'controle_excluir':              require_once '../controllers/ControleController.php';         excluir_controle($pdo); break;
    case 'controle_salvar':               require_once '../controllers/ControleController.php';         salvar_controle($pdo); break;
    case 'controle_lancamentos':          require_once '../controllers/ControleController.php';         lancamentos_por_controle($pdo); break;
    case 'controle_novo_lancamento':      require_once '../controllers/ControleController.php';         novo_lancamento($pdo); break;
    case 'controle_editar_lancamento':    require_once '../controllers/ControleController.php';         editar_lancamento($pdo); break;
    case 'controle_salvar_lancamento':    require_once '../controllers/ControleController.php';         salvar_lancamento($pdo); break;
    case 'controle_excluir_lancamento':   require_once '../controllers/ControleController.php';         excluir_lancamento($pdo); break;
    case 'grupo_excluir':                 require_once '../controllers/ControleController.php';         excluir_grupo($pdo); break;
    case 'bancos':                        require_once '../controllers/BancoController.php';            listar_bancos($pdo); break;
    case 'banco_novo':                    require_once '../controllers/BancoController.php';            banco_novo($pdo); break;
    case 'banco_editar':                  require_once '../controllers/BancoController.php';            banco_editar($pdo); break;
    case 'banco_excluir':                 require_once '../controllers/BancoController.php';            banco_excluir($pdo); break;
    case 'banco_salvar':                  require_once '../controllers/BancoController.php';            banco_salvar($pdo); break;
    case 'cartao':                        require_once '../controllers/CartaoController.php';           listar_cartoes($pdo); break;
    case 'cartao_salvar':                 require_once '../controllers/CartaoController.php';           cartao_salvar($pdo); break;
    case 'migrar_cartoes':                require_once '../controllers/MigracaoController.php';         migrar_cartoes($pdo); break;
       
    default: echo '<h1>Sistema de Controle</h1>'; break;
}
?>
