<form action="/cartoes_credito/store" method="POST">
    <label for="descricao">Descrição:</label>
    <input type="text" id="descricao" name="descricao" required>
    
    <label for="limite_credito">Limite de Crédito:</label>
    <input type="number" id="limite_credito" name="limite_credito" step="0.01" required>
    
    <label for="vencimento_fatura">Vencimento da Fatura:</label>
    <input type="date" id="vencimento_fatura" name="vencimento_fatura" required>
    
    <label for="ativo">Ativo:</label>
    <input type="checkbox" id="ativo" name="ativo" value="1">
    
    <button type="submit">Salvar</button>
</form>