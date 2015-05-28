<?php

namespace NFePHP\Common\Configure;

use NFePHP\Common\Modules\Modules;
use NFePHP\Common\Files\FilesFolders;
use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\Common\Exception\InvalidArgumentException;
use NFePHP\Common\Exception\RuntimeException;

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
    
    public static function checkCerts($cnpj = '', $pathCertsFiles = '', $certPfxName = '', $certPassword = '')
    {
        $flag = true;
        $msg = '';
        if (strlen($cnpj) != 14) {
            $flag = $flag && false;
            $msg .= "CNPJ incorreto! $cnpj \n";
        }
        if (! is_dir($pathCertsFiles)) {
            $flag = $flag && false;
            $msg .= "Diretório não localizado! $pathCertsFiles \n";
        }
        if (substr($pathCertsFiles, -1) !== DIRECTORY_SEPARATOR) {
            $pathCertsFiles .= DIRECTORY_SEPARATOR;
        }
        try {
            $cert = new Pkcs12($pathCertsFiles, $cnpj);
            $flag = $cert->loadPfxFile($pathCertsFiles.$certPfxName, $certPassword);
        } catch (InvalidArgumentException $exc) {
            $flag = false;
            $msg = $exc->getMessage();
        } catch (RuntimeException $exc) {
            $flag = false;
            $msg = $exc->getMessage();
        }
        if ($msg == '') {
            $msg = 'Certificado Validado, arquivos PEM criados na pasta.';
        }
        return array('cert' => array('status' => $flag, 'msg' => $msg));
    }
    
    public static function checkFolders(
        $pathnfe = '',
        $pathcte = '',
        $pathmdfe = '',
        $pathcle = '',
        $pathnfse = '',
        $pathcerts = ''
    ) {
        $aResp = array(
            'NFe' => array('status'=>true,'msg'=>''),
            'CTe' => array('status'=>true,'msg'=>''),
            'MDFe' => array('status'=>true,'msg'=>''),
            'CLe' => array('status'=>true,'msg'=>''),
            'NFSe' => array('status'=>true,'msg'=>''),
            'Certs' => array('status'=>true,'msg'=>'')
        );
        //testa e constroi a estrutura da pasta
        if ($pathnfe != '') {
            try {
                FilesFolders::createFolders($pathnfe);
            } catch (RuntimeException $e) {
                $aResp['NFe'] = array('status'=>false, 'msg'=>$e->getMessage());
            }
        }
        //testa e constroi a estrutura da pasta
        if ($pathcte != '') {
            try {
                FilesFolders::createFolders($pathcte);
            } catch (RuntimeException $e) {
                $aResp['CTe'] = array('status'=>false, 'msg'=>$e->getMessage());
            }
        }
        //testa e constroi a estrutura da pasta
        if ($pathmdfe != '') {
            try {
                FilesFolders::createFolders($pathmdfe);
            } catch (RuntimeException $e) {
                $aResp['MDFe'] = array('status'=>false, 'msg'=>$e->getMessage());
            }
        }
        //testa e constroi a estrutura da pasta
        if ($pathcle != '') {
            try {
                FilesFolders::createFolders($pathcle);
            } catch (RuntimeException $e) {
                $aResp['CLe'] = array('status'=>false, 'msg'=>$e->getMessage());
            }
        }
        //testa e constroi a estrutura da pasta
        if ($pathnfse != '') {
            try {
                FilesFolders::createFolders($pathnfse);
            } catch (RuntimeException $e) {
                $aResp['NFSe'] = array('status'=>false, 'msg'=>$e->getMessage());
            }
        }
        //testa diretorio certs
        if ($pathcerts != '') {
            if (! is_writable($pathcerts)) {
                $aResp['Certs'] = array('status'=>false, 'msg'=>'Diretório sem permissões de escrita');
            }
        }
        return $aResp;
    }
}
