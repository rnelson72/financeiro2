<?php
require_once '../models/Controle.php';

function listar_controles($pdo) {
    $model = new Controle($pdo);
    $grupos = $model->listarGrupos();
    $controles = $model->listarTodosComSaldo();

    $titulo = "Consulta de Controles";
    $conteudo = __DIR__ . '/../views/controle/index.php';
    include __DIR__ . '/../views/layout.php';
}

function novo_controle($pdo) {
    $registro = [];
    $model = new Controle($pdo);
    $grupos = $model->listarGrupos();

    $titulo = "Novo Controle";
    $conteudo = __DIR__ . '/../views/controle/form.php';
    include __DIR__ . '/../views/layout.php';
}

function editar_controle($pdo) {
    $id = $_GET['id'];
    $model = new Controle($pdo);
    $registro = $model->buscarControlePorId($id);
    $grupos = $model->listarGrupos();

    $titulo = "Editar Controle";
    $conteudo = __DIR__ . '/../views/controle/form.php';
    include __DIR__ . '/../views/layout.php';
}

function salvar_controle($pdo) {
    $model = new Controle($pdo);
    $dados = $_POST;

    if (!empty(trim($_POST['novo_grupo'] ?? ''))) {
        $dados['grupo_id'] = $model->salvarGrupo($_POST['novo_grupo']);
    }

    $dados['ativo'] = isset($_POST['ativo']) ? 1 : 0;
    $model->salvarControle($dados);

    header('Location: ?path=controles');
    exit;
}

function excluir_controle($pdo) {
    $id = $_GET['id'];
    $model = new Controle($pdo);
    $model->excluirControle($id);
    header('Location: ?path=controles');
    exit;
}

function excluir_grupo($pdo) {
    $id = $_GET['id'];
    $model = new Controle($pdo);
    if (!$model->excluirGrupo($id)) {
        die('Não é possível excluir o grupo: ele está em uso por controles.');
    }
    header('Location: ?path=controles');
    exit;
}

function lancamentos_por_controle($pdo) {
    $id = $_GET['controle_id'];
    $model = new Controle($pdo);
    $controle = $model->buscarControlePorId($id);
    $lancamentos = $model->listarLancamentos($id);

    $titulo = "Lançamentos do Controle";
    $conteudo = __DIR__ . '/../views/controle/lancamento/index.php';
    include __DIR__ . '/../views/layout.php';
}

function novo_lancamento($pdo) {
    $registro = [];
    $titulo = "Novo Lançamento";
    $conteudo = __DIR__ . '/../views/controle/lancamento/form.php';
    include __DIR__ . '/../views/layout.php';
}

function editar_lancamento($pdo) {
    $id = $_GET['id'];
    $model = new Controle($pdo);
    $registro = $model->buscarLancamentoPorId($id);

    $titulo = "Editar Lançamento";
    $conteudo = __DIR__ . '/../views/controle/lancamento/form.php';
    include __DIR__ . '/../views/layout.php';
}

function salvar_lancamento($pdo) {
    $model = new Controle($pdo);
    $model->salvarLancamento($_POST);
    $cid = $_POST['controle_id'];
    header("Location: ?path=controle_lancamentos&id=$cid");
    exit;
}

function excluir_lancamento($pdo) {
    $id = $_GET['id'];
    $ctrl = $_GET['ctrl'];
    $model = new Controle($pdo);
    $model->excluirLancamento($id);
    header("Location: ?path=controle_lancamentos&id=$ctrl");
    exit;
}