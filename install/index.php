<?php

namespace NFePHP\install;

/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 *
 * @package   NFePHP
 * @name      index.php
 * @version   4.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2015 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *          CONTRIBUIDORES (por ordem alfabetica):
 *              Clauber Santos <cload_info at yahoo dot com dot br>
 *              Leandro C. Lopez <leandro.castoldi@gmail.com>
 * 
**/
if (!defined('PATH_NFEPHP')) {
    define('PATH_NFEPHP', dirname(dirname(__FILE__)));
}
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once('../bootstrap.php');

use NFePHP\Common\Configure\Configure;
use NFePHP\Common\Files\FilesFolders;

$htmod = Configure::chkModules();

//variaveis da configuração
$tpAmb = 2;
$pathXmlUrlFileNFe = PATH_NFEPHP .'/config/nfe_ws3_mod55.xml';
$pathXmlUrlFileCTe = PATH_NFEPHP .'/config/cte_ws2.xml';
$pathXmlUrlFileMDFe = PATH_NFEPHP .'/config/mdf2_ws1.xml';
$pathXmlUrlFileCLe = '';
$pathXmlUrlFileNFSe = '';
$pathNFeFiles = '';
$pathCTeFiles = '';
$pathMDFeFiles = '';
$pathCLeFiles = '';
$pathNFSeFiles = '';
$pathCertsFiles = PATH_NFEPHP .'/certs/';
$siteUrl = str_replace('index.php', '', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]);
$schemesNFe = 'PL_008f';
$schemesCTe = 'PL_CTe_200';
$schemesMDFe = 'PL_MDFe_100';
$schemesCLe = '';
$schemesNFSe = '';

$razaosocial = '';
$siglaUF = 'SP';
$cnpj = '';
$tokenIBPT = '';
$tokenNFCe = '';
$tokenNFCeId = '';
$certPfxName = '';
$certPassword = '';
$certPhrase = '';
 
$format = 'P';
$paper = 'A4';
$southpaw = true;
$pathLogoFile = '';
$logoPosition = 'L';
$font = 'Times';
$printer = '';

$mailAuth = true;
$mailFrom = '';
$mailSmtp = '';
$mailUser = '';
$mailPass = '';
$mailProtocol = '';
$mailPort = '';
$mailFromMail = '';
$mailFromName = '';
$mailReplayToMail = '';
$mailReplayToName = '';
$mailImapHost = '';
$mailImapPort = '';
$mailImapSecurity = '';
$mailImapNocerts = '';
$mailImapBox = '';
   
$proxyIp = '';
$proxyPort = '';
$proxyUser = '';
$proxyPass = '';

$configfolder = PATH_NFEPHP .'/config';
$pathConfig =  $configfolder .'/config.json';

$configureVer = '4.0';

