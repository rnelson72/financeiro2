<!-- Formulário de edição de grupo de controle -->
<form action="/index.php?controller=GrupoControle&action=update&id=<?= $grupo->id ?>" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?= $grupo->nome ?>" required>
    <button type="submit">Salvar</button>
</form>