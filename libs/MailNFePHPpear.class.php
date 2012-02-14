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
 * @version   2.25
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright 2009-2012 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto.machado@superig.com.br>
 * 
 *          CONTRIBUIDORES (em ordem alfabetica):
 *              Carlos Eduardo Pacheco <eduardo at onlyone dot com dot br>
 *              João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
 *              Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 *              Rodrigo W Cardoso <rodrigogepem at gmail dot com>
 * 
 *
 * NOTA: Esta classe requer a instalação do pacote pear Mail e suas dependencias
 * para isso instale php-pear e em seguida o comando 
 * pear install --alldeps Mail (testado em Linux Debian e derivados), 
 * haverão problemas na configuração em ambiente windows, devido a aos caminhos das pastas.
 * NÃO É RECOMENDADO O USO DESSA CLASSE
 * 
 * Esta classe presume que será usada a mesma conta de email para o envio e recebimento das NFe
 */
//define o caminho base da instalação do sistema
if (!defined('PATH_ROOT')) {
   define('PATH_ROOT', dirname(dirname( __FILE__ )) . DIRECTORY_SEPARATOR);
}
require_once('Mail/Mail.php');
require_once('Mail/mime.php');

class MailNFePHP {

    public $mailAuth='1';
    public $mailFROM='';
    public $mailHOST='';
    public $mailUSER='';
    public $mailPASS='';
    public $mailERROR='';
    public $mailIMAPhost = '';
    public $mailIMAPport = '143';
    public $mailIMAPsecurity = 'tls';
    public $mailIMAPnocerts = 'novalidate-cert';
    public $mailIMAPbox = 'INBOX';
    public $recebidasDir ='';
    public $CNPJ='';


    function __construct($aConfig=''){
        $this->mailERROR='';
        if (is_array($aConfig)){
            $this->mailAuth  = $aConfig['mailAuth'];
            $this->mailFROM  = $aConfig['mailFROM'];
            $this->mailHOST = $aConfig['mailHOST'];
            $this->mailUSER  = $aConfig['mailUSER'];
            $this->mailPASS  = $aConfig['mailPASS'];
            $this->mailIMAPhost = $aConfig['mailIMAPhost'];
            $this->mailIMAPport = $aConfig['mailIMAPport'];
            $this->mailIMAPsecurity = $aConfig['mailIMAPsecurity'];
            $this->mailIMAPnocerts = $aConfig['mailIMAPnocerts'];
            $this->mailIMAPbox = $aConfig['mailIMAPbox'];
            $this->recebidasDir = $aConfig['recebidasDir'];
            $this->CNPJ = $aConfig['cnpj'];            
        } else {
            if ( is_file(PATH_ROOT.'config/config.php') ){
                include(PATH_ROOT.'config/config.php');
                $this->mailAuth  = $mailAuth;
                $this->mailFROM  = $mailFROM;
                $this->mailHOST = $mailHOST;
                $this->mailUSER  = $mailUSER;
                $this->mailPASS  = $mailPASS;
                $this->mailIMAPhost = $mailIMAPhost;
                $this->mailIMAPport = $mailIMAPport;
                $this->mailIMAPsecurity = $mailIMAPsecurity;
                $this->mailIMAPnocerts = $mailIMAPnocerts;
                $this->mailIMAPbox = $mailIMAPbox;
                if ($ambiente == 1){
                    $caminho = '/producao/recebidas';
                } else {
                    $caminho = '/homologacao/recebidas';
                }
                $this->recebidasDir = "$arquivosDir$caminho";
                $this->CNPJ = $cnpj;                
            }
        }     
    }

