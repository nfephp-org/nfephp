<?php

namespace NFe\Xml;

use NFe\Xml\Base;
use NFe\Xml\iXml;
use \DOMDocument;

class Emitente extends Base implements iXml
{
    public $cnpj = '';
    public $cpf = '';
    public $xNome = '';
    public $xFant = '';
    public $numIE = '';
    public $numIEST = '';
    public $numIM = '';
    public $cnae = '';
    public $crt = '';
    public $xLgr = '';
    public $nro = '';
    public $xCpl = '';
    public $xBairro = '';
    public $cMun = '';
    public $xMun = '';
    public $siglaUF = '';
    public $cep = '';
    public $cPais = '';
    public $xPais = '';
    public $fone = '';
    public $emit; //DOMElement

    private $enderEmit; //DOMElement
    
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
     * Ajusta da propriedade com o número do CNPJ do emitente, se houver
     * 
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
     * Ajusta a propriedade com o número do CPF, se houver (somente produtores rurais)
     * 
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
     * setNome
     * Ajusta a propriedade xNome, com a Razão Social da Empresa emitente
     * 
     * @param string $xNome
     * @return void
     */
    public function setNome($xNome = '')
    {
        if (!empty($xNome)) {
            $this->xNome = $xNome;
        }
    }
    
    /**
     * setFantasia
     * Ajusta a propriedade xFant, com o nome Fantasia (simplificação) do emitente
     * 
     * @param string $xFant
     * @return void
     */
    public function setFantasia($xFant = '')
    {
        if (!empty($xFant)) {
            $this->xFant = $xFant;
        }
    }
    
    /**
     * setIE
     * Ajusta a propriedade numIE, com o número da Inscrição Estadual
     * 
     * @param string $numIE
     * @return void
     */
    public function setIE($numIE = '')
    {
        if (!empty($numIE)) {
            $this->numIE = $numIE;
        }
    }
    
    /**
     * setIEST
     * Ajusta a propriedade numIEST, com o numero da inscr. estadual do substituto tributário
     * 
     * @param string $numIEST
     * @return void
     */
    public function setIEST($numIEST = '')
    {
        if (!empty($numIEST)) {
            $this->numIEST = $numIEST;
        }
    }
    
    /**
     * setIM
     * Ajusta a propriedade numIM, com o numero da inscrição municipal, se houver
     * 
     * @param string $numIM
     * @return void
     */
    public function setIM($numIM = '')
    {
        if (!empty($numIM)) {
            $this->numIM = $numIM;
        }
    }
    
    /**
     * setCNAE
     * Ajusta a propriedade CNAE, código nacional de atividade econômica
     * 
     * @param type $cnae
     * @return void
     */
    public function setCNAE($cnae = '')
    {
        if (!empty($cnae)) {
            $this->cnae = $cnae;
        }
    }
    
    /**
     * setCRT
     * Ajusta a propriedade CRT, Código do Regime Tributário
     * 
     * @param string $crt
     * @return void
     */
    public function setCRT($crt = '')
    {
        if (!empty($crt)) {
            $this->crt = $crt;
        }
    }
    
    /**
     * setLogradouro
     * Ajusta a propriedade xLgr, com o nome da Rua do endereço do emitente
     * 
     * @param string $xLgr
     * @return void
     */
    public function setLogradouro($xLgr = '')
    {
        if (!empty($xLgr)) {
            $this->xLgr = $xLgr;
        }
    }
    
    /**
     * setNumero
     * Ajusta a propriedade nro, com o numero do endereço
     * 
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
     * Ajusta a propriedade xCpl, com o complemento do endereço
     * 
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
     * Ajusta a propriedade xBairro, com o nome do bairro do endereço
     * 
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
     * Ajusta a propriedade cMun, com o código do municipio do IBGE
     * 
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
     * Ajusta a propriedade xMun, com o nome do município do endereço
     * 
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
     * Ajusta a propriedade siglaUF, com a sigla do estado do endereço
     * 
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
     * setCEP
     * Ajusta a propriedade CEP, com o numero do Codigo de endereçamento postal
     * 
     * @param string $cep
     * @return void
     */
    public function setCEP($cep = '')
    {
        if (!empty($cep)) {
            $this->cep = $cep;
        }
    }
    
