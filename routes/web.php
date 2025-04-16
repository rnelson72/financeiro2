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
    case 'cartao_novo':                   require_once '../controllers/CartaoController.php';           cartao_novo($pdo); break;
    case 'cartao_editar':                 require_once '../controllers/CartaoController.php';           cartao_editar($pdo); break;
    case 'cartao_excluir':                require_once '../controllers/CartaoController.php';           cartao_excluir($pdo); break;
    case 'cartao_salvar':                 require_once '../controllers/CartaoController.php';           cartao_salvar($pdo); break;
    case 'final_cartao_modal':            require_once '../controllers/CartaoController.php';           final_cartao_modal($pdo); break;
    case 'final_cartao_salvar':           require_once '../controllers/CartaoController.php';           final_cartao_salvar($pdo); break;
    case 'final_cartao_excluir':          require_once '../controllers/CartaoController.php';           final_cartao_excluir($pdo); break;
    case 'categoria':                     require_once '../controllers/CategoriaController.php';        listar_categorias($pdo); break;
    case 'categoria_novo':                require_once '../controllers/CategoriaController.php';        categoria_novo($pdo); break;
    case 'categoria_editar':              require_once '../controllers/CategoriaController.php';        categoria_editar($pdo); break;
    case 'categoria_excluir':             require_once '../controllers/CategoriaController.php';        categoria_excluir($pdo); break;
    case 'categoria_salvar':              require_once '../controllers/CategoriaController.php';        categoria_salvar($pdo); break;   
    case 'migrar_cartoes':                require_once '../controllers/MigracaoController.php';         migrar_cartoes($pdo); break;
    case 'migrar_categoria':              require_once '../config/migrations/migrate_categoria.php';    break;
           
    default: echo '<h1>Sistema de Controle</h1>'; break;
}
?>
