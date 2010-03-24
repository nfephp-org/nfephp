<?php
/**
 * Parametros de configuraçao do sistema
 *
**/
// tipo de ambiente
// esta informação deve ser editada pelo sistema
// 1-Produção
// 2-Homologação
// esta variável será utilizada para direcionar os arquivos e
// estabelecer o contato com o SEFAZ
$ambiente=1;

// nome da Empresa
$empresa = 'NOME DA EMPRESA';

// codigo da UF
$cUF = '35';

// sigla da UF
$UF = 'SP';

// nome do certificado
$certName = 'certificado.pfx';

// senha da chave privada
$keyPass = 'senha';

// senha de decriptaçao da chave (nada)
$passPhrase= '';

// Diretorio onde serão mantidos os arquivos com as NFe
// a partir deste arquivo serão montados todos os subdiretorios do sistema
// de manipulação e armazenamento das NFe
$arquivosDir="/var/www/NFE";

//configuração do DANFE
$danfeFormato = 'P'; //P-Retrato L-Paisagem
$danfePapel = 'A4'; //Tipo de papel utilizado
$danfeCanhoto = TRUE; //se verdadeiro imprime o canhoto na DANFE
$danfeServico = FALSE; //se verdadeiro monta dados de Serviço
$danfeLogo = '/var/www/NFePHP/images/logo.jpg'; //passa o caminho para o LOGO da empresa

//configuração do email
$mailFROM='endereço de email';
$maillHOST='smtp.suaempresa.com.br';
$mailUSER='endereço de email';
$mailPASS='senha do email';

?>
