<?php
class MigrationController 
{
  protected $pdo;
  protected $pdoLegado;

  public function __construct($pdo, $pdoLegado) {
    $this->pdo = $pdo;
    $this->pdoLegado = $pdoLegado;   
  }

  public function migrateBanco()
  {
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $this->pdo->exec("DROP TABLE IF EXISTS banco");

    $this->pdo->exec("CREATE TABLE IF NOT EXISTS banco (
      id INT AUTO_INCREMENT PRIMARY KEY,
      descricao VARCHAR(20) NOT NULL,
      numero VARCHAR(10) NOT NULL,
      agencia VARCHAR(10) NOT NULL,
      conta VARCHAR(20) NOT NULL,
      titular VARCHAR(100),
      pix VARCHAR(100),
      ativo TINYINT(1) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
  
      // Lê dados do PostgreSQL
    $dados = $this->pdoLegado->query("SELECT * FROM bancos")->fetchAll(PDO::FETCH_ASSOC);

    $this->pdo->beginTransaction();
    try {
        foreach ($dados as $linha) {
            $stmt = $this->pdo->prepare("
                INSERT INTO banco (id, descricao, numero, agencia, conta, titular, pix, ativo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $linha['id'],
                $linha['descricao'],
                $linha['numero'],
                $linha['agencia'],
                $linha['conta'],
                null, // titular não existe no legado
                null, // pix também não existe
                $linha['ativo'] ?? 1
            ]);
        }
        $this->pdo->commit();
        echo "<p><strong>Bancos migrados com sucesso!</strong></p>";
    } catch (Exception $e) {
        $this->pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar bancos: {$e->getMessage()}</p>";
    }
  }

  public function migrateCartao()
  {
    // Limpa as tabelas de destino
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $this->pdo->exec("DROP TABLE IF EXISTS final_cartao");
    $this->pdo->exec("DROP TABLE IF EXISTS cartao");
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS cartao (
            id INT AUTO_INCREMENT PRIMARY KEY,
            descricao VARCHAR(50) NOT NULL,
            bandeira VARCHAR(10),
            dia_vencimento INT,
            dia_fechamento INT,
            linha_credito DECIMAL(10,2),
            banco_id INT,
            ativo INT DEFAULT 1,
            FOREIGN KEY (banco_id) REFERENCES banco(id) ON DELETE CASCADE
        );
    ");

    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS final_cartao (
            id INT AUTO_INCREMENT PRIMARY KEY,
            final VARCHAR(4) NOT NULL,
            cartao_id INT NOT NULL,
            is_virtual INT DEFAULT 0,
            titular VARCHAR(100),
            ativo INT DEFAULT 1,
            FOREIGN KEY (cartao_id) REFERENCES cartao(id) ON DELETE CASCADE
        );
    ");


    // Lê os cartões antigos do PostgreSQL
    //$cartoes_antigos = $pdoLegado->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);
    $cartoes_antigos = $this->pdoLegado->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cartoes_antigos as $registro) {
        $id = $registro['id'];

        try {
            $this->pdo->beginTransaction();

            // Inserir no novo 'cartao' com mesmo ID
            $stmt = $this->pdo->prepare("INSERT INTO cartao (id, descricao, bandeira, dia_vencimento, dia_fechamento, linha_credito, banco_id, ativo)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id,
                $registro['descricao'],
                $this->nullIfEmpty($registro['bandeira']),
                $this->nullIfEmpty($registro['dia_vencimento']),
                $this->nullIfEmpty($registro['dia_fechamento']),
                $this->nullIfEmpty($registro['linha_credito']),
                null, // banco_id será associado manualmente depois
                $this->normalizaAtivo($registro['ativo'] ?? 1)
            ]);

            // Processar FINAIS como físicos (is_virtual = 0)
            $finais_array = $this->finaisParaArray($registro['finais'] ?? '');
            foreach ($finais_array as $item) {
                list($final, $titular) = $this->extrairFinalETitular($item);
                if (!is_numeric($final)) continue;

                $stmtFinal = $this->pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                            VALUES (?, ?, 0, ?, 1)");
                $stmtFinal->execute([$final, $id, $titular]);
            }

            // Processar VIRTUAIS separadamente (is_virtual = 1)
            $virtuais_array = $this->finaisParaArray($registro['virtuais'] ?? '');
            foreach ($virtuais_array as $item) {
                $final = substr($item, 0, 4);
                if (!is_numeric($final)) continue;

                $stmtFinal = $this->pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                            VALUES (?, ?, 1, ?, 1)");
                $stmtFinal->execute([$final, $id, null]); // virtuais não têm titular
            }

            $this->pdo->commit();
            echo "<p>Cartão ID <strong>$id</strong> migrado com sucesso.</p>";

        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "<p style='color:red'>Erro ao migrar cartão ID $id: {$e->getMessage()}</p>";
        }
    }

