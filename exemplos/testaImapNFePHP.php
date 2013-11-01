<?php

require_once('../libs/ImapNFePHP.class.php');

//use ImapNFePHP;

$mbox = new ImapNFePHP;

$mbox->setHost('imap.gmail.com');
$mbox->setPort('993');
$mbox->setProtocol('imap');
$mbox->setSecurity('ssl');
$mbox->setValidCerts('novalidate-cert');
$mbox->setImapfolder('INBOX');
$mbox->setDownfolder('../exemplos');
$mbox->setUser('user');
$mbox->setPass('pass');

$mbox->imapConnect();

if ($mbox->imapGetMessages()) {
    echo 'Baixadas';
} else {
    echo 'Error';
}

$mbox->imapDisconnect();
$mbox = null;
