<?php

$chave = '3510115955265300012655001000000184173562674';
echo $chave . '' . calcula_dv($chave);

function calcula_dv($chave43) {
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
