<?php
class Cartoes_credito {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM cartoes_credito WHERE ativo = 1");
        return $stmt->fetchAll();
    }
}
?>