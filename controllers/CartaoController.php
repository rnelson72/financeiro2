<?php

class CartaoController extends ControllerBase
{
    protected $modelClass = 'Cartao';
    protected $viewPath = 'cartao';

    // Utilidade
    private function nullIfEmpty($value)
    {
        return trim($value) === '' ? null : $value;
    }

    // Listar cartões de crédito
    public function listar()
    {
        $model = new Cartao($this->pdo);
        $cartoes = $model->listarTodos();
        $scriptsHead = [
            'https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css'
        ];
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js',
            'assets/js/datatables-init.js'
        ];
        $titulo = 'Cartões de Crédito';
        $conteudo = '../views/cartao/index.php';
        include '../views/layout.php';
    }

    public function novo()
    {
        $registro = [];
        $bancos = $this->pdo->query("SELECT id, descricao FROM bancos ORDER BY descricao")->fetchAll(PDO::FETCH_ASSOC);
        $titulo = 'Novo Cartão';
        $conteudo = '../views/cartao/form.php';
        include '../views/layout.php';
    }

    public function editar()
    {
        $model = new Cartao($this->pdo);
        $registro = $model->buscarPorId($_GET['id']);
        $bancos = $this->pdo->query("SELECT id, descricao FROM bancos ORDER BY descricao")->fetchAll(PDO::FETCH_ASSOC);
        $titulo = 'Editar Cartão';
        $conteudo = '../views/cartao/form.php';
        include '../views/layout.php';
    }

    public function excluir()
    {
        $stmt = $this->pdo->prepare("DELETE FROM cartao WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header('Location: ?path=cartao');
        exit;
    }

    public function salvar()
    {
        $banco_id = trim($_POST['banco_id']) !== '' ? $_POST['banco_id'] : null;
        if (!empty($_POST['id'])) {
            $stmt = $this->pdo->prepare("UPDATE cartao SET descricao = ?, bandeira = ?, dia_fechamento = ?, dia_vencimento = ?, linha_credito = ?, banco_id = ?, ativo = ? WHERE id = ?");
            $stmt->execute([
                $_POST['descricao'],
                $_POST['bandeira'],
                $_POST['dia_fechamento'],
                $_POST['dia_vencimento'],
                $_POST['linha_credito'],
                $banco_id,
                isset($_POST['ativo']) ? 1 : 0,
                $_POST['id']
            ]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO cartao (descricao, bandeira, dia_fechamento, dia_vencimento, linha_credito, banco_id, ativo)
                                         VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['descricao'],
                $_POST['bandeira'],
                $_POST['dia_fechamento'],
                $_POST['dia_vencimento'],
                $_POST['linha_credito'],
                $banco_id,
                isset($_POST['ativo']) ? 1 : 0
            ]);
        }
        header('Location: ?path=cartao');
        exit;
    }

    // ----- Finais de Cartão -----
    public function final_listar()
    {
        $cartao_id = $_GET['cartao_id'] ?? null;

        $cartaoModel = new Cartao($this->pdo);
        $cartao = $cartaoModel->buscarPorId($cartao_id);

        if (!$cartao) {
            echo "<div class='alert alert-danger'>Cartão não encontrado.</div>";
            return;
        }

        $finalModel = new FinalCartao($this->pdo);
        $finais = $finalModel->buscarPorCartaoId($cartao_id, false); // Exibe todos, ativos/inativos

        $titulo = "Finais de Cartão: " . htmlspecialchars($cartao['descricao']);
        $conteudo = '../views/cartao/final/index.php';
        $scriptsHead = [
            'https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css'
        ];
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js',
            '/financeiro2/public/assets/js/datatables-init.js'
        ];
        include '../views/layout.php';
    }

    public function final_novo()
    {
        $cartao_id = $_GET['cartao_id'];
        $registro = [
            'id' => '',
            'final' => '',
            'is_virtual' => 0,
            'titular' => '',
            'ativo' => 1
        ];

        $cartaoModel = new Cartao($this->pdo);
        $cartao = $cartaoModel->buscarPorId($cartao_id);

        $titulo = "Novo Final do Cartão: " . htmlspecialchars($cartao['descricao']);
        $conteudo = '../views/cartao/final/form.php';
        include '../views/layout.php';
    }

    public function final_editar()
    {
        $id = $_GET['id'] ?? null;
        $cartao_id = $_GET['cartao_id'];

        $finalModel = new FinalCartao($this->pdo);
        $registro = [
            'id' => '',
            'final' => '',
            'is_virtual' => 0,
            'titular' => '',
            'ativo' => 1
        ];
        if ($id) {
            $registro = $finalModel->buscarPorId($id);
        }

        $cartaoModel = new Cartao($this->pdo);
        $cartao = $cartaoModel->buscarPorId($cartao_id);

        $titulo = "Editar Final do Cartão: " . htmlspecialchars($cartao['descricao']);
        $conteudo = '../views/cartao/final/form.php';
        include '../views/layout.php';
    }

    public function final_salvar()
    {
        $finalModel = new FinalCartao($this->pdo);

        $dados = [
            'final'     => $_POST['final'],
            'cartao_id' => $_POST['cartao_id'],
            'is_virtual'=> isset($_POST['is_virtual']) ? 1 : 0,
            'titular'   => $this->nullIfEmpty($_POST['titular']),
            'ativo'     => 1
        ];

        if (!empty($_POST['id_final'])) {
            $finalModel->atualizar((int)$_POST['id_final'], $dados);
        } else {
            $finalModel->inserir($dados);
        }

        header("Location: ?path=final_cartao_listar&cartao_id={$_POST['cartao_id']}");
        exit;
    }

    public function final_excluir()
    {
        $finalModel = new FinalCartao($this->pdo);
        $id = $_POST['id'] ?? null;
        $cartao_id = $_POST['cartao_id'] ?? null;

        if ($id) {
            $finalModel->desativar((int)$id); // Use desativar em vez de deletar direto!
        }

        header("Location: ?path=final_cartao_lista&cartao_id=$cartao_id");
        exit;
    }
}