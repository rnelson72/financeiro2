<?php
function link_contextual($paramsExtra = []) {
    $base = array_merge($_GET, $paramsExtra);
    return '?' . http_build_query($base);
}
function destaque_valor($valor) {
    return $valor < 0
        ? '<span style="color:red">R$ ' . number_format($valor, 2, ',', '.') . '</span>'
        : 'R$ ' . number_format($valor, 2, ',', '.');
}

// Para montar o value do input type=month corretamente
$mes_ano_value = '';
if (!empty($contexto['filtros']['ano']) && !empty($contexto['filtros']['mes'])) {
    $mes_ano_value = $contexto['filtros']['ano'] . '-' . str_pad($contexto['filtros']['mes'], 2, '0', STR_PAD_LEFT);
}
?>
<h2 class="mb-4">Movimentações Financeiras</h2>



<div class="row align-items-end mb-3 g-2">
    <!-- Botão Novo -->
    <div class="col-12 col-md-2 order-1 order-md-2">
        <a href="?path=movimentacao_nova" class="btn btn-success w-100">+ Nova Movimentação</a>
    </div>
    <!-- Filtros -->
    <div class="col-12 col-md-10 order-2 order-md-1">
        <form method="GET" class="row g-2">
            <input type="hidden" name="path" value="movimentacao">
            <input type="hidden" name="carregado" value="1">
            <div class="col-lg-3 col-sm-6">
                <input type="text" name="busca"
                       value="<?= htmlspecialchars($contexto['busca']) ?>"
                       class="form-control" placeholder="Buscar por descrição">
            </div>
            <div class="col-lg-3 col-sm-6">
                <?php
                $mes_ano_value = '';
                if (!empty($contexto['filtros']['ano']) && !empty($contexto['filtros']['mes'])) {
                    $mes_ano_value = $contexto['filtros']['ano'] . '-' . str_pad($contexto['filtros']['mes'], 2, '0', STR_PAD_LEFT);
                }
                // Se um dos dois for vazio (após limpar), o value vai vazio mesmo!
                ?>
                <input type="month" name="mes_ano"
                       value="<?= htmlspecialchars($mes_ano_value) ?>"
                       class="form-control">
            </div>
            <div class="col-lg-3 col-sm-6">
                <select name="banco_id" class="form-select">
                    <option value="">Selecione o Banco</option>
                    <?php foreach ($bancos as $bb): ?>
                        <option value="<?= $bb['id'] ?>" <?= $contexto['filtros']['banco_id'] == $bb['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($bb['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-sm-6">
                <input type="number" name="qtde_linhas"
                       value="<?= htmlspecialchars($contexto['qtde_linhas']) ?>"
                       class="form-control" min="5" max="100" placeholder="Qtd linhas"
                       title="Linhas por página">
            </div>
            <div class="col-lg-1 col-12 d-flex gap-1">
                <button class="btn btn-primary w-100" type="submit">Filtrar</button>
                <a href="?path=movimentacao&limpar=1" class="btn btn-secondary w-100">Limpar</a>
            </div>
        </form>
    </div>
</div>
<!-- Contador de resultados -->
<div class="small mb-2 text-end text-muted">
    Exibindo <strong><?= count($movimentacoes) ?></strong> de <strong><?= $total_registros ?></strong>
</div>

<!-- Tabela -->
<table class="table table-bordered datatable table-hover">
    <thead>
        <tr>
            <?php
            $colunas = [
                'id' => 'ID',
                'data' => 'Data',
                'descricao' => 'Descrição',
                'valor' => 'Valor',
                'banco_id' => 'Banco',
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
                <th>
                    <a href="<?= $link ?>" class="text-decoration-none"><?= $titulo ?> <?= $icone ?></a>
                </th>
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
                <td><?= htmlspecialchars($mov['banco_nome'] ?? '-') ?></td>
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
$total_paginas = max(1, ceil($total_registros / $contexto['qtde_linhas']));
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