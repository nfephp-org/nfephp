<?php
/**
 *
 * verificaRec
 *
 *
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  1.0
 * @access   public
**/

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

$recibo = '350000005794407';

if ( $nfe->carregaCert() ){
    $bRet = $nfe->retornoNF($recibo);


    echo '<BR>'.$nfe->cStat;
    echo '<BR>'.$nfe->xMotivo;
    echo '<BR>'.$nfe->tpAmb;
    echo '<BR>'.$nfe->verAplic;
    echo '<BR>'.$nfe->nRec;
    echo '<pre>' . $nfe->debug_str  . '<\/pre>';
}


?>
