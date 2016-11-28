<?php

$depth = 2;
$path = dirname(__FILE__);
for( $d=1 ; $d <= $depth ; $d++ ) {
    $path = dirname($path);
}
$vendorDir = $path;

$autoload_path = $vendorDir . '/autoload.php';

$included = include $autoload_path;

if (!$included) {
    echo 'Falha no carregamento do autoload';
    exit(1);
}
