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
 * @package   NFePHP
 * @name      ImapNFePHP
 * @version   0.2.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2013 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Leandro C. Lopez <leandro dot castoldi at gmail dot com>
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 */
//namespace ImapNFePHP;

class ImapNFePHP
{

    public $imaperror = '';
    
    protected $imapconn = false;
    protected $mbox = '';//{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX por exemplo
    protected $host = '';//imap.gmail.com por exemplo
    protected $user = '';//seumnome@gmail.com por exemplo
    protected $pass = '';//passW0rd por exemplo
    protected $port = '';//143, 993
    protected $protocol = ''; //imap, pop3, nntp
    protected $security = ''; //tls, notls, ssl
    protected $validcerts = ''; //validate-cert, novalidate-cert
    protected $imapfolder = 'INBOX'; //INBOX
    protected $downfolder = '';//../exemplos
    protected $limitmsg = 10;//limite de mensagem a serem processadas de cada vez
    protected $filesulfix = 'xml';//sulfixo do arquivo anexado que desejamos baixar
    protected $imapaction = 'none'; //none, delele ou move
    protected $imapnewfolder = ''; //essa pasta já deve existir na caixa postal
    protected $processedmsgs = array(); //lista de mensagens processadas e os dados do processamento
    
    private $imapchange = false;// marca indica se houve modificações na caixa postal a serem atualizadas
    private $imapmod = false; //indica se o modulo imap está ativado no php
    
    /**
     * Construtor da classe
     * @param boolean $debug ativa o debug do php
     */
    public function __construct($debug = false)
    {
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        $this->checkImapModule();
        
    }//fim construct
    
    /**
     * destrutor da classe
     */
    public function __destruct()
    {
        $this->imapDisconnect();
    }
    
    private function checkImapModule()
    {
        if (!extension_loaded('imap')) {
            $this->imapmod = false;
            $this->imaperror = 'Modulo IMAP não está carregado no PHP';
        } else {
            $this->imapmod = true;
        }
    }
    
    //parametros
    public function setHost($str)
    {
        if ($str != '') {
            $this->host = $str;
        }
    }
    
    public function getHost()
    {
        return $this->host;
    }

    public function setPort($str)
    {
        if ($str != '') {
            $this->port = $str;
        }
    }
    
    public function getPort()
    {
        return $this->port;
    }

