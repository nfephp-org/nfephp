<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
<?php
/**
 * chkCadastro.php
 *
 * Solicita os dados cadastrais das empresas
 * O retorno desta chamada sera utilizada para verificar a correçaodos
 * dados cadastrais dos destinatarios das NFe
 *
 *
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  1.0
 * @access   public
 * 
 * Nao funciona retorna FALSE ????
**/

require_once('./libs/classNFEtools.php');
require_once('./config_inc.php');
require_once('./libs/basicFunctions.php');

//instanciar a classe
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

$UF = 'SP';
$IE = '';
$CNPJ = '58716523000119';
$CPF = '';

if ( $nfe->carregaCert() ){
    $retorno = $nfe->consultaCadastro($UF, $IE, $CNPJ, $CPF);
    if ($retorno){
        echo '<pre>';
        print_r($retorno);
        echo '</pre>';
    }
    echo $nfe->debug_str;
    //htmlspecialchars( $nfe->debug_str )
} else {
    //houve erro com o certificado
    echo $nfe->errorCod.' :  '.$nfe->errorMsg.'<BR>';
}

?>
  
  </body>
</html>