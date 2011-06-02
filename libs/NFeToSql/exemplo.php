<?php

/******* Exemplos de Uso *********/
require_once('/var/www/nfephp/branches/2.0/libs/ToolsNFePHP.class.php');
require_once('./NFeToSql.class.php');

$nfe = new ToolsNFePHP;

$aNames = $nfe->listDir($nfe->aprDir.date("Ym").DIRECTORY_SEPARATOR,'*.xml',true);
if ( count($aNames ) > 0){
      for ( $x=0; $x < count($aNames); $x++ ) {
         $xml = $aNames[$x];
         $teste1 = new NFeToSql;

         if(!$teste1->insereBD($xml)){
            echo $teste1->erro;
         }else {
            //unlink($xml);
         }

      }
}




//$teste1 = new XmlToSql;
//  $teste1->insereBD('/var/www/nfephp/nfe/homologacao/enviadas/aprovadas/201105/43110389673164000193550000005646940005646945-procNFe.xml');

//$teste2 = new XmlToSql;
//$teste2->insereBD('/var/www/nfephp/nfe/homologacao/enviadas/aprovadas/201105/43110589673164000193550000006684761006684767-nfe.xml');
?>