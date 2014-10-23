<?php

//namespace NFe\Xml;

//use NFe\Xml\Base;
//use NFe\Xml\iXml;
//use \DOMDocument;

require_once('Base.php');
require_once('iXml.php');

class Transporte extends Base implements iXml
{

    public $modFrete = 0;
    
    private $identificador = 'X01 <transp> - ';
    private $transp; //DOMElement
    private $transportadora; //DOMElement
    private $retTransp; //DOMElement
    private $veicTransp; //DOMElement
    private $aReboque = array(); //Array de DOMElement
    private $aVol = array(); //Array de DOMElement
    
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
    
    public function getTag()
    {
        $this->zTagTransp();
        return $this->transp;
    }
    
    public function setModoFrete($modfrete = 0)
    {
        if (empty($modfrete)) {
            $modfrete = 0;
        }
        $this->modFrete = $modfrete;
    }
    
    /**
     * setTransportadora
     * Grupo Transportador X03 pai X01
     * tag NFe/infNFe/transp/tranporta (opcional)
     * @return void
     */
    public function setTransportadora(
        $cnpj = '',
        $cpf = '',
        $xNome = '',
        $numIE = '',
        $xEnder = '',
        $xMun = '',
        $siglaUF = ''
    ) {
        $this->transportadora = $this->dom->createElement("transporta");
        $this->zAddChild(
            $this->transportadora,
            "CNPJ",
            $cnpj,
            false,
            $this->identificador . "CNPJ do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "CPF",
            $cpf,
            false,
            $this->identificador . "CPF do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "xNome",
            $xNome,
            false,
            $this->identificador . "Razão Social ou nome do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "IE",
            $numIE,
            false,
            $this->identificador . "Inscrição Estadual do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "xEnder",
            $xEnder,
            false,
            $this->identificador . "Endereço Completo do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "xMun",
            $xMun,
            false,
            $this->identificador . "Nome do município do Transportador"
        );
        $this->zAddChild(
            $this->transportadora,
            "UF",
            $siglaUF,
            false,
            $this->identificador . "Sigla da UF do Transportador"
        );
    }

    /**
     * pTagRetTransp
     * Grupo Retenção ICMS transporte X11 pai X01
     * tag NFe/infNFe/transp/retTransp (opcional)
     * @return void
     */
    public function setRetencaoICMS(
        $vServ = '',
        $vBCRet = '',
        $pICMSRet = '',
        $vICMSRet = '',
        $cfop = '',
        $cMunFG = ''
    ) {
        $this->retTransp = $this->dom->createElement("retTransp");
        $this->zAddChild(
            $this->retTransp,
            "vServ",
            $vServ,
            true,
            $this->identificador . "Valor do Serviço"
        );
        $this->zAddChild(
            $this->retTransp,
            "vBCRet",
            $vBCRet,
            true,
            $this->identificador . "BC da Retenção do ICMS"
        );
        $this->zAddChild(
            $this->retTransp,
            "pICMSRet",
            $pICMSRet,
            true,
            $this->identificador . "Alíquota da Retenção"
        );
        $this->zAddChild(
            $this->retTransp,
            "vICMSRet",
            $vICMSRet,
            true,
            $this->identificador . "Valor do ICMS Retido"
        );
        $this->zAddChild(
            $this->retTransp,
            "CFOP",
            $this->cfop,
            true,
            $this->identificador . "CFOP"
        );
        $this->zAddChild(
            $this->retTransp,
            "cMunFG",
            $cMunFG,
            true,
            $this->identificador . "Código do município de ocorrência do fato gerador do ICMS do transporte"
        );
    }
    
    /**
     * setVeiculo
     * Grupo Veículo Transporte X18 pai X17.1
     * tag NFe/infNFe/transp/veicTransp (opcional)
     * @return void
     */
    public function setVeiculo(
        $veicPlaca = '',
        $veicUF = '',
        $veicRNTC = ''
    ) {
        $this->veicTransp = $this->dom->createElement("veicTransp");
        $this->zAddChild(
            $this->veicTransp,
            "placa",
            $veicPlaca,
            true,
            $this->identificador . "Placa do Veículo"
        );
        $this->zAddChild(
            $this->veicTransp,
            "UF",
            $veicUF,
            true,
            $this->identificador . "Sigla da UF do Veículo"
        );
        $this->zAddChild(
            $this->veicTransp,
            "RNTC",
            $veicRNTC,
            false,
            $this->identificador . "Registro Nacional de Transportador de Carga (ANTT) do Veículo"
        );
    }
    
