<?php
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
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 *
 * @package   NFePHP
 * @name      install.php
 * @version   1.3.7
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *          CONTRIBUIDORES (por ordem alfabetica):
 *              Clauber Santos <cload_info at yahoo dot com dot br>
 *              Leandro C. Lopez <leandro.castoldi@gmail.com>
 * 
**/
if (!defined('PATH_NFEPHP')) {
    define('PATH_NFEPHP', dirname(__FILE__));
}
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once('bootstrap.php');

$pathConfig =  PATH_NFEPHP .'/config/config.json';
$configJson = Common\Files\FilesFolders::readFile($pathConfig);

$installConfig = json_decode($configJson);

$arquivoURLxml = $installConfig->pathXmlUrlFileNFe;
$arquivoURLxmlCTe = $installConfig->pathXmlUrlFileCTe;
$empresa = $installConfig->razaosocial;
$cnpj = $installConfig->cnpj;
$certName = $installConfig->certPfxName;
$keyPass = $installConfig->certPhrase;
$passPhrase = $installConfig->certPassword;
$baseurl = $installConfig->siteUrl;
$schemes = $installConfig->schemesNFe;
$schemesCTe = $installConfig->schemesCTe;
$danfePapel = $installConfig->aDocFormat->paper;
$danfePrinter = $installConfig->aDocFormat->printer;
$danfeLogo = $installConfig->aDocFormat->pathLogoFile;

$dactePapel = $installConfig->aDocFormat->paper;
$dactePrinter = $installConfig->aDocFormat->printer;
$dacteLogo = $installConfig->aDocFormat->pathLogoFile;

$mailFROM = $installConfig->aMailConf->mailFrom;
$mailHOST = $installConfig->aMailConf->mailImapHost;
$mailUSER = $installConfig->aMailConf->mailUser;
$mailPASS = $installConfig->aMailConf->mailPass;
$mailPORT = $installConfig->aMailConf->mailPort;
$mailFROMmail = $installConfig->aMailConf->mailFromMail;
$mailFROMname = $installConfig->aMailConf->mailFromName;

$mailREPLYTOmail = $installConfig->aMailConf->mailReplayToMail;
$mailREPLYTOname = $installConfig->aMailConf->mailReplayToName;
$mailIMAPhost = $installConfig->aMailConf->mailImapHost;
$mailIMAPport = $installConfig->aMailConf->mailImapPort;
$mailIMAPsecurity = $installConfig->aMailConf->mailImapSecurity;
$mailIMAPnocerts = $installConfig->aMailConf->mailImapNocerts;
$mailIMAPbox = $installConfig->aMailConf->mailImapBox;
$mailLayoutFile = '';
$proxyIP = $installConfig->aProxyConf->proxyIp;
$proxyPort = $installConfig->aProxyConf->proxyPort;
$proxyUSER = $installConfig->aProxyConf->proxyUser;
$proxyPASS = $installConfig->aProxyConf->proxyPass;


$installVer = '1.3.7';
//cores
$cRed = '#FF0000';
$cGreen = '#00CC00';

//versão do php
$phpversion = str_replace('-', '', substr(PHP_VERSION, 0, 6));

$phpver = convVer($phpversion);
if ($phpver > '050200') {
    $phpcor = $cGreen;
} else {
    $phpcor = $cRed;
}
//url
$guessedUrl = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
$guessed_url = rtrim(dirname($guessedUrl), 'install');
//path
$pathdir = dirname(__FILE__);

//teste dos modulos
$modules = new moduleCheck();

//Testa modulo cURL
$modcurl = false;
if ($modcurl = $modules->isLoaded('curl')) {
    $modcurl_ver = $modules->getModuleSetting('curl', 'cURL Information');
    $modcurl_ssl = $modules->getModuleSetting('curl', 'SSL Version');
}
$cCurl = $cRed;
$curlver = ' N&atilde;o instalado !!!';
if ($modcurl) {
    $curlver = convVer($modcurl_ver);
    if ($curlver > '071002') {
        $curlver = ' vers&atilde;o ' . $modcurl_ver;
        $cCurl = $cGreen;
    }
}
//Testa modulo OpenSSL
$modssl = $modules->isLoaded('openssl');
if ($modssl) {
    $modssl_ver = $modules->getModuleSetting('openssl', 'OpenSSL Library Version');
    $modssl_enable = $modules->getModuleSetting('openssl', 'OpenSSL support');
}
$cSSL = $cRed;
$sslver = ' N&atilde;o instalado !!!';
if ($modssl) {
    if ($modssl_enable=='enabled') {
        $cSSL = $cGreen;
        $sslver = $modssl_ver;
    }
}

//Testa modulo DOM
$moddom = $modules->isLoaded('dom');
if ($moddom) {
    $moddom_enable = $modules->getModuleSetting('dom', 'DOM/XML');
    $moddom_libxml = $modules->getModuleSetting('dom', 'libxml Version');
}
$cDOM = $cRed;
$domver = ' N&atilde;o instalado !!!';
if ($moddom) {
    $domver = convVer($moddom_libxml);
    if ($domver > '020600' && $moddom_enable=='enabled') {
        $domver = ' libxml vers&atilde;o ' . $moddom_libxml;
        $cDOM = $cGreen;
    } else {
        $domver = '';
    }
}

