<?php

namespace NFePHP\MDFe;

/**
 * Classe a construção do xml do Manifesto Eletrônico de Documentos Fiscais (MDF-e)
 * @category   NFePHP
 * @package    NFePHP\MDFe\MakeMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 * 
 * NOTA: Esta classe foi construida conforme estabelecido no 
 * Manual de Orientação do Contribuinte
 * Padrões Técnicos de Comunicação do Manifesto Eletrônico de Documentos Fiscais
 * versão 1.00 de Junho de 2012
 */

use NFePHP\Common\DateTime\DateTime;
use NFePHP\Common\Base\BaseMake;
use \DOMDocument;
use \DOMElement;

class MakeMDFe extends BaseMake
{
    /**
     * versao
     * numero da versão do xml da MDFe
     * @var double
     */
    public $versao = '1.00';
    /**
     * mod
     * modelo da MDFe 58
     * @var integer
     */
    public $mod = '58';
    /**
     * chave da MDFe
     * @var string
     */
    public $chMDFe = '';
    
    //propriedades privadas utilizadas internamente pela classe
    private $MDFe = ''; //DOMNode
    private $infMDFe = ''; //DOMNode
    private $ide = ''; //DOMNode
    private $emit = ''; //DOMNode
    private $enderEmit = ''; //DOMNode
    private $infModal = ''; //DOMNode
    private $tot = ''; //DOMNode
    private $infAdic = ''; //DOMNode
    private $rodo = ''; //DOMNode
    private $veicPrincipal = ''; //DOMNode
    private $aereo = ''; //DOMNode
    private $trem = ''; //DOMNode
    private $aqua = ''; //DOMNode
    
    // Arrays
    private $aInfMunCarrega = array(); //array de DOMNode
    private $aInfPercurso = array(); //array de DOMNode
    private $aInfMunDescarga = array(); //array de DOMNode
    private $aInfCTe = array(); //array de DOMNode
    private $aInfCT = array(); //array de DOMNode
    private $aInfNFe = array(); //array de DOMNode
    private $aInfNF = array(); //array de DOMNode
    private $aLacres = array(); //array de DOMNode
    private $aCondutor = array(); //array de DOMNode
    private $aReboque = array(); //array de DOMNode
    private $aDisp = array(); //array de DOMNode
    private $aVag = array(); //array de DOMNode
    private $aInfTermCarreg = array(); //array de DOMNode
    private $aInfTermDescarreg = array(); //array de DOMNode
    private $aInfEmbComb = array(); //array de DOMNode
    private $aCountDoc = array(); //contador de documentos fiscais
    
    /**
     * 
     * @return boolean
     */
    public function montaMDFe()
    {
        if (count($this->erros) > 0) {
            return false;
        }
        //cria a tag raiz da MDFe
        $this->zTagMDFe();
        //monta a tag ide com as tags adicionais
        $this->zTagIde();
        //tag ide [4]
        $this->dom->appChild($this->infMDFe, $this->ide, 'Falta tag "infMDFe"');
        //tag enderemit [30]
        $this->appChild($this->emit, $this->enderEmit, 'Falta tag "emit"');
        //tag emit [25]
        $this->dom->appChild($this->infMDFe, $this->emit, 'Falta tag "infMDFe"');
        //tag infModal [41]
        $this->zTagRodo();
        $this->zTagAereo();
        $this->zTagFerrov();
        $this->zTagAqua();
        $this->dom->appChild($this->infMDFe, $this->infModal, 'Falta tag "infMDFe"');
        //tag indDoc [44]
        $this->zTagInfDoc();
        //tag tot [68]
        $this->dom->appChild($this->infMDFe, $this->tot, 'Falta tag "infMDFe"');
        //tag lacres [76]
        $this->zTagLacres();
        //tag infAdic [78]
        $this->dom->appChild($this->infMDFe, $this->infAdic, 'Falta tag "infMDFe"');
        // testa da chave
        $this->zTestaChaveXML($this->dom);
        //convert DOMDocument para string
        $this->xml = $this->dom->saveXML();
        return true;
    }
    

    /**
     * taginfMDFe
     * Informações da MDFe 1 pai MDFe
     * tag MDFe/infMDFe
     * @param string $chave
     * @param string $versao
     * @return DOMElement
     */
    public function taginfMDFe($chave = '', $versao = '')
    {
        $this->infNFe = $this->dom->createElement("infMDFe");
        $this->infNFe->setAttribute("Id", 'MDFe'.$chave);
        $this->infNFe->setAttribute("versao", $versao);
        $this->versao = (int) $versao;
        $this->chMDFe = $chave;
        return $this->infMDFe;
    }
    
