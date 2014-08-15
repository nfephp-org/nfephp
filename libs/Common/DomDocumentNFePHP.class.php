<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package     NFePHP
 * @name        CommonNFePHP.class.php
 * @version     1.0.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Fernando Mertins <fernando dot mertins at gmail dot com>
 *
 *        CONTRIBUIDORES (por ordem alfabetica):
 *
 * Esta classe contem funcionalidades referentes à instanciação e manipulação de DOM Document
 */

class DomDocumentNFePHP extends DOMDocument
{

    /**
     * construtor
     * Executa o construtor-pai do DOMDocument e por padrão define o XML sem espaços
     * e sem identação
     * @param string $sXml Conteúdo XML opcional a ser carregado no DOM Document.
     * @return void
     */
    public function __construct($sXml = NULL)
    {
        parent::__construct('1.0', 'utf-8');
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
        
        if (is_string($sXml)) {
            $this->loadXML($sXml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        }
    }
}