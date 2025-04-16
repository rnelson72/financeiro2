<?php

function migrar_cartoes($pdo) {
    echo "<h3>Iniciando migração de cartões...</h3>";

    require_once '../config/migrations/schema_cartao.php';
    require_once '../config/migrations/migrate_cartoes.php';

    echo "<p>Migração concluída.</p>";
}