    /**
     * tgaide
     * Informações de identificação da MDFe 4 pai 1
     * tag MDFe/infMDFe/ide
     * 
     * @param string $cUF
     * @param string $tbAmb
     * @param string $tpEmit
     * @param string $mod
     * @param string $serie
     * @param string $nMDF
     * @param string $cMDF
     * @param string $cDV
     * @param string $modal
     * @param string $dhEmi
     * @param string $tpEmis
     * @param string $procEmi
     * @param string $verProc
     * @param string $ufIni
     * @param string $ufFim
     * @return DOMElement
     */
    public function tagide(
        $cUF = '',
        $tpAmb = '',
        $tpEmit = '',
        $mod = '58',
        $serie = '',
        $nMDF = '',
        $cMDF = '',
        $cDV = '',
        $modal = '',
        $dhEmi = '',
        $tpEmis = '',
        $procEmi = '',
        $verProc = '',
        $ufIni = '',
        $ufFim = ''
    ) {
        $this->tpAmb = $tpAmb;
        if ($dhEmi == '') {
            $dhEmi = DateTime::convertTimestampToSefazTime();
        }
        $identificador = '[4] <ide> - ';
        $ide = $this->dom->createElement("ide");
        $this->dom->addChild(
            $ide,
            "cUF",
            $cUF,
            true,
            $identificador . "Código da UF do emitente do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "tpAmb",
            $tpAmb,
            true,
            $identificador . "Identificação do Ambiente"
        );
        $this->dom->addChild(
            $ide,
            "tpEmit",
            $tpEmit,
            true,
            $identificador . "Indicador da tipo de emitente"
        );
        $this->dom->addChild(
            $ide,
            "mod",
            $mod,
            true,
            $identificador . "Código do Modelo do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "serie",
            $serie,
            true,
            $identificador . "Série do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "nMDF",
            $nMDF,
            true,
            $identificador . "Número do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "cMDF",
            $cMDF,
            true,
            $identificador . "Código do numérico do MDF"
        );
        $this->dom->addChild(
            $ide,
            "cDV",
            $cDV,
            true,
            $identificador . "Dígito Verificador da Chave de Acesso da NF-e"
        );
        $this->dom->addChild(
            $ide,
            "modal",
            $modal,
            true,
            $identificador . "Modalidade de transporte"
        );
        $this->dom->addChild(
            $ide,
            "dhEmi",
            $dhEmi,
            true,
            $identificador . "Data e hora de emissão do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "tpEmis",
            $tpEmis,
            true,
            $identificador . "Tipo de Emissão do Documento Fiscal"
        );
        $this->dom->addChild(
            $ide,
            "procEmi",
            $procEmi,
            true,
            $identificador . "Processo de emissão"
        );
        $this->dom->addChild(
            $ide,
            "verProc",
            $verProc,
            true,
            $identificador . "Versão do Processo de emissão"
        );
        $this->dom->addChild(
            $ide,
            "UFIni",
            $ufIni,
            true,
            $identificador . "Sigla da UF do Carregamento"
        );
        $this->dom->addChild(
            $ide,
            "UFFim",
            $ufFim,
            true,
            $identificador . "Sigla da UF do Descarregamento"
        );
        $this->mod = $mod;
        $this->ide = $ide;
        return $ide;
    }
    
    /**
     * tagInfMunCarrega
     * 
     * tag MDFe/infMDFe/ide/infMunCarrega
     * @param string $cMunCarrega
     * @param string $xMunCarrega
     * @return DOMElement
     */
    public function tagInfMunCarrega(
        $cMunCarrega = '',
        $xMunCarrega = ''
    ) {
        $infMunCarrega = $this->dom->createElement("infMunCarrega");
        $this->dom->addChild(
            $infMunCarrega,
            "cMunCarrega",
            $cMunCarrega,
            true,
            "Código do Município de Carregamento"
        );
        $this->dom->addChild(
            $infMunCarrega,
            "xMunCarrega",
            $xMunCarrega,
            true,
            "Nome do Município de Carregamento"
        );
        $this->aInfMunCarrega[] = $infMunCarrega;
        return $infMunCarrega;
    }
    
    /**
     * tagInfPercurso
     * 
     * tag MDFe/infMDFe/ide/infPercurso
     * @param string $ufPer
     * @return DOMElement
     */
    public function tagInfPercurso($ufPer = '')
    {
        $infPercurso = $this->dom->createElement("infPercurso");
        $this->dom->addChild(
            $infPercurso,
            "UFPer",
            $ufPer,
            true,
            "Sigla das Unidades da Federação do percurso"
        );
        $this->aInfPercurso[] = $infPercurso;
        return $infPercurso;
    }
    
