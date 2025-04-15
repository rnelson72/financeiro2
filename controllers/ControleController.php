<?php
require_once '../models/GrupoControle.php';
require_once '../models/Controle.php';

function listar_controles($pdo) {
    $grupoModel = new GrupoControle($pdo);
    $controleModel = new Controle($pdo);
    $grupos = $grupoModel->listarTodos();
    $controles = $controleModel->listarTodosComSaldo();

    $titulo = "Consulta de Controles";
    $conteudo = __DIR__ . '/../views/controle/index.php';
    include __DIR__ . '/../views/layout.php';
}

function editar_controle($pdo) {
    $id = $_GET['id'] ?? null;
    $stmt = $pdo->prepare('SELECT * FROM controle WHERE id = ?');
    $stmt->execute([$id]);
    $registro = $stmt->fetch();

    $grupoModel = new GrupoControle($pdo);
    $grupos = $grupoModel->listarTodos();

    $titulo = "Editar Controle";
    $conteudo = __DIR__ . '/../views/controle/form.php';
    include __DIR__ . '/../views/layout.php';
}

function salvar_controle($pdo) {
    $id = $_POST['id'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $grupo_id = $_POST['grupo_id'] ?? null;
    $novo_grupo = trim($_POST['novo_grupo'] ?? '');

    if ($novo_grupo !== '') {
        $stmt = $pdo->prepare("INSERT INTO grupo_controle (nome, ativo) VALUES (?, 1)");
        $stmt->execute([$novo_grupo]);
        $grupo_id = $pdo->lastInsertId();
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE controle SET descricao = ?, grupo_id = ? WHERE id = ?");
        $stmt->execute([$descricao, $grupo_id, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO controle (descricao, grupo_id, ativo) VALUES (?, ?, 1)");
        $stmt->execute([$descricao, $grupo_id]);
    }

    header('Location: ?path=controles');
    exit;
}

function novo_controle($pdo) {
    $registro = [];
    $grupoModel = new GrupoControle($pdo);
    $grupos = $grupoModel->listarTodos();

    $titulo = "Novo Controle";
    $conteudo = __DIR__ . '/../views/controle/form.php';
    include __DIR__ . '/../views/layout.php';
}

function lancamentos_por_controle($pdo) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM controle WHERE id = ?");
    $stmt->execute([$id]);
    $controle = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM lancamentos WHERE controle_id = ? ORDER BY data DESC");
    $stmt->execute([$id]);
    $lancamentos = $stmt->fetchAll();

    $titulo = "Lançamentos do Controle";
    $conteudo = __DIR__ . '/../views/controle/lancamentos.php';
    include __DIR__ . '/../views/layout.php';
}


function novo_lancamento($pdo) {
    $registro = [];

    $titulo = "Novo Lançamento";
    $conteudo = __DIR__ . '/../views/controle/form_lancamento.php';
    include __DIR__ . '/../views/layout.php';
}

function editar_lancamento($pdo) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM lancamentos WHERE id = ?");
    $stmt->execute([$id]);
    $registro = $stmt->fetch();

    $titulo = "Editar Lançamento";
    $conteudo = __DIR__ . '/../views/controle/form_lancamento.php';
    include __DIR__ . '/../views/layout.php';
}

function salvar_lancamento($pdo) {
    $id = $_POST['id'] ?? '';
    $controle_id = $_POST['controle_id'];
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    if ($id) {
        $stmt = $pdo->prepare("UPDATE lancamentos SET data = ?, descricao = ?, valor = ? WHERE id = ?");
        $stmt->execute([$data, $descricao, $valor, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO lancamentos (controle_id, data, descricao, valor) VALUES (?, ?, ?, ?)");
        $stmt->execute([$controle_id, $data, $descricao, $valor]);
    }

    header("Location: ?path=controle_lancamentos&id=$controle_id");
    exit;
}

function excluir_lancamento($pdo) {
    $id = $_GET['id'];
    $ctrl = $_GET['ctrl'];
    $stmt = $pdo->prepare("DELETE FROM lancamentos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: ?path=controle_lancamentos&id=$ctrl");
    exit;
}
?>