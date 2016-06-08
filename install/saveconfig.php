<?php

namespace NFePHP\install;

require_once('../bootstrap.php');

if (!defined('PATH_NFEPHP')) {
    define('PATH_NFEPHP', dirname(dirname(__FILE__)));
}

use NFePHP\Common\Files\FilesFolders;

$configfolder = filter_input(INPUT_GET, 'configfolder', FILTER_SANITIZE_STRING);

$defaultconfigfolder = PATH_NFEPHP .'/config';
$defaultpathConfig =  $defaultconfigfolder .'/config.json';


$tpAmb = filter_input(
    INPUT_GET,
    'tpAmb',
    FILTER_VALIDATE_INT,
    array("options" => array("min_range"=>1, "max_range"=>2))
);

$pathXmlUrlFileNFe = filter_input(INPUT_GET, 'pathXmlUrlFileNFe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileCTe = filter_input(INPUT_GET, 'pathXmlUrlFileCTe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileMDFe = filter_input(INPUT_GET, 'pathXmlUrlFileMDFe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileCLe = filter_input(INPUT_GET, 'pathXmlUrlFileCLe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileNFSe = filter_input(INPUT_GET, 'pathXmlUrlFileNFSe', FILTER_SANITIZE_STRING);

$pathNFeFiles = filter_input(INPUT_GET, 'pathNFeFiles', FILTER_SANITIZE_STRING);
$pathCTeFiles = filter_input(INPUT_GET, 'pathCTeFiles', FILTER_SANITIZE_STRING);
$pathMDFeFiles = filter_input(INPUT_GET, 'pathMDFeFiles', FILTER_SANITIZE_STRING);
$pathCLeFiles = filter_input(INPUT_GET, 'pathCLeFiles', FILTER_SANITIZE_STRING);
$pathNFSeFiles = filter_input(INPUT_GET, 'pathNFSeFiles', FILTER_SANITIZE_STRING);
$pathCertsFiles = filter_input(INPUT_GET, 'pathCertsFiles', FILTER_SANITIZE_STRING);

$siteUrl = filter_input(INPUT_GET, 'siteUrl', FILTER_SANITIZE_URL);

$schemesNFe = filter_input(INPUT_GET, 'schemesNFe', FILTER_SANITIZE_STRING);
$schemesCTe = filter_input(INPUT_GET, 'schemesCTe', FILTER_SANITIZE_STRING);
$schemesMDFe = filter_input(INPUT_GET, 'schemesMDFe', FILTER_SANITIZE_STRING);
$schemesCLe = filter_input(INPUT_GET, 'schemesCLe', FILTER_SANITIZE_STRING);
$schemesNFSe = filter_input(INPUT_GET, 'schemesNFSe', FILTER_SANITIZE_STRING);

$razaosocial = filter_input(INPUT_GET, 'razaosocial', FILTER_SANITIZE_SPECIAL_CHARS);
$nomefantasia = filter_input(INPUT_GET, 'nomefantasia', FILTER_SANITIZE_SPECIAL_CHARS);
$siglaUF = filter_input(INPUT_GET, 'siglaUF', FILTER_SANITIZE_STRING);
$cnpj = filter_input(INPUT_GET, 'cnpj', FILTER_SANITIZE_STRING);
$ie = filter_input(INPUT_GET, 'ie', FILTER_SANITIZE_STRING);
$im = filter_input(INPUT_GET, 'im', FILTER_SANITIZE_STRING);
$iest = filter_input(INPUT_GET, 'iest', FILTER_SANITIZE_STRING);
$cnae = filter_input(INPUT_GET, 'cnae', FILTER_SANITIZE_STRING);
$regime = filter_input(
    INPUT_GET,
    'regime',
    FILTER_VALIDATE_INT,
    array("options" => array("min_range"=>1, "max_range"=>3))
);
$tokenIBPT = filter_input(INPUT_GET, 'tokenIBPT', FILTER_SANITIZE_STRING);
$tokenNFCe = filter_input(INPUT_GET, 'tokenNFCe', FILTER_SANITIZE_STRING);
$tokenNFCeId = filter_input(INPUT_GET, 'tokenNFCeId', FILTER_SANITIZE_STRING);

$certPfxName = filter_input(INPUT_GET, 'certPfxName', FILTER_SANITIZE_STRING);
$certPassword = filter_input(INPUT_GET, 'certPassword', FILTER_SANITIZE_STRING);
$certPhrase = filter_input(INPUT_GET, 'certPhrase', FILTER_SANITIZE_STRING);
 
$format = filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING);
$paper = filter_input(INPUT_GET, 'paper', FILTER_SANITIZE_STRING);
$southpaw = filter_input(INPUT_GET, 'southpaw');
$pathLogoFile = filter_input(INPUT_GET, 'pathLogoFile', FILTER_SANITIZE_STRING);
$pathLogoNFe = filter_input(INPUT_GET, 'pathLogoNFe', FILTER_SANITIZE_STRING);
$pathLogoNFCe = filter_input(INPUT_GET, 'pathLogoNFCe', FILTER_SANITIZE_STRING);
$logoPosition = filter_input(INPUT_GET, 'logoPosition', FILTER_SANITIZE_STRING);
$font = filter_input(INPUT_GET, 'font', FILTER_SANITIZE_STRING);
$printer = filter_input(INPUT_GET, 'printer', FILTER_SANITIZE_STRING);

$mailAuth = filter_input(INPUT_GET, 'mailAuth');
$mailFrom = filter_input(INPUT_GET, 'mailFrom', FILTER_VALIDATE_EMAIL);
$mailSmtp = filter_input(INPUT_GET, 'mailSmtp', FILTER_SANITIZE_URL);
$mailUser = filter_input(INPUT_GET, 'mailUser', FILTER_SANITIZE_STRING);
$mailPass = filter_input(INPUT_GET, 'mailPass', FILTER_SANITIZE_STRING);
$mailProtocol = filter_input(INPUT_GET, 'mailProtocol', FILTER_SANITIZE_STRING);
$mailPort = filter_input(INPUT_GET, 'mailPort', FILTER_SANITIZE_NUMBER_INT);

$mailFromMail = filter_input(INPUT_GET, 'mailFromMail', FILTER_VALIDATE_EMAIL);
$mailFrom = empty($mailFrom) ? $mailFromMail : '';
$mailFromName = filter_input(INPUT_GET, 'mailFromName', FILTER_SANITIZE_STRING);
$mailReplayToMail = filter_input(INPUT_GET, 'mailReplayToMail', FILTER_VALIDATE_EMAIL);
$mailReplayToName = filter_input(INPUT_GET, 'mailReplayToName', FILTER_SANITIZE_STRING);
$mailImapHost = filter_input(INPUT_GET, 'mailImapHost', FILTER_SANITIZE_URL);
$mailImapPort = filter_input(INPUT_GET, 'mailImapPort', FILTER_SANITIZE_NUMBER_INT);
$mailImapSecurity = filter_input(INPUT_GET, 'mailImapSecurity', FILTER_SANITIZE_STRING);
$mailImapNocerts = filter_input(INPUT_GET, 'mailImapNocerts');
$mailImapBox = filter_input(INPUT_GET, 'mailImapBox', FILTER_SANITIZE_STRING);
   
$proxyIp = filter_input(INPUT_GET, 'proxyIp', FILTER_SANITIZE_STRING);
$proxyPort = filter_input(INPUT_GET, 'proxyPort', FILTER_SANITIZE_NUMBER_INT);
$proxyUser = filter_input(INPUT_GET, 'proxyUser', FILTER_SANITIZE_STRING);
$proxyPass = filter_input(INPUT_GET, 'proxyPass', FILTER_SANITIZE_STRING);

$aDocFormat = array(
    'format'=> $format,
    'paper' => $paper,
    'southpaw' => $southpaw,
    'pathLogoFile' => $pathLogoFile,
    'pathLogoNFe' => $pathLogoNFe,
    'pathLogoNFCe' => $pathLogoNFCe,
    'logoPosition' => $logoPosition,
    'font' => $font,
    'printer' => $printer
);

$aMailConf = array(
    'mailAuth' => $mailAuth,
    'mailFrom' => $mailFrom,
    'mailSmtp' => $mailSmtp,
    'mailUser' => $mailUser,
    'mailPass' => $mailPass,
    'mailProtocol' => $mailProtocol,
    'mailPort' => $mailPort,
    'mailFromMail' => $mailFromMail,
    'mailFromName' => $mailFromName,
    'mailReplayToMail' => $mailReplayToMail,
    'mailReplayToName' => $mailReplayToName,
    'mailImapHost' => $mailImapHost,
    'mailImapPort' => $mailImapPort,
    'mailImapSecurity' => $mailImapSecurity,
    'mailImapNocerts' => $mailImapNocerts,
    'mailImapBox' => $mailImapBox
);

$aProxyConf = array(
    'proxyIp'=> $proxyIp,
    'proxyPort'=> $proxyPort,
    'proxyUser'=> $proxyUser,
    'proxyPass'=> $proxyPass
);

$aConfig = array(
    'atualizacao' => date('Y-m-d h:i:s'),
    'tpAmb' => $tpAmb,
    'pathXmlUrlFileNFe' => $pathXmlUrlFileNFe,
    'pathXmlUrlFileCTe' => $pathXmlUrlFileCTe,
    'pathXmlUrlFileMDFe' => $pathXmlUrlFileMDFe,
    'pathXmlUrlFileCLe' => $pathXmlUrlFileCLe,
    'pathXmlUrlFileNFSe' => $pathXmlUrlFileNFSe,
    'pathNFeFiles' => $pathNFeFiles,
    'pathCTeFiles'=> $pathCTeFiles,
    'pathMDFeFiles'=> $pathMDFeFiles,
    'pathCLeFiles'=> $pathCLeFiles,
    'pathNFSeFiles'=> $pathNFSeFiles,
    'pathCertsFiles' => $pathCertsFiles,
    'siteUrl' => $siteUrl,
    'schemesNFe' => $schemesNFe,
    'schemesCTe' => $schemesCTe,
    'schemesMDFe' => $schemesMDFe,
    'schemesCLe' => $schemesCLe,
    'schemesNFSe' => $schemesNFSe,
    'razaosocial' => $razaosocial,
    'nomefantasia' => $nomefantasia,
    'siglaUF'=> $siglaUF,
    'cnpj' => $cnpj,
    'ie' => $ie,
    'im' => $im,
    'iest' => $iest,
    'cnae' => $cnae,
    'regime' => $regime,
    'tokenIBPT' => $tokenIBPT,
    'tokenNFCe' => $tokenNFCe,
    'tokenNFCeId' => $tokenNFCeId,
    'certPfxName' => $certPfxName,
    'certPassword' => $certPassword,
    'certPhrase' => $certPhrase,
    'aDocFormat' => $aDocFormat,
    'aMailConf' => $aMailConf,
    'aProxyConf' => $aProxyConf
);

$content = json_encode($aConfig);

$msg = 'SUCESSO !! arquivo de configuração confg.json SALVO.';
if (! $resdefault = FilesFolders::saveFile($defaultconfigfolder, 'config.json', $content)) {
    $msg = "Falha ao salvar o config.json na pasta $defaultconfigfolder \n";
    
}
if ($configfolder != $defaultconfigfolder) {
    if (! $res = FilesFolders::saveFile($configfolder, 'config.json', $content)) {
        $msg = "Falha ao salvar o config.json na pasta $configfolder \n";
    }
}
$aResp = array('status' => ($resdefault && $res), 'msg' => $msg);

print json_encode($aResp);
