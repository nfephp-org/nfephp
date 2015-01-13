<?php
require("txt2xml.class.php");



/*
Este código pega conteúdo de qualquer arquivo xml e transforma em um array multidimensional.
Para utilizar, passe a URL ou caminho para o arquivo XML. Exemplo:
$array = xml2array('http://seusite.com.br/arquivo.xml',array());
Fonte:  http://codigofonte.uol.com.br/codigo/php/xml/transformar-xml-em-array-com-php
* Precisa do PHP5 ou mais recente para funcionar
*/

function xml2array($source,$arr){
    $xml = simplexml_load_string(file_get_contents($source));
    $iter = 0;
        foreach($xml->children() as $b){
                $a = $b->getName();
                if(!$b->children()){
                        $arr[$a] = trim($b[0]);
                }
                else{
                        $arr[$a][$iter] = array();
                        $arr[$a][$iter] = xml2phpArray($b,$arr[$a][$iter]);
                }
        $iter++;
        }
        return $arr;
}






// Converte um Texto em XML e retorna o resultado em um array que pode ser utilizado em outras rotinas
// Útil para tratamento avançado de arquivos XML gerados a partir de um TXT
// A variável  $orig_txt é um path para o arquivo TXT
function txt2xml2array($orig_txt)
{
	$teste = new NFeTxt2Xml($orig_txt);
	return xml2array($teste);
}




// Imprime na tela um XML gerado a partir de um TXT
function imprime_txt2xml($texto)
{
   $teste = new NFeTxt2Xml($texto);
   print $teste->getXML();
}



?>



<?php

// Código original em que as idéias acima se inspiraram

// require("txt2xml.class.php");
// $teste = new NFeTxt2Xml("nfe2.txt");
// print $teste->getXML();
?>
