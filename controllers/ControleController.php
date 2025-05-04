<?php

class ControleController extends ControllerBase
{
    protected $modelClass = 'Controle';
    protected $viewPath = 'controle';

    // Listagem dos controles (com saldo e agrupamento)
    public function listar()
    {
        $model = new Controle($this->pdo);
        $grupos = $model->listarGrupos();
        $controles = $model->listarTodosComSaldo();

        $titulo = "Consulta de Controles";
        $conteudo = '../views/controle/index.php';
        include '../views/layout.php';
    }

    public function novo()
    {
        $registro = [];
        $model = new Controle($this->pdo);
        $grupos = $model->listarGrupos();

        $titulo = "Novo Controle";
        $conteudo = '../views/controle/form.php';
        include '../views/layout.php';
    }

    // Editar controle
    public function editar()
    {
        $id = $_GET['id'];
        $model = new Controle($this->pdo);
        $registro = $model->buscarControlePorId($id);
        $grupos = $model->listarGrupos();

        $titulo = "Editar Controle";
        $conteudo = '../views/controle/form.php';
        include '../views/layout.php';
    }

    // Salvar controle (inserir/atualizar + grupo extra opcional)
    public function salvar()
    {
        $model = new Controle($this->pdo);
        $dados = $_POST;

        if (!empty(trim($_POST['novo_grupo'] ?? ''))) {
            $dados['grupo_id'] = $model->salvarGrupo($_POST['novo_grupo']);
        }

        $dados['ativo'] = isset($_POST['ativo']) ? 1 : 0;
        $model->salvarControle($dados);

        header('Location: ?path=controle');
        exit;
    }

    // Excluir controle
    public function excluir()
    {
        $id = $_GET['id'];
        $model = new Controle($this->pdo);
        $model->excluirControle($id);
        header('Location: ?path=controle');
        exit;
    }

    // Excluir grupo de controle
    public function excluir_grupo()
    {
        $id = $_GET['id'];
        $model = new Controle($this->pdo);
        if (!$model->excluirGrupo($id)) {
            die('Não é possível excluir o grupo: ele está em uso por controles.');
        }
        header('Location: ?path=controle');
        exit;
    }

    // Lançamentos do controle
    public function lancamentos()
    {
        $id = $_GET['controle_id'];
        $model = new Controle($this->pdo);
        $controle = $model->buscarControlePorId($id);
        $lancamentos = $model->listarLancamentos($id);

        $titulo = "Lançamentos do Controle";
        $conteudo = '../views/controle/lancamento/index.php';
        include '../views/layout.php';
    }

    // Novo lançamento
    public function novo_lancamento()
    {
        $registro = [];
        $titulo = "Novo Lançamento";
        $conteudo = '../views/controle/lancamento/form.php';
        include '../views/layout.php';
    }

    // Editar lançamento
    public function editar_lancamento()
    {
        $id = $_GET['id'];
        $model = new Controle($this->pdo);
        $registro = $model->buscarLancamentoPorId($id);

        $titulo = "Editar Lançamento";
        $conteudo = '../views/controle/lancamento/form.php';
        include '../views/layout.php';
    }

    // Salvar lançamento
    public function salvar_lancamento()
    {
        $model = new Controle($this->pdo);
        $model->salvarLancamento($_POST);
        $cid = $_POST['controle_id'];
        header("Location: ?path=controle_lancamentos&controle_id=$cid");
        exit;
    }

    // Excluir lançamento
    public function excluir_lancamento()
    {
        $id   = $_GET['id'];
        $ctrl = $_GET['ctrl'];
        $model = new Controle($this->pdo);
        $model->excluirLancamento($id);
        header("Location: ?path=controle_lancamentos&controle_id=$ctrl");
        exit;
    }
}