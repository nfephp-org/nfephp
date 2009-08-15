<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$xml =
'<?xml version="1.0" encoding="UTF-8"?>
<retConsStatServ xmlns="http://www.portalfiscal.inf.br/nfe" versao="1.07">
    <tpAmb>2</tpAmb>
    <verAplic>SP_NFE_PL_005c</verAplic>
    <cStat>107</cStat>
    <xMotivo>Servi�o em Opera��o</xMotivo>
    <cUF>35</cUF>
    <dhRecbto>2009-06-20T18:38:08</dhRecbto>
    <tMed>1</tMed>
</retConsStatServ>';


//$xmldoc = new SimpleXMLElement($xml);
//$servCod = $xmldoc->cStat;

$xml = utf8_encode($xml);


$doc = new DOMDocument(); //cria objeto DOM
$doc->formatOutput = false;
$doc->preserveWhiteSpace = false;
$doc->loadXML($xml);

 // status do serviço
 $servCod = $doc->getElementsByTagName('cStat')->item(0)->nodeValue;
 //tempo de resposta
 $servTM = $doc->getElementsByTagName('tMed')->item(0)->nodeValue;
 // data e hora da mensagem
 $servDH = $doc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
 // motivo da resposta (opcional)
 $servMotivo = $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
 // obervaçoes opcional
 $servObs = 'Obs ' . $doc->getElementsByTagName('xObs')->item(0)->nodeValue.'<BR>';

$aDH = split('T',$servDH);
$adDH = split('-',$aDH[0]);
$atDH = split(':',$aDH[1]);

$timestampDH = mktime($atDH[0],$atDH[1],$atDH[2],$adDH[1],$adDH[2],$adDH[0]);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Language" content="pt-br">
   <title>Resposta</title>
</head>
<body>
<?php
iconv_set_encoding("input_encoding", "UTF-8");
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
var_dump(iconv_get_encoding('all'));


 echo '<BR>'.$servCod.'<BR>';
 echo $servTM.'<BR>';
 echo date('d/m/Y H:i:s',$timestampDH).'<BR>';
 echo $servMotivo.'<BR>';
 echo $servObs.'<BR>';
?>
</body>
</html>
