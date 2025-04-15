<?php
require_once '../models/Banco.php';

function listar_bancos($pdo) {
    $model = new Banco($pdo);
    $bancos = $model->listarTodos();
    include '../views/bancos/index.php';
}

function banco_novo($pdo) {
    include '../views/bancos/form.php';
}

function banco_editar($pdo) {
    $model = new Banco($pdo);
    $banco = $model->buscarPorId($_GET['id']);
    include '../views/bancos/form.php';
}

function banco_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM bancos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=bancos');
    exit;
}

function banco_salvar($pdo) {
    if (isset($_POST['id']) && $_POST['id']) {
        $stmt = $pdo->prepare("UPDATE bancos SET descricao = ?, numero = ?, conta = ?, titular = ?, pix = ?, ativo = ? WHERE id = ?");
        $stmt->execute([
            $_POST['descricao'],
            $_POST['numero'],
            $_POST['conta'],
            $_POST['titular'],
            $_POST['pix'],
            $_POST['ativo'] ?? 1,
            $_POST['id']
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO bancos (descricao, numero, conta, titular, pix, ativo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['descricao'],
            $_POST['numero'],
            $_POST['conta'],
            $_POST['titular'],
            $_POST['pix'],
            $_POST['ativo'] ?? 1
        ]);
    }
    header('Location: ?path=bancos');
    exit;
}
