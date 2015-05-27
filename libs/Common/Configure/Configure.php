<?php

namespace NFePHP\Common\Configure;

use NFePHP\Common\Modules\Modules;
use NFePHP\Common\Files\FilesFolders;

class Configure
{
    const CRED = '#FF0000';
    const CGREEN = '#00CC00';
    protected $cRed = '#FF0000';
    protected $cGreen = '#00CC00';
    
    //$name, $param1 = '', $param2 = '', $limit = '', $coment
    public $aRequirements = array(
        'PHP' => array('php','','','5.4.0','Versão do PHP'),
        'cURL'=> array('curl','cURL Information','','7.22.2','mínimo cURL 7.22.2'),
        'OpenSSL' => array('openssl','OpenSSL Library Version','','','mínimo OpenSSL 1.0'),
        'DOM' => array('dom','libxml Version','','2.0.6','mínimo DOM 2.0.6'),
        'GD' => array('gd','GD Version','','1.1.1','mínimo GD 1.1.1'),
        'SOAP' => array('soap','Soap Client','','','-----'),
        'ZIP' => array('zip','Zip version', '', '', '-----')
    );
    
    public function chkModules()
    {
        //instanciar a classe de modulos
        $modules = new Modules;
        //versão do php
        $phpversion = str_replace('-', '', substr(PHP_VERSION, 0, 6));
        $phpver = $modules->convVer($phpversion);
        $phpvermin = $modules->convVer($this->aRequirements['PHP'][3]);
        $status = 'NOK';
        $comment = "v. $phpversion Inadequada !!!";
        if ($phpver >= $phpvermin) {
            $comment = "mínimo PHP ". $this->aRequirements['PHP'][3];
            $status = 'OK';
        }
        $htmmod = "<table><tr>"
            . "<td>Versão do PHP $phpversion</td>"
            . "<td><div align=\"center\">$status</div></td>"
            . "<td>$comment</td></tr>";
        
        foreach ($this->aRequirements as $key => $param) {
            if ($key != 'PHP') {
                $htmmod .= $modules->testModule(
                    $param[0],
                    $key,
                    $param[1],
                    $param[2],
                    $param[3],
                    $param[4]
                );
            }
        }
        return $htmmod.'</table>';
    }
}
