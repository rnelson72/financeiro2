<?php

class CompraController extends ControllerBase
{
    protected $modelClass = 'Compra';
    protected $viewPath = 'compras';

    // Utilitário privado: captura contexto de filtros para busca/paginação
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
            'busca'         => $_GET['busca'] ?? '',
            'ordem_campo'   => $_GET['ordem_campo'] ?? 'data',
            'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
            'pagina'        => $_GET['pagina'] ?? 1,
            'qtde_linhas'   => $qt_linhas,
            'filtros' => [
                'mes'       => $mes,
                'ano'       => $ano,
                'cartao_id' => $_GET['cartao_id'] ?? null
            ]
        ];
    }

    private function capturarContextoParaUrl()
    {
        return [
            'busca'         => $_GET['busca'] ?? '',
            'ordem_campo'   => $_GET['ordem_campo'] ?? 'data',
            'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
            'pagina'        => $_GET['pagina'] ?? 1,
            'qtde_linhas'   => $_GET['qtde_linhas'] ?? 20,
            'mes'           => $_GET['mes'] ?? date('m'),
            'ano'           => $_GET['ano'] ?? date('Y'),
            'cartao_id'     => $_GET['cartao_id'] ?? null
        ];
    }

    // Listagem geral, com filtros/paginação
    public function listar()
    {
        $compraModel     = new Compra($this->pdo);
        $cartaoModel     = new Cartao($this->pdo);
        $categoriaModel  = new Categoria($this->pdo);

        $cartao     = $cartaoModel->listarTodos();
        $categoria  = $categoriaModel->listarTodos();

        $contexto   = $this->capturarContexto();
        $compras    = $compraModel->listarComContexto($contexto);
        $totalRegistros = $compraModel->contarComContexto($contexto);

        $titulo   = 'Compras em Cartões';
        $conteudo = '../views/compras/index.php';
        include '../views/layout.php';
    }

    // Formulário de nova compra
    public function novo()
    {
        $cartaoModel    = new Cartao($this->pdo);
        $categoriaModel = new Categoria($this->pdo);
        $finalModel     = new FinalCartao($this->pdo);

        $cartao    = $cartaoModel->listarTodos();
        $categoria = $categoriaModel->listarTodos();
        $final     = $finalModel->listarTodos(1);

        $contextoUrl = http_build_query($this->capturarContextoParaUrl());

        if (isset($_GET['fatura_id'])) {
            $fatura_id = $_GET['fatura_id'];
        }

        $titulo   = 'NOVA COMPRA em CARTÃO';
        $conteudo = '../views/compras/form.php';
        include '../views/layout.php';
    }

    // Editar compra existente
    public function editar()
    {
        $cartaoModel    = new Cartao($this->pdo);
        $categoriaModel = new Categoria($this->pdo);
        $finalModel     = new FinalCartao($this->pdo);
        $compraModel    = new Compra($this->pdo);

        $cartao    = $cartaoModel->listarTodos();
        $categoria = $categoriaModel->listarTodos();
        $final     = $finalModel->listarTodos(0);

        $contextoUrl = http_build_query($this->capturarContextoParaUrl());
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            throw new InvalidArgumentException("ID da compra inválido ou não fornecido.");
        }

        $registro = $compraModel->buscarPorId($id);
        if (!$registro) {
            throw new Exception("Compra não encontrada (ID: $id).");
        }

        if (isset($_GET['fatura_id'])) {
            $fatura_id = $_GET['fatura_id'];
        }

        $cartoes     = $cartaoModel->listarTodos();
        $categorias  = $categoriaModel->listarTodos();

        $titulo   = 'EDITAR COMPRA em CARTÃO';
        $conteudo = '../views/compras/form.php';
        include '../views/layout.php';
    }

    // Salvar compra (inserir ou atualizar)
    public function salvar()
    {
        $compraModel = new Compra($this->pdo);
        $dados = $_POST;
        $idOriginal = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT);
        $isInsert = !($idOriginal > 0);

        $tentarGerarOutros = isset($dados['gerar_outros']) && $dados['gerar_outros'] == '1';

        $dadosParaSalvar = [
            'cartao_id'       => (int)$dados['cartao_id'],
            'final_cartao_id' => !empty($dados['final_cartao_id']) ? (int)$dados['final_cartao_id'] : null,
            'data'            => $dados['data'],
            'descricao'       => trim($dados['descricao']) ?: null,
            'valor'           => $this->normalizarDecimal($dados['valor'] ?? '0'),
            'parcelas'        => !empty($dados['parcelas']) ? (int)$dados['parcelas'] : 1,
            'parcela_atual'   => !empty($dados['parcela_atual']) ? (int)$dados['parcela_atual'] : 1,
            'categoria_id'    => !empty($dados['categoria_id']) ? (int)$dados['categoria_id'] : null,
            'user_id'         => $_SESSION['user_id'] ?? null
        ];

        if ($isInsert) {
            $idSalvo = $compraModel->inserir($dadosParaSalvar);
        } else {
            $idSalvo = $compraModel->atualizar($idOriginal, $dadosParaSalvar);
        }

        if (!$idSalvo) {
            $errorMessage = $isInsert
                ? "Erro ao inserir o registro da compra."
                : "Erro ao atualizar o registro da compra ID {$idOriginal}.";
            throw new Exception($errorMessage);
        }

        // Gerar outras parcelas se solicitado
        $sucessoGeracao = true;
        if ($tentarGerarOutros) {
            $dadosParaGeracao = $dadosParaSalvar;
            $dadosParaGeracao['id'] = $idSalvo;
            $sucessoGeracao = $compraModel->gerarParcelasRestantes($dadosParaGeracao);

            if (!$sucessoGeracao) {
                throw new Exception("Operação principal realizada (ID: {$idSalvo}), mas falha ao gerar parcelas restantes.");
            }
        }

        $_SESSION['mensagem_sucesso'] = "Compra salva com sucesso (ID: {$idSalvo})."
            . ($tentarGerarOutros && $sucessoGeracao ? " Parcelas restantes geradas." : "");
        $contextoQueryString = http_build_query($this->capturarContextoParaUrl());

        if (isset($dados['fatura_id']) && strpos($dados['fatura_id'], '|') !== false) {
            list($fatura_id, $rota) = explode('|', $dados['fatura_id'], 2);
            header("Location: ?path={$rota}&id={$fatura_id}");
        } else {
            header("Location: ?path=compras&$contextoQueryString");
        }
        exit;
    }

    // Excluir
    public function excluir()
    {
        $compraModel = new Compra($this->pdo);
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            throw new InvalidArgumentException("ID da compra inválido para exclusão.");
        }
        $compraModel->excluir($id);
        if (isset($_GET['fatura_id'])){
            $modelFatura = new FaturaController($this->pdo);
            $modelFatura->Compras();
        } else {
            $contextoQueryString = http_build_query($this->capturarContextoParaUrl());
            header("Location: ?path=compras&$contextoQueryString");
            exit;
        }
    }
}