    /**
     * tagemit
     * Identificação do emitente da MDFe [25] pai 1
     * tag MDFe/infMDFe/emit
     * @param string $cnpj
     * @param string $cpf
     * @param string $xNome
     * @param string $xFant
     * @param string $numIE
     * @param string $numIEST
     * @param string $numIM
     * @param string $cnae
     * @param string $crt
     * @return DOMElement
     */
    public function tagemit(
        $cnpj = '',
        $numIE = '',
        $xNome = '',
        $xFant = ''
    ) {
        $identificador = '[25] <emit> - ';
        $this->emit = $this->dom->createElement("emit");
        $this->dom->addChild($this->emit, "CNPJ", $cnpj, true, $identificador . "CNPJ do emitente");
        $this->dom->addChild($this->emit, "IE", $numIE, true, $identificador . "Inscrição Estadual do emitente");
        $this->dom->addChild($this->emit, "xNome", $xNome, true, $identificador . "Razão Social ou Nome do emitente");
        $this->dom->addChild($this->emit, "xFant", $xFant, false, $identificador . "Nome fantasia do emitente");
        return $this->emit;
    }
    
    /**
     * tagenderEmit
     * Endereço do emitente [30] pai [25]
     * tag MDFe/infMDFe/emit/endEmit
     * @param string $xLgr
     * @param string $nro
     * @param string $xCpl
     * @param string $xBairro
     * @param string $cMun
     * @param string $xMun
     * @param string $cep
     * @param string $siglaUF
     * @param string $fone
     * @param string $email
     * @return DOMElement
     */
    public function tagenderEmit(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $cep = '',
        $siglaUF = '',
        $fone = '',
        $email = ''
    ) {
        $identificador = '[30] <enderEmit> - ';
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->dom->addChild(
            $this->enderEmit,
            "xLgr",
            $xLgr,
            true,
            $identificador . "Logradouro do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "nro",
            $nro,
            true,
            $identificador . "Número do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xCpl",
            $xCpl,
            false,
            $identificador . "Complemento do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xBairro",
            $xBairro,
            true,
            $identificador . "Bairro do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "cMun",
            $cMun,
            true,
            $identificador . "Código do município do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "xMun",
            $xMun,
            true,
            $identificador . "Nome do município do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "CEP",
            $cep,
            true,
            $identificador . "Código do CEP do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "UF",
            $siglaUF,
            true,
            $identificador . "Sigla da UF do Endereço do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "fone",
            $fone,
            false,
            $identificador . "Número de telefone do emitente"
        );
        $this->dom->addChild(
            $this->enderEmit,
            "email",
            $email,
            false,
            $identificador . "Endereço de email do emitente"
        );
        return $this->enderEmit;
    }

    /**
     * tagInfMunDescarga
     * tag MDFe/infMDFe/infDoc/infMunDescarga
     * @param integer $item
     * @param string $cMunDescarga
     * @param string $xMunDescarga
     * @return DOMElement
     */
    public function tagInfMunDescarga(
        $nItem = 0,
        $cMunDescarga = '',
        $xMunDescarga = ''
    ) {
        $infMunDescarga = $this->dom->createElement("infMunDescarga");
        $this->dom->addChild(
            $infMunDescarga,
            "cMunDescarga",
            $cMunDescarga,
            true,
            "Código do Município de Descarga"
        );
        $this->dom->addChild(
            $infMunDescarga,
            "xMunDescarga",
            $xMunDescarga,
            true,
            "Nome do Município de Descarga"
        );
        $this->aInfMunDescarga[$nItem] = $infMunDescarga;
        return $infMunDescarga;
    }
    
    /**
     * tagInfCTe
     * tag MDFe/infMDFe/infDoc/infMunDescarga/infCTe
     * @param integer $nItem
     * @param string $chCTe
     * @param string $segCodBarra
     * @return DOMElement
     */
    public function tagInfCTe(
        $nItem = 0,
        $chCTe = '',
        $segCodBarra = ''
    ) {
        $infCTe = $this->dom->createElement("infCTe");
        $this->dom->addChild(
            $infCTe,
            "chCTe",
            $chCTe,
            true,
            "Chave de Acesso CTe"
        );
        $this->dom->addChild(
            $infCTe,
            "SegCodBarra",
            $segCodBarra,
            false,
            "Segundo código de barras do CTe"
        );
        $this->aInfCTe[$nItem][] = $infCTe;
        return $infCTe;
    }
    
    /**
     * tagInfCT
     * tag MDFe/infMDFe/infDoc/infMunDescarga/infCT
     * @param string $nItem
     * @param string $nCT
     * @param string $serie
     * @param string $subser
     * @param string $dEmi
     * @param string $vCarga
     * @return string
     */
    public function tagInfCT(
        $nItem = 0,
        $nCT = '',
        $serie = '',
        $subser = '',
        $dEmi = '',
        $vCarga = ''
    ) {
        $infCT = $this->dom->createElement("infCT");
        $this->dom->addChild(
            $infCT,
            "nCT",
            $nCT,
            true,
            "Número do CT"
        );
        $this->dom->addChild(
            $infCT,
            "serie",
            $serie,
            true,
            "Série do CT"
        );
        $this->dom->addChild(
            $infCT,
            "subser",
            $subser,
            false,
            "Subserie do CT"
        );
        $this->dom->addChild(
            $infCT,
            "dEmi",
            $dEmi,
            true,
            "Data de emissão do CT"
        );
        $this->dom->addChild(
            $infCT,
            "vCarga",
            $vCarga,
            true,
            "Valor total da carga do CT"
        );
        $this->aInfCT[$nItem][] = $infCT;
        return $infCT;
    }
 
