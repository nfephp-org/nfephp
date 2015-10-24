<?php

	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST['conteudoArquivo']) || strlen(trim($_POST['conteudoArquivo'])) > 0){

		require_once('../../libs/NFe/ToolsNFePHP.class.php');
		$nfe = new ToolsNFePHP;
		$modSOAP = '2'; //usando cURL

		//obter um numero de lote
		$lote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
		// montar o array com a NFe
		$sNFe = $_POST['conteudoArquivo'];
		//array vazio passado como referencia
		$aResp = array();

		//enviar o lote
		if ($resp = $nfe->autoriza($sNFe, $lote, $aResp)) {
		    if ($aResp['bStat']) {
		        echo("Nota transmitida com sucesso! Utilize o número [" . $aResp['infRec']['nRec'] . "] do recibo para obter o protocolo ou informações de erro no xml com testaRecibo.php.");
		    } else {
		        echo('ERRO: ' . $nfe->errMsg);
		    }
		} else {
		    echo('ERRO: ' . $nfe->errMsg);
		}
		echo('---------- BEGIN - SOAP DEBUG ----------');
		echo($nfe->soapDebug);
		echo('----------- END - SOAP DEBUG -----------');

		die();
	}

	echo('ERRO: O conteúdo está vazio!');

?>