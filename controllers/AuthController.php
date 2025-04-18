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

function esqueci_senha($pdo) {
    $titulo = "Recuperar Senha";
    $conteudo = __DIR__ . '/../views/auth/esqueci_senha.php';
    include __DIR__ . '/../views/layout.php';
}
  
function esqueci_senha_post($pdo) {
$email = trim($_POST['email'] ?? '');
$nome = trim($_POST['nome'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND nome = ?");
$stmt->execute([$email, $nome]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: ?path=esqueci_senha&erro=1");
    exit;
}

header("Location: ?path=redefinir_senha&id=$usuario[id]");
exit;
}

function redefinir_senha($pdo) {
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

$titulo = "Nova Senha";
$conteudo = __DIR__ . '/../views/auth/redefinir_senha.php';
include __DIR__ . '/../views/layout.php';
}

function salvar_nova_senha($pdo) {
$id = $_POST['id'];
$senha = $_POST['senha'];
$confirmar = $_POST['confirmar'];

if ($senha !== $confirmar) {
    die("As senhas não coincidem.");
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE id = ?");
$stmt->execute([$hash, $id]);

header("Location: ?path=login");
exit;
}
