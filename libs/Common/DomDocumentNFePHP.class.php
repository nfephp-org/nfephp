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
 * @version     1.0.0
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
     * @return void
     */
    public function __construct()
    {
        parent::__construct('1.0', 'utf-8');
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
    }

    /**
     * getElementsByTagNameOpcional
     * Apenas uma simplificação de "getElementsByTagName($nome)->item(0)->nodeValue"
     * com a verificação se está vazio, para evitar repetições densas de operadores
     * ternários ao longo do código
     * @param string $nome
     * @param mixed $valorSeVazio
     * @throws nfephpException
     * @return mixed
     */
    public function getElementsByTagNameOpcional($nome, $valorSeVazio = '')
    {
        $element = $this->getElementsByTagName($nome)->item(0);
        //validação de segurança, se lista vazia será NULL e logo abaixo falharia
        //acessar "NULL->nodeValue", por isso gera exceção
        if ($element === NULL) {
            throw new nfephpException("Erro ao recuperar elemento DOM \"$nome\" pois item(0) retornou NULL, verifique!!");
        }
        //se elemento não estiver vazio retorna o seu conteúdo/valor
        if (! empty($element->nodeValue)) {
            return $node->nodeValue;
        }
        //elemento está vazio, retorna o parâmetro recebido para usar neste caso
        return $valorSeVazio;
    }
}