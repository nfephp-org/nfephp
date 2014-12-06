<?php
namespace Common\Modules;

/**
 * Classe auxiliar para obter informações dos modulos instalados no PHP
 * @category   NFePHP
 * @package    NFePHP\Common\Modules
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class Modules
{
    protected $list;
    protected $cRed = '#FF0000';
    protected $cGreen = '#00CC00';

    /**
     * 
     */
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
        $this->list = $vModules;
    }

    /**
     * Checagem rápida se o modulo está carregadp
     * true se carregado ou false se não
     * @param string $moduleName
     * @return boolean
     */
    public function isLoaded($moduleName)
    {
        if ($this->list[$moduleName]) {
            return true;
        }
        return false;
    }

    /**
     * Obtêm os parâmetros do modulo carregado
     * Pode ser uma simples configuração especificada por $setting 
     * ou todos os valores caso nada seja passado no parâmetro
     * @param string $moduleName
     * @param string $setting
     * @return string
     */
    public function getModuleSetting($moduleName, $setting = '')
    {
        //verifica se o modulo está carregado antes de continuar
        if ($this->isLoaded($moduleName)==false) {
            return 'Modulo não carregado';
        }
        if ($this->list[$moduleName][$setting]) {
            return $this->list[$moduleName][$setting];
        } elseif (empty($setting)) {
            return $this->list[$moduleName];
        }
        return 'Parâmetros não localizados';
    }
    
    /**
     * Lista todos os modulos php instalados sem seus 
     * parametros
     * @return array
     */
    public function listModules()
    {
        foreach (array_keys($this->list) as $moduleName) {
            $onlyModules[] = $moduleName;
        }
        return $onlyModules;
    }
    
    /**
     * Função para padronização do numero de versões de 2.7.2 para 020702 
     * @param string $ver
     * @return string
     */
    public function convVer($ver)
    {
        $ver = preg_replace('/[^\d.]/', '', $ver);
        $aVer = explode('.', $ver);
        $nver = str_pad($aVer[0], 2, "0", STR_PAD_LEFT) .
        str_pad(isset($aVer[1]) ? $aVer[1] : '', 2, "0", STR_PAD_LEFT) .
        str_pad(isset($aVer[2]) ? $aVer[2] : '', 2, "0", STR_PAD_LEFT);
        return $nver;
    }
    
    /**
     * testPHP
     * @param string $limit
     * @return string
     */
    public function testPHP($limit = '5.4')
    {
        $phpversion = str_replace('-', '', substr(PHP_VERSION, 0, 6));
        $phpver = $this->convVer($phpversion);
        $phpcor = $this->cGreen;
        $status = 'OK';
        if ($phpver < $this->convVer($limit)) {
            $phpcor = $this->cRed;
            $status = 'NOK';
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>PHP vers&atilde;o $phpversion</td>"
            . "<td bgcolor=\"$phpcor\"><div align=\"center\">$status</div></td>"
            . "<td>A vers&atilde;o do PHP deve ser $limit ou maior</td></tr>";
    }
    
    /**
     * testCurl
     * @param string $limit
     * @return string
     */
    public function testCurl($limit = '7.10.2')
    {
        $modcurl = false;
        if ($modcurl = $this->isLoaded('curl')) {
            $modcurlVer = $this->getModuleSetting('curl', 'cURL Information');
            $modcurlSsl = $this->getModuleSetting('curl', 'SSL Version');
        }
        $cCurl = $this->cRed;
        $curlver = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($modcurl) {
            $curlver = $this->convVer($modcurlVer);
            if ($curlver > $this->convVer($limit)) {
                $curlver = ' vers&atilde;o ' . $modcurlVer;
                $cCurl = $this->cGreen;
                $status = 'OK';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>cURL $curlver [ $modcurlSsl ]</td>"
            . "<td bgcolor=\"$cCurl\"><div align=\"center\">$status</div></td>"
            . "<td>A vers&atilde;o do cURL deve ser $limit ou maior</td></tr>";
    }
    
    /**
     * testSSL
     * @param string $limit
     * @return string
     */
    public function testSSL($limit = '0.9.0')
    {
        $modssl = $this->isLoaded('openssl');
        if ($modssl) {
            $modsslVer = $this->getModuleSetting('openssl', 'OpenSSL Library Version');
            $modsslEnable = $this->getModuleSetting('openssl', 'OpenSSL support');
        }
        $cSSL = $this->cRed;
        $status = 'NOK';
        $sslver = ' N&atilde;o instalado !!!';
        if ($modssl) {
            if ($modsslEnable == 'enabled') {
                $cSSL = $this->cGreen;
                $sslver = $modsslVer;
                $status = 'OK';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>SSL $sslver</td>"
            . "<td bgcolor=\"$cSSL\"><div align=\"center\">$status</div></td>"
            . "<td>A vers&atilde;o do OpenSSL deve ser $limit ou maior</td></tr>";
    }
    
    /**
     * testDOM
     * @param string $limit
     * @return string
     */
    public function testDOM($limit = '2.7.0')
    {
        $moddom = $this->isLoaded('dom');
        if ($moddom) {
            $moddomEnable = $this->getModuleSetting('dom', 'DOM/XML');
            $moddomLibxml = $this->getModuleSetting('dom', 'libxml Version');
        }
        $cDOM = $this->cRed;
        $domver = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($moddom) {
            $domver = $this->convVer($moddomLibxml);
            if ($domver > $this->convVer($limit) && $moddomEnable=='enabled') {
                $domver = ' libxml vers&atilde;o ' . $moddomLibxml;
                $cDOM = $this->cGreen;
                $status = 'OK';
            } else {
                $domver = '';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>DOM $domver</td>"
            . "<td bgcolor=\"$cDOM\"><div align=\"center\">$status</div></td>"
            . "<td>O vers&atilde;o do libxml deve ser $limit ou maior</td></tr>";
    }
    
    /**
     * testSOAP
     * @return string
     */
    public function testSOAP()
    {
        $modsoap = $this->isLoaded('soap');
        if ($modsoap) {
            $modsoapEnable = $this->getModuleSetting('soap', 'Soap Client');
        }
        $cSOAP = $this->cRed;
        $soapver = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($modsoap) {
            if ($modsoapEnable=='enabled') {
                $cSOAP = $this->cGreen;
                $soapver = $modsoapEnable;
                $status = 'OK';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>SOAP</td><td bgcolor=\"$cSOAP\"><div align=\"center\">$status</div></td>"
            . "<td>$soapver</td></tr>";
    }

    public function testGD($limit = '1.1.1')
    {
        $modgd = $this->isLoaded('gd');
        if ($modgd) {
            $modgdVer = $this->getModuleSetting('gd', 'GD Version');
        }
        $cgd = $this->cRed;
        $gdver = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($modgd) {
            $gdver = $this->convVer($modgdVer);
            if ($gdver  > $this->convVer($limit)) {
                $cgd = $this->cGreen;
                $gdver = ' vers&atilde;o ' . $modgdVer;
                $status = 'OK';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>GD $gdver</td>"
            . "<td bgcolor=\"$cgd\"><div align=\"center\">$status</div></td>"
            . "<td>gd &eacute; necess&aacute;rio para impressão</td></tr>";
    }

    public function testZIP()
    {
        $modZip = $this->isLoaded('zip');
        if ($modZip) {
            $modzipEnable = $this->getModuleSetting('zip', 'Zip');
            $modzipVer = $this->getModuleSetting('zip', 'Zip version');
        }
        $cZIP = $this->cRed;
        $zipver = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($modZip) {
            if ($modzipEnable=='enabled') {
                $cZIP = $this->cGreen;
                $status = 'OK';
                $zipver = ' vers&atilde;o ' . $modzipVer;
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>ZIP $zipver</td>"
            . "<td bgcolor=\"$cZIP\"><div align=\"center\">$status</div></td>"
            . "<td>ZIP necess&aacute;rio para download da NFe</td></tr>";
    }
    
    public function writeTest($path = '', $message = '')
    {
        $wdCerts= 'O diret&oacute;rio N&Atilde;O EXISTE';
        $cdCerts = $this->cRed;
        if (is_dir($path)) {
            $filen = $path.DIRECTORY_SEPARATOR.'teste.txt';
            $wdCerts= ' Sem permiss&atilde;o !!';
            if (file_put_contents($filen, "teste\r\n")) {
                $cdCerts = $this->cGreen;
                $wdCerts= ' Permiss&atilde;o OK';
                unlink($filen);
            }
        }
        return "<tr bgcolor=\"#FFFFCC\">"
            . "<td>$message</td>"
            . "<td bgcolor=\"$cdCerts\"><div align=\"center\">$wdCerts</div></td>"
            . "<td>O diret&oacute;rio deve ter permiss&atilde;o de escrita</td></tr>";
    }
}
