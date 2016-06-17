<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once '../../bootstrap.php';

use NFePHP\CTe\MakeCTe;
use NFePHP\CTe\ToolsCTe;

$cte = new MakeCTe();
$cteTools = new ToolsCTe('../../config/config.json');

$dhEmi = date("Y-m-d\TH:i:sP");

$chave = $cte->montaChave(
    $cUF = '43',
    $ano = date('y',strtotime($dhEmi)),
    $mes = date('m',strtotime($dhEmi)),
    $cnpj = $cteTools->aConfig['cnpj'],
    $mod = '57',
    $serie = '1',
    $numero = '10',
    $tpEmis = '1',
    $cNF = '10');

$resp = $cte->infCteTag(
    $chave,
    $versao = '2.00');

$cDV = substr($chave, -1); //Digito Verificador

$resp = $cte->ideTag(
    $cUF = '43',
    $cCT = '00000010',
    $CFOP = '5351',
    $natOp = 'Prestação de serviço de transporte para execução de serviço da mesma natureza',
    $mod = '57',
    $serie = '1',
    $nCT = '10',
    $dhEmi,
    $tpImp = '1',
    $tpEmis = '1',
    $cDV,
    $tpAmb = '2', //homologacao
    $tpCTe = '0',
    $procEmi = '0',
    $verProc = '2.0',
    $refCTE = '',
    $cMunEnv = '',
    $xMunEnv = '',
    $UFEnv = '',
    $modal = '01',
    $tpServ = '0',
    $cMunIni = '',
    $xMunIni = '',
    $UFIni = '',
    $cMunFim = '',
    $xMunFim = '',
    $UFFim = '',
    $retira = '1',
    $xDetRetira = '',
    $dhCont = '',
    $xJust = ''
);

$resp = $cte->toma03Tag(
    $toma = '0'
);

$resp = $cte->toma4Tag(
    $toma = '4',
    $CNPJ = '11509962000197',
    $CPF = '',
    $IE = 'ISENTO',
    $xNome = 'OTIMIZY',
    $xFant = 'OTIMIZY',
    $fone = '5434625522',
    $email = 'contato@otimizy.com.br'
);


$resp = $cte->enderTomaTag(
    $xLgr = '',
    $nro = '',
    $xCpl = '',
    $xBairro = '',
    $cMun = '',
    $xMun = '',
    $CEP = '',
    $UF = '',
    $cPais = '',
    $xPais = ''
);

$resp = $cte->emitTag(
    $CNPJ = $cteTools->aConfig['cnpj'],
    $IE = $cteTools->aConfig['ie'],
    $xNome = $cteTools->aConfig['razaosocial'],
    $xFant = $cteTools->aConfig['nomefantasia']
);


$resp = $cte->enderEmitTag(
    $xLgr = $cteTools->aConfig['razaosocial'],
    $nro = $cteTools->aConfig['razaosocial'],
    $xCpl = $cteTools->aConfig['razaosocial'],
    $xBairro = $cteTools->aConfig['razaosocial'],
    $cMun = $cteTools->aConfig['razaosocial'],
    $xMun = $cteTools->aConfig['razaosocial'],
    $CEP = $cteTools->aConfig['razaosocial'],
    $UF = $cteTools->aConfig['razaosocial'],
    $fone = $cteTools->aConfig['razaosocial']
);

$resp = $cte->montaCTe();

//$filename = "/Applications/XAMPP/xamppfiles/htdocs/projetos/nfephp/xml/{$chave}-cte.xml";
$filename = "/Applications/XAMPP/xamppfiles/htdocs/projetos/nfephp/xml/cte/{$chave}-cte.xml";

if ($resp) {
    //header('Content-type: text/xml; charset=UTF-8');
    $xml = $cte->getXML();
    file_put_contents($filename, $xml);
    //chmod($filename, 0777);
    //echo $xml;
} else {
    header('Content-type: text/html; charset=UTF-8');
    foreach ($cte->erros as $err) {
        echo 'tag: &lt;'.$err['tag'].'&gt; ---- '.$err['desc'].'<br>';
    }
}


$xml = file_get_contents($filename);
$xml = $cteTools->assina($xml);
$filename = "/Applications/XAMPP/xamppfiles/htdocs/projetos/nfephp/xml/cte/0008-cte.xml";
//$filename = "/Applications/XAMPP/xamppfiles/htdocs/projetos/nfephp/xml/cte/{$chave}-cte.xml";
file_put_contents($filename, $xml);
//chmod($filename, 0777);
//echo $xml;


$retorno = array();
$tpAmb = '2';
$idLote = '';
$indSinc = '1';
$flagZip = false;
$retorno = $cteTools->sefazEnvia($xml, $tpAmb = '2', $idLote, $retorno, $indSinc, $flagZip);
//echo '<br><br><pre>';
//echo htmlspecialchars($cteTools->soapDebug);
echo '</pre><br><br><pre>';
print_r($retorno);
echo "</pre><br>";
