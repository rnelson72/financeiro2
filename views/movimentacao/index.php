<?php
// Utilitários
function link_contextual($paramsExtra = []) {
    $base = array_merge($_GET, $paramsExtra);
    return '?' . http_build_query($base);
}

function destaque_valor($valor) {
    return $valor < 0
        ? '<span style="color:red">R$ ' . number_format($valor, 2, ',', '.') . '</span>'
        : 'R$ ' . number_format($valor, 2, ',', '.');
}
?>

<h2 class="mb-4">Movimentações Financeiras</h2>

<!-- Filtros -->
<form method="GET" class="row g-2 mb-3">
    <input type="hidden" name="path" value="movimentacao">

    <div class="col-md-3">
        <input type="text" name="busca" value="<?= htmlspecialchars($contexto['busca']) ?>" class="form-control" placeholder="Buscar por descrição">
    </div>
    <div class="col-md-2">
        <select name="mes" class="form-select">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= ($contexto['filtros']['mes'] == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>>
                    <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="ano" class="form-select">
            <?php for ($a = date('Y'); $a >= 2020; $a--): ?>
                <option value="<?= $a ?>" <?= ($contexto['filtros']['ano'] == $a) ? 'selected' : '' ?>><?= $a ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="conta_id" id="conta_id" class="form-select">
            <option value="">Selecione o Banco</option>
            <?php foreach ($contas as $conta): ?>
                <option value="<?= $conta['id'] ?>" <?= $contexto['filtros']['conta_id'] == $conta['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($conta['descricao']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<!-- Botão Novo -->
<div class="mb-3">
    <a href="<?= link_contextual(['path' => 'movimentacao_nova']) ?>" class="btn btn-success">+ Nova Movimentação</a>
</div>

<!-- Tabela -->
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <?php
            $colunas = [
                'id' => 'ID',
                'data' => 'Data',
                'descricao' => 'Descrição',
                'valor' => 'Valor',
                'conta_id' => 'Conta',
                'categoria_id' => 'Categoria',
                'codigo_pagamento' => 'Cód Pagto'
            ];
            foreach ($colunas as $campo => $titulo):
                $icone = ($contexto['ordem_campo'] == $campo)
                    ? ($contexto['ordem_direcao'] == 'ASC' ? '▲' : '▼')
                    : '';
                $nova_direcao = ($contexto['ordem_campo'] == $campo && $contexto['ordem_direcao'] == 'ASC') ? 'DESC' : 'ASC';
                $link = link_contextual([
                    'ordem_campo' => $campo,
                    'ordem_direcao' => $nova_direcao
                ]);
            ?>
                <th><a href="<?= $link ?>" class="text-decoration-none"><?= $titulo ?> <?= $icone ?></a></th>
            <?php endforeach; ?>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        foreach ($movimentacoes as $mov):
            $total += $mov['valor'];
        ?>
            <tr>
                <td><?= $mov['id'] ?></td>
                <td><?= date('d/m/Y', strtotime($mov['data'])) ?></td>
                <td><?= htmlspecialchars($mov['descricao']) ?></td>
                <td class="text-end"><?= destaque_valor($mov['valor']) ?></td>
                <td><?= htmlspecialchars($mov['conta_nome'] ?? '-') ?></td>
                <td><?= htmlspecialchars($mov['categoria_nome'] ?? '-') ?></td>
                <td><?= $mov['codigo_pagamento'] ?></td>
                <td>
                    <a href="<?= link_contextual(['path' => 'movimentacao_editar', 'id' => $mov['id']]) ?>" class="btn btn-sm btn-warning">✎</a>
                    <a href="<?= link_contextual(['path' => 'movimentacao_excluir', 'id' => $mov['id']]) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirma excluir?')">🗑</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Total da Página:</th>
            <th class="text-end"><?= destaque_valor($total) ?></th>
            <th colspan="4"></th>
        </tr>
    </tfoot>
</table>

<!-- Paginação -->
<?php
$total_paginas = ceil($total_registros / $contexto['qtde_linhas']);
$pagina_atual = $contexto['pagina'];
?>
<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $pagina_atual == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= link_contextual(['pagina' => $pagina_atual - 1]) ?>">&laquo;</a>
        </li>

        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= ($i == $pagina_atual) ? 'active' : '' ?>">
                <a class="page-link" href="<?= link_contextual(['pagina' => $i]) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= $pagina_atual == $total_paginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= link_contextual(['pagina' => $pagina_atual + 1]) ?>">&raquo;</a>
        </li>
    </ul>
</nav>
