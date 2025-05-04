<?php

class FinalCartao {

    private PDO $pdo;
    private string $tabela = 'final_cartao';

    /**
     * Construtor da classe.
     * @param PDO $pdo Instância da conexão PDO.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lista todos os finais de cartão, permitindo filtrar por ativos/inativos e por cartão.
     * Útil para preencher comboboxes em formulários de inserção (apenas ativos)
     * e atualização (todos ou apenas ativos, dependendo da necessidade).
     * Opcionalmente inclui informações do cartão principal.
     *
     * @param bool $apenasAtivos Se true (padrão), retorna apenas registros com ativo = 1. Se false, retorna todos.
     * @param int|null $cartaoIdFiltro Se fornecido, filtra os resultados por este cartao_id.
     * @return array Lista de finais de cartão.
     */
    public function listarTodos(bool $apenasAtivos = true, ?int $cartaoIdFiltro = null): array {
        $sql = "SELECT
                    fc.id, fc.final, fc.cartao_id, fc.is_virtual, fc.titular, fc.ativo,
                    c.descricao as cartao_descricao, c.bandeira as cartao_bandeira
                FROM {$this->tabela} fc
                LEFT JOIN cartao c ON fc.cartao_id = c.id";

        $conditions = [];
        $params = [];

        if ($apenasAtivos) {
            $conditions[] = "fc.ativo = 1";
        }

        if ($cartaoIdFiltro !== null) {
            $conditions[] = "fc.cartao_id = :cartao_id";
            $params[':cartao_id'] = $cartaoIdFiltro;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Adiciona uma ordem padrão para consistência
        $sql .= " ORDER BY c.descricao ASC, fc.final ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um final de cartão pelo seu ID, independentemente do status ativo.
     * Essencial para carregar dados para formulários de edição.
     *
     * @param int $id ID do final do cartão.
     * @return array|null Retorna os dados do final ou null se não encontrado.
     */
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT fc.*, c.descricao as cartao_descricao
                FROM {$this->tabela} fc
                LEFT JOIN cartao c ON fc.cartao_id = c.id
                WHERE fc.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

     /**
     * Busca todos os finais associados a um cartão principal específico.
     * Permite escolher se quer incluir apenas os ativos ou todos.
     * Ideal para popular selects dinamicamente com base na seleção do cartão principal.
     *
     * @param int $cartaoId ID do cartão principal.
     * @param bool $apenasAtivos Se true (padrão), retorna apenas os finais ativos. Se false, retorna todos.
     * @return array Lista de finais encontrados para o cartão.
     */
    public function buscarPorCartaoId(int $cartaoId, bool $apenasAtivos = true): array {
        $sql = "SELECT * FROM {$this->tabela} WHERE cartao_id = :cartao_id";
        if ($apenasAtivos) {
            $sql .= " AND ativo = 1";
        }
        $sql .= " ORDER BY final ASC"; // Ordem lógica para selects

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cartao_id', $cartaoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insere um novo final de cartão no banco de dados.
     *
     * @param array $dados Dados do final (ex: ['final' => '1234', 'cartao_id' => 1, 'titular' => 'Nome', 'is_virtual' => 0, 'ativo' => 1]).
     * @return int|false Retorna o ID do registro inserido ou false em caso de falha.
     * @throws Exception Se dados obrigatórios faltarem ou validação falhar.
     */
    public function inserir(array $dados): int|false {
        // Validação básica
        if (empty($dados['final']) || empty($dados['cartao_id'])) {
             throw new Exception("Os campos 'final' e 'cartao_id' são obrigatórios para inserir um final de cartão.");
        }
        if (strlen((string)$dados['final']) !== 4 || !ctype_digit((string)$dados['final'])) {
             throw new Exception("O campo 'final' deve conter exatamente 4 dígitos numéricos.");
        }


        $sql = "INSERT INTO {$this->tabela} (final, cartao_id, titular, is_virtual, ativo)
                VALUES (:final, :cartao_id, :titular, :is_virtual, :ativo)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':final', $dados['final']);
        $stmt->bindValue(':cartao_id', (int)$dados['cartao_id'], PDO::PARAM_INT);
        $stmt->bindValue(':titular', $dados['titular'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':is_virtual', isset($dados['is_virtual']) ? (int)(bool)$dados['is_virtual'] : 0, PDO::PARAM_INT);
        // Garante que 'ativo' seja tratado corretamente (padrão 1 se não fornecido)
        $stmt->bindValue(':ativo', isset($dados['ativo']) ? (int)(bool)$dados['ativo'] : 1, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return (int)$this->pdo->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Atualiza um final de cartão existente.
     *
     * @param int $id ID do final a ser atualizado.
     * @param array $dados Dados a serem atualizados (pelo menos um campo).
     * @return bool Retorna true se a atualização foi bem-sucedida, false caso contrário.
     * @throws Exception Se o array de dados estiver vazio ou se a validação falhar.
     */
    public function atualizar(int $id, array $dados): bool {
        if (empty($dados)) {
            throw new Exception("Nenhum dado fornecido para atualização.");
        }

         if (isset($dados['final']) && (strlen((string)$dados['final']) !== 4 || !ctype_digit((string)$dados['final']))) {
             throw new Exception("O campo 'final' deve conter exatamente 4 dígitos numéricos.");
        }

        $sets = [];
        $params = [':id' => $id];
        foreach ($dados as $campo => $valor) {
            $campoPermitido = match ($campo) {
                'final' => 'final',
                'cartao_id' => 'cartao_id',
                'titular' => 'titular',
                'is_virtual' => 'is_virtual',
                'ativo' => 'ativo',
                default => null
            };

            if ($campoPermitido) {
                $placeholder = ':' . $campoPermitido;
                $sets[] = "{$campoPermitido} = {$placeholder}";

                 $tipoParam = PDO::PARAM_STR;
                 if (in_array($campoPermitido, ['cartao_id', 'is_virtual', 'ativo'])) {
                    $tipoParam = PDO::PARAM_INT;
                     // Trata 'is_virtual' e 'ativo' para garantir 0 ou 1
                     if (in_array($campoPermitido, ['is_virtual', 'ativo'])) {
                        $valor = (int)(bool)$valor; // Converte para booleano e depois para int (0 ou 1)
                     } else { // cartao_id
                        $valor = (int)$valor;
                     }
                 } elseif ($campoPermitido === 'titular' && is_null($valor)) {
                     $tipoParam = PDO::PARAM_NULL;
                 }
                 $params[$placeholder] = $valor;
            }
        }

        if (empty($sets)) {
             throw new Exception("Nenhum campo válido fornecido para atualização.");
        }

        $sql = "UPDATE {$this->tabela} SET " . implode(', ', $sets) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $placeholder => $valor) {
                $tipoParam = PDO::PARAM_STR;
                if ($placeholder === ':id' || str_ends_with($placeholder, '_id') || in_array($placeholder, [':is_virtual', ':ativo'])) {
                $tipoParam = PDO::PARAM_INT;
                } elseif (is_null($valor)) {
                $tipoParam = PDO::PARAM_NULL;
                }
                $stmt->bindValue($placeholder, $valor, $tipoParam);
        }

        return $stmt->execute();
    }

     /**
     * Desativa (marca ativo = 0) um final de cartão.
     *
     * @param int $id ID do final do cartão a ser desativado.
     * @return bool True se desativado com sucesso, false caso contrário.
     */
    public function desativar(int $id): bool {
        $sql = "UPDATE {$this->tabela} SET ativo = 0 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Reativa (marca ativo = 1) um final de cartão.
     * Pode ser útil se houver uma função de "restaurar".
     *
     * @param int $id ID do final do cartão a ser reativado.
     * @return bool True se reativado com sucesso, false caso contrário.
     */
    public function reativar(int $id): bool {
        $sql = "UPDATE {$this->tabela} SET ativo = 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }


} // Fim da classe FinalCartao