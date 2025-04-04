<?php
require_once '../models/Cartoes_credito.php';

function listar_cartoes_credito($pdo) {
    $cartoes_credito_model = new Cartoes_credito($pdo);
    $cartoes_credito = $cartoes_credito_model->listarTodos();
    include '../views/cartoes_credito/index.php';
}
?>
function salvar_cartoes_credito($pdo) {
    $stmt = $pdo->prepare("INSERT INTO cartoes_credito (descricao, fechamento, vencimento, limite, banco_id, ativo) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute([$_POST['descricao'], $_POST['fechamento'], $_POST['vencimento'], $_POST['limite'], $_POST['banco_id']]);
    header('Location: ?path=cartoes_credito');
    exit;
}