//Testa modulo gd
$modgd = $modules->isLoaded('gd');
if ($modgd) {
    $modgd_ver = $modules->getModuleSetting('gd', 'GD Version');
}
$cgd = $cRed;
$gdver = ' N&atilde;o instalado !!!';
if ($modgd) {
    $gdver = convVer($modgd_ver);
    if ($gdver  > '010101') {
        $cgd = $cGreen;
        $gdver = ' vers&atilde;o ' . $modgd_ver;
    }
}

//Testa modulo SOAP
$modsoap = $modules->isLoaded('soap');
if ($modsoap) {
    $modsoap_enable = $modules->getModuleSetting('soap', 'Soap Client');
}
$cSOAP = $cRed;
$soapver = ' N&atilde;o instalado !!!';
if ($modsoap) {
    if ($modsoap_enable=='enabled') {
        $cSOAP = $cGreen;
        $soapver = $modsoap_enable;
    }
}

//Testa modulo zip
$modzip = $modules->isLoaded('zip');
if ($modzip) {
    $modzip_enable = $modules->getModuleSetting('zip', 'Zip');
    $modzip_ver = $modules->getModuleSetting('zip', 'Zip version');
}
$cZIP = $cRed;
$zipver = ' N&atilde;o instalado !!!';
if ($modzip) {
    if ($modzip_enable=='enabled') {
        $cZIP = $cGreen;
        $zipver = ' vers&atilde;o ' . $modzip_ver;
    }
}

//Teste de escrita no diretorio dos certificados
$filen = $pathdir.DIRECTORY_SEPARATOR.'certs'.DIRECTORY_SEPARATOR.'teste.txt';
$cdCerts = $cRed;
$wdCerts= ' Sem permiss&atilde;o !!';
if (file_put_contents($filen, "teste\r\n")) {
    $cdCerts = $cGreen;
    $wdCerts= ' Permiss&atilde;o OK';
    unlink($filen);
}

//Teste de escrita no diretorio do config
$filen = $pathdir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'teste.txt';
$cdConf = $cRed;
$wdConf= ' Sem permiss&atilde;o !!';
if (file_put_contents($filen, "teste\r\n")) {
    $cdConf = $cGreen;
    $wdConf= ' Permiss&atilde;o OK';
    unlink($filen);
}

//Teste de escrita no arquivo config/numloteenvio.xml e config/config.php
$filen = $pathdir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'numloteenvio.xml';
if (file_exists($filen)) {
    //copia o conteudo
    if ($conteudo = file_get_contents($filen)) {
        if (file_put_contents($filen, "teste\r\n")) {
            file_put_contents($filen, $conteudo);
        } else {
            //falhou Sem permissão
            $cdConf = $cRed;
            $wdConf .= ' Sem permiss&atilde;o escrita config/numloteenvio.xml !!';
        }
    }
}

//Teste permissão de escrita em config
$filen = $pathdir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
if (file_exists($filen)) {
    //copia o conteudo
    if ($conteudo = file_get_contents($filen)) {
        if (file_put_contents($filen, "teste\r\n")) {
            file_put_contents($filen, $conteudo);
        } else {
            //falhou Sem permissão
            $cdConf = $cRed;
            $wdConf .= ' Sem permiss&atilde;o escrita config/config.php !!';
        }
    }
}


//Teste do diretorio de arquivo dos xml NFe
$arquivosDir = $installConfig->pathNFeFiles;
$cDir = $cRed;
$wdDir = 'FALHA';
if (is_dir($arquivosDir)) {
    if (mkdir($arquivosDir. DIRECTORY_SEPARATOR . "teste", 0777)) {
        rmdir($arquivosDir. DIRECTORY_SEPARATOR . "teste");
        $cDir = $cGreen;
        $wdDir= ' Permiss&atilde;o OK';
        $obsDir = $arquivosDir;
    } else {
        //sem permissao
        $obsDir= ' Sem permiss&atilde;o !!';
    }
} else {
    //dir não existe
    $obsDir= " Diretório $arquivosDir n&atilde;o existe !!";
}

//Teste do diretorio de arquivo dos xml CTe
$arquivosDirCTe = $installConfig->pathCTeFiles;
$ccteDir = $cRed;
$wctedDir = 'FALHA';
if (isset($arquivosDirCTe)) {
    if (is_dir($arquivosDirCTe)) {
        if (mkdir($arquivosDirCTe. DIRECTORY_SEPARATOR . "teste", 0777)) {
            rmdir($arquivosDirCTe. DIRECTORY_SEPARATOR . "teste");
            $ccteDir = $cGreen;
            $wctedDir= ' Permiss&atilde;o OK';
            $obscteDir = $arquivosDirCTe;
        } else {
            //sem permissao
            $obscteDir= ' Sem permiss&atilde;o !!';
        }
    } else {
        //dir não existe
        $obscteDir= " Diretório $arquivosDirCTe n&atilde;o existe !!";
    }
} else {
    $obscteDir= " Diretório CTe n&atilde;o especificado !!";
}

