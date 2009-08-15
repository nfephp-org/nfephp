<?php
/**
 * Parametros de configuraçao do sistema
 * 
**/

//Variaveis Globais

//tipo de ambiente
$ambiente = 2;

// NF-e para assinar e enviar
$entradasDir = '/var/www/webproj/NFeTools/NFeFiles/entradasNF/';
// NF-e assinadas, ms nao validadas
$assinadasDir = '/var/www/webproj/NFeTools/NFeFiles/assinadasNF/';
// NF-e ja assinadas, validadas e prontas para envio
$validadasDir='/var/www/webproj/NFeTools/NFeFiles/validadasNF/';
$aprovadasDir='/var/www/webproj/NFeTools/NFeFiles/aprovadasNF/';
// NF-e ja enviadas (individualmente ou em lote)
$enviadasDir='/var/www/webproj/NFeTools/NFeFiles/enviadasNF/';
$canceladasDir='/var/www/webproj/NFeTools/NFeFiles/canceladasNF/';
$inutilizadasDir='/var/www/webproj/NFeTools/NFeFiles/inutilizadasNF/';
$temporarioDir='/var/www/webproj/NFeTools/NFeFiles/temporarioNF/';
$recebidasDir='/var/www/webproj/NFeTools/NFeFiles/recebidasNF/';
$consultadas='/var/www/webproj/NFeTools/NFeFiles/consultadasNF/';
// Bibliotecas e classes
$libDir='/var/www/webproj/NFeTools/libs/';
// Certificados e chaves
$certDir='/var/www/webproj/NFeTools/certs/';
// Esquemas
$xsdDir='/var/www/webproj/NFeTools/xsd/';

/**
 *Dados do Certificado
 */

// senha da chave privada
$keyPass = 'senha';
// senha de decriptaçao da chave
$passPhrase= '';
// nome do certificado
$certName = 'cerfificado.pfx';

/**
 *Dados da Empresa
 *
 */

// nome da Empresa
$empresa = 'Empresa Ltda';
// codigo da UF
$cUF = '35';
// sigla da UF
$UF = 'SP';


?>
