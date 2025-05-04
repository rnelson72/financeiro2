<?php

class Menu
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodos()
    {
        $stmt = $this->pdo->query("SELECT * FROM grupos_menu ORDER BY ordem, nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM grupos_menu WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO grupos_menu (nome, icone, ordem, ativo) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $dados['nome'],
            $dados['icone'],
            $dados['ordem'] ?? 0,
            $dados['ativo'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }

    public function atualizar($id, $dados)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE grupos_menu SET nome = ?, icone = ?, ordem = ?, ativo = ? WHERE id = ?"
        );
        return $stmt->execute([
            $dados['nome'],
            $dados['icone'],
            $dados['ordem'] ?? 0,
            $dados['ativo'] ?? 1,
            $id
        ]);
    }

    public function excluir($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM grupos_menu WHERE id = ?");
        return $stmt->execute([$id]);
    }
}