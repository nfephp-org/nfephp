<?php

namespace Common\Files;

/**
 * Classe auxiliar para criar, listar e testar os diretórios utilizados pela API
 * @category   NFePHP
 * @package    NFePHP\Common\Files
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Exception\RuntimeException;

class FilesFolders
{
    /**
     * createFolders
     * Cria a estrutura de diretorios para a guarda dos arquivos 
     * @param string $dirPath path do diretorio a ser criado
     * @return boolean
     * @throws Exception\RuntimeException
     */
    public function createFolders($dirPath = '')
    {
        $ambientes = array('homologacao','producao');
        $subdirs = array(
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
        
        //monta a arvore de diretórios necessária e estabelece permissões de acesso
        if (! is_dir($dirPath)) {
            if (! mkdir($dirPath, 0777)) {
                throw new Exception\RuntimeException(
                    "Não foi possivel criar o diretorio. Verifique as permissões"
                );
            }
        }
        foreach ($ambientes as $ambiente) {
            $folder = $dirPath.DIRECTORY_SEPARATOR.$ambiente;
            if (!is_dir($folder)) {
                mkdir($folder, 0777);
            }
            foreach ($subdirs as $subdir) {
                $folder = $arqDir.DIRECTORY_SEPARATOR.$ambiente.DIRECTORY_SEPARATOR.$subdir;
                if (!is_dir($folder)) {
                    mkdir($folder, 0777);
                }
            }
        }
        return true;
    }
    
    /**
     * listDir
     * Obtem todo o conteúdo de um diretorio, e que atendam ao critério indicado.
     * @param string $dir Diretorio a ser pesquisado
     * @param string $fileMatch Critério de seleção pode ser usados coringas como *-nfe.xml
     * @param boolean $retpath se true retorna o path completo dos arquivos se false so retorna o nome dos arquivos
     * @return mixed Matriz com os nome dos arquivos que atendem ao critério estabelecido ou false
     * @throws Exception\RuntimeException
     */
    public function listDir($dir, $fileMatch = '*-nfe.xml', $retpath = false)
    {
        if (trim($fileMatch) != '' && trim($dir) != '') {
            //passar o padrão para minúsculas
            $fileMatch = strtolower($fileMatch);
            //cria um array limpo
            $aName = array();
            //guarda o diretorio atual
            $oldDir = getcwd().DIRECTORY_SEPARATOR;
            //verifica se o parametro $dir define um diretorio real
            if (is_dir($dir)) {
                //mude para o novo diretorio
                chdir($dir);
                //pegue o diretorio
                $diretorio = getcwd().DIRECTORY_SEPARATOR;
                if (strtolower($dir) != strtolower($diretorio)) {
                    throw new Exception\RuntimeException(
                        "Falha! sem permissão de leitura no diretorio escolhido."
                    );
                }
                //abra o diretório
                $ponteiro  = opendir($diretorio);
                $numX = 0;
                // monta os vetores com os itens encontrados na pasta
                while (false !== ($file = readdir($ponteiro))) {
                    //procure se não for diretorio
                    if ($file != "." && $file != "..") {
                        if (! is_dir($file)) {
                            $tfile = strtolower($file);
                            //é um arquivo então
                            //verifique se combina com o $fileMatch
                            if (fnmatch($fileMatch, $tfile)) {
                                if ($retpath) {
                                    $aName[$numX] = $dir.$file;
                                } else {
                                    $aName[$numX] = $file;
                                }
                                $numX++;
                            }
                        }
                    }
                }
                closedir($ponteiro);
                //volte para o diretorio anterior
                chdir($oldDir);
            }
        }
        sort($aName);
        return $aName;
    }
    
    /**
     * Rotina para teste de escrita no path especificado
     * Usada na rotina de configuração (install.php)
     * @param string $path
     * @param string $message
     * @param string $respHtml passado por referencia irá conter a resposta em html
     * @return boolean
     */
    public function writeTest($path = '', $message = '', &$respHtml = '')
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
}
