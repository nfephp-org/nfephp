<?php

require_once('../libs/ImapNFePHP.class.php');

//use ImapNFePHP;

$mbox = new ImapNFePHP;

$mbox->setHost('imap.gamil.com');
$mbox->setPort('993');
$mbox->setProtocol('imap');
$mbox->setSecurity('ssl');
$mbox->setValidCerts('novalidate-cert');
$mbox->setImapfolder('INBOX');
$mbox->setDownfolder('../exemplos');
$mbox->setUser('seunome@gmail.com');
$mbox->setPass('passW0rd');
if (!$mbox->imapConnect()) {
    echo $mbox->getImapError().'<br>';
    echo $mbox->getMbox();
    exit();
}
if ($mbox->imapGetXmlFiles($retorno)) {
    echo 'Mensagens processadas';
} else {
    echo 'Error'.$mbox->getImapError().'<br>';
}
$mbox->imapDisconnect();
$mbox = null;
