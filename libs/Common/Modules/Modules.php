<?php

namespace NFePHP\Common\Modules;

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
     * Rotina de teste dos molulos instalados 
     * se a versão é suficiente e se estão habilitados
     * @param string $name
     * @param string $alias
     * @param string $param1
     * @param string $param2
     * @param string $limit
     * @param string $coment
     * @return string
     */
    public function testModule(
        $name,
        $alias = '',
        $param1 = '',
        $param2 = '',
        $limit = '',
        $coment = ''
    ) {
        $cor = $this->cRed;
        $msg = ' N&atilde;o instalado !!!';
        $status = 'NOK';
        if ($this->isLoaded($name)) {
            $msg = '';
            $num = '';
            $enabled = 'enabled';
            if (!empty($param1)) {
                $version = $this->getModuleSetting($name, $param1);
                $num = (int) $this->convVer($version);
                $msg = ' vers&atilde;o ' . $version;
            }
            if (!empty($param2)) {
                $enabled = $this->getModuleSetting($name, $param2);
            }
            if ($num >= (int) $this->convVer($limit) && $enabled == 'enabled') {
                $cor = $this->cGreen;
                $status = 'OK';
            }
        }
        return "<tr bgcolor=\"#FFFF99\">"
            . "<td>$alias $msg</td>"
            . "<td bgcolor=\"$cor\"><div align=\"center\">$status</div></td>"
            . "<td>$coment</td></tr>";
    }
}
