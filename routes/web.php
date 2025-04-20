<?php
$path = $_GET['path'] ?? '';
require_once '../config/database.php';
require_once '../config/autoload.php';

switch ($path) {

  // --- CONTROLE ---
    case 'controle':                    require_once '../controllers/ControleController.php';       listar_controles($pdo); break;
    case 'controle_novo':               require_once '../controllers/ControleController.php';       novo_controle($pdo); break;
    case 'controle_editar':             require_once '../controllers/ControleController.php';       editar_controle($pdo); break;
    case 'controle_excluir':            require_once '../controllers/ControleController.php';       excluir_controle($pdo); break;
    case 'controle_salvar':             require_once '../controllers/ControleController.php';       salvar_controle($pdo); break;
    case 'grupo_excluir':               require_once '../controllers/ControleController.php';       excluir_grupo($pdo); break;
// --- LANÇAMENTOS ---
    case 'controle_lancamentos':        require_once '../controllers/ControleController.php';       lancamentos_por_controle($pdo); break;
    case 'controle_novo_lancamento':    require_once '../controllers/ControleController.php';       novo_lancamento($pdo); break;
    case 'controle_editar_lancamento':  require_once '../controllers/ControleController.php';       editar_lancamento($pdo); break;
    case 'controle_salvar_lancamento':  require_once '../controllers/ControleController.php';       salvar_lancamento($pdo); break;
    case 'controle_excluir_lancamento': require_once '../controllers/ControleController.php';       excluir_lancamento($pdo); break;

    // --- BANCO ---   
    case 'banco':                       require_once '../controllers/BancoController.php';          listar_bancos($pdo); break;
    case 'banco_novo':                  require_once '../controllers/BancoController.php';          banco_novo($pdo); break;
    case 'banco_editar':                require_once '../controllers/BancoController.php';          banco_editar($pdo); break;
    case 'banco_excluir':               require_once '../controllers/BancoController.php';          banco_excluir($pdo); break;
    case 'banco_salvar':                require_once '../controllers/BancoController.php';          banco_salvar($pdo); break;

// --- CARTAO ---   
    case 'cartao':                      require_once '../controllers/CartaoController.php';         listar_cartoes($pdo); break;
    case 'cartao_novo':                 require_once '../controllers/CartaoController.php';         cartao_novo($pdo); break;
    case 'cartao_editar':               require_once '../controllers/CartaoController.php';         cartao_editar($pdo); break;
    case 'cartao_excluir':              require_once '../controllers/CartaoController.php';         cartao_excluir($pdo); break;
    case 'cartao_salvar':               require_once '../controllers/CartaoController.php';         cartao_salvar($pdo); break;
    case 'final_cartao_lista':          require_once '../controllers/CartaoController.php';         final_cartao_lista($pdo); break;
    case 'final_cartao_novo':
    case 'final_cartao_editar':         require_once '../controllers/CartaoController.php';         final_cartao_form($pdo); break;
    case 'final_cartao_salvar':         require_once '../controllers/CartaoController.php';         final_cartao_salvar($pdo); break;
    case 'final_cartao_excluir':        require_once '../controllers/CartaoController.php';         final_cartao_excluir($pdo); break;

// --- CATEGORIA ---   
    case 'categoria':                   require_once '../controllers/CategoriaController.php';      listar_categorias($pdo); break;
    case 'categoria_novo':              require_once '../controllers/CategoriaController.php';      categoria_novo($pdo); break;
    case 'categoria_editar':            require_once '../controllers/CategoriaController.php';      categoria_editar($pdo); break;
    case 'categoria_excluir':           require_once '../controllers/CategoriaController.php';      categoria_excluir($pdo); break;
    case 'categoria_salvar':            require_once '../controllers/CategoriaController.php';      categoria_salvar($pdo); break;   

// --- USUARIO ---   
    case 'usuario':                     require_once '../controllers/UsuarioController.php';        listar_usuarios($pdo); break;
    case 'usuario_novo':                require_once '../controllers/UsuarioController.php';        usuario_novo($pdo); break;
    case 'usuario_editar':              require_once '../controllers/UsuarioController.php';        usuario_editar($pdo); break;
    case 'usuario_excluir':             require_once '../controllers/UsuarioController.php';        usuario_excluir($pdo); break;
    case 'usuario_salvar':              require_once '../controllers/UsuarioController.php';        usuario_salvar($pdo); break;

// --- MIGRAÇÕES ---   
    case 'migrar_cartao':               require_once '../config/migrations/migrate_cartao.php';     break;
    case 'migrar_categoria':            require_once '../config/migrations/migrate_categoria.php';  break;
    case 'migrar_banco':                require_once '../config/migrations/migrate_banco.php';      break;
    case 'migrar_controle':             require_once '../config/migrations/migrate_controle.php';   break;
    case 'migrar_movimentacao':         require_once '../config/migrations/migrate_movimentacao.php'; break;
    
// --- AUTENTICAÇÃO ---   
    case 'login':                       require_once '../controllers/AuthController.php';           login($pdo); break;
    case 'autenticar':                  require_once '../controllers/AuthController.php';           autenticar($pdo); break;
    case 'logout':                      require_once '../controllers/AuthController.php';           logout(); break;
    case 'esqueci_senha':               require_once '../controllers/AuthController.php';           esqueci_senha($pdo); break;
    case 'esqueci_senha_post':          require_once '../controllers/AuthController.php';           esqueci_senha_post($pdo); break;
    case 'redefinir_senha':             require_once '../controllers/AuthController.php';           redefinir_senha($pdo); break;
    case 'salvar_nova_senha':           require_once '../controllers/AuthController.php';           salvar_nova_senha($pdo); break;

// --- MOVIMENTAÇÃO ---   
    case 'movimentacao':                require_once '../controllers/MovimentacaoController.php';   listar_movimentacoes($pdo);        break;
    case 'movimentacao_nova':           require_once '../controllers/MovimentacaoController.php';   movimentacao_nova($pdo);        break;
    case 'movimentacao_editar':         require_once '../controllers/MovimentacaoController.php';   movimentacao_editar($pdo);        break;
    case 'movimentacao_excluir':        require_once '../controllers/MovimentacaoController.php';   movimentacao_excluir($pdo);        break;
    case 'movimentacao_salvar':         require_once '../controllers/MovimentacaoController.php';   movimentacao_salvar($pdo);        break;
    
    default: 
        $titulo = 'Menu Principal';
        $conteudo = '../views/dashboard.php';
        include '../views/layout.php';
        break;
 }
?>
