<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFe\ToolsNFe;
use NFePHP\Extras\Danfce;
use NFePHP\Common\Files\FilesFolders;

$nfe = new ToolsNFe('../../config/config.json');

$saida = isset($_REQUEST['o']) ? $_REQUEST['o'] : 'pdf'; //pdf ou html

$tpAmb = $nfe->aConfig['tpAmb'];
$cUF = $nfe->getcUF($nfe->aConfig['siglaUF']); //cUF - Código IBGE da UF
$logo = $nfe->aConfig['aDocFormat']->pathLogoFile;
$idCSC = $nfe->aConfig['tokenNFCeId']; //ID do CSC
$codCSC = $nfe->aConfig['tokenNFCe']; //Código de Segurança do Contribuinte (antigo Token)

$urlQR = $nfe->zGetUrlQR($cUF, $tpAmb); //Busca o Link de consulta do QR-Code

$ecoNFCe = false; //false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
$chave = '52160700067985000172650010000002011000002015';
$xmlProt = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/enviadas/aprovadas/201607/{$chave}-protNFe.xml";
// Uso da nomeclatura '-danfce.pdf' para facilitar a diferenciação entre PDFs DANFE e DANFCE salvos na mesma pasta...
$pdfDanfe = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/pdf/201607/{$chave}-danfce.pdf";

$docxml = FilesFolders::readFile($xmlProt);
$danfce = new Danfce($docxml, $logo, 2, $idCSC, $codCSC, $urlQR);

$id = $danfce->montaDANFCE($ecoNFCe);
$salva = $danfce->printDANFCE('pdf', $pdfDanfe, 'F'); //Salva na pasta pdf
$abre = $danfce->printDANFCE($saida, $pdfDanfe, 'I'); //Abre na tela