    /**
     * setCodPais
     * Ajusta a propriedade cPais, com o código do pais do BACEN
     * 
     * @param string $cPais
     * @return void
     */
    public function setCodPais($cPais = '')
    {
        if (!empty($cPais)) {
            $this->cPais = $cPais;
        }
    }
    
    /**
     * setPais
     * Ajusta a propriedade xPais, com o nome do Pais do endereço
     * 
     * @param string $xPais
     * @return void
     */
    public function setNomePais($xPais = '')
    {
        if (!empty($xPais)) {
            $this->xPais = $xPais;
        }
    }
    
    /**
     * setFone
     * Ajusta a propriedade fone, com o numero do telefone do endereço
     * 
     * @param string $fone
     * @return void
     */
    public function setFone($fone = '')
    {
        if (!empty($fone)) {
            $this->fone = $fone;
        }
    }
    
    /**
     * getTag
     * Monta e retorna a tag emit da NFe, com os dados passados por parametros
     * para a classe
     * 
     * @return DOMElement
     */
    public function getTag()
    {
        $this->pTagemit();
        $this->pTagenderEmit();
        $node = $this->emit->getElementsByTagName("IE")->item(0);
        $this->emit->insertBefore($this->enderEmit, $node);
        return $this->emit;
    }
    
    /**
     * pTagemit
     * Identificação do emitente da NF-e C01 pai A01
     * tag NFe/infNFe/emit
     * @return void
     */
    private function pTagemit()
    {
        if (!empty($this->emit)) {
            $this->emit = null;
        }
        $identificador = 'C01 <emit> - ';
        $this->emit = $this->dom->createElement("emit");
        if ($cnpj != '') {
            $this->zAddChild($this->emit, "CNPJ", $this->cnpj, true, $identificador . "CNPJ do emitente");
        } else {
            $this->zAddChild($this->emit, "CPF", $this->cpf, true, $identificador . "CPF do remetente");
        }
        $this->zAddChild(
            $this->emit,
            "xNome",
            $this->xNome,
            true,
            $identificador . "Razão Social ou Nome do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "xFant",
            $this->xFant,
            false,
            $identificador . "Nome fantasia do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "IE",
            $this->numIE,
            true,
            $identificador . "Inscrição Estadual do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "IEST",
            $this->numIEST,
            false,
            $identificador . "IE do Substituto Tributário do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "IM",
            $this->numIM,
            false,
            $identificador . "Inscrição Municipal do Prestador de Serviço do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "CNAE",
            $this->cnae,
            false,
            $identificador . "CNAE fiscal do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "CRT",
            $this->crt,
            true,
            $identificador . "Código de Regime Tributário do emitente"
        );
    }
    
    /**
     * pTagenderEmit
     * Endereço do emitente C05 pai C01
     * tag NFe/infNFe/emit/endEmit
     * @return void
     */
    private function pTagenderEmit()
    {
        $identificador = 'C05 <enderEmit> - ';
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->zAddChild(
            $this->enderEmit,
            "xLgr",
            $this->xLgr,
            true,
            $identificador . "Logradouro do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "nro",
            $this->nro,
            true,
            $identificador . "Número do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "xCpl",
            $this->xCpl,
            false,
            $identificador . "Complemento do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "xBairro",
            $this->xBairro,
            true,
            $identificador . "Bairro do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "cMun",
            $this->cMun,
            true,
            $identificador . "Código do município do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "xMun",
            $this->xMun,
            true,
            $identificador . "Nome do município do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "UF",
            $this->siglaUF,
            true,
            $identificador . "Sigla da UF do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "CEP",
            $this->cep,
            true,
            $identificador . "Código do CEP do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "cPais",
            $this->cPais,
            false,
            $identificador . "Código do País do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "xPais",
            $this->xPais,
            false,
            $identificador . "Nome do País do Endereço do emitente"
        );
        $this->zAddChild(
            $this->enderEmit,
            "fone",
            $this->fone,
            false,
            $identificador . "Telefone do Endereço do emitente"
        );
    }
}
