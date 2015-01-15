<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_SendBatchRps extends NFSeTest_Provider_SendRps implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $return = new stdClass();
        $response = static::getResponse($params['MensagemXML']);

        $return->RetornoXML = <<<XML
<EnvioLoteRPSResponse>
    <Cabecalho>
        <Sucesso>true</Sucesso>
    </Cabecalho>
    <ChaveNFeRPS>
        {$response}
    </ChaveNFeRPS>
</EnvioLoteRPSResponse>
XML;
        return $return;
    }

    protected static function getResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        $nfeResponse = null;
        $rpsResponse = null;

        if ($xml->NFe) {
            foreach ($xml->NFe as $nfe) {
                $nfeResponse .= static::createNFeResponse($nfe) . PHP_EOL;
            }
        }

        if ($xml->RPS) {
            foreach ($xml->RPS as $rps) {
                $rpsResponse .= static::createRpsResponse($rps) . PHP_EOL;
            }
        }
        return $nfeResponse . $rpsResponse;
    }
}
