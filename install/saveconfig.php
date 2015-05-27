<?php

namespace NFePHP\install;

require_once('../bootstrap.php');

use NFePHP\Common\Files\FilesFolders;

$configfolder = filter_input(INPUT_POST, 'configfolder', FILTER_SANITIZE_STRING);

$tpAmb = filter_input(
    INPUT_POST,
    'tpAmb',
    FILTER_VALIDATE_INT,
    array("options" => array("min_range"=>1, "max_range"=>2))
);

$pathXmlUrlFileNFe = filter_input(INPUT_POST, 'pathXmlUrlFileNFe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileCTe = filter_input(INPUT_POST, 'pathXmlUrlFileCTe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileMDFe = filter_input(INPUT_POST, 'pathXmlUrlFileMDFe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileCLe = filter_input(INPUT_POST, 'pathXmlUrlFileCLe', FILTER_SANITIZE_STRING);
$pathXmlUrlFileNFSe = filter_input(INPUT_POST, 'pathXmlUrlFileNFSe', FILTER_SANITIZE_STRING);

$pathNFeFiles = filter_input(INPUT_POST, 'pathNFeFiles', FILTER_SANITIZE_STRING);
$pathCTeFiles = filter_input(INPUT_POST, 'pathCTeFiles', FILTER_SANITIZE_STRING);
$pathMDFeFiles = filter_input(INPUT_POST, 'pathMDFeFiles', FILTER_SANITIZE_STRING);
$pathCLeFiles = filter_input(INPUT_POST, 'pathCLeFiles', FILTER_SANITIZE_STRING);
$pathNFSeFiles = filter_input(INPUT_POST, 'pathNFSeFiles', FILTER_SANITIZE_STRING);
$pathCertsFiles = filter_input(INPUT_POST, 'pathCertsFiles', FILTER_SANITIZE_STRING);

$siteUrl = filter_input(INPUT_POST, 'siteUrl', FILTER_SANITIZE_URL);

$schemesNFe = filter_input(INPUT_POST, 'schemesNFe', FILTER_SANITIZE_STRING);
$schemesCTe = filter_input(INPUT_POST, 'schemesCTe', FILTER_SANITIZE_STRING);
$schemesMDFe = filter_input(INPUT_POST, 'schemesMDFe', FILTER_SANITIZE_STRING);
$schemesCLe = filter_input(INPUT_POST, 'schemesCLe', FILTER_SANITIZE_STRING);
$schemesNFSe = filter_input(INPUT_POST, 'schemesNFSe', FILTER_SANITIZE_STRING);

$razaosocial = filter_input(INPUT_POST, 'razaosocial', FILTER_SANITIZE_SPECIAL_CHARS);
$siglaUF = filter_input(INPUT_POST, 'siglaUF', FILTER_SANITIZE_STRING);
$cnpj = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
$certPfxName = filter_input(INPUT_POST, 'certPfxName', FILTER_SANITIZE_STRING);
$certPassword = filter_input(INPUT_POST, 'certPassword', FILTER_SANITIZE_STRING);
$certPhrase = filter_input(INPUT_POST, 'certPhrase', FILTER_SANITIZE_STRING);
 
$format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_STRING);
$paper = filter_input(INPUT_POST, 'paper', FILTER_SANITIZE_STRING);
$southpaw = filter_input(INPUT_POST, 'southpaw');
$pathLogoFile = filter_input(INPUT_POST, 'pathLogoFile', FILTER_SANITIZE_STRING);
$logoPosition = filter_input(INPUT_POST, 'logoPosition', FILTER_SANITIZE_STRING);
$font = filter_input(INPUT_POST, 'font', FILTER_SANITIZE_STRING);
$printer = filter_input(INPUT_POST, 'printer', FILTER_SANITIZE_STRING);

$mailAuth = filter_input(INPUT_POST, 'mailAuth');
$mailFrom = filter_input(INPUT_POST, 'mailFrom', FILTER_VALIDATE_EMAIL);
$mailSmtp = filter_input(INPUT_POST, 'mailSmtp', FILTER_SANITIZE_URL);
$mailUser = filter_input(INPUT_POST, 'mailUser', FILTER_SANITIZE_STRING);
$mailPass = filter_input(INPUT_POST, 'mailPass', FILTER_SANITIZE_STRING);
$mailProtocol = filter_input(INPUT_POST, 'mailProtocol', FILTER_SANITIZE_STRING);
$mailPort = filter_input(INPUT_POST, 'mailPort', FILTER_SANITIZE_NUMBER_INT);
$mailFromMail = filter_input(INPUT_POST, 'mailFromMail', FILTER_VALIDATE_EMAIL);
$mailFromName = filter_input(INPUT_POST, 'mailFromName', FILTER_SANITIZE_STRING);
$mailReplayToMail = filter_input(INPUT_POST, 'mailReplayToMail', FILTER_VALIDATE_EMAIL);
$mailReplayToName = filter_input(INPUT_POST, 'mailReplayToName', FILTER_SANITIZE_STRING);
$mailImapHost = filter_input(INPUT_POST, 'mailImapHost', FILTER_SANITIZE_URL);
$mailImapPort = filter_input(INPUT_POST, 'mailImapPort', FILTER_SANITIZE_NUMBER_INT);
$mailImapSecurity = filter_input(INPUT_POST, 'mailImapSecurity', FILTER_SANITIZE_STRING);
$mailImapNocerts = filter_input(INPUT_POST, 'mailImapNocerts');
$mailImapBox = filter_input(INPUT_POST, 'mailImapBox', FILTER_SANITIZE_STRING);
   
$proxyIp = filter_input(INPUT_POST, 'proxyIp', FILTER_VALIDATE_IP);
$proxyPort = filter_input(INPUT_POST, 'proxyPort', FILTER_SANITIZE_NUMBER_INT);
$proxyUser = filter_input(INPUT_POST, 'proxyUser', FILTER_SANITIZE_STRING);
$proxyPass = filter_input(INPUT_POST, 'proxyPass', FILTER_SANITIZE_STRING);

//verificações
//existe e é gravavel em $configFolder ?

//existe  $pathXmlUrlFileNFe $pathXmlUrlFileCTe $pathXmlUrlFileMDFe $pathXmlUrlFileCLe $pathXmlUrlFileNFSe

//existe e é gravavel  $pathNFeFiles $pathCTeFiles $pathMDFeFiles $pathCLeFiles $pathNFSeFiles $pathCertsFiles

//limpar caracteres de $razaosocial

//existe $docLogoFile
if (is_file($docLogoFile)) {
    $flag = false;
    $msg .= "Arquivo Logo não localizado em $docLogoFile\n";
}

$aDocFormat = array(
    'format'=> $format,
    'paper' => $paper,
    'southpaw' => $southpaw,
    'pathLogoFile' => $pathLogoFile,
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
    'siglaUF'=> $siglaUF,
    'cnpj' => $cnpj,
    'certPfxName' => $certPfxName,
    'certPassword' => $certPassword,
    'certPhrase' => $certPhrase,
    'aDocFormat' => $aDocFormat,
    'aMailConf' => $aMailConf,
    'aProxyConf' => $aProxyConf
);


$jsonConfig = json_encode($aConfig);