if (is_file($pathConfig)) {
    $configJson = FilesFolders::readFile($pathConfig);
    $installConfig = json_decode($configJson);

    $tpAmb = isset($installConfig->tpAmb) ? $installConfig->tpAmb : '2';
    $pathXmlUrlFileNFe = isset($installConfig->pathXmlUrlFileNFe) ? $installConfig->pathXmlUrlFileNFe : '';
    $pathXmlUrlFileCTe = isset($installConfig->pathXmlUrlFileCTe) ? $installConfig->pathXmlUrlFileCTe : '';
    $pathXmlUrlFileMDFe = isset($installConfig->pathXmlUrlFileMDFe) ? $installConfig->pathXmlUrlFileMDFe : '';
    $pathXmlUrlFileCLe = isset($installConfig->pathXmlUrlFileCLe) ? $installConfig->pathXmlUrlFileCLe : '';
    $pathXmlUrlFileNFSe = isset($installConfig->pathXmlUrlFileNFSe) ? $installConfig->pathXmlUrlFileNFSe : '';
    $pathNFeFiles = isset($installConfig->pathNFeFiles) ? $installConfig->pathNFeFiles : '';
    $pathCTeFiles = isset($installConfig->pathCTeFiles) ? $installConfig->pathCTeFiles : '';
    $pathMDFeFiles = isset($installConfig->pathMDFeFiles) ? $installConfig->pathMDFeFiles : '';
    $pathCLeFiles = isset($installConfig->pathCLeFiles) ? $installConfig->pathCLeFiles : '';
    $pathNFSeFiles = isset($installConfig->pathNFSeFiles) ? $installConfig->pathNFSeFiles : '';
    $pathCertsFiles = isset($installConfig->pathCertsFiles) ? $installConfig->pathCertsFiles : '';
    $siteUrl = isset($installConfig->siteUrl) ? $installConfig->siteUrl : $siteUrl;
    $schemesNFe = isset($installConfig->schemesNFe) ? $installConfig->schemesNFe : '';
    $schemesCTe = isset($installConfig->schemesCTe) ? $installConfig->schemesCTe : '';
    $schemesMDFe = isset($installConfig->schemesMDFe) ? $installConfig->schemesMDFe : '';
    $schemesCLe = isset($installConfig->schemesCLe) ? $installConfig->schemesCLe : '';
    $schemesNFSe = isset($installConfig->schemesNFSe) ? $installConfig->schemesNFSe : '';
    $razaosocial = isset($installConfig->razaosocial) ? $installConfig->razaosocial : '';
    $siglaUF = isset($installConfig->siglaUF) ? $installConfig->siglaUF : 'SP';
    $cnpj = isset($installConfig->cnpj) ? $installConfig->cnpj : '';
    $tokenIBPT = isset($installConfig->tokenIBPT) ? $installConfig->tokenIBPT : '';
    $tokenNFCe = isset($installConfig->tokenNFCe) ? $installConfig->tokenNFCe : '';
    $tokenNFCeId = isset($installConfig->tokenNFCeId) ? $installConfig->tokenNFCeId : '';
    
    $certPfxName = isset($installConfig->certPfxName) ? $installConfig->certPfxName : '';
    $certPassword = isset($installConfig->certPassword) ? $installConfig->certPassword : '';
    $certPhrase = isset($installConfig->certPhrase) ? $installConfig->certPhrase : '';
    
    $format = isset($installConfig->aDocFormat->format) ? $installConfig->aDocFormat->format : 'P';
    $paper = isset($installConfig->aDocFormat->paper) ? $installConfig->aDocFormat->paper : 'A4';
    $southpaw = isset($installConfig->aDocFormat->southpaw) ? $installConfig->aDocFormat->southpaw : true;
    $pathLogoFile = isset($installConfig->aDocFormat->pathLogoFile) ? $installConfig->aDocFormat->pathLogoFile : '';
    $logoPosition = isset($installConfig->aDocFormat->logoPosition) ? $installConfig->aDocFormat->logoPosition : 'L';
    $font = isset($installConfig->aDocFormat->font) ? $installConfig->aDocFormat->font : 'Times';
    $printer = isset($installConfig->aDocFormat->printer) ? $installConfig->aDocFormat->printer : '';

    $mailAuth = isset($installConfig->aMailConf->mailAuth) ? $installConfig->aMailConf->mailAuth : true;
    $mailFrom = isset($installConfig->aMailConf->mailFrom) ? $installConfig->aMailConf->mailFrom : '';
    $mailSmtp = isset($installConfig->aMailConf->mailSmtp) ? $installConfig->aMailConf->mailSmtp : '';
    $mailUser = isset($installConfig->aMailConf->mailUser) ? $installConfig->aMailConf->mailUser : '';
    $mailPass = isset($installConfig->aMailConf->mailPass) ? $installConfig->aMailConf->mailPass : '';
    $mailProtocol = isset($installConfig->aMailConf->mailProtocol) ? $installConfig->aMailConf->mailProtocol : '';
    $mailPort = isset($installConfig->aMailConf->mailPort) ? $installConfig->aMailConf->mailPort : '';
    $mailFromMail = isset($installConfig->aMailConf->mailFromMail) ? $installConfig->aMailConf->mailFromMail : '';
    $mailFromName = isset($installConfig->aMailConf->mailFromName) ? $installConfig->aMailConf->mailFromName : '';
    $mailReplayToMail = isset($installConfig->aMailConf->mailReplayToMail) ? $installConfig->aMailConf->mailReplayToMail : '';
    $mailReplayToName = isset($installConfig->aMailConf->mailReplayToName) ? $installConfig->aMailConf->mailReplayToName : '';
    $mailImapHost = isset($installConfig->aMailConf->mailImapHost) ? $installConfig->aMailConf->mailImapHost : '';
    $mailImapPort = isset($installConfig->aMailConf->mailImapPort) ? $installConfig->aMailConf->mailImapPort : '';
    $mailImapSecurity = isset($installConfig->aMailConf->mailImapSecurity) ? $installConfig->aMailConf->mailImapSecurity : '';
    $mailImapNocerts = isset($installConfig->aMailConf->mailImapNocerts) ? $installConfig->aMailConf->mailImapNocerts : '';
    $mailImapBox = isset($installConfig->aMailConf->mailImapBox) ? $installConfig->aMailConf->mailImapBox : '';
    
    $proxyIp = isset($installConfig->aProxyConf->proxyIp) ? $installConfig->aProxyConf->proxyIp : '';
    $proxyPort = isset($installConfig->aProxyConf->proxyPort) ? $installConfig->aProxyConf->proxyPort : '';
    $proxyUser = isset($installConfig->aProxyConf->proxyUser) ? $installConfig->aProxyConf->proxyUser : '';
    $proxyPass = isset($installConfig->aProxyConf->proxyPass) ? $installConfig->aProxyConf->proxyPass : '';
}

