<?php
require_once '../models/Cartao.php';

function listar_cartoes($pdo) {
    $cartao_model = new Cartao($pdo);
    $cartao_model = $cartao_model->listarTodos();
    include '../views/cartao/index.php';
}

function salvar_cartao($pdo) {
    $stmt = $pdo->prepare("INSERT INTO cartao (descricao, fechamento, vencimento, limite, banco_id, ativo) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$_POST['descricao'], $_POST['fechamento'], $_POST['vencimento'], $_POST['limite'], $_POST['banco_id']]);
    header('Location: ?path=cartao');
    exit;
}
?>