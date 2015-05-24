<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_QueryNFePeriod extends NFSeTest_Provider_QueryNFe implements NFSeTest_Provider_ProviderInterface
{

    protected static function getQueryResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        //workaround to returns a NFe
        $detail = $xml->addChild('Detalhe');
        $chaveNFe = $detail->addChild('ChaveNFe');
        $chaveNFe->addChild('InscricaoPrestador', 123);
        $chaveNFe->addChild('RazaoSocialPrestador', 'RAZAO SOCIAL PRESTADOR');
        $chaveNFe->addChild('NumeroNFe', '123');
        $response = static::createNFeQuery($xml);

        $chaveRPS = $detail->addChild('ChaveRPS');
        $chaveRPS->addChild('InscricaoPrestador', 123);
        $chaveRPS->addChild('SerieRPS', 1);
        $chaveRPS->addChild('DataEmissaoRPS', '2015-01-14');
        $chaveRPS->addChild('RazaoSocialTomador', 'RAZAO SOCIAL TOMADOR');
        $chaveRPS->addChild('NumeroRPS', '321');
        $response .= static::createRPSQuery($xml);

        return $response;
    }
}
