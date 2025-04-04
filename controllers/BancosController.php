<?php
require_once '../models/Bancos.php';

function listar_bancos($pdo) {
    $bancos_model = new Bancos($pdo);
    $bancos = $bancos_model->listarTodos();
    include '../views/bancos/index.php';
}
?>
function salvar_bancos($pdo) {
    $stmt = $pdo->prepare("INSERT INTO bancos (descricao, ativo) VALUES (?, 1)");
    $stmt->execute([$_POST['descricao']]);
    header('Location: ?path=bancos');
    exit;
}
