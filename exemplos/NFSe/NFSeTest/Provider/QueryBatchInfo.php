<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_QueryBatchInfo implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $return = new stdClass();
        $response = static::getResponse($params['MensagemXML']);

        $return->RetornoXML = <<<XML
<RetornoInformacoesLote>
    <Cabecalho>
        <Sucesso>true</Sucesso>
        {$response}
    </Cabecalho>
</RetornoInformacoesLote>
XML;
        return $return;
    }

    protected static function getResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        return <<<XML
<InformacoesLote>
    <NumeroLote>{$xml->Cabecalho->NumeroLote}</NumeroLote>
    <InscricaoPrestador>{$xml->Cabecalho->InscricaoPrestador}</InscricaoPrestador>
    <CPFCNPJRemetente>{$xml->Cabecalho->CPFCNPJRemetente->CNPJ}</CPFCNPJRemetente>
    <DataEnvioLote>2015-01-14</DataEnvioLote>
    <QtdNotasProcessadas>1</QtdNotasProcessadas>
    <TempoProcessamento>10</TempoProcessamento>
    <ValorTotalServicos>50.00</ValorTotalServicos>
    <ValorTotalDeducoes>10.00</ValorTotalDeducoes>
</InformacoesLote>
XML;
    }
}
