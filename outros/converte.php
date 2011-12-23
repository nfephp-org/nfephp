<?php
require("txt2xml.class.php");
$teste = new NFeTxt2Xml("nfe2.txt");
print $teste->getXML();
?>