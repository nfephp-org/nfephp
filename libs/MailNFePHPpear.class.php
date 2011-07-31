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
 * @package   NFePHP
 * @name      MailNFePHPpear
 * @version   2.14
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto.machado@superig.com.br>
 * 
 *          CONTRIBUIDORES (em ordem alfabetica):
 *              João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *
 * NOTA: Esta classe requer a instalação do pacote pear Mail e suas dependencias
 * para isso instale php-pear e em seguida o comando 
 * pear install --alldeps Mail (testado em Linux Debian e derivados)
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
require_once('Mail/Mail.php');
require_once('Mail/mime.php');

class MailNFePHP {

    public $mailAuth='1';
    private $mailFROM='';
    private $maillHOST='';
    private $mailUSER='';
    private $mailPASS='';
    public $mailERROR='';


    function __construct($aConfig=''){
        if (is_array($aConfig)){
            $this->mailAuth  = $aConfig['mailAuth'];
            $this->mailFROM  = $aConfig['mailFROM'];
            $this->maillHOST = $aConfig['maillHOST'];
            $this->mailUSER  = $aConfig['mailUSER'];
            $this->mailPASS  = $aConfig['mailPASS'];
        } else {
            if ( is_file(PATH_ROOT.'config/config.php') ){
                include(PATH_ROOT.'config/config.php');
                $this->mailAuth  = $mailAuth;
                $this->mailFROM  = $mailFROM;
                $this->maillHOST = $maillHOST;
                $this->mailUSER  = $mailUSER;
                $this->mailPASS  = $mailPASS;
            }
        }     
    }

