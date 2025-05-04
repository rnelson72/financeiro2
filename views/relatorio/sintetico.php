<h2><?=$titulo?></h2>

<form method="get" style="margin-bottom:20px;" action="">
    <input type="hidden" name="path" value="sintetico">
    <label for="mes">Mês:</label>
    <select name="mes" id="mes">
        <?php for ($i=1; $i<=12; $i++): ?>
            <option value="<?=$i?>" <?=($i == $mesSelecionado ? "selected" : "")?>>
                <?=str_pad($i, 2, '0', STR_PAD_LEFT)?>
            </option>
        <?php endfor; ?>
    </select>

    <label for="ano">Ano:</label>
    <input type="number" name="ano" id="ano" value="<?=$anoSelecionado?>" min="2020" max="<?=date('Y')?>">

    <button type="submit">OK</button>
    
</form>

<?php if (!empty($dados)): ?>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr style="background:#eee;">
                <th>Conta</th>
                <th>Descricao</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dados as $row): ?>
            <?php
                $classe = "";
                // Contas principais 1., 2., 3. etc
                if (preg_match('/^\d+\.$/', $row['conta'])) $classe = "tr-principal";
                // SUBTOTAL
                if ($row['categoria_tipo'] === "SUBTOTAL") $classe = "tr-subtotal";
                // Captura totais
                if ($row['conta'] === "4.") $classe = "tr-diferenca";
            ?>
            <tr class="<?=$classe?>">
                <td><?=htmlspecialchars($row['conta'])?></td>
                <td><?=htmlspecialchars($row['categoria_descricao'])?></td>
                <td class="td-valor"><?=number_format($row['total'], 2, ',', '.')?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div style="margin-top:20px;"><em>Nenhum lançamento no período selecionado.</em></div>
<?php endif; ?>