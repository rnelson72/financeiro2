<?php

namespace App\Controllers;

use App\Models\Banco;

class BancoController
{
    public function index()
    {
        $bancos = Banco::all();
        require_once '../views/bancos/index.php';
    }

    public function create()
    {
        require_once '../views/bancos/create.php';
    }

    public function store()
    {
        $banco = new Banco();
        $banco->nome = $_POST['nome'];
        $banco->numero_agencia = $_POST['numero_agencia'];
        $banco->numero_conta_corrente = $_POST['numero_conta_corrente'];
        $banco->saldo_inicial = $_POST['saldo_inicial'];
        $banco->save();
        header('Location: /bancos');
    }

    public function edit($id)
    {
        $banco = Banco::find($id);
        require_once '../views/bancos/edit.php';
    }

    public function update($id)
    {
        $banco = Banco::find($id);
        $banco->nome = $_POST['nome'];
        $banco->numero_agencia = $_POST['numero_agencia'];
        $banco->numero_conta_corrente = $_POST['numero_conta_corrente'];
        $banco->saldo_inicial = $_POST['saldo_inicial'];
        $banco->save();
        header('Location: /bancos');
    }

    public function delete($id)
    {
        $banco = Banco::find($id);
        $banco->delete();
        header('Location: /bancos');
    }
}