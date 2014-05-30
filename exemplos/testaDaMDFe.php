<?php
require_once('../libs/DamdfeNFePHP.class.php');

$arq = 'xml/MDFe41140581452880000139580010000000281611743166.xml';

if (is_file($arq)) {
    $damdfe = new DamdfeNFePHP($arq, 'P', 'A4', '../images/logo.jpg', 'I');
    $teste = $damdfe->printMDFe('teste', 'I');
}
