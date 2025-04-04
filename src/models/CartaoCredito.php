<?php

namespace App\Models;

use PDO;

class CartaoCredito
{
    public $id;
    public $descricao;
    public $limite_credito;
    public $vencimento_fatura;
    public $ativo;

    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM cartoes_credito");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM cartoes_credito WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public function save()
    {
        $db = Database::getInstance();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE cartoes_credito SET descricao = ?, limite_credito = ?, vencimento_fatura = ?, ativo = ? WHERE id = ?");
            $stmt->execute([$this->descricao, $this->limite_credito, $this->vencimento_fatura, $this->ativo, $this->id]);
        } else {
            $stmt = $db->prepare("INSERT INTO cartoes_credito (descricao, limite_credito, vencimento_fatura, ativo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->descricao, $this->limite_credito, $this->vencimento_fatura, $this->ativo]);
            $this->id = $db->lastInsertId();
        }
    }

    public function delete()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM cartoes_credito WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}