    /**
     * tagInfNFe
     * tag MDFe/infMDFe/infDoc/infMunDescarga/infNFe
     * @param integer $nItem
     * @param string $chCTe
     * @param string $segCodBarra
     * @return DOMElement
     */
    public function tagInfNFe(
        $nItem = 0,
        $chNFe = '',
        $segCodBarra = ''
    ) {
        $infNFe = $this->dom->createElement("infNFe");
        $this->dom->addChild(
            $infNFe,
            "chNFe",
            $chNFe,
            true,
            "Chave de Acesso da NFe"
        );
        $this->dom->addChild(
            $infNFe,
            "SegCodBarra",
            $segCodBarra,
            false,
            "Segundo código de barras da NFe"
        );
        $this->aInfNFe[$nItem][] = $infNFe;
        return $infNFe;
    }
    
    /**
     * tagInfNFe
     * tag MDFe/infMDFe/infDoc/infMunDescarga/infNF
     * @param string $nItem
     * @param string $cnpj
     * @param string $siglaUF
     * @param string $nNF
     * @param string $serie
     * @param string $dEmi
     * @param string $vNF
     * @param string $pin
     * @return DOMElement
     */
    public function tagInfNF(
        $nItem = 0,
        $cnpj = '',
        $siglaUF = '',
        $nNF = '',
        $serie = '',
        $dEmi = '',
        $vNF = '',
        $pin = ''
    ) {
        $infNF = $this->dom->createElement("infNF");
        $this->dom->addChild(
            $infNF,
            "CNPJ",
            $cnpj,
            true,
            "CNPJ do emitente da NF"
        );
        $this->dom->addChild(
            $infNF,
            "UF",
            $siglaUF,
            true,
            "Sigla da unidade da federação do emitente da NF"
        );
        $this->dom->addChild(
            $infNF,
            "nNF",
            $nNF,
            true,
            "Número da NF"
        );
        $this->dom->addChild(
            $infNF,
            "serie",
            $serie,
            true,
            "Série da NF"
        );
        $this->dom->addChild(
            $infNF,
            "dEmi",
            $dEmi,
            true,
            "Data de emissão da NF"
        );
        $this->dom->addChild(
            $infNF,
            "vNF",
            $vNF,
            true,
            "Valor total da NF"
        );
        $this->dom->addChild(
            $infNF,
            "PIN",
            $pin,
            false,
            "PIN SUFRAMA da NF"
        );
        $this->aInfNF[$nItem][] = $infNF;
        return $infNF;
    }
    
    /**
     * tagTot
     * tag MDFe/infMDFe/tot
     * @param string $qCTe
     * @param string $qCT
     * @param string $qNFe
     * @param string $qNF
     * @param string $vCarga
     * @param string $cUnid
     * @param string $qCarga
     * @return DOMElement
     */
    public function tagTot(
        $qCTe = '',
        $qCT = '',
        $qNFe = '',
        $qNF = '',
        $vCarga = '',
        $cUnid = '',
        $qCarga = ''
    ) {
        $tot = $this->dom->createElement("tot");
        $this->dom->addChild(
            $tot,
            "qCTe",
            $qCTe,
            false,
            "Quantidade total de CT-e relacionados no Manifesto"
        );
        $this->dom->addChild(
            $tot,
            "qCT",
            $qCT,
            false,
            "Quantidade total de CT relacionados no Manifesto"
        );
        $this->dom->addChild(
            $tot,
            "qNFe",
            $qNFe,
            false,
            "Quantidade total de NF-e relacionados no Manifesto"
        );
        $this->dom->addChild(
            $tot,
            "qNF",
            $qNF,
            false,
            "Quantidade total de NF relacionados no Manifesto"
        );
        $this->dom->addChild(
            $tot,
            "vCarga",
            $vCarga,
            true,
            "Valor total da mercadoria/carga transportada"
        );
        $this->dom->addChild(
            $tot,
            "cUnid",
            $cUnid,
            true,
            "Código da unidade de medida do Peso Bruto da Carga / Mercadoria Transportada"
        );
        $this->dom->addChild(
            $tot,
            "qCarga",
            $qCarga,
            true,
            "Peso Bruto Total da Carga / Mercadoria Transportada"
        );
        $this->tot = $tot;
        return $tot;
    }
    
    /**
     * tagLacres
     * tag MDFe/infMDFe/lacres
     * @param string $nLacre
     * @return DOMElement
     */
    public function tagLacres(
        $nLacre = ''
    ) {
        $lacres = $this->dom->createElement("lacres");
        $this->dom->addChild(
            $lacres,
            "nLacre",
            $nLacre,
            false,
            "Número do lacre"
        );
        $this->aLacres[] = $lacres;
        return $lacres;
    }
    
