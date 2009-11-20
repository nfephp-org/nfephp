<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; sem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * @package   NFePHP
 * @name      MailNFePHP
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author  Roberto L. Machado <roberto.machado@superig.com.br>
  */

require_once('libs/Mail/Mail.php');
require_once('libs/Mail/mime.php');

class MailNFePHP {

    public $mailERROR='';
    
    private $mailFROM;
    private $maillHOST;
    private $mailUSER;
    private $mailPASS;


    function __construct(){
        if ( is_file("config/config.php") ){
            include("config/config.php");
            $this->mailFROM  = $mailFROM;
            $this->maillHOST = $maillHOST;
            $this->mailUSER  = $mailUSER;
            $this->mailPASS  = $mailPASS;
        }
    }

    /**
     * sendNFe
     * Função para envio da NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendNFe
     * @version 1.0
     * @param string $docXML Conteúdo do arquivo XML
     * @param string $docPDF DANFE em formato PDF
     * @param string $nomeXML Nome do arquivo XML
     * @param string $nomePDF Nome do arquivo PDF
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendNFe($docXML,$docPDF,$nomeXML,$nomePDF,$aMail) {

        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $vtotal = $aMail['vtotal'];

        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - " . $emitente);
        // cabeçalho do email
        $headers = array('From' => $this->mailFROM,'Subject' => $subject);
        //mensagem no corpo do email
        $msg = "<p><b>Prezado Sr(a) $contato - $razao,</b>";
        $msg .= "<p>Você está recebendo a Nota Fiscal Eletrônica número $numero, série $serie de $emitente, no valor de R$ $vtotal. Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.";
        $msg .= "<p><i>Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.</i>";
        $msg .= "<p><i>Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.</i>";
        $msg .= "<p><i>Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.</i>";
        $msg .= "<p><b>O DANFE não uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.</b>";
        $msg .= "<p>Para mais detalhes sobre o projeto, consulte: <a href='http://www.nfe.fazenda.gov.br/portal/Default.aspx'>www.nfe.fazenda.gov.br</a>";
        $msg .= "<p><p>Atenciosamente,<p>$emitente";
        //corrige de utf8 para iso
        $msg = utf8_decode($msg);
        // O email será enviado no formato HTML
        // LEMBRETE : não deve have nenhum espaço após a palavra chave PDFMAIL
        $htmlMessage = "<html><body bgcolor='#ffffff'>$msg</body></html>";
        // criar uma nova instância da classe mime
        $mime = new Mail_Mime();
        // setar para conteudo em HTML
        $mime->setHtmlBody($htmlMessage);
        // IMPORTANTE: adicionar o arquivo pdf como um anexo
        $mime->addAttachment($docPDF, 'application/pdf', $nomePDF, false, 'base64');
        // IMPORTANTE: adicionar o arquivo xml como um anexo
        $mime->addAttachment($docXML,'application/xml',$nomeXML,false,'base64');
        // construir a mensagem de emial e salvar na variavel $body
        $mailBody = $mime->get();
        // construir o cabeçalho do emial
        $mailHead = $mime->headers($headers);
        //preparar o emial para envio
        $mail = &Mail::factory('smtp',array ('host' => $this->maillHOST,'auth' => true,'username' => $this->mailUSER,'password' => $this->mailPASS));
        // Enviar o email para o endereço indicado
        $mail->send($to, $mailHead, $mailBody);

        if (PEAR::isError($mail)) {
            $this->mailERROR = $mail->getMessage();
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
?>
