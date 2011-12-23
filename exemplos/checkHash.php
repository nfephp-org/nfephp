<?php

$arq = './35110708754767000118550000000029161000029166-nfe.xml';

echo chkHASH($arq);

function chkHASH($file){
    if (!is_file($file)){
        return false;
    }
    $docxml = file_get_contents($file);
    // carrega o documento no DOM
    $xmldoc = new DOMDocument();
    $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
    $xmldoc->formatOutput = false;
    // muito importante deixar ativadas as opçoes para limpar os espacos em branco
    // e as tags vazias
    $xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
    //extrair a tag com os dados a serem assinados
    $node = $xmldoc->getElementsByTagName('infNFe')->item(0);
    $digInfo = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
    //extrai os dados da tag para uma string
    $dados = $node->C14N(false,false,NULL,NULL);
    //calcular o hash dos dados
    $hashValue = hash('sha1',$dados,true);
    //converte o valor para base64 para serem colocados no xml
    $digValue = base64_encode($hashValue);
    
    return 'Digest Calculado: '.$digValue .'<BR>Digest da NFe: '.$digInfo;
}

?>
