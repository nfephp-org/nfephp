<?php
/**
 *
 * autoSign
 * Automaticamente verifica o diretorio de entrada, assina e envia para
 * a pasta de NFe assinadas para posterior envio
 *
 * Este processo deve ser invocado periodicamente (ex. atraves do cron)
 * 
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  1.0
 * @access   public
**/

require_once('./libs/classNFEtools.php');
require_once('./config_inc.php');
require_once('./libs/basicFunctions.php');

// ler o diretorio entradasNF
// montar matriz com os arquivos encontrados na pasta
$inName = listDir($entradasDir,'xml');


//para cada elemento da matriz assinar e gravar dados na base
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

if( $nfe->carregaCert() ) {

}

    // antes de assinar verificar a validade do certificado
    //if (!$nfe->validCert($certDir.$certName)){
    //    break;
    //}

// se foi retornado algum arquivo
  if (count($inName) > 0){
      for ($x=0; $x <= count($inName); $x++){
             
        //carrega nfe para assinar em uma strig
        $filename = $entradasDir.$inName[$x];
        if ($nfefile = file_get_contents($filename)){

            //assinador usando somente o PHP da classe classNFe
            $signn = $nfe->assina($nfefile, 'infNFe', $assinadasDir);

            
        }
    }
}


?>