    /**
     * taginfAdic
     * Grupo de Informações Adicionais Z01 pai A01
     * tag MDFe/infMDFe/infAdic (opcional)
     * @param string $infAdFisco
     * @param string $infCpl
     * @return DOMElement
     */
    public function taginfAdic(
        $infAdFisco = '',
        $infCpl = ''
    ) {
        $infAdic = $this->dom->createElement("infAdic");
        $this->dom->addChild(
            $infAdic,
            "infAdFisco",
            $infAdFisco,
            false,
            "Informações Adicionais de Interesse do Fisco"
        );
        $this->dom->addChild(
            $infAdic,
            "infCpl",
            $infCpl,
            false,
            "Informações Complementares de interesse do Contribuinte"
        );
        $this->infAdic = $infAdic;
        return $infAdic;
    }
    
    /**
     * tagInfModal
     * tag MDFe/infMDFe/infModal
     * @param type $versaoModal
     * @return DOMElement
     */
    public function tagInfModal($versaoModal = '')
    {
        $infModal = $this->dom->createElement("infModal");
        $this->dom->addChild(
            $infModal,
            "versaoModal",
            $versaoModal,
            false,
            "Versão do leiaute específico para o Modal"
        );
        $this->infModal = $infModal;
        return $infModal;
    }
    
    /**
     * tagAereo
     * tag MDFe/infMDFe/infModal/aereo
     * @param string $nac
     * @param string $matr
     * @param string $nVoo
     * @param string $cAerEmb
     * @param string $cAerDes
     * @param string $dVoo
     * @return DOMElement
     */
    public function tagAereo(
        $nac = '',
        $matr = '',
        $nVoo = '',
        $cAerEmb = '',
        $cAerDes = '',
        $dVoo = ''
    ) {
        $aereo = $this->dom->createElement("aereo");
        $this->dom->addChild(
            $aereo,
            "nac",
            $nac,
            true,
            "Marca da Nacionalidade da aeronave"
        );
        $this->dom->addChild(
            $aereo,
            "matr",
            $matr,
            true,
            "Marca de Matrícula da aeronave"
        );
        $this->dom->addChild(
            $aereo,
            "nVoo",
            $nVoo,
            true,
            "Número do Vôo"
        );
        $this->dom->addChild(
            $aereo,
            "cAerEmb",
            $cAerEmb,
            true,
            "Aeródromo de Embarque - Código IATA"
        );
        $this->dom->addChild(
            $aereo,
            "cAerDes",
            $cAerDes,
            true,
            "Aeródromo de Destino - Código IATA"
        );
        $this->dom->addChild(
            $aereo,
            "dVoo",
            $dVoo,
            true,
            "Data do Vôo"
        );
        $this->aereo = $aereo;
        return $aereo;
    }
    
    /**
     * tagTrem
     * tag MDFe/infMDFe/infModal/ferrov/trem
     * @param string $xPref
     * @param string $dhTrem
     * @param string $xOri
     * @param string $xDest
     * @param string $qVag
     * @return DOMElement
     */
    public function tagTrem(
        $xPref = '',
        $dhTrem = '',
        $xOri = '',
        $xDest = '',
        $qVag = ''
    ) {
        $trem = $this->dom->createElement("trem");
        $this->dom->addChild(
            $trem,
            "xPref",
            $xPref,
            true,
            "Prefixo do Trem"
        );
        $this->dom->addChild(
            $trem,
            "dhTrem",
            $dhTrem,
            false,
            "Data e hora de liberação do trem na origem"
        );
        $this->dom->addChild(
            $trem,
            "xOri",
            $xOri,
            true,
            "Origem do Trem"
        );
        $this->dom->addChild(
            $trem,
            "xDest",
            $xDest,
            true,
            "Destino do Trem"
        );
        $this->dom->addChild(
            $trem,
            "qVag",
            $qVag,
            true,
            "Quantidade de vagões"
        );
        $this->trem = $trem;
        return $trem;
    }
    
    /**
     * tagVag
     * tag MDFe/infMDFe/infModal/ferrov/trem/vag
     * @param string $serie
     * @param string $nVag
     * @param string $nSeq
     * @param string $tUtil
     * @return DOMElement
     */
    public function tagVag(
        $serie = '',
        $nVag = '',
        $nSeq = '',
        $tonUtil = ''
    ) {
        $vag = $this->dom->createElement("vag");
        $this->dom->addChild(
            $vag,
            "serie",
            $serie,
            true,
            "Série de Identificação do vagão"
        );
        $this->dom->addChild(
            $vag,
            "nVag",
            $nVag,
            true,
            "Número de Identificação do vagão"
        );
        $this->dom->addChild(
            $vag,
            "nSeq",
            $nSeq,
            false,
            "Sequência do vagão na composição"
        );
        $this->dom->addChild(
            $vag,
            "TU",
            $tonUtil,
            true,
            "Tonelada Útil"
        );
        $this->aVag[] = $vag;
        return $vag;
    }
    
