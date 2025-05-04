<?php
class Categoria {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos($filtro = '') {
        if (empty($filtro)) {
            $stmt = $this->pdo->query("SELECT * FROM categoria ORDER BY conta");
        } else {
            $stmt = $this->pdo->query("SELECT * FROM categoria WHERE {$filtro} ORDER BY conta");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        if ($this->contaJaExiste($dados['conta'])) {
            throw new Exception("Conta já existente.");
        }
        $stmt = $this->pdo->prepare("INSERT INTO categoria (conta, descricao, tipo, ativo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dados['conta'], $dados['descricao'], $dados['tipo'], $dados['ativo']]);
    }

    public function atualizar($id, $dados) {
        if ($this->contaJaExiste($dados['conta'], $id)) {
            throw new Exception("Conta já existente.");
        }
        $stmt = $this->pdo->prepare("UPDATE categoria SET conta = ?, descricao = ?, tipo = ?, ativo = ? WHERE id = ?");
        $stmt->execute([$dados['conta'], $dados['descricao'], $dados['tipo'], $dados['ativo'], $id]);
    }

    public function contaJaExiste($conta, $id = null) {
        $sql = "SELECT id FROM categoria WHERE conta = ?" . ($id ? " AND id != ?" : "");
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($id ? [$conta, $id] : [$conta]);
        return $stmt->fetch() !== false;
    }
} 
?>