$nfe = new NFe\ToolsNFe($pathConfig);


$certificate = new Common\Certificate\Pkcs12($installConfig->pathCertsFiles, $installConfig->cnpj);
  
if ($certificate->expireTimestamp > 0) {
    $certVal = "Certificado v&aacute;lido";
} else {
    $certVal = "Certificado INV&Aacute;LIDO !!!";
}

//Tipo de ambiente
$ambiente = $installConfig->tpAmb;
if ($ambiente == 1) {
    $selAmb2 = '';
    $selAmb1 = 'selected';
} else {
    $selAmb1 = '';
    $selAmb2 = 'selected';
}

//Unidade da federação
$UF = $installConfig->siglaUF;
$aEstados = explode('.', 'AC.AL.AM.AP.BA.CE.DF.ES.GO.MA.MG.MS.MT.PA.PB.PE.PI.PR.RJ.RN.RO.RR.RS.SC.SE.SP.TO');
foreach ($aEstados as $ufAux) {
    if ($UF == $ufAux) {
        $duf = "\$selUF{$ufAux} = \"".'selected=\"selected\"'."\";";
    } else {
        $duf = "\$selUF{$ufAux} = '';";
    }
    eval($duf);
}

//Fontes básicas compiladas no FPDF
$danfeFonte = $installConfig->aDocFormat->font;
$aFontes = explode('.', 'Times.Helvetica.Corrier');
$i = 0;
foreach ($aFontes as $f) {
    if ($danfeFonte == $f) {
        $dfont = "\$selFont{$i} = \"".'selected=\"selected\"'."\";";
        $cfont = "\$selcteFont{$i} = \"".'selected=\"selected\"'."\";";
    } else {
        $dfont = "\$selFont{$i} = '';";
        $cfont = "\$selcteFont{$i} = '';";
    }
    eval($dfont);
    eval($cfont);
    $i++;
}

//Danfe formato
$danfeFormato = $installConfig->aDocFormat->format;
if ($danfeFormato=='P') {
    $selFormP = 'selected';
    $selFormL = '';
} else {
    $selFormL = 'selected';
    $selFormP = '';
}

//Danfe canhoto
$danfeCanhoto = '';
if ($danfeCanhoto) {
    $selCanh1 = 'selected';
    $selCanh0 = '';
} else {
    $selCanh0 = 'selected';
    $selCanh1 = '';
}

//Danfe posicao logo
$danfeLogoPos = $installConfig->aDocFormat->logoPosition;
if ($danfeLogoPos == 'L') {
    $seldposL = 'selected';
    $seldposC = '';
    $seldposR = '';
}
if ($danfeLogoPos == 'C') {
    $seldposC = 'selected';
    $seldposL = '';
    $seldposR = '';
}
if ($danfeLogoPos == 'R') {
    $seldposR = 'selected';
    $seldposC = '';
    $seldposL = '';
}

//Dacte formato
$dacteFormato = $installConfig->aDocFormat->logoPosition;
if ($dacteFormato=='P') {
    $selcteFormP = 'selected';
    $selcteFormL = '';
} else {
    $selcteFormL = 'selected';
    $selcteFormP = '';
}

//Dacte canhoto
$dacteCanhoto ='';
if ($dacteCanhoto) {
    $selcteCanh1 = 'selected';
    $selcteCanh0 = '';
} else {
    $selcteCanh0 = 'selected';
    $selcteCanh1 = '';
}

//Dacte posicao logo
$dacteLogoPos = $installConfig->aDocFormat->logoPosition;
if ($dacteLogoPos == 'L') {
    $selctedposL = 'selected';
    $selctedposC = '';
    $selctedposR = '';
}
if ($dacteLogoPos == 'C') {
    $selctedposC = 'selected';
    $selctedposL = '';
    $selctedposR = '';
}
if ($dacteLogoPos == 'R') {
    $selctedposR = 'selected';
    $selctedposC = '';
    $selctedposL = '';
}

//Autenticação obrigatória para email
$mailAuth = $installConfig->aMailConf->mailAuth;
if ($mailAuth == 1) {
    $selMAuthS = 'selected';
    $selMAuthN = '';
} else {
    $selMAuthN = 'selected';
    $selMAuthS = '';
}
$mailPROTOCOL = $installConfig->aMailConf->mailProtocol;
if ($mailPROTOCOL == 'ssl') {
    $selMprotS = 'selected';
    $selMprotT = '';
    $selMprotN = '';
}
if ($mailPROTOCOL == 'tls') {
    $selMprotT = 'selected';
    $selMprotS = '';
    $selMprotN = '';
}
if ($mailPROTOCOL == '') {
    $selMprotN = 'selected';
    $selMprotS = '';
    $selMprotT = '';
}

