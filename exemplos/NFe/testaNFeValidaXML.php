<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');

$arq = '../xml/11101284613439000180550010000004881093997017-nfe.xml';

$nfe = new ToolsNFePHP;

$docxml = file_get_contents($arq);

//para NFe 3.10
//$xsdFile = '../../schemes/PL_008e/nfe_v3.10.xsd';
//para NFe 2.00 com protocolo
$xsdFile = '../../schemes/PL_006u/procNFe_v2.00.xsd';

$aErro = array();

if (! $nfe->validXML($docxml, $xsdFile, $aErro)) {
    echo 'Estrutura do XML da NFe contêm erros --- <br>';
    foreach ($aErro as $er) {
        echo $er .'<br>';
    }
} else {
    echo 'Estrutura do XML da NFe foi VALIDADO!';
}
