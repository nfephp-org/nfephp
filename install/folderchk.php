<?php

namespace NFePHP\install;

require_once('../bootstrap.php');

use NFePHP\Common\Files\FilesFolders;

$pathnfe = filter_input(INPUT_POST, 'pathNFeFiles', FILTER_SANITIZE_STRING);
$pathcte = filter_input(INPUT_POST, 'pathCTeFiles', FILTER_SANITIZE_STRING);
$pathmdfe = filter_input(INPUT_POST, 'pathMDFeFiles', FILTER_SANITIZE_STRING);
$pathcle = filter_input(INPUT_POST, 'pathCLeFiles', FILTER_SANITIZE_STRING);
$pathnfse = filter_input(INPUT_POST, 'pathNFSeFiles', FILTER_SANITIZE_STRING);
$pathcerts = filter_input(INPUT_POST, 'pathCertsFiles', FILTER_SANITIZE_STRING);

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

print json_encode($aResp);
