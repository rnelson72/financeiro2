<?php
class Fatura {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todas as faturas (com eventual filtro)
    public function listarTodos($filtros = []) {
        $sql = "SELECT f.*, cartao.descricao as cartao_nome FROM fatura f INNER JOIN cartao ON cartao.id = f.cartao_id WHERE 1=1";
        $params = [];
        
        if (isset($filtros['cartao_id'])) {
            $sql .= " AND f.cartao_id = ?";
            $params[] = $filtros['cartao_id'];
        }
        $sql .= " ORDER BY f.data_vencimento DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar uma fatura pelo ID
    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT f.*, cartao.descricao as cartao_nome FROM fatura f INNER JOIN cartao ON cartao.id = f.cartao_id WHERE f.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserir nova fatura
    public function inserir($dados) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO fatura (cartao_id, data_fechamento, data_vencimento, valor_total, valor_pago, status, movimentacao_id) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $dados['cartao_id'],
            $dados['data_fechamento'],
            $dados['data_vencimento'],
            $dados['valor_total'],
            $dados['valor_pago'] ?? null,
            $dados['status'] ?? 'Aberta',
            $dados['movimentacao_id'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }

    // Atualizar fatura existente
    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare(
            "UPDATE fatura SET 
                cartao_id = ?, 
                data_fechamento = ?, 
                data_vencimento = ?, 
                valor_total = ?, 
                valor_pago = ?, 
                status = ?, 
                movimentacao_id = ?
             WHERE id = ?"
        );
        $stmt->execute([
            $dados['cartao_id'],
            $dados['data_fechamento'],
            $dados['data_vencimento'],
            $dados['valor_total'],
            $dados['valor_pago'] ?? null,
            $dados['status'] ?? 'Aberta',
            $dados['movimentacao_id'] ?? null,
            $id
        ]);
    }

    // Excluir fatura
    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM fatura WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>