    public function setUser($str)
    {
        if ($str != '') {
            $this->user = $str;
        }
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setPass($str)
    {
        if ($str != '') {
            $this->pass = $str;
        }
    }
    
    public function getPass()
    {
        return $this->pass;
    }
   
    public function setProtocol($str)
    {
        if ($str != '') {
            if ($str == 'imap' || $str == 'pop3' || $str == 'nntp') {
                $this->protocol = $str;
            } else {
                $this->protocol = '';
            }
        }
    }
    
    public function getProtocol()
    {
        return $this->protocol;
    }

    public function setSecurity($str)
    {
        if ($str != '') {
            if ($str == 'ssl' || $str == 'tls' || $str == 'notls') {
                $this->security = $str;
            } else {
                $this->security = '';
            }
        }
    }
    
    public function getSecurity()
    {
        return $this->security;
    }
    
    public function setValidCerts($str)
    {
        if ($str != '') {
            if ($str == 'novalidate-cert' || $str == 'validate-cert') {
                $this->validcerts = $str;
            } else {
                $this->validcerts = '';
            }
        }
    }
    
    public function getValidCerts()
    {
        return $this->validcerts;
    }
    
    public function setImapFolder($str)
    {
        if ($str != '') {
            $this->imapfolder = $str;
        }
    }
    
    public function getImapFolder()
    {
        return $this->imapfolder;
    }
    
    public function setDownFolder($str)
    {
        if ($str != '') {
            if (is_dir($str)) {
                $this->downfolder = $str;
            }
        }
    }

    public function getDownFolder()
    {
        return $this->downfolder;
    }
 
    public function setLimitMsgs($str)
    {
        if ($str != '') {
            if (is_numeric($str)) {
                $this->limitmsg = $str;
            }
        }
    }
    
    public function getLimitMsgs()
    {
        return $this->limitmsg;
    }
    
    public function setFileSulfix($str)
    {
        if ($str != '') {
            $this->filesulfix = $str;
        }
    }
    
    public function getFileSulfix()
    {
        return $this->filesulfix;
    }
    
    public function setImapAction($str)
    {
        if ($str == 'delete' || $str == 'move' || $str == 'none') {
            $this->imapaction = $str;
        } else {
            $this->imapaction = 'none';
        }
    }
    
    public function getImapAction()
    {
        return $this->imapaction;
    }
    
    public function setImapNewFolder($str)
    {
        $this->imapnewfolder = $str;
    }
    
    public function getImapNewFolder()
    {
        return $this->imapnewfolder;
    }
    
    public function getMbox()
    {
        return $this->mbox;
    }
    
    public function getImapError()
    {
        return $this->imaperror;
    }
    
    public function getProcessedMsgs()
    {
        return $this->processedmsgs;
    }
    
    /**
     * Monta expressão para conexão imap
     */
    protected function mboxExpression()
    {
        if ($this->imapmod && $this->host != ''
                && $this->port != ''
                && $this->imapfolder != ''
                && $this->downfolder != '') {
            $tProtocol = ($this->protocol != '') ? '/'. $this->protocol : '';
            $tSecurity = ($this->security != '') ? '/'. $this->security : '';
            $tValidcerts = ($this->validcerts != '') ? '/'. $this->validcerts : '';
            $this->mbox = "{".$this->host.":".$this->port.$tProtocol.$tSecurity.$tValidcerts."}".$this->imapfolder;
        } else {
            $this->mbox = '';
        }
    }
    
    /**
     * Estabelece conexão com servidor IMAP
     * 
     * @param string $config array para configuração
     * @return boolean true sucesso ou false fracasso, nesse caso consulte a variável imaperror
     */
    public function imapConnect($config = '')
    {
        if ($this->imapconn !== false) {
            return true;
        }
        if (is_array($config)) {
            $this->makeConfig($config);
        }
        $this->mboxExpression();
        if ($this->mbox != '') {
            $this->imapconn = imap_open($this->mbox, $this->user, $this->pass);
            if ($this->imapconn !== false) {
                //sucesso
                return true;
            } else {
                //fracasso
                $this->imaperror .= imap_last_error();
                return false;
            }
        } else {
            return false;
        }
    }//fim connect

    private function makeConfig($config)
    {
        $this->setHost($config['host']);
        $this->setUser($config['user']);
        $this->setPass($config['pass']);
        $this->setPort($config['port']);
        $this->setProtocol($config['protocol']);
        $this->setSecurity($config['security']);
        $this->setValidCerts($config['validcerts']);
        $this->setImapFolder($config['imapfolder']);
        $this->setDownFolder($config['downfolder']);
        $this->setFileSulfix($config['filesulfix']);
        $this->setImapAction($config['action']);
        $this->setImapNewFolder($config['newfolder']);
        $this->mboxExpression();
    }
    
    /**
     * Finaliza a comunicação IMAP anteriormente iniciada, se houver
     */
    public function imapDisconnect()
    {
        if ($this->imapconn != false) {
            if ($this->imapchange) {
                imap_expunge($this->imapconn);
            }
            imap_close($this->imapconn);
            $this->imapconn = false;
        }
    }
    
    /**
     * Busca por toda a pasta imap da caixa de correio por mensagens contendo arquivos xml
     * anexados, caso existam estes serão baixados para a pasta de download indicada.
     * Todas as mensagens serão removidas da pasta da caixa postal, 
     * aquelas sem anexos em xml imeditamente e as com anexos xml após os mesmos serem 
     * baixados com sucesso
     * @return boolean
     */
    public function imapGetXmlFiles()
    {
        $response = array();
        if ($this->imapConnect()) {
            $qtd = @imap_num_msg($this->imapconn);
            if ($qtd > $this->limitmsg) {
                $max = $this->limitmsg;
            } else {
                $max = $qtd;
            }
            for ($nMsg = 1; $nMsg <= $max; $nMsg++) {
                //verificar cada mensagem por anexos
                $uid = @imap_uid($this->imapconn, $nMsg);
                $aResults = @imap_fetch_overview($this->imapconn, $uid, FT_UID);
                foreach ($aResults as $message) {
                    $msgno = $message->msgno;
                    $actionmark = $this->downFile($msgno, $aAtt);
                    $response[$nMsg-1]['actionmark'] = $actionmark;
                    $response[$nMsg-1]['action'] = $this->imapaction;
                    $response[$nMsg-1]['from'] = $message->from;
                    $response[$nMsg-1]['subject'] = $message->subject;
                    $response[$nMsg-1]['date'] = $message->date;
                    $response[$nMsg-1]['attachments'] = $aAtt;
                    if ($actionmark) {
                        $success = $this->imapAction($msgno, $message->uid);
                    }
                    $response[$nMsg-1]['success'] = $success;
                }//fim foreach message
            }//fim for $qtd
        }//fim imapConnect
        if (isset($response)) {
            $this->processedmsgs = $response;
        }
        return true;
    }//fim imapGet
    
    private function imapAction($msgno, $uid)
    {
        $success = true;
        switch ($this->imapaction) {
            case 'delete':
                if (imap_delete($this->imapconn, $uid, FT_UID)) {
                    $this->imapchange = true;
                } else {
                    $this->imaperror .= imap_last_error();
                    $success = false;
                }
                break;
            case 'move':
                if (imap_mail_move($this->imapconn, "$msgno:$msgno", $this->imapnewfolder)) {
                    $this->imapchange = true;
                } else {
                    $this->imaperror .= imap_last_error();
                    $success = false;
                }
                break;
            case 'none':
                break;
            default:
                break;
        }
        return $success;
    }
    
    /**
     * Executa o download propriamente dito do arquivo anexado ao email
     * @param string $msgno numero da mensagem
     * @param array $aAtt array com os dados dos anexos e dos resultados do download
     * @return boolean $delete indica se a mensagem deve ser deixada ou (movida ou deletada)
     */
    private function downFile($msgno, &$aAtt)
    {
        $aArqs = $this->imapAttachments($this->imapconn, $msgno);
        $actionmark = true;
        $iCount = 0;
        foreach ($aArqs as $arq) {
            if ($arq['is_attachment'] == false) {
                //não tem anexo então marcar para ação
                continue; //foreach $arq
            }
            $attachname = strtolower($arq['filename']);
            if (!$this->fileSulfixCompare($attachname, $this->filesulfix)) {
                //tem anexo mas não tem o sulfixo indicado, então marcar para ação
                $aAtt[$iCount]['attachname'] = $attachname;
                $aAtt[$iCount]['download'] = false;
                $iCount++;
                continue; //foreach $arq
            }
            $filename = date('Ymd').$msgno.str_replace(' ', '_', $attachname);
            //$content = str_replace(array("\n","\r","\t"), "", $arq['attachment']);
            $aAtt[$iCount]['attachname'] = $attachname;
            $content = $arq['attachment'];
            $fileH = fopen($this->downfolder.DIRECTORY_SEPARATOR.$filename, "w");
            if (fwrite($fileH, $content)) {
                fclose($fileH);
                @chmod($this->downfolder.DIRECTORY_SEPARATOR.$filename, 0755);
                //arquivo salvo com sucesso, então marcar para ação
                $aAtt[$iCount]['download'] = true;
                $iCount++;
            } else {
                //como não foi possivel fazer o download manter o email
                $aAtt[$iCount]['download'] = false;
                $this->imaperror .= 'Falha ao tentar gravar o aquivo.';
                $actionmark = false;
            }
        }//fim foreach $arq
        if (!isset($aAtt)) {
            $aAtt = '';
        }
        return $actionmark;
    }
    
    private function fileSulfixCompare($filename, $filesulfix)
    {
        if ($filesulfix == '') {
            return false;
        }
        if ($filesulfix == '*') {
            return true;
        }
        if (is_array($filesulfix)) {
            $aSulf = $filesulfix;
        } else {
            $aSulf = array($filesulfix);
        }
        foreach ($aSulf as $sulfix) {
            $num = (-1 * strlen($sulfix));
            if (substr($filename, $num) == strtolower($sulfix)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Recupera todos os anexos da mensagem
     * @param object $connection
     * @param string $messageNumber
     * @return array com os dados dos anexos
     */
    protected function imapAttachments($connection, $messageNumber)
    {
        $attachments = array();
        $structure = imap_fetchstructure($connection, $messageNumber);
        if (isset($structure->parts) && count($structure->parts)) {
            for ($iCount = 0; $iCount < count($structure->parts); $iCount++) {
                $attachments[$iCount] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
                if ($structure->parts[$iCount]->ifdparameters) {
                    foreach ($structure->parts[$iCount]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$iCount]['is_attachment'] = true;
                            $attachments[$iCount]['filename'] = $object->value;
                        }
                    }
                }
                if ($structure->parts[$iCount]->ifparameters) {
                    foreach ($structure->parts[$iCount]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$iCount]['is_attachment'] = true;
                            $attachments[$iCount]['name'] = $object->value;
                        }
                    }
                }
                if ($attachments[$iCount]['is_attachment']) {
                    $attachments[$iCount]['attachment'] = imap_fetchbody($connection, $messageNumber, $iCount+1);
                    if ($structure->parts[$iCount]->encoding == 3) { // 3 = BASE64
                        $attachments[$iCount]['attachment'] = base64_decode($attachments[$iCount]['attachment']);
                    } elseif ($structure->parts[$iCount]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$iCount]['attachment'] = quoted_printable_decode(
                            $attachments[$iCount]['attachment']
                        );
                    }
                }
            }//fim for
        }
        return $attachments;
    }//fim
}//fim classe
