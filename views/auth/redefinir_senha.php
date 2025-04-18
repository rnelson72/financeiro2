<h2 class="mb-4">Definir Nova Senha</h2>

<form method="post" action="?path=salvar_nova_senha">
  <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

  <div class="mb-3">
    <label for="senha" class="form-label">Nova Senha</label>
    <input type="password" name="senha" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="confirmar" class="form-label">Confirme a Senha</label>
    <input type="password" name="confirmar" class="form-control" required>
  </div>

  <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Salvar nova senha</button>
</form>
