<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="text-center mb-4">Acesso ao Sistema</h4>

        <?php if (!empty($erro)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="?path=autenticar">
          <div class="mb-3">
            <label class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" required autofocus>
          </div>

          <div class="mb-3">
            <label class="form-label">Senha:</label>
            <input type="password" name="senha" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Entrar</button>
          <div class="text-center mt-2">
            <a href="?path=esqueci_senha" class="text-decoration-none text-muted">
              <i class="bi bi-question-circle me-1"></i> Esqueci minha senha
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
