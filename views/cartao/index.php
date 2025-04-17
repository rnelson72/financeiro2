<h2 class="mb-4">Cartões de Crédito</h2>

<a href='?path=cartao_novo' class='btn btn-primary mb-3'><i class="bi bi-plus-circle"></i> Novo Cartão</a>

<table id="tabela-cartoes" class='table datatable table-striped table-hover'>
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Bandeira</th>
            <th>Venc.</th>
            <th>Fech.</th>
            <th>Limite</th>
            <th>Banco</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cartoes as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['descricao']) ?></td>
            <td><?= $item['bandeira'] ?></td>
            <td><?= $item['dia_vencimento'] ?></td>
            <td><?= $item['dia_fechamento'] ?></td>
            <td><?= number_format($item['linha_credito'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($item['banco_nome'] ?? '-') ?></td>
            <td>
                <a href='?path=cartao_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'><i class="bi bi-pencil-square"></i></a>
                <a href='?path=cartao_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'><i class="bi bi-trash"></i></a>
                <a href="#" class='btn btn-sm btn-outline-secondary abrir-finais' data-id="<?= $item['id'] ?>" title='Finais'>
                    <i class="bi bi-credit-card"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Finais -->
<div class="modal fade" id="modalFinais" tabindex="-1" aria-labelledby="modalFinaisLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFinaisLabel">Finais do Cartão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="modal-finais-conteudo">
        <p>Carregando...</p>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).on('click', '.abrir-finais', function (e) {
        e.preventDefault();

        const cartaoId = $(this).data('id');
        $('#modal-finais-conteudo').html('<p>Carregando...</p>');
        $('#modalFinais').modal('show');

        $.get('?path=final_cartao_modal&id=' + cartaoId, function (data) {
            $('#modal-finais-conteudo').html(data);
        });
    });
</script>

</body>
</html>
