<?php
class Contas_contabeis {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM contas_contabeis WHERE ativo = 1");
        return $stmt->fetchAll();
    }
}
?>