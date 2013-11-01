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
 * Está atualizada para :
 *      PHP 5.3
 *      Versão 2 dos webservices da SEFAZ com comunicação via SOAP 1.2
 *      e conforme Manual de Integração Versão 5
 *
 *
 * @package   NFePHP
 * @name      ImapNFePHP
 * @version   0.0.1
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2013 &copy; NFePHP
 * @link      http://www.nfephp.org/
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
    protected $mbox = '';
    protected $host = '';
    protected $user = '';
    protected $pass = '';
    protected $port = '';//143, 993
    protected $protocol = ''; //imap, pop3, nntp
    protected $security = ''; //tls, notls, ssl
    protected $validcerts = ''; //validate-cert, novalidate-cert
    protected $imapfolder = 'INBOX'; //INBOX
    protected $downfolder = '';
    
    private $imapchange = false;
    
    public function __contruct(
        $host = '',
        $user = '',
        $pass = '',
        $port = '',
        $protocol = '',
        $security = '',
        $validcerts = '',
        $imapfolder = '',
        $downfolder = ''
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
        $this->mboxExpress();
    }//fim construct
    
    public function __destruct()
    {
        if ($this->imapconn != false) {
            if ($this->imapchange) {
                imap_expunge($this->imapconn);
            }
            imap_close($this->imapconn);
        }
    }
    
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
            if ($str != 'imap' || $str != 'pop3' || $str != 'nntp') {
                $this->protocol = '';
            } else {
                $this->protocol = $str;
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
            if ($str != 'ssl' || $str != 'tls' || $str != 'notls') {
                $this->security = '';
            } else {
                $this->security = $str;
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
            if ($str != 'novalidate-cert' || $str != 'validate-cert') {
                $this->validcerts = '';
            } else {
                $this->validcerts = $str;
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
    
    public function getDownfolder()
    {
        return $this->downfolder;
    }
    
    public function getMbox()
    {
        return $this->mbox;
    }

    protected function mboxExpress()
    {
        if ($this->host != '' && $this->port != '' && $this->imapfolder != '' && $this->downfolder != '') {
            $tProtocol = ($this->protocol != '') ? '/'. $this->protocol : '';
            $tSecurity = ($this->security != '') ? '/'. $this->security : '';
            $tValidcerts = ($this->validcerts != '') ? '/'. $this->validcerts : '';
            $this->mbox = "{".$this->host.":".$this->port.$tProtocol.$tSecurity.$tValidcerts."}".$this->imapfolder;
        } else {
            $this->mbox = '';
        }
    }
    
    public function imapConnect(
        $host = '',
        $user = '',
        $pass = '',
        $port = '',
        $protocol = '',
        $security = '',
        $validcerts = '',
        $imapfolder = '',
        $downfolder = ''
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
        if ($this->imapconn === false) {
            $this->mboxExpress();
            if ($this->mbox != '') {
                $this->imapconn = imap_open($this->mbox, $this->user, $this->pass);
                if ($this->imapconn !== false) {
                    //sucesso
                    return true;
                } else {
                    //fracasso
                    $this->imaperror = imap_last_error();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }//fim connect

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
    
    public function imapGetMessages()
    {
        if ($this->imapConnect()) {
            $qtde = @imap_num_msg($this->imapconn);
            for ($nMsg = 1; $nMsg <= $qtde; $nMsg++) {
                $uid = @imap_uid($this->imapconn, $nMsg);
                $aResults = @imap_fetch_overview($this->imapconn, $uid, FT_UID);
                foreach ($aResults as $message) {
                    $msgno = $message->msgno;
                    $aArqs = $this->imapAttachments($this->imapconn, $msgno);
                    foreach ($aArqs as $arq) {
                        if ($arq['is_attachment'] == true) {
                            $attachname = strtolower($arq['filename']);
                            if (substr($attachname, -3) != "xml") {
                                //marcar para deleção
                                imap_delete($this->imapconn, $message->uid, FT_UID);
                                $this->imapchange = true;
                                continue;
                            }
                            $filename = date('Ymd').$msgno.str_replace(' ', '_', $attachname);
                            $content = str_replace(array("\n","\r","\t"), "", $arq['attachment']);
                            $fileH = fopen($this->downfolder.DIRECTORY_SEPARATOR.$filename, "w");
                            if (fwrite($fileH, $content)) {
                                fclose($fileH);
                                @chmod($this->downfolder.DIRECTORY_SEPARATOR.$filename, 0755);
                                imap_mail_move($this->imapconn, "$msgno:$msgno", "NFe");
                                imap_delete($this->imapconn, $message->uid, FT_UID);
                                $this->imapchange = true;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }//fim imapGet
    
    protected function imapAttachments($connection, $message_number)
    {
        $attachments = array();
        $structure = imap_fetchstructure($connection, $message_number);
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
                    $attachments[$iCount]['attachment'] = imap_fetchbody($connection, $message_number, $iCount+1);
                    if ($structure->parts[$iCount]->encoding == 3) { // 3 = BASE64
                        $attachments[$iCount]['attachment'] = base64_decode($attachments[$iCount]['attachment']);
                    } elseif ($structure->parts[$iCount]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$iCount]['attachment'] = quoted_printable_decode(
                            $attachments[$iCount]['attachment']
                        );
                    }
                }
            }
        }
        return $attachments;
    }//fim
}//fim classe
