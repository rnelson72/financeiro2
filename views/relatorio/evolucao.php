<?php
// ======== FORMULÁRIO INICIAL ======== //
if (empty($dados)) {
?>
    <form method="get" style="max-width:500px;margin:40px auto;">
        <input type="hidden" name="path" value="evolucao">
        <label>Escolha até 3 categorias:</label><br>
        <?php for ($i = 0; $i < 3; $i++) { ?>
            <select name="categorias[]" style="width:100%;margin-bottom:6px;">
                <option value="">-- Nenhuma --</option>
                <?php foreach ($todasCategorias as $cat) { ?>
                    <option value="<?= (int) $cat['id'] ?>"
                        <?= (isset($_GET['categorias'][$i]) && $_GET['categorias'][$i]==$cat['id'] ? 'selected' : '') ?>>
                        <?= htmlspecialchars($cat['descricao']) ?>
                    </option>
                <?php } ?>
            </select><br>
        <?php } ?>
        <label for="meses">Período:</label>
        <select id="meses" name="meses" required>
            <option value="3">Últimos 3 meses</option>
            <option value="6">Últimos 6 meses</option>
            <option value="9">Últimos 9 meses</option>
            <option value="12">Últimos 12 meses</option>
        </select>
        <button type="submit" style="margin-left:16px;">Gerar Relatório</button>
    </form>
<?php
} else {
// ======== PREPARA OS DADOS PHP PARA O GRÁFICO ======== //

// IDs escolhidos pelo usuário
$cat_ids = [];
if (isset($_GET['categorias'])) {
    foreach((array)$_GET['categorias'] as $catId) {
        $catId = (int) $catId;
        if ($catId) $cat_ids[] = $catId;
    }
}

// Mapeia id => nome
$idToNome = [];
foreach ($todasCategorias as $cat) $idToNome[$cat['id']] = $cat['descricao'];

// Garante todos os meses únicos
$meses_unicos = [];
foreach ($dados as $cat_id => $lista) {
    foreach ($lista as $linha) {
        $meses_unicos[$linha['mes']] = true;
    }
}
$meses_unicos = array_keys($meses_unicos);

// Preenche matriz categoria x mês
$matriz = [];
foreach ($cat_ids as $cat_id) {
    foreach ($meses_unicos as $mes) {
        $matriz[$cat_id][$mes] = 0;
    }
    foreach ($dados[$cat_id] ?? [] as $linha) {
        $matriz[$cat_id][$linha['mes']] = (float)$linha['total'];
    }
}

// Paleta de cores para até 12 meses
$cores = ['#e6194b','#3cb44b','#ffe119','#4363d8','#f58231','#911eb4','#46f0f0','#f032e6','#bcf60c','#fabebe','#008080','#e6beff'];
?>

<div style="max-width:900px;margin:48px auto 24px;">
    <div style="text-align:right;margin-bottom:12px;">
        <label>Mudar visualização: </label>
        <select id="tipoVisualizacao">
            <option value="agrupado">Agrupado por Categoria</option>
            <option value="simples">Comparativo Puro (Categoria + Mês)</option>
        </select>
    </div>
    <canvas id="graficoEvolucao"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// -------- AGRUPADO --------
const labelsAgrupado = <?= json_encode(array_map(function($id) use ($idToNome) {
    return $idToNome[$id] ?? $id;
}, $cat_ids)) ?>;
const datasetsAgrupado = [];
<?php foreach ($meses_unicos as $i => $mes): ?>
datasetsAgrupado.push({
    label: "<?= date('m/Y', strtotime($mes.'-01')) ?>",
    data: <?= json_encode(array_map(function($cat_id) use ($matriz, $mes) {
        return $matriz[$cat_id][$mes] ?? 0;
    }, $cat_ids)) ?>,
    backgroundColor: "<?= $cores[$i % count($cores)] ?>"
});
<?php endforeach; ?>

// -------- SIMPLES --------
const labelsSimples = [];
const dataSimples = [];
<?php foreach($cat_ids as $cat_id): foreach($meses_unicos as $mes): ?>
labelsSimples.push("<?= addslashes($idToNome[$cat_id] ?? $cat_id).' ['.date('m/Y', strtotime($mes.'-01')).']' ?>");
dataSimples.push(<?= (float)($matriz[$cat_id][$mes] ?? 0) ?>);
<?php endforeach; endforeach; ?>

const colorSimple = "#4fc3f7";
const bgSimples = labelsSimples.map(() => colorSimple);

let chartObj = null;
function renderChart(tipo) {
    if(chartObj) chartObj.destroy();
    const ctx = document.getElementById("graficoEvolucao").getContext('2d');
    if (tipo === 'agrupado') {
        chartObj = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsAgrupado,
                datasets: datasetsAgrupado
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    title: { display: true, text: 'Evolução das Categorias (Agrupado por mês)' }
                },
                responsive: true,
                scales: {
                    x: { beginAtZero: true, title: { display: true, text: 'Total (R$)' } },
                    y: { title: { display: true, text: 'Categoria' } }
                }
            }
        });
    } else {
        chartObj = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsSimples,
                datasets: [{
                    label: 'Total (R$)',
                    data: dataSimples,
                    backgroundColor: bgSimples
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    title: { display: true, text: "Comparativo Puro: Categoria + Mês" }
                },
                responsive: true,
                scales: {
                    x: { beginAtZero: true, title: { display: true, text: 'Total (R$)' } },
                    y: { title: { display: true, text: 'Categoria/Mês' } }
                }
            }
        });
    }
}
document.getElementById('tipoVisualizacao').addEventListener('change', function(){
    renderChart(this.value);
});
renderChart('agrupado');
</script>
<?php } ?>