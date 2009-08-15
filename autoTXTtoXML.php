<?php
/**
 * autoTXTtoXML.php
 *
 * Funçao : Ler o diretorio das NFe em busca de NF em TXT
 *          ler e trasformar o arquivo TXT em XML
 *          Este script deve ser disparado periodicamente
 *          atraves do cron
 *
 * @author   Roberto L. Machado <roberto.machado@superig.com.br>
 * @version  1.0
 * @access   public
 *
 * TODO: tudo
**/

require_once('./config_inc.php');
require_once('./libs/basicFunctions.php');

// ler o diretorio entradasNF
// montar matriz com os arquivos encontrados na pasta
$inName = listDir($entradasDir,'txt');

// se foi retornado exite algum arquivo txt
if (count($inName) > 0){
    //para cada elemento da matriz converter e gravar dados na base
    for ($x=0; $x <= count($inName); $x++){

        
        $filename = $entradasDir.$inName[$x];
        if ($nfetxt = file_get_contents($filename)){
            
            //ler cada linha do txt e identificar a chave
            $nfetxt=explode("\n", $nfetxt);
            foreach ($lNFe as $line=>$data) {

                //$line contem a linha extraida do arquivo

                // passar a linha para o conversor

            }
        }
    }
}

?>