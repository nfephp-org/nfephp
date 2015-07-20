<?php

	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST['conteudoArquivo']) || strlen(trim($_POST['conteudoArquivo'])) > 0){

		$fileName = './xml/' . date('YmdHisu') . '-nfe';

		if (!file_put_contents($fileName.'.txt',$_POST['conteudoArquivo'])){
            echo "ERRO: Gravação";die();
        }

        require_once('../../libs/NFe/ConvertNFePHP.class.php');

        $nfe = new ConvertNFePHP();

        $xml = $nfe->nfetxt2xml($fileName.'.txt');
        $xml = $xml[0];

        if ($xml != ''){
	        echo(trim($xml));
	    }

	    unlink($fileName.'.txt');die();
	}

	echo('ERRO: O conteúdo está vazio!');
?>