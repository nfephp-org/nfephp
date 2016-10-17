<?php

namespace NFePHP\Common\Configure;

use NFePHP\Common\Modules\Modules;
use NFePHP\Common\Files\FilesFolders;
use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\Common\Exception\InvalidArgumentException;
use NFePHP\Common\Exception\RuntimeException;

class Configure
{
    /**
     * $cRed
     *
     * @var hex
     */
    protected static $cRed = '#FF0000';
    
    /**
     *$cGreen
     *
     * @var hex
     */
    protected static $cGreen = '#00CC00';
    
    /**
     * $aRequirements
     *
     * @var array
     */
    public static $aRequirements = array(
        'PHP' => array('php','','','5.4.0','Versão do PHP'),
        'cURL'=> array('curl','cURL Information','','7.22.2','mínimo cURL 7.22.2'),
        'OpenSSL' => array('openssl','OpenSSL Library Version','','','mínimo OpenSSL 1.0'),
        'DOM' => array('dom','libxml Version','','2.0.6','mínimo DOM 2.0.6'),
        'GD' => array('gd','GD Support','','','-----'),
        'SOAP' => array('soap','Soap Client','','','-----'),
        'ZIP' => array('zip','Zip version', '', '', '-----')
    );
    
    /**
     * chkModules
     *
     * @return string
     */
    public static function chkModules()
    {
        //instanciar a classe de modulos
        $modules = new Modules();
        //versão do php
        $phpversion = str_replace('-', '', substr(PHP_VERSION, 0, 6));
        $phpver = $modules->convVer($phpversion);
        $phpvermin = $modules->convVer(self::$aRequirements['PHP'][3]);
        $status = 'NOK';
        $bcor = "bgcolor=\"".self::$cRed."\"";
        $comment = "v. $phpversion Inadequada !!!";
        if ($phpver >= $phpvermin) {
            $comment = "mínimo PHP ". self::$aRequirements['PHP'][3];
            $status = 'OK';
            $bcor = "bgcolor=\"".self::$cGreen."\"";
        }
        $htmmod = "<table><tr bgcolor=\"#FFFF99\">"
            . "<td>Versão do PHP $phpversion</td>"
            . "<td $bcor><div align=\"center\">$status</div></td>"
            . "<td>$comment</td></tr>";
        
        foreach (self::$aRequirements as $key => $param) {
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
    
    /**
     * checkCerts
     *
     * @param  string $cnpj
     * @param  string $pathCertsFiles
     * @param  string $certPfxName
     * @param  string $certPassword
     * @return array
     */
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
    
    /**
     * checkFolders
     *
     * @param  string $pathnfe
     * @param  string $pathcte
     * @param  string $pathmdfe
     * @param  string $pathcle
     * @param  string $pathnfse
     * @param  string $pathcerts
     * @return array
     */
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
        //testa e constroi a estrutura da pasta NFe
        $aResp['NFe'] = self::zFolderMTest($pathnfe);
        //testa e constroi a estrutura da pasta CTe
        $aResp['CTe'] = self::zFolderMTest($pathcte);
        //testa e constroi a estrutura da pasta MDFe
        $aResp['MDFe'] = self::zFolderMTest($pathmdfe);
        //testa e constroi a estrutura da pasta cle
        $aResp['CLe'] = self::zFolderMTest($pathcle);
        //testa e constroi a estrutura da pasta NFSe
        $aResp['NFSe'] = self::zFolderMTest($pathnfse);
        //testa diretorio certs
        if ($pathcerts != '') {
            if (! is_writable($pathcerts)) {
                $aResp['Certs'] = array('status'=>false, 'msg'=>'Diretório sem permissões de escrita');
            }
        }
        return $aResp;
    }
    
    /**
     * zFolderMTest
     *
     * @param  string $path
     * @return array
     */
    protected static function zFolderMTest($path = '')
    {
        $aResp = array('status' => true, 'msg' => '');
        if ($path != '') {
            try {
                FilesFolders::createFolders($path);
            } catch (RuntimeException $e) {
                $aResp = array('status' => false, 'msg' => $e->getMessage());
            }
        }
        return $aResp;
    }
}
