<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?path=faturas">Faturas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Fatura #<?= htmlspecialchars($fatura['id']) ?></li>
  </ol>
</nav>
<a href="?path=faturas" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Voltar</a>

<h2>Fatura #<?= htmlspecialchars($fatura['id']) ?> - <?= htmlspecialchars($fatura['cartao_nome']) ?></h2>
<p>
    <b>Fechamento:</b> <?= date('d/m/Y', strtotime($fatura['data_fechamento'])) ?> |
    <b>Vencimento:</b> <?= date('d/m/Y', strtotime($fatura['data_vencimento'])) ?> |
    <b>Valor total:</b> R$ <?= number_format($fatura['valor_total'],2,',','.') ?> |
    <b>Status:</b>
    <span class="badge bg-<?= $fatura['status']=='Paga'?'success':($fatura['status']=='Fechada'?'warning':'primary') ?>">
        <?= htmlspecialchars($fatura['status']) ?>
    </span>
</p>
<?php if(in_array($fatura['status'], ['Fechada','Paga'])): ?>
    <div class="alert alert-warning">
        Esta fatura está fechada<?= $fatura['status']=='Paga' ? ' e paga' : '' ?>.
        Só ajustes em lançamentos existentes são permitidos!
    </div>
<?php endif; ?>

<table class="table datatable table-striped table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Parcela</th>
            <th>Descrição</th>
            <th class='text-end'>Valor</th>
            <th>Categoria</th>
            <th class="text-center">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($compras as $c): ?>
            <tr>
                <td><?= htmlspecialchars(date('d/m/Y',strtotime($c['data']))) ?></td>
                <td><?= (isset($c['parcelas']) && $c['parcelas'] > 1) ? ($c['parcela_atual'] ?? 1).' / '.$c['parcelas'] : 'À vista' ?></td>
                <td><?= htmlspecialchars($c['descricao']) ?></td>
                <td class='text-end'>R$ <?= number_format($c['valor'],2,',','.') ?></td>
                <td><?= htmlspecialchars($c['categoria_nome'] ?? 'N/I') ?></td>
                <td class="text-center">
                    <a href="?path=compras_editar&id=<?= $c['id'] ?>&fatura_id=<?= $fatura['id'].'|fatura_compras' ?>" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                    <?php if($fatura['status'] != 'Paga'): ?>
                        <a href="?path=compras_excluir&id=<?= $c['id'] ?>&fatura_id=<?= $fatura['id'] ?>"
                           class="btn btn-sm btn-danger" title="Excluir"
                           onclick="return confirm('Confirma excluir esta compra? Esta ação não pode ser desfeita.')"><i class="bi bi-trash"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if(empty($compras)): ?>
            <tr><td colspan="6" class="text-center text-muted">Nenhuma compra nesta fatura.</td></tr>
        <?php endif; ?>
    </tbody>
</table>