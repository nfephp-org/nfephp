<?php
require_once('../libs/CCeNFePHP.class.php');

$arq = './cce.xml';
$aEnd = array('logradouro' => 'AV. ATLANTICA','numero' => '1702','complemento' => '','bairro' => 'COPACABANA','CEP' => '22021001','municipio' => 'RIO DE JANEIRO','UF'=>'RJ','telefone'=>'2100000000','email'=>'copa@copapalace.com.br');
if ( is_file($arq) ){
    $cce = new CCeNFePHP($arq, 'P', 'A4','../images/logo.jpg','I',$aEnd,'','Times',1);
    $teste = $cce->printCCe('teste.pdf','I');
}
?>
