<?php // views/cartao/final/index.php ?>

<h2>Finais do Cartão: <?= htmlspecialchars($cartao['descricao']) ?></h2>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?path=cartao">Cartões</a></li>
    <li class="breadcrumb-item active" aria-current="page">Finais</li>
  </ol>
</nav>

<a href='?path=final_cartao_novo&cartao_id=<?= $cartao['id'] ?>' class='btn btn-primary mb-3'>
  <i class="bi bi-plus-circle"></i> Novo Final
</a>

<table class="table datatable table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Final</th>
            <th>Virtual</th>
            <th>Titular</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($finais as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['final'] ?></td>
            <td><?= $item['is_virtual'] ? 'Sim' : 'Não' ?></td>
            <td><?= htmlspecialchars($item['titular']) ?></td>
            <td>
                <a href='?path=final_cartao_editar&id=<?= $item['id'] ?>&cartao_id=<?= $cartao['id'] ?>' class='btn btn-sm btn-outline-primary'><i class="bi bi-pencil-square"></i></a>
                <form method="post" action="?path=final_cartao_excluir" class="d-inline" onsubmit="return confirm('Confirma exclusão?')">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="cartao_id" value="<?= $cartao['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
