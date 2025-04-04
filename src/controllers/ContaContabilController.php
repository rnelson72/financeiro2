<?php

namespace App\Controllers;

use App\Models\ContaContabil;

class ContaContabilController
{
    public function index()
    {
        $contas = ContaContabil::all();
        require_once '../views/contas_contabeis/index.php';
    }

    public function create()
    {
        require_once '../views/contas_contabeis/create.php';
    }

    public function store()
    {
        $conta = new ContaContabil();
        $conta->descricao = $_POST['descricao'];
        $conta->tipo_conta_id = $_POST['tipo_conta_id'];
        $conta->save();
        header('Location: /contas_contabeis');
    }

    public function edit($id)
    {
        $conta = ContaContabil::find($id);
        require_once '../views/contas_contabeis/edit.php';
    }

    public function update($id)
    {
        $conta = ContaContabil::find($id);
        $conta->descricao = $_POST['descricao'];
        $conta->tipo_conta_id = $_POST['tipo_conta_id'];
        $conta->save();
        header('Location: /contas_contabeis');
    }

    public function delete($id)
    {
        $conta = ContaContabil::find($id);
        $conta->delete();
        header('Location: /contas_contabeis');
    }
}