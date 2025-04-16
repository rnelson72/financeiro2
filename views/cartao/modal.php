<div class="mb-3">
    <form id="form-novo-final" class="row g-2">
        <input type="hidden" name="id_final" id="id_final">
        <input type="hidden" name="cartao_id" value="<?= $_GET['id'] ?>">
        <div class="col-md-2">
            <input type="text" name="final" class="form-control" placeholder="Final" maxlength="4" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="titular" class="form-control" placeholder="Titular">
        </div>
        <div class="col-md-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_virtual" id="radioFisico" value="0" checked>
                <label class="form-check-label" for="radioFisico">Físico</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_virtual" id="radioVirtual" value="1">
                <label class="form-check-label" for="radioVirtual">Virtual</label>
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success" id="botao_final">Salvar</button>
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
                <button class="btn btn-sm btn-outline-primary editar-final"
                        data-id="<?= $item['id'] ?>"
                        data-final="<?= $item['final'] ?>"
                        data-titular="<?= htmlspecialchars($item['titular'] ?? '') ?>"
                        data-is_virtual="<?= $item['is_virtual'] ?>"
                        title="Editar">
                    <i class="bi bi-pencil"></i>
                </button>

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
    
    // Botão editar preenche os campos no topo
    $(document).on('click', '.editar-final', function () {
        const id = $(this).data('id');
        const final = $(this).data('final');
        const titular = $(this).data('titular');
        const isVirtual = $(this).data('is_virtual');

        $('#id_final').val(id);
        $('input[name="final"]').val(final);
        $('input[name="titular"]').val(titular);
        $('input[name="is_virtual"][value="' + isVirtual + '"]').prop('checked', true);

        $('#botao-final').text('Atualizar');
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
