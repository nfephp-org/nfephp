<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 * 
 * @package   NFePHP
 * @name      enviaMail
 * @version   1.05
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license   http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <roberto.machado@superig.com.br>
 * 
 */
require_once('../libs/DanfeNFePHP.class.php');
require_once('../libs/MailNFePHPAlternate.class.php');

//inicalizar a classe de envio
$nfeMail = new MailNFePHP();

$nfefile='';
if ( isset($_GET['nfe']) ){
    $nfefile = $_GET['nfe'];
}	
$para = '';

$html = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
    <title>enviaMail</title>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <link rel='stylesheet' type='text/css' href='images/fimatec.css'>
    <script language='javascript'>
    <!--
        setTimeout('self.close();',20000)
    //-->
    </script> 
</head>
<body>";

if ($nfefile != ''){
    //carregar o xml
    $docXML = file_get_contents($nfefile);
    $dom = new DomDocument;
    $dom->loadXML($docXML);
    $ide        = $dom->getElementsByTagName("ide")->item(0);
    $emit       = $dom->getElementsByTagName("emit")->item(0);
    $dest       = $dom->getElementsByTagName("dest")->item(0);
    $obsCont    = $dom->getElementsByTagName("obsCont")->item(0);
    $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);

    $razao = utf8_decode($dest->getElementsByTagName("xNome")->item(0)->nodeValue);
    $cnpj = $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue;
    $numero = str_pad($ide->getElementsByTagName('nNF')->item(0)->nodeValue, 9, "0", STR_PAD_LEFT);
    $serie = str_pad($ide->getElementsByTagName('serie')->item(0)->nodeValue, 3, "0", STR_PAD_LEFT);
    $emitente = utf8_decode($emit->getElementsByTagName("xNome")->item(0)->nodeValue);
    $vtotal = number_format($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ",", ".");
    $email[] = !empty($dest->getElementsByTagName("email")->item(0)->nodeValue) ? utf8_decode($dest->getElementsByTagName("email")->item(0)->nodeValue) : '';
    if (isset($obsCont)){
        foreach ($obsCont as $obs){
            $campo =  $obsCont->item($i)->getAttribute("xCampo");
            $xTexto = !empty($obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($i)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
            if (substr($campo, 0, 5) == 'email' && $xTexto != '') {
                $email[] = $xTexto;
            }
            $i++;
        }
    }
    foreach($email as $e){
        if ($nfeMail->validEmailAdd($e)){
            $mailto[] = $e; 
        } else {
            echo "<p align='center'><b>O e-mail $e Não é um endereço válido, CORRIGIR !!<b></p><BR>";
        } //fimda validaçao
    }            
    echo $html;
    flush();
    //verificar se tem endereços validos
    if ( count($mailto)== 0 ){
        echo "<p align='center'><b>$razao - Não há registro de e-mail para deste cliente.</b></p><BR>";
	flush();
    } else {
	//inicializar a DANFE
	$danfe = new DanfeNFePHP($docXML, 'P', 'A4','images/logo.jpg','I','');
	//montar o PDF e o nome do arquivo PDF
	$nome = $danfe->montaDANFE();
	$nomePDF = $nome . '.pdf';
	$nomeXML = $nome . '-nfe.xml';
	//carregar o arquivo pdf numa variavel
	$docPDF = $danfe->printDANFE($nomePDF,'S');
	//enviar o email e testar
        foreach($mailto as $para){
            //para testes
            //$para = 'roberto.machado@superig.com.br';
            $aMail = array('emitente'=>$emitente,'para'=>$para,'contato'=>$contato,'razao'=>$razao,'numero'=>$numero,'serie'=>$serie,'vtotal'=>$vtotal);
       	    echo "<p align='center'>Enviando e-mail com a NFe N. $numero para $para - $razao </p>" ;
            flush();
            if ( $nfeMail->sendNFe($docXML,$docPDF,$nomeXML,$nomePDF,$aMail,'1') ){
                echo '<p align="center">E-mail enviado com sucesso!! </p><br>';
            } else {
                echo "<p>$nfeMail->mailERROR</p><br>";
            }
        }    
    }//fim dos emails
    echo "<div><center><form method='POST' action=''><p><input type='button' value='Fechar' name='B1' onclick='self.close()'></p></form></center></div>";
    echo "</body></html>";
    flush();
}//fim nfe

?>