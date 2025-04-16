<?php
class Cartao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM cartao ");
        return $stmt->fetchAll();
    }
}
?>