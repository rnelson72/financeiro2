<?php

class UsuarioController extends ControllerBase
{
    protected $modelClass = 'Usuario';
    protected $viewPath = 'usuario';

    // Sobrescrevendo métodos que precisam de customização
    public function salvar()
    {
        $model = new $this->modelClass($this->pdo);
        $dados = [
            'nome'  => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha'] ?? '',
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        $id = $_POST['id'] ?? null;

        // Validação de duplicidade de e-mail
        if ($model->emailJaExiste($dados['email'], $id)) {
            $erro = 'Já existe um usuário com esse e-mail.';
            $registro = $dados;
            $titulo = empty($id) ? 'Novo Usuário' : 'Editar Usuário';
            $conteudo = '../views/usuario/form.php';
            $scriptsHead = [
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
            ];
            include '../views/layout.php';
            return;
        }

        // Só faz hash da senha se ela foi enviada
        if (!empty($dados['senha'])) {
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        } else {
            unset($dados['senha']); // Para não sobrescrever ou inserir senha vazia
        }

        if (!empty($id)) {
            $model->atualizar($id, $dados);
        } else {
            $model->inserir($dados);
        }

        header('Location: ?path=usuario');
        exit;
    }

}