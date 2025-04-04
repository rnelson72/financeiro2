<?php

namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct($db) {
        $this->db = $db;
        $this->usuario = new Usuario($this->db);
    }

    public function index() {
        $usuarios = Usuario::all($this->db);
        require_once(__DIR__ . '/../views/usuarios/index.php');
    }

    public function create() {
        require_once(__DIR__ . '/../views/usuarios/create.php');
    }

    public function store() {
        $this->usuario->nome = $_POST['nome'];
        $this->usuario->email = $_POST['email'];
        $this->usuario->save();
        header('Location: /index.php?controller=Usuario');
    }

    public function edit($id) {
        $usuario = Usuario::find($this->db, $id);
        require_once(__DIR__ . '/../views/usuarios/edit.php');
    }

    public function update($id) {
        $usuario = Usuario::find($this->db, $id);
        $usuario->nome = $_POST['nome'];
        $usuario->email = $_POST['email'];
        $usuario->save();
        header('Location: /index.php?controller=Usuario');
    }

    public function destroy($id) {
        $usuario = Usuario::find($this->db, $id);
        $usuario->delete();
        header('Location: /index.php?controller=Usuario');
    }
}
?>