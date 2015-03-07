ToolsNFe.php
====
Classe principal para a comunicação com a SEFAZ

Métodos
=====

setModelo
----
```php
(void) public function setModelo($modelo)
```
Seta o modelo a ser usado pela classe. Default é '55' e para NFCe deve ser alterado para '65'

getModelo
----
```php
(string) public function getModelo()
```
Retorna o modelo setado para a classe.

ativaContingencia
----
```php
(bool) public function ativaContingencia($siglaUF, $motivo)
```
Os parametros são obrigatórios. Seta a contingência SVCRS ou SVCAN em função do estado da federação.
O motivo de entrada em contingência e a hora desse fato são registrados. O sistema permanecerá em contingência 
até que seja desativada.
Os dados dos propriedades da classe, abaixo, deverão ser usados para a criação ou recriação das NFe que serão emitidas em contigência,

```php
$tools->motivoContingencia;
$tools->tsContingencia;
```

desativaContingencia
----
```php
(void) public function desativaContingencia()
```
Desativa o sistema de contingencia SVC e volta a operação normal

imprime
----
```php
(void|string) public function imprime($pathXml, $pathDestino, $printer)
```
Imprime o documento. Pode ser :

* DANFE
* DANFCE
* DACCE

O método pode retornar uma string com o pdf gerado ou nada

Parâmetros

* (string) $pathXml - Caminho completo ao documento xml
* (string) $pathDestino - o destino pode ser um diretório ou vazio
* (string) $printer - nome da impressora, se o ambiente estiver adequadamente configurado o pdf será enviado a impressora

enviaMail
----
```php
(bool) public function enviaMail($pathXml, $aMails)
```
Envia email com o arquivo xml anexado.
Para os endereços contidos no array $aMails ou no caso de uma NFe,
para os endereços de emails contidos na propria NFe.
Retorna true se sucesso ou false se houve falha

Parâmetros

* (string) $pathXml - Caminho completo ao documento xml a ser enviado
* (array) $aMails - Matriz com os email de destino

addB2B
----
```php
(string) public function addB2B($pathNFefile, $pathB2Bfile, $tagB2B)
```
Adiciona a tag de comunicação B2B ao arquivo da NFe.
Esse tipo de recurso foi criado pela ANFAVEA para simplificar os processos de EDI entre as empresas do setor.

Parâmetros

* (string) $pathNFefile - Caminho completo ao arquivo da NFe (já protocolado) 
* (string) $pathB2Bfile - Caminho completo ao arquivo xml com a estrutura B2B
* (string) $tagB2B - Tag a ser inclusa

addProtocolo
----
```php
(string) public function addProtocolo($pathNFefile, $pathProtfile, $saveFile)
```
Adiciona o protocolo de autorização ao xml da NFe. A NFe somente está válida com o protocolo anexado.
Este método retorna uma string com o xml da NFe já protocolado.

Parâmetros

* (string) $pathNFefile - Caminho completo ao arquivo da NFe sem o protocolo
* (string) $pathProtfile - Caminho completo ao arquivo com o protocolo de autorização da NFe
* (bool) $saveFile - true salva a NFe na pasta aprovadas

addCancelamento
----
```php
(string) public function addCancelamento($pathNFefile, $pathCancfile, $saveFile)
```
Adiciona o protocolo de cancelamento a NFe. Esse método retorna o xml modificado com o cancelamento,
não existe exigência legal para isso mas é uma forma de idnetificar as NFe que foram canceladas
apenas pelo próprio xml.

Parâmetros

* (string) $pathNFefile - Caminho completo ao arquivo da NFe a ser marcada como cancelada
* (string) $pathCancfile - Caminho completo ao protocolo de cancelamento
* (bool) $saveFile - true salva a NFe cancelada sobre a original

verificaValidade
----
```php
(bool) public function verificaValidade($pathXmlFile, $aRetorno)
```
Verifica a validade de uma NFe recebida de terceiros.
Este método retorna verdadeiro ou falso

Parâmetros

* (string) $pathXmlFile - Caminho completo ao arquivo da NFe a ser validada
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

assina
----
```php
(string) public function assina($xml [, $saveFile])
```
Assina uma NFe.
Este método retorna o xml da NFe assinada com o certificado digital.

Parâmetros

* (string) $xml - xml da NFe
* (bool) $saveFile - salva a NFe na pasta assinadas

sefazEnviaLote
----
```php
(string) public function sefazEnviaLote($aXml, $tpAmb, $idLote, $aRetorno [, $indSinc, $compactarZip])
```
Solicita a validação de um lote de NFe.
Esse método retorna o XML de resposta da SEFAZ.
Pode enviar de 1 até 50 NFe (assíncrono) ou caso a autorizadora permita enviar uma única NFe pelo método síncono.
O envio síncrono não está ativado como padrão e em geral são as autorizadoras do NFCe (modelo 65) que permitem seu uso.

Parâmetros

* (string | array) $aXml - String com NFe ou um array com várias NFe's
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $idLote - Número de identificação do lote, será criado se não for passado nada.
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ
* (int) $indSinc - 1-Ativa envio síncrono 0-defaul modo assíncrono
* (bool) $compactarZip

