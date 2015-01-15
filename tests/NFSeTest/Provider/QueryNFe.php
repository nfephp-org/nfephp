<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_QueryNFe extends NFSeTest_Provider_SendRps implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $response = self::getQueryResponse($params['MensagemXML']);

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
        $response = self::createNFeQuery($xml);
        return $response;
    }

    protected static function createNFeQuery(SimpleXMLElement $xml)
    {
        $chaveNFe = parent::createNFeRetorno($xml->Detalhe);
        return <<<XML
<NFe>
    {$chaveNFe}
    <NumeroLote>1</NumeroLote>
    <StatusNFe>N</StatusNFe>
</NFe>
XML;
    }
}
