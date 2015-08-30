<?php

require __DIR__ . '/../../../vendor/autoload.php';

// Dto que carregará dados LoteRps com RPSs de São Paulo
$rpss = array( new \NFSe\Layouts\NotaPaulistana\Rps() );
$loteRpsSP = new \NFSe\Dto\LoteRps(1, \NFSe\LayoutType::LAYOUT_NOTA_PAULISTANA, $rpss);

// Novo "serviço" de fazer requisição em São Paulo
$makeWebServiceRequest = new \NFSe\MakeWebServiceRequest();

// Envio o lote e tenho um retorno em string -- será um envelope XML a ser tratado para retornar uma resposta
// padrão em forma de Dto.

// makeWebServiceRequest implementa factory pattern. O método a classe decide lá dentro a implementação que usará
$response = $makeWebServiceRequest->enviarLoteRps($loteRpsSP);

// Exemplo de resposta
print $response . "\n";

?>