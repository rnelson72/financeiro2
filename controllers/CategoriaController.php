<?php
require_once '../models/Categoria.php';

function listar_categorias($pdo) {
    $model = new Categoria($pdo);
    $categorias = $model->listarTodos();

    $titulo = 'Categorias';
    $conteudo = '../views/categoria/index.php';
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

function categoria_novo($pdo) {
    $registro = [];

    $titulo = 'Nova Categoria';
    $conteudo = '../views/categoria/form.php';
    include '../views/layout.php';
}

function categoria_editar($pdo) {
    $model = new Categoria($pdo);
    $registro = $model->buscarPorId($_GET['id']);

    $titulo = 'Editar Categoria';
    $conteudo = '../views/categoria/form.php';
    include '../views/layout.php';
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
        'conta'      => $_POST['conta'],
        'descricao'  => $_POST['descricao'],
        'tipo'       => $_POST['tipo'],
        'ativo'      => isset($_POST['ativo']) ? 1 : 0
    ];

    $id = $_POST['id'] ?? null;
    if ($model->contaJaExiste($dados['conta'], $id)) {
        $erro = "Já existe uma categoria com essa conta.";
        $registro = $dados;
        $titulo = empty($id) ? 'Nova Categoria' : 'Editar Categoria';
        $conteudo = '../views/categoria/form.php';
        include '../views/layout.php';
        return;
    }

    if ($id) {
        $model->atualizar($id, $dados);
    } else {
        $model->inserir($dados);
    }

    header('Location: ?path=categoria');
    exit;
}
