<h2 class="mb-4"><?= isset($registro) ? 'Editar Transação' : 'Nova Transação' ?></h2>

<form method="POST" action="?path=transacao_salvar">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">

    <div class="mb-3">
        <label class="form-label">Código:</label>
        <input type="text" name="codigo" class="form-control" value="<?= htmlspecialchars($registro['codigo'] ?? '') ?>" required autofocus>
        <small class="form-text text-muted">Ex: FIN_CADASTRO_CLIENTE</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Nome da Transação:</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($registro['nome'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Rota (Path):</label>
        <input type="text" name="rota" class="form-control" value="<?= htmlspecialchars($registro['rota'] ?? '') ?>" required>
        <small class="form-text text-muted">Caminho/URL do sistema.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Componente/Programa:</label>
        <input type="text" name="componente" class="form-control" value="<?= htmlspecialchars($registro['componente'] ?? '') ?>">
        <small class="form-text text-muted">Nome do arquivo ou classe do componente. (Opcional)</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Ação no Componente:</label>
        <input type="text" name="acao" class="form-control" value="<?= htmlspecialchars($registro['acao'] ?? '') ?>">
        <small class="form-text text-muted">Nome da função na classe do componente. (Opcional)</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Grupo de Menu:</label>
        <select name="grupo_id" class="form-select">
            <option value="">Selecione um grupo...</option>
            <?php foreach ($grupos_menu as $grupo): ?>
                <option value="<?= $grupo['id'] ?>" <?= ($registro['grupo_id'] ?? '') == $grupo['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($grupo['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Tipo:</label>
        <select name="tipo" class="form-select">
            <?php
                $tipos = ['consulta', 'relatorio', 'cadastro', 'fiscal', 'outros'];
                $valorAtual = $registro['tipo'] ?? '';
                foreach ($tipos as $t) {
                    $selected = ($valorAtual === $t) ? 'selected' : '';
                    echo "<option value='$t' $selected>$t</option>";
                }
            ?>
        </select>
        <small class="form-text text-muted">Define a categoria ou finalidade desta transação.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Ícone:</label>
        <input type="text" name="icone" class="form-control" value="<?= htmlspecialchars($registro['icone'] ?? '') ?>">
        <small class="form-text text-muted">Classe do ícone (exemplo: <code>bi bi-graph-up</code>).</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Ordem:</label>
        <input type="number" name="ordem" class="form-control" value="<?= $registro['ordem'] ?? 0 ?>">
        <small class="form-text text-muted">Define a ordem de exibição no menu.</small>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="visivel_no_menu" value="1" <?= (!isset($registro['visivel_no_menu']) || $registro['visivel_no_menu']) ? 'checked' : '' ?>>
        <label class="form-check-label">Visível no menu</label>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="ativo" value="1" <?= (!isset($registro['ativo']) || $registro['ativo']) ? 'checked' : '' ?>>
        <label class="form-check-label">Ativo</label>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="?path=transacoes" class="btn btn-secondary ms-2">Cancelar</a>
</form>