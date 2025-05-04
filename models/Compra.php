<?php

class Compra  {
    private $pdo;

    /**
     * Construtor da classe Compra.
     * @param PDO $pdo Instância da conexão PDO.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lista as compras com base em filtros, busca, ordenação e paginação.
     *
     * @param array $contexto Array contendo: busca, filtros (mes, ano, cartao_id, categoria_id),
     *                         ordem_campo, ordem_direcao, qtde_linhas, pagina.
     * @return array Lista de compras encontradas.
     */
    public function listarComContexto(array $contexto): array {
        $filtros_sql = [];
        $params = [];
        $joins_sql = "LEFT JOIN categoria cat ON c.categoria_id = cat.id
                      LEFT JOIN cartao cart ON c.cartao_id = cart.id
                      LEFT JOIN final_cartao final ON c.final_cartao_id = final.id"; // Joins necessários para busca e exibição

        // Filtro por busca genérica
        if (!empty($contexto['busca'])) {
            // Busca em campos da compra, nome da categoria e nomes dos cartões
            $filtros_sql[] = "(
                c.descricao LIKE :busca
                OR DATE_FORMAT(c.data, '%d/%m/%Y') LIKE :busca
                OR CAST(c.valor AS CHAR) LIKE :busca
                OR cat.descricao LIKE :busca
                OR cart.descricao LIKE :busca
                OR final.final LIKE :busca   
            )";
            $params[':busca'] = '%' . $contexto['busca'] . '%';
        }

        // Filtros específicos (adicionar outros se necessário)
        if (!empty($contexto['filtros']['mes'])) {
            $filtros_sql[] = "MONTH(c.data) = :mes";
            $params[':mes'] = $contexto['filtros']['mes'];
        }

        if (!empty($contexto['filtros']['ano'])) {
            $filtros_sql[] = "YEAR(c.data) = :ano";
            $params[':ano'] = $contexto['filtros']['ano'];
        }

        if (!empty($contexto['filtros']['cartao_id'])) {
            $filtros_sql[] = "c.cartao_id = :cartao_id";
            $params[':cartao_id'] = $contexto['filtros']['cartao_id'];
        }

        // Monta cláusula WHERE
        $where = '';
        if (!empty($filtros_sql)) {
            $where = 'WHERE ' . implode(' AND ', $filtros_sql);
        }

