<?php
/**
 * chkStatus.php
 *
 * Verifica o status do serviço.
 * O retorno desta chamada sera utilizada para estabelercer se devemos 
 * entrar em contingencia.
 *
 * Este processo deve ser invocado periodicamente (ex. atraves do cron)
 * Ou antes do envio dos arquivos em lote
 *
 * Caso a resposta nao seja "On Line" as outras operaçoes estao suspensas
 * cancelamento, inutulizaçao, etc..
 *
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  1.0
 * @access   public
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

if ( $nfe->carregaCert() ){
    $retorno = $nfe->statusServico();
    if ($retorno) {
        echo "Serviço ON line <BR>";
        echo "Tempo Medio de Resposta : ".$nfe->tMed.' s<BR>';
        echo "Verificado em : ".date('d/m/Y H:i:s',$nfe->dhRecbto).'<BR>';
    } else {
        echo "OFF LINE .... Serviço PARADO!!!<BR>";
        echo "Verificado em : ".date('d/m/Y H:i:s',$nfe->dhRecbto).'<BR>';
        echo "Codigo  : ".$nfe->cStat.'<BR>';
        echo "Motivo : ".$nfe->xMotivo.'<BR>';
        echo "Obs : ".$nfe->xObs.'<BR>';
        echo '<pre>' . htmlspecialchars( $nfe->debug_str ) . '</pre>';
    }
} else {
    //houve erro com o certificado
    echo $nfe->errorCod.' :  '.$nfe->errorMsg.'<BR>';
}

?>
