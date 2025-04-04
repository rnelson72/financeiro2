<?php
class Usuarios {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios WHERE ativo = 1");
        return $stmt->fetchAll();
    }
}
?>