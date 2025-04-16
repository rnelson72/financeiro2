<h2 class="mb-4">Categorias</h2>
<a href="?path=categoria_novo" class="btn btn-primary mb-3">Nova Categoria</a>
<table id="tabela-categorias" class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Conta</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['conta']) ?></td>
                <td><?= htmlspecialchars($item['descricao']) ?></td>
                <td><?= $item['tipo'] ?></td>
                <td><?= $item['ativo'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href="?path=categoria_editar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="?path=categoria_excluir&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#tabela-categorias').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });
    });
</script>