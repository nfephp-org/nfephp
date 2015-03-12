<?php
namespace Common\Base;

/**
 * Classe base para a criação das classes construtoras dos XML
 * tanto para NFe, NFCe, CTe e MDFe
 *  
 * @category   NFePHP
 * @package    NFePHP\Common\Base\BaseMake
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Dom\Dom;
use Common\Keys\Keys;

class BaseMake
{
    /**
     * erros
     * Matriz contendo os erros reportados pelas tags obrigatórias
     * e sem conteúdo
     * @var array
     */
    public $erros = array();
    /**
     * versao
     * numero da versão do xml do documento fiscal
     * @var double
     */
    public $versao = 0;
    /**
     * mod
     * modelo do documento fiscal (55, 65, 57 ou 58)
     * @var integer
     */
    public $mod = 0;
    /**
     * xml
     * String com o xml da NFe montado
     * @var string
     */
    public $xml = '';
    /**
     * dom
     * Variável onde será montado o xml do documento fiscal
     * @var DOMDocument
     */
    public $dom;
    /**
     * tpAmb
     * tipo de ambiente 
     * @var string
     */
    public $tpAmb = '2';
    
    /**
     * __contruct
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
     * @return string
     */
    public function getXML()
    {
        return $this->xml;
    }
    
    /**
     * montaChave
     * Monta a chave do documento fiscal
     * 
     * @param string $cUF
     * @param string $ano
     * @param string $mes
     * @param string $cnpj
     * @param string $mod
     * @param string $serie
     * @param string $numero
     * @param string $tpEmis
     * @param string $codigo
     * @return string
     */
    public function montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo)
    {
        return Keys::buildKey($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo);
    }
}
