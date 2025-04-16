<?php
class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function validarLogin($email, $senha) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1 LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return false;
    }
}
?>
