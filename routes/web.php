<?php
$path = $_GET['path'] ?? '';

require_once '../config/database.php';
require_once '../config/autoload.php';

if (!empty($path)) {
    $model = new Transacao($pdo);
    $transacao = $model->buscarPorRota($path);
    if ($transacao) {
        $componente = $transacao['componente'] ?? null;
        $acao = $transacao['acao'] ?? null;
        if (!isset($_SESSION['usuario_id']) || !(new Permissao($pdo))->usuarioTemPermissao($_SESSION['usuario_id'], $transacao['id']) ) {
            if ($componente && file_exists("../controllers/" . $componente)) {
                require_once "../controllers/" . $componente;
                $classe = str_replace('.php', '',$componente);
                $acao = $transacao['acao'];
                if (class_exists($classe) && method_exists($classe, $acao)) {
                    // Migrations temporariamente carregam Legado
                    if ($classe == "MigrationController") {
                        require_once '../config/legado.php';
                        $ctrl = new MigrationController($pdo, $pdoLegado);
                        $ctrl->$acao();
                    } else {
                        $ctrl = new $classe($pdo);
                        $ctrl->$acao();
                    }
                } else {
                    // fallback (opcional): um método padrão do controller
                    $_SESSION['mensagem_erro'] = 'Método não encontrado: ['.$componente.']';
                    header("Location: ?path=dashboard");
                }
            } else {
                // componente não encontrado
                $_SESSION['mensagem_erro'] = 'Componente não encontrado: ['.$acao.']';
                $titulo = 'Erro de Roteamento';
                $conteudo = '../views/erro_route.php';
                include '../views/layout.php';
            }
        } else {
            $_SESSION['mensagem_erro'] = 'Você não tem permissão para acessar esta funcionalidade.';
            header("Location: ?path=dashboard");
        } 
    } else {
        // Rota não existente; mostra dashboard padrão
        $_SESSION['mensagem_erro'] = 'Rota não existente: ['.$path.']';
        header("Location: ?path=dashboard");
    } 

} else {
    // Path não informado - login
    header('Location: ?path=login');
}