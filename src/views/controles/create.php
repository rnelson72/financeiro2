<!-- Formulário de criação de controle -->
<form action="/controles/store" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome">
    <label for="grupo_controle_id">Grupo de Controle:</label>
    <select id="grupo_controle_id" name="grupo_controle_id">
        <!-- Opções de grupos de controle -->
    </select>
    <button type="submit">Salvar</button>
</form>