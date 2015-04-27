<?php

namespace NFePHP\Common\Files;

/**
 * Classe auxiliar para criar, listar e testar os diretórios utilizados pela API
 * @category   NFePHP
 * @package    NFePHP\Common\Files
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Exception;

class FilesFolders
{
    
    protected static $ambientes = array('homologacao','producao');
    protected static $subdirs = array(
        'entradas',
        'assinadas',
        'validadas',
        'rejeitadas',
        'enviadas',
        'enviadas/aprovadas',
        'enviadas/denegadas',
        'enviadas/rejeitadas',
        'enviadas/encerradas',
        'canceladas',
        'inutilizadas',
        'cartacorrecao',
        'eventos',
        'dpec',
        'temporarias',
        'recebidas',
        'consultadas',
        'pdf'
    );
    
    /**
     * getAmbiente
     * @param string $tpAmb
     * @return string
     */
    public static function getAmbiente($tpAmb = '2')
    {
        if ($tpAmb == '2') {
            return 'homologacao';
        }
        return 'producao';
    }
    
    /**
     * getFilePath
     * @param string $tpAmb
     * @param string $dirbase
     * @param string $subdir
     * @return string
     * @throws Exception\RuntimeException
     */
    public static function getFilePath($tpAmb = '2', $dirbase = '', $subdir = '')
    {
        $path = $dirbase
            . DIRECTORY_SEPARATOR
            . self::getAmbiente($tpAmb)
            . DIRECTORY_SEPARATOR
            . $subdir;
        
        if (! is_dir($path)) {
            $msg = "Não existe o diretorio $path !";
            throw new Exception\RuntimeException($msg);
        }
        return $path;
    }
    
    /**
     * createFolders
     * Cria a estrutura de diretorios para a guarda dos arquivos 
     * @param string $dirPath path do diretorio a ser criado
     * @return boolean
     * @throws Exception\RuntimeException
     */
    public static function createFolders($dirPath = '')
    {
        //monta a arvore de diretórios necessária e estabelece permissões de acesso
        self::createFolder($dirPath);
        foreach (self::$ambientes as $ambiente) {
            $folder = $dirPath.DIRECTORY_SEPARATOR.$ambiente;
            self::createFolder($folder);
            foreach (self::$subdirs as $subdir) {
                $folder = $dirPath.DIRECTORY_SEPARATOR.$ambiente.DIRECTORY_SEPARATOR.$subdir;
                self::createFolder($folder);
            }
        }
        return true;
    }
    
    /**
     * createFolder
     * @param string $folder
     * @throws Exception\RuntimeException
     */
    public static function createFolder($folder = '')
    {
        if (! is_dir($folder)) {
            if (! mkdir($folder, 0777)) {
                throw new Exception\RuntimeException(
                    "Não foi possivel criar o diretorio $folder. Verifique as permissões"
                );
            }
        }
    }
    
    /**
     * saveFile
     * @param string $path
     * @param string $filename
     * @param string $content
     * @return boolean
     */
    public static function saveFile($path = '', $filename = '', $content = '')
    {
        self::createFolder($path);
        $filePath = $path.DIRECTORY_SEPARATOR.$filename;
        if (! file_put_contents($filePath, $content)) {
            return false;
        }
        if (! chmod($filePath, 0777)) {
            return false;
        }
        return true;
    }
    
    /**
     * listDir
     * Obtem todo o conteúdo de um diretorio, e que atendam ao critério indicado.
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Critério de seleção pode ser usados coringas como *-nfe.xml
     * @param boolean $retpath se true retorna o path completo dos arquivos se false so retorna o nome dos arquivos
     * @return array com os nome dos arquivos que atendem ao critério estabelecido ou false
     * @throws Exception\InvalidArgumentException
     */
    public static function listDir($folder, $fileMatch = '*-nfe.xml', $retpath = false)
    {
        if ($folder == '' || $fileMatch == '') {
            throw new Exception\InvalidArgumentException(
                "É necessário passar os parametros diretório e filtro!!!"
            );
        }
        if (! is_dir($folder)) {
            throw new Exception\InvalidArgumentException(
                "O diretório não existe!!!"
            );
        }
        $aList = array();
        $search = $folder;
        if (substr($folder, -1) == DIRECTORY_SEPARATOR) {
            $search = substr($folder, 0, strlen($folder)-1);
        }
        $searchmatch = $search.DIRECTORY_SEPARATOR.$fileMatch;
        $aGlob = glob($searchmatch);
        $aList = $aGlob;
        if (! $retpath && ! empty($aGlob)) {
            $aList = array();
            foreach ($aGlob as $pathFile) {
                $aList[] = str_replace($search.DIRECTORY_SEPARATOR, '', $pathFile);
            }
        }
        return $aList;
    }
    
    /**
     * Rotina para teste de escrita no path especificado
     * Usada na rotina de configuração (install.php)
     * @param string $path
     * @param string $message
     * @param string $respHtml passado por referencia irá conter a resposta em html
     * @return boolean
     */
    public static function writeTest($path = '', $message = '', &$respHtml = '')
    {
        $cRed = '#FF0000';
        $cGreen = '#00CC00';
        $comentDir = 'O diret&oacute;rio N&Atilde;O EXISTE';
        $corDir = $cRed;
        if (is_dir($path)) {
            $filen = $path.DIRECTORY_SEPARATOR.'teste.txt';
            $comentDir = ' Sem permiss&atilde;o !!';
            if (file_put_contents($filen, "teste\r\n")) {
                $corDir = $cGreen;
                $comentDir = ' Permiss&atilde;o OK';
                unlink($filen);
            }
        }
        $respHtml = "<tr bgcolor=\"#FFFFCC\">"
            . "<td>$message</td>"
            . "<td bgcolor=\"$corDir\"><div align=\"center\">$comentDir</div></td>"
            . "<td>O diret&oacute;rio deve ter permiss&atilde;o de escrita</td></tr>";
        if ($corDir == $cRed) {
            return false;
        }
        return true;
    }
    
    /**
     * Apaga um diretorio e todo o seu conteúdo
     * @param string $dirPath
     * @return boolean
     * @throws Exception\RuntimeException
     */
    public static function removeFolder($dirPath)
    {
        $files = array_diff(scandir($dirPath), array('.','..'));
        foreach ($files as $file) {
            if (is_dir("$dirPath/$file")) {
                self::removeFolder("$dirPath/$file");
            } else {
                if (! unlink("$dirPath/$file")) {
                    throw new Exception\RuntimeException(
                        "Falha! sem permissão de exclusão do arquivo $dirPath/$file"
                    );
                }
            }
        }
        if (! rmdir($dirPath)) {
            $msg = "Falha! sem permissão de exclusão do diretório $dirPath";
            throw new Exception\RuntimeException($msg);
        }
        return true;
    }
    
    /**
     * readFile
     * @param string $pathFile
     * @return string
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function readFile($pathFile = '')
    {
        if ($pathFile == '') {
            $msg = "Um caminho para o arquivo deve ser passado!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (! is_file($pathFile)) {
            $msg = "O arquivo indicado não foi localizado!!";
            throw new Exception\InvalidArgumentException($msg);
        }
        if (! is_readable($pathFile)) {
            $msg = "O arquivo indicado não pode ser lido. Permissões!!";
            throw new Exception\RuntimeException($msg);
        }
        return file_get_contents($pathFile);
    }
}
