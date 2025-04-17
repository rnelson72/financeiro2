<h2 class="mb-4"><?= isset($registro) ? 'Editar Usuário' : 'Novo Usuário' ?></h2>

<form method="POST" action="?path=usuario_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Nome:</label>
        <input type="text" name="nome" class="form-control" value="<?= $registro['nome'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" value="<?= $registro['email'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Senha:</label>
        <input type="password" name="senha" class="form-control" <?= isset($registro) ? '' : 'required' ?>>
        <?php if (isset($registro)) echo '<small class="text-muted">Deixe em branco para manter a senha atual.</small>'; ?>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=usuario" class="btn btn-secondary ms-2">Cancelar</a>
</form>
