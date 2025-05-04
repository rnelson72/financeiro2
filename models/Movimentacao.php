<?php

class Movimentacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarComContexto($contexto) {
        $filtros_sql = [];
        $params = [];

        // Filtro por busca
        if (!empty($contexto['busca'])) {
            $filtros_sql[] = "(
                m.descricao LIKE ?
                OR DATE_FORMAT(m.data, '%d/%m/%Y') LIKE ?
                OR CAST(m.valor AS CHAR) LIKE ?
                OR CAST(m.codigo_pagamento AS CHAR) LIKE ?
                OR c.descricao LIKE ?
                OR b.descricao LIKE ?
            )";
            $busca = '%' . $contexto['busca'] . '%';
            // Repete para cada campo buscado acima
            $params[] = $busca; // descricao
            $params[] = $busca; // data
            $params[] = $busca; // valor
            $params[] = $busca; // codigo_pagamento
            $params[] = $busca; // categoria_nome (c.descricao)
            $params[] = $busca; // banco_nome (b.descricao)
        }
        
        // Filtros adicionais: mês, ano, conta
        if (!empty($contexto['filtros']['mes'])) {
            $filtros_sql[] = "MONTH(data) = ?";
            $params[] = $contexto['filtros']['mes'];
        }

        if (!empty($contexto['filtros']['ano'])) {
            $filtros_sql[] = "YEAR(data) = ?";
            $params[] = $contexto['filtros']['ano'];
        }

        if (!empty($contexto['filtros']['banco_id'])) {
            $filtros_sql[] = "banco_id = ?";
            $params[] = $contexto['filtros']['banco_id'];
        }

        // Monta cláusula WHERE
        $where = '';
        if (!empty($filtros_sql)) {
            $where = 'WHERE ' . implode(' AND ', $filtros_sql);
        }

        // Ordenação e paginação
        $ordem = $contexto['ordem_campo'] . ' ' . $contexto['ordem_direcao'];
        $limite = $contexto['qtde_linhas'];
        $offset = ($contexto['pagina'] - 1) * $limite;

        $sql = "
            SELECT m.*, 
                    c.descricao AS categoria_nome,
                    b.descricao AS banco_nome
                FROM movimentacao m
                LEFT JOIN categoria c ON m.categoria_id = c.id
                LEFT JOIN banco b ON m.banco_id = b.id
                $where
                ORDER BY $ordem
                LIMIT $limite OFFSET $offset
            ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarComContexto($contexto) {
        $filtros_sql = [];
        $params = [];

        if (!empty($contexto['busca'])) {
            $filtros_sql[] = "(descricao LIKE ?)";
            $params[] = '%' . $contexto['busca'] . '%';
        }

        if (!empty($contexto['filtros']['mes'])) {
            $filtros_sql[] = "MONTH(data) = ?";
            $params[] = $contexto['filtros']['mes'];
        }

        if (!empty($contexto['filtros']['ano'])) {
            $filtros_sql[] = "YEAR(data) = ?";
            $params[] = $contexto['filtros']['ano'];
        }

        if (!empty($contexto['filtros']['banco_id'])) {
            $filtros_sql[] = "banco_id = ?";
            $params[] = $contexto['filtros']['banco_id'];
        }

        $where = '';
        if (!empty($filtros_sql)) {
            $where = 'WHERE ' . implode(' AND ', $filtros_sql);
        }

        $sql = "SELECT COUNT(*) as total FROM movimentacao $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] ?? 0;
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM movimentacao WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO movimentacao 
            (data, descricao, valor, categoria_id, banco_id, codigo_pagamento, fatura_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $dados['data'],
            $dados['descricao'],
            $dados['valor'],
            $dados['categoria_id'],
            $dados['banco_id'],
            $dados['codigo_pagamento'],
            $dados['fatura_id']
        ]);
        return $this->pdo->lastInsertId(); // <-- retorna o último id inserido
    }
    
    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE movimentacao SET
            data = ?, descricao = ?, valor = ?, categoria_id = ?, banco_id = ?, codigo_pagamento = ?, fatura_id = ?
            WHERE id = ?");
        $stmt->execute([
            $dados['data'],
            $dados['descricao'],
            $dados['valor'],
            $dados['categoria_id'],
            $dados['banco_id'],
            $dados['codigo_pagamento'],
            $dados['fatura_id'],
            $id
        ]);
    }
    
    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM movimentacao WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getProximoCodigoPagamento() {
        $stmt = $this->pdo->query("SELECT MAX(codigo_pagamento) AS ultimo FROM movimentacao");
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se não existir nenhum registro ainda, começa do 1
        $proximo = (isset($ultimo['ultimo']) && $ultimo['ultimo'] !== null)
            ? $ultimo['ultimo'] + 1
            : 1;

        return $proximo;
    }

}
