<?php

namespace App\Controllers;

use App\Models\Lancamento;
use App\Models\Controle;

class LancamentoController {
    private $db;
    private $lancamento;

    public function __construct($db) {
        $this->db = $db;
        $this->lancamento = new Lancamento($db);
    }

    public function index() {
        $lancamentos = Lancamento::all($this->db);
        require_once(__DIR__ . '/../views/lancamentos/index.php');
    }

    public function create() {
        $controles = Controle::all($this->db);
        require_once(__DIR__ . '/../views/lancamentos/create.php');
    }

    public function store() {
        $this->lancamento->controle_id = $_POST['controle_id'];
        $this->lancamento->valor = $_POST['valor'];
        $this->lancamento->data = $_POST['data'];
        $this->lancamento->save();
        header('Location: /index.php?controller=Lancamento');
    }

    public function edit($id) {
        $lancamento = Lancamento::find($this->db, $id);
        $controles = Controle::all($this->db);
        require_once(__DIR__ . '/../views/lancamentos/edit.php');
    }

    public function update($id) {
        $lancamento = Lancamento::find($this->db, $id);
        $lancamento->controle_id = $_POST['controle_id'];
        $lancamento->valor = $_POST['valor'];
        $lancamento->data = $_POST['data'];
        $lancamento->save();
        header('Location: /index.php?controller=Lancamento');
    }

    public function destroy($id) {
        $lancamento = Lancamento::find($this->db, $id);
        $lancamento->delete();
        header('Location: /index.php?controller=Lancamento');
    }

    public function findByControle($controle_id) {
        $lancamentos = Lancamento::findByControle($this->db, $controle_id);
        require_once(__DIR__ . '/../views/lancamentos/index.php');
    }
}
?>