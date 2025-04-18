<h2 class="mb-4">Recuperar Senha</h2>

<?php if (!empty($_GET['erro'])): ?>
  <div class="alert alert-danger">Usuário não encontrado com os dados informados.</div>
<?php endif; ?>

<form method="post" action="?path=esqueci_senha_post" class="row g-3">
  <div class="col-md-6">
    <label for="email" class="form-label">E-mail cadastrado</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label for="nome" class="form-label">Nome completo</label>
    <input type="text" name="nome" class="form-control" required>
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary"><i class="bi bi-unlock"></i> Validar</button>
    <a href="?path=login" class="btn btn-secondary">Voltar</a>
  </div>
</form>
