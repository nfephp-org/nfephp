<?php
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
 *
 * @package   NFePHP
 * @name      MailNFePHPAlternate
 * @version   1.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto dot machado at superig dot com dot br>
 *
 *          CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Cristiano Soares <soares dot cr at gmail dot com>
 * 
 */

//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
//carrega as classes do PHPMailer
require_once('PHPMailer/class.phpmailer.php');

class MailNFePHP {

    public $mailERROR='';
    public $mailFROM;
    public $mailHOST;
    public $mailUSER;
    public $mailPASS;
    public $mailPORT;
    public $mailPROTOCOL;            
    public $mailFROMmail;
    public $mailFROMname;
    public $mailREPLYTOmail;
    public $mailREPLYTOname;

    function __construct($aConfig=''){
        if (is_array($aConfig)){
            $this->mailFROM  = $aConfig['mailFROM'];
            $this->mailHOST = $aConfig['mailHOST'];
            $this->mailUSER  = $aConfig['mailUSER'];
            $this->mailPASS  = $aConfig['mailPASS'];
            $this->mailPORT  = $aConfig['mailPORT'];
            $this->mailPROTOCOL  = $aConfig['mailPROTOCOL'];            
            $this->mailFROMmail  = $aConfig['mailFROMmail'];
            $this->mailFROMname  = $aConfig['mailFROMname'];
            $this->mailREPLYTOmail = $aConfig['mailREPLYTOmail'];
            $this->mailREPLYTOname = $aConfig['mailREPLYTOname'];
        } else {
            if ( is_file(PATH_ROOT.'config/config.php') ){
                include(PATH_ROOT.'config/config.php');
                $this->mailFROM  = $mailFROM;
                $this->mailHOST = $mailHOST;
                $this->mailUSER  = $mailUSER;
                $this->mailPASS  = $mailPASS;
                $this->mailPORT  = $mailPORT;
                $this->mailPROTOCOL  = $mailPROTOCOL;
                $this->mailFROMmail  = $mailFROMmail;
                $this->mailFROMname  = $mailFROMname;
                $this->mailREPLYTOmail = $mailREPLYTOmail;
                $this->mailREPLYTOname = $mailREPLYTOname;
            }
        }     
    } // end__construct

    /**
     * sendNFeMail
     * Função para envio da NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendNFeMail
     * @version 2.0
     * @param string $docXML Path para o arquivo XML
     * @param string $docPDF Path para DANFE em formato PDF
     * @param string $nomeXML Nome do arquivo XML
     * @param string $nomePDF Nome do arquivo PDF
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendNFeMail($docXML='',$docPDF='',$nomeXML='',$nomePDF='',$aMail) {

        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $vtotal = $aMail['vtotal'];

        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - " . $emitente);
        //mensagem no corpo do email em txt
        $txt = "Prezado Sr(a) $contato - $razao,\n\r";
        $txt .= "Você está recebendo a Nota Fiscal Eletrônica número $numero, série $serie de $emitente, no valor de R$ $vtotal. Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.\n\r";
        $txt .= "Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.\n\r";
        $txt .= "Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.\n\r";
        $txt .= "Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.\n\r";
        $txt .= "O DANFE não uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.\n\r";
        $txt .= "Para mais detalhes sobre o projeto, consulte: www.nfe.fazenda.gov.br\n\r";
        $txt .= "Atenciosamente, $emitente \n\r";
        //altera de utf8 para iso
        $txt = utf8_decode($txt);
        //mensagem no corpo do email em html
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
        $htmlMessage = "<body bgcolor='#ffffff'>$msg</body>";
        // o parametro true indica que uma exceção será criada em caso de erro,
        // é necessário buscar o erro       
        $mail = new PHPMailer(true); 
        // informa a classe para usar SMTP
        $mail->IsSMTP();
        // executa ações
        try {
            $mail->Host       = $this->mailHOST;        // SMTP server
            $mail->SMTPDebug  = 2;                      // habilita debug SMTP para testes
            $mail->SMTPAuth   = true;                   // habilita autienticação SMTP
            $mail->SMTPSecure = $this->mailPROTOCOL;    // "tls" ou "ssl"
            $mail->Port       = $this->mailPORT;        // Seta a porta a ser usada pelo SMTP
            $mail->Username   = $this->mailUSER;        // Nome do usuários do SMTP
            $mail->Password   = $this->mailPASS;        // Password do usuário SMPT
            $mail->AddReplyTo($this->mailREPLYTOmail,$this->mailREPLYTOname); //Indicação do email de retorno
            $mail->AddAddress($to,$contato);            // nome do destinatário
            $mail->SetFrom($this->mailFROMmail,$this->mailFROMname); //identificação do emitente
            $mail->Subject = $subject;                  // Assunto
            $mail->AltBody = $txt;                      // Corpo a mensagem em txt
            $mail->MsgHTML($htmlMessage);               // Corpo da mensagem em HTML
            if (is_file($docXML)){
                $mail->AddAttachment($docXML);          // Anexo
            }
            if (is_file($docPDF)){
                $mail->AddAttachment($docPDF);          // Anexo
            }
            $mail->Send();                              // Comando de envio
            $result = TRUE;
        } catch (phpmailerException $e) {               // captura de erros
            $this->mailERROR = $e->errorMessage();      //Mensagens de erro do PHPMailer
            $result = FALSE;
        } catch (Exception $e) {
            $this->mailERROR .=  $e->getMessage();      //Mensagens de erro por outros motivos
        }
        
        return $result;                                 //retorno da função
    } //fim da função sendNFeMail
} //fim da classe
?>
