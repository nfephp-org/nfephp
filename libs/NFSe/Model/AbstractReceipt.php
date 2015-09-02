<?php
namespace NFSe\Model;

/**
 * Classe com propriedades comuns de RPS e NFS-e
 *
 * @category   NFePHP
 * @package    NFSe\Dto\AbstractReceipt
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
abstract class AbstractReceipt
{
    /**
     * Prestador
     *
     * @var Prestador
     */
    protected $prestador;

    /**
     * Tomador
     *
     * @var Tomador
     */
    protected $tomador;

    /**
     * Código do Município
     *
     * @var string
     */
    protected $codigoMunicipio;

    /**
     * Data da Emissão
     *
     * @var \DateTime
     */
    protected $dataEmissao;

    /**
     * Optante pelo Simples Nacional?
     *
     * @var boolean
     */
    protected $isSimplesNacional;

    /**
     * Inscrição municipal do tomador
     *
     * @var string
     */
    protected $inscricaoMunicipal;

    /**
     * CNAE (Atividade Economica)
     *
     * @var string
     */
    protected $atividadeEconomica;

    /**
     * Atividade Municipal
     *
     * Cada município implementa a sua atividade municipal. A depender do município, pode ser Item da Lista
     * de Serviço, Código do Serviço ou outra coisa.
     *
     * @var AbstractAtividadeMunicipal
     */
    protected $atividadeMunicipal;

    /**
     * Discriminação
     *
     * @var string
     */
    protected $discriminacao;

    /**
     * Valor do serviço
     *
     * @var float
     */
    protected $valorServicos;

    /**
     * Valor das deduções
     *
     * @var float
     */
    protected $valorDeducoes;

    /**
     * Alíquota do ISS
     *
     * Valor de alíquota sem porcentagem. Exemplo: 0.02
     *
     * @var float
     */
    protected $aliquotaIss;

    /**
     * Valor do ISS Retido
     *
     * @var float
     */
    protected $valorIss;

    /**
     * Valor do PIS retido
     *
     * @var float
     */
    protected $valorPis;

    /**
     * Valor do Cofins retido
     *
     * @var float
     */
    protected $valorCofins;

    /**
     * Valor do Inss retido
     *
     * @var float
     */
    protected $valorInss;

    /**
     * Valor do Ir retido
     *
     * @var float
     */
    protected $valorIr;

    /**
     * Valor do Csll retido
     *
     * @var float
     */
    protected $valorCsll;

    /**
     * Valor do desconto condicionado
     *
     * @var float
     */
    protected $descontoCondicionado;

    /**
     * Valor do desconto incondicionado
     *
     * @var float
     */
    protected $descontoIncondicionado;

    /**
     * Outras retenções
     *
     * @var float
     */
    protected $outrasRetencoes;

    /**
     * Base de Cálculo
     *
     * Na maioria dos municípios, a base de cálculo da NFS-e é o Valor Total de Serviços, subtraído do Valor de
     * Deduções previstas em lei e do Desconto Incondicionado
     *
     * @var float
     */
    protected $baseCalculo;

    /**
     * @return Prestador
     */
    public function getPrestador()
    {
        return $this->prestador;
    }

    /**
     * @return Tomador
     */
    public function getTomador()
    {
        return $this->tomador;
    }

    /**
     * @return string
     */
    public function getCodigoMunicipio()
    {
        return $this->codigoMunicipio;
    }

    /**
     * @return \DateTime
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
    }

    /**
     * @return boolean
     */
    public function getIsSimplesNacional()
    {
        return $this->isSimplesNacional;
    }

    /**
     * @return string
     */
    public function getInscricaoMunicipal()
    {
        return $this->inscricaoMunicipal;
    }

    /**
     * @return string
     */
    public function getAtividadeEconomica()
    {
        return $this->atividadeEconomica;
    }

    /**
     * @return AbstractAtividadeMunicipal
     */
    public function getAtividadeMunicipal()
    {
        return $this->atividadeMunicipal;
    }

    /**
     * @return string
     */
    public function getDiscriminacao()
    {
        return $this->discriminacao;
    }

    /**
     * @return float
     */
    public function getValorServicos()
    {
        return $this->valorServicos;
    }

    /**
     * @return float
     */
    public function getValorDeducoes()
    {
        return $this->valorDeducoes;
    }

    /**
     * @return float
     */
    public function getAliquotaIss()
    {
        return $this->aliquotaIss;
    }

    /**
     * @return float
     */
    public function getValorIss()
    {
        return $this->valorIss;
    }

    /**
     * @return float
     */
    public function getValorPis()
    {
        return $this->valorPis;
    }

    /**
     * @return float
     */
    public function getValorCofins()
    {
        return $this->valorCofins;
    }

    /**
     * @return float
     */
    public function getValorInss()
    {
        return $this->valorInss;
    }

    /**
     * @return float
     */
    public function getValorIr()
    {
        return $this->valorIr;
    }

    /**
     * @return float
     */
    public function getValorCsll()
    {
        return $this->valorCsll;
    }

    /**
     * @return float
     */
    public function getDescontoCondicionado()
    {
        return $this->descontoCondicionado;
    }

    /**
     * @return float
     */
    public function getDescontoIncondicionado()
    {
        return $this->descontoIncondicionado;
    }

    /**
     * @return float
     */
    public function getOutrasRetencoes()
    {
        return $this->outrasRetencoes;
    }

    /**
     * @return float
     */
    public function getBaseCalculo()
    {
        return $this->baseCalculo;
    }


}

?>