<?php
// models/Controle.php

class Controle {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodosComSaldo() {
        $stmt = $this->pdo->query("SELECT * FROM vw_controle 
            ORDER BY CASE WHEN ativo = 1 AND grupo_id IS NULL THEN 0
                          WHEN ativo = 1 AND grupo_id IS NOT NULL THEN 1
                          ELSE 2 END,
                     grupo, descricao");
        return $stmt->fetchAll();
    }

    public function listarGrupos() {
        $stmt = $this->pdo->query("SELECT * FROM grupo_controle WHERE ativo = 1 ORDER BY descricao");
        return $stmt->fetchAll();
    }

    public function buscarControlePorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM controle WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function salvarControle($dados) {
        if (!empty($dados['id'])) {
            $stmt = $this->pdo->prepare("UPDATE controle SET descricao = ?, grupo_id = ?, ativo = ? WHERE id = ?");
            $stmt->execute([
                $dados['descricao'],
                $dados['grupo_id'] !== '' ? $dados['grupo_id'] : null,
                $dados['ativo'],
                $dados['id']
            ]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO controle (descricao, grupo_id, ativo) VALUES (?, ?, ?)");
            $stmt->execute([
                $dados['descricao'],
                $dados['grupo_id'] !== '' ? $dados['grupo_id'] : null,
                $dados['ativo']
            ]);
        }
    }

    public function excluirControle($id) {
        $stmt = $this->pdo->prepare("DELETE FROM controle WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function salvarGrupo($descricao) {
        $stmt = $this->pdo->prepare("INSERT INTO grupo_controle (descricao, ativo) VALUES (?, 1)");
        $stmt->execute([$descricao]);
        return $this->pdo->lastInsertId();
    }

    public function excluirGrupo($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM controle WHERE grupo_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }
        $stmt = $this->pdo->prepare("DELETE FROM grupo_controle WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    }

    // ====== LANÇAMENTOS ======

    public function listarLancamentos($controle_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lancamentos WHERE controle_id = ? ORDER BY data DESC");
        $stmt->execute([$controle_id]);
        return $stmt->fetchAll();
    }

    public function buscarLancamentoPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lancamentos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function salvarLancamento($dados) {
        if (!empty($dados['id'])) {
            $stmt = $this->pdo->prepare("UPDATE lancamentos SET data = ?, descricao = ?, valor = ? WHERE id = ?");
            $stmt->execute([
                $dados['data'],
                $dados['descricao'],
                $dados['valor'],
                $dados['id']
            ]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO lancamentos (controle_id, data, descricao, valor) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $dados['controle_id'],
                $dados['data'],
                $dados['descricao'],
                $dados['valor']
            ]);
        }
    }

    public function excluirLancamento($id) {
        $stmt = $this->pdo->prepare("DELETE FROM lancamentos WHERE id = ?");
        $stmt->execute([$id]);
    }
}