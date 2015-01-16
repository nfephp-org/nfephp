<?php

namespace Common\Modules;

class Modules
{
    public $list;

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
     * Checagem rápida de o modulo esta carregadp
     * true se carregado ou false se não
     * 
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
     * Obtem os parametros do modulo carregado
     * Pode ser uma simples configuração espacificada por $setting 
     * ou todos os valotes case nada seja passado no parâmetro
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
        if ($this->Modules[$moduleName][$setting]) {
            return $this->Modules[$moduleName][$setting];
        } elseif (empty($setting)) {
            return $this->Modules[$moduleName];
        }
        return 'Parâmetros não localizados';
    }

    
    /**
     * Lista todos os modulos php intalados sem seus 
     * parametros
     * @return array
     */
    public function listModules()
    {
        foreach (array_keys($this->Modules) as $moduleName) {
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
}
