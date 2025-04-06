<?php
function carregarEnv($arquivo) {
if (!file_exists($arquivo)) return;
$linhas = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($linhas as $linha) {
if (str_starts_with(trim($linha), '#')) continue;
list($chave, $valor) = explode('=', $linha, 2);
putenv(trim($chave) . '=' . trim($valor));
}
}
?>