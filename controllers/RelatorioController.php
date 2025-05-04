<?php

class RelatorioController {
    protected $pdo;
    protected $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Relatorio($this->pdo);
    }

    public function melhorCartao() {
        // Pega o dia atual e relativos
        $hoje = (int) date('j');
        $anteontem = (int) date('j', strtotime('-2 days'));
        $depoisAmanha = (int) date('j', strtotime('+2 days'));
        $ontem = (int) date('j', strtotime('-1 days'));
        $seteDias = (int) date('j', strtotime('+7 days'));

        // Consulta cartões
        $cartoes = $this->model->melhorCartao();

        $melhoresParaCompra = [];
        $fechandoAgora = [];
        $proximosVencimentos = [];

        foreach ($cartoes as $cartao) {
            $fechamento = (int) $cartao['dia_fechamento'];
            $vencimento = (int) $cartao['dia_vencimento'];

            if ($fechamento <= $anteontem) {
                $melhoresParaCompra[] = $cartao;
            }
            if ($fechamento >= $anteontem && $fechamento <= $depoisAmanha) {
                $fechandoAgora[] = $cartao;
            }
            if ($vencimento > $ontem && $vencimento <= $seteDias) {
                $proximosVencimentos[] = $cartao;
            }
        }

        // Ordena o melhores por fechamento decrescente (igual sua view fazia)
        usort($melhoresParaCompra, fn($a, $b) => $b['dia_fechamento'] <=> $a['dia_fechamento']);

        // Passa os arrays processados para a view:
        $titulo = "Dashboard";
        $conteudo = "../views/relatorio/melhorCartao.php";
        include "../views/layout.php";
    }

    public function sintetico($params = []) {
        // Posição segura: assume mês/ano atual se não vier nada do GET
        $mes = isset($_GET['mes']) ? sprintf('%02d', (int)$_GET['mes']) : date('m');
        $ano = isset($_GET['ano']) ? (int) $_GET['ano'] : date('Y');
    
        $ano_mes = $ano.'-'.$mes;
        $mesSelecionado = $mes;
        $anoSelecionado = $ano;

        $dados = $this->model->sintetico([$ano_mes]);
        // $dados já vem ordenado do banco!
        $total1 = $total2 = $total3 = null; // null para saber se existe
        foreach ($dados as &$row) {
            if ($row['conta'] === '1.') $total1 = $row['total'];
            if ($row['conta'] === '2.') $total2 = $row['total'];
            if ($row['conta'] === '3.') $total3 = $row['total'];
            if ($row['conta'] === '4.') {
                // Só calcula se conhece os totais
                if ($total1 !== null && $total2 !== null && $total3 !== null) {
                    $row['total'] = $total1 - ($total2 + $total3);
                } else {
                    $row['total'] = 0; // opcional: caso falte algum dos totais
                }
            }
        }
        unset($row); // boa prática ao usar referência

        $titulo = "Relatório Sintético";
        $conteudo = "../views/relatorio/sintetico.php";
        $scriptsHead = ['assets/css/style.css'];
        include "../views/layout.php";
    }

    public function analitico($params = []) {
        // Posição segura: assume mês/ano atual se não vier nada do GET
        $mes = isset($_GET['mes']) ? sprintf('%02d', (int)$_GET['mes']) : date('m');
        $ano = isset($_GET['ano']) ? (int) $_GET['ano'] : date('Y');
    
        $ano_mes = $ano.'-'.$mes;
        $mesSelecionado = $mes;
        $anoSelecionado = $ano;

        $dados = $this->model->analitico([$ano_mes]);
        // $dados já vem ordenado do banco!
        $total1 = $total2 = $total3 = null; // null para saber se existe
        foreach ($dados as &$row) {
            if ($row['conta'] === '1.') $total1 = $row['total'];
            if ($row['conta'] === '2.') $total2 = $row['total'];
            if ($row['conta'] === '3.') $total3 = $row['total'];
            if ($row['conta'] === '4.') {
                // Só calcula se conhece os totais
                if ($total1 !== null && $total2 !== null && $total3 !== null) {
                    $row['total'] = $total1 - ($total2 + $total3);
                } else {
                    $row['total'] = 0; // opcional: caso falte algum dos totais
                }
            }
        }
        unset($row); // boa prática ao usar referência

        $titulo = "Relatório Analítico";
        $conteudo = "../views/relatorio/analitico.php";
        $scriptsHead = ['assets/css/style.css'];
        include "../views/layout.php";
    }

    public function comprometimento($params = []) {
        $dados = $this->model->comprometimento($params);
        include 'templates/relatorios/comprometimento.php';
    }

    public function evolucao($params = []) {
        $modelCategoria = new Categoria($this->pdo);
        $todasCategorias = $modelCategoria->listarTodos("((Ativo=1) AND (Tipo='Despesa'))");
    
        // Recebe os IDs das categorias e o período em meses do GET
        $categoriasSelecionadas = isset($_GET['categorias']) ? array_map('intval', (array)$_GET['categorias']) : [];
        $meses = isset($_GET['meses']) ? (int)$_GET['meses'] : 3; // default para 3 meses, pode ajustar
    
        $dados = [];
        if (!empty($categoriasSelecionadas) && $meses) {
            foreach ($categoriasSelecionadas as $categoria_id) {
                // A ordem dos filtros deve bater com a ordem dos '?' no SQL do seu model
                $filtros = [$categoria_id, $meses];
                $dados[$categoria_id] = $this->model->evolucao($filtros);
            }
        }
    
        $titulo = "Evolução por Categoria";
        $conteudo = "../views/relatorio/evolucao.php";
        include "../views/layout.php";
    }
    
    public function top10_365d($params = []) {
        $dados = $this->model->top10_365d($params);
        // Variáveis obrigatórias pro layout
        $titulo = "TOP 10 Despesas";
        $conteudo = "../views/relatorio/top10_365d.php";
        include "../views/layout.php";
    }

    public function extrato() {
        // Posição segura: assume mês/ano atual se não vier nada do GET
        $mes = isset($_GET['mes']) ? (int) $_GET['mes'] : date('n');
        $ano = isset($_GET['ano']) ? (int) $_GET['ano'] : date('Y');
    
        $mesSelecionado = $mes;
        $anoSelecionado = $ano;
    
        $inicio = sprintf('%04d-%02d-01', $ano, $mes);
        $fim = date('Y-m-t', strtotime($inicio));
    
        $filtros = [
            'data_inicio' => $inicio,
            'data_fim'    => $fim,
        ];
    
        $dados = $this->model->extrato($filtros);
    
        // CSV (mantém igual ao anterior)
        if (isset($_GET['csv']) && $_GET['csv'] == 1) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="extrato_'.$mes.'_'.$ano.'.csv"');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Data', 'Categoria', 'Descrição', 'Valor', 'Pagamento']);
            foreach ($dados as $row) {
                fputcsv($output, [
                    $row['data'],
                    $row['categoria'],
                    $row['descricao'],
                    $row['valor'],
                    $row['pagamento']
                ]);
            }
            fclose($output);
            exit;
        }
    
        // Variáveis obrigatórias pro layout
        $titulo = "Extrato";
        $conteudo = "../views/relatorio/extrato.php";
        include "../views/layout.php";
    }
}
