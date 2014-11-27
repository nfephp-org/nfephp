<?php

namespace NFe\Xml;

use NFe\Xml\Base;
use NFe\Xml\iXml;
use \DOMDocument;

class Retirada extends Base implements iXml
{
    public $cnpj = '';
    public $cpf = '';
    public $xLgr = '';
    public $nro = '';
    public $xCpl = '';
    public $xBairro = '';
    public $cMun = '';
    public $xMun = '';
    public $siglaUF = '';
    private $retirada; //DOMElement
    
    /**
     * __contruct
     * Função construtora cria um objeto DOMDocument
     * 
     * @return void
     */
    public function __construct($dom = '')
    {
        parent::__construct($dom);
    }
    
    /**
     * setCNPJ
     * @param string $cnpj
     * @return void
     */
    public function setCNPJ($cnpj = '')
    {
        if (!empty($cnpj)) {
            $this->cnpj = $cnpj;
        }
    }
    
    /**
     * setCPF
     * @param string $cpf
     * @return void
     */
    public function setCPF($cpf = '')
    {
        if (!empty($cpf)) {
            $this->cpf = $cpf;
        }
    }
    
    /**
     * setLogradouro
     * @param string $xLgr
     * @retunr void
     */
    public function setLogradouro($xLgr = '')
    {
        if (!empty($xLgr)) {
            $this->xLgr = $xLgr;
        }
    }
    
    /**
     * setNumero
     * @param string $nro
     * @return void
     */
    public function setNumero($nro = '')
    {
        if (!empty($nro)) {
            $this->nro = $nro;
        }
        
    }
    
    /**
     * setComplemento
     * @param string $xCpl
     * @return void
     */
    public function setComplemento($xCpl = '')
    {
        if (!empty($xCpl)) {
            $this->xCpl = $xCpl;
        }
        
    }
    
    /**
     * setBairro
     * @param string $xBairro
     * @return void
     */
    public function setBairro($xBairro = '')
    {
        if (!empty($xBairro)) {
            $this->xBairro = $xBairro;
        }
        
    }
    
    /**
     * setCodMun
     * @param string $cMun
     * @return void
     */
    public function setCodMun($cMun = '')
    {
        if (!empty($cMun)) {
            $this->cMun = $cMun;
        }
        
    }
    
    /**
     * setMunicipio
     * @param string $xMun
     * @return void
     */
    public function setMunicipio($xMun = '')
    {
        if (!empty($xMun)) {
            $this->xMun = $xMun;
        }
        
    }
    
    /**
     * setUF
     * @param string $siglaUF
     * @return void
     */
    public function setUF($siglaUF = '')
    {
        if (!empty($siglaUF)) {
            $this->siglaUF = $siglaUF;
        }
    }

    /**
     * getTag
     * Identificação do Local de entrega G01 pai A01
     * tag NFe/infNFe/entrega (opcional)
     * @return DOMElement
     */
    public function getTag()
    {
        $identificador = 'F01 <retirada> - ';
        $this->retirada = $this->dom->createElement("retirada");
        if ($this->cnpj != '') {
            $this->zAddChild(
                $this->retirada,
                "CNPJ",
                $this->cnpj,
                true,
                $identificador . "CNPJ do Cliente da Retirada"
            );
        } else {
            $this->zAddChild(
                $this->retirada,
                "CPF",
                $this->cpf,
                true,
                $identificador . "CPF do Cliente da Retirada"
            );
        }
        $this->zAddChild(
            $this->retirada,
            "xLgr",
            $this->xLgr,
            true,
            $identificador . "Logradouro do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "nro",
            $this->nro,
            true,
            $identificador . "Número do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "xCpl",
            $this->xCpl,
            false,
            $identificador . "Complemento do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "xBairro",
            $this->xBairro,
            true,
            $identificador . "Bairro do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "cMun",
            $this->cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "xMun",
            $this->xMun,
            true,
            $identificador . "Nome do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "UF",
            $this->siglaUF,
            true,
            $identificador . "Sigla da UF do Endereco do Cliente da Retirada"
        );
        return $this->retirada;
    }
}
