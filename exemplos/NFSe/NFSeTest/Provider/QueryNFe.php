<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_QueryNFe extends NFSeTest_Provider_SendRps implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $response = static::getQueryResponse($params['MensagemXML']);

        $return = new stdClass();
        $return->RetornoXML = <<<XML
<RetornoConsulta>
    <Cabecalho>
        <Sucesso>true</Sucesso>
        <Versao>1</Versao>
    </Cabecalho>
    {$response}
</RetornoConsulta>
XML;
        return $return;
    }

    protected static function getQueryResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        $response = static::createNFeQuery($xml);
        $response .= static::createRPSQuery($xml);
        return $response;
    }

    protected static function createNFeQuery(SimpleXMLElement $xml)
    {
        $keyNFe = '';
        if ($xml->Detalhe->ChaveNFe) {
            $keyNFe .= static::createNFeResponse($xml->Detalhe) . PHP_EOL;
            return <<<XML
<NFe>
    {$keyNFe}
    <NumeroLote>1</NumeroLote>
    <StatusNFe>N</StatusNFe>
</NFe>
XML;
        }
        return '';
    }

    protected static function createRPSQuery($xml)
    {
        $keyRPS = '';
        if ($xml->Detalhe->ChaveRPS) {
            $keyRPS .= static::createRpsResponse($xml->Detalhe) . PHP_EOL;
            return <<<XML
<RPS>
    {$keyRPS}
    <NumeroLote>1</NumeroLote>
    <StatusRPS>N</StatusRPS>
</RPS>
XML;
        }
        return '';
    }
}
