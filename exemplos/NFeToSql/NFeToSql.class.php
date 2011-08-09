<?php
/**
 *
 * Esta classe permite transformar os dados de um XML de uma NFe em SQLs de inserзгo em um BD
 *
 * @name        NFeToSql
 * @version     1.0c
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2011
 * @author      Leandro C. Lopez <leandro.castoldi at gmail dot com>
 *
 * Requisitos   PEAR MDB2
 *
 * Obs:         Favor nгo rir do codigo, como todos nуs, jб foi bonito quando crianзa. :op
 *              Os cуdigos comentados foram deixados de propуsito para eventual debug e/ou ver como funciona a classe (se й que funciona)
 *              Ver na linha 187 a conexao com o Banco de Dados
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);

class NFeToSql {
    // Nгo pergunte, apenas use estas variбveis...
    private $infNFe, $protNFe, $atrib, $conta, $nfR, $id_p, $id_pv, $nobs;
    private $sub1 = array('NFref', 'IPITrib', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Outr', 'NT', 'Part', 'SN', 'Aliq', 'Qtde');
    private $sub2 = array('refNFe', 'IPI', '');
    private $q = array();
    private $qv = array();
    public  $erro;

    private $pai = array('refNF' => 'ide',
                         'refNFe' => 'ide',
                         'enderEmit' => 'emit',
                         'enderDest' => 'dest',
                         'ICMS' => 'prod',
                         'IPI' => 'prod',
                         'II' => 'prod',
                         'PIS' => 'prod',
                         'COFINS' => 'prod',
                         'COFINSST' => 'prod',
                         'ISSQN' => 'prod',
                         'PISST' => 'prod',
                         'med' => 'prod',
                         'veicProd' => 'prod',
                         'arma' => 'prod',
                         'comb' => 'prod',
                         'DI' => 'prod',
                         'adi' => 'DI',
                         'prod' => 'det',
                         'infAdProd' => 'prod',
                         'transporta' => 'transp',
                         'veicTransp' => 'transp',
                         'vol' => 'transp',
                         'retTransp' => 'transp',
                         'reboque' => 'transp',
                         'lacres' => 'vol',
                         'obsFisco' => 'infAdic',
                         'procRef' => 'infAdic',
                         'obsCont' => 'infAdic'
                         );


    private function geraSQL($nos){ // Codigo feito apos o 10o cafe, soh podia dar nisso...
       foreach($nos->childNodes as $node){
         if($node->childNodes->length == 1){
            foreach($node->childNodes as $node2){
              if($node2->childNodes->length >= 1){
                  if($this->atrib == ''){
                     $this->conta++;
                  }
                  if(($atr = $node->attributes) !== NULL){
                    foreach($atr as $key){
                      $atri[0] = $key->value;
                      $atri[1] = '`'.$key->name.'`, ';
                    }
                  }else {
                    $atri[0] = '';
                    $atri[1] = '';
                  }
                  $campo = str_replace($this->sub1, $this->sub2, $node->nodeName);

                  if(!array_key_exists($campo.$this->atrib.$this->conta, $this->q)){
                     if($campo == 'obsCont'){
                       $this->nobs++;
                     }
                     $this->q[$campo.$this->atrib.$this->conta.$this->nobs] = "insert into ".$campo.' (`'.$campo.'_id`, '.$atri[1];
                  }
                  if($atri[0] != ''){
                     $this->qv[$campo.$this->atrib.$this->conta.$this->nobs] .= "'".addslashes($atri[0])."', ";
                  }
                  $this->geraSQL($node);
              }else {
                 if($this->atrib != '' or array_key_exists($node->parentNode->nodeName.$this->atrib, $this->q)){
                   $this->conta = '';
                 }
                 $campo = str_replace($this->sub1, $this->sub2, $node->parentNode->nodeName);
                 if(substr($campo, -2) == 'ST'){
                   $campo = substr($campo, 0, -2);
                 }else if($campo == 'refNF' or $campo == 'refNFP' or $campo == 'refECF'){
                   $campo .= $this->nfR;
                   $this->conta = '';
                 }
                 if(!array_key_exists($campo.$this->atrib.$this->conta.$this->nobs, $this->q)){
                    $this->q[$campo.$this->atrib.$this->conta.$this->nobs] = "insert into ".$campo.' (`'.$campo.'_id`, '.$atri[1];
                 }
                 if($node->nodeValue != ''){
                    $this->q[$campo.$this->atrib.$this->conta.$this->nobs] .= '`'.$node->nodeName.'`, ';
                    $this->qv[$campo.$this->atrib.$this->conta.$this->nobs] .= "'".addslashes($node->nodeValue)."', ";
                 }
              }
            }
         }else if($node->childNodes->length > 1){
             if(($atr = $node->attributes) !== NULL){
               foreach($atr as $key){
                 $this->atrib = '*'.$key->value;
               }
             }else {
               $this->atrib = '';
             }
             $campo = str_replace($this->sub1, $this->sub2, $node->nodeName);
             if(substr($campo, -2) == 'ST'){
                $campo = substr($campo, 0, -2);
             }else if($campo == 'refNF' or $campo == 'refNFP' or $campo == 'refECF'){
               $this->nfR++;
               $campo .= $this->nfR;
             }else if($campo == 'vol' or $campo == 'dup'){
               $this->nobs++;
             }

             if(($atr = $node->attributes) !== NULL){
                foreach($atr as $key){
                   $atri[0] = $key->value;
                   $atri[1] = '`'.$key->name.'`, ';
                }
             }else {
                $atri[0] = '';
                $atri[1] = '';
             }
             if(!array_key_exists($campo.$this->atrib.$this->nobs, $this->q)){
                $this->q[$campo.$this->atrib.$this->nobs] = "insert into ".$campo.' (`'.$campo.'_id`, '.$atri[1];
             }
             if($atri[0] != ''){
                $this->qv[$campo.$this->atrib.$this->nobs] .= "'".addslashes($atri[0])."', ";
                $atri[0] = '';
                $atri[1] = '';
             }
             $this->geraSQL($node);
          }
       }
    }

    private function geraProtSQL($nos){
      $this->q[0] = '';
      $this->qv[0] = '';
       foreach($nos->childNodes as $node){
          if($node->nodeName == 'dhRecbto'){
            $dhRecbto = explode('T', $node->nodeValue);
            $this->q[0] .= ', `dRecbto`';
            $this->qv[0] .= ", '".addslashes($dhRecbto[0])."'";

            $this->q[0] .= ', `hRecbto`';
            $this->qv[0] .= ", '".addslashes($dhRecbto[1])."'";
          }else {
            $this->q[0] .= ', `'.$node->nodeName.'`';
            $this->qv[0] .= ", '".addslashes($node->nodeValue)."'";
          }
       }
    }

    public function insereBD($xml, $tpAmb){
         // Obrigatуrio instalaзгo do PEAR MDB2
         require_once('MDB2.php');

              $this->dom = new DomDocument;
              $this->dom->load($xml);

              // Funciona somente com os dados da NFe contidos no infNFe
              $this->infNFe  = $this->dom->getElementsByTagName("infNFe")->item(0);

              if($tpAmb == 1){
                $bd = 'nfephp';
              }else {
                $bd = 'nfephp_homolog';
              }

              // Configure aqui a sua conexгo com o BD
              $con =& MDB2::factory('mysqli://userBD:senhaBD@meuenderecoservidor:porta/'.$bd);

              if(PEAR::isError($con)) { $this->erro = date('d-m-Y H:i:s').": $xml -> ".$con->getMessage()."\n"; return false; }


              if(($atr = $this->infNFe->attributes) !== NULL){
                foreach($atr as $key){
                  $atri[0] .= "`".$key->name.'`, ';
                  $atri[1] .= "'".$key->value."', ";
                }

                // Query para gerar ID da NFe no BD
                $query = "insert into NFe (`NFe_id`, `situacao_id`, ".substr($atri[0], 0, -2).") values('', '2', ".substr($atri[1], 0, -2).");";
                //echo $query."\n";
                $i_nfe =& $con->exec($query);

                if(PEAR::isError($i_nfe)) {
                   $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$i_nfe->getMessage()."\n";

                   if($i_nfe->getMessage() != "MDB2 Error: constraint violation"){
                      return false;
                   }else {
                      $attr_campo = explode(',', $atri[0]);
                      $attr_valor = explode(',', $atri[1]);

                      $query2 = "delete from NFe where ".$attr_campo[0]." = ".$attr_valor[0]." and ".$attr_campo[1]." = ".$attr_valor[1];

                      $del_nfe =& $con->exec($query2);

                      if(PEAR::isError($del_nfe)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query2.' -> '.$del_nfe->getMessage()."\n"; return false; }

                      $i_nfe =& $con->exec($query);

                      if(PEAR::isError($i_nfe)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$i_nfe->getMessage()."\n"; return false; }
                   }
                }

                $id_nfe = $con->lastInsertID('NFe', 'NFe_id');
              }

              $this->geraSQL($this->infNFe);

              foreach($this->q as $key => $value){
                //echo 'Key: '.$key.' - '.$value."\n";
                if(substr($value, -6) == '_id`, '){
                  $camp = substr($key, 0, strpos($key, '*'));
                  if(in_array($camp, $this->pai)){
                    if(!array_key_exists($camp, $this->pai)){
                      $id_p = 'NFe';
                      $id_pv = $id_nfe;
                    }else {
                      $id_p = $this->pai[$camp];
                      $id_pv = $this->paiv[$camp];
                    }

                    $query = $value.'`'.$id_p.'_id`) values("", "'.$id_pv.'");';
                    //echo $query."\n";

                    $ret =& $con->exec($query);

                    if(PEAR::isError($ret)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$ret->getMessage()."\n"; return false; }

                    $this->paiv[$camp] = $con->lastInsertID($camp, $camp.'_id');

                  }
                }else if(substr($key, 0, 5) == 'refNF' or substr($key, 0, 6) == 'refECF'){
                  $id_p = 'ide';
                  $id_pv = $this->paiv['ide'];
                  $query = str_replace($this->sub1, $this->sub2, $value).'`'.$id_p.'_id`) values("", '.$this->qv[$key]."'".$id_pv."');";
                  //echo $query."\n\n";

                  $ret =& $con->exec($query);

                  if(PEAR::isError($ret)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$ret->getMessage()."\n"; return false; }

                }else {
                  $_key = (strpos($key, '*') === false)? $key: substr($key, 0, strpos($key, '*'));
                  //echo $key.' - '.$_key."___\n";
                  if(!array_key_exists($_key, $this->pai)){
                    $id_p = 'NFe';
                    $id_pv = $id_nfe;
                  }else {
                    $id_p = $this->pai[$_key];
                    $id_pv = $this->paiv[$id_p];
                  }

                  $query = $value.'`'.$id_p.'_id`) values("", '.$this->qv[$key]."'".$id_pv."');";
                  //echo $query."\n\n";

                  $ret =& $con->exec($query);

                  if(PEAR::isError($ret)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$ret->getMessage()."\n"; return false; }
                  //echo $key.' --- '.$_key."...\n";
                  $this->paiv[$_key] = $con->lastInsertID($_key, $_key.'_id');
                }
              }

              $this->protNFe  = $this->dom->getElementsByTagName("infProt")->item(0);
              if($this->protNFe !== false) {
                 $this->geraProtSQL($this->protNFe);

                 $query = "insert into protNFe (`protNFe_id`, `NFe_id`".$this->q[0].") values('', '".$id_nfe."'".$this->qv[0].");";
                 //echo $query."\n";

                 $ret =& $con->exec($query);

                 if(PEAR::isError($ret)) { $this->erro = date('d-m-Y H:i:s').": $xml = ".$query.' -> '.$ret->getMessage()."\n"; return false; }
              }

              $con->disconnect();

              return true;
    }

}

?>