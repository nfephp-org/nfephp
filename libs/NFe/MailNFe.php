<?php

namespace NFePHP\NFe;

/**
 * Classe para envio dos emails aos interessados
 * @category   NFePHP
 * @package    NFePHP\NFe\MailNFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMail;
use Html2Text\Html2Text;
use \DOMDocument;

class MailNFe extends BaseMail
{
    public $error = '';
    protected $msgHtml = '';
    protected $msgTxt = '';
    protected $aMail = array();
    
    /**
     * enviaMail
     * @param string $pathFile
     * @param array $aMail
     * @param boolean $comPdf
     * @param string $pathPdf
     * @return boolean
     */
    public function envia($pathFile = '', $aMail = array(), $comPdf = false, $pathPdf = '')
    {
        if ($comPdf) {
            if ($pathPdf == '') {
                //se $comPdf é verdadeiro então é criado o DANFE
                //com seu path na variável $pathPdf
                //então é anexado ao email
                //TODO : renderizar o pdf
            }
            //cria o anexo do pdf
            $this->addAttachment($pathPdf, '');
        }
        $assunto = $this->zMontaMessagem($pathFile);
        //cria o anexo do xml
        $this->addAttachment($pathFile, '');
        //constroi a mensagem
        $this->buildMessage($this->msgHtml, $this->msgTxt);
        //se $aMail está vazio então pega o endereço de email do destinatário no xml
        if (! empty($aMail)) {
            //se $aMail não é vazio então envia o email para todos os endereços do array
            $this->aMail = $aMail;
        }
        $err = $this->sendMail($assunto, $this->aMail);
        if ($err === true) {
            return true;
        } else {
            $this->error = $err;
            return false;
        }
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
        $infNFe = $dom->getNode('infNFe', 0);
        $ide = $infNFe->getElementsByTagName('ide')->item(0);
        $dest = $infNFe->getElementsByTagName('dest')->item(0);
        $emit = $infNFe->getElementsByTagName('emit')->item(0);
        $icmsTot = $infNFe->getElementsByTagName('ICMSTot')->item(0);
        $razao = $emit->getElementsByTagName('xNome')->item(0)->nodeValue;
        $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $xNome = $dest->getElementsByTagName('xNome')->item(0)->nodeValue;
        $dhEmi = ! empty($ide->getElementsByTagName('dhEmi')->item(0)->nodeValue) ?
                $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue :
                $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $data = date('d/m/Y', DateTime::convertSefazTimeToTimestamp($dhEmi));
        $vNF = $icmsTot->getElementsByTagName('vNF')->item(0)->nodeValue;
        $this->aMail[] = $dest->getElementsByTagName('email')->item(0)->nodeValue;
        //peagar os emails que existirem em obsCont
        $infAdic = $infNFe->getElementsByTagName('infAdic')->item(0);
        if (!empty($infAdic)) {
            $obsConts = $infAdic->getElementsByTagName('obsCont');
            foreach ($obsConts as $obsCont) {
                if (strtoupper($obsCont->getAttribute('xCampo')) === 'EMAIL') {
                    $this->aMail[] = $obsCont->getElementsByTagName('xTexto')->item(0)->nodeValue;
                }
            }
        }
        $this->msgHtml = $this->zRenderTemplate($xNome, $data, $nNF, $serie, $vNF, $razao);
        $this->msgTxt = utf8_encode(Html2Text::convert(utf8_decode($this->msgHtml)));
        return "NFe n. $nNF - $razao";
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
    protected function zRenderTemplate($xNome, $data, $nNF, $serie, $vNF, $razao)
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
          $nNF,
          $serie,
          $vNF,
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
            $this->template = "<p><b>Prezados {contato},</b></p>".
                "<p>Você está recebendo a Nota Fiscal Eletrônica emitida em {data} com o número ".
                "{numero}, série {serie} de {emitente}, no valor de R$ {valor}. ".
                "Junto com a mercadoria, você receberá também um DANFE (Documento ".
                "Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.</p>".
                "<p><i>Podemos conceituar a Nota Fiscal Eletrônica como um documento ".
                "de existência apenas digital, emitido e armazenado eletronicamente, ".
                "com o intuito de documentar, para fins fiscais, uma operação de ".
                "circulação de mercadorias, ocorrida entre as partes. Sua validade ".
                "jurídica garantida pela assinatura digital do remetente (garantia ".
                "de autoria e de integridade) e recepção, pelo Fisco, do documento ".
                "eletrônico, antes da ocorrência do Fato Gerador.</i></p>".
                "<p><i>Os registros fiscais e contábeis devem ser feitos, a partir ".
                "do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o ".
                "DANFE, que representa graficamente a Nota Fiscal Eletrônica. ".
                "A validade e autenticidade deste documento eletrônico pode ser ".
                "verificada no site nacional do projeto (www.nfe.fazenda.gov.br), ".
                "através da chave de acesso contida no DANFE.</i></p>".
                "<p><i>Para poder utilizar os dados descritos do DANFE na ".
                "escrituração da NF-e, tanto o contribuinte destinatário, ".
                "como o contribuinte emitente, terão de verificar a validade da NF-e. ".
                "Esta validade está vinculada à efetiva existência da NF-e nos ".
                "arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.</i></p>".
                "<p><b>O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, ".
                "servindo apenas como instrumento auxiliar para consulta da NF-e no ".
                "Ambiente Nacional.</b></p>".
                "<p>Para mais detalhes, consulte: <a href=\"http://www.nfe.fazenda.gov.br/\">".
                "www.nfe.fazenda.gov.br</a></p>".
                "<br>".
                "<p>Atenciosamente,</p>".
                "<p>{emitente}</p>";
        }
    }
}
