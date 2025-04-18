<?php
<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/legado.php';
require_once __DIR__ . '/../schema_categoria.php';

function tabelaExiste($pdo, $nome) {
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$nome]);
    return $stmt->fetch() !== false;
}

// Drop se desejar recomeçar do zero
$pdo->exec("DROP TABLE IF EXISTS categoria");
require_once 'schema_categoria.php';

if (tabelaExiste($pdoLegado, 'contas_contabeis')) {
    $rows = $pdoLegado->query("SELECT * FROM contas_contabeis")->fetchAll(PDO::FETCH_ASSOC);
    
    $pdo->beginTransaction();
    try {
        foreach ($rows as $linha) {
            $stmt = $pdo->prepare("INSERT INTO categoria (id, conta, descricao, tipo, ativo) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $linha['id'],
                $linha['conta'],
                $linha['descricao'],
                $linha['tipo'],
                $linha['ativo'] ?? 1
            ]);
        }
        $pdo->commit();
        echo "<p><strong>Dados migrados de contas_contabeis para categoria com sucesso.</strong></p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar dados: {$e->getMessage()}</p>";
    }
} else {
    echo "<p style='color:orange'>Tabela contas_contabeis não encontrada no banco legado. Nenhum dado migrado.</p>";
}