$aUF = array(
    'AC',
    'AL',
    'AM',
    'AP',
    'BA',
    'CE',
    'DF',
    'ES',
    'GO',
    'MA',
    'MG',
    'MS',
    'MT',
    'PA',
    'PB',
    'PE',
    'PI',
    'PR',
    'RJ',
    'RN',
    'RO',
    'RR',
    'RS',
    'SC',
    'SE',
    'SP',
    'TO'
);

$selUF = "<select id=\"siglaUF\" name=\"siglaUF\" size=\"1\">";
foreach ($aUF as $sigla) {
    $option = '';
    if ($sigla == $siglaUF) {
        $option = 'SELECTED';
    }
    $selUF .= "<option value=\"$sigla\" $option>$sigla</option>";
}
$selUF .= "</select>";

$selAmb = "<select id=\"tpAmb\" name=\"tpAmb\" size=\"1\">";
if ($tpAmb == 1) {
    $selAmb .= "<option value=\"1\" selected>Produção</option>";
    $selAmb .= "<option value=\"2\">Homologação</option>";
} else {
    $selAmb .= "<option value=\"1\">Produção</option>";
    $selAmb .= "<option value=\"2\" selected>Homologação</option>";
}
$selAmb .= "</select>";

$selDocFormat = "<select id=\"format\" name=\"format\" size=\"1\">";
if ($format != 'P') {
    $selDocFormat .= "<option value=\"L\">Landscape</option>";
    $selDocFormat .= "<option value=\"P\" SELECTED>Portrait</option>";
} else {
    $selDocFormat .= "<option value=\"L\" SELECTED>Landscape</option>";
    $selDocFormat .= "<option value=\"P\">Portrait</option>";
}
$selDocFormat .= "</select>";

$selDocSouthpaw = "<select id=\"southpaw\" name=\"southpaw\" size=\"1\">";
if ($southpaw) {
    $selDocSouthpaw .= "<option value=\"0\">NÃO</option>";
    $selDocSouthpaw .= "<option value=\"1\" SELECTED>SIM</option>";
} else {
    $selDocSouthpaw .= "<option value=\"0\" SELECTED>NÃO</option>";
    $selDocSouthpaw .= "<option value=\"1\">SIM</option>";
}
$selDocSouthpaw .= "</select>";

$selLogoPosition = "<select id=\"logoPosition\" name=\"logoPosition\" size=\"1\">";
if ($logoPosition == 'L') {
    $selLogoPosition .= "<option value=\"L\" SELECTED>Left</option>";
    $selLogoPosition .= "<option value=\"C\">Center</option>";
    $selLogoPosition .= "<option value=\"R\">Right</option>";
} elseif ($logoPosition == 'C') {
    $selLogoPosition .= "<option value=\"L\">Left</option>";
    $selLogoPosition .= "<option value=\"C\" SELECTED>Center</option>";
    $selLogoPosition .= "<option value=\"R\">Right</option>";
} else {
    $selLogoPosition .= "<option value=\"L\">Left</option>";
    $selLogoPosition .= "<option value=\"C\">Center</option>";
    $selLogoPosition .= "<option value=\"R\" SELECTED>Right</option>";
}
$selLogoPosition .= "</select>";

$selMailAuth = "<select id=\"mailAuth\" name=\"mailAuth\" size=\"1\">";
if ($mailAuth) {
    $selMailAuth .= "<option value=\"0\" >NÃO</option>";
    $selMailAuth .= "<option value=\"1\" SELECTED>SIM</option>";
} else {
    $selMailAuth .= "<option value=\"0\" SELECTED>NÃO</option>";
    $selMailAuth .= "<option value=\"1\">SIM</option>";
}
$selMailAuth .= "</select>";

$selMailProt = "<select id=\"mailProtocol\" name=\"mailProtocol\" size=\"1\">";
if ($mailProtocol == '') {
    $selMailProt .= "<option value=\"\" selected>none</option>";
    $selMailProt .= "<option value=\"ssl\">ssl</option>";
    $selMailProt .= "<option value=\"tls\">tls</option>";
} elseif ($mailProtocol == 'ssl') {
    $selMailProt .= "<option value=\"\">none</option>";
    $selMailProt .= "<option value=\"ssl\"  selected>ssl</option>";
    $selMailProt .= "<option value=\"tls\">tls</option>";
} elseif ($mailProtocol == 'tls') {
    $selMailProt .= "<option value=\"\">none</option>";
    $selMailProt .= "<option value=\"ssl\">ssl</option>";
    $selMailProt .= "<option value=\"tls\" selected>tls</option>";
}
$selMailProt .= "</select>";

