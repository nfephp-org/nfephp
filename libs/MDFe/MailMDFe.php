<?php

namespace NFePHP\MDFe;

/**
 * Classe para envio dos emails aos interessados
 * @category   NFePHP
 * @package    NFePHP\MDFe\MailMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 * 
 * NOTA: O envio de email's pelo sistema de MDFe não é necessário pois não 
 * envolve clientes ou fornecedores é apenas uma forma de comunicar o transporte de 
 * mercadorias, principalmente nos postos fiscais.
 * Esta classe foi eleborada apenas para o caso de por aulgum motivo o emitente
 * deseje enviar o MDFe a outros e também para manter a compatibilidade e estrutura similar
 * com o modulo de NF. 
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMail;
use Html2Text\Html2Text;
use \DOMDocument;

class MailMDFe
{
    protected $msgHtml = '';
    protected $msgTxt = '';
    protected $aMail = array();
    
    /**
     * enviaMail
     * @param string $pathFile
     * @param array $aMail
     * @param boolean $comPdf
     * @return boolean
     */
    public function envia($pathFile = '', $aMail = array(), $comPdf = false)
    {
        //se $comPdf é falso então somente é anexado o xml
        if ($comPdf) {
            //se $comPdf é verdadeiro então é criado o DANFE e anexado ao email
            //TODO : renderizar o pdf e anexar ao email
        }
        $assunto = $this->zMontaMessagem($pathFile);
        $this->addAttachment($pathFile, '');
        $this->buildMessage($this->msgHtml, $this->msgTxt);
        //se $aMail está vazio então pega o endereço de email do destinatário no xml
        if (! empty($aMail)) {
            //se $aMail não é vazio então envia o email para todos os endereços do array
            $this->aMail = $aMail;
        }
        $this->sendMail($assunto, $this->aMail);
        return true;
    }
    
    /**
     * zMontaMessagem
     * @param string $pathFile
     */
    protected function zMontaMessagem($pathFile)
    {
        $dom = new Dom();
        $dom->loadXMLFile($pathFile);
        $infMDFe = $dom->getNode('infMDFe', 0);
        $ide = $infMDFe->getElementsByTagName('ide')->item(0);
        $emit = $infMDFe->getElementsByTagName('emit')->item(0);
        $tot = $infMDFe->getElementsByTagName('tot')->item(0);
        $razao = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
        $nMDF = $ide->getElementsByTagName('nMDF')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $dhEmi = ! empty($ide->getElementsByTagName('dhEmi')->item(0)->nodeValue) ?
            $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue :
            $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $data = date('d/m/Y', DateTime::convertSefazTimeToTimestamp($dhEmi));
        $vCarga = $tot->getElementsByTagName('vCarga')->item(0)->nodeValue;
        $xNome = '';
        $this->msgHtml = $this->zRenderTemplate($xNome, $data, $nMDF, $serie, $vCarga, $razao);
        $this->msgTxt = utf8_encode(Html2Text::convert(utf8_decode($this->msgHtml)));
        return "MDFe n. $nMDF - $razao";
    }
    
    /**
     * zRenderTemplate
     * @param string $xNome
     * @param string $data
     * @param string $nNF
     * @param string $serie
     * @param string $vNF
     * @param string $razao
     * @return string
     */
    protected function zRenderTemplate($xNome, $data, $nMDF, $serie, $vCarga, $razao)
    {
        $this->zTemplate();
        $temp = $this->template;
        $aSearch = array(
            '{contato}',
            '{data}',
            '{numero}',
            '{serie}',
            '{valor}',
            '{emitente}'
        );
        $aReplace = array(
          $xNome,
          $data,
          $nMDF,
          $serie,
          $vCarga,
          $razao
        );
        $temp = str_replace($aSearch, $aReplace, $temp);
        return $temp;
    }

    /**
     * zTemplate
     * Seo template estiver vazio cria o basico
     */
    protected function zTemplate()
    {
        if (empty($this->template)) {
            $this->template = "<p><b>Prezados,</b></p>" .
                "<p>Você está recebendo o Manifesto Eletrônico de Documentos Fiscais (MDF-e) " .
                "emitido em {data} com o número {numero}, série {serie} de {emitente}, com " .
                "mercadorias no valor total de R$ {valor}.</p>" .
                "<p>Junto com a mercadoria, acompanha um DAMDFE (Documento Auxiliar do Manifesto " .
                "Eletrônico de Documentos Fiscais), que acompanha o trânsito das mercadorias.</p>" .
                "<p><i>Manifesto Eletrônico de Documentos Fiscais (MDF-e) é o documento emitido e armazenado " .
                "eletronicamente, de existência apenas digital, para vincular os documentos fiscais utilizados na " .
                "operação e/ou prestação, à unidade de carga utilizada no transporte, cuja validade jurídica é ".
                "garantida pela assinatura digital do emitente e autorização de uso pela administração tributária da ".
                "unidade federada do contribuinte.</i></p>" .
                "<p><i>O MDF-e deverá ser emitido por empresas prestadoras de serviço de transporte para prestações " .
                "com mais de um conhecimento de transporte ou pelas demais empresas nas operações, cujo " .
                "transporte seja realizado em veículos próprios, arrendados, ou mediante contratação de " .
                "transportador autônomo de cargas, com mais de uma nota fiscal.</i></p>" .
                "A finalidade do MDF-e é agilizar o registro em lote de documentos fiscais em trânsito e identificar a " .
                "unidade de carga utilizada e demais características do transporte.</i></p>" .
                "Autorização de uso do MDF-e implicará em registro posterior dos eventos, nos documentos fiscais " .
                "<p>Para mais detalhes, consulte: <a href=\"http://https://mdfe-portal.sefaz.rs.gov.br/\">" .
                "https://mdfe-portal.sefaz.rs.gov.br</a></p>" .
                "<br>" .
                "<p>Atenciosamente,</p>" .
                "<p>{emitente}</p>";
        }
    }
}
