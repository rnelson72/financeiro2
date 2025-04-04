<!-- Formulário de criação de lançamento -->
<form action="/index.php?controller=Lancamento&action=store" method="POST">
    <label for="controle_id">Controle:</label>
    <select id="controle_id" name="controle_id" required>
        <?php foreach ($controles as $controle): ?>
            <option value="<?= $controle->id ?>"><?= $controle->nome ?></option>
        <?php endforeach; ?>
    </select>
    <label for="valor">Valor:</label>
    <input type="number" id="valor" name="valor" step="0.01" required>
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" required>
    <button type="submit">Salvar</button>
</form>