    /**
     * tagAqua
     * tag MDFe/infMDFe/infModal/Aqua
     * @param string $cnpjAgeNav
     * @param string $tpEmb
     * @param string $cEmbar
     * @param string $nViagem
     * @param string $cPrtEmb
     * @param string $cPrtDest
     * @return DOMElement
     */
    public function tagAqua(
        $cnpjAgeNav = '',
        $tpEmb = '',
        $cEmbar = '',
        $nViagem = '',
        $cPrtEmb = '',
        $cPrtDest = ''
    ) {
        $aqua = $this->dom->createElement("Aqua");
        $this->dom->addChild(
            $aqua,
            "CNPJAgeNav",
            $cnpjAgeNav,
            true,
            "CNPJ da Agência de Navegação"
        );
        $this->dom->addChild(
            $aqua,
            "tpEmb",
            $tpEmb,
            true,
            "Código do tipo de embarcação"
        );
        $this->dom->addChild(
            $aqua,
            "cEmbar",
            $cEmbar,
            true,
            "Código da Embarcação"
        );
        $this->dom->addChild(
            $aqua,
            "nViagem",
            $nViagem,
            true,
            "Número da Viagem"
        );
        $this->dom->addChild(
            $aqua,
            "cPrtEmb",
            $cPrtEmb,
            true,
            "Código do Porto de Embarque"
        );
        $this->dom->addChild(
            $aqua,
            "cPrtDest",
            $cPrtDest,
            true,
            "Código do Porto de Destino"
        );
        $this->aqua = $aqua;
        return $aqua;
    }
    
    /**
     * tagInfTermCarreg
     * tag MDFe/infMDFe/infModal/Aqua/infTermCarreg
     * @param string $cTermCarreg
     * @return DOMElement
     */
    public function tagInfTermCarreg(
        $cTermCarreg = ''
    ) {
        $infTermCarreg = $this->dom->createElement("infTermCarreg");
        $this->dom->addChild(
            $infTermCarreg,
            "cTermCarreg",
            $cTermCarreg,
            true,
            "Código do Terminal de Carregamento"
        );
        $this->aInfTermCarreg[] = $infTermCarreg;
        return $infTermCarreg;
    }

    /**
     * tagInfTermDescarreg
     * tag MDFe/infMDFe/infModal/Aqua/infTermDescarreg
     * @param string $cTermDescarreg
     * @return DOMElement
     */
    public function tagInfTermDescarreg(
        $cTermDescarreg = ''
    ) {
        $infTermDescarreg = $this->dom->createElement("infTermDescarreg");
        $this->dom->addChild(
            $infTermDescarreg,
            "cTermCarreg",
            $cTermDescarreg,
            true,
            "Código do Terminal de Descarregamento"
        );
        $this->aInfTermDescarreg[] = $infTermDescarreg;
        return $infTermDescarreg;
    }

    /**
     * tagInfEmbComb
     * tag MDFe/infMDFe/infModal/Aqua/infEmbComb
     * @param string $$cEmbComb
     * @return DOMElement
     */
    public function tagInfEmbComb(
        $cEmbComb = ''
    ) {
        $infEmbComb = $this->dom->createElement("infEmbComb");
        $this->dom->addChild(
            $infEmbComb,
            "cEmbComb",
            $cEmbComb,
            true,
            "Código da embarcação do comboio"
        );
        $this->aInfEmbComb[] = $infEmbComb;
        return $infEmbComb;
    }
    
    /**
     * tagRodo
     * tag MDFe/infMDFe/infModal/rodo
     * @param string $rntrc
     * @param string $ciot
     * @return DOMElement
     */
    public function tagRodo(
        $rntrc = '',
        $ciot = ''
    ) {
        $rodo = $this->dom->createElement("rodo");
        $this->dom->addChild(
            $rodo,
            "RNTRC",
            $rntrc,
            false,
            "Registro Nacional de Transportadores Rodoviários de Carga"
        );
        $this->dom->addChild(
            $rodo,
            "CIOT",
            $ciot,
            false,
            "Código Identificador da Operação de Transporte"
        );
        $this->rodo = $rodo;
        return $rodo;
    }
    
    /**
     * tagVeicPrincipal
     * tag MDFe/infMDFe/infModal/rodo/veicPrincipal
     * @param string $cInt
     * @param string $placa
     * @param string $tara
     * @param string $capKG
     * @param string $capM3
     * @param string $propRNTRC
     * @return DOMElement
     */
    public function tagVeicPrincipal(
        $cInt = '',
        $placa = '',
        $tara = '',
        $capKG = '',
        $capM3 = '',
        $propRNTRC = ''
    ) {
        $veicPrincipal = $this->zTagVeiculo('veicPrincipal', $cInt, $placa, $tara, $capKG, $capM3, $propRNTRC);
        $this->veicPrincipal = $veicPrincipal;
        return $veicPrincipal;
    }
    
