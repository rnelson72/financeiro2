<?php
// ... (cabeçalho e variáveis como antes) ...

$tituloPagina = isset($registro['id']) && $registro['id'] ? 'Editar Compra no Cartão' : 'Nova Compra no Cartão';
$titulo = $titulo ?? $tituloPagina;

?>
<?php
$linkVoltar = '?path=compras&' . $contextoUrl;
?>
<h2 class="mb-4"><?= htmlspecialchars($titulo) ?></h2>

<form method="POST" action="?path=compras_salvar&<?= $contextoUrl ?>">
    <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token_csrf ?? '') ?>">
    <input type="hidden" name="busca" value="<?= htmlspecialchars($contexto['busca'] ?? '') ?>">
    <input type="hidden" name="ordem_campo" value="<?= htmlspecialchars($contexto['ordem_campo'] ?? 'data') ?>">
    <input type="hidden" name="ordem_direcao" value="<?= htmlspecialchars($contexto['ordem_direcao'] ?? 'DESC') ?>">
    <input type="hidden" name="pagina" value="<?= htmlspecialchars($contexto['pagina'] ?? 1) ?>">
    <input type="hidden" name="qtde_linhas" value="<?= htmlspecialchars($contexto['qtde_linhas'] ?? 20) ?>">
    <input type="hidden" name="mes_ano" value="<?=
        (isset($contexto['filtros']['ano']) && isset($contexto['filtros']['mes']))
            ? $contexto['filtros']['ano'] . '-' . str_pad($contexto['filtros']['mes'], 2, '0', STR_PAD_LEFT)
            : ''
    ?>">
    <input type="hidden" name="cartao_id" value="<?= htmlspecialchars($contexto['filtros']['cartao_id'] ?? '') ?>">
    <?php if (isset($fatura_id)) { ?>
        <input type="hidden" name="fatura_id" value="<?= $fatura_id ?>">
    <?php } ?>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Data <span class="text-danger">*</span>:</label>
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($registro['data'] ?? date('Y-m-d')) ?>" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Descrição:</label>
            <input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($registro['descricao'] ?? '') ?>" maxlength="255">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">Valor <span class="text-danger">*</span>:</label>
            <input type="number" step="0.01" name="valor" class="form-control" value="<?= htmlspecialchars($registro['valor'] ?? '') ?>" placeholder="0,00" required min="0.01">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-1">
             <p class="form-text text-primary">Identifique o cartão: selecione o Cartão Principal <strong>OU</strong> o Final Utilizado (não ambos).</p>
             <?php if (isset($erros['cartao_identificacao'])): // Erro genérico para esta regra ?>
                <div class="alert alert-danger p-2" role="alert"><?= htmlspecialchars($erros['cartao_identificacao']) ?></div>
             <?php endif; ?>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Cartão Principal:</label>
            <select name="cartao_id" class="form-select">
                <option value="">-- Selecione o Cartão --</option>
                <?php if (!empty($cartao)): ?>
                    <?php foreach ($cartao as $cc): ?>
                        <?php $id = (int)$cc['id']; ?>
                        <option value="<?= $id ?>" <?= ($registro['cartao_id'] ?? '') == $id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cc['descricao']) // Ajuste 'descricao' ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if (isset($erros['cartao_id'])): // Erro específico se o ID for inválido ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($erros['cartao_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Final Utilizado:</label>
            <select name="final_cartao_id" class="form-select">
                <option value="">-- Selecione o Final --</option>
                <?php if (!empty($final)): ?>
                    <?php foreach ($final as $ff): ?>
                        <?php $id = (int)$ff['id']; ?>
                        <option value="<?= $id ?>" <?= ($registro['final_cartao_id'] ?? '') == $id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ff['final']) ?> (<?= htmlspecialchars($ff['titular'] ?? 'N/D') ?>) <?= $ff['is_virtual'] ? '[V]' : '' ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>Nenhum final disponível</option>
                <?php endif; ?>
            </select>
             <?php if (isset($erros['final_cartao_id'])): // Erro específico se o ID for inválido ?>
                <div class="text-danger small mt-1"><?= htmlspecialchars($erros['final_cartao_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Categoria:</label>
            <select name="categoria_id" class="form-select">
                <option value="">Nenhuma</option>
                 <?php if (!empty($categoria)): ?>
                    <?php foreach ($categoria as $cat): ?>
                        <?php $id = (int)$cat['id']; ?>
                        <option value="<?= $id ?>" <?= ($registro['categoria_id'] ?? '') == $id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
             <?php // Adicionar exibição de erro para categoria_id se necessário ?>
        </div>
    </div>

    <div class="row">
        <!-- Campo Nº Parcelas -->
        <div class="form-group">
            <label for="parcelas">Nº Parcelas:</label>
            <input type="number" class="form-control" id="parcelas" name="parcelas" value="<?= htmlspecialchars($compra['parcelas'] ?? 1) ?>" min="1" required>
        </div>

        <!-- Campo Parcela Atual (geralmente 1 para nova, ou o valor existente para editar) -->
        <div class="form-group">
            <label for="parcela_atual">Parcela Atual:</label>
            <input type="number" class="form-control" id="parcela_atual" name="parcela_atual" value="<?= htmlspecialchars($compra['parcela_atual'] ?? 1) ?>" min="1" required>
        </div>

        <!-- NOVO: Campo Gerar Outros (inicialmente oculto) -->
        <div class="form-group" id="gerar_outros_div" style="display: none;">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="gerar_outros" name="gerar_outros">
                <label class="form-check-label" for="gerar_outros">
                    Gerar automaticamente as outras parcelas?
                </label>
            </div>
            <small class="form-text text-muted">Marque esta opção para criar os registros das parcelas futuras com base nos dados desta primeira parcela.</small>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <?php
            if (isset($fatura_id) && strpos($fatura_id, '|') !== false) {
                list($id, $rota) = explode('|', $fatura_id, 2);
        ?>
            <a href="?path=<?= htmlspecialchars($rota) ?>&id=<?= htmlspecialchars($id) ?>" class="btn btn-secondary ms-2">Cancelar</a>
        <?php
            } else { ?>
            <a href="<?= $linkVoltar ?>" class="btn btn-secondary ms-2">Cancelar</a>
        <?php } ?>
    </div>

<!-- JavaScript (no final do arquivo ou em um JS separado) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parcelasInput = document.getElementById('parcelas');
        const gerarOutrosDiv = document.getElementById('gerar_outros_div');
        const parcelaAtualInput = document.getElementById('parcela_atual'); // Campo da parcela atual

        function toggleGerarOutros() {
            const numParcelas = parseInt(parcelasInput.value, 10) || 0;
            const numParcelaAtual = parseInt(parcelaAtualInput.value, 10) || 0; // Pega o valor da parcela atual

            // Mostrar apenas se nº parcelas > 1 E se for a primeira parcela sendo editada/criada
            if (numParcelas > 1 && numParcelaAtual === 1) {
                gerarOutrosDiv.style.display = 'block';
            } else {
                gerarOutrosDiv.style.display = 'none';
                // Desmarcar se ficar oculto para não enviar valor indesejado
                document.getElementById('gerar_outros').checked = false;
            }
        }

        // Verifica ao carregar a página (importante para edição)
        toggleGerarOutros();

        // Verifica ao mudar o valor das parcelas ou da parcela atual
        parcelasInput.addEventListener('input', toggleGerarOutros);
        parcelaAtualInput.addEventListener('input', toggleGerarOutros); // Adicionado listener para parcela atual
    });
</script>
</form>