Classes para emissão da NFe (padrão estadual SEFAZ)

ToolsNFe.php
====
Classe principal para a comunicação com a SEFAZ

Métodos
=====

public function setModelo($modelo)
----

public function getModelo()
----

public function ativaContingencia($siglaUF, $motivo)
----

public function desativaContingencia()
----

public function imprime($pathXml, $pathDestino, $printer)
----

public function enviaMail($pathXml, $aMails)
----

public function addB2B($pathNFefile, $pathB2Bfile, $tagB2B)
----

public function addProtocolo($pathNFefile, $pathProtfile, $saveFile)
----

public function addCancelamento($pathNFefile, $pathCancfile, $saveFile)
----

public function verificaValidade($pathXmlFile, $aRetorno)
----

public function assina($xml, $saveFile)
----

public function sefazEnviaLote($aXml, $tpAmb, $idLote, $aRetorno, $indSinc, $compactarZip)
----

public function sefazConsultaRecibo($recibo, $tpAmb, $aRetorno)
----

public function sefazConsultaChave($chave, $tpAmb, $aRetorno)
----

public function sefazInutiliza($nSerie, $nIni, $nFin, $xJust, $tpAmb, $aRetorno)
----

public function sefazCadastro($siglaUF, $tpAmb, $cnpj, $iest, $cpf, $aRetorno)
----

public function sefazStatus($siglaUF, $tpAmb, $aRetorno)
----

public function sefazDistDFe($fonte, $tpAmb, $cnpj, $ultNSU, $numNSU, $aRetorno, $descompactar)
----

public function sefazCCe($chNFe, $tpAmb, $xCorrecao, $nSeqEvento, $aRetorno)
----

public function sefazCancela($chNFe, $tpAmb, $xJust, $nProt, $aRetorno)
----

public function sefazManifesta($chNFe, $tpAmb, $xJust, $tpEvento, $aRetorno)
----
