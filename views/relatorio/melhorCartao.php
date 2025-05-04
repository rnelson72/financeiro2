<div class="d-flex flex-wrap">
    <!-- Post-it: Melhor cartão -->
    <div class="postit azul">
        <h5>💳 Melhor cartão p/ hoje</h5>
        <?php if ($melhoresParaCompra): ?>
            <ul>
                <?php foreach ($melhoresParaCompra as $index => $c): ?>
                    <li<?= $index === 0 ? ' style="color:red; font-weight:bold;"' : '' ?>>
                        <?= $c['descricao'] ?> (dia <?= $c['dia_fechamento'] ?>)<?= $index === 0 ? ' 🔥 Use esse!' : '' ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum cartão ideal.</p>
        <?php endif; ?>
    </div>

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