<?php

namespace NFePHP\Common\Base;

/**
 * Classe base para a criação das classes construtoras dos XML
 * tanto para NFe, NFCe, CTe e MDFe
 *
 * @category  NFePHP
 * @package   NFePHP\Common\Base\BaseMake
 * @copyright Copyright (c) 2008-2015
 * @license   http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Dom\Dom as Dom;
use NFePHP\Common\Keys\Keys;
use NFePHP\Common\Identify\Identify;
use NFePHP\Common\Files\FilesFolders;

class BaseMake
{
    /**
     * erros
     * Matriz contendo os erros reportados pelas tags obrigatórias
     * e sem conteúdo
     *
     * @var array
     */
    public $erros = array();
    /**
     * versao
     * numero da versão do xml do documento fiscal
     *
     * @var string
     */
    public $versao = '';
    /**
     * mod
     * modelo do documento fiscal (55, 65, 57 ou 58)
     *
     * @var integer
     */
    public $mod = 0;
    /**
     * xml
     * String com o xml da NFe montado
     *
     * @var string
     */
    public $xml = '';
    /**
     * dom
     * Variável onde será montado o xml do documento fiscal
     *
     * @var \NFePHP\Common\Dom\Dom
     */
    public $dom;
    /**
     * tpAmb
     * tipo de ambiente
     *
     * @var string
     */
    public $tpAmb = '2';
    
    /**
     * __construct
     * Função construtora cria um objeto DOMDocument
     * que será carregado com o documento fiscal
     */
    public function __construct()
    {
        $this->dom = new Dom();
    }
    
    /**
     * getXML
     * retorna o xml que foi montado
     *
     * @return string
     */
    public function getXML()
    {
        return $this->xml;
    }
    
    /**
     * gravaXML
     * grava o xml do documento fiscal na estrutura de pastas
     * em path indicar por exemplo /var/www/nfe ou /dados/cte ou /arquivo/mdfe
     * ou seja as pastas principais onde guardar os arquivos
     * Esse método itá colocar na subpastas [producao] ou [homologacao]
     * na subpasta [entradas] e na subpasta [ANOMES]
     *
     * @param  string $path
     * @return boolean
     */
    public function gravaXML($path = '')
    {
        //pode ser NFe, CTe, MDFe e pode ser homologação ou produção
        //essas informações estão dentro do xml
        if ($path == '') {
            return false;
        }
        if (! is_dir($path)) {
            return false;
        }
        if (substr($path, -1) == DIRECTORY_SEPARATOR) {
            $path = substr($path, 0, strlen($path)-1);
        }
        $aResp = array();
        $aList = array('NFe' => 'nfe','CTe' => 'cte','MDFe' => 'mdfe');
        Identify::setListSchemesId($aList);
        $schem = Identify::identificacao($this->xml, $aResp);
        if ($aResp['chave'] == '') {
            return false;
        }
        $filename = $aResp['chave'].'-'.$schem.'.xml';
        $dirBase = 'homologacao';
        if ($aResp['tpAmb'] == '1') {
            $dirBase = 'producao';
        }
        $aDh = explode('-', $aResp['dhEmi']);
        $anomes = date('Ym');
        if (count($aDh) > 1) {
            $anomes = $aDh[0].$aDh[1];
        }
        $completePath = $path.
            DIRECTORY_SEPARATOR.
            $dirBase.
            DIRECTORY_SEPARATOR.
            'entradas'.
            DIRECTORY_SEPARATOR.
            $anomes;
        
        $content = $this->xml;
        if (! FilesFolders::saveFile($completePath, $filename, $content)) {
            return false;
        }
        return true;
    }

    
    /**
     * montaChave
     * Monta a chave do documento fiscal
     *
     * @param  string $cUF
     * @param  string $ano
     * @param  string $mes
     * @param  string $cnpj
     * @param  string $mod
     * @param  string $serie
     * @param  string $numero
     * @param  string $tpEmis
     * @param  string $codigo
     * @return string
     */
    public function montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo)
    {
        return Keys::buildKey($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo);
    }
}
