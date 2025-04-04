<?php
require_once '../models/Banco.php';

function listarBancos($pdo) {
    $bancoModel = new Banco($pdo);
    $bancos = $bancoModel->listarTodos();
    include '../views/bancos/index.php';
}
?>