?>
<!doctype html>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <title>NFePHP Configure API</title>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="./nfephp.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  
  $(document).ready(function(){
    $(':submit').on('click', function() {
        var urldir = 'folderchk.php';
        var button = $(this).val();
        if (button == 'save') {
            urldir = 'saveconfig.php';
        }
        if (button == 'certtest') {
            urldir = 'certchk.php';
        }
        //alert(urldir);
        var dados = $("#formconf").serialize();
        $.ajax({
            url: urldir,
            data: dados,
            dataType: 'json',
        })
        .done(function(data) {
            var jArray = JSON.parse(JSON.stringify(data));
            if (button == 'save') {
                var msg = jArray['msg'];
                document.getElementById('dialogcert').textContent = msg;
                $( "#dialogcert" ).dialog( "open" );
            }
            if (button == 'certtest') {
                for (var key in jArray) {
                    var resp = jArray[key];
                    var flag = resp['status'];
                    var msg = resp['msg'];
                    var title = 'teste';
                    document.getElementById('dialogcert').textContent = msg;
                    $( "#dialogcert" ).dialog( "open" );
                }    
            }
            if (button == 'test') {
                for (var key in jArray) {
                    var resp = jArray[key];
                    var flag = resp['status'];
                    var msg = resp['msg']
                    changeAlerts(key, flag, msg);
                }
            }
        });
        return false;
    });
});

$(function() {
    $( "#dialogcert" ).dialog({
        autoOpen: false,  
    });
    $( "#opener" ).click(function() {
        $( "#dialogcert" ).dialog( "open" );
    });
});

function changeAlerts(key, flag, msg) {
    var marca = 'path'+key+'Files';
    var lbl = 'lbl'+key;
    if (flag) {
        document.getElementById(marca).style.color = "lightgreen";
        document.getElementById(marca).style.backgroundColor = "white";
        document.getElementById(lbl).innerHTML = '';
    } else {
        document.getElementById(marca).style.color = "black";
        document.getElementById(marca).style.backgroundColor = "red";
        document.getElementById(lbl).innerHTML = msg;
    }
}
</script>
</head>
<body>
    <div id="content">
        <h1>Configurador do Ambiente NFePHP (versão <?php echo $configureVer?>)</h1>    
    </div>    
