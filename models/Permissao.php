<?php

class Permissao
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function incluirPorUsuario($usuario_id, $novas_permissoes)
    {
        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO permissoes (usuario_id, transacao_id) VALUES (?, ?)"
        );
        $count = 0;
        foreach ($novas_permissoes as $transacao_id) {
            $stmt->execute([$usuario_id, $transacao_id]);
            $count++;
        }
        return $count;
    }

    public function excluirPorUsuario($usuario_id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM permissoes WHERE usuario_id = ?"
        );
        return $stmt->execute([$usuario_id]);
    }
    
    // Métodos auxiliares:
    public function listarPorUsuario($usuario_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM permissoes WHERE usuario_id = ?"
        );
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorTransacao($transacao_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM permissoes WHERE transacao_id = ?"
        );
        $stmt->execute([$transacao_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function transacoesPermitidas($usuario_id){
        $stmt = $this->pdo->prepare(
            "SELECT t.* 
             FROM transacoes t
             INNER JOIN permissoes p ON p.transacao_id = t.id
             WHERE p.usuario_id = ?"
        );
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usuarioTemPermissao($usuario_id, $transacao_id){
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM permissoes WHERE usuario_id = ? and transacao_id = ?"
        );
        $stmt->execute([$usuario_id, $transacao_id]);
        return (bool) $stmt->fetchColumn();
    }
}