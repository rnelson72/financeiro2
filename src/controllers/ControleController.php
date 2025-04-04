<?php

namespace App\Controllers;

use App\Models\Controle;
use App\Models\GrupoControle;
use App\Models\Lancamento;

class ControleController {
    private $db;
    private $controle;

    public function __construct($db) {
        $this->db = $db;
        $this->controle = new Controle($db);
    }

    public function index() {
        $controles = Controle::all($this->db);
        require_once(__DIR__ . '/../views/controles/index.php');
    }

    public function create() {
        $grupos = GrupoControle::all($this->db);
        require_once(__DIR__ . '/../views/controles/create.php');
    }

    public function store() {
        $this->controle->nome = $_POST['nome'];
        $this->controle->grupo_controle_id = $_POST['grupo_controle_id'];
        $this->controle->save();
        header('Location: /index.php?controller=Controle');
    }

    public function edit($id) {
        $controle = Controle::find($this->db, $id);
        $grupos = GrupoControle::all($this->db);
        require_once(__DIR__ . '/../views/controles/edit.php');
    }

    public function update($id) {
        $controle = Controle::find($this->db, $id);
        $controle->nome = $_POST['nome'];
        $controle->grupo_controle_id = $_POST['grupo_controle_id'];
        $controle->save();
        header('Location: /index.php?controller=Controle');
    }

    public function destroy($id) {
        $controle = Controle::find($this->db, $id);
        $controle->delete();
        header('Location: /index.php?controller=Controle');
    }
}
?>