<div id="content">
<form id="formconf" name="formconf" method="post" action="saveconfig.php">    
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Módulos</a></li>
    <li><a href="#tabs-2">Emitente</a></li>
    <li><a href="#tabs-3">Pastas</a></li>
    <li><a href="#tabs-4">Impressão</a></li>
    <li><a href="#tabs-5">Webservices</a></li>
    <li><a href="#tabs-6">Schemas</a></li>
    <li><a href="#tabs-7">Email</a></li>
    <li><a href="#tabs-8">Proxy</a></li>
    <li><a href="#tabs-9">Finalizar</a></li>
  </ul>
  <div id="tabs-1">
    <h2 align="right">Verificação dos Módulos</h2>
    <div id="esquerda">
    <?php echo $htmod;?>
    </div>
    <div id="direita">
        <h3>O uso do NFePHP requer vários modulos do PHP para que possa funcionar. Ou seja podem haver problemas no uso da API dependendo do Sistema Operacional e da atualização de cada um dos módulos requeridos.</h3>
        <h3>Alguns problemas conhecidos se referem ao cURL que é usado para a comunicação SOAP, dependendo do S.O. o mesmo pode estar compilado para usar NSS ao invés do OpenSSL que é o padrão. E o NSS tem alguns problemas com o formato PKCS#8 das chaves privadas exportadas pelo PHP.</h3>
        <h3>Outro problema conhecido diz respeito a versão do PHP e também ao cURL. Em alguns casos o PHP não reconhece de forma automática qual é o protocolo de encripatação da conexão (SSLv3, TLSv1, TLSv1.1, etc..) e algumas SEFAZ estão usando vários desses protocolos diferentes. Se o PHP não puder reconhecer o protoclo de forma autonoma, será necessário usar os recursos da API para forçar o uso do protocolo correto, caso a caso.</h3>
        <h3>Portanto é recomendável que seja usado o S.O. mais atualizado possível. Testes foram feitos usando DEBIAN (wheezy e Jessie) e UBUNTU 14.04 LTS em ambos os casos a API funcionou perfeitamente, e usando apenas os pacotes da distribuição sem a necessidade de baixar pacotes de outras fontes ou compila-los de fonte mais atualizada.</h3>
        <p></p>
        <h3>Outros modulos podem ser necessários em função dos pacotes usados e instalados via Composer (vide composer.json)</h3>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-2">
    <h2 align="right">Cadastro do Emitente</h2>
    <div id="esquerda">
    <span title="Indique o ambiente padrão (1-Produção ou 2-Homologação)">Ambiente</span><br>
    <?php echo $selAmb;?><br>
    <span title="Indique a Razão Social Completa do Emitente">Razão Social do Emitente</span><br>
    <input type="text" id="razaosocial" name="razaosocial" placeholder="Nome completo do emitente"  value="<?php echo $razaosocial;?>" required /><br>
    <span title="Indique o CNPJ do emitente SEM FORMATAÇÃO">CNPJ do Emitente</span><br>
    <input type="text" id="cnpj" name="cnpj" placeholder="CNPJ sem formatação" size="25" value="<?php echo $cnpj;?>" required /><br>
    <span title="Selecione o estado do Emitente">Unidade da Federação</span><br>
    <?php echo $selUF;?><br>
    <span title="Indique o path real para a pasta dos certificados. Esta pasta deve ter permissões de escrita pelo usuário do servidor web. Ex. www-data">Path dos Certificados</span><br>
    <input type="text" id="pathCertsFiles" name="pathCertsFiles" placeholder="Caminho para a pasta"  value="<?php echo $pathCertsFiles;?>" required /><label id="lblCerts"></label><br>
    <span title="Indique o nome do certificado que foi salvo na pasta dos certificados">Nome do arquivo pfx (Certificado)</span><br>
    <input type="text" id="certPfxName" name="certPfxName" placeholder="Nome do arquivo PFX"  value="<?php echo $certPfxName;?>" required /><br>
    <span title="Indique a senha configurada quando você criou o certificado">Senha do Certificado</span><br>
    <input type="password" id="certPassword" name="certPassword" placeholder="senha" size="10" value="<?php echo $certPassword;?>" required /><button value="certtest" type="submit">Testar</button><br>
    <span title="Indique a palavra passe que você quer usar para encriptar os arquivos PEM do certificado">Palavra Passe</span><br>
    <input type="password" id="certPhrase" name="certPhrase" placeholder="não usado" size="35" value="<?php echo $certPhrase;?>" readonly /><br>
    <span title="Indique o endereço para acessar a base do seu sistema">URL do Site</span><br>
    <input type="text" id="siteUrl" name="siteUrl" placeholder="Site url"  value="<?php echo $siteUrl;?>" required /><br>
    <span title="Indique o token para pesquisa no IBPT, requer cadastramento prévio">Token IBPT</span><br>
    <input type="text" id="tokenIBPT" name="tokenIBPT" placeholder="token para IBPT"  value="<?php echo $tokenIBPT;?>" /><br>
    <span title="Indique o token para montagem do QRCode nas NFCe, requer cadastramento prévio na SEFAZ">Token NFCe</span><br>
    <input type="text" id="tokenNFCe" name="tokenNFCe" placeholder="toke para NFCe"  value="<?php echo $tokenNFCe;?>" /><br>
    <span title="Indique o ID do token NFCe, 6 digitos numericos com zeros a esquerda">Token Id NFCe</span><br>
    <input type="text" id="tokenNFCeId" name="tokenNFCeId" placeholder="000000" size="8" value="<?php echo $tokenNFCeId;?>" /><br>
    </div>
    <div id="direita">
        <h3>Estes campos referen-se a os dados principais do emitente e todos os campos em amarelo são OBRIGATÓRIOS.</h3>
        <h3>Razão Social - indicar a razão social do emitente exatamente igual ao seu registro na SEFAZ, alguns simbolos poderão ser substituidos por seus equivalentes em entidades html (ex. &amp;)</h3>
        <h3></h3>
        <h3></h3>
        <h3></h3>
        <h3></h3>
        <h3></h3>
        <h3></h3>
        <h3></h3>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-3">
    <h2 align="right">Estrutura de Pastas (Files)</h2>
    <div id="esquerda">
    <span title="Indique o path completo para a pasta onde ficarão das NFe. Essa pasta deverá ser criada manualmente e ter permissões de escrita pelo usuário do servidor web.">Pasta das NFe</span><br>
    <input type="text" id="pathNFeFiles" name="pathNFeFiles" placeholder="Pasta das NFe"  value="<?php echo $pathNFeFiles;?>" required /><label id="lblNFe"></label><br>
    <span title="Indique o path completo para a pasta onde ficarão das CTe. Essa pasta deverá ser criada manualmente e ter permissões de escrita pelo usuário do servidor web.">Pasta das CTe</span><br>
    <input type="text" id="pathCTeFiles" name="pathCTeFiles" placeholder="Pasta das CTe"  value="<?php echo $pathCTeFiles;?>" required /><label id="lblCTe"></label><br>
    <span title="Indique o path completo para a pasta onde ficarão das MDFe. Essa pasta deverá ser criada manualmente e ter permissões de escrita pelo usuário do servidor web.">Pasts das MDFe</span><br>
    <input type="text" id="pathMDFeFiles" name="pathMDFeFiles" placeholder="Pasta das MDFe"  value="<?php echo $pathMDFeFiles;?>" required /><label id="lblMDFe"></label><br>
    <span title="Indique o path completo para a pasta onde ficarão das CLe. Essa pasta deverá ser criada manualmente e ter permissões de escrita pelo usuário do servidor web.">Pasta das CLe</span><br>
    <input type="text" id="pathCLeFiles" name="pathCLeFiles" placeholder="Pasta das CLe"  value="<?php echo $pathCLeFiles;?>" required /><label id="lblCLe"></label><br>
    <span title="Indique o path completo para a pasta onde ficarão das NFSe. Essa pasta deverá ser criada manualmente e ter permissões de escrita pelo usuário do servidor web.">Pasta das NFSe</span><br>
    <input type="text" id="pathNFSeFiles" name="pathNFSeFiles" placeholder="Pasta das NFSe"  value="<?php echo $pathNFSeFiles;?>" required /><label id="lblNFSe"></label><br>
    <p>Teste de Escrita e Estruturação das pastas. Este teste irá verificar as permissões de escrita e criará toda a estrutura necessária nas pastas indicadas</p>
    <button value="test" type="submit">Testar</button>
    </div>
    <div id="direita">
        <h3>A estrutura de pastas é onde os arquivos xml são salvos, sejam NFe, NFCe, CTe, MDFe, CLe, ou NFSe, bem como todos os arquivos xml relatvos as mensagens da comunicaçao SOAP, tanto as enviadas como as recebidas. Tanto para ambiente de produção como de homologação.</h3>
        <h3>As pastas indicadas devem ser criadas manualmente anteriormente e com permissões pré-configuradas para permitir o acesso de escrita por parte do usuário do servidor web.</h3>
        <h3>Cada pasta desta estrutura tem uma finalidade e podem ou nao estar sendo utilizadas. Em geral sao utilizadas as pastas:</h3>
        <h3>enviadas/aprovadas - XML de documentos aprovados pela SEFAZ (esses arquivos devem ser mantidos por pelo menos 5 anos)</h3>
        <h3>enviadas/denegadas - XML de documentos DENEGADOS pela SEFAZ (esses arquivos devem ser mantidos por pelo menos 5 anos)</h3>
        <h3>recebidas - XML dos documentos recebidos de terceiros (esses arquivos devem ser mantidos por pelo menos 5 anos)</h3>
        <h3>temporarias - XML da comunicação, tanto mensagens enviadas como recebidas.</h3>
        <p><i>NOTA: A pasta das temporarias é sem duvida a mais carregada de todas e com certeza irá requerer limpezas periódicas.</i></p>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-4">
    <h2 align="right">Configurações para Impressão</h2>
    <div id="esquerda">
    <span title="">Formato Padrão na impressão</span><br>
    <?php echo $selDocFormat ?><br>
    <span title="">Tamanho do Papel (A4 apenas)</span><br>
    <input type="text" id="paper" name="paper" size="4" value="<?php echo $paper;?>" readonly /><br>
    <span title="">Imprimir o Canhoto</span><br>
    <?php echo $selDocSouthpaw ?><br>
    <span title="">Posição do Logo</span><br>
    <?php echo $selLogoPosition ?><br>
    <span title="A fonte a ser usada é obrigatoriamente TIMES. Exigência da SEFAZ.">Fonte</span><br>
    <input type="text" id="font" name="font" size="10" value="<?php echo $font;?>" readonly /><br>
    <span title="Normalmente não usado mas teria que complementar o script para permitir a impressão direta. Pois esse recurso depende do sistema operacional.">Impressora</span><br>
    <input type="text" id="printer" name="printer" placeholder="Nome da Impressora" size="30" value="<?php echo $printer;?>" /><br>
    <span title="Imagem com a logomarca, deverá ser um aquivo JPEG ou PNG">Logo</span><br>
    <input type="text" id="pathLogoFile" name="pathLogoFile" placeholder="Path completo para o arquivo com o logo"  value="<?php echo $pathLogoFile;?>" /><br>
    </div>
    <div id="direita">
        <h3>Para a impressão dos documentos auxiliares seja para NFe, CTe, MDFe ou CLe, está definido em documentação oficial o uso obrigatório de papel A4 e do tipo de letras "Times" (serifados), isso é fixo e não pode ser mudado.</h3>
        <h3>O formato de impressão seja ele "Portraite" ou "Landscape" é definido também pelo campo &lt;tpImp&gt; do XML, portanto essa definição aqui estabelecida NÃO sobrepõe a especificação contida no XML, e nem é válida para qualquer documento auxiliar.</h3>
        <h3>Para a impressão dos documentos auxiliares da NFCe as regras são diferentes tendo em vista que se trata de um substitudo do cupom fiscal. Nesses casos é usual havem impressoras termicas não-fiscais, especificas para a impressão desses cupons.</h3>
        <h3>Dito isso, a impressão desses cupons (DANFCE) pode ser mais complexa pois algumas dessas impressoras para serem eficientes dependem do envio de comandos diretos no padrão ESC/POS ou similar (ex. Epson TM T20). Nesse momento ainda não temos na nossa API este recurso e portanto a DANFCE por enquato é gerada apenas em PDF.</h3>
    </div>
    <div class="clear"> </div>

  </div>
  <div id="tabs-5">
    <h2 align="right">Webservices</h2>
    <div id="esquerda">
    <span title="Arquivo de configuração dos Webservices para NFe.">WebServices NFe</span><br>
    <input type="text" id="pathXmlUrlFileNFe" name="pathXmlUrlFileNFe" placeholder="Path para nfe_ws3_mod55.xml" size="30" value="<?php echo $pathXmlUrlFileNFe;?>" required /><br>
    <span title="Arquivo de configuração dos Webservices para CTe.">WebServices CTe</span><br>
    <input type="text" id="pathXmlUrlFileCTe" name="pathXmlUrlFileCTe" placeholder="Path para cte_ws1.xml" size="30" value="<?php echo $pathXmlUrlFileCTe;?>" required /><br>
    <span title="Arquivo de configuração dos Webservices para MDFe.">WebServices MDFe</span><br>
    <input type="text" id="pathXmlUrlFileMDFe" name="pathXmlUrlFileMDFe" placeholder="Path para mdfe_ws1.xml" size="30" value="<?php echo $pathXmlUrlFileMDFe;?>" required /><br>
    <span title="Arquivo de configuração dos Webservices para CLe.">WebServices CLe</span><br>
    <input type="text" id="pathXmlUrlFileCLe" name="pathXmlUrlFileCLe" placeholder="Path para cle_ws1.xml" size="30" value="<?php echo $pathXmlUrlFileCLe;?>" /><br>
    <span title="Arquivo de configuração dos Webservices para NFSe.">WebServices NFSe</span><br>
    <input type="text" id="pathXmlUrlFileNFSe" name="pathXmlUrlFileNFSe" placeholder="Path para nfse_ws.xml" size="30" value="<?php echo $pathXmlUrlFileNFSe;?>" /><br>
    </div>
    <div id="direita">
      <h3>Os endereços dos webservices estão arquivados em arquivos xml na pasta config.</h3>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-6">
    <h2 align="right">Schemas</h2>
    <div id="esquerda">
    <span title="Indique o path completo para o arquivo de schemas para NFe. ">Schemas NFe</span><br>
    <input type="text" id="schemesNFe" name="schemesNFe" placeholder="pasta dos arquivos xsd"  value="<?php echo $schemesNFe;?>" /><br>
    <span title="Indique o path completo para o arquivo de schemas para CTe. ">Schemas CTe</span><br>
    <input type="text" id="schemesCTe" name="schemesCTe" placeholder="pasta dos arquivos xsd"  value="<?php echo $schemesCTe;?>" /><br>
    <span title="Indique o path completo para o arquivo de schemas para MDFe. ">Schemas MDFe</span><br>
    <input type="text" id="schemesMDFe" name="schemesMDFe" placeholder="pasta dos arquivos xsd"  value="<?php echo $schemesMDFe;?>" /><br>
    <span title="Indique o path completo para o arquivo de schemas para CLe. ">Schemas CLe</span><br>
    <input type="text" id="schemesCLe" name="schemesCLe" placeholder="pasta dos arquivos xsd"  value="<?php echo $schemesCLe;?>" /><br>
    <span title="Indique o path completo para os arquivos de schemas para NFSe. ">Schemas NFSe</span><br>
    <input type="text" id="schemesNFSe" name="schemesNFSe" placeholder="pasta dos arquivos xsd"  value="<?php echo $schemesNFSe;?>" /><br>
    </div>
    <div id="direita">
        <h3>Os esquemas são arquivos xml (terminação xsd) que contêm as regras de formatação para cada campo de um determinado xml.</h3>
    </div>
    <div class="clear"> </div>

  </div>
  <div id="tabs-7">
    <h2 align="right">Configurações de Email</h2>
    <div id="esquerda">
    <span title="Indique se é necessária a autenticação ">Autenticação</span><br>
    <?php echo $selMailAuth;?><br>
    <span title="Indique o endereço de email que será usado para envio dos documentos eletrônicos. Ex. nfe@seudominio.com.br">Endereço de Email</span><br>
    <input type="text" id="mailUser" name="mailUser" placeholder="Nome do usuário do email" size="50" value="<?php echo $mailUser;?>" /><br>
    <span title="Indique a senha de acesso da caixa postal do endereço de email">Senha de Email</span><br>
    <input type="password" id="mailPass" name="mailPass" placeholder="Senha do usuário" size="12" value="<?php echo $mailPass;?>" /><br>
    <span title="Indique o URL do Servidor SMTP Ex. smtp.seudominio.com.br">SMTP Server</span><br>
    <input type="text" id="mailSmtp" name="mailSmtp" placeholder="Servidor SMTP" size="50" value="<?php echo $mailSmtp;?>" /><br>
    <span title="Indique ">From</span><br>
    <input type="text" id="mailFrom" name="mailFrom" placeholder="from" size="50" value="<?php echo $mailFrom;?>" /><br>
    <span title="Indique ">Protocol</span><br>
    <?php echo $selMailProt;?><br>
    <span title="Indique ">Port</span><br>
    <input type="text" id="mailPort" name="mailPort" placeholder="Numero da porta" size="50" value="<?php echo $mailPort;?>" /><br>
  </div>
    <div id="direita">
        <h3>As configuração de email se referem a uma caixa postal criada pare enviar e receber os XML dos documetos fiscais da empresa como determina a legislação fiscal.</h3>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-8">
    <h2 align="right">Configurações de Proxy</h2>
    <div id="esquerda">
    <span title="Indique o numero IP do proxy server da sua rede interna, caso exista">Proxy IP</span><br>
    <input type="text" id="proxyIp" name="proxyIp" placeholder="Numero IP do seu proxy server" size="20" value="<?php echo $proxyIp;?>" /><br>
    <span title="Indique o numero da porta do proxy server da sua rede interna, caso exista">Proxy Port</span><br>
    <input type="text" id="proxyPort" name="proxyPort" placeholder="Numero da porta que seu proxy escuta" size="20" value="<?php echo $proxyPort;?>" /><br>
    <span title="Indique o nome do usuário autorizado a usar o proxy, caso exija autenticação">Proxy User</span><br>
    <input type="text" id="proxyUser" name="proxyUser" placeholder="Nome do usuário" size="20" value="<?php echo $proxyUser;?>" /><br>
    <span title="Indique a senha de acesso do usuário autorizado, caso exija autenticação">Proxy Pass</span><br>
    <input type="password" id="proxyPass" name="proxyPass" placeholder="Senha de acesso" size="20" value="<?php echo $proxyPass;?>" /><br>
    </div>
    <div id="direita">
        <h3>Aqui devem inclusas as configurações caso o sistema esteja instalado em um ambiente de rede cujo acesso a internet seja feito atraves de um servidor proxy. Caso não exista um servidor proxy na rede interna deixe esses campos em BRANCO.</h3>
        <h3>No campo "Proxy IP" deve ser indcado o numero IP do servidor Proxy ex. 192.168.0.200</h3>
        <h3>No campo "Proxy Port" (numero da Porta) indicar a porta que o servidor proxy escuta ex. 3128 (squid)</h3>
        <h3>No campo "Proxy User" deve ser indicado o nome do usuário autorizado a acessar a internet, se o proxy for autenticado, caso contrario deixe em BRANCO.</h3>
        <h3>No campo "Proxy Pass" indicar a senha de acesso do usuário, deixe em branco caso não seja exigida a senha.</h3>
    </div>
    <div class="clear"> </div>
  </div>
  <div id="tabs-9">
    <div id="esquerda">
    <p>Clique no botão abaixo para  salvar essas configurações e criar o arquivo config/config.json</p>
    <input type="text" id="configfolder" name="configfolder" placeholder="Path completo para a pasta"  value="<?php echo $configfolder;?>" /><br>
    <button value="save" type="submit">Salvar Configuração</button>
    </div>
    <div id="direita">
        <h3>O arquivo de configuração "config.json" será salvo na pasta indicada no path e também na pasta "default" do NFePHP, caso não sejas as mesmas.</h3>
        <h3>A ideia por traz do uso do formato json foi permitir de maneira mais facil usar a base de dados pra guardar os dados de configuração para o uso da API, sem ter recorrer a um arquivo mantido em pastas.</h3>
        <h3>Se bem executado isso pode facilitar em muito o uso no mesmo servidor para multiplos clientes, além de prover um grau um pouco maior de segurança.</h3>
        <p><i>NOTA: os dados passarão por validações antes da gravação. Caso não atendam as regras os dados serão rejeitados. Mantenha o arquivo config.json em local seguro fora do acesso de intrusos.</i></p>
    </div>
  <div class="clear"> </div>
  </div>
</div>
</form>
</div>
<div id="dialogcert" title="Resultado">....</div>
</body>
</html>