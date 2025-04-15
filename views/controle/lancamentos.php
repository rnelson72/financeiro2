<h2 class="mb-4">Lançamentos: <?= htmlspecialchars($controle['descricao']) ?></h2>

<!-- Botão Novo -->
<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalNovo">
  <i class="bi bi-plus-circle"></i> Novo Lançamento
</button>

<!-- Tabela de Lançamentos -->
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>Data</th>
      <th>Descrição</th>
      <th class="text-end">Valor</th>
      <th class="text-end">Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($lancamentos as $l): ?>
    <tr>
      <td><?= date('d/m/Y', strtotime($l['data'])) ?></td>
      <td><?= htmlspecialchars($l['descricao']) ?></td>
      <td class="text-end"><?= number_format($l['valor'], 2, ',', '.') ?></td>
      <td class="text-end">
        <!-- Botão Editar com data-atributos -->
        <button 
          type="button"
          class="btn btn-sm btn-outline-primary"
          title="Editar Lançamento"
          data-bs-toggle="modal"
          data-bs-target="#modalNovo"
          data-id="<?= $l['id'] ?>"
          data-data="<?= $l['data'] ?>"
          data-descricao="<?= htmlspecialchars($l['descricao'], ENT_QUOTES) ?>"
          data-valor="<?= $l['valor'] ?>">
          <i class="bi bi-pencil"></i>
        </button>

        <!-- Botão Excluir -->
        <a href="?path=controle_excluir_lancamento&id=<?= $l['id'] ?>&ctrl=<?= $controle['id'] ?>" 
           class="btn btn-sm btn-outline-danger" 
           onclick="return confirm('Excluir lançamento?')">
          <i class="bi bi-trash"></i>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Novo/Editar -->
<div class="modal fade" id="modalNovo" tabindex="-1" aria-labelledby="modalNovoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="?path=controle_salvar_lancamento">
      <input type="hidden" name="controle_id" value="<?= $controle['id'] ?>">
      <input type="hidden" name="id" value="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNovoLabel">Lançamento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <input type="date" class="form-control" name="data" required value="<?= date('Y-m-d') ?>">
          </div>
          <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" name="descricao" required autofocus>
          </div>
          <div class="mb-3">
            <label for="valor" class="form-label">Quantidade / Valor</label>
            <input type="number" step="0.01" class="form-control" name="valor" required>
            <small class="text-muted">Use ponto para decimais. Ex: 1.5</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-check2-circle"></i> Salvar
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JS para preencher o modal ao editar -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalNovo = document.getElementById('modalNovo');
  modalNovo.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var data = button.getAttribute('data-data');
    var descricao = button.getAttribute('data-descricao');
    var valor = button.getAttribute('data-valor');

    modalNovo.querySelector('input[name="id"]').value = id || '';
    modalNovo.querySelector('input[name="data"]').value = data || '<?= date('Y-m-d') ?>';
    modalNovo.querySelector('input[name="descricao"]').value = descricao || '';
    modalNovo.querySelector('input[name="valor"]').value = valor || '';
  });
});
</script>
