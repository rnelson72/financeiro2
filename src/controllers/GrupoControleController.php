<?php

namespace App\Controllers;

use App\Models\GrupoControle;

class GrupoControleController {
    private $db;
    private $grupoControle;

    public function __construct($db) {
        $this->db = $db;
        $this->grupoControle = new GrupoControle($db);
    }

    public function index() {
        $grupos = GrupoControle::all($this->db);
        require_once(__DIR__ . '/../views/grupo_controle/index.php');
    }

    public function create() {
        require_once(__DIR__ . '/../views/grupo_controle/create.php');
    }

    public function store() {
        $this->grupoControle->nome = $_POST['nome'];
        $this->grupoControle->save();
        header('Location: /index.php?controller=GrupoControle');
    }

    public function edit($id) {
        $grupo = GrupoControle::find($this->db, $id);
        require_once(__DIR__ . '/../views/grupo_controle/edit.php');
    }

    public function update($id) {
        $grupo = GrupoControle::find($this->db, $id);
        $grupo->nome = $_POST['nome'];
        $grupo->save();
        header('Location: /index.php?controller=GrupoControle');
    }

    public function destroy($id) {
        $grupo = GrupoControle::find($this->db, $id);
        $grupo->delete();
        header('Location: /index.php?controller=GrupoControle');
    }
}
?>