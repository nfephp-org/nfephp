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
 * @name        ConvertNFePHP.interface.php
 * @version     1.01
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Marcos Diez <marcos at unitron dot com dot br>
 * 
 * Esta interface garante a semelhança entre a Convert***NFePHP.class.php
 *	existem 2 funções basicas, TXT2XML e XML2TXT
 *	TXT2XML, pode receber uma string com o conteudo TXT, ou uma string com o nome do arquivo, ou um array com o conteudo do TXT ja 'parcialmente' interpretado
 *			o retorno é um array no seguinte formato:
 *			array(
 *				'id da nota no arquivo'=>array(
 *					'XML' => string do xml
 *					'erros'=>array( array de erros fatais que não deixam converter para o XML - erro de schema, neste caso a string XML pode existir ou não, depende do erro )
 *					'avisos'=>array( array de aviso que deixam o XML ser gerado, mas pode ter alterado o dado do TXT original )
 *				)
 *	XML2TXT, pode receber um objeto XML, um arquivo XML ou uma string XML, ou um array com objetos/arquivos/strings
 *			o retorno é um arquivo TXT com todos os XML:
 *				observação...
 *				em nfe, e cte, o retorno vem com um cabeçalho "NOTA FISCAL|5|" por exemplo
 * 
 */

#if(!interface_exists('ConvertNFePHP')){
#	interface ConvertNFePHP {
#	    public function TXT2XML($txt,$output_string=true);
#	    public function XML2TXT($xml);
#	}
#}
?>