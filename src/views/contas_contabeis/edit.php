<form action="/contas_contabeis/update/<?php echo $conta->id; ?>" method="POST">
    <label for="descricao">Descrição:</label>
    <input type="text" id="descricao" name="descricao" value="<?php echo $conta->descricao; ?>" required>
    
    <label for="tipo_conta_id">Tipo de Conta:</label>
    <input type="number" id="tipo_conta_id" name="tipo_conta_id" value="<?php echo $conta->tipo_conta_id; ?>" required>
    
    <button type="submit">Salvar</button>
</form>