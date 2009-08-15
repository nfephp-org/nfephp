<?php
/* 
 * Sistema de teste para validaçao da NFe
 * 
 */

require_once('./libs/classNFEtools.php');
require_once('./config_inc.php');
require_once('./libs/basicFunctions.php');


$nfe = new NFEtools;
$nfe->ambiente      =   $ambiente;
$nfe->pathCerts     =   $certDir;
$nfe->nameCert      =   $certName;
$nfe->passKey       =   $keyPass;
$nfe->passPhrase    =   $passPhrase;

$nfe->entradasNF    =   $entradasDir;
$nfe->assinadasNF   =   $assinadasDir;
$nfe->validadasNF   =   $validadasDir;
$nfe->aprovadasNF   =   $aprovadasDir;
$nfe->enviadasNF    =   $enviadasDir;
$nfe->canceladasNF  =   $canceladasDir;
$nfe->inutilizadasNF=   $inutilizadasDir;
$nfe->temporarioNF  =   $temporarioDir;
$nfe->recebidasNF   =   $recebidasDir;
$nfe->consultadasNF =   $consultadas;

$nfe->pathXSD       =   $xsdDir;


$filename = $assinadasDir.'35080599999090910270550010000000015180051273-NFe.xml';
$filename = $assinadasDir.'35090671780456000160550010000000010000000017-NFe.xml';
$xsdfile = $nfe->pathXSD.'nfe_v1.10.xsd';

//$nfefile = file_get_contents($filename);

$bResp = $nfe->validaXML($filename, $xsdfile);

if (!$bResp){
    echo $nfe->errorMsg;
}

?>
