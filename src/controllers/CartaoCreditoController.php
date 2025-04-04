<?php

namespace App\Controllers;

use App\Models\CartaoCredito;

class CartaoCreditoController
{
    public function index()
    {
        $cartoes = CartaoCredito::all();
        require_once '../views/cartoes_credito/index.php';
    }

    public function create()
    {
        require_once '../views/cartoes_credito/create.php';
    }

    public function store()
    {
        $cartao = new CartaoCredito();
        $cartao->descricao = $_POST['descricao'];
        $cartao->limite_credito = $_POST['limite_credito'];
        $cartao->vencimento_fatura = $_POST['vencimento_fatura'];
        $cartao->ativo = $_POST['ativo'];
        $cartao->save();
        header('Location: /cartoes_credito');
    }

    public function edit($id)
    {
        $cartao = CartaoCredito::find($id);
        require_once '../views/cartoes_credito/edit.php';
    }

    public function update($id)
    {
        $cartao = CartaoCredito::find($id);
        $cartao->descricao = $_POST['descricao'];
        $cartao->limite_credito = $_POST['limite_credito'];
        $cartao->vencimento_fatura = $_POST['vencimento_fatura'];
        $cartao->ativo = $_POST['ativo'];
        $cartao->save();
        header('Location: /cartoes_credito');
    }

    public function delete($id)
    {
        $cartao = CartaoCredito::find($id);
        $cartao->delete();
        header('Location: /cartoes_credito');
    }
}