<!-- Formulário de edição de lançamento -->
<form action="/index.php?controller=Lancamento&action=update&id=<?= $lancamento->id ?>" method="POST">
    <label for="controle_id">Controle:</label>
    <select id="controle_id" name="controle_id" required>
        <?php foreach ($controles as $controle): ?>
            <option value="<?= $controle->id ?>" <?= $controle->id == $lancamento->controle_id ? 'selected' : '' ?>><?= $controle->nome ?></option>
        <?php endforeach; ?>
    </select>
    <label for="valor">Valor:</label>
    <input type="number" id="valor" name="valor" step="0.01" value="<?= $lancamento->valor ?>" required>
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" value="<?= $lancamento->data ?>" required>
    <button type="submit">Salvar</button>
</form>