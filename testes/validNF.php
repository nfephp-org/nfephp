<?php
require_once('config_inc.php');

//$nfeFile = $outputDir."NFe35080599999090910270550010000000015180051273.xml";
$nfeFile = $outputDir."NFe35090509060442000106550010000000392020202020.xml";

// Habilita a manipulaçao de erros da libxml
libxml_use_internal_errors(true);

// instancia novo objeto DOM
$xml = new DOMDocument();
// carrega arquivo xml
$xml->load($nfeFile);

// valida o xml com o xsd
if (!$xml->schemaValidate('./xsd/nfe_v1.10.xsd')) {
    // carrega os erros em um array
    $aErrors = libxml_get_errors();
    libxml_clear_errors();
    $flagOK = false;
    foreach ($aErrors as $error){
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Atençao $error->code: ";
                break;
             case LIBXML_ERR_ERROR:
                $return .= "Erro $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Erro Fatal $error->code: ";
                break;
        }
        $return .= $error->message;
        $aErros[] = $return;
    }

} else {
    $flagOK = true;
}

// extrair o certificado e valida-lo
$beginpem = "-----BEGIN CERTIFICATE-----\n";
$endpem = "-----END CERTIFICATE-----\n";

// extrai o certificado publico anexado ao xml
$cert2 = $xml->getElementsByTagName('X509Data')->item(0)->nodeValue;
// retorna os marcadores de inicio e fim do certificado
$cert2 = $beginpem.$cert2.$endpem;


// extrai a data de validade do
$data = openssl_x509_read($cert2);
$cert_data = openssl_x509_parse($cert2);

// reformata a data de validade;
$ano = substr($cert_data['validTo'],0,2);
$mes = substr($cert_data['validTo'],2,2);
$dia = substr($cert_data['validTo'],4,2);
$dValid = gmmktime(0,0,0,$mes,$dia,$ano);

// obtem a data de hoje
$dHoje = gmmktime(0,0,0,date("m"),date("d"),date("Y"));

// compara a data de validade com a data atual
if ($dValid < $dHoje ){
    $flagOK = false;
    $aErros[]= "Erro Certificado:  A Validade do certificado expirou INVALIDO ["  . $dia.'/'.$mes.'/'.$ano . "] !!";
} else {
    $flagOK = $flagOK && true;
}

if ($cert_data['issuer']['O'] != 'ICP-Brasil' ){
    $flagOK = false;
    $aErros[] = "Erro Certificado: O Certificado nao pertence a cadeia reconhecida ICP-Brasil INVALIDO [ ". $cert_data['issuer']['O'] ." ] !!";
} else {
    $flagOK = $flagOK && true;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<?php
echo '<pre>';
print_r($aErros);
echo '</pre>';
?>