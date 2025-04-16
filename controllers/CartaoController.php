<?php
require_once '../models/Cartao.php';

function nullIfEmpty($value) {
    return trim($value) === '' ? null : $value;
}

function listar_cartoes($pdo) {
    $model = new Cartao($pdo);
    $cartoes = $model->listarTodos();
    include '../views/cartao/index.php';
}

function cartao_novo($pdo) {
    $registro = [];
    $bancos = $pdo->query("SELECT id, descricao FROM bancos ORDER BY descricao")->fetchAll(PDO::FETCH_ASSOC);
    include '../views/cartao/form.php';
}

function cartao_editar($pdo) {
    $model = new Cartao($pdo);
    $registro = $model->buscarPorId($_GET['id']);
    $bancos = $pdo->query("SELECT id, descricao FROM bancos ORDER BY descricao")->fetchAll(PDO::FETCH_ASSOC);
    include '../views/cartao/form.php';
}

function cartao_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM cartao WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: ?path=cartao');
    exit;
}

function cartao_salvar($pdo) {
    if (!empty($_POST['id'])) {
        $banco_id = trim($_POST['banco_id']) !== '' ? $_POST['banco_id'] : null;
        $stmt = $pdo->prepare("UPDATE cartao SET descricao = ?, bandeira = ?, dia_fechamento = ?, dia_vencimento = ?, linha_credito = ?, banco_id = ?, ativo = ? WHERE id = ?");
        $stmt->execute([
            $_POST['descricao'],
            $_POST['bandeira'],
            $_POST['dia_fechamento'],
            $_POST['dia_vencimento'],
            $_POST['linha_credito'],
            $banco_id,
            isset($_POST['ativo']) ? 1 : 0,
            $_POST['id']
        ]);
    } else {
        $banco_id = trim($_POST['banco_id']) !== '' ? $_POST['banco_id'] : null;
        $stmt = $pdo->prepare("INSERT INTO cartao (descricao, bandeira, dia_fechamento, dia_vencimento, linha_credito, banco_id, ativo)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['descricao'],
            $_POST['bandeira'],
            $_POST['dia_fechamento'],
            $_POST['dia_vencimento'],
            $_POST['linha_credito'],
            $banco_id,
            isset($_POST['ativo']) ? 1 : 0
        ]);
    }

    header('Location: ?path=cartao');
    exit;
}

function final_cartao_modal($pdo) {
    $cartao_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM final_cartao WHERE cartao_id = ? ORDER BY final");
    $stmt->execute([$cartao_id]);
    $finais = $stmt->fetchAll(PDO::FETCH_ASSOC);
    include '../views/cartao/modal.php';
}

function final_cartao_salvar($pdo) {
    $stmt = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                           VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([
        $_POST['final'],
        $_POST['cartao_id'],
        $_POST['is_virtual'],
        nullIfEmpty($_POST['titular'])
    ]);
    exit;
}

function final_cartao_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM final_cartao WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    exit;
}
