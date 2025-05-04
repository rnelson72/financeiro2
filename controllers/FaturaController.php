<?php

class FaturaController extends ControllerBase
{
    protected $modelClass = 'Fatura';
    protected $viewPath = 'fatura';

    // Listagem das faturas (com filtro por cartão)
    public function listar()
    {
        $faturaModel = new Fatura($this->pdo);
        $cartaoModel = new Cartao($this->pdo);

        $cartao_id = filter_input(INPUT_GET, 'cartao_id', FILTER_VALIDATE_INT);
        $cartoes = $cartaoModel->listarTodos();

        $filtros = [];
        if ($cartao_id) {
            $filtros['cartao_id'] = $cartao_id;
        }
        $faturas = $faturaModel->listarTodos($filtros);

        $titulo = 'Faturas';
        $conteudo = '../views/fatura/index.php';
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
            'assets/js/datatables-init.js',
            'assets/js/onclick-datatables.js'
        ];
        $scriptsHead = [
            'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css'
        ];

        include '../views/layout.php';
    }

    // Formulário de nova fatura
    public function novo()
    {
        $cartaoModel = new Cartao($this->pdo);
        $cartoes = $cartaoModel->listarTodos();
        $fatura = null;

        $titulo = 'Nova Fatura';
        $conteudo = '../views/fatura/form.php';
        include '../views/layout.php';
    }

    // Editar fatura
    public function editar()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) die('ID inválido.');

        $faturaModel = new Fatura($this->pdo);
        $cartaoModel = new Cartao($this->pdo);

        $fatura = $faturaModel->buscarPorId($id);
        if (!$fatura) die('Fatura não encontrada.');

        $cartoes = $cartaoModel->listarTodos();
        $titulo = "Editar Fatura";
        $conteudo = '../views/fatura/form.php';
        include '../views/layout.php';
    }

    // Excluir
    public function excluir()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) die('ID inválido.');
        $faturaModel = new Fatura($this->pdo);
        $faturaModel->excluir($id);
        header('Location: ?path=faturas');
        exit;
    }

    // Inserir/atualizar
    public function salvar()
    {
        $faturaModel = new Fatura($this->pdo);
        $dados = $_POST;

        $id = filter_var($dados['id'] ?? null, FILTER_VALIDATE_INT);
        $dadosSalvos = [
            'cartao_id'       => (int)$dados['cartao_id'],
            'data_fechamento' => $dados['data_fechamento'],
            'data_vencimento' => $dados['data_vencimento'],
            'valor_total'     => $this->normalizarDecimal($dados['valor_total'] ?? '0'),
            'valor_pago'      => isset($dados['valor_pago']) ? $this->normalizarDecimal($dados['valor_pago']) : null,
            'status'          => $dados['status'] ?? 'Aberta',
            'movimentacao_id' => isset($dados['movimentacao_id']) ? (int)$dados['movimentacao_id'] : null
        ];

        if ($id) {
            $faturaModel->atualizar($id, $dadosSalvos);
            header('Location: ?path=faturas');
            exit;
        } else {
            $novoId = $faturaModel->inserir($dadosSalvos);
            header("Location: ?path=fatura_select_compras&id=$novoId");
            exit;
        }
    }

    // Fluxo especial: selecionar compras para vincular à fatura recém-criada
    public function select_compras()
    {
        $faturaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
            ?? filter_input(INPUT_POST, 'fatura_id', FILTER_VALIDATE_INT);
        if (!$faturaId) die('Fatura não definida==.');

        $faturaModel = new Fatura($this->pdo);
        $compraModel = new Compra($this->pdo);

        $fatura = $faturaModel->buscarPorId($faturaId);
        if (!$fatura) die('Fatura não encontrada.');

        $comprasPendentes = $compraModel->listarPorCartaoSemFatura($fatura['cartao_id']);
        $titulo = "Selecionar Compras para a Fatura de " . $fatura['data_fechamento'];
        $conteudo = "../views/fatura/select_compras.php";
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
            'assets/js/datatables-init.js',
            'assets/js/onclick-datatables.js'
        ];
        $scriptsHead = [
            'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css'
        ];
        include '../views/layout.php';
    }

    // Salvar vinculação das compras à fatura e fechar
    public function fechar()
    {
        $faturaModel = new Fatura($this->pdo);
        $compraModel = new Compra($this->pdo);

        $faturaId = filter_input(INPUT_POST, 'fatura_id', FILTER_VALIDATE_INT);
        if (!$faturaId) die('Fatura não definida.***');

        $comprasSelecionadas = $_POST['compra_ids'] ?? [];
        if (!is_array($comprasSelecionadas) || empty($comprasSelecionadas)) {
            $_SESSION['mensagem_erro'] = "Selecione ao menos uma compra para fechar a fatura!";
            header("Location: ?path=fatura_select_compras&id=$faturaId");
            exit;
        }

        $somaCompras = $compraModel->somarValorPorIds($comprasSelecionadas);
        $fatura = $faturaModel->buscarPorId($faturaId);

        if (abs($somaCompras - $fatura['valor_total']) > 0.01) {
            $_SESSION['mensagem_erro'] = 
                "A soma das compras não confere com o valor da fatura (" .
                number_format($fatura['valor_total'],2,',','.') . ").";
            header("Location: ?path=fatura_select_compras&id=$faturaId");
            exit;
        }

        $compraModel->atualizarFaturaEmLote($comprasSelecionadas, $faturaId);

        $fatura['status'] = 'Fechada';
        $faturaModel->atualizar($faturaId, $fatura);

        $_SESSION['mensagem_sucesso'] = "Fatura fechada com sucesso!";
        header("Location: ?path=faturas");
        exit;
    }

    // Listagem das compras/vinculações de uma fatura
    public function compras()
    {
        $faturaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$faturaId) {
//        } elseif (isset($_GET['fatura_id'])) { 
//            $faturaId = $_GET['fatura_id'];
//        } elseif (isset($_POST['fatura_id'])) { 
//            $faturaId = $_POST['fatura_id'];
//        } else { 
            die('Fatura não definida++.');
        }

        $faturaModel = new Fatura($this->pdo);
        $compraModel = new Compra($this->pdo);

        $fatura = $faturaModel->buscarPorId($faturaId);
        if (!$fatura) die('Fatura não encontrada.');

        $compras = $compraModel->listarPorFatura($faturaId);

        $titulo = "Movimentações da Fatura #" . $faturaId;
        $conteudo = '../views/fatura/detalhe.php';
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
            'assets/js/datatables-init.js'
        ];
        $scriptsHead = [
            'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css'
        ];
        include '../views/layout.php';
    }
}