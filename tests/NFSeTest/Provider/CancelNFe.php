<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_CancelNFe implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $response = static::getCancelResponse($params['MensagemXML']);

        $return = new stdClass();
        $return->RetornoXML = <<<XML
<RetornoCancelamentoNFSe>
    <Cabecalho>
        <CodCidade></CodCidade>
        <Sucesso>true</Sucesso>
        <CPFCNPJRemetente></CPFCNPJRemetente>
        <Versao></Versao>
    </Cabecalho>
    <NotasCanceladas>
        {$response}
    </NotasCanceladas>
</RetornoCancelamentoNFSe>
XML;
        return $return;
    }

    protected static function getCancelResponse($xmlString)
    {
        $xml = new SimpleXMLElement($xmlString);
        $response = static::createNFeCancelada($xml->Detalhe->ChaveNFe->NumeroNFe);
        return $response;
    }

    protected static function createNFeCancelada($invoiceNumber)
    {
        return <<<XML
<Nota>
    <InscricaoMunicipalPrestador>123</InscricaoMunicipalPrestador>
    <NumeroNota>{$invoiceNumber}</NumeroNota>
    <CodigoVerificacao>123</CodigoVerificacao>
    <MotivoCancelamento>MOTIVO CANCELAMENTO</MotivoCancelamento>
</Nota>
XML;
    }
}
