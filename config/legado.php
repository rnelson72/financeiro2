<?php
require_once 'env.php';
carregarEnv(__DIR__ . '/../.env');

$uri = getenv('LEGADO_STRING');
$parts = parse_url($uri);

// Confere se tudo foi extraído corretamente
$host = $parts['host'] ?? '';
$port = $parts['port'] ?? '5432';
$user = $parts['user'] ?? '';
$pass = $parts['pass'] ?? '';
$dbname = ltrim($parts['path'], '/');

// Monta DSN compatível com PDO
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=verify-full";

try {
    $pdoLegado = new PDO($dsn, $user, $pass);
    $pdoLegado->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Erro ao conectar ao PostgreSQL via string do Render: ' . $e->getMessage());
}