sefazConsultaRecibo
----
```php
(string) public function sefazConsultaRecibo($recibo, $tpAmb, $aRetorno)
```
Solicita a situação de um lote enviado, pelo número do recibo.
É complementar a ao método anterior sefazEnviaLote(), caso o envia seja assíncrono.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $recibo - Número do recibo retornado pelo método sefazEnviaLote()
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazConsultaChave
----
```php
(string) public function sefazConsultaChave($chave, $tpAmb, $aRetorno)
```
Solicita a situação da NFe identificada por sua chave.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $chNFe - chave da NFe (com 44 dígitos numéricos)
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazInutiliza
----
```php
(string) public function sefazInutiliza($nSerie, $nIni, $nFin, $xJust, $tpAmb, $aRetorno)
```
Solicita a inutilização de faixa de numeros de NFe.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (int) $nSerie - Número da série de NFe
* (int) $nIni - Número inicial
* (int) $nFin - Número final
* (string) $xJust - Justificativa para a inutilização da faixa de numeros
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazCadastro
----
```php
(string) public function sefazCadastro($siglaUF, $tpAmb, $cnpj, $iest, $cpf, $aRetorno)
```
Solicita os dados cadastrais da empresa identificada.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $siglaUF - Sigla da unidade da federação
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $cnpj - Número do CNPJ a ser pesquisado (esse é prioritário, se for diferente de vazio será usado)
* (string) $iest - Número da instrição estadual a ser pesquisado
* (string) $cpf - Número do CPF a ser pesquisado 
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazStatus
----
```php
(string) public function sefazStatus($siglaUF, $tpAmb, $aRetorno)
```
Solicita o status dos serviços da SEFAZ.
Esse método retorna o XML de resposta da SEFAZ.
ATENÇÃO - Já foi informado que esse serviço irá deixar de funcionar.

Parâmetros

* (string) $siglaUF - Sigla da unidade da federação
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazDistDFe
----
```php
(string) public function sefazDistDFe($fonte, $tpAmb, $cnpj, $ultNSU, $numNSU, $aRetorno)
```
Solicita os documentos destinados ao CNPJ. Essa pesquisa pode ser feita pelo $ultNSU e serão retornados até 30 documentos de cada vez.
Ou pelo numero de NSU (se for diferente de zero) onde será retornado apenas o documento relativo a esse NSU.
Os documentos são retornados descompatados.
CUIDADO no uso não faça solicitições muito frequentes pois a SEFAZ vai bloquear.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $fonte - Usualmente 'AN', mas pode ser para alguns casos 'RS'
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $cnpj - Número do CNPJ a ser pesquisado (isso esta atrelado ao certificado)
* (int) $ultNSU - Número do último NSU pesquisado
* (int) $numNSU - Número do NSU que se deseja (deixe zero para perquisar pelo ultNSU)
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazCCe
----
```php
(string) public function sefazCCe($chNFe, $tpAmb, $xCorrecao, $nSeqEvento, $aRetorno)
```
Solicita uma carta de correção para uma NFe.Esse método retorna o XML de resposta da SEFAZ.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $chNFe - chave da NFe (com 44 dígitos numéricos)
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $xCorrecao - Qual é a correção a ser feita na NFe
* (int) $nSeqEvento - Número sequencial de eventos dessa NFe
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazCancela
----
```php
(string) public function sefazCancela($chNFe, $tpAmb, $xJust, $nProt, $aRetorno)
```
Realiza a soliciatação de cancelamento da NFe.
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $chNFe - chave da NFe (com 44 dígitos numéricos)
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $xJust - Justificativa para o cancelamento da NFe
* (string) $nProt - Número do protocolo de autorização da NFe
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazManifesta
----
```php
(string) public function sefazManifesta($chNFe, $tpAmb, $xJust, $tpEvento, $aRetorno)
```
Realiza a solicitação de manifestação do destinatário. Esse método retorna o XML de resposta da SEFAZ.
Esse método retorna o XML de resposta da SEFAZ.
   * 210200 – Confirmação da Operação
   * 210210 – Ciência da Operação
   * 210220 – Desconhecimento da Operação
   * 210240 – Operação não Realizada, apenas nesse caso xJust é obrigatório. 

Parâmetros

* (string) $chNFe - chave da NFe (com 44 dígitos numéricos)
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $xJust - Justificativa para "210240 – Operação não Realizada"
* (string) $tpEvento - Código do tipo de evento
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ

sefazDownload
----
```php
(string) public function sefazDownload($chNFe, $tpAmb, $cnpj, $aRetorno)
```
Realiza a solicitação de download de NFe já manifestada
Esse método retorna o XML de resposta da SEFAZ.

Parâmetros

* (string) $chNFe - chave da NFe (com 44 dígitos numéricos)
* (string) $tpAmb - tipo de ambiente 1-produção ou 2-homologação
* (string) $cnpj - Número do CNPJ do destinatário ou interessado autorizado a fazer o download
                 caso não seja passado nenhum valor o CNPJ da configuração será usado
* (array) $aRetorno - Array passado como referência e retorna com os dados de retorno da SEFAZ
