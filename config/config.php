<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada
 * pela Fundação para o Software Livre, na versão 3 da licença, ou qualquer
 * versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral
 * GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da 
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>. 
 *
 * Está atualizada para:
 *      PHP 5.3
 *      Versão 3.10 dos webservices da SEFAZ com comunicação via SOAP 1.2
 *      e conforme Manual de Integração Versão 5
 *
 * @package   NFePHP
 * @name      config.php
 * @abstract  Definições dos parâmetros do sistema.
 * @version   2.50
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              
 */

// Evita a exibição dos erros iniciais que aparecem ao instalar o sistema.
// Ao gravar as alterações pela primeira vez,
// esta linha é suprimida permitindo, então, a exibição dos erros
error_reporting(0);

//###############################
//#### CONFIGURAÇÕES GERAIS #####
//###############################
// Tipo de ambiente. Esta informação deve ser editada pelo sistema
// 1-Produção 2-Homologação
// Esta variável será utilizada para direcionar os arquivos e estabelecer o
// contato com o SEFAZ.
$ambiente=2;

// Nomes dos arquivos com todas as URLs dos webservices do SEFAZ incluindo a
// versão dos mesmos, pois alguns estados não estão utilizando as mesmas versões
// Para NF-e utiliza por padrão o arquivo do modelo "55"
$arquivoURLxml='nfe_ws3_mod55.xml';
$arquivoURLxmlCTe="cte_ws2.xml";

// Diretório onde serão mantidos os arquivos com as NFe em xml
// a partir deste diretório serão montados todos os subdiretórios do sistema
// de manipulação e armazenamento das NFe. Não utilizar barra no final.
$arquivosDir='/var/www/nfe';
$arquivosDirCTe="/var/www/cte";

// URL base da API, passa a ser necessária em virtude do uso dos arquivos wsdl
// para acesso ao ambiente nacional. Não utilizar barra no final.
$baseurl='http://localhost/nfephp';

// Versão em uso dos shemas utilizados para validação dos xmls
$schemes='PL_008c';
$schemesCTe="PL_CTe_200";

//###############################
//###### EMPRESA EMITENTE #######
//###############################
//Nome da Empresa
$empresa='Sua Razao Social Aqui (sem acentos ou simbolos)';
//Sigla da UF
$UF='SP';
//Número do CNPJ
$cnpj='1234567890001';

//##############################
//#### CERTIFICADO DIGITAL #####
//##############################
//Pasta dos certificados, deixar vazio para usar automático em "/certs", ou seja,
//informe apenas se precisar do certificado em outro local da sua preferência
$certsDir='';
//Nome do arquivo do certificado digital
$certName='certificado_teste.pfx';
//Senha da chave privada
$keyPass='associacao';
//Senha de decriptaçao da chave, normalmente não é necessaria
$passPhrase='';

//###############################
//############ DANFE ############
//###############################
//Configuração do DANFE
$danfeFormato='P'; //P-Retrato L-Paisagem
$danfePapel='A4'; //Tipo de papel utilizado
$danfeCanhoto=1; //se verdadeiro imprime o canhoto na DANFE
$danfeLogo='/var/www/nfephp/images/logo.jpg'; //passa o caminho para o LOGO da empresa
$danfeLogoPos='L'; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta
$danfeFonte='Times'; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times)
$danfePrinter='hpteste'; //define a impressora para impressão da Danfe

//###############################
//############ DACTE ############
//###############################
//Configuração do DACTE
$dacteFormato="P"; //P-Retrato L-Paisagem 
$dactePapel="A4"; //Tipo de papel utilizado 
$dacteCanhoto=1; //se verdadeiro imprime o canhoto na DANFE 
$dacteLogo="/var/www/nfephp/images/logo.jpg"; //passa o caminho para o LOGO da empresa 
$dacteLogoPos="L"; //define a posição do logo na Danfe L-esquerda, C-dentro e R-direta 
$dacteFonte="Times"; //define a fonte do Danfe limitada as fontes compiladas no FPDF (Times) 
$dactePrinter="hpteste"; //define a impressora para impressão da Dacte 

//###############################
//############ EMAIL ############
//###############################
//Configuração do email
$mailAuth='1';
$mailFROM='nfe@seudominio.com.br';
$mailHOST='smtp.seudominio.com.br';
$mailUSER='nfe@seudominio.com.br';
$mailPASS='suasenha';
$mailPROTOCOL='';
$mailPORT='25';
$mailFROMmail='nfe@seudominio.com.br';
$mailFROMname='NFe';
$mailREPLYTOmail='nfe@seudominio.com.br';
$mailREPLYTOname='NFe';
$mailIMAPhost = 'mail.seudominio.com.br';
$mailIMAPport = '143';
$mailIMAPsecurity = 'tls';
$mailIMAPnocerts = 'novalidate-cert';
$mailIMAPbox = 'INBOX';

//###############################
//############ PROXY ############
//###############################
//Configuração de proxy
$proxyIP='';
$proxyPORT='';
$proxyUSER='';
$proxyPASS='';
