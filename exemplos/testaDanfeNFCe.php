<?php
error_reporting(0);
ini_set('display_errors', 'Off');
require_once('../libs/DanfeNFCeNFePHP.class.php');

$saida = $_REQUEST['o'];

if (!isset($_REQUEST['o'])) {
    $saida = 'pdf';
}

$arq = 'xml/exemploNFCe.xml';
if (is_file($arq)) {
    $docxml = file_get_contents($arq);
    $danfe = new DanfeNFCeNFePHP($docxml, '', 0);
    $id = $danfe->montaDANFE(false);
    $teste = $danfe->printDANFE($saida, $id.'.pdf', 'I');
    exit();
}
