<?php
	
	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST['conteudoArquivo']) || strlen(trim($_POST['conteudoArquivo'])) > 0){

		require_once('../../libs/NFe/ToolsNFePHP.class.php');

		$nfe = new ToolsNFePHP;

		if ($xml = $nfe->signXML($_POST['conteudoArquivo'], 'infNFe')){
			echo(trim($xml));
		} else {
		    echo('ERRO: ' . $nfe->errMsg);
		}

		die();
	}

	echo('ERRO: O conteúdo está vazio!');

?>