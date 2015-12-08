<?php

require __DIR__ . '/../../../vendor/autoload.php';

// Dto que carregará dados LoteRps com RPSs de São Paulo
$prestador = new \NFSe\Model\Prestador( \NFSe\Model\City::SAO_PAULO );
$tomador = new \NFSe\Model\Tomador();
$atividadeMunicipal = new \NFSe\Layouts\NotaPaulistana\Model\AtividadeMunicipal('789567');
$codigoMunicipio = \NFSe\Model\City::SAO_PAULO;
$rps = new \NFSe\Layouts\NotaPaulistana\Model\Rps(
    $prestador,
    $tomador,
    $codigoMunicipio, // $codigoMunicipio de São Paulo
    new \DateTime(), // $dataEmissao
    true, // $isSimplesNacional
    '11223344', // $inscricaoMunicipal dummy
    '6201500', // $atividadeEconomica CNAE
    $atividadeMunicipal,
    'Desenvolvimento de banco de dados', // $discriminacao
    1000, // $valorServicos
    0, // $valorDeducoes
    0.0279, // $aliquotaIss
    0, // $valorIss ISS não retido
    0, // $valorPis PIS não retido
    0, // $valorCofins COFINS não retido
    0, // $valorInss INSS não retido
    0, // $valorIr IR não retido
    0, // $valorCsll CSLL não retido
    0, // $descontoCondicionado
    0, // $descontoIncondicionado
    0, // $outrasRetencoes
    1000 //$baseCalculo
);
$rpss = array( $rps );

// Novo Lote RPS
$loteRpsSP = new \NFSe\Model\LoteRps(1, $rpss, $codigoMunicipio);

// Novo "serviço" de fazer requisição em São Paulo
$makeWebServiceRequest = new \NFSe\Service\WebServiceRequesterService();

// Envio o lote e tenho um retorno em string -- será um envelope XML a ser tratado para retornar uma resposta
// padrão em forma de Dto.

// webServiceRequesterService implementa factory pattern. O método a classe decide lá dentro a implementação que usará
$response = $makeWebServiceRequest->enviarLoteRps($loteRpsSP);

// Exemplo de resposta
print $response . "\n";

?>