<div class="mb-3">
    <form id="form-novo-final" class="row g-2">
        <input type="hidden" name="cartao_id" value="<?= $_GET['id'] ?>">
        <div class="col-md-2">
            <input type="text" name="final" class="form-control" placeholder="Final" maxlength="4" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="titular" class="form-control" placeholder="Titular">
        </div>
        <div class="col-md-3">
            <select name="is_virtual" class="form-select">
                <option value="0">Físico</option>
                <option value="1">Virtual</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>
</div>

<hr>

<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>Final</th>
            <th>Titular</th>
            <th>Virtual</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody id="tabela-finais">
        <?php foreach ($finais as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['final']) ?></td>
            <td><?= htmlspecialchars($item['titular'] ?? '') ?></td>
            <td><?= $item['is_virtual'] ? 'Sim' : 'Não' ?></td>
            <td>
                <button class="btn btn-sm btn-outline-danger excluir-final"
                        data-id="<?= $item['id'] ?>" title="Excluir">
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $('#form-novo-final').on('submit', function(e) {
        e.preventDefault();
        $.post('?path=final_cartao_salvar', $(this).serialize(), function() {
            const cartaoId = $('input[name="cartao_id"]').val();
            $.get('?path=final_cartao_modal&id=' + cartaoId, function(data) {
                $('#modal-finais-conteudo').html(data);
            });
        });
    });

    $('.excluir-final').click(function() {
    if (!confirm('Confirma exclusão?')) return;
    const id = $(this).data('id');
    const cartaoId = $('input[name="cartao_id"]').val();

    $.post('?path=final_cartao_excluir', { id: id }, function() {
        $.get('?path=final_cartao_modal&id=' + cartaoId, function(data) {
            $('#modal-finais-conteudo').html(data);
        });
    });
});

</script>
