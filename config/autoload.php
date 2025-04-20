<?php
spl_autoload_register(function ($classe) {
    $pastas = ['../models/', '../controllers/'];
    foreach ($pastas as $pasta) {
        $arquivo = $pasta . $classe . '.php';
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});