    /**
     * tagCondutor
     * tag MDFe/infMDFe/infModal/rodo/veicPrincipal/condutor
     * @param string $xNome
     * @param string $cpf
     * @return DOMElement
     */
    public function tagCondutor(
        $xNome = '',
        $cpf = ''
    ) {
        $condutor = $this->dom->createElement("condutor");
        $this->dom->addChild(
            $condutor,
            "xNome",
            $xNome,
            true,
            "Nome do condutor"
        );
        $this->dom->addChild(
            $condutor,
            "CPF",
            $cpf,
            true,
            "CPF do condutor"
        );
        $this->aCondutor[] = $condutor;
        return $condutor;
    }
    
    /**
     * tagVeicReboque
     * tag MDFe/infMDFe/infModal/rodo/reboque
     * @param type $cInt
     * @param type $placa
     * @param type $tara
     * @param type $capKG
     * @param type $capM3
     * @param type $propRNTRC
     * @return DOMElement
     */
    public function tagVeicReboque(
        $cInt = '',
        $placa = '',
        $tara = '',
        $capKG = '',
        $capM3 = '',
        $propRNTRC = ''
    ) {
        $reboque = $this->zTagVeiculo('reboque', $cInt, $placa, $tara, $capKG, $capM3, $propRNTRC);
        $this->aReboque[] = $reboque;
        return $reboque;
    }

    /**
     * tagValePed
     * tag MDFe/infMDFe/infModal/rodo/valePed
     * @param type $cnpjForn
     * @param type $cnpjPg
     * @param type $nCompra
     * @return DOMElement
     */
    public function tagValePed(
        $cnpjForn = '',
        $cnpjPg = '',
        $nCompra = ''
    ) {
        $disp = $this->dom->createElement($disp);
        $this->dom->addChild(
            $disp,
            "CNPJForn",
            $cnpjForn,
            true,
            "CNPJ da empresa fornecedora do Vale-Pedágio"
        );
        $this->dom->addChild(
            $disp,
            "CNPJPg",
            $cnpjPg,
            false,
            "CNPJ do responsável pelo pagamento do Vale-Pedágio"
        );
        $this->dom->addChild(
            $disp,
            "nCompra",
            $nCompra,
            true,
            "Número do comprovante de compra"
        );
        $this->aDisp[] = $disp;
        return $disp;
    }
    
    /**
     * zTagVeiculo
     * @param string $cInt
     * @param string $placa
     * @param string $tara
     * @param string $capKG
     * @param string $capM3
     * @param string $propRNTRC
     * @return DOMElement
     */
    protected function zTagVeiculo(
        $tag = '',
        $cInt = '',
        $placa = '',
        $tara = '',
        $capKG = '',
        $capM3 = '',
        $propRNTRC = ''
    ) {
        $node = $this->dom->createElement($tag);
        $this->dom->addChild(
            $node,
            "cInt",
            $cInt,
            false,
            "Código interno do veículo"
        );
        $this->dom->addChild(
            $node,
            "placa",
            $placa,
            true,
            "Placa do veículo"
        );
        $this->dom->addChild(
            $node,
            "tara",
            $tara,
            true,
            "Tara em KG"
        );
        $this->dom->addChild(
            $node,
            "capKG",
            $capKG,
            false,
            "Capacidade em KG"
        );
        $this->dom->addChild(
            $node,
            "capM3",
            $capM3,
            false,
            "Capacidade em M3"
        );
        if ($propRNTRC != '') {
            $prop = $this->dom->createElement("prop");
            $this->dom->addChild(
                $prop,
                "RNTRC",
                $propRNTRC,
                true,
                "Registro Nacional dos Transportadores Rodoviários de Carga"
            );
            $this->dom->appChild($node, $prop, '');
        }
        return $node;
    }
    
    /**
     * zTagMDFe
     * Tag raiz da MDFe
     * tag MDFe DOMNode
     * Função chamada pelo método [ monta ]
     * @return DOMElement
     */
    protected function zTagMDFe()
    {
        if (empty($this->MDFe)) {
            $this->MDFe = $this->dom->createElement("MDFe");
            $this->MDFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/mdfe");
        }
        return $this->MDFe;
    }
    
    /**
     * Adiciona as tags
     * infMunCarrega e infPercurso
     * a tag ide
     */
    protected function zTagIde()
    {
        $this->dom->addArrayChild($this->ide, $this->aInfMunCarrega);
        $this->dom->addArrayChild($this->ide, $this->aInfPercurso);
    }
    
    /**
     * Processa lacres
     */
    protected function zTagLacres()
    {
        $this->dom->addArrayChild($this->infMDFe, $this->aLacres);
    }

