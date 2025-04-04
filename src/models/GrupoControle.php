<?php

namespace App\Models;

class GrupoControle {
    private $conn;
    private $table_name = "grupo_controle";

    public $id;
    public $nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    public static function all($db) {
        $query = "SELECT * FROM grupo_controle";
        $stmt = $db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\Models\GrupoControle');
    }

    public static function find($db, $id) {
        $query = "SELECT * FROM grupo_controle WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchObject('App\Models\GrupoControle');
    }

    public function save() {
        if ($this->id) {
            $query = "UPDATE grupo_controle SET nome = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome, $this->id]);
        } else {
            $query = "INSERT INTO grupo_controle (nome) VALUES (?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome]);
            $this->id = $this->conn->lastInsertId();
        }
    }

    public function delete() {
        $query = "DELETE FROM grupo_controle WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
    }
}
?>