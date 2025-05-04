<?php
function hsv2rgb($h, $s, $v, $a = 0.7) {
    $h = $h % 360;
    $c = $v * $s;
    $x = $c * (1 - abs(fmod($h / 60.0, 2) - 1));
    $m = $v - $c;
    if ($h < 60)      list($r, $g, $b) = [$c, $x, 0];
    elseif ($h < 120) list($r, $g, $b) = [$x, $c, 0];
    elseif ($h < 180) list($r, $g, $b) = [0, $c, $x];
    elseif ($h < 240) list($r, $g, $b) = [0, $x, $c];
    elseif ($h < 300) list($r, $g, $b) = [$x, 0, $c];
    else              list($r, $g, $b) = [$c, 0, $x];
    return sprintf('rgba(%d,%d,%d,%.2f)', ($r + $m) * 255, ($g + $m) * 255, ($b + $m) * 255, $a);
}

$categorias = [];
$valores = [];
foreach ($dados as $linha) {
    $categorias[] = $linha['categoria'];
    $valores[] = (float)$linha['total'];
}

// Gera as cores ao longo do arco-íris
$n = count($categorias);
$cores = [];
for ($i = 0; $i < $n; $i++) {
    // Hue de 0° a 300° com pequenos ajustes para evitar extremos repetidos
    $hue = intval(300 * $i / max($n - 1, 1));
    $cores[] = hsv2rgb($hue, 0.8, 0.85);
}
?>
<div style="max-width:900px;margin:auto;padding:24px;">
  <canvas id="despesasTop10"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const categorias = <?php echo json_encode($categorias); ?>;
const valores = <?php echo json_encode($valores); ?>;
const cores = <?php echo json_encode($cores); ?>;

const ctx = document.getElementById('despesasTop10').getContext('2d');
const chartData = {
    labels: categorias,
    datasets: [{
        label: 'Total Despesa (R$)',
        data: valores,
        backgroundColor: cores
    }]
};

const myChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      indexAxis: 'y',
      plugins: {
          title: {
              display: true,
              text: 'TOP 10 Despesas dos últimos 365 dias',
              color: '#fff', // cor do título
              font: {
                  weight: 'bold',
                  size: 20      // opcional: deixe maior/menor
              }
          },
          legend: {
              display: false
          }
      },
      responsive: true,
      scales: {
          x: {
              title: {
                  display: true,
                  text: 'Total (R$)',
                  color: '#fff',           // cor do título do eixo X
                  font: { weight: 'bold' }
              },
              ticks: {
                  color: '#fff',           // cor dos valores do eixo X
                  font: { weight: 'bold' }
              },
              grid: {
                  color: 'rgba(255,255,255,0.3)' // linhas de grade mais suaves/brancas
              },
              beginAtZero: true
          },
          y: {
              title: {
                  display: true,
                  text: 'Categoria',
                  color: '#fff',           // cor do título do eixo Y
                  font: { weight: 'bold' }
              },
              ticks: {
                  color: '#fff',           // cor das labels do eixo Y
                  font: { weight: 'bold' }
              },
              grid: {
                  color: 'rgba(255,255,255,0.3)'
              }
          }
      }
    }
});
</script>