        // Ordenação (Validação do campo DEVE ocorrer ANTES, no Controller/Helper)
        // Usar placeholders aqui é complicado e geralmente não traz benefícios de segurança se a validação já foi feita.
        // Certifique-se que $contexto['ordem_campo'] e $contexto['ordem_direcao'] são seguros.
        $ordemValida = $contexto['ordem_campo'] ?? 'data'; // Campo padrão se não vier
        $direcaoValida = strtoupper($contexto['ordem_direcao'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC'; // Garante ASC ou DESC
        $orderBy = "ORDER BY " . $ordemValida . " " . $direcaoValida;

        // Paginação (Validação/Casting para int DEVE ocorrer ANTES)
        $limite = (int)($contexto['qtde_linhas'] ?? 10);
        $pagina = (int)($contexto['pagina'] ?? 1);
        $offset = ($pagina - 1) * $limite;

        $sql = "
            SELECT
                c.*,
                cat.descricao AS categoria_nome,
                cart.descricao AS cartao_nome,
                final.final AS final_nome
            FROM
                compras c
            {$joins_sql}
            {$where}
            {$orderBy}
            LIMIT {$limite} OFFSET {$offset}
        ";
        // Nota: Usamos parâmetros nomeados (:busca, :mes, etc.) para clareza
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Conta o total de compras com base nos mesmos filtros e busca de listarComContexto.
     * IMPORTANTE: Inclui os JOINS e a condição de BUSCA completa para consistência.
     *
     * @param array $contexto Array contendo: busca, filtros (mes, ano, cartao_id, categoria_id).
     * @return int Total de compras encontradas.
     */
    public function contarComContexto(array $contexto): int {
        $filtros_sql = [];
        $params = [];
        // Os mesmos joins são necessários se a busca ou filtros dependem deles
        $joins_sql = "LEFT JOIN categoria cat ON c.categoria_id = cat.id
                      LEFT JOIN cartao cart ON c.cartao_id = cart.id
                      LEFT JOIN final_cartao final ON c.final_cartao_id = final.id"; // Joins necessários para busca e exibição

        // Filtro por busca genérica (IDÊNTICO AO listarComContexto)
        if (!empty($contexto['busca'])) {
            $filtros_sql[] = "(
                c.descricao LIKE :busca
                OR DATE_FORMAT(c.data, '%d/%m/%Y') LIKE :busca
                OR CAST(c.valor AS CHAR) LIKE :busca
                OR cat.descricao LIKE :busca
                OR cart.descricao LIKE :busca
                OR final.final LIKE :busca   
            )";
            $params[':busca'] = '%' . $contexto['busca'] . '%';
        }

        // Filtros específicos (IDÊNTICOS AO listarComContexto)
        if (!empty($contexto['filtros']['mes'])) {
            $filtros_sql[] = "MONTH(c.data) = :mes";
            $params[':mes'] = $contexto['filtros']['mes'];
        }

        if (!empty($contexto['filtros']['ano'])) {
            $filtros_sql[] = "YEAR(c.data) = :ano";
            $params[':ano'] = $contexto['filtros']['ano'];
        }

        if (!empty($contexto['filtros']['cartao_id'])) {
            $filtros_sql[] = "c.cartao_id = :cartao_id";
            $params[':cartao_id'] = $contexto['filtros']['cartao_id'];
        }


        // Monta cláusula WHERE
        $where = '';
        if (!empty($filtros_sql)) {
            $where = 'WHERE ' . implode(' AND ', $filtros_sql);
        }

        // Query para contar
        $sql = "SELECT COUNT(c.id) as total
                FROM compras c
                {$joins_sql}
                {$where}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna o total ou 0 se não encontrar
        return (int)($resultado['total'] ?? 0);
    }

    /**
     * Busca uma única compra pelo seu ID.
     *
     * @param int $id ID da compra.
     * @return array|false Retorna a compra como array associativo ou false se não encontrada.
     */
    public function buscarPorId(int $id) {
        // Pode ser útil fazer JOIN aqui também para já trazer os nomes, se necessário na edição
        $sql = "SELECT * FROM compras c WHERE c.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna false se não encontrar
    }

    /**
     * Insere uma nova compra no banco de dados.
     *
     * @param array $dados Dados da compra a serem inseridos.
     *                     Esperado: cartao_id, final_cartao_id (pode ser null), data,
     *                     descricao (pode ser null), valor, parcelas, parcela_atual,
     *                     categoria_id (pode ser null).
     *                     fatura_id NÃO é gerenciado aqui.
     * @return string|false Retorna o ID do último registro inserido ou false em caso de erro.
     */
    public function inserir(array $dados) {
        $sql = "INSERT INTO compras
                    (cartao_id, final_cartao_id, data, descricao, valor, parcelas, parcela_atual, categoria_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $sucesso = $stmt->execute([
            $dados['cartao_id'],
            $dados['final_cartao_id'] ?? null, // Usa null se não existir no array
            $dados['data'],
            $dados['descricao'] ?? null,
            $dados['valor'],
            $dados['parcelas'] ?? 1,
            $dados['parcela_atual'] ?? 1,
            $dados['categoria_id'] ?? null
        ]);

        return $sucesso ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Atualiza um registro de compra existente no banco de dados.
     *
     * @param int $id ID da compra a ser atualizada.
     * @param array $dados Array associativo contendo os dados a serem atualizados.
     *                     Ex: ['cartao_id' => 1, 'data' => '2024-01-15', 'valor' => 100.50, ...]
     * @return int|false Retorna o ID da compra atualizada em caso de sucesso, ou false em caso de falha.
     */
    public function atualizar(int $id, array $dados): int|false
    {

        // Monta a query (considerar adicionar updated_at = NOW() se tiver o campo)
        $sql = "UPDATE compras SET
                    cartao_id = ?,
                    final_cartao_id = ?,
                    data = ?,
                    descricao = ?,
                    valor = ?,
                    parcelas = ?,
                    parcela_atual = ?,
                    categoria_id = ?
                    -- , updated_at = NOW() -- Descomente se tiver este campo
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);

        // Executa a query
        $sucesso = $stmt->execute([
            // Garanta que as chaves existam ou use ?? null com cuidado
            $dados['cartao_id'],                    // Assumindo que sempre existe
            $dados['final_cartao_id'] ?? null,
            $dados['data'],                         // Assumindo que sempre existe
            $dados['descricao'] ?? null,
            $dados['valor'],                        // Assumindo que sempre existe
            $dados['parcelas'] ?? 1,
            $dados['parcela_atual'] ?? 1,
            $dados['categoria_id'] ?? null,
            $id                                     // ID para a cláusula WHERE
        ]);

        // Verifica o sucesso da execução
        if ($sucesso) {
            // Se a execução foi bem-sucedida, retorna o ID que foi atualizado.
            // Opcionalmente, você pode verificar $stmt->rowCount() > 0 se quiser
            // retornar false caso a query rode mas nenhuma linha seja afetada
            // (ex: ID não existe ou dados são idênticos). Mas geralmente,
            // retornar o ID se execute() for true é suficiente.
            return $id;
        } else {
            // Se execute() retornou false, houve uma falha.
            error_log("Falha ao executar atualização para compra ID {$id}. Erro PDO: " . implode(" - ", $stmt->errorInfo()));
            return false;
        }
    }

    /**
     * Gera e insere os registros das parcelas restantes com base nos dados fornecidos.
     * Replicação dos dados: descricao, valor, data (compra), categoria_id, cartao_id, final_cartao_id, user_id.
     * Incremento: 'parcela_atual'.
     * Definição fixa: 'fatura_id' = NULL, 'pago' = 0 para as novas parcelas.
     *
     * NÃO gera parcelas se os dados base fornecidos já tiverem um 'fatura_id' não nulo.
     * Gera parcelas de (parcela_atual + 1) até 'parcelas'.
     *
     * IMPORTANTE: Esta função deve ser chamada DENTRO de uma transação no Controller.
     *
     * @param array $dadosBase Dados da parcela atual que serve como base para as futuras.
     *                         Deve conter as chaves do schema: 'data', 'descricao', 'valor', 'parcelas', 'parcela_atual',
     *                         'categoria_id', 'cartao_id', 'final_cartao_id', 'fatura_id', e também 'user_id'.
     * @return bool True se as parcelas foram geradas com sucesso OU se não era necessário/permitido gerar. False em caso de erro.
     * @throws PDOException Se ocorrer um erro no banco de dados durante a preparação ou execução.
     */
    public function gerarParcelasRestantes(array $dadosBase): bool
    {
        $numeroTotalParcelas = (int)($dadosBase['parcelas'] ?? 1);
        $parcelaBase = (int)($dadosBase['parcela_atual'] ?? 1);
        $faturaIdBase = $dadosBase['fatura_id'] ?? null; // Pega o fatura_id dos dados base

        // --- Verificações Iniciais ---
        // 1. Crucial: Não gerar se a compra base JÁ tem fatura_id
        if (!is_null($faturaIdBase)) {
            error_log("GerarParcelasRestantes: Geração cancelada. Dados base já possuem fatura_id ({$faturaIdBase}).");
            return true; // Não é erro, apenas não permitido.
        }

        // 2. Não gerar se já é a última parcela ou se o número de parcelas é inválido
        if ($parcelaBase >= $numeroTotalParcelas) {
            return true; // Não há parcelas subsequentes a gerar.
        }

        // --- Preparação para Multi-Row Insert ---
        $parcelasParaGerar = [];
        $valoresParaBind = [];
        $numeroParcelaInicialGeracao = $parcelaBase + 1;

        // Dados que serão replicados (extrair uma vez)
        // Usar 'data' conforme schema
        $dataCompra = $dadosBase['data'];
        $descricao = $dadosBase['descricao'] ?? null;
        $valor = $dadosBase['valor']; // Assumir que validação ocorreu antes
        $categoriaId = $dadosBase['categoria_id'] ?? null;
        $cartaoId = $dadosBase['cartao_id']; // Schema diz NOT NULL
        $finalCartaoId = $dadosBase['final_cartao_id'] ?? null;


        for ($i = $numeroParcelaInicialGeracao; $i <= $numeroTotalParcelas; $i++) {
            // Cria um placeholder para cada conjunto de valores
            $parcelasParaGerar[] = '(?, ?, ?, ?, ?, ?, ?, ?)'; // pago=0, fatura_id=NULL

            // Adiciona os valores na ordem correta para este conjunto
            $valoresParaBind[] = $descricao;
            $valoresParaBind[] = $valor;
            $valoresParaBind[] = $dataCompra; // data
            $valoresParaBind[] = $numeroTotalParcelas; // parcelas (total)
            $valoresParaBind[] = $i; // parcela_atual
            $valoresParaBind[] = $categoriaId;
            $valoresParaBind[] = $cartaoId;
            $valoresParaBind[] = $finalCartaoId;
            // Note: pago (0) e fatura_id (NULL) estão fixos na string SQL
        }

        // Se não há valores para bindar (caso raro, mas seguro verificar), retorna sucesso
        if (empty($valoresParaBind)) {
            return true;
        }

        // --- Montagem e Execução da Query ---
        // Usar 'data' no lugar de 'data_compra' conforme schema
        // Adicionar user_id, pago, obs (se existir), created_at, updated_at se necessário
        $sqlBase = "INSERT INTO compras
                    (descricao, valor, data, parcelas, parcela_atual,
                    categoria_id, cartao_id, final_cartao_id)
                    VALUES ";

        $sqlCompleta = $sqlBase . implode(', ', $parcelasParaGerar);
        echo $sqlCompleta;
        echo $valoresParaBind;
        $stmt = $this->pdo->prepare($sqlCompleta);
        // Executa a query com todos os valores concatenados
        if ($stmt->execute($valoresParaBind)) {
            return true; // Sucesso
        } else {
            error_log("Falha ao executar multi-row insert em gerarParcelasRestantes. SQL: " . $sqlCompleta);
            return false; // Falha na execução
        }
    }

    public function listarPorCartaoSemFatura($cartao_id){
        // Pode ser útil fazer JOIN aqui também para já trazer os nomes, se necessário na edição
        $sql = "SELECT 
                    c.*,
                    categoria.descricao AS categoria_nome
                FROM compras c
                INNER JOIN categoria ON categoria.id = c.categoria_id
                WHERE c.cartao_id = ?
                AND c.fatura_id IS NULL
                AND c.data < NOW()
                AND c.parcela_atual = (
                        SELECT MIN(c2.parcela_atual)
                        FROM compras c2
                        WHERE c2.cartao_id = c.cartao_id
                        AND c2.descricao = c.descricao
                        AND c2.valor = c.valor
                        AND c2.data = c.data
                        AND c2.parcelas = c.parcelas
                        AND c2.fatura_id IS NULL
                    )
                ORDER BY c.data, c.descricao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cartao_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 

    }

    public function somarValorPorIds($comprasSelecionadas) {
        if (empty($comprasSelecionadas)) {
            return 0;
        }
        // Monta as interrogações separadas por vírgula
        $placeholders = implode(',', array_fill(0, count($comprasSelecionadas), '?'));
    
        $sql = "SELECT SUM(valor) as soma FROM compras WHERE id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($comprasSelecionadas); // Aqui, como array, vai preencher cada ?
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return floatval($row['soma'] ?? 0);
    }

    public function atualizarFaturaEmLote(array $compraIds, int $faturaId): bool {
        if (empty($compraIds)) {
            return false;
        }
        
        // Gera o número correto de placeholders
        $placeholders = implode(',', array_fill(0, count($compraIds), '?'));
        
        // Monta o SQL
        $sql = "UPDATE compras SET fatura_id = ? WHERE id IN ($placeholders)";
        
        $params = array_merge([$faturaId], $compraIds); // faturaId primeiro, depois os ids
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function listarPorFatura($faturaId) {
        $sql = "SELECT c.*, categoria.descricao as categoria_nome
                FROM compras c 
                LEFT JOIN categoria ON categoria.id = c.categoria_id
                WHERE c.fatura_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$faturaId]); // Corrigido aqui!
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}