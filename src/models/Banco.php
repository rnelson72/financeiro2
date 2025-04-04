<?php

namespace App\Models;

use PDO;

class Banco
{
    public $id;
    public $nome;
    public $numero_agencia;
    public $numero_conta_corrente;
    public $saldo_inicial;

    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM bancos");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function find($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM bancos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public function save()
    {
        $db = Database::getInstance();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE bancos SET nome = ?, numero_agencia = ?, numero_conta_corrente = ?, saldo_inicial = ? WHERE id = ?");
            $stmt->execute([$this->nome, $this->numero_agencia, $this->numero_conta_corrente, $this->saldo_inicial, $this->id]);
        } else {
            $stmt = $db->prepare("INSERT INTO bancos (nome, numero_agencia, numero_conta_corrente, saldo_inicial) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->nome, $this->numero_agencia, $this->numero_conta_corrente, $this->saldo_inicial]);
            $this->id = $db->lastInsertId();
        }
    }

    public function delete()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM bancos WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}