<?php
class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM categoria ORDER BY descricao");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO categoria (conta, descricao, tipo, ativo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dados['conta'], $dados['descricao'], $dados['tipo'], $dados['ativo']]);
    }

    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE categoria SET conta = ?, descricao = ?, tipo = ?, ativo = ? WHERE id = ?");
        $stmt->execute([$dados['conta'], $dados['descricao'], $dados['tipo'], $dados['ativo'], $id]);
    }
} 
?>