//Função para padronização do numero de versões de 2.7.2 para 020702
function convVer($ver)
{
    $ver = preg_replace('/[^\d.]/', '', $ver);
    $aVer = explode('.', $ver);
    $nver = str_pad($aVer[0], 2, "0", STR_PAD_LEFT) .
    str_pad(isset($aVer[1]) ? $aVer[1] : '', 2, "0", STR_PAD_LEFT) .
    str_pad(isset($aVer[2]) ? $aVer[2] : '', 2, "0", STR_PAD_LEFT);
    return $nver;
}

//classe de verificação dos modulos instalados no PHP
class moduleCheck
{
    public $Modules;

    //function parseModules() {
    public function __construct()
    {
        ob_start();
        phpinfo(INFO_MODULES);
        $data0 = ob_get_contents();
        ob_end_clean();
        $data1 = strip_tags($data0, '<h2><th><td>');
        $data2 = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $data1);
        $data = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $data2);
        // Split the data into an array
        $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        $vModules = array();
        $count = count($vTmp);
        for ($i = 1; $i < $count; $i += 2) {
            if (preg_match('/<h2>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
                $moduleName = trim($vMat[1]);
                $vTmp2 = explode("\n", $vTmp[$i+1]);
                foreach ($vTmp2 as $vOne) {
                    $vPat = '<info>([^<]+)<\/info>';
                    $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                    $vPat2 = "/$vPat\s*$vPat/";
                    if (preg_match($vPat3, $vOne, $vMat)) {
                        $vModules[$moduleName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                    } elseif (preg_match($vPat2, $vOne, $vMat)) {
                        $vModules[$moduleName][trim($vMat[1])] = trim($vMat[2]);
                    }
                }
            }
        }
        $this->Modules = $vModules;
    }

    /**
     * Quick check if module is loaded
     * Returns true if loaded, false if not
     * 
     * @param type $moduleName
     * @return boolean
     */
    public function isLoaded($moduleName)
    {
        if ($this->Modules[$moduleName]) {
            return true;
        }
        return false;
    }

    /**
     * Get a module setting
     * Can be a single setting by specifying $setting value or all settings by not specifying $setting value
     * @param type $moduleName
     * @param type $setting
     * @return string
     */
    public function getModuleSetting($moduleName, $setting = '')
    {
        // check if module is loaded before continuing
        if ($this->isLoaded($moduleName)==false) {
            return 'Modulo n&atilde;o carregado';
        }
        if ($this->Modules[$moduleName][$setting]) {
            return $this->Modules[$moduleName][$setting];
        } elseif (empty($setting)) {
            return $this->Modules[$moduleName];
        }
        // If setting specified and no value found return error
        return 'Setting not found';
    }

    // List all php modules installed with no settings
    public function listModules()
    {
        foreach (array_keys($this->Modules) as $moduleName) {
            // $moduleName is the key of $this->Modules, which is also module name
            $onlyModules[] = $moduleName;
        }
        return $onlyModules;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Install NFePHP - Configurador</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="nfephp.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
  <table width="70%" border="0" align="center">
    <tr>
      <td width="41%"><div align="center">
              <h2>Configurador da Instala&ccedil;&atilde;o NFePHP 2.0</h2><p><i><?php echo 'vers&atilde;o do configurador '.$installVer;?></i></p>
        </div></td>
      <td width="14%">&nbsp;</td>
      <td width="45%"><div align="center"><img src="images/logo.jpg" width="163" height="50"></div></td>
    </tr>
    <tr>
      <td colspan="3"><p>Esta rotina ir&aacute; verificar as condi&ccedil;&otilde;es
          da sua instala&ccedil;&atilde;o do PHP, se todas as necessidades para
          o funcionamento da API foram satisfeitas. Tamb&eacute;m fornece os meios
          para corrigir o arquivo de configura&ccedil;&atilde;o(config.php).</p>
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#CC9933">
      <td> <div align="center"><strong>Modulos</strong></div></td>
      <td> <div align="center"><strong>Status</strong></div></td>
      <td> <div align="center"><strong>Comentario</strong></div></td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>PHP vers&atilde;o <?php echo $phpversion;?></td>
      <td bgcolor="<?php echo $phpcor;?>"><div align="center">ok</div></td>
      <td>A vers&atilde;o do PHP deve ser 5.2 ou maior</td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>cURL <?php echo $curlver;?>  [ <?php echo $modcurl_ssl;?> ]</td>
      <td bgcolor="<?php echo $cCurl;?>"><div align="center">ok</div></td>
      <td>A vers&atilde;o do cURL deve ser 7.10.2 ou maior</td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>SSL <?php echo $sslver;?></td>
      <td bgcolor="<?php echo $cSSL;?>"><div align="center">ok</div></td>
      <td>A vers&atilde;o do OpenSSL deve ser 0.9.0 ou maior</td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>DOM <?php echo $domver;?></td>
      <td bgcolor="<?php echo $cDOM;?>"><div align="center">ok</div></td>
      <td>O vers&atilde;o do libxml deve ser 2.7.0 ou maior</td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>SOAP </td>
      <td bgcolor="<?php echo $cSOAP;?>"><div align="center">ok</div></td>
      <td><?php echo $soapver;?></td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>GD <?php echo $gdver;?></td>
      <td bgcolor="<?php echo $cgd;?>"><div align="center">ok</div></td>
      <td>gd &eacute; necess&aacute;rio para DANFE</td>
    </tr>
    <tr bgcolor="#FFFF99">
      <td>ZIP <?php echo $zipver;?></td>
      <td bgcolor="<?php echo $cZIP;?>"><div align="center">ok</div></td>
      <td>ZIP necess&aacute;rio para download da NFe</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#666666">
      <td colspan="3"><font color="#FFFFFF"><strong>Permiss&atilde;o de escrita</strong></font></td>
    </tr>
    <tr>
      <td colspan="3"><table width="90%" border="0" align="center">
          <tr bgcolor="#FFFFCC">
            <td>Diretorio certs</td>
            <td bgcolor="<?php echo $cdCerts;?>"><div align="center"><?php echo $wdCerts;?></div></td>
            <td>O diret&oacute;rio deve ter permiss&atilde;o de escrita</td>
          </tr>
          <tr bgcolor="#FFFFCC">
            <td>Diretorio NFe</td>
            <td bgcolor="<?php echo $cDir;?>"><div align="center"><?php echo $wdDir;?></div></td>
            <td bgcolor="#FFFFCC"><?php echo $obsDir;?></td>
          </tr>

          <tr bgcolor="#FFFFCC">
            <td>Diretorio CTe</td>
            <td bgcolor="<?php echo $ccteDir;?>"><div align="center"><?php echo $wctedDir;?></div></td>
            <td bgcolor="#FFFFCC"><?php echo $obscteDir;?></td>
          </tr>

          <tr bgcolor="#FFFFCC">
            <td>Diretorio config</td>
            <td bgcolor="<?php echo $cdConf;?>"><div align="center"><?php echo $wdConf;?></div></td>
            <td bgcolor="#FFFFCC">O diret&oacute;rio config e seu conte&uacute;do devem ter permiss&atilde;o de escrita</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><form action="grava_config.php" method="post" name="formSave" id="formSave">
          <table width="90%" border="0" align="center">
            <tr bgcolor="#000000">
              <td width="40%"> <div align="center"><font color="#FFFFFF"><strong>Configura&ccedil;&otilde;es</strong></font></div></td>
              <td width="32%"> <div align="center"><font color="#FFFFFF"><strong>SetUp</strong></font></div></td>
              <td width="28%"> <div align="center"><font color="#FFFFFF"><strong>Coment&aacute;rios</strong></font></div></td>
            </tr>
            <tr bordercolor="#666666">
              <td><div align="right">Tipo de ambiente</div></td>
              <td><select name="ambiente" size="1" id="ambiente">
                  <option value="1" <?php echo $selAmb1;?>>Produ&ccedil;&atilde;o</option>
                  <option value="2" <?php echo $selAmb2;?>>Homologa&ccedil;&atilde;o</option>
                </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">URL dos WebServices NFe</div></td>
              <td><input name="urlws" type="text" id="" value="<?php echo $arquivoURLxml;?>" size="30" maxlength="200"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">URL dos WebServices CTe</div></td>
              <td><input name="urlwscte" type="text" id="" value="<?php echo $arquivoURLxmlCTe;?>" size="30" maxlength="200"></td>
              <td>&nbsp;</td>
            </tr>

            <tr>
              <td height="26"><div align="right">Raz&atilde;o Social</div></td>
              <td><input name="razao" type="text" id="razao" value="<?php echo $empresa;?>" size="30" maxlength="200"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Unidade da Federa&ccedil;&atilde;o do Emitente</div></td>
              <td><select name="siglauf" size="1">
                  <option value="AC" <?php echo $selUFAC;?>>AC</option>
                  <option value="AL" <?php echo $selUFAL;?>>AL</option>
                  <option value="AM" <?php echo $selUFAM;?>>AM</option>
                  <option value="AP" <?php echo $selUFAP;?>>AP</option>
                  <option value="BA" <?php echo $selUFBA;?>>BA</option>
                  <option value="CE" <?php echo $selUFCE;?>>CE</option>
                  <option value="DF" <?php echo $selUFDF;?>>DF</option>
                  <option value="ES" <?php echo $selUFES;?>>ES</option>
                  <option value="GO" <?php echo $selUFGO;?>>GO</option>
                  <option value="MA" <?php echo $selUFMA;?>>MA</option>
                  <option value="MG" <?php echo $selUFMG;?>>MG</option>
                  <option value="MS" <?php echo $selUFMS;?>>MS</option>
                  <option value="MT" <?php echo $selUFMT;?>>MT</option>
                  <option value="PA" <?php echo $selUFPA;?>>PA</option>
                  <option value="PB" <?php echo $selUFPB;?>>PB</option>
                  <option value="PE" <?php echo $selUFPE;?>>PE</option>
                  <option value="PI" <?php echo $selUFPI;?>>PI</option>
                  <option value="PR" <?php echo $selUFPR;?>>PR</option>
                  <option value="RJ" <?php echo $selUFRJ;?>>RJ</option>
                  <option value="RN" <?php echo $selUFRN;?>>RN</option>
                  <option value="RO" <?php echo $selUFRO;?>>RO</option>
                  <option value="RR" <?php echo $selUFRR;?>>RR</option>
                  <option value="RS" <?php echo $selUFRS;?>>RS</option>
                  <option value="SC" <?php echo $selUFSC;?>>SC</option>
                  <option value="SE" <?php echo $selUFSE;?>>SE</option>
                  <option value="SP" <?php echo $selUFSP;?>>SP</option>
                  <option value="TO" <?php echo $selUFTO;?>>TO</option>
                </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Numero do CNPJ do emitente</div></td>
              <td><input name="numcnpj" type="text" id="numcnpj" value="<?php echo $cnpj;?>" size="14" maxlength="14"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Nome do arquivo pfx (Certificado)</div></td>
              <td><input name="pfx" type="text" id="pfx" value="<?php echo $certName;?>" size="30" maxlength="200"></td>
              <td><i><?php echo $certVal;?></i></td>
            </tr>
            <tr>
              <td><div align="right">Senha da chave privada</div></td>
              <td><input name="keysenha" type="password" id="keysenha" value="<?php echo $keyPass;?>" size="20" maxlength="30"></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><div align="right">Senha de Decripta&ccedil;ao</div></td>
              <td><input name="passe" type="password" id="passe" value="<?php echo $passPhrase;?>" size="20" maxlength="30"></td>
              <td><i>Normalmente não usado</i></td>
            </tr>
            <tr>
              <td><div align="right">URL base da API</div></td>
              <td><input name="urlapi" type="text" id="urlapi" value="<?php echo $baseurl;?>" size="30" maxlength="200"></td>
              <td><i><?php echo $guessed_url;?></i></td>
            </tr>
            <tr>
              <td><div align="right">Path completo</div></td>
              <td><input name="caminho" type="text" id="caminho" value="<?php echo $pathdir;?>" size="30" maxlength="200"></td>
              <td><i><?php echo PATH_NFEPHP;?></i></td>
            </tr>
            <tr>
              <td><div align="right">Diret&oacute;rio de arquivo das NFe</div></td>
              <td><input name="dirnfe" type="text" id="dirnfe" value="<?php echo $arquivosDir;?>" size="30" maxlength="200"></td>
              <td><i>Indique o path completo para a pasta das NFe</i></td>
            </tr>
            <tr>
              <td><div align="right">Diret&oacute;rio de arquivo das CTe</div></td>
              <td><input name="dircte" type="text" id="dircte" value="<?php echo $arquivosDirCTe;?>" size="30" maxlength="200"></td>
              <td><i>Indique o path completo para a pasta das CTe</i></td>
            </tr>
            
            <tr>
              <td colspan="3" bgcolor="#999999"><strong>Schemas</strong></td>
            </tr>
            <tr>
              <td><div align="right">Vers&atilde;o 2.00 NFe</div></td>
              <td><input name="schema" type="text" id="schema" value="<?php echo $schemes;?>" size="30" maxlength="200"></td>
              <td><i>Indique a versão do schema (veja pasta schemes)</i></td>
            </tr>
            <tr>
              <td><div align="right">Vers&atilde;o 1.00 CTe</div></td>
              <td><input name="schemacte" type="text" id="schemacte" value="<?php echo $schemesCTe;?>" size="30" maxlength="200"></td>
              <td><i>Indique a versão do schema CTe (veja pasta schemes)</i></td>
            </tr>
            <tr bgcolor="#999999">
              <td colspan="3"><strong>Configura&ccedil;&atilde;o do DANFE</strong></td>
            </tr>
            <tr>
              <td><div align="right">Formato</div></td>
              <td>
                <select name="formato" id="formato">
                    <option value="P" <?php echo $selFormP;?>>Portraite</option>
                    <option value="L" <?php echo $selFormL;?>>Landscape</option>
                </select>
              </td>
              <td><i>Formato padrão do DANFE</i></td>
            </tr>
            <tr>
              <td><div align="right">Papel</div></td>
              <td><input name="papel" type="text" id="papel" value="<?php echo $danfePapel;?>" size="2" maxlength="2"></td>
              <td><i>Sempre deve ser A4</i></td>
            </tr>
            <tr>
              <td><div align="right">Canhoto</div></td>
              <td>
                  <select name="canhoto" size="1" id="canhoto">
                    <option value="1" <?php echo $selCanh1;?>>TRUE</option>
                    <option value="0" <?php echo $selCanh0;?>>FALSE</option>
                  </select>
              </td>
              <td><i>O padrão é sempre com canhoto</i></td>
            </tr>
            <tr>
                <td><div align="right">Posição do Logo</div></td>
                <td>
                    <select name="logopos" size="1" id="logopos">
                    <option value="L" <?php echo $seldposL;?>>Left</option>
                    <option value="C" <?php echo $seldposC;?>>Center</option>
                    <option value="R" <?php echo $seldposR;?>>Rigth</option>
                  </select>
                </td>
                <td><i>Posição da Logomarca no DANFE</i></td>
            </tr>
            <tr>
                <td><div align="right">Fonte</div></td>
                <td>
                    <select name="fonte" size="1" id="fonte">
                        <option value="Times"<?php echo $selFont0;?>>Times</option>
                        <option value="Helvetica"<?php echo $selFont1;?>>Helvetica</option>
                        <option value="Corrier"<?php echo $selFont2;?>>Corrier</option>
                    </select>
                </td>
                <td><i>Fonte padrão TIMES</i></td>
            </tr>
            <tr>
              <td><div align="right">Impressora</div></td>
              <td><input name="printer" type="text" id="printer" value="<?php echo $danfePrinter;?>" size="20" maxlength="40"></td>
              <td><i>Nome da impressora padrão</i></td>
            </tr>
            <tr>
              <td><div align="right">Logo</div></td>
              <td><input name="logo" type="text" id="logo" value="<?php echo $danfeLogo;?>" size="30" maxlength="200"></td>
              <td><i>Nome do arquivo da logomarca</i></td>
            </tr>

            <tr bgcolor="#999999">
              <td colspan="3"><strong>Configura&ccedil;&atilde;o do DACTE</strong></td>
            </tr>
            <tr>
              <td><div align="right">Formato</div></td>
              <td>
                <select name="formatocte" id="formatocte">
                    <option value="P" <?php echo $selcteFormP;?>>Portraite</option>
                    <option value="L" <?php echo $selcteFormL;?>>Landscape</option>
                </select>
              </td>
              <td><i>Formato padrão do DACTE</i></td>
            </tr>
            <tr>
              <td><div align="right">Papel</div></td>
              <td><input name="papelcte" type="text" id="papelcte" value="<?php echo $dactePapel;?>" size="2" maxlength="2"></td>
              <td><i>Sempre deve ser A4</i></td>
            </tr>
            <tr>
              <td><div align="right">Canhoto</div></td>
              <td>
                  <select name="canhotocte" size="1" id="canhotocte">
                    <option value="1" <?php echo $selcteCanh1;?>>TRUE</option>
                    <option value="0" <?php echo $selcteCanh0;?>>FALSE</option>
                  </select>
              </td>
              <td><i>O padrão é sempre com canhoto</i></td>
            </tr>
            <tr>
                <td><div align="right">Posição do Logo</div></td>
                <td>
                    <select name="logoposcte" size="1" id="logoposcte">
                    <option value="L" <?php echo $selctedposL;?>>Left</option>
                    <option value="C" <?php echo $selctedposC;?>>Center</option>
                    <option value="R" <?php echo $selctedposR;?>>Rigth</option>
                  </select>
                </td>
                <td><i>Posição da Logomarca no DACTE</i></td>
            </tr>
            <tr>
                <td><div align="right">Fonte</div></td>
                <td>
                    <select name="fontecte" size="1" id="fontecte">
                        <option value="Times"<?php echo $selcteFont0;?>>Times</option>
                        <option value="Helvetica"<?php echo $selcteFont1;?>>Helvetica</option>
                        <option value="Corrier"<?php echo $selcteFont2;?>>Corrier</option>
                    </select>
                </td>
                <td><i>Fonte padrão TIMES</i></td>
            </tr>
            <tr>
              <td><div align="right">Impressora</div></td>
              <td><input name="printercte" type="text" id="printercte" value="<?php echo $dactePrinter;?>" size="20" maxlength="40"></td>
              <td><i>Nome da impressora padrão</i></td>
            </tr>
            <tr>
              <td><div align="right">Logo</div></td>
              <td><input name="logocte" type="text" id="logocte" value="<?php echo $dacteLogo;?>" size="30" maxlength="200"></td>
              <td><i>Nome do arquivo da logomarca</i></td>
            </tr>

            <tr bgcolor="#999999">
              <td colspan="3"><strong>Configura&ccedil;&atilde;o do email</strong></td>
            </tr>
            <tr>
              <td><div align="right">Emitente</div></td>
              <td><input name="emitente" type="text" id="emitente" value="<?php echo $mailFROM;?>" size="30" maxlength="100"></td>
              <td><i>Indique o email do Remetente</i></td>
            </tr>
            <tr>
              <td><div align="right">URL SMTP</div></td>
              <td><input name="smtp" type="text" id="smtp" value="<?php echo $mailHOST;?>" size="30" maxlength="100"></td>
              <td><i>Indique o endereço do SMTP</i></td>
            </tr>
            <tr>
              <td><div align="right">Autenticação Obrigat&oacute;ria</div></td>
              <td>
                  <select name="mailAuth" size="1" id="mailAuth">
                    <option value="1" <?php echo $selMAuthS;?>>SIM</option>
                    <option value="0" <?php echo $selMAuthN;?>>N&atilde;o</option>
              </td>
              <td><i>Indique não se a autenticação não for exigida</i></td>
            </tr>
            <tr>
              <td><div align="right">Username</div></td>
              <td><input name="user" type="text" id="user" value="<?php echo $mailUSER;?>" size="30" maxlength="100"></td>
              <td><i>Indique o nome do usuário para autenticação</i></td>
            </tr>
            <tr>
              <td><div align="right">Password</div></td>
              <td><input name="password" type="password" id="password" value="<?php echo $mailPASS;?>" size="20" maxlength="30"></td>
              <td><i>Indique o password para autenticação</i></td>
            </tr>
            <tr>
              <td><div align="right">Protocolo</div></td>
              <td>
                  <select name="protocol" size="1" id="protocol">
                    <option value="" <?php echo $selMprotN;?>>None</option>
                    <option value="ssl" <?php echo $selMprotS;?>>SSL</option>
                    <option value="tls" <?php echo $selMprotT;?>>TLS</option>
              <td><i>nenhum, ssl ou tls (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">Porta SMTP</div></td>
              <td><input name="porta" type="text" id="porta" value="<?php echo $mailPORT;?>" size="20" maxlength="30"></td>
              <td><i>Porta SMPT ex.25 (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailFromMail</div></td>
              <td><input name="mailfrommail" type="text" id="mailfrommail" value="<?php echo $mailFROMmail;?>" size="20" maxlength="30"></td>
              <td><i>Muda o email do remetente (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailFromName</div></td>
              <td><input name="mailfromname" type="text" id="mailfromname" value="<?php echo $mailFROMname;?>" size="20" maxlength="30"></td>
              <td><i>Nome do Remetente (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailReplyToMail</div></td>
              <td><input name="mailreplytomail" type="text" id="mailreplytomail" value="<?php echo $mailREPLYTOmail;?>" size="20" maxlength="30"></td>
              <td><i>E-mail para resposta (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailReplyToName</div></td>
              <td><input name="mailreplytoname" type="text" id="mailreplytoname" value="<?php echo $mailREPLYTOname;?>" size="20" maxlength="30"></td>
              <td><i>Nome do destinatário da resposta (PHPMailer)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailIMAP Host</div></td>
              <td><input name="mailimaphost" type="text" id="mailimaphost" value="<?php echo $mailIMAPhost;?>" size="20" maxlength="130"></td>
              <td><i>URL do Servidor IMAP (ex. mail.host.com)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailIMAP Port</div></td>
              <td><input name="mailimapport" type="text" id="mailimapport" value="<?php echo $mailIMAPport;?>" size="5" maxlength="5"></td>
              <td><i>Porta do Servidor IMAP (ex. 143)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailIMAP Security</div></td>
              <td><input name="mailimapsecurity" type="text" id="mailimapsecurity" value="<?php echo $mailIMAPsecurity;?>" size="5" maxlength="5"></td>
              <td><i>Esquema de segurança do Servidor IMAP (ex. tls)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailIMAP NoCerts</div></td>
              <td><input name="mailimapnocerts" type="text" id="mailimapnocerts" value="<?php echo $mailIMAPnocerts;?>" size="20" maxlength="100"></td>
              <td><i>Desabilitar a verificação do Certificado IMAP (ex. novalidate-certs)</i></td>
            </tr>
            <tr>
              <td><div align="right">MailIMAP Box</div></td>
              <td><input name="mailimapbox" type="text" id="mailimapbox" value="<?php echo $mailIMAPbox;?>" size="20" maxlength="100"></td>
              <td><i>Caixa de Entrada IMAP (ex. INBOX)</i></td>
            </tr>
            <tr>
              <td><div align="right">LayOut email File</div></td>
              <td><input name="maillayout" type="text" id="maillayout" value="<?php echo $mailLayoutFile;?>" size="20" maxlength="100"></td>
              <td><i>Nome arquivo html (UTF8) do template para o corpo do email (na pasta config)</i></td>
            </tr>
            <tr bgcolor="#999999">
              <td colspan="3"><strong>Configura&ccedil;&atilde;o de Proxy</strong></td>
            </tr>
            <tr>
              <td><div align="right">Proxy IP</div></td>
              <td><input name="proxyip" type="text" id="proxyip" value="<?php echo $proxyIP;?>" size="15" maxlength="15"></td>
              <td><i>Indique o IP do servidor Proxy</i></td>
            </tr>
            <tr>
              <td><div align="right">Proxy Port</div></td>
              <td><input name="proxyport" type="text" id="proxyport" value="<?php echo $proxyPort;?>" size="5" maxlength="5"></td>
              <td><i>Indique a porta do Proxy</i></td>
            </tr>
            <tr>
              <td><div align="right">Proxy Username</div></td>
              <td><input name="proxyuser" type="text" id="proxyuser" value="<?php echo $proxyUSER;?>" size="30" maxlength="100"></td>
              <td><i>Se o Proxy exigir autenticação, indique o nome do usuário<i></td>
            </tr>
            <tr>
              <td><div align="right">Proxy Password</div></td>
              <td><input name="proxypass" type="text" id="proxypass" value="<?php echo $proxyPASS;?>" size="20" maxlength="30"></td>
              <td><i>Se o Proxy exigir autenticação, indique a senha</i></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="Gravar" type="submit" id="Gravar" value="Gravar"></td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <div align="center"></div>
        </form></td>
    </tr>
  </table>
</div>
</body>
</html>
