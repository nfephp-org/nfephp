<?php

$filename = "./certs/plastcert.pem";
$fHandle = fopen($filename, "r");
$cert = fread($fHandle,  filesize($filename));
fclose($fHandle);

$data = openssl_x509_read($cert);

echo "*********************";
echo "<br>";
echo "Parse<br>";
print_r(openssl_x509_parse($cert));
echo "<br>";
function openssl_to_timestamp ($in) {
        $year  = substr($in, 0, 2); /* NOTE: Yes, this returns a two digit year */
        $month = substr($in, 2, 2);
        $day   = substr($in, 4, 2);
        $hour  = substr($in, 6, 2);
        $min   = substr($in, 8, 2);
        $sec   = substr($in, 10, 2);
        return date('Y-m-d',gmmktime($hour, $min, $sec, $month, $day, $year));
}
echo openssl_to_timestamp($data['validTo']);



function staticGet509XCerts($certs, $isPEMFormat=TRUE) {
        if ($isPEMFormat) {
            $data = '';
            $certlist = array();
            $arCert = explode("\n", $certs);
            $inData = FALSE;
            foreach ($arCert AS $curData) {
                if (! $inData) {
                    if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                        $inData = TRUE;
                    }
                } else {
                    if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                        $inData = FALSE;
                        $certlist[] = $data;
                        echo $data.'<BR>';
                        $data = '';
                        continue;
                    }
                    $data .= trim($curData)."\n";
                }
            }
            return $certlist;
        } else {
            return array($certs);
        }
    }

echo "<BR>TESTE<BR><BR><BR>";
$aCert = staticGet509XCerts($cert,TRUE);
//print_r($aCert);

?>
