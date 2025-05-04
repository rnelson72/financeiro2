<?php
class Relatorio {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function sintetico($filtros = []) {
        $sql = "SELECT c.conta, c.descricao AS categoria_descricao, c.tipo AS categoria_tipo,
                       CASE 
                           WHEN c.tipo = 'SUBTOTAL' THEN COALESCE(total_conta_mensal(c.id, ?),0)
                           ELSE COALESCE(sub.total, 0)
                       END AS total
                FROM categoria c
                LEFT JOIN (
                    SELECT m.categoria_id, SUM(m.valor) as total
                    FROM vw_receitas_despesas m
                    WHERE DATE_FORMAT(m.data, '%Y-%m') = ?
                    GROUP BY m.categoria_id
                ) sub ON sub.categoria_id = c.id
                WHERE c.ativo=1
                ORDER BY 1";
        $stmt = $this->pdo->prepare($sql);
        // repita parâmetro para ambos os lugares
        $stmt->execute([$filtros[0], $filtros[0]]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function analitico($filtros = []) {
        $sql = "SELECT c.conta, c.descricao AS categoria_descricao, c.tipo AS categoria_tipo, sub.data, sub.descr AS descricao,
                       IF(c.tipo = 'SUBTOTAL', COALESCE(total_conta_mensal(c.id, ?),0), COALESCE(sub.valor, 0) ) AS total
                FROM categoria c
                LEFT JOIN (
                    SELECT m.categoria_id, m.valor, m.data, m.descr
                    FROM vw_receitas_despesas m
                    WHERE DATE_FORMAT(m.data, '%Y-%m') = ?
                ) sub ON sub.categoria_id = c.id
                WHERE c.ativo=1
                ORDER BY conta, data, descricao";
        $stmt = $this->pdo->prepare($sql);
        // repita parâmetro para ambos os lugares
        $stmt->execute([$filtros[0], $filtros[0]]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function comprometimento($filtros = []) {
        // **Só usa compras não faturadas!**
        $sql = "SELECT ... FROM compras WHERE faturada = 0";
        // Filtros...
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function evolucao($filtros = []) {
        $sql = "SELECT 
                    DATE_FORMAT(data, '%Y-%m') as mes,
                    SUM(valor) AS total
                FROM vw_receitas_despesas
                WHERE categoria_id = ?
                  AND data >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                GROUP BY mes
                ORDER BY mes ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($filtros);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function top10_365d($filtros = []) {
        $sql = "
            SELECT c.descricao as categoria, SUM(valor) AS total
            FROM vw_receitas_despesas v
            INNER JOIN categoria c ON c.id = v.categoria_id
            WHERE c.tipo = 'DESPESA' 
              AND v.data >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)
            GROUP BY c.descricao
            ORDER BY total DESC
            LIMIT 10
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function extrato($filtros) {
        $sql = "
            SELECT v.data, c.descricao as categoria, v.descr as descricao, v.valor, v.pagamento 
              FROM vw_receitas_despesas v 
              INNER JOIN categoria c ON c.id = v.categoria_id 
             WHERE v.data >= :data_inicio 
               AND v.data <= :data_fim 
               AND c.tipo = 'DESPESA'
               AND (c.conta like '2.%' or c.conta like '3.%')
             ORDER BY v.data, categoria";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':data_inicio' => $filtros['data_inicio'],
            ':data_fim'    => $filtros['data_fim']
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function melhorCartao() {
        $stmt = $this->pdo->query("SELECT * FROM cartao WHERE ativo = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}