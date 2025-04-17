<?php
// models/Usuario.php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $senhaCriptografada = password_hash($dados['senha_hash'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dados['nome'], $dados['email'], $senhaCriptografada]);
    }

    public function atualizar($id, $dados) {
        if (!empty($dados['senha'])) {
            $senhaCriptografada = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome = ?, email = ?, senha_hash = ?, WHERE id = ?";
            $params = [$dados['nome'], $dados['email'], $senhaCriptografada, $id];
        } else {
            $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $params = [$dados['nome'], $dados['email'], $id];
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }
    public function emailJaExiste($email, $id = null) {
        // Verifica se o e-mail já está cadastrado para outro usuário
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        $params = [$email];
    
        if ($id) {
            $sql .= " AND id != ?";
            $params[] = $id;
        }
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchColumn() > 0;
    }
    
} 
