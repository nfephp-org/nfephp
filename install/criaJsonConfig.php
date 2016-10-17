<?php

if (!defined('PATH_NFEPHP')) {
    define('PATH_NFEPHP', dirname(dirname(__FILE__)));
}

$aDocFormat = array(
    'format'=>'P',
    'paper' => 'A4',
    'southpaw' => true,
    'pathLogoFile' => PATH_NFEPHP . 'images/logo.jpg',
    'logoPosition' => 'L',
    'font' => 'Times',
    'printer' => ''
);

$aMailConf = array(
    'mailAuth' => true,
    'mailFrom' => 'nfe@suaempresa.com.br',
    'mailSmtp' => 'smtp.suaempresa.com.br',
    'mailUser'=>'nfe@suaempresa.com.br',
    'mailPass'=>'senha',
    'mailProtocol'=>'',
    'mailPort'=>'587',
    'mailFromMail'=>'nfe@suaempresa.com.br',
    'mailFromName'=>'NFe',
    'mailReplayToMail'=> 'nfe@suaempresa.com.br',
    'mailReplayToName' => 'NFe',
    'mailImapHost' => 'imap.suaempresa.com.br',
    'mailImapPort' => '143',
    'mailImapSecurity'=> 'tls',
    'mailImapNocerts'=> 'novalidate-cert',
    'mailImapBox'=>'INBOX'
);

$aProxyConf = array(
    'proxyIp' => '',
    'proxyPort' => '',
    'proxyUser' => '',
    'proxyPass' => ''
);

$aConfig = array(
    'atualizacao' => date('Y-m-d h:i:s'),
    'tpAmb' => '2',
    'pathXmlUrlFileNFe' => 'nfe_ws3_mod55.xml',
    'pathXmlUrlFileCTe' => 'cte_ws2.xml',
    'pathXmlUrlFileMDFe' => 'mdfe_ws1.xml',
    'pathXmlUrlFileCLe' => '',
    'pathXmlUrlFileNFSe' => '',
    'pathNFeFiles' => '/var/www/nfe',
    'pathCTeFiles'=> '/var/www/cte',
    'pathMDFeFiles'=> '/var/www/mdfe',
    'pathCLeFiles'=> '/var/www/cle',
    'pathNFSeFiles'=> '/var/www/nfse',
    'pathCertsFiles' => PATH_NFEPHP . 'certs/',
    'siteUrl' => str_replace('criaJsonConfig.php', '', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]),
    'schemesNFe' => 'PL_008f',
    'schemesCTe' => 'PL_CTe_200',
    'schemesMDFe' => 'PL_MDFe_100',
    'schemesCLe' => '',
    'schemesNFSe' => '',
    'razaosocial' => 'Sua Empresa Ltda',
    'siglaUF'=> 'SP',
    'cnpj' => '9999999999999',
    'tokenIBPT' => '',
    'tokenNFCe' => '',
    'tokenNFCeId' => '',
    'certPfxName' => 'certificado.pfx',
    'certPassword' => 'senha',
    'certPhrase' => '',
    'aDocFormat' => $aDocFormat,
    'aMailConf' => $aMailConf,
    'aProxyConf' => $aProxyConf
);

print_r($aConfig);
echo "<BR><BR><BR>";

$config = json_encode($aConfig);
$filename = PATH_NFEPHP . '/config/config.json';
file_put_contents($filename, $config);
