<?php
class Cartao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("
            SELECT c.*, b.descricao AS banco_nome
            FROM cartao c
            LEFT JOIN banco b ON c.banco_id = b.id
            ORDER BY c.descricao
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cartao WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
