<?php
require_once('../../libs/NFe/ToolsNFePHP.class.php');
$nfe = new ToolsNFePHP;
$nfefile = $nfe->envDir.'35130471780456000160550010000000411000000410-nfe.xml';
$protfile = $nfe->temDir.'35130471780456000160550010000000411000000410-prot.xml';
if ($xml = $nfe->addProt($nfefile, $protfile)){
    file_put_contents($nfe->aprDir.'35130471780456000160550010000000411000000410-procNfe.xml', $xml);
}

?>
