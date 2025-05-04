<?php

class TransacaoController extends ControllerBase
{
    protected $modelClass = 'Transacao';
    protected $viewPath = 'transacao';

    // Para Listagem
    protected function getExtrasListar()
    {
        $menuModel = new Menu($this->pdo);
        $categoriaModel = new Categoria($this->pdo);

        return [
            'grupos_menu' => $menuModel->listarTodos(),
            'categorias' => $categoriaModel->listarTodos()
        ];
    }

    // Para "Novo"
    protected function getExtrasNovo()
    {
        $menuModel = new Menu($this->pdo);
        $categoriaModel = new Categoria($this->pdo);

        return [
            'grupos_menu' => $menuModel->listarTodos(),
            'categorias' => $categoriaModel->listarTodos()
        ];
    }

    // Para "Editar"
    protected function getExtrasEditar($registro = null)
    {
        $menuModel = new Menu($this->pdo);
        $categoriaModel = new Categoria($this->pdo);

        return [
            'grupos_menu' => $menuModel->listarTodos(),
            'categorias' => $categoriaModel->listarTodos()
        ];
    }
}