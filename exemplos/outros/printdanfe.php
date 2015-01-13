<?php
require_once('libs/DanfeNFePHP.class.php');
$docxml = file_get_contents('/var/www/xmlNFE_homologacao/enviadas/35091058716523000119550000000013746870020727-nfe.xml');
$danfe = new DanfeNFePHP($docxml, 'P', 'A4','/var/www/trunkNFe/images/logo.jpg','I','');
$id = $danfe->montaDANFE();
$teste = $danfe->printDANFE($id.'.pdf','I');
?>
