<?php

namespace NFePHP\CTe;

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
 * @name        MakeCTe
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

use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMake;
use \DOMDocument;
use \DOMElement;

class MakeCTe extends BaseMake
{
    /**
     * versao
     * numero da versão do xml da CTe
     * @var double
     */
    public $versao = 2.00;
    /**
     * mod
     * modelo da CTe 57
     * @var integer
     */
    public $mod = 57;
    /**
     * chave da MDFe
     * @var string
     */
    public $chCTe = '';
    
    public function montaCTe()
    {
        
    }
}