    /**
     * enviaMail 
     * Função de envio de emails da NFe a partir dos endereços de email inclusos no próprio xml
     *
     * @package NFePHP
     * @name    enviaMail
     * @version 1.00
     * @author  Roberto L. Machado <linux.rlm at gmail dot com>
     * @param   string $filename passar uma string com o caminho completo para o arquivo XML
     * @return  boolean TRUE sucesso ou FALSE falha
     */
    public function enviaMail($filename=''){
        if(is_file($filename)){
            $retorno = true;
            //quebra o path em um array usando o separador / ou \
            $aFile = explode(DIRECTORY_SEPARATOR,$filename);
            $nomeXML = $aFile[count($aFile)-1];
            print_r($aFile).'<BR><BR><BR>';
            $docXML = file_get_contents($filename);
            $dom = new DomDocument;
            $dom->loadXML($docXML);
            $ide        = $dom->getElementsByTagName("ide")->item(0);
            $emit       = $dom->getElementsByTagName("emit")->item(0);
            $dest       = $dom->getElementsByTagName("dest")->item(0);
            $obsCont    = $dom->getElementsByTagName("obsCont");
            $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
            $razao      = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
            $numero     = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
            $serie      = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
            $emitente   = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
            $vtotal     = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
            $email = array();
            //buscar emails
            $emailaddress = !empty($dest->getElementsByTagName("email")->item(0)->nodeValue) ? utf8_decode($dest->getElementsByTagName("email")->item(0)->nodeValue) : '';
            if (strtoupper(trim($emailaddress)) == 'N/D' || strtoupper(trim($emailaddress)) == ''){
                $emailaddress = '';
            } else {
                $emailaddress = trim($emailaddress);
                $emailaddress = str_replace(';',',',$emailaddress);
                $emailaddress = str_replace(':',',',$emailaddress);
                $emailaddress = str_replace('/',',',$emailaddress);
                $email[] = explode(',', $emailaddress);
            }      
            if (isset($obsCont)){
                $i = 0;
                foreach($obsCont as $obs){
                    $campo =  $obsCont->item($i)->getAttribute("xCampo");
                    $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                    if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                        $xTexto = str_replace(';',',',$xTexto);
                        $xTexto = str_replace(':',',',$xTexto);
                        $xTexto = str_replace('/',',',$xTexto);
                        $aTexto = explode(',', $xTexto);
                        foreach ($aTexto as $t){
                            $email[] = $t;
                        }    
                    }
                    $i++;
                } //foreach($obsCont
            }//fim if (isset($obsCont))
            $aMail['contato'] = $razao;
            $aMail['razao'] = $razao;
            $aMail['numero'] = $numero;
            $aMail['serie'] = $serie;
            $aMail['emitente'] = $emitente;
            $aMail['vtotal'] = $vtotal;
            //para cada endereço de email encontrado na NFe
            foreach($email as $mail){
                $aMail['para'] = $mail;
                if ( !$this->sendNFe($docXML,'',$nomeXML,'',$aMail,'1') ){
                    $this->mailERROR .= 'Falha ao enviar para '.$mail.'!! ';
                    $retorno = false;
                }
            } //fim foreach
            
        } //fim if(is_file(
        return $retorno;
    }//fim enviaMail
    
   
    /**
     * sendNFe
     * Função para envio da NF-e por email usando as classes Mail::Pear
     *
     * @package NFePHP
     * @name sendNFe
     * @version 2.14
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
            $this->mailERROR .= 'O endereço informado não é valido! '.$aMail['para'];
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
            $mail = &Mail::factory('smtp',array ('host' => $this->mailHOST,'auth' => true,'username' => $this->mailUSER,'password' => $this->mailPASS));
	} else {
            $mail = &Mail::factory('smtp',array ('host' => $this->mailHOST,'auth' => false));
	}
        // Enviar o email para o endereço indicado
        $mail->send($to, $mailHead, $mailBody);
        if (PEAR::isError($mail)) {
            $this->mailERROR .= 'Houve erro no envio do email, DEBUGAR!! '.$mail->getMessage();
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
     * @version 1.02
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
            $this->mailERROR .= 'O endereço informado não é valido! '.$aMail['para'];
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
            $mail = &Mail::factory('smtp',array ('host' => $this->mailHOST,'auth' => true,'username' => $this->mailUSER,'password' => $this->mailPASS));
	} else {
            $mail = &Mail::factory('smtp',array ('host' => $this->mailHOST,'auth' => false));
	}
        // Enviar o email para o endereço indicado
        $mail->send($to, $mailHead, $mailBody);
        if (PEAR::isError($mail)) {
            $this->mailERROR .= 'Houve erro no envio do email, DEBUGAR!! '.$mail->getMessage();
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
     * @version 1.02
     * @author  Douglas Lovell <http://www.linuxjournal.com/article/9585>
     * @param string $email Endereço de email a ser testado, podem ser passados vários endereços separados por virgula 
     * @return boolean True se endereço é verdadeiro ou false caso haja algum erro 
     */
    public function validEmailAdd($email){
        $isValid = true;
        $aMails = explode(',', $email);
        foreach($aMails as $email){
            $atIndex = strrpos($email, "@");
            if (is_bool($atIndex) && !$atIndex){
                $this->mailERROR .= "$email - Isso não é um endereço de email.\n";
                $isValid = false;
            } else {
                $domain = substr($email, $atIndex+1);
                $local = substr($email, 0, $atIndex);
                $localLen = strlen($local);
                $domainLen = strlen($domain);
                if ($localLen < 1 || $localLen > 64){
                    // o endereço local é muito longo
                    $this->mailERROR .= "$email - O endereço é muito longo.\n";
                    $isValid = false;
                } else if ($domainLen < 1 || $domainLen > 255){
                    // o comprimento da parte do dominio é muito longa
                    $this->mailERROR .= "$email - O comprimento do dominio é muito longo.\n";
                    $isValid = false;
                } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
                    // endereço local inicia ou termina com ponto
                    $this->mailERROR .= "$email - Parte do endereço inicia ou termina com ponto.\n";
                    $isValid = false;
                } else if (preg_match('/\\.\\./', $local)){
                    // endereço local com dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do endereço tem dois pontos consecutivos.\n";
                    $isValid = false;
                } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
                    // caracter não valido na parte do dominio
                    $this->mailERROR .= "$email - Caracter não válido na parte do domínio.\n";
                    $isValid = false;
                } else if (preg_match('/\\.\\./', $domain)) {
                    // parte do dominio tem dois pontos consecutivos
                    $this->mailERROR .= "$email - Parte do domínio tem dois pontos consecutivos.\n";
                    $isValid = false;
                } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
                    // caracter não valido na parte do endereço
                    if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))){
                        $this->mailERROR .= "$email - Caracter não válido na parte do endereço.\n";
                        $isValid = false;
                    }
                }
                if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
                    // dominio não encontrado no DNS
                    $this->mailERROR .= "$email - O domínio não foi encontrado no DNS.\n";
                    $isValid = false;
                }
            }
        }
        return $isValid;
    } //fim função validEmailAdd

    /**
     * buscaEmail
     * Método que recupera o XML da NFe do email e o coloca na pasta "recebidas"
     * para posterior validação e aceitação via registro de evento na SEFAZ
     * 1 - caso na caixa postal tenha email com xml de NFe, esse anexo será 
     *     baixado e colocado na pasta "recebidas", e o email será movido para
     *     uma subpasta denominada pelo ANOMES da NFe;
     * 2 - caso na caixa postal tenha email sem anexos, ou os anexos não 
     *     sejam NFe (em xml) o email será simplesmente deletado;
     * 3 - caso na caixa postal tenha email com xml de NFe e não seja possivel 
     *     salvar o xml na pasta do sistema o email não será movido nem deletado    
     *       
     * @package NFePHP
     * @name buscaEmail
     * @version 1.10
     * @author  Roberto L. Machado <linux dot rlm at gmail dot com>
     * @author  Leandro C. Lopez <leandro dot castoldi at gmail dot com> 
     * @return boolean True se verdadeiro ou false caso haja algum erro 
     */
    public function buscaEmail(){
        //abre a conexão IMAP
        $porta = !empty($this->mailIMAPport) ? ':'.$this->mailIMAPport : ':143';
        $security = !empty($this->mailIMAPsecurity) ? '/'.$this->mailIMAPsecurity : '';
        $nocerts = !empty($this->mailIMAPnocerts) ? '/'.$this->mailIMAPnocerts : '';
        $stream = "{".$this->mailIMAPhost.$porta.$security.$nocerts."}".$this->mailIMAPbox;
        $objMail = imap_open($stream, $this->mailUSER, $this->mailPASS);
        if($objMail === false){
            $this->mailERROR = "Falha na conexão IMAP \n";
            break;
        } else {
            //obter a lista de pastas existentes na caixa postal
            $list =  imap_list($objMail, '{'.$this->mailIMAPhost.'}', "*");
            if (is_array($list)) {
                foreach ($list as $val) {
                    $pasta = str_replace(array('{'.$this->mailIMAPhost.'}'.$this->mailIMAPbox.'.','{'.$this->mailIMAPhost.'}'.$this->mailIMAPbox), "", imap_utf7_decode($val));
                    if ($pasta != '') {
                        $folders[] = $pasta;
                    }
                }
            } else {
                $this->mailERROR = "Falha na listagem de pastas imap_list : " . imap_last_error() . "\n";
            }
            sort($folders);
            //obter o total de mensagens na caixa de entrada 
            $qtde = imap_num_msg($objMail);
            for($i = 1; $i <= $qtde; $i++){
                //obter o identificador de cada mensagem
                $uid = imap_uid($objMail, $i);
                //obter o resumo da mensagem
                $result = imap_fetch_overview($objMail, $uid, FT_UID);
                //pegar o numero da mensagem na caixa postal para buscar anexos
                $msgno = $result[0]->msgno;
                //marca sem xml em anexo
                $flagXML = 0;
                //marca xml como não salvo
                $flagNFeSalva = 0;
                //buscar os anexos da mensagem
                $anexos = $this->__getAnexosXML($objMail, $msgno);
                for($j = 0; $j < count($anexos); $j++){
                    $dom = new DOMDocument(); //cria objeto DOM
                    $dom->formatOutput = false;
                    $dom->preserveWhiteSpace = false;
                    $dom->loadXML($anexos[$j], LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                    $NFe = $dom->getElementsByTagName("NFe")->item(0);
                    if(!is_null($NFe)){
                        //é uma NFe 
                        $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
                        $idNFe = str_replace('NFe', '', $infNFe->getAttribute("Id"));
                        $protNFe = $dom->getElementsByTagName("protNFe")->item(0);
                        $dEmi = $dom->getElementsByTagName("dEmi")->item(0)->nodeValue;
                        if (isset($dEmi)){
                            $anomes = substr(str_replace('-', '', $dEmi),0,6);
                        }
                        //verificar se é para a empresa
                        $dest = $dom->getElementsByTagName("dest")->item(0);
                        $destCNPJ = $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue;
                        if($destCNPJ == $CNPJ ){
                            //sim essa NFe é endereçada a nós
                            $flagXML = 1;
                            $nfeXML = $dom->saveXML();
                            $xmlname = $recebidasDir.'/'.$idNFe.'-nfe.xml';
                            //salva o xml na pasta correta
                            if (!file_put_contents($xmlname, $nfeXML)){
                                $this->mailERROR = date("d-m-Y H:i:s").' - NFe - Falha ao salvar o arquivo na pasta. - '.utf8_decode($result[0]->from)."\n";
                            } else {
                                chmod($xmlname, 0755);
                                $flagNFeSalva = 1;
                            }    
                        } //fim if cnpj
                    }//fim if NFe
                }//fim for enexos
                if ($flagNFeSalva == 1){
                    //a mensagem continha uma NFe válida e foi salva 
                    //então mover o email para uma outra pasta $anomes
                    //verificar se já existe a pasta $anomes
                    $flagFolder = 0;
                    foreach($folders as $f){
                        if ($f == $anomes ){
                         $flagFolder = 1;
                        }
                    }
                    if (!$flagFolder){
                        //criar uma pasta $anomes na caixa postal
                        $stream = "{".$this->mailIMAPhost."}".$this->mailIMAPbox.'.'.$anomes;
                        if (imap_createmailbox($objMail,imap_utf7_encode($stream))){
                            $flagFolder = 1;
                            $folders[]=$anomes;
                            sort($folders);
                        }        
                    } else {
                        //ou a pasta já existia ou foi agora criada
                        //para permitir mover a mensagem para esta pasta
                        if (imap_mail_move($objMail, "$msgno:$msgno","$this->mailIMAPbox.$anomes")){
                            //imap_delete($objMail, $result[0]->uid, FT_UID);
                            $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' . $result[0]->from . ' em ' .$result[0]->date . ' Assunto ' . $result[0]->subject . ". Foi movida para a caixa postal [ $anomes ] e o anexo foi salvo na pasta recebidas \n";  
                        }    
                    }
                } else {
                    if ($flagXML == 1){
                        //a mensagem continha um xml de NFe válido mas falhou ao ser salva no diretorio
                        //então manter a mensagem onde está
                        $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' . $result[0]->from . ' em ' .$result[0]->date . ' Assunto ' . $result[0]->subject . ". Foi mantida na caixa postal por falha na gravação do xml \n";  
                    } else {
                        //a mensagem não continha um xml de NFe válido
                        //então marcar para deletar a mensagem da caixa postal 
                        if(imap_delete($objMail, $uid, FT_UID)){                    
                            $this->mailERROR .= 'A mensagem '.$result[0]->msgno . ' enviada por ' . $result[0]->from . ' em ' .$result[0]->date . ' Assunto ' . $result[0]->subject . ". Foi apagada da caixa postal \n";  
                        }    
                    }
                }//fim if
            }//fim for mensagens
            //apaga todas as mensagens marcadas para deleção
            imap_expunge($objMail);
            //fecha a conexão IMAP
            imap_close($objMail);
        }//fim if 
     }//fim buscaMail    

    /**
     * __getAnexosXML
     * Método que extrai os anexos xml do email e os retorna para posterior
     * processamento e arquivo
     * 
     * @package NFePHP
     * @name __getAnexosXML
     * @version 1.11
     * @author  Leandro C. Lopez <leandro dot castoldi at gmail dot com>
     * @author  Roberto L. Machado <linux dot rlm at gmail dot com>
     * @return mixed vazio ou array 
     */
    private function __getAnexosXML($connection, $message_number) {
        $attachments = array();
        $structure = imap_fetchstructure($connection, $message_number);
        if(isset($structure->parts) && count($structure->parts || $structure->disposition == "attachment")){
            if($structure->disposition == "attachment") {
                $n = count($structure->parameters);
            } else {
                $n = count($structure->parts);
            }        
            for($i = 0; $i < $n; $i++){
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'attachment' => ''
                );
                if($structure->parts[$i]->ifdparameters){
                    foreach($structure->parts[$i]->dparameters as $object){
                        if(strtolower($object->attribute) == 'filename'){
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }//fim foreach
                }//fim if
                if($structure->parts[$i]->ifparameters){
                    foreach($structure->parts[$i]->parameters as $object){
                        if(strtolower($object->attribute) == 'name'){
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }//fim foreach
                }//fim if
                if($structure->disposition == "attachment"){
                    $object = $structure->parameters[$i];
                    if(strtolower($object->attribute) == 'name'){
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['filename'] = $object->value;
                        $structure->parts[$i] = $structure; //alteração para não ter que mexer nas linhas abaixo.
                    }
                }//fim if
                if($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);
                    if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    } elseif($structure->parts[$i]->encoding == 4){ // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }//fim if
                }//fim if    
            }//fim for
        }//fim if
        $j = 0;
        for($i = 0; $i < $n; $i++){
            //se o anexo existir e o arquivo tiver no final do seu nome .xml
            if ($attachments[$i]['is_attachment'] && strtolower(substr($attachments[$i]['filename'], -4)) == '.xml') {
                $anexos[$j] = $attachments[$i]['attachment'];
                $j++;
            }
        }
	/*
         Existiam outros tipos de anexo que não conseguiam ser lidos,
	 Anexos interiores de uma mensagem com conteudo escrito,
	 Seus anexos ficavam escondidos atrás de parts->parts->parts,
	 Seus conteudos dentro do fetchbody se escondiam em subpartes da mensagem,
	 Sendo assim, caso o sistema não encontre nenhum anexos ele checa se os anexos 
         estão nessa situação.
         Rodrigo W Cardoso <rodrigogepem at gmail dot com>  
	*/
	if (empty($anexos[0])) {
            $a = 1;
            $k = 1;
            foreach($structure->parts as $part1) {
                foreach($part1->parts as $part2) {
                    $z = 1;
                    foreach($part2->parts as $part3) {
                        foreach($part3->parameters as $object) {
                            if(((strtolower($object->attribute)) == 'name') || ((strtolower($object->attribute)) == 'filename')) {
                                $attachments[$k]['is_attachment'] = true;
                                $attachments[$k]['filename'] = $object->value;
                                $attachments[$k]['parts'] = $a;
                                $attachments[$k]['encoding'] = $part3->encoding;
                                $anexo = ($a).'.'.($z+1);
                                $attachments[$k]['attachment'] = imap_fetchbody($connection, $message_number, $anexo);
                                if($attachments[$k]['encoding'] == 3) { // 3 = BASE64
                                    $attachments[$k]['attachment'] = base64_decode($attachments[$k]['attachment']);
                                } elseif($attachments[$k]['encoding'] == 4){ // 4 = QUOTED-PRINTABLE
                                    $attachments[$k]['attachment'] = quoted_printable_decode($attachments[$k]['attachment']);
                                }//fim if
                                if ($attachments[$k]['is_attachment'] && strtolower(substr($attachments[$k]['filename'], -4)) == '.xml') {
                                    $anexos[$j] = $attachments[$k]['attachment'];
                                    $j++;
                                }//fim if attachments	
                                $z++;
                                $k++;
                            }//fim if attribute
			}//fim foreach parameters
                    }//fim foreach part3
                }//fim foreach part2
                $a++;
            }//fim foreach part1
        }//fim if anexos empty
        return $anexos;
    }//fim __getAnexosXML
   
}//fim classe MailNFePHP
?>
