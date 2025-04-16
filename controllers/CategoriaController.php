<?php
// =======================
// CONTROLLER - CategoriaController.php
// =======================
require_once '../models/Categoria.php';

function listar_categorias($pdo) {
    $model = new Categoria($pdo);
    $categorias = $model->listarTodos();
    include '../views/categoria/index.php';
}

function categoria_novo($pdo) {
    $registro = [];
    include '../views/categoria/form.php';
}

function categoria_editar($pdo) {
    $model = new Categoria($pdo);
    $registro = $model->buscarPorId($_GET['id']);
    include '../views/categoria/form.php';
}

function categoria_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM categoria WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=categoria');
    exit;
}

function categoria_salvar($pdo) {
    $model = new Categoria($pdo);
    $dados = [
        'conta' => $_POST['conta'],
        'descricao' => $_POST['descricao'],
        'tipo' => $_POST['tipo'],
        'ativo' => isset($_POST['ativo']) ? 1 : 0
    ];

    if (!empty($_POST['id'])) {
        $model->atualizar($_POST['id'], $dados);
    } else {
        $model->inserir($dados);
    }
    header('Location: ?path=categoria');
    exit;
}
