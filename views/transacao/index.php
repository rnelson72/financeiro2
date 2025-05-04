<h2 class="mb-4">Transações (Funcionalidades)</h2>

<a href='?path=transacao_novo' class='btn btn-primary mb-3'>
    <i class="bi bi-plus-circle"></i> Nova Transação
</a>

<table id="tabela-transacoes" class='table datatable table-striped table-hover'>
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Rota</th>
            <th>Componente</th>
            <th>Grupo</th>
            <th>Tipo</th>
            <th>Ordem</th>
            <th>Menu</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= htmlspecialchars($item['nome']) ?></td>
            <td><?= htmlspecialchars($item['rota']) ?></td>
            <td><?= htmlspecialchars($item['componente'] . '::' . $item['acao']) ?></td>
            <td>
                <?php
                // Busca o nome do grupo
                $nomeGrupo = '-';
                foreach ($grupos_menu as $g) {
                    if ($g['id'] == $item['grupo_id']) {
                        $nomeGrupo = htmlspecialchars($g['nome']);
                        break;
                    }
                }
                echo $nomeGrupo;
                ?>
            </td>
            <td><?= htmlspecialchars($item['tipo']) ?></td>
            <td><?= $item['ordem'] ?></td>
            <td>
                <?= !empty($item['visivel_no_menu']) ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?>
            </td>
            <td>
                <?= !empty($item['ativo']) ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-secondary">Não</span>' ?>
            </td>
            <td>
                <a href='?path=transacao_editar&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-primary' title='Editar'><i class="bi bi-pencil-square"></i></a>
                <a href='?path=transacao_excluir&id=<?= $item['id'] ?>' class='btn btn-sm btn-outline-danger' title='Excluir' onclick='return confirm("Confirma exclusão?")'><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>