<?php
require_once('../../libs/NFe/DacceNFePHP.class.php');

$arq = 'xml/10142785000190-cce.xml';
$aEnd = array('razao'=>'HOTEL COPACABANA','logradouro' => 'AV. ATLANTICA','numero' => '1702','complemento' => '','bairro' => 'COPACABANA','CEP' => '22021001','municipio' => 'RIO DE JANEIRO','UF'=>'RJ','telefone'=>'2100000000','email'=>'copa@copapalace.com.br');
if ( is_file($arq) ){
    $cce = new DacceNFePHP($arq, 'P', 'A4','../images/logo.jpg','I',$aEnd,'','Times',1);
    $teste = $cce->printDACCE('teste.pdf','I');
}
?>
