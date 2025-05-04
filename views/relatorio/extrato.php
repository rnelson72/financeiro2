<!-- ../views/relatorio/extrato.php -->

<h2><?=$titulo?></h2>

<form method="get" style="margin-bottom:20px;" action="">
    <input type="hidden" name="path" value="extrato">
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
    
    <!-- O botão faz download CSV dos dados do filtro atual -->
    <?php if (!empty($dados)): ?>
        <a href="?path=extrato&mes=<?=$mesSelecionado?>&ano=<?=$anoSelecionado?>&csv=1" >Download CSV</a>
    <?php endif; ?>
</form>

<?php if (!empty($dados)): ?>
    <table width="100%" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
        <thead>
            <tr style="background:#eee;">
                <th>Data</th>
                <th>Categoria</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Pagamento</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($dados as $row): ?>
            <tr>
                <td><?=htmlspecialchars(date('d/m/Y', strtotime($row['data'])))?></td>
                <td><?=htmlspecialchars($row['categoria'])?></td>
                <td><?=htmlspecialchars($row['descricao'])?></td>
                <td style="text-align:right;"><?=number_format($row['valor'], 2, ',', '.')?></td>
                <td><?=htmlspecialchars($row['pagamento'])?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div style="margin-top:20px;"><em>Nenhum lançamento no período selecionado.</em></div>
<?php endif; ?>