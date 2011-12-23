<?php

/**
 * @author  Leandro G. Santana <leandrosantana1 at gmail dot com>
 * 
 */


error_reporting(E_ALL);
require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;

$dir=''; //diretorio onde estão as NFe aprovadas que devem ser organizadas

function arruma_arquivos_xml_no_diretorio($dir=''){
    if ($dir == ''){
        return false;
    } 
    $files=scandir($dir);
    $cont=count($files);
    $canc = false;
      
    for($i=0;$i<$cont;$i++){
        if((substr($files[$i],-3,3)=="xml")) {
            $arquivo = $dir.$files[$i];
            $xml = simplexml_load_file($arquivo);
            if(array_key_exists("NFe",$xml)){
                foreach($xml->NFe->infNFe->attributes() as $id=> $idx){
                    if($id == 'Id'){
                        $ID = substr($idx,3,strlen($idx));
                    }
                 }
            }
                
            foreach($xml->NFe->infNFe->ide->children() as $elemento =>$valor){
                if($elemento == "dEmi"){
                    $datax = $valor;
                }
            }
                
            // pegando a data de emissão
            $dataxx = explode("-",$datax);
            $mes = $dataxx[1];
            $ano = $dataxx[0];
                
            if($mes==1){
                $mes = "jan";
            }
            if($mes==2){
                $mes = "fev";
            }
            if($mes==3){
                $mes = "mar";
            }
            if($mes==4){
                $mes = "abr";
            }
            if($mes==5){
                $mes = "mai";
            }
            if($mes==6){
                $mes = "jun";
            }
            if($mes==7){
                $mes = "jul";
            }
            if($mes==8){
                $mes = "ago";
            }
            if($mes==9){
                $mes = "set";
            }
            if($mes==10){
                $mes = "out";
            }
            if($mes==11){
                $mes = "nov";
            }
            if($mes==12){
                $mes = "dez";
            }
                
            if(is_dir($dir.$ano)){
                if(!is_dir($dir.$ano."/".$mes)){
                    mkdir($dir.$ano."/".$mes, 0777);
                    chmod($dir.$ano."/".$mes, 0777);
                }
            }else{
                mkdir($dir.$ano, 0777); 
                chmod($dir.$ano, 0777);
            }
                
            if(!is_dir($dir.$ano."/".$mes)){
                mkdir($dir.$ano."/".$mes, 0777);
                chmod($dir.$ano."/".$mes, 0777);
            }
                
            $local = $arquivo.$file[$i];
            $destino = $nfe->aprDir.$ano."/".$mes."/".$ID.".xml";
            if(file_exists($destino)){
                @unlink($local);
                @rename($local, $destino);
                chmod($destino, 0777);
            }else{
                @rename($local, $destino);
                chmod($destino, 0777);
            }
        } 
    }
}//fim da função

?>
