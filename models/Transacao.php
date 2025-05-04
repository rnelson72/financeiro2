<?php
class Transacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM transacoes ORDER BY ordem, nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transacoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO transacoes (codigo, nome, rota, componente, acao, grupo_id, tipo, icone, ordem, visivel_no_menu, ativo)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $dados['codigo'],
            $dados['nome'],
            $dados['rota'],
            $dados['componente'],
            $dados['acao'],
            $dados['grupo_id'],
            $dados['tipo'],
            $dados['icone'],
            $dados['ordem'] ?? 0,
            $dados['visivel_no_menu'] ?? 1,
            $dados['ativo'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }

    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare(
            "UPDATE transacoes SET codigo = ?, nome = ?, rota = ?, componente = ?, acao = ?, grupo_id = ?, tipo = ?, icone = ?, ordem = ?, visivel_no_menu = ?, ativo = ? WHERE id = ?"
        );
        return $stmt->execute([
            $dados['codigo'],
            $dados['nome'],
            $dados['rota'],
            $dados['componente'],
            $dados['acao'],
            $dados['grupo_id'],
            $dados['tipo'],
            $dados['icone'],
            $dados['ordem'] ?? 0,
            $dados['visivel_no_menu'] ?? 0,
            $dados['ativo'] ?? 0,
            $id
        ]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM transacoes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function buscarPorRota($rota)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transacoes WHERE rota = ? AND ativo = 1");
        $stmt->execute([$rota]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // [No futuro] verifica permissão, etc
    public function usuarioTemPermissao($usuarioId, $transacaoId)
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM permissoes_usuario_transacao WHERE usuario_id = ? AND transacao_id = ?");
        $stmt->execute([$usuarioId, $transacaoId]);
        return (bool) $stmt->fetchColumn();
    }
}
?>