    echo "<p><strong>Migração finalizada com sucesso.</strong></p>";

  }

  public function migrateCategoria()
  {
    // Drop se desejar recomeçar do zero
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $this->pdo->exec("DROP TABLE IF EXISTS categoria");

    $this->pdo->exec("CREATE TABLE IF NOT EXISTS categoria (
      id INT AUTO_INCREMENT PRIMARY KEY,
      conta VARCHAR(20) NOT NULL,
      descricao VARCHAR(100) NOT NULL,
      tipo VARCHAR(20) NOT NULL,
      ativo TINYINT DEFAULT 1
      )");

    $rows = $this->pdoLegado->query("SELECT * FROM contas_contabeis")->fetchAll(PDO::FETCH_ASSOC);
    
    $this->pdo->beginTransaction();
    try {
        foreach ($rows as $linha) {
            $stmt = $this->pdo->prepare("INSERT INTO categoria (id, conta, descricao, tipo, ativo) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $linha['id'],
                $linha['conta'],
                $linha['descricao'],
                $linha['tipo'],
                $this->normalizaAtivo($linha['ativo']) ?? 1
            ]);
        }
        $this->pdo->commit();
        echo "<p><strong>Dados migrados de contas_contabeis para categoria com sucesso.</strong></p>";
    } catch (Exception $e) {
        $this->pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar dados: {$e->getMessage()}</p>";
    }
  }

  public function migrateControle()
  {
    // Apaga as tabelas (ordem correta: filho → pai)
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $this->pdo->exec("DROP TABLE IF EXISTS lancamentos");
    $this->pdo->exec("DROP TABLE IF EXISTS controle");
    $this->pdo->exec("DROP TABLE IF EXISTS grupo_controle");
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");

$this->pdo->exec("CREATE TABLE IF NOT EXISTS grupo_controle (
        id INT NOT NULL,
        descricao VARCHAR(255) NOT NULL,
        ativo TINYINT(1) DEFAULT 1,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
    
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS controle (
        id INT NOT NULL,
        descricao VARCHAR(100) NOT NULL,
        ativo TINYINT(1) DEFAULT 1,
        grupo_id INT DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (grupo_id) REFERENCES grupo_controle(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
    
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS lancamentos (
        id INT NOT NULL,
        controle_id INT DEFAULT NULL,
        data DATE NOT NULL,
        descricao VARCHAR(100) NOT NULL,
        valor DECIMAL(12,2) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (controle_id) REFERENCES controle(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");


    // Migra GRUPOS
    $grupos = $this->pdoLegado->query("SELECT * FROM grupo_controle")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($grupos as $grupo) {
        $stmt = $this->pdo->prepare("INSERT INTO grupo_controle (id, descricao, ativo) VALUES (?, ?, ?)");
        $stmt->execute([
            $grupo['id'],
            $grupo['descricao'],
            $this->normalizaAtivo($grupo['ativo']) ?? 1
        ]);
    }

    // Migra CONTROLES
    $controles = $this->pdoLegado->query("SELECT * FROM controle")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($controles as $ctrl) {
        $stmt = $this->pdo->prepare("INSERT INTO controle (id, descricao, ativo, grupo_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $ctrl['id'],
            $ctrl['descricao'],
            $this->normalizaAtivo($ctrl['ativo']) ?? 1,
            $ctrl['grupo_id'] ?? null
        ]);
    }

    // Migra LANCAMENTOS
    $lancamentos = $this->pdoLegado->query("SELECT * FROM lancamentos")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($lancamentos as $lanc) {
        $stmt = $this->pdo->prepare("INSERT INTO lancamentos (id, controle_id, data, descricao, valor) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $lanc['id'],
            $lanc['controle_id'],
            $lanc['data'],
            $lanc['descricao'],
            $lanc['valor']
        ]);
    }
  }

  public function migrateMovimentacao()
  {
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $this->pdo->exec("DROP TABLE IF EXISTS compras");
    $this->pdo->exec("DROP TABLE IF EXISTS fatura");
    $this->pdo->exec("DROP TABLE IF EXISTS movimentacao");
    
    $this->pdo->exec("CREATE TABLE movimentacao (
        id INT PRIMARY KEY,
        data DATE NOT NULL,
        descricao VARCHAR(255) not null,
        valor DECIMAL(12,2) not null,
        categoria_id INT not null,
        banco_id INT not null,
        codigo_pagamento INT,
        fatura_id INT,
        FOREIGN KEY (categoria_id) REFERENCES categoria(id),
        FOREIGN KEY (banco_id) REFERENCES banco(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
    //     FOREIGN KEY (fatura_id) REFERENCES fatura(id)
    
    $this->pdo->exec("CREATE TABLE fatura (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cartao_id INT NOT NULL,
        data_fechamento DATE NOT NULL,
        data_vencimento DATE NOT NULL,
        valor_total DECIMAL(12,2),
        valor_pago DECIMAL(12,2),
        status VARCHAR(10) DEFAULT 'aberta',
        movimentacao_id INT,
        FOREIGN KEY (cartao_id) REFERENCES cartao(id),
        FOREIGN KEY (movimentacao_id) REFERENCES movimentacao(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
    
    $this->pdo->exec("CREATE TABLE compras (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cartao_id INT NOT NULL,
        final_cartao_id INT,
        data DATE NOT NULL,
        descricao VARCHAR(255),
        valor DECIMAL(12,2) NOT NULL,
        parcelas INT DEFAULT 1,
        parcela_atual INT DEFAULT 1,
        categoria_id INT,
        fatura_id INT,
        FOREIGN KEY (cartao_id) REFERENCES cartao(id),
        FOREIGN KEY (final_cartao_id) REFERENCES final_cartao(id),
        FOREIGN KEY (fatura_id) REFERENCES fatura(id),
        FOREIGN KEY (categoria_id) REFERENCES categoria(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
    
    $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    echo "<h3>Iniciando migração de movimentações e faturas...</h3>";

    /*  Carrega inicialmente todas as movimentações que não pertencem a cartão
    //  ou seja, cujo cartao_id = 0 ou NULL */
    // Lê dados do PostgreSQL
    $mov1 = $this->pdo->query("SELECT * FROM movimentacao_financeira WHERE ((cartao_id = 0) OR (cartao_id is null))")->fetchAll(PDO::FETCH_ASSOC);

    $this->pdo->beginTransaction();
    try {
        foreach ($mov1 as $linha) {
            $stmt = $this->pdo->prepare("INSERT INTO movimentacao 
            (id, `data`, descricao, valor, categoria_id, banco_id, codigo_pagamento)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $linha['id'],
                $linha['data'],
                $linha['descricao'],
                $linha['valor'],
                $linha['conta_id'],
                $linha['banco_id'],
                $linha['codigo_pagamento']
            ]);
        }
        $this->pdo->commit();
        echo "<p><strong>Movimentação 1 migrada com sucesso!</strong></p>";
    } catch (Exception $e) {
        $this->pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar movimentação 1: {$e->getMessage()}<br />CodigoPagamento=[{$linha['codigo_pagamento']}]</p>";
    }

    // Consulta todos os registros do legado
    // $stmt = $pdoLegado->query("
    $stmt = $this->pdo->query("
        SELECT id, data AS data_pagamento, data_compra, valor, descricao, cartao_id, codigo_pagamento, 
               conta_id AS categoria_id, banco_id 
        FROM movimentacao_financeira
        WHERE  ((cartao_id > 0) and (cartao_id is not null))
        ORDER BY codigo_pagamento, cartao_id, data_compra
    ");
    
    $buffer_fatura = [];
    $cartao_atual = null;
    $codigo_atual = null;
    
    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($linha['cartao_id'] === null) {
            // Registro sem cartão → movimentação direta
            $this->inserirMovimentacaoDireta($linha);
        } else {
            $grupo = $linha['cartao_id'] . '-' . $linha['codigo_pagamento'];
    
            if (!empty($buffer_fatura) && (
                $linha['cartao_id'] != $cartao_atual ||
                $linha['codigo_pagamento'] != $codigo_atual
            )) {
                $this->processarGrupoFatura($buffer_fatura);
                $buffer_fatura = [];
            }
    
            $buffer_fatura[] = $linha;
            $cartao_atual = $linha['cartao_id'];
            $codigo_atual = $linha['codigo_pagamento'];
        }
    }
    
    // Finaliza o último grupo (caso exista)
    if (!empty($buffer_fatura)) {
        $this->processarGrupoFatura($buffer_fatura);
    }
    
    /*  Carrega agora, para a tabela compras os registros de despesas_cartao (compras não faturadas)
    //  sendo que, obrigatoriamente o campo fatura_id será NULL. */
    // Lê dados do PostgreSQL
    $dados = $this->pdoLegado->query("SELECT * FROM despesas_cartoes WHERE cartao_id is not NULL and cartao_id > 0")->fetchAll(PDO::FETCH_ASSOC);

    $this->pdo->beginTransaction();
    try {
        foreach ($dados as $linha) {
            $stmt = $this->pdo->prepare("
                INSERT INTO compras (cartao_id, data, valor, descricao, parcelas, parcela_atual, categoria_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $linha['cartao_id'],
                $linha['data_compra'],
                $linha['valor'],
                $linha['descricao'],
                $linha['parcelas'],
                $linha['parcela_atual'], 
                $linha['conta_id']
            ]);
        }
        $this->pdo->commit();
        echo "<p><strong>Compras não faturadas migradas com sucesso!</strong></p>";
    } catch (Exception $e) {
        $this->pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar compras não faturadas: {$e->getMessage()}<br />Cartao=[{$linha['cartao_id']}]</p>";
    }
      
    echo "<p><strong>Reconstrução concluída com sucesso!</strong></p>";
  }

/*  Funções auxiliares
//  inserirMovimentacaoDireta($linha)
//  processarGrupoFatura($compras)
//  nullIfEmpty($value)
//  normalizaAtivo($valor)
//  limparFinal($item)
//  extrairFinalETitular($item)
//  finaisParaArray($campo)
//  tabelaExiste($nome)
*/
protected function inserirMovimentacaoDireta($linha) {
    echo "<p>➡️ Entrou aqui mas não devia ID=[" . $linha['id'] . "]</p>";
}

  protected function processarGrupoFatura($compras) {
    $cartao_id = $compras[0]['cartao_id'];
    $codigo_pagamento = $compras[0]['codigo_pagamento'];
    $data_pagamento = $compras[0]['data_pagamento'];
    $id_movimentacao = $compras[0]['id'];
    $categoria_id = $compras[0]['categoria_id'];
    $banco_id = $compras[0]['banco_id'];

    $valor_total = 0;
    foreach ($compras as &$c) {
      $descricao = $c['descricao'];
      $c['parcela_atual'] = 1;
      $c['parcelas'] = 1;

      if (preg_match('/(\d{1,2})\/(\d{1,2})/', $descricao, $match)) {
        $c['parcela_atual'] = intval($match[1]);
        $c['parcelas'] = intval($match[2]);
        $descricao = preg_replace('/\(?\b\d{1,2}\/\d{1,2}\)?/', '', $descricao, 1);
        $descricao = trim($descricao);
      }
      
      $c['descricao_limpa'] = $descricao;
      $valor_total += floatval($c['valor']);
    }

    // 1. Cria fatura
    $stmt = $this->pdo->prepare("INSERT INTO fatura 
      (cartao_id, data_fechamento, data_vencimento, valor_total, valor_pago, status)
      VALUES (?, ?, ?, ?, ?, 'paga')");
    $data_fechamento = date('Y-m-d', strtotime($data_pagamento . ' +30 days'));
    $stmt->execute([$cartao_id, $data_fechamento, $data_pagamento, $valor_total, $valor_total]);
    $fatura_id = $this->pdo->lastInsertId();

    // 2. Cria compras
    $stmt = $this->pdo->prepare("INSERT INTO compras 
        (cartao_id, data, valor, descricao, parcelas, parcela_atual, categoria_id, fatura_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($compras as $c) {
        $stmt->execute([
            $cartao_id,
            $c['data_compra'],
            $c['valor'],
            $c['descricao_limpa'],
            $c['parcelas'],
            $c['parcela_atual'],
            $c['categoria_id'],
            $fatura_id
        ]);
    }

    // 3. Cria movimentação "FATURA CARTÃO" com ID original
    $stmt = $this->pdo->prepare("INSERT INTO movimentacao 
        (id, data, descricao, valor, categoria_id, banco_id, fatura_id, codigo_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $id_movimentacao,
        $data_pagamento,
        'FATURA CARTÃO',
        $valor_total,
        94,
        $banco_id,
        $fatura_id,
        $codigo_pagamento
    ]);

    // 4. Atualiza fatura com o ID da movimentação
    $this->pdo->prepare("UPDATE fatura SET movimentacao_id = ? WHERE id = ?")
        ->execute([$id_movimentacao, $fatura_id]);

    echo "<p>📦 Fatura {$fatura_id} (Cartão {$cartao_id} / Código {$codigo_pagamento}): R$ " . number_format($valor_total, 2, ',', '.') . "</p>";
  }

  protected function nullIfEmpty($value) {
    return trim($value) === '' ? null : $value;
  }

  protected function normalizaAtivo($valor) {
    // Lida com diferentes formatos de booleano (postgres, php, etc)
    if (is_null($valor)) return null;
    if ($valor === '' || strtolower($valor) === 'f' || $valor === false || $valor === 0 || $valor === '0') return 0;
    return 1; // qualquer outra coisa é considerado ativo
  }

  protected function limparFinal($item) {
    $item = strtoupper(trim($item));
    if ($item === '' || $item === '0' || $item === '---') return null;
    return $item;
  }

  protected function extrairFinalETitular($item) {
    $item = strtoupper(trim($item));
    $final = substr($item, 0, 4);
    $titular = strlen($item) > 4 ? substr($item, 4, 1) : null;
    return [$final, $titular];
  }

  protected function finaisParaArray($campo) {
    if (!$campo || in_array(trim($campo), ['0', '---'])) return [];
    return array_filter(array_map([$this,'limparFinal'], explode(';', $campo)));
  }

}