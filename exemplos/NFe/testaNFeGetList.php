<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP('',1,false);
$modSOAP = '2'; //usando cURL
$tpAmb = '1';//usando produção
$indNFe = '0';
$indEmi = '0';
$ultNSU = '';
$AN = true;
$retorno = array();
$indCont = 1;
$limite = 1;

while ($indCont != 0) { 
    header('Content-type: text/html; charset=UTF-8');
    if (!$xml = $nfe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP, $retorno)){
        echo "Houve erro !! $nfe->errMsg";
        echo '<br><br><PRE>';
        echo htmlspecialchars($nfe->soapDebug);
        echo '</PRE><BR>';
        exit;
    } else {
        //carrega o retorno 
        $indCont = $retorno['indCont'];
        $ultNSU = '';
        echo "$limite - Pesquisa   [$indCont]<BR>";
        if (!empty($retorno['NFe'])) {
            foreach($retorno['NFe'] as $nfe) {
                echo 'NFe : ' . $nfe['chNFe'];
                echo '<BR>';
                echo 'CNPJ: ' . $nfe['CNPJ'] . ' Emitente :' . $nfe['xNome'];
                echo '<BR>';
                echo 'Data da emissão : ' . $nfe['dEmi'] .' e Data Autorização : ' . $nfe['dhRecbto'];
                if ($nfe['tpNF'] == 0) {
                    $tptxt = 'NF de Entrada';
                } else {
                     $tptxt = 'NF de Saída';
                }
                echo '    ' . $tptxt;
                switch ($nfe['cSitNFe']) {
                    case 1:
                        $sittxt='Uso autorizado no momento da consulta';
                        break;
                    case 2:
                        $sittxt='Uso denegado';
                        break;
                    case 3:
                        $sittxt='NF-e cancelada';
                }
                echo '<BR>  Situação : ' . $sittxt;
                switch ($nfe['cSitNFe']) {
                    case 0:
                       $mdtxt = 'Sem Manifestação do Destinatário';
                       break; 
                    case 1:
                        $mdtxt ='Confirmada Operação';
                        break;
                    case 2:
                        $mdtxt ='Desconhecida';
                        break;
                    case 3:
                        $mdtxt ='Operação não Realizada';
                        break;
                    case 4:
                        $mdtxt ='Ciência';
                        break;
                    default:
                        $mdtxt = 'Sem Manifestação do Destinatário';
                }
                echo '<br>Manifestação : '.$mdtxt;
                echo "<BR>";
                echo 'NSU : ' . $nfe['NSU'];
                echo "<BR><BR>";
            }    
        } else {
            echo "Nada localizado";
            echo "<BR><BR>";
        }        
    }
    flush();
    //atençao o tempo de execução desse loop pode exceder 
    //o tempo limite de processamento do php e o script pode ser interrompido
    //é recomendável que a pesquisa seja feita em etapas usando o numero do ultNSU
    //registrado 
    $limite++;
    //tem de haver um intervalo de tempo entre cada pesquisa caso contrario o 
    //webservice pode parar de responder, considerando ou um excesso de consultas
    //ou um ataque DoS
    sleep(5);
}
