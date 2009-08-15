<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('./libs/nusoap/nusoap.php');
require_once('config_inc.php');

$wsdl     = 'https://homologacao.nfe.fazenda.sp.gov.br/nfeweb/services/nfestatusservico.asmx?WSDL';
$xsi      = 'http://www.w3.org/2001/XMLSchema-instance';
$xsd      = 'http://www.w3.org/2001/XMLSchema';
$nfe      = 'http://www.portalfiscal.inf.br/nfe';


?>
