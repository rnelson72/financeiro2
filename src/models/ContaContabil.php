<?php

namespace App\Models;

use PDO;

class ContaContabil
{
    public $id;
    public $descricao;
    public $tipo_conta_id;

    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM contas_contabeis");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM contas_contabeis WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public function save()
    {
        $db = Database::getInstance();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE contas_contabeis SET descricao = ?, tipo_conta_id = ? WHERE id = ?");
            $stmt->execute([$this->descricao, $this->tipo_conta_id, $this->id]);
        } else {
            $stmt = $db->prepare("INSERT INTO contas_contabeis (descricao, tipo_conta_id) VALUES (?, ?)");
            $stmt->execute([$this->descricao, $this->tipo_conta_id]);
            $this->id = $db->lastInsertId();
        }
    }

    public function delete()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM contas_contabeis WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}