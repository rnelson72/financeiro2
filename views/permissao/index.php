<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">

            <!-- CARD PRINCIPAL -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary bg-gradient text-white d-flex align-items-center">
                    <i class="bi bi-shield-lock fs-4 me-2"></i>
                    <h2 class="mb-0 fs-4">Permissões de Usuário</h2>
                </div>
                <div class="card-body">

                    <!-- MENSAGEM DE SUCESSO -->
                    <?php if (!empty($mensagem_sucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check2-circle"></i>
                            <?= $mensagem_sucesso ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="?path=permissao">

                        <!-- SELEÇÃO DO USUÁRIO -->
                        <?php if (empty($usuario_id)): ?>
                            <div class="mb-4 col-md-6 mx-auto">
                                <div class="form-floating">
                                    <select name="usuario_id" class="form-select" id="floatingUsuario" required onchange="this.form.submit()">
                                        <option value="">Selecione um usuário...</option>
                                        <?php foreach ($usuarios as $u): ?>
                                            <option value="<?= $u['id'] ?>">
                                                <?= htmlspecialchars($u['nome']) ?> (<?= htmlspecialchars($u['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="floatingUsuario">
                                        <i class="bi bi-person-fill"></i> Usuário
                                    </label>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- PERMISSÕES -->
                        <?php if (!empty($usuario_id)): ?>
                            <!-- Recupera usuário selecionado -->
                            <?php
                            $usuario_sel = null;
                            foreach ($usuarios as $u) {
                                if ($u['id'] == $usuario_id) {
                                    $usuario_sel = $u;
                                    break;
                                }
                            }
                            ?>
                            <div class="row align-items-center mb-4">
                                <div class="col-md-8">
                                    <div class="badge rounded-pill bg-secondary fs-6 py-2 px-3 mb-2 d-inline-flex align-items-center">
                                        <i class="bi bi-person-check fs-5 me-2"></i>
                                        <?= htmlspecialchars($usuario_sel['nome'] ?? '') ?>
                                        <span class="ms-2 text-light-emphasis">
                                            (<?= htmlspecialchars($usuario_sel['email'] ?? '') ?>)
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.location='?path=permissao'">
                                        <i class="bi bi-arrow-left"></i> Trocar usuário
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario_id) ?>">

                            <!-- RESPONSIVO: CARDS POR GRUPO -->
                            <div class="row">
                                <?php
                                // Organiza transações por grupo (Garanta nomes iguais aos do banco)
                                $porGrupo = [
                                    'Cadastros' => [],
                                    'Financeiro' => [],
                                    'Administração' => [],
                                ];
                                foreach ($transacoes as $t) {
                                    $nomeGrupo = '-';
                                    foreach ($grupos_menu as $g) {
                                        if ($g['id'] == $t['grupo_id']) {
                                            $nomeGrupo = $g['nome'];
                                            break;
                                        }
                                    }
                                    if (isset($porGrupo[$nomeGrupo])) {
                                        $porGrupo[$nomeGrupo][] = $t;
                                    }
                                }
                                $permissoes_ids = array_column($permissoes_usuario, 'transacao_id');
                                $coresCartao = [
                                    'Cadastros' => 'info',
                                    'Financeiro' => 'warning',
                                    'Administração' => 'danger',
                                ];
                                foreach (['Cadastros', 'Financeiro', 'Administração'] as $grupo):
                                ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 border-<?= $coresCartao[$grupo] ?>">
                                            <div class="card-header bg-<?= $coresCartao[$grupo] ?> bg-opacity-25 fw-bold text-center">
                                                <i class="bi <?= 
                                                    $grupo === 'Cadastros' ? 'bi-journal-text' : 
                                                    ($grupo === 'Financeiro' ? 'bi-cash-coin' : 'bi-gear-fill') ?>"></i>
                                                <?= $grupo ?>
                                            </div>
                                            <div class="card-body py-3">
                                                <?php if (!empty($porGrupo[$grupo])): ?>
                                                    <?php foreach ($porGrupo[$grupo] as $t): ?>
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="permissoes[]"
                                                                value="<?= $t['id'] ?>"
                                                                id="transacao_<?= $t['id'] ?>"
                                                                <?= in_array($t['id'], $permissoes_ids) ? 'checked' : '' ?>>
                                                            <label class="form-check-label ms-2" for="transacao_<?= $t['id'] ?>">
                                                                <?= htmlspecialchars($t['nome']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Nenhuma transação neste grupo</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" name="salvar" class="btn btn-success fw-bold px-5 py-2 fs-5">
                                    <i class="bi bi-save"></i> Salvar Permissões
                                </button>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
            </div>

            <div class="text-center text-muted small">
                <i class="bi bi-shield-lock"></i> Gerenciamento seguro de permissões &mdash; <?= date('Y') ?>
            </div>
        </div>
    </div>
</div>