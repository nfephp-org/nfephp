<?php
namespace NFSe\Layouts\NotaPaulistana\Model;
use NFSe\Model\AbstractAtividadeMunicipal;
use NFSe\Model\AbstractRps;
use NFSe\Model\Prestador;
use NFSe\Model\Tomador;


/**
 * @category   NFePHP
 * @package    NFSe\Layouts\NotaPaulistana${NAME}
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class Rps extends AbstractRps
{
    /**
     * @param Prestador $prestador
     * @param Tomador $tomador
     * @param string $codigoMunicipio
     * @param \DateTime $dataEmissao
     * @param bool $isSimplesNacional
     * @param string $inscricaoMunicipal
     * @param string $atividadeEconomica
     * @param AbstractAtividadeMunicipal $atividadeMunicipal
     * @param string $discriminacao
     * @param float $valorServicos
     * @param float $valorDeducoes
     * @param float $aliquotaIss
     * @param float $valorIss
     * @param float $valorPis
     * @param float $valorCofins
     * @param float $valorInss
     * @param float $valorIr
     * @param float $valorCsll
     * @param float $descontoCondicionado
     * @param float $descontoIncondicionado
     * @param float $outrasRetencoes
     * @param float $baseCalculo
     */
    public function __construct(Prestador $prestador, Tomador $tomador, $codigoMunicipio, \DateTime $dataEmissao, $isSimplesNacional, $inscricaoMunicipal, $atividadeEconomica, AbstractAtividadeMunicipal $atividadeMunicipal, $discriminacao, $valorServicos, $valorDeducoes, $aliquotaIss, $valorIss, $valorPis, $valorCofins, $valorInss, $valorIr, $valorCsll, $descontoCondicionado, $descontoIncondicionado, $outrasRetencoes, $baseCalculo)
    {
        parent::__construct($prestador, $tomador, $codigoMunicipio, $dataEmissao, $isSimplesNacional, $inscricaoMunicipal, $atividadeEconomica, $atividadeMunicipal, $discriminacao, $valorServicos, $valorDeducoes, $aliquotaIss, $valorIss, $valorPis, $valorCofins, $valorInss, $valorIr, $valorCsll, $descontoCondicionado, $descontoIncondicionado, $outrasRetencoes, $baseCalculo);
    }
}

?>