<?php
/**
 * TESTE_CORRECAO_CTE
 * @author João Eduardo Silva Corrêa
 * @email jcorrea2 at gmail dot com  
 * @data 18/08/2014
 * @package NFe
 */

require_once ('../libs/CTeNFePHP.class.php');

$cte = new CTeNFePHP;

$dados = array(
array('grupo' => 'exped', 'campo' => 'CNPJ', 'valor' => '09603630000559'),
array('grupo' => 'exped', 'campo' => 'IE', 'valor' => '304057490113'),
array('grupo' => 'exped', 'campo' => 'xNome', 'valor' => 'RVS COMERCIO EXTERIOR E LOGISTICA LTDA'),
array('grupo' => 'enderExped', 'campo' => 'xLgr', 'valor' => 'Avenida Gandolfi'),
array('grupo' => 'enderExped', 'campo' => 'nro', 'valor' => 'SN'),
array('grupo' => 'enderExped', 'campo' => 'xBairro', 'valor' => 'Zona Rural'),
array('grupo' => 'enderExped', 'campo' => 'cMun', 'valor' => '3515509'),
array('grupo' => 'enderExped', 'campo' => 'xMun', 'valor' => 'FERNANDOPOLIS'),
array('grupo' => 'enderExped', 'campo' => 'CEP', 'valor' => '15600000')
);

$ret = $cte->envCCe('35140700308337000322570020000006831291404461',$dados,'3','2');

print_r($ret);
?>