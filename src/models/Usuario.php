<?php

namespace App\Models;

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nome;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public static function all($db) {
        $query = "SELECT * FROM usuarios";
        $stmt = $db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\Models\Usuario');
    }

    public static function find($db, $id) {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchObject('App\Models\Usuario');
    }

    public function save() {
        if ($this->id) {
            $query = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome, $this->email, $this->id]);
        } else {
            $query = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$this->nome, $this->email]);
            $this->id = $this->conn->lastInsertId();
        }
    }

    public function delete() {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
    }
}
?>