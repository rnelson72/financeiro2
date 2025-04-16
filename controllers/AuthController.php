<?php
require_once '../models/Auth.php';

function login($pdo) {
    $titulo = 'Login';
    $conteudo = '../views/auth/login.php';
    include '../views/layout.php';
}

function autenticar($pdo) {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $auth = new Auth($pdo);
    $usuario = $auth->validarLogin($email, $senha);

    if ($usuario) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header('Location: ?path=menu');
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
        $titulo = 'Login';
        $conteudo = '../views/auth/login.php';
        include '../views/layout.php';
    }
}

function logout() {
    session_start();
    session_destroy();
    header('Location: ?path=login');
    exit;
}
