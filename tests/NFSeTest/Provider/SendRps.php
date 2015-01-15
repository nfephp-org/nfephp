<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_SendRps implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $return = new stdClass();
        $response = static::getResponse($params['MensagemXML']);

        $return->RetornoXML = <<<XML
<EnvioRPSResponse>
    <Cabecalho>
        <Sucesso>true</Sucesso>
    </Cabecalho>
    <ChaveNFeRPS>
        {$response}
    </ChaveNFeRPS>
</EnvioRPSResponse>
XML;
        return $return;
    }

    protected static function getResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        $nfeResponse = null;
        $rpsResponse = null;

        if ($xml->NFe) {
            $nfeResponse = static::createNFeResponse($xml->NFe);
        }

        if ($xml->RPS) {
            $rpsResponse = static::createRpsResponse($xml->RPS);
        }
        return $nfeResponse . $rpsResponse;
    }

    protected static function createRpsResponse(SimpleXMLElement $rps)
    {
        return <<<XML
<ChaveRPS>
    <InscricaoPrestador>{$rps->ChaveRPS->InscricaoPrestador}</InscricaoPrestador>
    <SerieRPS>{$rps->ChaveRPS->SerieRPS}</SerieRPS>
    <DataEmissaoRPS>2014-01-14</DataEmissaoRPS>
    <RazaoSocialPrestador>{$rps->ChaveRPS->RazaoSocialTomador}</RazaoSocialPrestador>
    <NumeroRPS>{$rps->ChaveRPS->NumeroRPS}</NumeroRPS>
    <CodigoVerificacao>1</CodigoVerificacao>
</ChaveRPS>
XML;
    }

    protected static function createNFeResponse(SimpleXMLElement $nfe)
    {
        return <<<XML
<ChaveNFe>
    <InscricaoPrestador>{$nfe->ChaveNFe->InscricaoPrestador}</InscricaoPrestador>
    <RazaoSocialPrestador>{$nfe->ChaveNFe->RazaoSocialPrestador}</RazaoSocialPrestador>
    <NumeroNFe>{$nfe->ChaveNFe->NumeroNFe}</NumeroNFe>
    <CodigoVerificacao>1</CodigoVerificacao>
</ChaveNFe>
XML;
    }
}
