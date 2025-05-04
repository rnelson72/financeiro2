<?php

abstract class ControllerBase {
    protected $pdo;
    protected $modelClass;
    protected $viewPath;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // HOOKS - sobrescreva apenas nos filhos que quiser!
    // Por padrão, retornam array vazio
    // ---------------------------------- exemplos de ajustes
    //     protected function getExtrasListar() {
    //        $menuModel = new Menu($this->pdo);
    //        return ['grupos_menu' => $menuModel->listarTodos()];
    //    }
    //    protected function getExtrasNovo() {
    //        $menuModel = new Menu($this->pdo);
    //        $categoriaModel = new Categoria($this->pdo);
    //        return [
    //            'grupos_menu' => $menuModel->listarTodos(),
    //            'categorias' => $categoriaModel->listarTodos()
    //        ];
    //    }

    protected function getExtrasListar() { return []; }
    protected function getExtrasNovo() { return []; }
    protected function getExtrasEditar($registro = null) { return []; }

    // LISTAR
    public function listar() {
        $model = new $this->modelClass($this->pdo);
        $registros = $model->listarTodos();
        // Chama o HOOK, que retorna variáveis extras (ex: combos)
        extract($this->getExtrasListar());

        $titulo = ucfirst($this->viewPath);
        $conteudo = "../views/{$this->viewPath}/index.php";
        $scriptsBody = [
            'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
            'assets/js/datatables-init.js',
            'assets/js/onclick-datatables.js'
        ];
        $scriptsHead = ['https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css'];
        include '../views/layout.php';
    }

    // NOVO (formulário vazio)
    public function novo() {
        $registro = [];
        extract($this->getExtrasNovo());

        $titulo = "Novo " . ucfirst($this->viewPath);
        $conteudo = "../views/{$this->viewPath}/form.php";
        include '../views/layout.php';
    }

    // EDITAR (formulário preenchido)
    public function editar() {
        $model = new $this->modelClass($this->pdo);
        $registro = $model->buscarPorId($_GET['id']);
        extract($this->getExtrasEditar($registro));

        $titulo = "Editar " . ucfirst($this->viewPath);
        $conteudo = "../views/{$this->viewPath}/form.php";
        include '../views/layout.php';
    }

    // EXCLUIR
    public function excluir() {
        $model = new $this->modelClass($this->pdo);
        $model->excluir($_GET['id']);
        header("Location: ?path={$this->viewPath}");
        exit;
    }

    // SALVAR
    public function salvar() {
        $model = new $this->modelClass($this->pdo);
        $dados = $_POST;

        if (!empty($_POST['id'])) {
            $model->atualizar($_POST['id'], $dados);
        } else {
            $model->inserir($dados);
        }
        header("Location: ?path={$this->viewPath}");
        exit;
    }

    public function normalizarDecimal($valor) {
        // Remove espaços e tudo que não seja número, ponto ou vírgula
        $valor = preg_replace('/\s+/', '', (string)$valor);
        // Se tem vírgula, é separador decimal (pt-BR)
        if (strpos($valor, ',') !== false) {
            // Remove pontos (de milhar) e troca vírgula por ponto
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }
        // Agora só números e ponto decimal
        $valor = preg_replace('/[^\d\.]/', '', $valor);
        return (float)$valor;
    }
}