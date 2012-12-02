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
 * @version   1.3.3
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * 
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Leandro C. Lopez <leandro.castoldi@gmail.com>
 * 
 **/
//identificar qual é o SO do servidor
$flagWIN = false;
if (strtoupper(substr(PHP_OS,0,3))=='WIN'){
    $flagWIN = true;
}
$datat = date('d-m-Y H:i:s');
$texto = "<?php\n";
$texto .= "/**\n";
$texto .= " * Parâmetros de configuração do sistema\n";
$texto .= " * Última alteração em $datat \n ";
$texto .= "**/\n\n";
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
$texto .= '$arquivoURLxmlCTe="' . $_POST['urlwscte'] . '"' .";\n";
$texto .= "//Diretório onde serão mantidos os arquivos com as NFe em xml\n";
$texto .= "//a partir deste diretório serão montados todos os subdiretórios do sistema\n";
$texto .= "//de manipulação e armazenamento das NFe e CTe\n";
if ($flagWIN){
    $texto .= '$arquivosDir="' . addcslashes($_POST['dirnfe'], "\\") . '"' . ";\n";
} else {
    $texto .= '$arquivosDir="' . $_POST['dirnfe'] . '"' .";\n";
}
if ($flagWIN){
    $texto .= '$arquivosDirCTe="' . addcslashes($_POST['dircte'], "\\") . '"' . ";\n";
} else {
    $texto .= '$arquivosDirCTe="' . $_POST['dircte'] . '"' .";\n";
}
$texto .= "//URL base da API, passa a ser necessária em virtude do uso dos arquivos wsdl\n";
$texto .= "//para acesso ao ambiente nacional\n";
$texto .= '$baseurl="' . $_POST['urlapi'] . '"' .";\n";
$texto .= "//Versão em uso dos shemas utilizados para validação dos xmls\n";
$texto .= '$schemes="' . $_POST['schema'] . '"' .";\n";
$texto .= '$schemesCTe="' . $_POST['schemacte'] . '"' .";\n";
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
if ($flagWIN){
    $texto .= '$danfeLogo="' . addcslashes($_POST['logo'], "\\") . '"' ."; //passa o caminho para o LOGO da empresa \n";
} else {
    $texto .= '$danfeLogo="' . $_POST['logo'] . '"' ."; //passa o caminho para o LOGO da empresa \n";
}    
$texto .= '$danfeLogoPos="' . $_POST['logopos'] . '"' ."; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta \n";
$texto .= '$danfeFonte="' . $_POST['fonte'] . '"' ."; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) \n";
$texto .= '$danfePrinter="' . $_POST['printer'] . '"' ."; //define a impressora para impressão da Danfe \n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ DACTE ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração do DACTE\n";
$texto .= '$dacteFormato="' . $_POST['formatocte'] . '"' ."; //P-Retrato L-Paisagem \n";
$texto .= '$dactePapel="' . $_POST['papelcte'] . '"' ."; //Tipo de papel utilizado \n";
$texto .= '$dacteCanhoto=' . $_POST['canhotocte'] . '' ."; //se verdadeiro imprime o canhoto na DANFE \n";
if ($flagWIN){
    $texto .= '$dacteLogo="' . addcslashes($_POST['logocte'], "\\") . '"' ."; //passa o caminho para o LOGO da empresa \n";
} else {
    $texto .= '$dacteLogo="' . $_POST['logocte'] . '"' ."; //passa o caminho para o LOGO da empresa \n";
}    
$texto .= '$dacteLogoPos="' . $_POST['logoposcte'] . '"' ."; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta \n";
$texto .= '$dacteFonte="' . $_POST['fontecte'] . '"' ."; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) \n";
$texto .= '$dactePrinter="' . $_POST['printercte'] . '"' ."; //define a impressora para impressão da Dacte \n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ EMAIL ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração do email\n";
$texto .= '$mailAuth="' . $_POST['mailAuth'] . '"' ."; //ativa ou desativa a obrigatoriedade de autenticação no envio de email, na maioria das vezes ativar \n";
$texto .= '$mailFROM="' . $_POST['emitente'] . '"' ."; //identificação do emitente \n";
$texto .= '$mailHOST="' . $_POST['smtp'] . '"' ."; //endereço do servidor SMTP \n";
$texto .= '$mailUSER="' . $_POST['user'] . '"' ."; //username para autenticação, usando quando mailAuth é 1\n";
$texto .= '$mailPASS="' . $_POST['password'] . '"' ."; //senha de autenticação do serviço de email\n";
$texto .= '$mailPROTOCOL="' . $_POST['protocol'] . '"' ."; //protocolo de email utilizado (classe alternate)\n";
$texto .= '$mailPORT="' . $_POST['porta'] . '"' ."; //porta utilizada pelo smtp (classe alternate)\n";
$texto .= '$mailFROMmail="' . $_POST['mailfrommail'] . '"' ."; //para alteração da identificação do remetente, pode causar problemas com filtros de spam \n";
$texto .= '$mailFROMname="' . $_POST['mailfromname'] . '"' ."; //para indicar o nome do remetente \n";
$texto .= '$mailREPLYTOmail="' . $_POST['mailreplytomail'] . '"' ."; //para indicar o email de resposta\n";
$texto .= '$mailREPLYTOname="' . $_POST['mailreplytoname'] . '"' ."; //para indicar email de cópia\n";
$texto .= '$mailIMAPhost="' . $_POST['mailimaphost'] . '"' ."; //url para o servidor IMAP\n";
$texto .= '$mailIMAPport="' . $_POST['mailimapport'] . '"' ."; //porta do servidor IMAP\n";
$texto .= '$mailIMAPsecurity="' . $_POST['mailimapsecurity'] . '"' ."; //esquema de segurança do servidor IMAP\n";
$texto .= '$mailIMAPnocerts="' . $_POST['mailimapnocerts'] . '"' ."; //desabilita verificação de certificados do Servidor IMAP\n";
$texto .= '$mailIMAPbox="' . $_POST['mailimapbox'] . '"' ."; //caixa postal de entrada do servidor IMAP\n";
$texto .= '$mailLayoutFile="' . $_POST['maillayout'] . '"' . "; //layout da mensagem do email\n";
$texto .= "\n";
$texto .= "//###############################\n";
$texto .= "//############ PROXY ############\n";
$texto .= "//###############################\n";
$texto .= "//Configuração de Proxy\n";
$texto .= '$proxyIP="' . $_POST['proxyip'] . '"' ."; //ip do servidor proxy, se existir \n";
$texto .= '$proxyPORT="' . $_POST['proxyport'] . '"' ."; //numero da porta usada pelo proxy \n";
$texto .= '$proxyUSER="' . $_POST['proxyuser'] . '"' ."; //nome do usuário, se o proxy exigir autenticação\n";
$texto .= '$proxyPASS="' . $_POST['proxypass'] . '"' ."; //senha de autenticação do proxy \n";
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
