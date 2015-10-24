<?php

namespace NFePHP\install;

require_once('../bootstrap.php');

use NFePHP\Common\Configure\Configure;

$cnpj = filter_input(INPUT_GET, 'cnpj', FILTER_SANITIZE_STRING);
$pathCertsFiles = filter_input(INPUT_GET, 'pathCertsFiles', FILTER_SANITIZE_STRING);
$certPfxName = filter_input(INPUT_GET, 'certPfxName', FILTER_SANITIZE_STRING);
$certPassword = filter_input(INPUT_GET, 'certPassword', FILTER_SANITIZE_STRING);
$certPhrase = filter_input(INPUT_GET, 'certPhrase', FILTER_SANITIZE_STRING);
$cnpj = preg_replace('/[^0-9]/', '', $cnpj);

$aResp = Configure::checkCerts($cnpj, $pathCertsFiles, $certPfxName, $certPassword);

print json_encode($aResp);
