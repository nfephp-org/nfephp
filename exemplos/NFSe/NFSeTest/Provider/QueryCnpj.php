<?php

/**
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */
class NFSeTest_Provider_QueryCnpj implements NFSeTest_Provider_ProviderInterface
{

    public static function response(array $params)
    {
        $return = new stdClass();

        $return->RetornoXML = <<<XML
<RetornoConsultaCNPJ>
    <Cabecalho>
        <Sucesso>true</Sucesso>
    </Cabecalho>
    <Detalhe>
        <InscricaoMunicipal>1234567890</InscricaoMunicipal>
        <EmiteNFe>true</EmiteNFe>
    </Detalhe>
</RetornoConsultaCNPJ>
XML;
        return $return;
    }
}
