<?php

namespace NFSe;

/**
 * Value Object class for RPS
 * @package   NFePHPaulista
 * @author Reinaldo Nolasco Sanches <reinaldo@mandic.com.br>
 * @copyright Copyright (c) 2010, Reinaldo Nolasco Sanches
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

class NFeRPS
{
    /**
     * CCM do prestador
     * @var string
     */
    public $CCM;
    /**
     *
     * @var string
     */
    public $serie;
    /**
     *
     * @var string
     */
    public $numero;
    /**
     * RPS ­ Recibo Provisório de Serviços
     * RPS-M ­ Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista)
     * RPS-C ­ Cupom 
     * @var type 
     */
    public $type = 'RPS';
    /**
     *
     * @var string 
     */
    public $dataEmissao;
    /**
     * N ­ Normal
     * C ­ Cancelada
     * E ­ Extraviada 
     * @var string
     */
    public $status = 'N';
    /**
     * T - Tributação no município de São Paulo
     * F - Tributação fora do município de São Paulo
     * I ­- Isento
     * J - ISS Suspenso por Decisão Judicial 
     * "I have problem with F and J options"
     * @var string
     */
    public $tributacao = 'I';
    /**
     *
     * @var number 
     */
    public $valorServicos = 0;
    /**
     *
     * @var number
     */
    public $valorDeducoes = 0;
    /**
     *
     * @var string
     */
    public $codigoServico;
    /**
     * Alíquota dos Serviços
     * @var numeber 
     */
    public $aliquotaServicos;
    /**
     * ISS retido
     * @var boolean
     */
    public $comISSRetido = false;
    /**
     * new ContractorRPS
     * @var string 
     */
    public $contractorRPS;
    /**
     * Discriminação dos serviços
     * @var string 
     */
    public $discriminacao;
}

/**
 * Value Object class for Contractor
 *
 * @author Reinaldo Nolasco Sanches <reinaldo@mandic.com>
 */
class ContractorRPS
{
    public $cnpjTomador; // CPF/CNPJ
    public $ccmTomador; // CCM
    public $type = 'C'; // C = Corporate (CNPJ), F = Personal (CPF)
    public $name;
    public $tipoEndereco;
    public $endereco;
    public $enderecoNumero;
    public $complemento;
    public $bairro;
    public $cidade;
    public $estado;
    public $cep;
    public $email;
    public $email2;
}
