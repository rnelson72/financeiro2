<?php

class GrupoControle {
    private $pdo;

    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM grupo_controle WHERE ativo = 1 ORDER BY descricao");
        return $stmt->fetchAll();
    }
}
?>