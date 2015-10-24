<?php

	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST['conteudoArquivo']) || strlen(trim($_POST['conteudoArquivo'])) > 0){
		require_once('../../libs/NFe/ToolsNFePHP.class.php');

		$xsdFile = '../../schemes/PL_008f/nfe_v3.10.xsd';

		if(!is_file($xsdFile)){echo('ERRO: Arquivo não encontrando ['.$xsdFile.']');die();}

		$nfe = new ToolsNFePHP;

		$aErro = array();

		if (!$nfe->validXML($_POST['conteudoArquivo'], $xsdFile, $aErro)) {
		    echo('ERRO: Estrutura do XML da NFe contêm erros | ');
		    echo('Verifique se o arquivo XSD confrontado é o mais atual! | ');
		    foreach ($aErro as $er) {
		        echo $er .' | ';
		    }
		}else{
			echo 'Estrutura do XML da NFe foi VALIDADO!';
		}		

		die();
	}

	echo('ERRO: O conteúdo está vazio!');
?>