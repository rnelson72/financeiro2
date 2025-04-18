<?php
require_once __DIR__ . '/../config/database.php';

// Pega o dia de hoje e dias relativos
$hoje = (int) date('j'); // Dia do mês sem zero à esquerda
$anteontem = (int) date('j', strtotime('-2 days'));
$depoisAmanha = (int) date('j', strtotime('+2 days'));
$ontem = (int) date('j', strtotime('-1 days'));
$seteDias = (int) date('j', strtotime('+7 days'));

// Busca todos os cartões ativos
$stmt = $pdo->query("SELECT * FROM cartao WHERE ativo = 1");
$cartoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Classificações
$melhoresParaCompra = [];
$fechandoAgora = [];
$proximosVencimentos = [];

foreach ($cartoes as $cartao) {
    $fechamento = (int) $cartao['dia_fechamento'];
    $vencimento = (int) $cartao['dia_vencimento'];

    // Melhor para comprar: fechou até anteontem
    if ($fechamento <= $anteontem) {
        $melhoresParaCompra[] = $cartao;
    }

    // Está fechando agora: entre anteontem e depois de amanhã
    if ($fechamento >= $anteontem && $fechamento <= $depoisAmanha) {
        $fechandoAgora[] = $cartao;
    }

    // Vencimento nos próximos 7 dias: entre ontem e +7 dias
    if ($vencimento > $ontem && $vencimento <= $seteDias) {
        $proximosVencimentos[] = $cartao;
    }
}
?>

<div class="d-flex flex-wrap">
    <!-- Post-it: Melhor cartão -->
    <div class="postit azul">
        <h5>💳 Melhor cartão p/ hoje</h5>
        <?php if ($melhoresParaCompra): ?>
            <ul>
                <?php foreach ($melhoresParaCompra as $c): ?>
                    <li><?= $c['descricao'] ?> (fechou dia <?= $c['dia_fechamento'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum cartão ideal.</p>
        <?php endif; ?>
    </div>

    <!-- Post-it: Fechando agora -->
    <div class="postit amarelo">
        <h5>📅 Fechando agora</h5>
        <?php if ($fechandoAgora): ?>
            <ul>
                <?php foreach ($fechandoAgora as $c): ?>
                    <li><?= $c['descricao'] ?> (fecha dia <?= $c['dia_fechamento'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum cartão fechando.</p>
        <?php endif; ?>
    </div>

    <!-- Post-it: Vencimento próximo -->
    <div class="postit vermelho">
        <h5>📆 Vence nos próximos dias</h5>
        <?php if ($proximosVencimentos): ?>
            <ul>
                <?php foreach ($proximosVencimentos as $c): ?>
                    <li><?= $c['descricao'] ?> (dia <?= $c['dia_vencimento'] ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Tudo tranquilo por enquanto!</p>
        <?php endif; ?>
    </div>
</div>
