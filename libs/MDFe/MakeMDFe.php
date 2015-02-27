<?php

namespace MDFe;

/**
  * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * Estrutura baseada nas notas técnicas:
 *          
 * 
 * @package     NFePHP
 * @name        MakeMDFe
 * @version     0.0.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * 
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 * 
 */

use Common\DateTime\DateTime;
use Common\Base\BaseMake;
use \DOMDocument;
use \DOMElement;

class MakeMDFe extends BaseMake
{
    /**
     * versao
     * numero da versão do xml da MDFe
     * @var double
     */
    public $versao = 1.00;
    /**
     * mod
     * modelo da MDFe 58
     * @var integer
     */
    public $mod = 58;
    /**
     * chave da MDFe
     * @var string
     */
    public $chMDFe = '';
    
    //propriedades privadas utilizadas internamente pela classe
    private $MDFe = ''; //DOMNode
    private $infMDFe = ''; //DOMNode
    private $ide = ''; //DOMNode
    private $emit = ''; //DOMNode
    private $enderEmit = ''; //DOMNode
    private $infModal = ''; //DOMNode
    private $infDoc = ''; //DOMNode
    private $tot = ''; //DOMNode
    private $infAdic = ''; //DOMNode
    private $rodo = ''; //DOMNode
    private $veicPrincipal = ''; //DOMNode
    private $valePed = ''; //DOMNode
    private $aereo = ''; //DOMNode
    private $ferrov = ''; //DOMNode
    private $aqua = ''; //DOMNode
    
    // Arrays
    private $aInfMunCarrega = array(); //array de DOMNode
    private $aInfPercurso = array(); //array de DOMNode
    private $aInfMunDescarga = array(); //array de DOMNode
    private $aInfCTe = array(); //array de DOMNode
    private $aInfCT = array(); //array de DOMNode
    private $aInfNFe = array(); //array de DOMNode
    private $aInfNF = array(); //array de DOMNode
    private $aLacres = array(); //array de DOMNode
    private $aCondutor = array(); //array de DOMNode
    private $aVeicReboque = array(); //array de DOMNode
    private $aDisp = array(); //array de DOMNode
    private $aVag = array(); //array de DOMNode
    private $aInfTermCarreg = array(); //array de DOMNode
    private $aInfTermDescarreg = array(); //array de DOMNode
    private $aInfEmbComb = array(); //array de DOMNode
    
    public function montaMDFe()
    {
        
    }
}
