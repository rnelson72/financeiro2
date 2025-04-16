<?php
require_once '../models/Banco.php';

function listar_bancos($pdo) {
    $model = new Banco($pdo);
    $bancos = $model->listarTodos();

    $titulo = 'Bancos';
    $conteudo = '../views/banco/index.php';
    $scriptsBody = [
        'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
        '/financeiro2/public/assets/js/datatables-init.js'
    ];
    $scriptsHead = [
        'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css'
    ];
    include '../views/layout.php';
}

function banco_novo($pdo) {
    $registro = [];

    $titulo = 'Novo Banco';
    $conteudo = '../views/banco/form.php';
    include '../views/layout.php';
}

function banco_editar($pdo) {
    $model = new Banco($pdo);
    $registro = $model->buscarPorId($_GET['id']);

    $titulo = 'Editar Banco';
    $conteudo = '../views/banco/form.php';
    include '../views/layout.php';
}

function banco_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM bancos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=banco');
    exit;
}

function banco_salvar($pdo) {
    $model = new Banco($pdo);
    $dados = [
        'descricao' => $_POST['descricao'],
        'ativo'     => isset($_POST['ativo']) ? 1 : 0
    ];

    if (!empty($_POST['id'])) {
        $model->atualizar($_POST['id'], $dados);
    } else {
        $model->inserir($dados);
    }

    header('Location: ?path=banco');
    exit;
}
