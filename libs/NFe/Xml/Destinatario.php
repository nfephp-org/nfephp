<?php

//namespace NFe\Xml;

//use NFe\Xml\Base;
//use NFe\Xml\iXml;
//use \DOMDocument;

require_once('Base.php');
require_once('iXml.php');

class Destinatario extends Base implements iXml
{
    public $cnpj = '';
    public $cpf = '';
    public $idEstrangeiro = '';
    public $xNome = '';
    public $indIEDest = '';
    public $numIE = '';
    public $isUF = '';
    public $numIM = '';
    public $email = '';
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
    public $dest; //DOMElement

    private $enderDest; //DOMElement

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
     * Ajusta da propriedade com o número do CNPJ do destinatário, se houver
     * 
     * @param string $cnpj
     */
    public function setCNPJ($cnpj = '')
    {
        if (!empty($cnpj)) {
            $this->cnpj = $cnpj;
        }
    }
    
    /**
     * setCPF
     * Ajusta a propriedade com o número do CPF, se houver
     * 
     * @param string $cpf
     */
    public function setCPF($cpf = '')
    {
        if (!empty($cpf)) {
            $this->cpf = $cpf;
        }
    }
    
    /**
     * setIdEstrangeiro
     * Ajusta a propriedade com o número do Identificação de Estrangeiro, se houver
     * 
     * @param type $idEstrangeiro
     */
    public function setIdEstrangeiro($idEstrangeiro = '')
    {
        if (!empty($idEstrangeiro)) {
            $this->idEstrangeiro = $idEstrangeiro;
        }
    }
    
    /**
     * setNome
     * Ajusta a propriedade xNome, com a Razão Social do destinatário
     * 
     * @param string $xNome
     */
    public function setNome($xNome = '')
    {
        if (!empty($xNome)) {
            $this->xNome = $xNome;
        }
    }
    
    /**
     * setIndIEDest
     * Ajusta a propriedade indIEDest 
     * 
     * @param string $indIEDest
     */
    public function setIndIEDest($indIEDest = '')
    {
        if (!empty($indIEDest)) {
            $this->indIEDest = $indIEDest;
        }
    }
    
    /**
     * setIE
     * Ajusta a propriedade numIE, com o número da Inscrição Estadual
     * 
     * @param string $numIE
     */
    public function setIE($numIE = '')
    {
        if (!empty($numIE)) {
            $this->numIE = $numIE;
        }
    }
    
    /**
     * setIsUF
     * Ajusta a propriedade isUF
     * 
     * @param string $numIEST
     */
    public function setIsUF($isUF = '')
    {
        if (!empty($isUF)) {
            $this->isUF = $isUF;
        }
    }
    
    /**
     * setIM
     * Ajusta a propriedade numIM, com o numero da inscrição municipal, se houver
     * 
     * @param string $numIM
     */
    public function setIM($numIM = '')
    {
        if (!empty($numIM)) {
            $this->numIM = $numIM;
        }
    }
    
    /**
     * setEmail
     * Ajusta a propriedade email
     * 
     * @param type $email
     */
    public function setEmail($email = '')
    {
        if (!empty($email)) {
            $this->email = $email;
        }
    }
    
    /**
     * setLogradouro
     * Ajusta a propriedade xLgr, com o nome da Rua do endereço do destinatário
     * 
     * @param string $xLgr
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
     */
    public function setFone($fone = '')
    {
        if (!empty($fone)) {
            $this->fone = $fone;
        }
    }
    
    /**
     * getDestTag
     * Monta e retorna a tag emit da NFe, com os dados passados por parametros
     * para a classe
     * 
     * @return DOMElement
     */
    public function getTag()
    {
        $this->pTagDest();
        $this->pTagEnderDest();
        $node = $this->dest->getElementsByTagName("indIEDest")->item(0);
        if (!isset($node)) {
            $node = $this->dest->getElementsByTagName("IE")->item(0);
        }
        $this->dest->insertBefore($this->enderDest, $node);
        return $this->dest;
    }
    
