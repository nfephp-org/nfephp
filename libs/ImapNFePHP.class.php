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
 * @version   0.1.0
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
    protected $imapaction = 'del'; //del ou mov
    protected $imapnewfolder = ''; //essa pasta já deve existir na caixa postal
    
    private $imapchange = false;// marca indica se houve modificações na caixa postal a serem atualizadas
    private $imapmod = false; //indica se o modulo imap está ativado no php
    
    /**
     * Construtor da classe
     * @param string $host url do servidor
     * @param string $user nome do usuário geralmente o prórpio endereço de email
     * @param string $pass password
     * @param string $port porta para a conexão usuaçmente 143 ou 993
     * @param string $protocol protocolo de comunicação, usualmente imap, mas pode ser pop3 ou nntp
     * @param string $security segurança do servidor usualmente ssl, ou tls ou nenhum notls
     * @param string $validcerts caso o servidor possua um certifica assinado por ele mesmo
     *                           use novalidate-cert, caso contrario use validate-cert
     * @param string $imapfolder pasta imap que será consultada, normalmente INBOX
     * @param string $downfolder pasta onde serão colocados os xml baixados
     * @param string $action del as mensagens serão deletadas ou mov as mensagens serão apenas movidas para outra pasta
     * @param string $newfolder nova pasta da caixa postal para onde deverão ser movidas as mensagens 
     */
    public function __construct(
        $host = '',
        $user = '',
        $pass = '',
        $port = '',
        $protocol = '',
        $security = '',
        $validcerts = '',
        $imapfolder = '',
        $downfolder = '',
        $action = '',
        $newfolder = ''
    ) {
        $this->setHost($host);
        $this->setUser($user);
        $this->setPass($pass);
        $this->setPort($port);
        $this->setProtocol($protocol);
        $this->setSecurity($security);
        $this->setValidCerts($validcerts);
        $this->setImapfolder($imapfolder);
        $this->setDownfolder($downfolder);
        $this->setImapAction($action);
        $this->setImapNewFolder($newfolder);
        $this->checkImapModule();
        $this->mboxExpression();
    }//fim construct
    
    /**
     * destrutor da classe
     */
    public function __destruct()
    {
        if ($this->imapconn != false) {
            if ($this->imapchange) {
                imap_expunge($this->imapconn);
            }
            imap_close($this->imapconn);
        }
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
    
    public function setImapfolder($str)
    {
        if ($str != '') {
            $this->imapfolder = $str;
        }
    }
    
    public function getImapfolder()
    {
        return $this->imapfolder;
    }
    
    public function setDownfolder($str)
    {
        if ($str != '') {
            if (is_dir($str)) {
                $this->downfolder = $str;
            }
        }
    }
    
    public function setImapAction($str)
    {
        if ($str == 'del' || $str == 'mov') {
            $this->imapaction = $str;
        } else {
            $this->imapaction = 'del';
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
    
    public function getDownfolder()
    {
        return $this->downfolder;
    }
    
    public function getMbox()
    {
        return $this->mbox;
    }
    
    public function getImapError()
    {
        return $this->imaperror;
    }
    
    /**
     * Monta expressão para conexão imap
     */
    protected function mboxExpression()
    {
        if ($this->imapmod && $this->host != '' && $this->port != '' && $this->imapfolder != '' && $this->downfolder != '') {
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
     * @param string $host url do servidor
     * @param string $user nome do usuário geralmente o prórpio endereço de email
     * @param string $pass password
     * @param string $port porta para a conexão usuaçmente 143 ou 993
     * @param string $protocol protocolo de comunicação, usualmente imap, mas pode ser pop3 ou nntp
     * @param string $security segurança do servidor usualmente ssl, ou tls ou nenhum notls
     * @param string $validcerts caso o servidor possua um certifica assinado por ele mesmo
     *                           use novalidate-cert, caso contrario use validate-cert
     * @param string $imapfolder pasta imap que será consultada, normalmente INBOX
     * @param string $downfolder pasta onde serão colocados os xml baixados
     * @param string $action del as mensagens serão deletadas ou mov as mensagens serão apenas movidas para outra pasta
     * @param string $newfolder nova pasta da caixa postal para onde deverão ser movidas as mensagens 
     * @return boolean true sucesso ou false fracasso, nesse caso consulte a variável imaperror
     */
    public function imapConnect(
        $host = '',
        $user = '',
        $pass = '',
        $port = '',
        $protocol = '',
        $security = '',
        $validcerts = '',
        $imapfolder = '',
        $downfolder = '',
        $action = '',
        $newfolder = ''
    ) {
        if ($this->imapconn !== false) {
            return true;
        }
        $this->setHost($host);
        $this->setUser($user);
        $this->setPass($pass);
        $this->setPort($port);
        $this->setProtocol($protocol);
        $this->setSecurity($security);
        $this->setValidCerts($validcerts);
        $this->setImapfolder($imapfolder);
        $this->setDownfolder($downfolder);
        $this->setImapAction($action);
        $this->setImapNewFolder($newfolder);
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
     * @param array $response retorna array com a lista de mensagens 
     * @return boolean
     */
    public function imapGetXmlFiles(&$response = '')
    {
        $response = array();
        if ($this->imapConnect()) {
            $qtd = @imap_num_msg($this->imapconn);
            for ($nMsg = 1; $nMsg <= $qtd; $nMsg++) {
                $uid = @imap_uid($this->imapconn, $nMsg);
                $aResults = @imap_fetch_overview($this->imapconn, $uid, FT_UID);
                foreach ($aResults as $message) {
                    $msgno = $message->msgno;
                    $delete = $this->downXML($msgno, $aAtt);
                    //comando de deleção
                    $response[]['deleted'] = $delete;
                    $response[]['from'] = $message->from;
                    $response[]['subject'] = $message->subject;
                    $response[]['date'] = $message->date;
                    $response[]['attachments'] = $aAtt;
                    if ($delete) {
                        if ($this->imapaction == 'del') {
                            if (imap_delete($this->imapconn, $message->uid, FT_UID)) {
                                $this->imapchange = true;
                            } else {
                                $this->imaperror .= imap_last_error();
                            }
                        } else {
                            if (imap_mail_move($this->imapconn, "$msgno:$msgno", $this->imapnewfolder)) {
                                $this->imapchange = true;
                            } else {
                                $this->imaperror .= imap_last_error();
                            }
                        }
                    }
                }//fim foreach message
            }//fim for $qtd
        }//fim imapConnect
        return true;
    }//fim imapGet
    
    /**
     * Executa o download propriamente dito do xml anexado ao email
     * @param string $msgno numero da mensagem
     * @param array $aAtt array com os dados dos anexos e dos resultados do download
     * @return boolean $delete indica se a mensagem deve ser deixada ou (movida ou deletada)
     */
    protected function downXML($msgno, &$aAtt)
    {
        $aArqs = $this->imapAttachments($this->imapconn, $msgno);
        $delete = true;
        foreach ($aArqs as $arq) {
            $aAtt = array();
            if ($arq['is_attachment'] == false) {
                continue; //foreach $arq
            }
            $attachname = strtolower($arq['filename']);
            if (substr($attachname, -3) != "xml") {
                //tem anexo mas não é um xml, então marcar para deleção
                $aAtt[]['attachments'] = $attachname;
                $aAtt[]['download'] = false;
                continue; //foreach $arq
            }
            $filename = date('Ymd').$msgno.str_replace(' ', '_', $attachname);
            $content = str_replace(array("\n","\r","\t"), "", $arq['attachment']);
            $fileH = fopen($this->downfolder.DIRECTORY_SEPARATOR.$filename, "w");
            if (fwrite($fileH, $content)) {
                fclose($fileH);
                @chmod($this->downfolder.DIRECTORY_SEPARATOR.$filename, 0755);
                //arquivo xml salvo com sucesso, então marcar para deleção
                $aAtt[]['attachments'] = $attachname;
                $aAtt[]['download'] = true;
            } else {
                //como não foi possivel fazer o download manter o email
                $this->imaperror .= 'Falha ao tentar gravar o xml.';
                $delete = false;
            }
        }//fim foreach $arq
        return $delete;
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
