<?php

class AuthController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        $titulo = 'Login';
        $conteudo = '../views/auth/login.php';
        include '../views/layout.php';
    }

    public function autenticar() {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $auth = new Auth($this->pdo);
        $usuario = $auth->validarLogin($email, $senha);

        if ($usuario) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            // --- MONTA O MENU E GUARDA NA SESSÃO ---
            $_SESSION['menu_agrupado'] = $this->montarMenuAgrupado($usuario['id']);

            header('Location: ?path=dashboard');
            exit;
        } else {
            $erro = "Usuário ou senha inválidos.";
            $titulo = 'Login';
            $conteudo = '../views/auth/login.php';
            include '../views/layout.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ?path=login');
        exit;
    }

    public function esqueci_senha() {
        $titulo = "Recuperar Senha";
        $conteudo = __DIR__ . '/../views/auth/esqueci_senha.php';
        include __DIR__ . '/../views/layout.php';
    }

    public function esqueci_senha_post() {
        $email = trim($_POST['email'] ?? '');
        $nome = trim($_POST['nome'] ?? '');

        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND nome = ?");
        $stmt->execute([$email, $nome]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            header("Location: ?path=esqueci_senha&erro=1");
            exit;
        }

        header("Location: ?path=redefinir_senha&id={$usuario['id']}");
        exit;
    }

    public function redefinir_senha() {
        $id = $_GET['id'];
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        $titulo = "Nova Senha";
        $conteudo = __DIR__ . '/../views/auth/redefinir_senha.php';
        include __DIR__ . '/../views/layout.php';
    }

    public function salvar_nova_senha() {
        $id = $_POST['id'];
        $senha = $_POST['senha'];
        $confirmar = $_POST['confirmar'];

        if ($senha !== $confirmar) {
            die("As senhas não coincidem.");
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE id = ?");
        $stmt->execute([$hash, $id]);

        header("Location: ?path=login");
        exit;
    }
    /**
     * Monta o menu agrupado navegável a partir do banco
     * Retorna um array agrupado por grupo
     */
    private function montarMenuAgrupado($usuario_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT g.nome AS grupo_nome, t.nome, t.rota, t.icone, t.ordem
            FROM transacoes t
            INNER JOIN grupos_menu g ON g.id = t.grupo_id
            INNER JOIN permissoes p ON p.transacao_id = t.id
            WHERE t.visivel_no_menu = 1 
                AND t.ativo = 1
                AND p.usuario_id = ?
            ORDER BY g.ordem, t.ordem, t.nome
        ");
        $stmt->execute([$usuario_id]);
        
        $menuAgrupado = [];
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $grupo = $item['grupo_nome'];
            // Exemplo de estrutura, você pode adicionar mais campos se precisar
            $menuAgrupado[$grupo][] = [
                'nome'  => $item['nome'],
                'rota'  => $item['rota'],
                'icone' => $item['icone'] ?? '',
                'ordem' => $item['ordem'],
            ];
        }

        return $menuAgrupado;
    }
}