    /**
     * setReboque
     * Grupo Reboque X22 pai X17.1
     * tag NFe/infNFe/transp/reboque (opcional)
     * @param string $placa
     * @param string $siglaUF
     * @param string $rntc
     * @param string $vagao
     * @param string $balsa
     * @return void
     */
    public function setReboques(
        $placa = '',
        $siglaUF = '',
        $rntc = '',
        $vagao = '',
        $balsa = ''
    ) {
        $reboque = $this->dom->createElement("reboque");
        $this->zAddChild(
            $reboque,
            "placa",
            $placa,
            true,
            $this->identificador . "Placa do Veículo Reboque"
        );
        $this->zAddChild(
            $reboque,
            "UF",
            $siglaUF,
            true,
            $this->identificador . "Sigla da UF do Veículo Reboque"
        );
        $this->zAddChild(
            $reboque,
            "RNTC",
            $rntc,
            false,
            $this->identificador . "Registro Nacional de Transportador de Carga (ANTT) do Veículo Reboque"
        );
        $this->zAddChild(
            $reboque,
            "vagao",
            $vagao,
            false,
            $this->identificador . "Identificação do vagão do Veículo Reboque"
        );
        $this->zAddChild(
            $reboque,
            "balsa",
            $balsa,
            false,
            $this->identificador . "Identificação da balsa do Veículo Reboque"
        );
        $this->aReboque[] = $reboque;
    }

    /**
     * setVolumes
     * Grupo Volumes X26 pai X01
     * tag NFe/infNFe/transp/vol (opcional)
     * @param string $qVol
     * @param string $esp
     * @param string $marca
     * @param string $nVol
     * @param string $pesoL
     * @param string $pesoB
     * @param array $aLacres
     * @return void
     */
    public function setVolumes(
        $qVol = '',
        $esp = '',
        $marca = '',
        $nVol = '',
        $pesoL = '',
        $pesoB = '',
        $aLacres = array()
    ) {
        $vol = $this->dom->createElement("vol");
        $this->zAddChild($vol, "qVol", $qVol, false, "Quantidade de volumes transportados");
        $this->zAddChild($vol, "esp", $esp, false, "Espécie dos volumes transportados");
        $this->zAddChild($vol, "marca", $marca, false, "Marca dos volumes transportados");
        $this->zAddChild($vol, "nVol", $nVol, false, "Numeração dos volumes transportados");
        $this->zAddChild($vol, "pesoL", $pesoL, false, "Peso Líquido (em kg) dos volumes transportados");
        $this->zAddChild($vol, "pesoB", $pesoB, false, "Peso Bruto (em kg) dos volumes transportados");
        if (!empty($aLacres)) {
            //tag transp/vol/lacres (opcional)
            foreach ($aLacres as $nLacre) {
                $lacre = $this->zTaglacres($nLacre);
                $vol->appendChild($lacre);
                $lacre = null;
            }
        }
        $this->aVol[] = $vol;
    }

    /**
     * zTagTransp
     * Grupo Informações do Transporte X01 pai A01
     * tag NFe/infNFe/transp (obrigatório)
     * @param string $modFrete
     * @return vopid
     */
    private function zTagTransp()
    {
        $this->transp = $this->dom->createElement("transp");
        $this->zAddChild(
            $this->transp,
            "modFrete",
            $this->modFrete,
            true,
            $this->identificador . "Modalidade do frete"
        );
        
        if (!empty($this->transportadora)) {
            $this->zAppChild(
                $this->transp,
                $this->transportadora,
                $this->identificador . 'A tag transp deveria ter sido carregada primeiro.'
            );
        }
        if (!empty($this->retTransp)) {
            $retTransp = $this->pTagRetTransp();
            $this->zAppChild(
                $this->transp,
                $this->retTransp,
                $this->identificador . 'A tag transp deveria ter sido carregada primeiro.'
            );
        }
        if (!empty($this->veicTransp)) {
            $this->zAppChild(
                $this->transp,
                $this->veicTransp,
                $this->identificador . 'A tag transp deveria ter sido carregada primeiro.'
            );
        }
        foreach ($this->aReboque as $reboque) {
            $this->zAppChild(
                $this->transp,
                $reboque,
                $this->identificador . 'A tag transp deveria ter sido carregada primeiro.'
            );
        }
        foreach ($this->aVol as $volume) {
            $this->zAppChild(
                $this->transp,
                $volume,
                $this->identificador . 'A tag transp deveria ter sido carregada primeiro.'
            );
        }
    }
}
