<?php
require_once('./libs/xmlseclibs.php');
require_once('config_inc.php');

$inputName = 'nfe.xml';

// carrega o arquivo em uma variavel para limpeza
$nfe = file_get_contents($inputDir.$inputName);
// limpa espaços, e outros indesejaveis
$nfe = preg_replace('/[\n\r\t]/', '', $nfe);

$doc = new DOMDocument(); //cria objeto DOM
$doc->formatOutput = false;
$doc->preserveWhiteSpace = false;

$doc->loadXML($nfe);
//$doc->load($inputDir.$inputName); //carrega o documento no objeto DOM

$infNFe = $doc->getElementsByTagName('infNFe')->item(0); //carrega o node que sera assinado
$id = trim($infNFe->getAttribute("Id")); //extrai o id da NF

$outputName = $id.'.xml'; //monta o nome da nf com base no id

// se a nf ja existir deletar
if (file_exists($outputDir.$outputName)) {
    unlink($outputDir.$outputName);
}

// cria objeto de assinatura digital da lib xmlseclibs.php
// esse objeto e que sera assinado
$objDSig = new XMLSecurityDSig();

// estabelece o metodo de canonizaçao
$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

//acrescenta a referencia, o node infNFe, os transforms e as opçoes
$objDSig->addReference($infNFe, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature','http://www.w3.org/TR/2001/REC-xml-c14n-20010315'),array('id_name'=>'Id','overwrite'=>FALSE));

// cria o objeto chave que ira conter as chaves e certificados
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));

// carrega a chave privada
$objKey->loadKey($certDir.$keyName, TRUE);

// carrega a senha para a chave privada, se houver
//$objKey->passphrase = $keyPass;

// metodo sign, processa a assinatura
$objDSig->sign($objKey);

//metodo add509Cert adiciona o certificado digital
//$objDSig->add509Cert(file_get_contents($certDir.$certName));
$objDSig->add509Cert($certDir.$certName,TRUE,TRUE);

//insere a assinatura no objeto DOM
$objDSig->appendSignature($doc->documentElement);

// salva o xml assinado
$doc->saveXML();
$doc->save($outputDir.$outputName);

// limpa a memoria
unset($doc);
unset($nfe);

// carrega o arquivo em uma variavel para limpeza
$nfe = file_get_contents($outputDir.$outputName);

// limpa espaços, e outros indesejaveis
//$nfe = preg_replace('/[\n\r\t]/', '', $nfe);
$nfe = str_replace('ds:','',$nfe);
$nfe = str_replace(':ds','',$nfe);

//carrega novamente o documento no objeto DOM
$doc = new DOMDocument();
//$doc->formatOutput = false;
//$doc->preserveWhiteSpace = false;
$doc->loadXML($nfe);

//**********************************
// Substitui os dados do certificado que foram alterados
// na inserçao no xml pelo conjunto correto de dados preservando
// os LF - chr(10)
/*
$beginpem = "-----BEGIN CERTIFICATE-----\n";
$endpem = "-----END CERTIFICATE-----\n";
$cert = file_get_contents($certDir.$certName);
$cert = str_replace($beginpem,'',$cert);
$cert = str_replace($endpem,'',$cert);
$X509cert = $doc->getElementsByTagName('X509Certificate')->item(0); //->nodeValue;
$X509cert->nodeValue=$cert;
//**********************************
*/

//salva as alteraçoes no objeto DOM
$doc->saveXML();
//salva o arquivo com a NFe assinada
$doc->save($outputDir.$outputName);

//mostra a NFe na tela
header('Content-type: text/xml');
echo $doc->saveXML();

?>