    /**
     * pTagDest
     * Identificação do Destinatário da NF-e E01 pai A01
     * tag NFe/infNFe/dest (opcional para modelo 65)
     * @return void
     */
    public function tagdest()
    {
        $identificador = 'E01 <dest> - ';
        if (!empty($this->dest)) {
            $this->dest = null;
        }
        $this->dest = $this->dom->createElement("dest");
        if ($cnpj != '') {
            $this->zAddChild(
                $this->dest,
                "CNPJ",
                $this->cnpj,
                true,
                $identificador . "CNPJ do destinatário"
            );
        } elseif ($cpf != '') {
            $this->zAddChild(
                $this->dest,
                "CPF",
                $this->cpf,
                true,
                $identificador . "CPF do destinatário"
            );
        } else {
            $this->zAddChild(
                $this->dest,
                "idEstrangeiro",
                $this->idEstrangeiro,
                true,
                $identificador . "Identificação do destinatário no caso de comprador estrangeiro"
            );
        }
        $this->zAddChild(
            $this->dest,
            "xNome",
            $this->xNome,
            true,
            $identificador . "Razão Social ou nome do destinatário"
        );
        if ($this->mod == '65') {
            $this->indIEDest = '9';
        }
        $this->zAddChild(
            $this->dest,
            "indIEDest",
            $this->indIEDest,
            true,
            $identificador . "Indicador da IE do Destinatário"
        );
        if ($indIEDest != '9' && $indIEDest != '2') {
            $this->zAddChild(
                $this->dest,
                "IE",
                $this->numIE,
                true,
                $identificador . "Inscrição Estadual do Destinatário"
            );
        }
        $this->zAddChild(
            $this->dest,
            "ISUF",
            $this->isUF,
            false,
            $identificador . "Inscrição na SUFRAMA do destinatário"
        );
        $this->zAddChild(
            $this->dest,
            "IM",
            $numIM,
            false,
            $identificador . "Inscrição Municipal do Tomador do Serviço do destinatário"
        );
        $this->zAddChild(
            $this->dest,
            "email",
            $email,
            false,
            $identificador . "Email do destinatário"
        );
    }
    
    /**
     * pTagEnderDest
     * Endereço do Destinatário da NF-e E05 pai E01 
     * tag NFe/infNFe/dest/enderDest  (opcional para modelo 65)
     * Os dados do destinatário devem ser inseridos antes deste método
     * @return void
     */
    private function pTagEnderDest()
    {
        $identificador = 'E05 <enderDest> - ';
        if (!empty($this->enderDest)) {
            $this->enderDest = null;
        }
        $this->enderDest = $this->dom->createElement("enderDest");
        $this->zAddChild(
            $this->enderDest,
            "xLgr",
            $this->xLgr,
            true,
            $identificador . "Logradouro do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "nro",
            $this->nro,
            true,
            $identificador . "Número do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "xCpl",
            $this->xCpl,
            false,
            $identificador . "Complemento do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "xBairro",
            $this->xBairro,
            true,
            $identificador . "Bairro do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "cMun",
            $this->cMun,
            true,
            $identificador . "Código do município do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "xMun",
            $this->xMun,
            true,
            $identificador . "Nome do município do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "UF",
            $this->siglaUF,
            true,
            $identificador . "Sigla da UF do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "CEP",
            $this->cep,
            true,
            $identificador . "Código do CEP do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "cPais",
            $this->cPais,
            false,
            $identificador . "Código do País do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "xPais",
            $this->xPais,
            false,
            $identificador . "Nome do País do Endereço do destinatário"
        );
        $this->zAddChild(
            $this->enderDest,
            "fone",
            $this->fone,
            false,
            $identificador . "Telefone do Endereço do destinatário"
        );
    }
}
