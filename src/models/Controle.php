<?php

namespace App\Models;

class Controle {
    private $conn;
    private $table_name = "controles";

    public $id;
    public $nome;
    public $grupo_controle_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function calcularSaldo() {
        $query = "SELECT saldo FROM vw_controle WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row['saldo'];
    }

    public static function all($db) {
        $query = "SELECT * FROM vw_controle";
        $stmt = $db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\Models\Controle');
    }

    public static function find($db, $id) {
        $query = "SELECT * FROM vw_controle WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchObject('App\Models\Controle');
    }

    public function save() {
        if ($this->id) {
            $query = "UPDATE controles SET nome = ?, grupo_controle_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome, $this->grupo_controle_id, $this->id]);
        } else {
            $query = "INSERT INTO controles (nome, grupo_controle_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome, $this->grupo_controle_id]);
            $this->id = $this->conn->lastInsertId();
        }
    }

    public function delete() {
        $query = "DELETE FROM controles WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
    }
}
?>