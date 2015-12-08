<?php
namespace NFSe\Model;

/**
 * Superclasse com propriedades básicas de um RPS em qualquer município
 *
 * @category   NFePHP
 * @package    NFSe\Dto
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

abstract class AbstractRps extends AbstractReceipt
{
    /**
     * AbstractRps constructor.
     *
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
        $this->prestador = $prestador;
        $this->tomador = $tomador;
        $this->codigoMunicipio = $codigoMunicipio;
        $this->dataEmissao = $dataEmissao;
        $this->isSimplesNacional = $isSimplesNacional;
        $this->inscricaoMunicipal = $inscricaoMunicipal;
        $this->atividadeEconomica = $atividadeEconomica;
        $this->atividadeMunicipal = $atividadeMunicipal;
        $this->discriminacao = $discriminacao;
        $this->valorServicos = $valorServicos;
        $this->valorDeducoes = $valorDeducoes;
        $this->aliquotaIss = $aliquotaIss;
        $this->valorIss = $valorIss;
        $this->valorPis = $valorPis;
        $this->valorCofins = $valorCofins;
        $this->valorInss = $valorInss;
        $this->valorIr = $valorIr;
        $this->valorCsll = $valorCsll;
        $this->descontoCondicionado = $descontoCondicionado;
        $this->descontoIncondicionado = $descontoIncondicionado;
        $this->outrasRetencoes = $outrasRetencoes;
        $this->baseCalculo = $baseCalculo;
    }
}

?>