<?php
/**
 * Parâmetros de configuração do sistema
 * Última alteração em 17-01-2015 15:01:45 
 **/

//###############################
//########## GERAL ##############
//###############################
// tipo de ambiente esta informação deve ser editada pelo sistema
// 1-Produção 2-Homologação
// esta variável será utilizada para direcionar os arquivos e
// estabelecer o contato com o SEFAZ
$ambiente=2;
//esta variável contêm o nome do arquivo com todas as url dos webservices do sefaz
//incluindo a versao dos mesmos, pois alguns estados não estão utilizando as
//mesmas versões
$arquivoURLxml="nfe_ws3_mod55.xml";
$arquivoURLxmlCTe="cte_ws1.xml";
//Diretório onde serão mantidos os arquivos com as NFe em xml
//a partir deste diretório serão montados todos os subdiretórios do sistema
//de manipulação e armazenamento das NFe e CTe
$arquivosDir="/var/www/NFePHP/Arquivos/NFe/101";
$arquivosDirCTe="/var/www/NFePHP/Arquivos/CTe/101";
//URL base da API, passa a ser necessária em virtude do uso dos arquivos wsdl
//para acesso ao ambiente nacional
$baseurl="http://localhost/NFePHP/101/";
//Versão em uso dos shemas utilizados para validação dos xmls
$schemes="PL_008c";
$schemesCTe="PL_CTE_104";

//###############################
//###### EMPRESA EMITENTE #######
//###############################
//Nome da Empresa
$empresa="Migliorini e Migliorini Ltda Epp";
//Sigla da UF
$UF="MT";
//Código da UF
$cUF="51";
//Número do CNPJ
$cnpj="04576775000160";

//###############################
//#### CERITIFICADO DIGITAL #####
//###############################
//Nome do certificado que deve ser colocado na pasta certs da API
$certName="2014-03-24-Migliorini.pfx";
//Senha da chave privada
$keyPass="serasa12";
//Senha de decriptaçao da chave, normalmente não é necessaria
$passPhrase="";

//###############################
//############ DANFE ############
//###############################
//Configuração do DANFE
$danfeFormato="P"; //P-Retrato L-Paisagem 
$danfePapel="A4"; //Tipo de papel utilizado 
$danfeCanhoto=1; //se verdadeiro imprime o canhoto na DANFE 
$danfeLogo="/var/www/NFePHP/MGPapelariaSeloPretoBranco.jpg"; //passa o caminho para o LOGO da empresa 
$danfeLogoPos="L"; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta 
$danfeFonte="Times"; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) 
$danfePrinter="hpteste"; //define a impressora para impressão da Danfe 

//###############################
//############ DACTE ############
//###############################
//Configuração do DACTE
$dacteFormato="P"; //P-Retrato L-Paisagem 
$dactePapel="A4"; //Tipo de papel utilizado 
$dacteCanhoto=1; //se verdadeiro imprime o canhoto na DANFE 
$dacteLogo="/var/www/NFePHP/MGPapelariaSeloPretoBranco.jpg"; //passa o caminho para o LOGO da empresa 
$dacteLogoPos="L"; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta 
$dacteFonte="Times"; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) 
$dactePrinter="hpteste"; //define a impressora para impressão da Dacte 

//###############################
//############ EMAIL ############
//###############################
//Configuração do email
$mailAuth="1"; //ativa ou desativa a obrigatoriedade de autenticação no envio de email, na maioria das vezes ativar 
$mailFROM="nfe@mgpapelaria.com.br"; //identificação do emitente 
$mailHOST="smtp.mgpapelaria.com.br"; //endereço do servidor SMTP 
$mailUSER="nfe@mgpapelaria.com.br"; //username para autenticação, usando quando mailAuth é 1
$mailPASS="trocar01"; //senha de autenticação do serviço de email
$mailPROTOCOL=""; //protocolo de email utilizado (classe alternate)
$mailPORT="25"; //porta utilizada pelo smtp (classe alternate)
$mailFROMmail="nfe@mgpapelaria.com.br"; //para alteração da identificação do remetente, pode causar problemas com filtros de spam 
$mailFROMname="NFe"; //para indicar o nome do remetente 
$mailREPLYTOmail="nfe@mgpapelaria.com.br"; //para indicar o email de resposta
$mailREPLYTOname="NFe"; //para indicar email de cópia
$mailIMAPhost="imap.mgpapelaria.com.br"; //url para o servidor IMAP
$mailIMAPport="143"; //porta do servidor IMAP
$mailIMAPsecurity="tls"; //esquema de segurança do servidor IMAP
$mailIMAPnocerts="novalidate-cert"; //desabilita verificação de certificados do Servidor IMAP
$mailIMAPbox="INBOX"; //caixa postal de entrada do servidor IMAP
$mailLayoutFile=""; //layout da mensagem do email

//###############################
//############ PROXY ############
//###############################
//Configuração de Proxy
$proxyIP=""; //ip do servidor proxy, se existir 
$proxyPORT=""; //numero da porta usada pelo proxy 
$proxyUSER=""; //nome do usuário, se o proxy exigir autenticação
$proxyPASS=""; //senha de autenticação do proxy 

?>