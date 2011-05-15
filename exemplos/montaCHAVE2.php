<?php

$cUF = '35';    //Código da UF [02] 
$aamm = '1102';     //AAMM da emissão [4]
$cnpj = '58716523000119';     //CNPJ do Emitente [14]
$mod='55';      //Modelo [02]
$serie='001';     //Série [03]
$num='7';       //Número da NF-e [09]
$tpEmis='1';     //forma de emissão da NF-e [01] 1 – Normal – emissão normal; 2 – Contingência FS; 3 – Contingência SCAN; 4 – Contingência DPEC; 5 – Contingência FS-DA 
$cn='';         //Código Numérico [08]
$dv='';         //DV [01]


//ajusta comprimento do numero
$num = str_pad($num, 9, '0',STR_PAD_LEFT);
//calcula codigo numérico aleatório
$cn = geraCN(8);

//monta a chave sem o digito verificador
$chave = "$cUF$aamm$cnpj$mod$serie$num$tpEmis$cn";
//calcula o digito verificador
$dv = calculaDV($chave);

$chave .= $dv;

$n = strlen($chave);


echo 'cUF = '.$cUF.'<BR>';
echo 'AAMM = '.$aamm.'<BR>';
echo 'CNPJ = '.$cnpj.'<BR>';
echo 'MOD = '.$mod.'<BR>';
echo 'SERIE = '.$serie.'<BR>';
echo 'NUM = '.$num.'<BR>';
echo 'tpEmis = '.$tpEmis.'<BR>';
echo 'CODIGO = '.$cn.'<BR>';
echo 'DV = '.$dv.'<BR>';
echo "CHAVE = $chave  [$n]";

function geraCN($length=8){
    $numero = '';    
    for ($x=0;$x<$length;$x++){
        $numero .= rand(0,9);
    }
    return $numero;
}


function calculaDV($chave43) {
    $multiplicadores = array(2,3,4,5,6,7,8,9);
    $i = 42;
    while ($i >= 0) {
        for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
            $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
            $i--;
        }
    }
    $resto = $soma_ponderada % 11;
    if ($resto == '0' || $resto == '1') {
        return 0;
    } else {
        return (11 - $resto);
   }
}


?>
