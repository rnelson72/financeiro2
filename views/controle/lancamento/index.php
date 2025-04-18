<h2>Lançamentos: <?= htmlspecialchars($controle['descricao']) ?></h2>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?path=controle">Controles</a></li>
    <li class="breadcrumb-item active" aria-current="page">Lançamentos</li>
  </ol>
</nav>

<a href='?path=controle_novo_lancamento&controle_id=<?= $controle['id'] ?>' class='btn btn-primary mb-3'>
  <i class="bi bi-plus-circle"></i> Novo Lançamento
</a>

<table class="table datatable table-striped table-hover">
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
        <a href='?path=controle_editar_lancamento&id=<?= $l['id'] ?>&controle_id=<?= $controle['id'] ?>' class='btn btn-sm btn-outline-primary'>
          <i class="bi bi-pencil"></i>
        </a>
        <a href='?path=controle_excluir_lancamento&id=<?= $l['id'] ?>&ctrl=<?= $controle['id'] ?>' class='btn btn-sm btn-outline-danger' onclick="return confirm('Excluir lançamento?')">
          <i class="bi bi-trash"></i>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
