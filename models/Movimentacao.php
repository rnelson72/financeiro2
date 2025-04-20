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
            $filtros_sql[] = "(descricao LIKE ?)";
            $params[] = '%' . $contexto['busca'] . '%';
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

        if (!empty($contexto['filtros']['conta_id'])) {
            $filtros_sql[] = "conta_id = ?";
            $params[] = $contexto['filtros']['conta_id'];
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
                    b.descricao AS conta_nome
                FROM movimentacao m
                LEFT JOIN categoria c ON m.categoria_id = c.id
                LEFT JOIN banco b ON m.conta_id = b.id
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

        if (!empty($contexto['filtros']['conta_id'])) {
            $filtros_sql[] = "conta_id = ?";
            $params[] = $contexto['filtros']['conta_id'];
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
            (data, descricao, valor, categoria_id, conta_id, codigo_pagamento, fatura_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $dados['data'],
            $dados['descricao'],
            $dados['valor'],
            $dados['categoria_id'],
            $dados['conta_id'],
            $dados['codigo_pagamento'],
            $dados['fatura_id']
        ]);
    }

    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE movimentacao SET
            data = ?, descricao = ?, valor = ?, categoria_id = ?, conta_id = ?, codigo_pagamento = ?, fatura_id = ?
            WHERE id = ?");
        $stmt->execute([
            $dados['data'],
            $dados['descricao'],
            $dados['valor'],
            $dados['categoria_id'],
            $dados['conta_id'],
            $dados['codigo_pagamento'],
            $dados['fatura_id'],
            $id
        ]);
    }
}
