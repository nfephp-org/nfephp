<?php

$aDocFormat = array(
    'format'=>'P',
    'paper' => 'A4',
    'southpaw' => true,
    'pathLogoFile' => '/var/www/nfephp/images/logo.jpg',
    'logoPosition' => 'L',
    'font' => 'Times',
    'printer' => 'hpteste'
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
    'proxyIp'=>'',
    'proxyPort'=>'',
    'proxyUser'=>'',
    'proxyPass'=>''
);

$aConfig = array(
    'tpAmb' => '2',
    'pathXmlUrlFileNFe' => 'nfe_ws3_mod55.xml',
    'pathXmlUrlFileCTe' => 'cte_ws2.xml',
    'pathXmlUrlFileMDFe' => 'mdfe_ws1.xml',
    'pathXmlUrlFileCLe' => 'cle_ws1.xml',
    'pathNFeFiles' => '/var/www/nfe',
    'pathCTeFiles'=> '/var/www/cte',
    'pathMDFeFiles'=> '/var/www/mdfe',
    'pathCLeFiles'=> '/var/www/cle',
    'pathCertsFiles' => '/var/www/nfephp/certs/',
    'siteUrl' => 'http://localhost/nfephp',
    'schemesNFe' => 'PL_008c',
    'schemesCTe' => 'PL_CTE_104',
    'schemesMDFe' => 'MDFe_100',
    'schemesCLe' => 'CLe_100',
    'razaosocial' => 'Sua Empresa Ltda',
    'siglaUF'=> 'SP',
    'cnpj' => '9999999999999',
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
$filename = './config/config.json';
file_put_contents($filename, $config);
