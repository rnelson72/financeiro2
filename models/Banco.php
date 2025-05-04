<?php
class Banco {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM banco ORDER BY descricao");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM banco WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO banco (descricao, numero, agencia, conta, titular, pix, ativo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$dados['descricao'], $dados['numero'], $dados['agencia'], $dados['conta'], $dados['titular'], $dados['pix'], $dados['ativo']]);
    }

    public function atualizar($id, $dados) {
        // Se 'ativo' está vindo NULL ou não foi definido, define como 0
        $ativo = isset($dados['ativo']) && $dados['ativo'] !== NULL ? $dados['ativo'] : 0;
    
        $stmt = $this->pdo->prepare("UPDATE banco SET descricao = ?, numero = ?, agencia = ?, conta = ?, titular = ?, pix = ?, ativo = ? WHERE id = ?");
        $stmt->execute([
            $dados['descricao'],
            $dados['numero'],
            $dados['agencia'],
            $dados['conta'],
            $dados['titular'],
            $dados['pix'],
            $ativo,
            $id
        ]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM banco WHERE id = ?");
        $stmt->execute([$id]);
    }
}