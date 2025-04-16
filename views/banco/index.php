<h2 class="mb-4">Cadastro de Bancos</h2>
<a href='?path=banco_novo' class='btn btn-primary mb-3'>Novo Banco</a>
<table id="tabela-bancos" class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Número</th>
            <th>Conta</th>
            <th>Titular</th>
            <th>PIX</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bancos as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['descricao']) ?></td>
                <td><?= htmlspecialchars($item['numero']) ?></td>
                <td><?= htmlspecialchars($item['conta']) ?></td>
                <td><?= htmlspecialchars($item['titular']) ?></td>
                <td><?= htmlspecialchars($item['pix']) ?></td>
                <td><?= $item['ativo'] ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href='?path=banco_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'>
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href='?path=banco_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'>
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<!-- Inicialização -->
<script>
    $(document).ready(function() {
        $('#tabela-bancos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        }
        });
    });
</script>