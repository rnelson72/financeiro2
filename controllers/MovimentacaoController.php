<?php

function listar_movimentacoes($pdo) {
    $model = new Movimentacao($pdo);
    $contexto = capturar_contexto();

    $movimentacoes = $model->listarComContexto($contexto);
    $total_registros = $model->contarComContexto($contexto);

    $titulo = 'Movimentações Financeiras';
    $conteudo = '../views/movimentacao/index.php';
    include '../views/layout.php';
}

function movimentacao_nova($pdo) {
    $registro = [];
    $contexto = capturar_contexto();

    $categoriaModel = new Categoria($pdo);
    $bancoModel = new Banco($pdo);
    $categorias = $categoriaModel->listarTodos();
    $contas = $bancoModel->listarTodos();

    // Recupera o último código_pagamento
    $ultimo = $pdo->query("SELECT MAX(codigo_pagamento) AS ultimo FROM movimentacao")->fetch(PDO::FETCH_ASSOC);
    $registro['codigo_pagamento'] = ($ultimo['ultimo'] ?? 0) + 1;

    $titulo = 'Nova Movimentação';
    $conteudo = '../views/movimentacao/form.php';
    include '../views/layout.php';
}

function movimentacao_editar($pdo) {
    $model = new Movimentacao($pdo);
    $registro = $model->buscarPorId($_GET['id']);
    $contexto = capturar_contexto();

    $titulo = 'Editar Movimentação';
    $conteudo = '../views/movimentacao/form.php';
    include '../views/layout.php';
}

function movimentacao_excluir($pdo) {
    $stmt = $pdo->prepare("DELETE FROM movimentacao WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    $queryString = http_build_query(capturar_contexto_para_url());
    header("Location: ?path=movimentacao&$queryString");
    exit;
}

function movimentacao_salvar($pdo) {
    $model = new Movimentacao($pdo);
    $dados = [
        'data'             => $_POST['data'],
        'descricao'        => $_POST['descricao'],
        'valor'            => $_POST['valor'],
        'categoria_id'     => $_POST['categoria_id'] ?? null,
        'conta_id'         => $_POST['conta_id'] ?? null,
        'codigo_pagamento' => $_POST['codigo_pagamento'] ?? null,
        'fatura_id'        => $_POST['fatura_id'] ?? null
    ];

    if (!empty($_POST['id'])) {
        $model->atualizar($_POST['id'], $dados);
    } else {
        $model->inserir($dados);
    }

    $queryString = http_build_query(capturar_contexto_para_url());
    header("Location: ?path=movimentacao&$queryString");
    exit;
}

function capturar_contexto() {
    return [
        'busca' => $_GET['busca'] ?? '',
        'ordem_campo' => $_GET['ordem_campo'] ?? 'data',
        'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
        'pagina' => $_GET['pagina'] ?? 1,
        'qtde_linhas' => $_GET['qtde_linhas'] ?? 20,
        'filtros' => [
            'mes' => $_GET['mes'] ?? date('m'),
            'ano' => $_GET['ano'] ?? date('Y'),
            'conta_id' => $_GET['conta_id'] ?? null
        ]
    ];
}

function capturar_contexto_para_url() {
    return [
        'busca' => $_GET['busca'] ?? '',
        'ordem_campo' => $_GET['ordem_campo'] ?? 'data',
        'ordem_direcao' => $_GET['ordem_direcao'] ?? 'DESC',
        'pagina' => $_GET['pagina'] ?? 1,
        'qtde_linhas' => $_GET['qtde_linhas'] ?? 20,
        'mes' => $_GET['mes'] ?? date('m'),
        'ano' => $_GET['ano'] ?? date('Y'),
        'conta_id' => $_GET['conta_id'] ?? null
    ];
}
