<?php
require_once '../models/Usuario.php';

function emailJaExiste($email, $id = null) {
    // Verifica se o e-mail já está cadastrado para outro usuário
    $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
    $params = [$email];

    if ($id) {
        $sql .= " AND id != ?";
        $params[] = $id;
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchColumn() > 0;
}

function listar_usuarios($pdo) {
    $model = new Usuario($pdo);
    $usuarios = $model->listarTodos();

    $titulo = 'Usuários';
    $conteudo = '../views/usuario/index.php';
    $scriptsBody = [
        'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
        '/financeiro2/public/assets/js/datatables-init.js'
    ];
    include '../views/layout.php';
}

function usuario_novo($pdo) {
    $registro = [];

    $titulo = 'Novo Usuário';
    $conteudo = '../views/usuario/form.php';
    include '../views/layout.php';
}

function usuario_editar($pdo) {
    $model = new Usuario($pdo);
    $registro = $model->buscarPorId($_GET['id']);

    $titulo = 'Editar Usuário';
    $conteudo = '../views/usuario/form.php';
    include '../views/layout.php';
}

function usuario_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=usuario');
    exit;
}

function usuario_salvar($pdo) {
    $model = new Usuario($pdo);
    $dados = [
        'nome'  => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'] ?? '',
        'ativo' => isset($_POST['ativo']) ? 1 : 0
    ];

    // Validação de duplicidade de e-mail (opcional)
    if ($model->emailJaExiste($dados['email'], $_POST['id'] ?? null)) {
        $erro = 'Já existe um usuário com esse e-mail.';
        $registro = $dados;
        $titulo = empty($_POST['id']) ? 'Novo Usuário' : 'Editar Usuário';
        $conteudo = '../views/usuario/form.php';
        include '../views/layout.php';
        return;
    }

    if (!empty($_POST['id'])) {
        $model->atualizar($_POST['id'], $dados);
    } else {
        $model->inserir($dados);
    }

    header('Location: ?path=usuario');
    exit;
}

