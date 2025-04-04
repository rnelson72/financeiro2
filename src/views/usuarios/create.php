<!-- Formulário de criação de usuário -->
<form action="/index.php?controller=Usuario&action=store" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Salvar</button>
</form>