<?php

namespace NFePHP\install;

require_once('../bootstrap.php');

use NFePHP\Common\Configure\Configure;

$pathnfe = filter_input(INPUT_GET, 'pathNFeFiles', FILTER_SANITIZE_STRING);
$pathcte = filter_input(INPUT_GET, 'pathCTeFiles', FILTER_SANITIZE_STRING);
$pathmdfe = filter_input(INPUT_GET, 'pathMDFeFiles', FILTER_SANITIZE_STRING);
$pathcle = filter_input(INPUT_GET, 'pathCLeFiles', FILTER_SANITIZE_STRING);
$pathnfse = filter_input(INPUT_GET, 'pathNFSeFiles', FILTER_SANITIZE_STRING);
$pathcerts = filter_input(INPUT_GET, 'pathCertsFiles', FILTER_SANITIZE_STRING);

$aResp = Configure::checkFolders($pathnfe, $pathcte, $pathmdfe, $pathcle, $pathnfse, $pathcerts);

print json_encode($aResp);
