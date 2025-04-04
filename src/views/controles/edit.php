<!-- Formulário de edição de controle -->
<form action="/controles/update" method="POST">
    <input type="hidden" id="id" name="id" value="<?= $controle->id ?>">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?= $controle->nome ?>">
    <label for="grupo_controle_id">Grupo de Controle:</label>
    <select id="grupo_controle_id" name="grupo_controle_id">
        <!-- Opções de grupos de controle -->
    </select>
    <button type="submit">Salvar</button>
</form>