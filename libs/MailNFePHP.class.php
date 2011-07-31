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
 * @name      MailNFePHP
 * @version   2.17
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto dot machado at superig dot com dot br>
 *
 *          CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Cristiano Soares <soares dot cr at gmail dot com>
 * 		Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
//carrega as classes do PHPMailer
require_once('PHPMailer/class.phpmailer.php');

class MailNFePHP {

    public $mailAuth='1';
    public $mailFROM='';
    public $mailHOST='';
    public $mailUSER='';
    public $mailPASS='';
    public $mailPORT='';
    public $mailPROTOCOL='';            
    public $mailFROMmail='';
    public $mailFROMname='';
    public $mailREPLYTOmail='';
    public $mailREPLYTOname='';
    public $mailERROR='';
    
    function __construct($aConfig=''){
        if (is_array($aConfig)){
            $this->mailAuth  = $aConfig['mailAuth'];
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
                $this->mailAuth  = $mailAuth;
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
     * sendNFe
     * Função para envio da NF-e por email usando PHPMailer
     *
     * @package NFePHP
     * @name sendNFe
     * @version 2.14
     * @author    Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $docXML arquivo XML, é obrigatório
     * @param string $docPDF DANFE em formato PDF, se não quizer mandar o pdf deixe em branco
     * @param string $nomeXML Nome do arquivo XML, é obrigatório
     * @param string $nomePDF Nome do arquivo PDF
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @param boolean $auth Indica se é necessária a autenticação
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendNFe($docXML='',$docPDF='',$nomeXML='',$nomePDF='',$aMail,$auth='') {
	//se não forem passados os parametros de envio sair
        if (!is_array($aMail)){
            $this->mailERROR = 'Não foram passados parametros de envio!';
	    return false;
	}	
	//retorna se não foi passado o xml
	if ($docXML != '' && $nomeXML != ''){
            $fileXML = PATH_ROOT.$nomeXML;
            //retorna false se houve erro na gravação
            if ( !file_put_contents($fileXML,$docXML) ){
                $this->mailERROR = 'Não foi possivel gravar o XML para envio. Permissão!';
                return false;
            }
	} else {
            $this->mailERROR = 'Não foi passados o XML da NFe para envio. O XML é Obrigatório!';
            return false;
        }
        //validar o endereço de email passado
        if (!$this->validEmailAdd($aMail['para'])){
            $this->mailERROR = 'O endereço informado não é valido! '.$aMail['para'];
            return false;
        }
        if ($auth == '') {
            if (isset($this->mailAuth)){
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
        //se não foi passado o pdf ignorar e só enviar o xml
        if ($docPDF != '' && $nomePDF != ''){
            //salvar temporariamente os arquivo passados
            $filePDF = PATH_ROOT.'pdf-'. number_format(microtime(true)*1000000,0,0,15) .'.pdf';
            file_put_contents($filePDF,$docPDF);
        } else {
            $filePDF = '';
        }
        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - N.$numero - $emitente");
        //mensagem no corpo do email em txt
        $txt = "Prezado Sr(a) $contato,\n\r";
        $txt .= "Você está recebendo a Nota Fiscal Eletrônica número $numero, série $serie de $emitente, no valor de R$ $vtotal. Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.\n\r";
        $txt .= "Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.\n\r";
        $txt .= "Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.\n\r";
        $txt .= "Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.\n\r";
        $txt .= "O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.\n\r";
        $txt .= "Para mais detalhes sobre o projeto, consulte: www.nfe.fazenda.gov.br\n\r";
        $txt .= "Atenciosamente, $emitente \n\r";
        //altera de utf8 para iso
        $txt = utf8_decode($txt);
        //mensagem no corpo do email em html
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
        $htmlMessage = "<body bgcolor='#ffffff'>$msg</body>";
        //enviar o email
        if ( !$result = $this->__sendM($to,$contato,$subject,$txt,$htmlMessage,$fileXML,$filePDF,$auth)){
            //houve falha no envio reportar
            $this->mailERROR = 'Houve erro no envio do email, DEBUGAR!! ' .$this->mailERROR; 
        }
    	//apagar os arquivos salvos temporariamente
        if (is_file($fileXML)){
            unlink($fileXML);
        }
        if (is_file($filePDF)){
            unlink($filePDF);
        }
        return $result; //retorno da função
    } //fim da função sendNFe

    /**
     * sendCanc
     * Função para envio do Cancelamento ad NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendCanc
     * @version 1.02
     * @author  João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
     * @author  Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $docXML Conteúdo do arquivo XML
     * @param string $nomeXML Nome do arquivo XML
     * @param array $aMail Matriz com as informações necessárias para envio do email
     * @return boolean TRUE sucesso ou FALSE falha
     */
    public function sendCanc($docXML='',$nomeXML='',$aMail='',$auth='') {
        //retorna se não foi passada a matriz com os dados de envio
        if (!is_array($aMail)){
            $this->mailERROR = 'Não foram passados parametros de envio!';            
	    return false;
	}
        //retorna se não foi passado o xml
	if ($docXML != '' && $nomeXML != ''){
            $fileXML = PATH_ROOT.$nomeXML;
            //retorna false se houve erro na gravação
            if ( !file_put_contents($fileXML,$docXML) ){
                $this->mailERROR = 'Não foi possivel gravar o XML para envio. Permissão!';                
                return false;
            }
	} else {
            $this->mailERROR = 'Não foi passados o XML da NFe para envio. O XML é Obrigatório!';
            return false;
        }
        if($auth == ''){
            if(isset($this->mailAuth)){
                $auth = $this->mailAuth;
            } else {
                $auth = '1';
            }    
        }
        //validar o endereço de email passado
        if (!$this->validEmailAdd($aMail['para'])){
            $this->mailERROR = 'O endereço informado não é valido! '.$aMail['para'];
            return false;
        }
        $datat = date('d-m-Y H:i:s');
        $to = $aMail['para'];
        $contato = $aMail['contato'];
        $razao = $aMail['razao'];
        $numero = $aMail['numero'];
        $serie = $aMail['serie'];
        $emitente = $aMail['emitente'];
        $chNFe = $aMail['chNFe'];
	if ($contato == ''){
            $contato = $razao;
	} else {
            $contato .= ' - '.$razao;
	}
        // assunto email
        $subject = utf8_decode("NF-e Nota Fiscal Eletrônica - Cancelamento - " . $emitente);
        //mensagem no corpo do email
        $txt = "À $contato,\n\r";
        $txt .= "Em  anexo você está recebendo o XML de cancelamento da Nota Fiscal Eletrônica número $numero, série $serie emitida por $emitente.\n\r";
        $txt .= "A validade deste documento eletrônico pode ser verificada no site nacional do projeto http://www.nfe.fazenda.gov.br\n\r";
        $txt .= "através da chave $chNFe\n\r";
        $txt .= "\n\r\n\rAtenciosamente,$emitente.\n\r";
        $txt .= "\n\r$datat";
        $txt = utf8_decode($txt);
        //mensagem no corpo do email
        $msg = "<p><b>À $contato,</b>";
        $msg .= "<p>Em anexo você está recebendo o XML de cancelamento da Nota Fiscal Eletrônica número $numero, série $serie emitida por $emitente.";
        $msg .= "<p><i>A validade deste documento eletrônico pode ser verificada no site nacional do projeto <a href='http://www.nfe.fazenda.gov.br/portal/Default.aspx'>www.nfe.fazenda.gov.br</a>";
        $msg .= " através da chave $chNFe</i>";
        $msg .= "<p><p>Atenciosamente,<p>$emitente";
        $msg .= "<p><p><i><small>$datat</small></i>";
        //corrige de utf8 para iso
        $msg = utf8_decode($msg);
        // O email será enviado no formato HTML
        $htmlMessage = "<body bgcolor='#ffffff'>$msg</body>";
        //enviar o email
        $filePDF = '';
        if (!$result = $this->__sendM($to,$contato,$subject,$txt,$htmlMessage,$fileXML,$filePDF,$auth)){
            //houve falha no envio reportar
            $this->mailERROR = 'Houve erro no envio do email, DEBUGAR!! '.$this->mailERROR ; 
        }    
        //apagar os arquivos salvos temporariamente
        if (is_file($fileXML)){
            unlink($fileXML);
        }
        return $result; //retorno da função
    } //fim do método sendCanc

    /**
     * __sendM
     * Função de envio do email
     * 
     * @package NFePHP
     * @name __sendM
     * @version 1.00
     * @author    Roberto L. Machado <linux.rlm at gmail dot com>
     * @param string $to            endereço de email do destinatário 
     * @param string $contato       Nome do contato - empresa
     * @param string $subject       Assunto
     * @param string $txt           Corpo do email em txt
     * @param string $htmlMessage   Corpo do email em html
     * @param string $fileXML       path completo para o arquivo xml
     * @param string $filePDF       path completo para o arquivo pdf
     * @param string $auth          Flag da autorização requerida 1-Sim 0-Não
     * @return boolean FALSE em caso de erro e TRUE se sucesso
     */
    private function __sendM($to,$contato,$subject,$txt,$htmlMessage,$fileXML,$filePDF,$auth){
        // o parametro true indica que uma exceção será criada em caso de erro,
        $mail = new PHPMailer(true); 
        // informa a classe para usar SMTP
        $mail->IsSMTP();
        // executa ações
        try {
            $mail->Host       = $this->mailHOST;        // SMTP server
            $mail->SMTPDebug  = 0;                      // habilita debug SMTP para testes
            $mail->Port       = $this->mailPORT;        // Seta a porta a ser usada pelo SMTP
            if ($auth=='1' && $this->mailUSER != '' && $this->mailPASS !=''){
                $mail->SMTPAuth   = true;                   // habilita autienticação SMTP
                if ($this->mailPROTOCOL !=''){
                    $mail->SMTPSecure = $this->mailPROTOCOL;    // "tls" ou "ssl"
                }    
		$mail->Username   = $this->mailUSER;        // Nome do usuários do SMTP
		$mail->Password   = $this->mailPASS;        // Password do usuário SMPT
            } else {
                $mail->SMTPAuth   = false;
            }	
            $mail->AddReplyTo($this->mailREPLYTOmail,$this->mailREPLYTOname); //Indicação do email de retorno
            $mail->AddAddress($to,$contato);            // nome do destinatário
            $mail->SetFrom($this->mailFROMmail,$this->mailFROMname); //identificação do emitente
            $mail->Subject = $subject;                  // Assunto
            $mail->AltBody = $txt;                      // Corpo a mensagem em txt
            $mail->MsgHTML($htmlMessage);               // Corpo da mensagem em HTML
            if (is_file($fileXML)){
                $mail->AddAttachment($fileXML);          // Anexo
            }
            if (is_file($filePDF)){
                $mail->AddAttachment($filePDF);          // Anexo
            }
            $mail->Send();                              // Comando de envio
            $result = TRUE;
        // é necessário buscar o erro       
        } catch (phpmailerException $e) {               // captura de erros
            $this->mailERROR = $e->errorMessage();      //Mensagens de erro do PHPMailer
            $result = FALSE;
        } catch (Exception $e) {
            $this->mailERROR .=  $e->getMessage();      //Mensagens de erro por outros motivos
            $result = FALSE;
        }
        return $result;
    } //fim __sendM
    
    
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

} //fim da classe
?>
