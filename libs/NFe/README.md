Classes para emissão da NFe (padrão estadual SEFAZ)

ToolsNFe.php
====
Classe principal para a comunicação com a SEFAZ

Métodos
=====
```php
(void) public function setModelo($modelo)
```
Seta o modelo a ser usado pela classe. Default é '55' e para NFCe deve ser alterado para '65'

```php
(string) public function getModelo()
```
Retorna o modelo setado para a classe.

```php
(bool) public function ativaContingencia($siglaUF, $motivo)
```
Os parametros são obrigatórios. Seta a contingência SVCRS ou SVCAN em função do estado da federação.
O motivo de entrada em contingência e a hora desse fato são registrados. O sistema permanecerá em contingência 
até que seja desativada.

```php
(void) public function desativaContingencia()
```

```php
public function imprime($pathXml, $pathDestino, $printer)
```

```php
public function enviaMail($pathXml, $aMails)
```

```php
public function addB2B($pathNFefile, $pathB2Bfile, $tagB2B)
```

```php
public function addProtocolo($pathNFefile, $pathProtfile, $saveFile)
```

```php
public function addCancelamento($pathNFefile, $pathCancfile, $saveFile)
```

```php
public function verificaValidade($pathXmlFile, $aRetorno)
```

```php
public function assina($xml, $saveFile)
```

```php
public function sefazEnviaLote($aXml, $tpAmb, $idLote, $aRetorno, $indSinc, $compactarZip)
```

```php
public function sefazConsultaRecibo($recibo, $tpAmb, $aRetorno)
```

```php
public function sefazConsultaChave($chave, $tpAmb, $aRetorno)
```

```php
public function sefazInutiliza($nSerie, $nIni, $nFin, $xJust, $tpAmb, $aRetorno)
```

```php
public function sefazCadastro($siglaUF, $tpAmb, $cnpj, $iest, $cpf, $aRetorno)
```

```php
public function sefazStatus($siglaUF, $tpAmb, $aRetorno)
```

```php
public function sefazDistDFe($fonte, $tpAmb, $cnpj, $ultNSU, $numNSU, $aRetorno, $descompactar)
```

```php
public function sefazCCe($chNFe, $tpAmb, $xCorrecao, $nSeqEvento, $aRetorno)
```

```php
public function sefazCancela($chNFe, $tpAmb, $xJust, $nProt, $aRetorno)
```

```php
public function sefazManifesta($chNFe, $tpAmb, $xJust, $tpEvento, $aRetorno)
```
