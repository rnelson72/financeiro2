<?php
require_once '../models/Contas_contabeis.php';

function listar_contas_contabeis($pdo) {
    $contas_contabeis_model = new Contas_contabeis($pdo);
    $contas_contabeis = $contas_contabeis_model->listarTodos();
    include '../views/contas_contabeis/index.php';
}

function salvar_contas_contabeis($pdo) {
    $stmt = $pdo->prepare("INSERT INTO contas_contabeis (descricao, tipo, ativo) VALUES (?, ?, 1)");
    $stmt->execute([$_POST['descricao'], $_POST['tipo']]);
    header('Location: ?path=contas_contabeis');
    exit;
}
?>