<?php
require_once '../database.php';
require_once '../legado.php';

// Limpa e recria a tabela banco
$pdo->exec("DROP TABLE IF EXISTS banco");
require_once 'schema_banco.php';

// Lê dados do PostgreSQL
$dados = $pdoLegado->query("SELECT * FROM bancos")->fetchAll(PDO::FETCH_ASSOC);

$pdo->beginTransaction();
try {
    foreach ($dados as $linha) {
        $stmt = $pdo->prepare("
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
    $pdo->commit();
    echo "<p><strong>Bancos migrados com sucesso!</strong></p>";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p style='color:red'>Erro ao migrar bancos: {$e->getMessage()}</p>";
}
