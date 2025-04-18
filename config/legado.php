<?php
require_once 'env.php';
carregarEnv(__DIR__ . '/../.env');

$dsn = 'pgsql:host=' . getenv('LEGADO_HOST') . ';dbname=' . getenv('LEGADO_NAME') . ';charset=utf8';

try {
    $pdoLegado = new PDO($dsn, getenv('LEGADO_USER'), getenv('LEGADO_PASS'));
    $pdoLegado->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erro ao conectar ao PostgreSQL: ' . $e->getMessage());
}
