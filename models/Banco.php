<?php
class Banco {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM bancos WHERE ativo = 1");
        return $stmt->fetchAll();
    }
}
?>