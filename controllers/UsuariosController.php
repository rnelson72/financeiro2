<?php
require_once '../models/Usuarios.php';

function listar_usuarios($pdo) {
    $usuarios_model = new Usuarios($pdo);
    $usuarios = $usuarios_model->listarTodos();
    include '../views/usuarios/index.php';
}

function salvar_usuarios($pdo) {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, ativo) VALUES (?, ?, ?, 1)");
    $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['senha']]);
    header('Location: ?path=usuarios');
    exit;
}
?>