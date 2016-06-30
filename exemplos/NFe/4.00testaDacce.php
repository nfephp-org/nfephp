<?php
/**
 * ATENÇÃO : Esse exemplo usa classe PROVISÓRIA que será removida assim que 
 * a nova classe DACCE estiver refatorada e a pasta EXTRAS será removida.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\Extras\Dacce;
use NFePHP\Common\Files\FilesFolders;

$xml = '../xml/proccce.xml';

$aEnd = array(
    'razao' => 'QQ Comercio e Ind. Ltda',
    'logradouro' => 'Rua vinte e um de março',
    'numero' => '200',
    'complemento' => 'sobreloja',
    'bairro' => 'Nova Onda',
    'CEP' => '99999-999',
    'municipio' => 'Onda',
    'UF' => 'MG',
    'telefone' => '33333-3333',
    'email' => 'qq@gmail.com' 
);

$docxml = FilesFolders::readFile($xml);
$dacce = new Dacce($docxml, 'P', 'A4', '../../images/logo.jpg', 'I', $aEnd);
$id = $dacce->chNFe . '-CCE';
$teste = $dacce->printDACCE($id.'.pdf', 'I');
