<!-- Formulário de edição de usuário -->
<form action="/index.php?controller=Usuario&action=update&id=<?= $usuario->id ?>" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?= $usuario->nome ?>" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= $usuario->email ?>" required>
    <button type="submit">Salvar</button>
</form>