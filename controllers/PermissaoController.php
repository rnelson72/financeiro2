<?php

class PermissaoController extends ControllerBase
{
    protected $modelClass = 'Permissao';
    protected $viewPath = 'permissao';

    public function listar()
    {
        $model = new Permissao($this->pdo);
        $modelUsuario = new Usuario($this->pdo);
        $usuarios = $modelUsuario->listarTodos();
        $transacaoModel = new Transacao($this->pdo);
        $transacoes = $transacaoModel->listarTodos();
        $modelMenu = new Menu($this->pdo);
        $grupos_menu = $modelMenu->listarTodos();
        $mensagem_sucesso = null;

        // Recupera o usuário, seja do POST (form de seleção/salvar), seja do GET (permite recarregar e manter selecionado)
        $usuario_id = $_POST['usuario_id'] ?? $_GET['usuario_id'] ?? null;

        // Só executa alteração (delete/insert) se veio um POST qualquer (troca de usuário ou salvar permissões)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id']) && isset($_POST['salvar'])) {
            // Remove permissões antigas desse usuário
            $model->excluirPorUsuario($usuario_id);

            // Coleta permissões marcadas (pode estar vazio, se desmarcar tudo)
            $novas_permissoes = $_POST['permissoes'] ?? [];

            // Insere as novas permissões
            $model->incluirPorUsuario($usuario_id, $novas_permissoes);

            $mensagem_sucesso = 'Permissões atualizadas com sucesso!';
        }

        // Sempre carrega as permissões atuais desse usuário (ou array vazio)
        $permissoes_usuario = [];
        if (!empty($usuario_id)) {
            $permissoes_usuario = $model->listarPorUsuario($usuario_id);
        }

        $scriptsHead = ['https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
                        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css'];
        $titulo = 'Permissões das Transações';
        $conteudo = '../views/permissao/index.php';
        include '../views/layout.php';
    }

}