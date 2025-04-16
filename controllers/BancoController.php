<?php
require_once '../models/Banco.php';

function nullIfEmpty($value) {
    return trim($value) === '' ? null : $value;
}

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
    $registro = $model->buscarPorId($_GET['id']);
    include '../views/bancos/form.php';
}

function banco_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM bancos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=bancos');
    exit;
}

function banco_salvar($pdo) {
    $id = $_POST['id'] ?? null;

    if (!empty($id)) {
        // Atualizar
        $stmt = $pdo->prepare("UPDATE bancos SET descricao = ?, numero = ?, conta = ?, titular = ?, pix = ?, ativo = ? WHERE id = ?");
        $stmt->execute([
            $_POST['descricao'],
            nullIfEmpty($_POST['numero']),
            nullIfEmpty($_POST['conta']),
            nullIfEmpty($_POST['titular']),
            nullIfEmpty($_POST['pix']),
            isset($_POST['ativo']) ? 1 : 0,
            $id
        ]);
    } else {
        // Inserir novo
        $stmt = $pdo->prepare("INSERT INTO bancos (descricao, numero, conta, titular, pix, ativo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['descricao'],
            nullIfEmpty($_POST['numero']),
            nullIfEmpty($_POST['conta']),
            nullIfEmpty($_POST['titular']),
            nullIfEmpty($_POST['pix']),
            isset($_POST['ativo']) ? 1 : 0
        ]);
    }

    header('Location: ?path=bancos');
    exit;
}