    /**
     * Proecessa documentos fiscais
     */
    protected function zTagInfDoc()
    {
        $this->aCountDoc = array('CTe'=>0, 'CT'=>0, 'NFe'=>0, 'NF'=>0);
        if (! empty($this->aInfMunDescarga)) {
            $infDoc = $this->dom->createElement("infDoc");
            foreach ($this->aInfMunDescarga as $nItem => $node) {
                $this->aCountDoc['CTe'] = $this->dom->addArrayChild($node, $this->aInfCTe[$nItem]);
                $this->aCountDoc['CT'] = $this->dom->addArrayChild($node, $this->aInfCT[$nItem]);
                $this->aCountDoc['NFe'] = $this->dom->addArrayChild($node, $this->aInfNFe[$nItem]);
                $this->aCountDoc['NF'] = $this->dom->addArrayChild($node, $this->aInfNF[$nItem]);
                $this->dom->appChild($infDoc, $node, '');
            }
            $this->dom->appChild($this->infMDFe, $infDoc, 'Falta tag "infModal"');
        }
        //ajusta quantidades em tot
        $this->tot->getElementsByTagName('qCTe')->item(0)->nodeValue = $this->aCountDoc['CTe'];
        $this->tot->getElementsByTagName('qCT')->item(0)->nodeValue = $this->aCountDoc['CT'];
        $this->tot->getElementsByTagName('qNFe')->item(0)->nodeValue = $this->aCountDoc['NFe'];
        $this->tot->getElementsByTagName('qNF')->item(0)->nodeValue = $this->aCountDoc['NF'];
    }

    /**
     * Processa modal rodoviario
     */
    protected function zTagRodo()
    {
        if (! empty($this->rodo)) {
            $this->dom->addArrayChild($this->veicPrincipal, $this->aCondutor);
            $this->dom->appChild($this->rodo, $this->veicPrincipal, 'Falta tag "rodo"');
            $this->dom->addArrayChild($this->rodo, $this->aReboque);
            if (! empty($this->aDisp)) {
                $valePed = $this->dom->createElement("valePed");
                foreach ($this->aDisp as $node) {
                    $this->dom->appChild($valePed, $node, '');
                }
                $this->dom->appChild($this->rodo, $valePed, '');
            }
            $this->dom->appChild($this->infModal, $this->rodo, 'Falta tag "infModal"');
        }
    }

    /**
     * Proecessa modal ferroviario
     */
    protected function zTagFerrov()
    {
        if (! empty($this->trem)) {
            $this->dom->addArrayChild($this->trem, $this->aVag);
            $ferrov = $this->dom->createElement("ferrov");
            $this->dom->appChild($ferrov, $this->trem, '');
            $this->dom->appChild($this->infModal, $ferrov, 'Falta tag "infModal"');
        }
    }
    
    /**
     * Processa modal aereo
     */
    protected function zTagAereo()
    {
        if (! empty($this->aereo)) {
            $this->dom->appChild($this->infModal, $this->aereo, 'Falta tag "infModal"');
        }
    }
    
    /**
     * Processa modal aquaviário
     */
    protected function zTagAqua()
    {
        if (! empty($this->aqua)) {
            $this->dom->addArrayChild($this->aqua, $this->aInfTermCarreg);
            $this->dom->addArrayChild($this->aqua, $this->aInfTermDescarreg);
            $this->dom->addArrayChild($this->aqua, $this->aInfEmbComb);
            $this->dom->appChild($this->infModal, $this->aqua, 'Falta tag "infModal"');
        }
    }
    
    /**
     * zTestaChaveXML
     * Remonta a chave da NFe de 44 digitos com base em seus dados
     * Isso é útil no caso da chave informada estar errada
     * se a chave estiver errada a mesma é substituida
     * @param object $dom
     */
    private function zTestaChaveXML($dom)
    {
        $infMDFe= $dom->getElementsByTagName("infNFe")->item(0);
        $ide = $dom->getElementsByTagName("ide")->item(0);
        $emit = $dom->getElementsByTagName("emit")->item(0);
        $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $dhEmi = $ide->getElementsByTagName('dhEmi')->item(0)->nodeValue;
        $cnpj = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nNF = $ide->getElementsByTagName('nMDF')->item(0)->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cNF = $ide->getElementsByTagName('cMDF')->item(0)->nodeValue;
        $chave = str_replace('MDFe', '', $infMDFe->getAttribute("Id"));
        $tempData = explode("-", $dhEmi);
        $chaveMontada = $this->montaChave(
            $cUF,
            $tempData[0] - 2000,
            $tempData[1],
            $cnpj,
            $mod,
            $serie,
            $nNF,
            $tpEmis,
            $cNF
        );
        //caso a chave contida na NFe esteja errada
        //substituir a chave
        if ($chaveMontada != $chave) {
            $ide->getElementsByTagName('cDV')->item(0)->nodeValue = substr($chaveMontada, -1);
            $infMDFe = $dom->getElementsByTagName("infMDFe")->item(0);
            $infMDFe->setAttribute("Id", "MDFe" . $chaveMontada);
            $this->chMDFe = $chaveMontada;
        }
    }
}
