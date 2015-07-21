<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>GUI de Teste - NFe 3.10</title>
</head>
<body>
	<h1>Detalhes da Nota - NFe 3.10</h1>
	<form action="javascript:void(0);" method="POST">
		<p>Formato: <input type="radio" name="rdFormato" id="rdFormato-0" value="0" placeholder="" checked="cheked"> XML <input type="radio" name="rdFormato" id="rdFormato-1" value="1" placeholder=""> TXT</p>
		<p>Conteúdo do Arquivo:<br /><textarea name="conteudoArquivo" id="conteudoArquivo" rows="10" cols="60"></textarea></p>
		<p><input type="button" name="btnConverterTXT" id="btnConverterTXT" style="display:none;" value="Converter TXT->XML"></p>
		<p><input type="button" name="btnValidarXML" id="btnValidarXML" value="Validar XML"><input type="button" name="btnAssinarXML" id="btnAssinarXML" value="Assinar XML"><input type="button" name="btnTransmitirXML" id="btnTransmitirXML" value="Transmitir XML"></p>
		<p id="crlTxtRetornos">Retornos:<br /><textarea name="txtRetornos" id="txtRetornos" rows="10" cols="60"></textarea></p>
	</form>

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			<?php
				$url = $_SERVER['REQUEST_URI'];
				$i = 0;
				for($i=strlen($url);substr($url, $i,1) != '/';$i--);
				$url = $_SERVER['SERVER_NAME'] . substr($url, 0, $i);
			?>

			//$('#btnConverterTXT').hide();

			$('input:radio[name="rdFormato"]').click(function(){
				if($('input:radio[name="rdFormato"]:checked').val() == "0"){
			        $('#btnConverterTXT').hide();
			        $('#btnValidarXML').show();
			        $('#btnAssinarXML').show();
			        $('#btnTransmitirXML').show();
			        $('#crlTxtRetornos').show();
			    }else{
			    	$('#btnConverterTXT').show();
			    	$('#btnValidarXML').hide();
			    	$('#btnAssinarXML').hide();
			    	$('#btnTransmitirXML').hide();
			    	$('#crlTxtRetornos').hide();
			    }
			});

			$('input[name="btnConverterTXT"]').click(function(){
				$.ajax({
				  method: "POST",
				  url: '//<?php echo($url.'/guiTesteConvertTXT.php'); ?>',
				  data: { conteudoArquivo: $('#conteudoArquivo').val() }
				}).done(function( msg ) {
				    
				    $('#conteudoArquivo').val(msg);

				    if(msg.substr(0, 4) != 'ERRO:'){
				    	$('#rdFormato-0').prop('checked', true);
				    	$('input:radio[name="rdFormato"]').click();
				    }

				});
			});

			$('input[name="btnValidarXML"]').click(function(){
				$.ajax({
				  method: "POST",
				  url: '//<?php echo($url.'/guiTesteValidarXML.php'); ?>',
				  data: { conteudoArquivo: $('#conteudoArquivo').val() }
				}).done(function( msg ) {
				    $('#txtRetornos').val(msg);
				});
			});

			$('input[name="btnAssinarXML"]').click(function(){
				$.ajax({
				  method: "POST",
				  url: '//<?php echo($url.'/guiTesteAssinarXML.php'); ?>',
				  data: { conteudoArquivo: $('#conteudoArquivo').val() }
				}).done(function( msg ) {
					if(msg.substr(0, 4) != 'ERRO:'){
						$('#conteudoArquivo').val(msg);
				    	$('#txtRetornos').val('XML Assinado! Nota Pronta para ser transmitida!');
				    }else{
				    	$('#txtRetornos').val(msg);
				    }
				});
			});

			$('input[name="btnTransmitirXML"]').click(function(){
				$.ajax({
				  method: "POST",
				  url: '//<?php echo($url.'/guiTesteTransmitirXML.php'); ?>',
				  data: { conteudoArquivo: $('#conteudoArquivo').val() }
				}).done(function( msg ) {
					$('#txtRetornos').val(msg);
				});
			});
		});
	</script>

</body>
</html>