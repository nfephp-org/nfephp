<?php

require_once('../../libs/Common/ImapNFePHP.class.php');

//use ImapNFePHP;
$debug = false;
$mbox = new ImapNFePHP($debug);

$mbox->setHost('imap.gmail.com');
$mbox->setPort('993');
$mbox->setProtocol('imap');
$mbox->setSecurity('ssl');
$mbox->setValidCerts('novalidate-cert');
$mbox->setImapfolder('INBOX');
$mbox->setImapNewFolder('NFe');
$mbox->setDownfolder('../exemplos');
$mbox->setUser('seunome@gmail.com');
$mbox->setPass('passW0rd');
$mbox->setFileSulfix('XML');
$mbox->setLimitMsgs(3);
$mbox->setImapAction('move');

if (!$mbox->imapConnect()) {
    echo $mbox->getImapError().'<br>';
    echo $mbox->getMbox();
    exit();
}
if ($mbox->imapGetXmlFiles()) {
    echo 'Mensagens processadas<BR>';
    $aMsgs = $mbox->getProcessedMsgs();
    $iCount = 1;
    foreach ($aMsgs as $msg) {
        echo '['.$iCount.'] '.$msg['from'].''.$msg['subject'].''.$msg['date'].'<br>';
        echo '..............'.$msg['action'].'('.$msg['actionmark'].') : Sucesso ['.$msg['success'].']<br>';
        if (is_array($msg['attachments'])) {
            foreach ($msg['attachments'] as $att) {
                echo '_____________________'.$att['attachname'] . ': download [' .$att['download'].']<br>';
            }
        }
        $iCount++;
    }
} else {
    echo 'Error'.$mbox->getImapError().'<br>';
}
$mbox->imapDisconnect();
$mbox = null;
