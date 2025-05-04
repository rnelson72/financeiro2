<?php

class MovimentacaoController extends ControllerBase
{
    protected $modelClass = 'Movimentacao';
    protected $viewPath = 'movimentacao';


    public function listar()
    {
        $model = new Movimentacao($this->pdo);
        $bancoModel = new Banco($this->pdo);

        $contexto = $this->capturarContexto();
        $bancos = $bancoModel->listarTodos();

        $movimentacoes = $model->listarComContexto($contexto);
        $total_registros = $model->contarComContexto($contexto);

        $titulo = 'Movimentações Financeiras';
        $conteudo = '../views/movimentacao/index.php';
        include '../views/layout.php';
    }

    public function novo()
    {
        $registro = [];
        $contexto = $this->capturarContexto();

        $categoriaModel = new Categoria($this->pdo);
        $bancoModel = new Banco($this->pdo);
        $categorias = $categoriaModel->listarTodos();
        $bancos = $bancoModel->listarTodos();
        $contextoUrl = http_build_query($this->capturarContextoParaUrl());
        
        // Recupera o último código_pagamento
        $model = new Movimentacao($this->pdo);
        $proximoCodigo = $model->getProximoCodigoPagamento();
        $registro['codigo_pagamento'] = $proximoCodigo;

        $titulo = 'Nova Movimentação';
        $conteudo = '../views/movimentacao/form.php';
        include '../views/layout.php';
    }

    public function editar()
    {
        $model = new Movimentacao($this->pdo);
        $registro = $model->buscarPorId($_GET['id']);
        $contexto = $this->capturarContexto();

        $categoriaModel = new Categoria($this->pdo);
        $bancoModel = new Banco($this->pdo);
        $categorias = $categoriaModel->listarTodos();
        $bancos = $bancoModel->listarTodos();
        $contextoUrl = http_build_query($this->capturarContextoParaUrl());

        $titulo = 'Editar Movimentação';
        $conteudo = '../views/movimentacao/form.php';
        include '../views/layout.php';
    }

    public function excluir()
    {
        $model = new Movimentacao($this->pdo);
        $model->excluir($_GET['id']);

        $queryString = http_build_query($this->capturarContextoParaUrl());
        header("Location: ?path=movimentacao&$queryString");
        exit;
    }

    public function salvar()
    {
        $model = new Movimentacao($this->pdo);
    
        $dados = [
            'data'             => $_POST['data'],
            'descricao'        => $_POST['descricao'],
            'valor'            => $_POST['valor'],
            'categoria_id'     => $_POST['categoria_id'] ?? null,
            'banco_id'         => $_POST['banco_id'] ?? null,
            'codigo_pagamento' => $_POST['codigo_pagamento'] ?? null,
            'fatura_id'        => $_POST['fatura_id'] ?? null
        ];
    
        $id = $_POST['id'];
        if (!empty($id)) {
            $model->atualizar($id, $dados);
        } else {
            $id = $model->inserir($dados); // <-- armazena o id retornado
        }
    
        $queryString = http_build_query(
            array_merge(
                $this->capturarContextoParaUrl(),
                ['id' => $id]
            )
        );
        header("Location: ?path=movimentacao&$queryString");
        exit;
    }
    // Métodos utilitários como métodos privados, pois servem apenas dentro desse controller
    private function capturarContexto()
    {
        $mes = '';
        $ano = '';
        // Se 'limpar' está presente, não aplica default algum
        if ((isset($_GET['limpar'])) ) {
            $mes = '';
            $ano = '';
        } elseif (!empty($_GET['mes_ano'])) {
            [$ano, $mes] = explode('-', $_GET['mes_ano']);
        } elseif (!isset($_GET['carregado'])) { 
            // Primeira vez (acesso padrão): sugere mês/ano atual
            $mes = date('m');
            $ano = date('Y');
        }
        if (isset($_GET['qtde_linhas'])) {
            $qt_linhas = $_GET['qtde_linhas'];
        } elseif (!isset($_GET['carregado'])) {
            $qt_linhas = 20; // default
        }
    
        return [
            'busca' => $_GET['busca'] ?? '',
            'ordem_campo' => $_GET['ordem_campo'] ?? 'data',
            'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
            'pagina' => $_GET['pagina'] ?? 1,
            'qtde_linhas' => $qt_linhas,
            'filtros' => [
                'mes' => $mes,
                'ano' => $ano,
                'banco_id' => $_GET['banco_id'] ?? null
            ]
        ];
    }
        
    private function capturarContextoParaUrl()
    {
        return [
            'busca' => $_GET['busca'] ?? '',
            'ordem_campo' => $_GET['ordem_campo'] ?? 'data',
            'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
            'pagina' => $_GET['pagina'] ?? 1,
            'qtde_linhas' => $_GET['qtde_linhas'] ?? 20,
            'mes' => $_GET['mes'] ?? date('m'),
            'ano' => $_GET['ano'] ?? date('Y'),
            'banco_id' => $_GET['banco_id'] ?? null
        ];
    }
}