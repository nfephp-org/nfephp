<?php
/*
 * Primeiro racunho da comunicação da Carta de Correção Eletrônica
 * Colocar na pasta exemplos
 * 
 * Este script monta a mensagem a ser transmitida conforme Nota Técnica 2010/08
 * Registro de Eventos da Nota Fiscal Eletrônica - Carta de Correção
 * 
 */

require_once('../libs/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;


/*
 
 Atualmente só há dois webservices para testes e estão no RS  
  
https://homologacao.nfe.sefaz.rs.gov.br/ws/recepcaoevento/recepcaoevento.asmx
https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/recepcaoevento/recepcaoevento.asmx
  
  
POST /ws/recepcaoevento/recepcaoevento.asmx HTTP/1.1
Host: homologacao.nfe.sefaz.rs.gov.br
Content-Type: application/soap+xml; charset=utf-8
Content-Length: length

<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Header>
    <nfeCabecMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">
      <cUF>string</cUF>
      <versaoDados>string</versaoDados>
    </nfeCabecMsg>
  </soap12:Header>
  <soap12:Body>
    <nfeDadosMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">xml</nfeDadosMsg>
  </soap12:Body>
</soap12:Envelope>

HTTP/1.1 200 OK
Content-Type: application/soap+xml; charset=utf-8
Content-Length: length

<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Header>
    <nfeCabecMsg xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">
      <cUF>string</cUF>
      <versaoDados>string</versaoDados>
    </nfeCabecMsg>
  </soap12:Header>
  <soap12:Body>
    <nfeRecepcaoEventoResult xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/RecepcaoEvento">xml</nfeRecepcaoEventoResult>
  </soap12:Body>
</soap12:Envelope>  
 

 110110 Carta de Correção
    
*/

$URLPortal='http://www.portalfiscal.inf.br/nfe';
$namespace = $URLPortal.'/wsdl/RecepcaoEvento';
$versao = '1.00';

$cnpj = '10290739000139';
$cUF = '35';
$tpAmb ='2';
$TDZ = '-03:00'; //horário de brasilia

$chNFe = '35110310290739000139550010000000011051128041';
$idLote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);

$verEvento = '1.00';
$nSeqEvento = '1';
$descEvento = 'Carta de Correcao';
$xCorrecao = 'Texto de teste para Carta de Correção. Conteúdo do campo xCorrecao.';

$tpEvento = '110110';
$dhEvento = date('Y-m-d').'T'.date('H:i:s').$TDZ;
$id = 'ID'.$tpEvento.$chNFe.$nSeqEvento;

//$descEvento = htmlentities($descEvento,ENT_NOQUOTES, "UTF-8"); 
//$xCorrecao = htmlentities($xCorrecao,ENT_NOQUOTES, "UTF-8"); 
//$descEvento = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $descEvento);
//$xCorrecao = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $xCorrecao);
$xCorrecao = limpaAcentos($xCorrecao);

$cabecalho = '<nfeCabecMsg xmlns="'. $namespace . '"><cUF>'.$cUF.'</cUF><versaoDados>'.$versao.'</versaoDados></nfeCabecMsg>';

$dados = '<envEvento versao="'.$versao.'">';
$dados .= '<idLote>'.$idLote.'</idLote>';
$dados .= '<evento xmlns="'.$URLPortal.'" versao="'.$versao.'">';
$dados .= '<infEvento Id="'.$id.'">';
$dados .= '<cOrgao>'.$cUF.'</cOrgao>';
$dados .= '<tpAmb>'.$tpAmb.'</tpAmb>';
$dados .= '<CNPJ>'.$cnpj.'</CNPJ>';
$dados .= '<chNFe>'.$chNFe.'</chNFe>';
$dados .= '<dhEvento>'.$dhEvento.'</dhEvento>';
$dados .= '<tpEvento>'.$tpEvento.'</tpEvento>';
$dados .= '<nSeqEvento>'.$nSeqEvento.'</nSeqEvento>';
$dados .= '<verEvento>'.$verEvento.'</verEvento>';
$dados .= '<detEvento versao="'.$verEvento.'">';
$dados .= '<descEvento>'.$descEvento.'</descEvento>';
$dados .= '<xCorrecao>'.$xCorrecao.'</xCorrecao>';
$dados .= '</detEvento>';
$dados .= '</infEvento>';
$dados .= '</evento>';
$dados .= '</envEvento>';

$dados = $nfe->signXML($dados, 'infEvento');
$dados = '<nfeDadosMsg xmlns="'. $namespace . '">'.$dados.'</nfeDadosMsg>';
//remove as tags xml que porventura tenham sido inclusas ou quebas de linhas
$dados = str_replace('<?xml version="1.0"?>','', $dados);
$dados = str_replace('<?xml version="1.0" encoding="utf-8"?>','', $dados);
$dados = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $dados);
$dados = str_replace(array("\r","\n","\s"),"", $dados);

$charset = "utf-8";
$mime    = (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) ? "application/xhtml+xml" : "text/html";
header("content-type:$mime;charset=$charset");

echo $dados;

function limpaAcentos($texto=''){
    if ($texto == ''){
        return '';
    }
    setlocale(LC_ALL, 'pt_BR.utf8');
    $output = iconv('utf8', 'ascii//TRANSLIT', $texto);
    $output = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $output);
    return $output;
}
?>