    /**
     * sendNFe
     * Função para envio da NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendNFe
     * @version 2.13
     * @author    Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $docXML Conteúdo do arquivo XML, é obrigatório
     * @param string $docPDF DANFE em formato PDF, se não quizer enviar deixe em branco
     * @param string $nomeXML Nome do arquivo XML, é obrigatório
     * @param string $nomePDF Nome do arquivo PDF
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @param boolean $auth indica se é necessária a autenticação ou não 
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendNFe($docXML='',$docPDF='',$nomeXML='',$nomePDF='',$aMail='',$auth='') {
        //retorna se não foi passada a matriz com os dados de envio
        if (!is_array($aMail)){
            $this->mailERROR = 'Não foram passados parametros de envio!';
	    return false;
	}
        //retorna se não foi passado o xml
	if ($docXML == '' || $nomeXML == ''){
            $this->mailERROR = 'Não foi passados o XML da NFe para envio. O XML é Obrigatório!';
            return false;
	}
        //validar o endereço de email passado
        if (!$this->validEmailAdd($aMail['para'])){
            $this->mailERROR = 'O endereço informado não é valido! '.$aMail['para'];
            return false;
        }
        if($auth == ''){
            if(isset($this->mailAuth)){
                $auth = $this->mailAuth;
            } else {
                $auth = '1';
            }    
        }
        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $vtotal = $aMail['vtotal'];
	if ($contato==''){
            $contato = $razao;
	} else {
            $contato .= ' - '.$razao;
	}
        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - N.$numero - $emitente");
        // cabeçalho do email
        $headers = array('From' => $this->mailFROM,'Subject' => $subject);
        //mensagem no corpo do email
        $msg = "<p><b>Prezado Sr(a) $contato,</b>";
        $msg .= "<p>Você está recebendo a Nota Fiscal Eletrônica número $numero, série $serie de $emitente, no valor de R$ $vtotal. Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.";
        $msg .= "<p><i>Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.</i>";
        $msg .= "<p><i>Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.</i>";
        $msg .= "<p><i>Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.</i>";
        $msg .= "<p><b>O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.</b>";
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
        // adicionar o arquivo pdf como um anexo se for passado
        if ($docPDF != '' && $nomePDF != ''){
            $mime->addAttachment($docPDF, 'application/pdf', $nomePDF, false, 'base64');
	}	
        // IMPORTANTE: adicionar o arquivo xml como um anexo
        $mime->addAttachment($docXML,'application/xml',$nomeXML,false,'base64');
        // construir a mensagem de emial e salvar na variavel $body
        $mailBody = $mime->get();
        // construir o cabeçalho do emial
        $mailHead = $mime->headers($headers);
        //preparar o email para envio
	if ($auth=='1' && $this->mailUSER!='' && $this->mailPASS!=''){
            $mail = &Mail::factory('smtp',array ('host' => $this->maillHOST,'auth' => true,'username' => $this->mailUSER,'password' => $this->mailPASS));
	} else {
            $mail = &Mail::factory('smtp',array ('host' => $this->maillHOST,'auth' => false));
	}
        // Enviar o email para o endereço indicado
        $mail->send($to, $mailHead, $mailBody);
        if (PEAR::isError($mail)) {
            $this->mailERROR = 'Houve erro no envio do email, DEBUGAR!! '.$mail->getMessage();
            return false;
        } else {
            return true;
        }
    }//fim método sendNFe
    
    /**
     * sendCanc
     * Função para envio do Cancelamento ad NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendCanc
     * @version 1.01
     * @param string $docXML Conteúdo do arquivo XML
     * @param string $nomeXML Nome do arquivo XML
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @author João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendCanc($docXML='',$nomeXML='',$aMail='',$auth='') {
        //retorna se não foi passada a matriz com os dados de envio
        if (!is_array($aMail)){
            $this->mailERROR = 'Não foram passados parametros de envio!';
	    return false;
	}
        //retorna se não foi passado o xml
	if ($docXML == '' || $nomeXML == ''){
            $this->mailERROR = 'Não foi passados o XML da NFe para envio. O XML é Obrigatório!';
            return false;
	}
        //validar o endereço de email passado
        if (!$this->validEmailAdd($aMail['para'])){
            $this->mailERROR = 'O endereço informado não é valido! '.$aMail['para'];
            return false;
        }
        if($auth == ''){
            if(isset($this->mailAuth)){
                $auth = $this->mailAuth;
            } else {
                $auth = '1';
            }    
        }
        $datat = date('d-m-Y H:i:s');
        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $chNFe = $aMail['chNFe'];
        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - Cancelamento - " . $emitente);
        // cabeçalho do email
        $headers = array('From' => $this->mailFROM,'Subject' => $subject);
        //mensagem no corpo do email
        $msg = "<p><b>À $razao - $contato,</b>";
        $msg .= "<p>Em  anexo você está recebendo o XML de cancelamento da Nota Fiscal Eletrônica número $numero, série $serie emitida por $emitente.";
        $msg .= "<p><i>A validade deste documento eletrônico pode ser verificada no site nacional do projeto <a href='http://www.nfe.fazenda.gov.br/portal/Default.aspx'>www.nfe.fazenda.gov.br</a>";
        $msg .= " através da chave $chNFe</i>";
        $msg .= "<p><p>Atenciosamente,<p>$emitente";
        $msg .= "<p><p><i><small>$datat</small></i>";
        //corrige de utf8 para iso
        $msg = utf8_decode($msg);
        // O email será enviado no formato HTML
        $htmlMessage = "<html><body bgcolor='#ffffff'>$msg</body></html>";
        // criar uma nova instância da classe mime
        $mime = new Mail_Mime();
        // setar para conteudo em HTML
        $mime->setHtmlBody($htmlMessage);
        $mime->addAttachment($docXML,'application/xml',$nomeXML,false,'base64');
        // construir a mensagem de emial e salvar na variavel $body
        $mailBody = $mime->get();
        // construir o cabeçalho do email
        $mailHead = $mime->headers($headers);
        //preparar o email para envio
      	if ($auth=='1' && $this->mailUSER!='' && $this->mailPASS!=''){
            $mail = &Mail::factory('smtp',array ('host' => $this->maillHOST,'auth' => true,'username' => $this->mailUSER,'password' => $this->mailPASS));
	} else {
            $mail = &Mail::factory('smtp',array ('host' => $this->maillHOST,'auth' => false));
	}
        // Enviar o email para o endereço indicado
        $mail->send($to, $mailHead, $mailBody);
        if (PEAR::isError($mail)) {
            $this->mailERROR = 'Houve erro no envio do email, DEBUGAR!! '.$mail->getMessage();
            return false;
        } else {
            return true;
        }
    } //fim do método sendCanc
    

    /**
     * validEmailAdd
     * Função de validação dos endereços de email
     * 
     * @package NFePHP
     * @name validEmailAdd
     * @version 1.00
     * @author  Douglas Lovell <http://www.linuxjournal.com/article/9585>
     * @param string $email Endereço de email a ser testado
     * @return boolean True se endereço é verdadeiro ou false caso haja algum erro 
     */
    public function validEmailAdd($email){
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex){
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64){
                // o endereço local é muito longo
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255){
                // o comprimento da parte do dominio é muito longa
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
                // endereço local inica ou termina com ponto
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)){
                // endereço local com dois pontos consecutivos
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
                // caracter não valido na parte do dominio
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // parte do dominio tem dois pontos consecutivos
                $isValid = false;
            } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
                // caracter não valido na parte do endereço
                if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))){
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
                // dominio encontrado no DNS
                $isValid = false;
            }
         }
        return $isValid;
    } //fim função validEmailAdd
    
}//fim classe MailNFePHP
?>
