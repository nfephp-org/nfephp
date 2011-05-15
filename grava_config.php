<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 *
 * @package   NFePHP
 * @name      grava_config.php
 * @version   1.26
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 **/

$texto = "<?\n";
$texto .= "/**\n * Parametros de configuração do sistema\n *\n */\n\n";
$texto .= "//###############################\n";
$texto .= "//########## GERAL ##############\n";
$texto .= "//###############################\n";
$texto .= "// tipo de ambiente esta informação deve ser editada pelo sistema\n";
$texto .= "// 1-Produção 2-Homologação\n";
$texto .= "// esta variável será utilizada para direcionar os arquivos e\n";
$texto .= "// estabelecer o contato com o SEFAZ\n";
$texto .= '$ambiente=' . $_POST['ambiente'] .";\n";
$texto .= "//esta variável contêm o nome do arquivo com todas as url dos webservices do sefaz\n";
$texto .= "//incluindo a versao dos mesmos, pois alguns estados não estão utilizando as\n";
$texto .= "//mesmas versões\n";
$texto .= '$arquivoURLxml="' . $_POST['urlws'] . '"' .";\n";
$texto .= "//Diretório onde serão mantidos os arquivos com as NFe em xml\n";
$texto .= "//a partir deste diretório serão montados todos os subdiretórios do sistema\n";
$texto .= "//de manipulação e armazenamento das NFe\n";
$texto .= '$arquivosDir="' . $_POST['dirnfe'] . '"' .";\n";
$texto .= "//URL base da API, passa a ser necessária em virtude do uso dos arquivos wsdl\n";
$texto .= "//para acesso ao ambiente nacional\n";
$texto .= '$baseurl="' . $_POST['urlapi'] . '"' .";\n";
$texto .= "//Versão em uso dos shemas utilizados para validação dos xmls\n";
$texto .= '$schemes="' . $_POST['schema'] . '"' .";\n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//###### EMPRESA EMITENTE #######\n";
$texto .= "//###############################\n";
$texto .= "//Nome da Empresa\n";
$texto .= '$empresa="' . $_POST['razao'] . '"' .";\n";
$texto .= "//Sigla da UF\n";
$texto .= '$UF="' . $_POST['siglauf'] . '"' .";\n";

$cUFlist = array('AC'=>'12',
                 'AL'=>'27',
                 'AM'=>'13',
                 'AP'=>'16',
                 'BA'=>'29',
                 'CE'=>'23',
                 'DF'=>'53',
                 'ES'=>'32',
                 'GO'=>'52',
                 'MA'=>'21',
                 'MG'=>'31',
                 'MS'=>'50',
                 'MT'=>'51',
                 'PA'=>'15',
                 'PB'=>'25',
                 'PE'=>'26',
                 'PI'=>'22',
                 'PR'=>'41',
                 'RJ'=>'33',
                 'RN'=>'24',
                 'RO'=>'11',
                 'RR'=>'14',
                 'RS'=>'43',
                 'SC'=>'42',
                 'SE'=>'28',
                 'SP'=>'35',
                 'TO'=>'17'
                  );

$texto .= "//Código da UF\n";
$texto .= '$cUF="' . $cUFlist[$_POST['siglauf']] . '"' .";\n";
$texto .= "//Número do CNPJ\n";
$texto .= '$cnpj="' . $_POST['numcnpj'] . '"' .";\n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//#### CERITIFICADO DIGITAL #####\n";
$texto .= "//###############################\n";
$texto .= "//Nome do certificado que deve ser colocado na pasta certs da API\n";
$texto .= '$certName="' . $_POST['pfx'] . '"' .";\n";
$texto .= "//Senha da chave privada\n";
$texto .= '$keyPass="' . $_POST['keysenha'] . '"' .";\n";
$texto .= "//Senha de decriptaçao da chave, normalmente não é necessaria\n";
$texto .= '$passPhrase="' . $_POST['passe'] . '"' .";\n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ DANFE ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração do DANFE\n";
$texto .= '$danfeFormato="' . $_POST['formato'] . '"' ."; //P-Retrato L-Paisagem \n";
$texto .= '$danfePapel="' . $_POST['papel'] . '"' ."; //Tipo de papel utilizado \n";
$texto .= '$danfeCanhoto=' . $_POST['canhoto'] . '' ."; //se verdadeiro imprime o canhoto na DANFE \n";
$texto .= '$danfeLogo="' . $_POST['logo'] . '"' ."; //passa o caminho para o LOGO da empresa \n";
$texto .= '$danfeLogoPos="' . $_POST['logopos'] . '"' ."; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta \n";
$texto .= '$danfeFonte="' . $_POST['fonte'] . '"' ."; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) \n";
$texto .= '$danfePrinter="' . $_POST['printer'] . '"' ."; //define a impressora para impressão da Danfe \n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ EMAIL ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração do email\n";
$texto .= '$mailFROM="' . $_POST['emitente'] . '"' .";\n";
$texto .= '$mailHOST="' . $_POST['smtp'] . '"' .";\n";
$texto .= '$mailUSER="' . $_POST['user'] . '"' .";\n";
$texto .= '$mailPASS="' . $_POST['password'] . '"' .";\n";
$texto .= '$mailPROTOCOL="' . $_POST['protocol'] . '"' .";\n";
$texto .= '$mailFROMmail="' . $_POST['mailfrommail'] . '"' .";\n";
$texto .= '$mailFROMname="' . $_POST['mailfromname'] . '"' .";\n";
$texto .= '$mailREPLYTOmail="' . $_POST['mailreplymail'] . '"' .";\n";
$texto .= '$mailREPLYTOname="' . $_POST['mailreplyname'] . '"' .";\n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ PROXY ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração de Proxy\n";
$texto .= '$proxyIP="' . $_POST['proxyip'] . '"' .";\n";
$texto .= '$proxyPORT="' . $_POST['proxyport'] . '"' .";\n";
$texto .= '$proxyUSER="' . $_POST['proxyuser'] . '"' .";\n";
$texto .= '$proxyPASS="' . $_POST['proxypass'] . '"' .";\n";
$texto .= "\n";
$texto .= "?>";

if ( !file_put_contents('config/config.php', $texto) ){
    $txtResp = "Erro durante a gravação do arquivo de configuração!! Permissão de escrita negada.";
    $txtResp .= '<PRE>';
    $txtResp .= htmlspecialchars($texto);
    $txtResp .= '</PRE>';
    $color = "#FF0000";
} else {
    $txtResp = "Gravado com Sucesso!!";
    $color = "#00FFFF";
}

?>
<html>
    <head>
    <meta HTTP-EQUIV="Refresh" CONTENT="2;URL=./install.php">
    <title></title>
    </head>
    <body>
        <p align="center"><font face="Verdana" color="<?=$color;?>"><big><strong><?=$txtResp;?></strong></big></font></p>
    </body>
</html>
