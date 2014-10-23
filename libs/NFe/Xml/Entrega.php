<?php

//namespace NFe\Xml;

//use NFe\Xml\Base;
//use \DOMDocument;

require_once('Base.php');
require_once('iXml.php');

class Entrega extends Base implements iXml
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
    private $entrega; //DOMElement
    
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
        $identificador = 'G01 <entrega> - ';
        $this->entrega = $this->dom->createElement("entrega");
        if ($cnpj != '') {
            $this->zAddChild(
                $this->entrega,
                "CNPJ",
                $this->cnpj,
                true,
                $identificador . "CNPJ do Cliente da Entrega"
            );
        } else {
            $this->zAddChild(
                $this->entrega,
                "CPF",
                $this->cpf,
                true,
                $identificador . "CPF do Cliente da Entrega"
            );
        }
        $this->zAddChild(
            $this->entrega,
            "xLgr",
            $this->this->xLgr,
            true,
            $identificador . "Logradouro do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "nro",
            $this->nro,
            true,
            $identificador . "Número do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "xCpl",
            $this->xCpl,
            false,
            $identificador . "Complemento do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "xBairro",
            $this->xBairro,
            true,
            $identificador . "Bairro do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "cMun",
            $this->cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "xMun",
            $this->xMun,
            true,
            $identificador . "Nome do município do Endereco do Cliente da Entrega"
        );
        $this->zAddChild(
            $this->entrega,
            "UF",
            $this->siglaUF,
            true,
            $identificador . "Sigla da UF do Endereco do Cliente da Entrega"
        );
        return $this->entrega;
    }
}
