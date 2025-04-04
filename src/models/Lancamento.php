<?php

namespace App\Models;

class Lancamento {
    private $conn;
    private $table_name = "lancamentos";

    public $id;
    public $controle_id;
    public $valor;
    public $data;

    public function __construct($db) {
        $this->conn = $db;
    }

    public static function all($db) {
        $query = "SELECT * FROM lancamentos";
        $stmt = $db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\Models\Lancamento');
    }

    public static function find($db, $id) {
        $query = "SELECT * FROM lancamentos WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchObject('App\Models\Lancamento');
    }

    public static function findByControle($db, $controle_id) {
        $query = "SELECT * FROM lancamentos WHERE controle_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$controle_id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\Models\Lancamento');
    }

    public function save() {
        if ($this->id) {
            $query = "UPDATE lancamentos SET controle_id = ?, valor = ?, data = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->controle_id, $this->valor, $this->data, $this->id]);
        } else {
            $query = "INSERT INTO lancamentos (controle_id, valor, data) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->controle_id, $this->valor, $this->data]);
            $this->id = $this->conn->lastInsertId();
        }
    }

    public function delete() {
        $query = "DELETE FROM lancamentos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
    }
}
?>