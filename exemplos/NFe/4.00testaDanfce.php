<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once '../../bootstrap.php';

use NFePHP\Extras\Danfce;

$saida = isset($_REQUEST['o']) ? $_REQUEST['o'] : 'pdf';

$arq = '../xml/exemploNFCe.xml';
if (is_file($arq)) {
    $docxml = file_get_contents($arq);
    $danfe = new Danfce($docxml, '../../images/logo.jpg', 2);
    $id = $danfe->montaDANFE(false);
    $teste = $danfe->printDANFE($saida, $id.'.pdf', 'I');
}
exit();
