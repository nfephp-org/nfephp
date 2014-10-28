<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * Estrutura baseada nas notas técnicas:
 *          NT2013.005 versão 1.02 Dezembro de 2013
 *          
 * 
 * @package     NFePHP
 * @name        MakeNFePHP
 * @version     0.1.11
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * 
 *        CONTRIBUIDORES (em ordem alfabetica):
 *
 *              Cleiton Perin <cperin20 at gmail dot com>
 *              Elias Müller <elias at oxigennio dot com dot br>
 *              Marcos Vinicios Balbi <marcusbalbi at hotmail dot com>
 * 
 */

//namespace NFe;

//use \DOMDocument;
//use \DOMElement;

class MakeNFe
{
    /**
     * erros
     * Matriz contendo os erros reportados pelas tags obrigatórias
     * e sem conteúdo
     * @var array
     */
    public $erros = array();
    /**
     * versao
     * numero da versão do xml da NFe
     * @var double
     */
    public $versao = 3.10;
    /**
     * mod
     * modelo da nfe por ser 55-NFe ou 65-NFCe
     * @var integer
     */
    public $mod = 55;
    /**
     * xml
     * String com o xml da NFe montado
     * @var string
     */
    public $xml = '';
    /**
     * dom
     * Variável onde será montado o xml da NFe
     * @var DOMDocument
     */
    public $dom; //DOMDocument
    
    //propriedades privadas utilizadas internamente pela classe
    private $NFe = ''; //DOMNode
    private $infNFe = ''; //DOMNode
    private $ide = ''; //DOMNode
    private $emit = ''; //DOMNode
    private $enderEmit = ''; //DOMNode
    private $dest = ''; //DOMNode
    private $enderDest = ''; //DOMNode
    private $retirada = ''; //DOMNode
    private $entrega = ''; //DOMNode
    private $total = ''; //DOMNode
    private $pag = ''; //DOMNode
    private $cobr = ''; //DOMNode
    private $transp = ''; //DOMNode
    private $infAdic = ''; //DOMNode
    private $exporta = ''; //DOMNode
    private $compra = ''; //DOMNode
    private $cana = ''; //DOMNode
    // Arrays
    private $aNFref = array(); //array de DOMNode
    private $aDup = array(); //array de DOMNodes
    private $aReboque = array(); //array de DOMNodes
    private $aVol = array(); //array de DOMNodes
    private $aAutXML = array(); //array de DOMNodes
    private $aDet = array(); //array de DOMNodes
    private $aProd = array(); //array de DOMNodes
    private $aDetExport = array(); //array de DOMNodes
    private $aDI = array(); //array de DOMNodes
    private $aAdi = array(); //array de DOMNodes
    private $aVeicProd = array(); //array de DOMNodes
    private $aMed = array(); //array de DOMNodes
    private $aArma = array(); //array de DOMNodes
    private $aComb = array(); //array de DOMNodes
    private $aImposto = array(); //array de DOMNodes
    private $aICMS = array(); //array de DOMNodes
    private $aICMSST = array(); //array de DOMNodes
    private $aICMSSN = array(); //array de DOMNodes
    private $aIPI = array(); //array de DOMNodes
    private $aII = array(); //array de DOMNodes
    private $aISSQN = array(); //array de DOMNodes
    private $aPIS = array(); //array de DOMNodes
    private $aPISST = array(); //array de DOMNodes
    private $aCOFINS = array(); //array de DOMNodes
    private $aCOFINSST = array(); //array de DOMNodes
    private $aImpostoDevol = array(); //array de DOMNodes
    private $aInfAdProd = array(); //array de DOMNodes
    private $aObsCont = array(); //array de DOMNodes
    private $aObsFisco = array(); //array de DOMNodes
    private $aProcRef = array(); //array de DOMNodes
    private $aForDia = array(); //array de DOMNodes
    private $aDeduc = array(); //array de DOMNodes
    
    /**
     * __contruct
     * Função construtora cria um objeto DOMDocument
     * que será carregado com a NFe
     * 
     * @return none
     */
    public function __construct($formatOutput = true, $preserveWhiteSpace = false)
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = $formatOutput;
        $this->dom->preserveWhiteSpace = $preserveWhiteSpace;
    }
    
    /**
     * getXML
     * retorna o xml da NFe que foi montado
     * @return string
     */
    public function getXML()
    {
        return $this->xml;
    }
    
    /**
     * montaNFe
     * Método de montagem do xml da NFe 
     * essa função retorna TRUE em caso de sucesso ou FALSE se houve erro
     * O xml da NFe deve ser recuperado pela funçao getXML() ou diretamente pela
     * propriedade publica $xml
     * @return boolean
     */
    public function montaNFe()
    {
        if (count($this->erros) > 0) {
            return false;
        }
        //cria a tag raiz da Nfe
        $this->zTagNFe();
        //processa nfeRef e coloca as tags na tag ide
        foreach ($this->aNFref as $nfeRef) {
            $this->zAppChild($this->ide, $nfeRef, 'Falta tag "ide"');
        }
        //monta as tags det com os detalhes dos produtos
        $this->zTagdet();
        //[2] tag ide (5 B01)
        $this->zAppChild($this->infNFe, $this->ide, 'Falta tag "infNFe"');
        //[8] tag emit (30 C01)
        $this->zAppChild($this->infNFe, $this->emit, 'Falta tag "infNFe"');
        //[10] tag dest (62 E01)
        $this->zAppChild($this->infNFe, $this->dest, 'Falta tag "infNFe"');
        //[12] tag retirada (80 F01)
        $this->zAppChild($this->infNFe, $this->retirada, 'Falta tag "infNFe"');
        //[13] tag entrega (89 G01)
        $this->zAppChild($this->infNFe, $this->entrega, 'Falta tag "infNFe"');
        //[14] tag autXML (97a.1 G50)
        foreach ($this->aAutXML as $aut) {
            $this->zAppChild($this->infNFe, $aut, 'Falta tag "infNFe"');
        }
        //[14a] tag det (98 H01)
        foreach ($this->aDet as $det) {
            $this->zAppChild($this->infNFe, $det, 'Falta tag "infNFe"');
        }
        //[28a] tag total (326 W01)
        $this->zAppChild($this->infNFe, $this->total, 'Falta tag "infNFe"');
        //[32] tag transp (356 X01)
        $this->zAppChild($this->infNFe, $this->transp, 'Falta tag "infNFe"');
        //[39a] tag cobr (389 Y01)
        $this->zAppChild($this->infNFe, $this->cobr, 'Falta tag "infNFe"');
        //[42] tag pag (398a YA01)
        $this->zAppChild($this->infNFe, $this->pag, 'Falta tag "infNFe"');
        //[44] tag infAdic (399 Z01)
        $this->zAppChild($this->infNFe, $this->infAdic, 'Falta tag "infNFe"');
        //[48] tag exporta (402 ZA01)
        $this->zAppChild($this->infNFe, $this->exporta, 'Falta tag "infNFe"');
        //[49] tag compra (405 ZB01)
        $this->zAppChild($this->infNFe, $this->compra, 'Falta tag "infNFe"');
        //[50] tag cana (409 ZC01)
        $this->zAppChild($this->infNFe, $this->cana, 'Falta tag "infNFe"');
        //[1] tag infNFe (1 A01)
        $this->zAppChild($this->NFe, $this->infNFe, 'Falta tag "NFe"');
        //[0] tag NFe
        $this->zAppChild($this->dom, $this->NFe, 'Falta DOMDocument');
        $this->xml = $this->dom->saveXML();
        return true;
    }
    
    /**
     * taginfNFe
     * Informações da NF-e A01 pai NFe
     * tag NFe/infNFe
     * @param string $chave
     * @param string $versao
     * @return DOMElement
     */
    public function taginfNFe($chave = '', $versao = '')
    {
        $this->infNFe = $this->dom->createElement("infNFe");
        $this->infNFe->setAttribute("Id", 'NFe'.$chave);
        $this->infNFe->setAttribute("versao", $versao);
        //$this->infNFe->setAttribute("pk_nItem",'');
        $this->versao = (int) $versao;
        return $this->infNFe;
    }
    
    /**
     * tgaide
     * Informações de identificação da NF-e B01 pai A01
     * tag NFe/infNFe/ide
     * @param string $cUF
     * @param string $cNF
     * @param string $natOp
     * @param string $indPag
     * @param string $mod
     * @param string $serie
     * @param string $nNF
     * @param string $dhEmi
     * @param string $dhSaiEnt
     * @param string $tpNF
     * @param string $idDest
     * @param string $cMunFG
     * @param string $tpImp
     * @param string $tpEmis
     * @param string $cDV
     * @param string $tpAmb
     * @param string $finNFe
     * @param string $indFinal
     * @param string $indPres
     * @param string $procEmi
     * @param string $verProc
     * @param string $dhCont
     * @param string $xJust
     * @return DOMElement
     */
    public function tagide(
        $cUF = '',
        $cNF = '',
        $natOp = '',
        $indPag = '',
        $mod = '',
        $serie = '',
        $nNF = '',
        $dhEmi = '',
        $dhSaiEnt = '',
        $tpNF = '',
        $idDest = '',
        $cMunFG = '',
        $tpImp = '',
        $tpEmis = '',
        $cDV = '',
        $tpAmb = '',
        $finNFe = '',
        $indFinal = '',
        $indPres = '',
        $procEmi = '',
        $verProc = '',
        $dhCont = '',
        $xJust = ''
    ) {
        $identificador = 'B01 <ide> - ';
        $ide = $this->dom->createElement("ide");
        $this->zAddChild($ide, "cUF", $cUF, true, $identificador . "Código da UF do emitente do Documento Fiscal");
        $this->zAddChild($ide, "cNF", $cNF, true, $identificador . "Código Numérico que compõe a Chave de Acesso");
        $this->zAddChild($ide, "natOp", $natOp, true, $identificador . "Descrição da Natureza da Operaçãoo");
        $this->zAddChild($ide, "indPag", $indPag, true, $identificador . "Indicador da forma de pagamento");
        $this->zAddChild($ide, "mod", $mod, true, $identificador . "Código do Modelo do Documento Fiscal");
        $this->zAddChild($ide, "serie", $serie, true, $identificador . "Série do Documento Fiscal");
        $this->zAddChild($ide, "nNF", $nNF, true, $identificador . "Número do Documento Fiscal");
        $this->zAddChild($ide, "dhEmi", $dhEmi, true, $identificador . "Data e hora de emissão do Documento Fiscal");
        if ($mod == '55' && $dhSaiEnt != '') {
            $this->zAddChild(
                $ide,
                "dhSaiEnt",
                $dhSaiEnt,
                false,
                $identificador . "Data e hora de Saída ou da Entrada da Mercadoria/Produto"
            );
        }
        $this->zAddChild($ide, "tpNF", $tpNF, true, $identificador . "Tipo de Operação");
        $this->zAddChild(
            $ide,
            "idDest",
            $idDest,
            true,
            $identificador . "Identificador de local de destino da operação"
        );
        $this->zAddChild(
            $ide,
            "cMunFG",
            $cMunFG,
            true,
            $identificador . "Código do Município de Ocorrência do Fato Gerador"
        );
        $this->zAddChild($ide, "tpImp", $tpImp, true, $identificador . "Formato de Impressão do DANFE");
        $this->zAddChild($ide, "tpEmis", $tpEmis, true, $identificador . "Tipo de Emissão da NF-e");
        $this->zAddChild($ide, "cDV", $cDV, true, $identificador . "Dígito Verificador da Chave de Acesso da NF-e");
        $this->zAddChild($ide, "tpAmb", $tpAmb, true, $identificador . "Identificação do Ambiente");
        $this->zAddChild($ide, "finNFe", $finNFe, true, $identificador . "Finalidade de emissão da NF-e");
        $this->zAddChild($ide, "indFinal", $indFinal, true, $identificador . "Indica operação com Consumidor final");
        $this->zAddChild(
            $ide,
            "indPres",
            $indPres,
            true,
            $identificador . "Indicador de presença do comprador no estabelecimento comercial no momento da operação"
        );
        $this->zAddChild($ide, "procEmi", $procEmi, true, $identificador . "Processo de emissão da NF-e");
        $this->zAddChild($ide, "verProc", $verProc, true, $identificador . "Versão do Processo de emissão da NF-e");
        if ($dhCont != '' && $xJust != '') {
            $this->zAddChild($ide, "dhCont", $dhCont, true, $identificador . "Data e Hora da entrada em contingência");
            $this->zAddChild($ide, "xJust", $xJust, true, $identificador . "Justificativa da entrada em contingência");
        }
        $this->mod = $mod;
        $this->ide = $ide;
        return $ide;
    }

    
    /**
     * tagrefNFe
     * Chave de acesso da NF-e referenciada BA02 pai BA01
     * tag NFe/infNFe/ide/NFref/refNFe
     * @param string $refNFe
     * @return DOMElement
     */
    public function tagrefNFe($refNFe = '')
    {
        $num = $this->zTagNFref();
        $refNFe = $this->dom->createElement("refNFe", $refNFe);
        $this->zAppChild($this->aNFref[$num-1], $refNFe);
        return $refNFe;
    }
    
    /**
     * tagrefNF
     * Informação da NF modelo 1/1A referenciada BA03 pai BA01
     * tag NFe/infNFe/ide/NFref/NF DOMNode
     * @param string $cUF
     * @param string $aamm
     * @param string $cnpj
     * @param string $mod
     * @param string $serie
     * @param string $nNF
     * @return DOMElement
     */
    public function tagrefNF(
        $cUF = '',
        $aamm = '',
        $cnpj = '',
        $mod = '',
        $serie = '',
        $nNF = ''
    ) {
        $identificador = 'BA03 <refNF> - ';
        $num = $this->zTagNFref();
        $refNF = $this->dom->createElement("refNF");
        $this->zAddChild($refNF, "cUF", $cUF, true, $identificador . "Código da UF do emitente");
        $this->zAddChild($refNF, "AAMM", $aamm, true, $identificador . "Ano e Mês de emissão da NF-e");
        $this->zAddChild($refNF, "CNPJ", $cnpj, true, $identificador . "CNPJ do emitente");
        $this->zAddChild($refNF, "mod", $mod, true, $identificador . "Modelo do Documento Fiscal");
        $this->zAddChild($refNF, "serie", $serie, true, $identificador . "Série do Documento Fiscal");
        $this->zAddChild($refNF, "nNF", $nNF, true, $identificador . "Número do Documento Fiscal");
        $this->zAppChild($this->aNFref[$num-1], $refNF);
        return $refNF;
    }
    
    /**
     * tagrefNFP
     * Informações da NF de produtor rural referenciada BA10 pai BA01
     * tag NFe/infNFe/ide/NFref/refNFP
     * @param string $cUF
     * @param string $aamm
     * @param string $cnpj
     * @param string $cpf
     * @param string $numIE
     * @param string $mod
     * @param string $serie
     * @param string $nNF
     * @return DOMElement
     */
    public function tagrefNFP(
        $cUF = '',
        $aamm = '',
        $cnpj = '',
        $cpf = '',
        $numIE = '',
        $mod = '',
        $serie = '',
        $nNF = ''
    ) {
        $identificador = 'BA10 <refNFP> - ';
        $num = $this->zTagNFref();
        $refNFP = $this->dom->createElement("refNFP");
        $this->zAddChild($refNFP, "cUF", $cUF, true, $identificador . "Código da UF do emitente");
        $this->zAddChild($refNFP, "AAMM", $aamm, true, $identificador . "AAMM da emissão da NF de produtor");
        $this->zAddChild(
            $refNFP,
            "CNPJ",
            $cnpj,
            true,
            $identificador . "Informar o CNPJ do emitente da NF de produtor"
        );
        $this->zAddChild($refNFP, "CPF", $cpf, true, $identificador . "Informar o CPF do emitente da NF de produtor");
        $this->zAddChild(
            $refNFP,
            "IE",
            $numIE,
            true,
            $identificador . "Informar a IE do emitente da NF de Produtor ou o literal 'ISENTO'"
        );
        $this->zAddChild($refNFP, "mod", $mod, true, $identificador . "Modelo do Documento Fiscal");
        $this->zAddChild($refNFP, "serie", $serie, true, $identificador . "Série do Documento Fiscal");
        $this->zAddChild($refNFP, "nNF", $nNF, true, $identificador . "Número do Documento Fiscal");
        $this->zAppChild($this->aNFref[$num-1], $refNFP);
        return $refNFP;
    }
    
    /**
     * tagrefCTe
     * Chave de acesso do CT-e referenciada BA19 pai BA01
     * tag NFe/infNFe/ide/NFref/refCTe
     * @param string $refCTe
     * @return DOMElement
     */
    public function tagrefCTe($refCTe = '')
    {
        $num = $this->zTagNFref();
        $refCTe = $this->dom->createElement("refCTe", $refCTe);
        $this->zAppChild($this->aNFref[$num-1], $refCTe);
        return $refCTe;
    }
    
    /**
     * tagrefECF
     * Informações do Cupom Fiscal referenciado BA20 pai BA01
     * tag NFe/infNFe/ide/NFref/refECF
     * @param string $mod
     * @param string $nECF
     * @param string $nCOO
     * @return DOMElement
     */
    public function tagrefECF(
        $mod = '',
        $nECF = '',
        $nCOO = ''
    ) {
        $identificador = 'BA20 <refECF> - ';
        $num = $this->zTagNFref();
        $refECF = $this->dom->createElement("refECF");
        $this->zAddChild($refECF, "mod", $mod, true, $identificador . "Modelo do Documento Fiscal");
        $this->zAddChild($refECF, "nECF", $nECF, true, $identificador . "Número de ordem sequencial do ECF");
        $this->zAddChild(
            $refECF,
            "nCOO",
            $nCOO,
            true,
            $identificador . "Número do Contador de Ordem de Operação - COO"
        );
        $this->zAppChild($this->aNFref[$num-1], $refECF);
        return $refECF;
    }
    
    /**
     * tagemit
     * Identificação do emitente da NF-e C01 pai A01
     * tag NFe/infNFe/emit
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
        $cpf = '',
        $xNome = '',
        $xFant = '',
        $numIE = '',
        $numIEST = '',
        $numIM = '',
        $cnae = '',
        $crt = ''
    ) {
        $identificador = 'C01 <emit> - ';
        $this->emit = $this->dom->createElement("emit");
        if ($cnpj != '') {
            $this->zAddChild($this->emit, "CNPJ", $cnpj, true, $identificador . "CNPJ do emitente");
        } else {
            $this->zAddChild($this->emit, "CPF", $cpf, true, $identificador . "CPF do remetente");
        }
        $this->zAddChild($this->emit, "xNome", $xNome, true, $identificador . "Razão Social ou Nome do emitente");
        $this->zAddChild($this->emit, "xFant", $xFant, false, $identificador . "Nome fantasia do emitente");
        $this->zAddChild($this->emit, "IE", $numIE, true, $identificador . "Inscrição Estadual do emitente");
        $this->zAddChild(
            $this->emit,
            "IEST",
            $numIEST,
            false,
            $identificador . "IE do Substituto Tributário do emitente"
        );
        $this->zAddChild(
            $this->emit,
            "IM",
            $numIM,
            false,
            $identificador . "Inscrição Municipal do Prestador de Serviço do emitente"
        );
        $this->zAddChild($this->emit, "CNAE", $cnae, false, $identificador . "CNAE fiscal do emitente");
        $this->zAddChild($this->emit, "CRT", $crt, true, $identificador . "Código de Regime Tributário do emitente");
        return $this->emit;
    }
    
    /**
     * tagenderEmit
     * Endereço do emitente C05 pai C01
     * tag NFe/infNFe/emit/endEmit
     * @param string $xLgr
     * @param string $nro
     * @param string $xCpl
     * @param string $xBairro
     * @param string $cMun
     * @param string $xMun
     * @param string $siglaUF
     * @param string $cep
     * @param string $cPais
     * @param string $xPais
     * @param string $fone
     * @return DOMElement
     */
    public function tagenderEmit(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $siglaUF = '',
        $cep = '',
        $cPais = '',
        $xPais = '',
        $fone = ''
    ) {
        $identificador = 'C05 <enderEmit> - ';
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->zAddChild($this->enderEmit, "xLgr", $xLgr, true, $identificador . "Logradouro do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "nro", $nro, true, $identificador . "Número do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xCpl", $xCpl, false, $identificador . "Complemento do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xBairro", $xBairro, true, $identificador . "Bairro do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "cMun", $cMun, true, $identificador . "Código do município do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xMun", $xMun, true, $identificador . "Nome do município do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "UF", $siglaUF, true, $identificador . "Sigla da UF do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "CEP", $cep, true, $identificador . "Código do CEP do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "cPais", $cPais, false, $identificador . "Código do País do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xPais", $xPais, false, $identificador . "Nome do País do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "fone", $fone, false, $identificador . "Telefone do Endereço do emitente");
        $node = $this->emit->getElementsByTagName("IE")->item(0);
        $this->emit->insertBefore($this->enderEmit, $node);
        return $this->enderEmit;
    }
    
    /**
     * tagdest
     * Identificação do Destinatário da NF-e E01 pai A01
     * tag NFe/infNFe/dest (opcional para modelo 65)
     * @param string $cnpj
     * @param string $cpf
     * @param string $idEstrangeiro
     * @param string $xNome
     * @param string $indIEDest
     * @param string $numIE
     * @param string $isUF
     * @param string $numIM
     * @param string $email
     * @return DOMElement
     */
    public function tagdest(
        $cnpj = '',
        $cpf = '',
        $idEstrangeiro = '',
        $xNome = '',
        $indIEDest = '',
        $numIE = '',
        $isUF = '',
        $numIM = '',
        $email = ''
    ) {
        $identificador = 'E01 <dest> - ';
        $this->dest = $this->dom->createElement("dest");
        if ($cnpj != '') {
            $this->zAddChild($this->dest, "CNPJ", $cnpj, true, $identificador . "CNPJ do destinatário");
        } elseif ($cpf != '') {
            $this->zAddChild($this->dest, "CPF", $cpf, true, $identificador . "CPF do destinatário");
        } else {
            $this->zAddChild(
                $this->dest,
                "idEstrangeiro",
                $idEstrangeiro,
                true,
                $identificador . "Identificação do destinatário no caso de comprador estrangeiro"
            );
        }
        $this->zAddChild($this->dest, "xNome", $xNome, true, $identificador . "Razão Social ou nome do destinatário");
        if ($this->mod == '65') {
            $indIEDest = '9';
            $this->zAddChild(
                $this->dest,
                "indIEDest",
                $indIEDest,
                true,
                $identificador . "Indicador da IE do Destinatário"
            );
        } else {
            $this->zAddChild(
                $this->dest,
                "indIEDest",
                $indIEDest,
                true,
                $identificador . "Indicador da IE do Destinatário"
            );
        }
        if ($indIEDest != '9' && $indIEDest != '2') {
            $this->zAddChild($this->dest, "IE", $numIE, true, $identificador . "Inscrição Estadual do Destinatário");
        }
        $this->zAddChild($this->dest, "ISUF", $isUF, false, $identificador . "Inscrição na SUFRAMA do destinatário");
        $this->zAddChild(
            $this->dest,
            "IM",
            $numIM,
            false,
            $identificador . "Inscrição Municipal do Tomador do Serviço do destinatário"
        );
        $this->zAddChild($this->dest, "email", $email, false, $identificador . "Email do destinatário");
        return $this->dest;
    }
    
    /**
     * tagenderDest
     * Endereço do Destinatário da NF-e E05 pai E01 
     * tag NFe/infNFe/dest/enderDest  (opcional para modelo 65)
     * Os dados do destinatário devem ser inseridos antes deste método
     * @param string $xLgr
     * @param string $nro
     * @param string $xCpl
     * @param string $xBairro
     * @param string $cMun
     * @param string $xMun
     * @param string $siglaUF
     * @param string $cep
     * @param string $cPais
     * @param string $xPais
     * @param string $fone
     * @return DOMElement
     */
    public function tagenderDest(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $siglaUF = '',
        $cep = '',
        $cPais = '',
        $xPais = '',
        $fone = ''
    ) {
        $identificador = 'E05 <enderDest> - ';
        if (empty($this->dest)) {
            throw new Exception('A TAG dest deve ser criada antes do endereço do mesmo.');
        }
        $this->enderDest = $this->dom->createElement("enderDest");
        $this->zAddChild($this->enderDest, "xLgr", $xLgr, true, $identificador . "Logradouro do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "nro", $nro, true, $identificador . "Número do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xCpl", $xCpl, false, $identificador . "Complemento do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xBairro", $xBairro, true, $identificador . "Bairro do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "cMun", $cMun, true, $identificador . "Código do município do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xMun", $xMun, true, $identificador . "Nome do município do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "UF", $siglaUF, true, $identificador . "Sigla da UF do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "CEP", $cep, false, $identificador . "Código do CEP do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "cPais", $cPais, false, $identificador . "Código do País do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xPais", $xPais, false, $identificador . "Nome do País do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "fone", $fone, false, $identificador . "Telefone do Endereço do Destinatário");
        $node = $this->dest->getElementsByTagName("indIEDest")->item(0);
        if (!isset($node)) {
            $node = $this->dest->getElementsByTagName("IE")->item(0);
        }
        $this->dest->insertBefore($this->enderDest, $node);
        return $this->enderDest;
    }
    
    /**
     * tagretirada
     * Identificação do Local de retirada F01 pai A01
     * tag NFe/infNFe/retirada (opcional)
     * @param string $cnpj
     * @param string $cpf
     * @param string $xLgr
     * @param string $nro
     * @param string $xCpl
     * @param string $xBairro
     * @param string $cMun
     * @param string $xMun
     * @param string $siglaUF
     * @return DOMElement
     */
    public function tagretirada(
        $cnpj = '',
        $cpf = '',
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $siglaUF = ''
    ) {
        $identificador = 'F01 <retirada> - ';
        $this->retirada = $this->dom->createElement("retirada");
        if ($cnpj != '') {
            $this->zAddChild($this->retirada, "CNPJ", $cnpj, true, $identificador . "CNPJ do Cliente da Retirada");
        } else {
            $this->zAddChild($this->retirada, "CPF", $cpf, true, $identificador . "CPF do Cliente da Retirada");
        }
        $this->zAddChild($this->retirada, "xLgr", $xLgr, true, $identificador . "Logradouro do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "nro", $nro, true, $identificador . "Número do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "xCpl", $xCpl, false, $identificador . "Complemento do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "xBairro", $xBairro, true, $identificador . "Bairro do Endereco do Cliente da Retirada");
        $this->zAddChild(
            $this->retirada,
            "cMun",
            $cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "xMun",
            $xMun,
            true,
            $identificador . "Nome do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild($this->retirada, "UF", $siglaUF, true, $identificador . "Sigla da UF do Endereco do Cliente da Retirada");
        return $this->retirada;
    }
    
    /**
     * tagentrega
     * Identificação do Local de entrega G01 pai A01
     * tag NFe/infNFe/entrega (opcional)
     * @param string $cnpj
     * @param string $cpf
     * @param string $xLgr
     * @param string $nro
     * @param string $xCpl
     * @param string $xBairro
     * @param string $cMun
     * @param string $xMun
     * @param string $siglaUF
     * @return DOMElement
     */
    public function tagentrega(
        $cnpj = '',
        $cpf = '',
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $siglaUF = ''
    ) {
        $identificador = 'G01 <entrega> - ';
        $this->entrega = $this->dom->createElement("entrega");
        if ($cnpj != '') {
            $this->zAddChild($this->entrega, "CNPJ", $cnpj, true, $identificador . "CNPJ do Cliente da Entrega");
        } else {
            $this->zAddChild($this->entrega, "CPF", $cpf, true, $identificador . "CPF do Cliente da Entrega");
        }
        $this->zAddChild($this->entrega, "xLgr", $xLgr, true, $identificador . "Logradouro do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "nro", $nro, true, $identificador . "Número do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "xCpl", $xCpl, false, $identificador . "Complemento do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "xBairro", $xBairro, true, $identificador . "Bairro do Endereco do Cliente da Entrega");
        $this->zAddChild(
            $this->entrega,
            "cMun",
            $cMun,
            true,
            $identificador . "Código do município do Endereco do Cliente da Entrega"
        );
        $this->zAddChild($this->entrega, "xMun", $xMun, true, $identificador . "Nome do município do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "UF", $siglaUF, true, $identificador . "Sigla da UF do Endereco do Cliente da Entrega");
        return $this->entrega;
    }
    
    /**
     * tagautXML
     * Pessoas autorizadas para o download do XML da NF-e G50 pai A01
     * tag NFe/infNFe/autXML (somente versão 3.1)
     * @param string $cnpj
     * @param string $cpf
     * @return array
     */
    public function tagautXML($cnpj = '', $cpf = '')
    {
        $identificador = 'G50 <autXML> - ';
        if ($this->versao > 2) {
            $autXML = $this->dom->createElement("autXML");
            if ($cnpj != '') {
                $this->zAddChild($autXML, "CNPJ", $cnpj, true, $identificador . "CNPJ do Cliente Autorizado");
            } else {
                $this->zAddChild($autXML, "CPF", $cpf, true, $identificador . "CPF do Cliente Autorizado");
            }
            $this->aAutXML[]=$autXML;
            return $autXML;
        } else {
            return array();
        }
    }
    
    /**
     * Insere dentro da tag det os produtos
     * tag NFe/infNFe/det[]
     */
    private function zTagdet()
    {
        if (empty($this->aProd)) {
            return '';
        }
        //insere DI
        if (!empty($this->aDI)) {
            foreach ($this->aDI as $nItem => $aDI) {
                $prod = $this->aProd[$nItem];
                foreach ($aDI as $child) {
                    $this->zAppChild($prod, $child, "Inclusão do node DI");
                }
                $this->aProd[$nItem] = $prod;
            }
        }
        //insere detExport
        if (!empty($this->aDetExport)) {
            foreach ($this->aDetExport as $nItem => $child) {
                $prod = $this->aProd[$nItem];
                $this->zAppChild($prod, $child, "Inclusão do node detExport");
                $this->aProd[$nItem] = $prod;
            }
        }
        //insere veiculo
        if (!empty($this->aVeicProd)) {
            foreach ($this->aVeicProd as $nItem => $child) {
                $prod = $this->aProd[$nItem];
                $this->zAppChild($prod, $child, "Inclusão do node veiculo");
                $this->aProd[$nItem] = $prod;
            }
        }
        //insere medicamentos
        if (!empty($this->aMed)) {
            foreach ($this->aMed as $nItem => $child) {
                $prod = $this->aProd[$nItem];
                $this->zAppChild($prod, $child, "Inclusão do node medicamento");
                $this->aProd[$nItem] = $prod;
            }
        }
        //insere armas
        if (!empty($this->aArma)) {
            foreach ($this->aArma as $nItem => $child) {
                $prod = $this->aProd[$nItem];
                $this->zAppChild($prod, $child, "Inclusão do node arma");
                $this->aProd[$nItem] = $prod;
            }
        }
        //insere combustivel
        if (!empty($this->aComb)) {
            foreach ($this->aComb as $nItem => $child) {
                $prod = $this->aProd[$nItem];
                $this->zAppChild($prod, $child, "Inclusão do node combustivel");
                $this->aProd[$nItem] = $prod;
            }
        }
        //montagem da tag imposto[]
        $this->zTagImp();
        //montagem da tag det[]
        foreach ($this->aProd as $nItem => $prod) {
            $det = $this->dom->createElement("det");
            $det->setAttribute("nItem", $nItem);
            $det->appendChild($prod);
            //insere imposto
            if (!empty($this->aImposto[$nItem])) {
                $child = $this->aImposto[$nItem];
                $this->zAppChild($det, $child, "Inclusão do node imposto");
            }
            //insere impostoDevol
            if (!empty($this->aImpostoDevol)) {
                $child = $this->aImpostoDevol[$nItem];
                $this->zAppChild($det, $child, "Inclusão do node impostoDevol");
            }
            //insere infAdProd
            if (!empty($this->aInfAdProd[$nItem])) {
                $child = $this->aInfAdProd[$nItem];
                $this->zAppChild($det, $child, "Inclusão do node infAdProd");
            }
            $this->aDet[] = $det;
            $det = null;
        }
    }

    /**
     * tagprod
     * Detalhamento de Produtos e Serviços I01 pai H01
     * tag NFe/infNFe/det[]/prod
     * @param string $nItem
     * @param string $cProd
     * @param string $cEAN
     * @param string $xProd
     * @param string $NCM
     * @param string $NVE
     * @param string $EXTIPI
     * @param string $CFOP
     * @param string $uCom
     * @param string $qCom
     * @param string $vUnCom
     * @param string $vProd
     * @param string $cEANTrib
     * @param string $uTrib
     * @param string $qTrib
     * @param string $vUnTrib
     * @param string $vFrete
     * @param string $vSeg
     * @param string $vDesc
     * @param string $vOutro
     * @param string $indTot
     * @param string $xPed
     * @param string $nItemPed
     * @param string $nFCI
     * @param string $nRECOPI
     * @return DOMElement
     */
    public function tagprod(
        $nItem = '',
        $cProd = '',
        $cEAN = '',
        $xProd = '',
        $NCM = '',
        $NVE = '',
        $EXTIPI = '',
        $CFOP = '',
        $uCom = '',
        $qCom = '',
        $vUnCom = '',
        $vProd = '',
        $cEANTrib = '',
        $uTrib = '',
        $qTrib = '',
        $vUnTrib = '',
        $vFrete = '',
        $vSeg = '',
        $vDesc = '',
        $vOutro = '',
        $indTot = '',
        $xPed = '',
        $nItemPed = '',
        $nFCI = '',
        $nRECOPI = ''
    ) {
        $identificador = 'I01 <prod> - ';
        $prod = $this->dom->createElement("prod");
        $this->zAddChild($prod, "cProd", $cProd, true, $identificador . "[item $nItem] Código do produto ou serviço");
        $this->zAddChild(
            $prod,
            "cEAN",
            $cEAN,
            true,
            $identificador . "[item $nItem] GTIN (Global Trade Item Number) do produto, antigo "
            . "código EAN ou código de barras",
            true
        );
        $this->zAddChild($prod, "xProd", $xProd, true, $identificador . "[item $nItem] Descrição do produto ou serviço");
        $this->zAddChild($prod, "NCM", $NCM, true, $identificador . "[item $nItem] Código NCM com 8 dígitos ou 2 dígitos (gênero)");
        $this->zAddChild(
            $prod,
            "NVE",
            $NVE,
            false,
            $identificador . "[item $nItem] Codificação NVE - Nomenclatura de Valor Aduaneiro e Estatística"
        );
        $this->zAddChild($prod, "EXTIPI", $EXTIPI, false, $identificador . "[item $nItem] Preencher de acordo com o código EX da TIPI");
        $this->zAddChild($prod, "CFOP", $CFOP, true, $identificador . "[item $nItem] Código Fiscal de Operações e Prestações");
        $this->zAddChild($prod, "uCom", $uCom, true, $identificador . "[item $nItem] Unidade Comercial do produto");
        $this->zAddChild($prod, "qCom", $qCom, true, $identificador . "[item $nItem] Quantidade Comercial do produto");
        $this->zAddChild($prod, "vUnCom", $vUnCom, true, $identificador . "[item $nItem] Valor Unitário de Comercialização do produto");
        $this->zAddChild($prod, "vProd", $vProd, true, $identificador . "[item $nItem] Valor Total Bruto dos Produtos ou Serviços");
        $this->zAddChild(
            $prod,
            "cEANTrib",
            $cEANTrib,
            true,
            $identificador . "[item $nItem] GTIN (Global Trade Item Number) da unidade tributável, antigo "
            . "código EAN ou código de barras",
            true
        );
        $this->zAddChild($prod, "uTrib", $uTrib, true, $identificador . "[item $nItem] Unidade Tributável do produto");
        $this->zAddChild($prod, "qTrib", $qTrib, true, $identificador . "[item $nItem] Quantidade Tributável do produto");
        $this->zAddChild($prod, "vUnTrib", $vUnTrib, true, $identificador . "[item $nItem] Valor Unitário de tributação do produto");
        $this->zAddChild($prod, "vFrete", $vFrete, false, $identificador . "[item $nItem] Valor Total do Frete");
        $this->zAddChild($prod, "vSeg", $vSeg, false, $identificador . "[item $nItem] Valor Total do Seguro");
        $this->zAddChild($prod, "vDesc", $vDesc, false, $identificador . "[item $nItem] Valor do Desconto");
        $this->zAddChild($prod, "vOutro", $vOutro, false, $identificador . "[item $nItem] Outras despesas acessórias");
        $this->zAddChild(
            $prod,
            "indTot",
            $indTot,
            true,
            $identificador . "[item $nItem] Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)"
        );
        $this->zAddChild($prod, "xPed", $xPed, false, $identificador . "[item $nItem] Número do Pedido de Compra");
        $this->zAddChild($prod, "nItemPed", $nItemPed, false, $identificador . "[item $nItem] Item do Pedido de Compra");
        $this->zAddChild(
            $prod,
            "nFCI",
            $nFCI,
            false,
            $identificador . "[item $nItem] Número de controle da FCI - Ficha de Conteúdo de Importação"
        );
        $this->zAddChild($prod, "nRECOPI", $nRECOPI, false, $identificador . "[item $nItem] Número do RECOPI");
        $this->aProd[$nItem] = $prod;
        return $prod;
    }
    
    /**
     * taginfAdProd
     * Informações adicionais do produto 
     * tag NFe/infNFe/det[]/infAdProd
     * @param type $nItem
     * @param type $texto
     */
    public function taginfAdProd($nItem = '', $texto = '')
    {
        $infAdProd = $this->dom->createElement("infAdProd", $texto);
        $this->aInfAdProd[$nItem] = $infAdProd;
        return $infAdProd;
    }
    
    /**
     * tagDI
     * Declaração de Importação I8 pai I01
     * tag NFe/infNFe/det[]/prod/DI
     * @param string $nItem
     * @param string $nDI
     * @param string $dDI
     * @param string $xLocDesemb
     * @param string $UFDesemb
     * @param string $dDesemb
     * @param string $tpViaTransp
     * @param string $vAFRMM
     * @param string $tpIntermedio
     * @param string $CNPJ
     * @param string $UFTerceiro
     * @param string $cExportador
     * @return DOMELEment
     */
    public function tagDI(
        $nItem = '',
        $nDI = '',
        $dDI = '',
        $xLocDesemb = '',
        $UFDesemb = '',
        $dDesemb = '',
        $tpViaTransp = '',
        $vAFRMM = '',
        $tpIntermedio = '',
        $CNPJ = '',
        $UFTerceiro = '',
        $cExportador = ''
    ) {
        $identificador = 'I8 <DI> - ';
        $tDI = $this->dom->createElement("DI");
        $this->zAddChild($tDI, "nDI", $nDI, true, $identificador . "[item $nItem] Número do Documento de Importação (DI, DSI, DIRE, ...)");
        $this->zAddChild($tDI, "dDI", $dDI, true, $identificador . "[item $nItem] Data de Registro do documento");
        $this->zAddChild($tDI, "xLocDesemb", $xLocDesemb, true, $identificador . "[item $nItem] Local de desembaraço");
        $this->zAddChild($tDI, "UFDesemb", $UFDesemb, true, $identificador . "[item $nItem] Sigla da UF onde ocorreu o Desembaraço Aduaneiro");
        $this->zAddChild($tDI, "dDesemb", $dDesemb, true, $identificador . "[item $nItem] Data do Desembaraço Aduaneiro");
        $this->zAddChild(
            $tDI,
            "tpViaTransp",
            $tpViaTransp,
            true,
            $identificador . "[item $nItem] Via de transporte internacional informada na Declaração de Importação (DI)"
        );
        $this->zAddChild(
            $tDI,
            "vAFRMM",
            $vAFRMM,
            false,
            $identificador . "[item $nItem] Valor da AFRMM - Adicional ao Frete para Renovação da Marinha Mercante"
        );
        $this->zAddChild(
            $tDI,
            "tpIntermedio",
            $tpIntermedio,
            true,
            $identificador . "[item $nItem] Forma de importação quanto a intermediação"
        );
        $this->zAddChild($tDI, "CNPJ", $CNPJ, false, $identificador . "[item $nItem] CNPJ do adquirente ou do encomendante");
        $this->zAddChild(
            $tDI,
            "UFTerceiro",
            $UFTerceiro,
            false,
            $identificador . "[item $nItem] Sigla da UF do adquirente ou do encomendante"
        );
        $this->zAddChild($tDI, "cExportador", $cExportador, true, $identificador . "[item $nItem] Código do Exportador");
        $this->aDI[$nItem][$nDI] = $tDI;
        return $tDI;
    }
    
    /**
     * tagadi
     * Adições I25 pai I18
     * tag NFe/infNFe/det[]/prod/DI/adi
     * @param string $nItem
     * @param string $nDI
     * @param string $nAdicao
     * @param string $nSeqAdicC
     * @param string $cFabricante
     * @param string $vDescDI
     * @param string $nDraw
     * @return DOMElement
     */
    public function tagadi(
        $nItem = '',
        $nDI = '',
        $nAdicao = '',
        $nSeqAdicC = '',
        $cFabricante = '',
        $vDescDI = '',
        $nDraw = ''
    ) {
        $identificador = 'I25 <adi> - ';
        $adi = $this->dom->createElement("adi");
        $this->zAddChild($adi, "nAdicao", $nAdicao, true, $identificador . "[item $nItem] Número da Adição");
        $this->zAddChild($adi, "nSeqAdicC", $nSeqAdicC, true, $identificador . "[item $nItem] Número sequencial do item dentro da Adição");
        $this->zAddChild($adi, "cFabricante", $cFabricante, true, $identificador . "[item $nItem] Código do fabricante estrangeiro");
        $this->zAddChild($adi, "vDescDI", $vDescDI, false, $identificador . "[item $nItem] Valor do desconto do item da DI Adição");
        $this->zAddChild($adi, "nDraw", $nDraw, false, $identificador . "[item $nItem] Número do ato concessório de Drawback");
        $this->aAdi[$nItem][$nDI][] = $adi;
        //colocar a adi em seu DI respectivo
        $nodeDI = $this->aDI[$nItem][$nDI];
        $this->zAppChild($nodeDI, $adi);
        $this->aDI[$nItem][$nDI] = $nodeDI;
        return $adi;
    }
    
    /**
     * tagdetExport
     * Grupo de informações de exportação para o item I50 pai I01
     * tag NFe/infNFe/det[]/prod/detExport
     * @param string $nItem
     * @param string $nDraw
     * @param string $exportInd
     * @param string $nRE
     * @param string $chNFe
     * @param string $qExport
     * @return DOMElement
     */
    public function tagdetExport(
        $nItem = '',
        $nDraw = '',
        $exportInd = '',
        $nRE = '',
        $chNFe = '',
        $qExport = ''
    ) {
        $identificador = 'I50 <detExport> - ';
        $detExport = $this->dom->createElement("detExport");
        $this->zAddChild($detExport, "nDraw", $nDraw, false, $identificador . "[item $nItem] Número do ato concessório de Drawback");
        $this->zAddChild($detExport, "exportInd", $exportInd, false, $identificador . "[item $nItem] Grupo sobre exportação indireta");
        $this->zAddChild($detExport, "nRE", $nRE, true, $identificador . "[item $nItem] Número do Registro de Exportação");
        $this->zAddChild($detExport, "chNFe", $chNFe, true, $identificador . "[item $nItem] Chave de Acesso da NF-e recebida para exportação");
        $this->zAddChild($detExport, "qExport", $qExport, true, $identificador . "[item $nItem] Quantidade do item realmente exportado");
        $this->aDetExport[$nItem] = $detExport;
        return $detExport;
    }
    
    /**
     * tagveicProd
     * Detalhamento de Veículos novos J01 pai I90
     * tag NFe/infNFe/det[]/prod/veicProd (opcional)
     * @param string $nItem
     * @param string $tpOp
     * @param string $chassi
     * @param string $cCor
     * @param string $xCor
     * @param string $pot
     * @param string $cilin
     * @param string $pesoL
     * @param string $pesoB
     * @param string $nSerie
     * @param string $tpComb
     * @param string $nMotor
     * @param string $CMT
     * @param string $dist
     * @param string $anoMod
     * @param string $anoFab
     * @param string $tpPint
     * @param string $tpVeic
     * @param string $espVeic
     * @param string $VIN
     * @param string $condVeic
     * @param string $cMod
     * @param string $cCorDENATRAN
     * @param string $lota
     * @param string $tpRest
     * @return DOMElement
     */
    public function tagveicProd(
        $nItem = '',
        $tpOp = '',
        $chassi = '',
        $cCor = '',
        $xCor = '',
        $pot = '',
        $cilin = '',
        $pesoL = '',
        $pesoB = '',
        $nSerie = '',
        $tpComb = '',
        $nMotor = '',
        $cmt = '',
        $dist = '',
        $anoMod = '',
        $anoFab = '',
        $tpPint = '',
        $tpVeic = '',
        $espVeic = '',
        $VIN = '',
        $condVeic = '',
        $cMod = '',
        $cCorDENATRAN = '',
        $lota = '',
        $tpRest = ''
    ) {
        $identificador = 'J01 <veicProd> - ';
        $veicProd = $this->dom->createElement("veicProd");
        $this->zAddChild($veicProd, "tpOp", $tpOp, true, "$identificador [item $nItem] Tipo da operação do veículo");
        $this->zAddChild($veicProd, "chassi", $chassi, true, "$identificador [item $nItem] Chassi do veículo");
        $this->zAddChild($veicProd, "cCor", $cCor, true, "$identificador [item $nItem] Cor do veículo");
        $this->zAddChild($veicProd, "xCor", $xCor, true, "$identificador [item $nItem] Descrição da Cor do veículo");
        $this->zAddChild($veicProd, "pot", $pot, true, "$identificador [item $nItem] Potência Motor (CV) do veículo");
        $this->zAddChild($veicProd, "cilin", $cilin, true, "$identificador [item $nItem] Cilindradas do veículo");
        $this->zAddChild($veicProd, "pesoL", $pesoL, true, "$identificador [item $nItem] Peso Líquido do veículo");
        $this->zAddChild($veicProd, "pesoB", $pesoB, true, "$identificador [item $nItem] Peso Bruto do veículo");
        $this->zAddChild($veicProd, "nSerie", $nSerie, true, "$identificador [item $nItem] Serial (série) do veículo");
        $this->zAddChild($veicProd, "tpCpmb", $tpComb, true, "$identificador [item $nItem] Tipo de combustível do veículo");
        $this->zAddChild($veicProd, "nMotor", $nMotor, true, "$identificador [item $nItem] Número de Motor do veículo");
        $this->zAddChild($veicProd, "CMT", $cmt, true, "$identificador [item $nItem] Capacidade Máxima de Tração do veículo");
        $this->zAddChild($veicProd, "dist", $dist, true, "$identificador [item $nItem] Distância entre eixos do veículo");
        $this->zAddChild($veicProd, "anoMd", $anoMod, true, "$identificador [item $nItem] Ano Modelo de Fabricação do veículo");
        $this->zAddChild($veicProd, "anoFab", $anoFab, true, "$identificador [item $nItem] Ano de Fabricação do veículo");
        $this->zAddChild($veicProd, "tpPint", $tpPint, true, "$identificador [item $nItem] Tipo de Pintura do veículo");
        $this->zAddChild($veicProd, "tpVeic", $tpVeic, true, "$identificador [item $nItem] Tipo de Veículo");
        $this->zAddChild($veicProd, "espVeic", $espVeic, true, "$identificador [item $nItem] Espécie de Veículo");
        $this->zAddChild($veicProd, "VIN", $VIN, true, "$identificador [item $nItem] Condição do VIN do veículo");
        $this->zAddChild($veicProd, "condVeic", $condVeic, true, "$identificador [item $nItem] Condição do Veículo");
        $this->zAddChild($veicProd, "cMod", $cMod, true, "$identificador [item $nItem] Código Marca Modelo do veículo");
        $this->zAddChild($veicProd, "cCorDENATRAN", $cCorDENATRAN, true, "$identificador [item $nItem] Código da Cor do veículo");
        $this->zAddChild($veicProd, "lota", $lota, true, "$identificador [item $nItem] Capacidade máxima de lotação do veículo");
        $this->zAddChild($veicProd, "tpResp", $tpRest, true, "$identificador [item $nItem] Restrição do veículo");
        $this->aVeicProd[$nItem] = $veicProd;
        return $veicProd;
    }
    
    /**
     * tagmed
     * Detalhamento de medicamentos K01 pai I90
     * tag NFe/infNFe/det[]/prod/med (opcional)
     * @param string $nItem
     * @param string $nLote
     * @param string $qLote
     * @param string $dFab
     * @param string $dVal
     * @param string $vPMC
     * @return DOMElement
     */
    public function tagmed(
        $nItem = '',
        $nLote = '',
        $qLote = '',
        $dFab = '',
        $dVal = '',
        $vPMC = ''
    ) {
        $identificador = 'K01 <med> - ';
        $med = $this->dom->createElement("med");
        $this->zAddChild(
            $med,
            "nLote",
            $nLote,
            true,
            "$identificador [item $nItem] Número do Lote de medicamentos ou de matérias-primas farmacêuticas"
        );
        $this->zAddChild(
            $med,
            "qLote",
            $qLote,
            true,
            "$identificador [item $nItem] Quantidade de produto no Lote de medicamentos ou de matérias-primas farmacêuticas"
        );
        $this->zAddChild($med, "dFab", $dFab, true, "$identificador [item $nItem] Data de fabricação");
        $this->zAddChild($med, "dVal", $dVal, true, "$identificador [item $nItem] Data de validade");
        $this->zAddChild($med, "vPMC", $vPMC, true, "$identificador [item $nItem] Preço máximo consumidor");
        $this->aMed[$nItem] = $med;
        return $med;
    }
    
    /**
     * tagarma
     * Detalhamento de armas L01 pai I90
     * tag NFe/infNFe/det[]/prod/arma (opcional)
     * @param type $nItem
     * @param type $tpArma
     * @param type $nSerie
     * @param type $nCano
     * @param type $descr
     * @return DOMElement
     */
    public function tagarma(
        $nItem = '',
        $tpArma = '',
        $nSerie = '',
        $nCano = '',
        $descr = ''
    ) {
        $identificador = 'L01 <arma> - ';
        $arma = $this->dom->createElement("arma");
        $this->zAddChild($arma, "tpArma", $tpArma, true, "$identificador [item $nItem] Indicador do tipo de arma de fogo");
        $this->zAddChild($arma, "nSerie", $nSerie, true, "$identificador [item $nItem] Número de série da arma");
        $this->zAddChild($arma, "nCano", $nCano, true, "$identificador [item $nItem] Número de série do cano");
        $this->zAddChild(
            $arma,
            "descr",
            $descr,
            true,
            "$identificador [item $nItem] Descrição completa da arma, compreendendo: calibre, marca, capacidade, "
            . "tipo de funcionamento, comprimento e demais elementos que "
            . "permitam a sua perfeita identificação."
        );
        $this->aArma[$nItem] = $arma;
        return $arma;
    }
    
    /**
     * tagcomb
     * Detalhamento de combustiveis L101 pai I90
     * tag NFe/infNFe/det[]/prod/comb (opcional)
     * @param string $nItem
     * @param string $cProdANP
     * @param string $pMixGN
     * @param string $codif
     * @param string $qTemp
     * @param string $ufCons
     * @param string $qBCProd
     * @param string $vAliqProd
     * @param string $vCIDE
     * @return DOMElement
     */
    public function tagcomb(
        $nItem = '',
        $cProdANP = '',
        $pMixGN = '',
        $codif = '',
        $qTemp = '',
        $ufCons = '',
        $qBCProd = '',
        $vAliqProd = '',
        $vCIDE = ''
    ) {
        $identificador = 'L101 <comb> - ';
        $comb = $this->dom->createElement("comb");
        $this->zAddChild($comb, "cProdANP", $cProdANP, true, "$identificador [item $nItem] Código de produto da ANP");
        $this->zAddChild(
            $comb,
            "pMixGN",
            $pMixGN,
            false,
            "$identificador [item $nItem] Percentual de Gás Natural para o produto GLP (cProdANP=210203001)"
        );
        $this->zAddChild($comb, "CODIF", $codif, false, "[item $nItem] Código de autorização / registro do CODIF");
        $this->zAddChild(
            $comb,
            "qTemp",
            $qTemp,
            false,
            "$identificador [item $nItem] Quantidade de combustível faturada à temperatura ambiente."
        );
        $this->zAddChild($comb, "UFCons", $ufCons, true, "[item $nItem] Sigla da UF de consumo");
        if ($qBCProd != "") {
            $tagCIDE = $this->dom->createElement("CIDE");
            $this->zAddChild($tagCIDE, "qBCProd", $qBCProd, true, "$identificador [item $nItem] BC da CIDE");
            $this->zAddChild($tagCIDE, "vAliqProd", $vAliqProd, true, "$identificador [item $nItem] Valor da alíquota da CIDE");
            $this->zAddChild($tagCIDE, "vCIDE", $vCIDE, true, "$identificador [item $nItem] Valor da CIDE");
            $this->zAppChild($comb, $tagCIDE);
        }
        $this->aComb[$nItem] = $comb;
        return $comb;
    }
    
    /**
     * tagimposto
     * M01 pai H01
     * tag NFe/infNFe/det[]/imposto
     * @param string $nItem
     * @param string $vTotTrib
     * @return DOMElement
     */
    public function tagimposto($nItem = '', $vTotTrib = '')
    {
        $identificador = 'M01 <imposto> - ';
        $imposto = $this->dom->createElement("imposto");
        $this->zAddChild(
            $imposto,
            "vTotTrib",
            $vTotTrib,
            false,
            "$identificador [item $nItem] Valor aproximado total de tributos federais, estaduais e municipais."
        );
        $this->aImposto[$nItem] = $imposto;
        return $imposto;
    }
    
    /**
     * tagICMS
     * Informações do ICMS da Operação própria e ST N01 pai M01
     * tag NFe/infNFe/det[]/imposto/ICMS
     * @param string $nItem
     * @param string $orig
     * @param string $CST
     * @param string $modBC
     * @param string $vBC
     * @param string $pICMS
     * @param string $vICMS
     * @param string $vICMSDeson
     * @param string $motDesICMS
     * @param string $modBCST
     * @param string $pMVAST
     * @param string $pRedBCST
     * @param string $vBCST
     * @param string $pICMSST
     * @param string $vICMSST
     * @param string $pDif
     * @param string $vICMSDif
     * @param string $vICMSOp
     * @param string $vBCSTRet
     * @param string $vICMSSTRet
     * @return DOMElement
     */
    public function tagICMS(
        $nItem = '',
        $orig = '',
        $cst = '',
        $modBC = '',
        $pRedBC = '',
        $vBC = '',
        $pICMS = '',
        $vICMS = '',
        $vICMSDeson = '',
        $motDesICMS = '',
        $modBCST = '',
        $pMVAST = '',
        $pRedBCST = '',
        $vBCST = '',
        $pICMSST = '',
        $vICMSST = '',
        $pDif = '',
        $vICMSDif = '',
        $vICMSOp = '',
        $vBCSTRet = '',
        $vICMSSTRet = ''
    ) {
        $identificador = 'N01 <ICMSxx> - ';
        switch ($cst) {
            case '00':
                $icms = $this->dom->createElement("ICMS00");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 00");
                $this->zAddChild($icms, 'modBC', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'vBC', $vBC, true, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pICMS', $pICMS, true, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMS', $vICMS, true, "$identificador [item $nItem] Valor do ICMS");
                break;
            case '10':
                $icms = $this->dom->createElement("ICMS10");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 10");
                $this->zAddChild($icms, 'modBC', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'vBC', $vBC, true, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pICMS', $pICMS, true, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMS', $vICMS, true, "$identificador [item $nItem] Valor do ICMS");
                $this->zAddChild($icms, 'modBCST', $modBCST, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS ST");
                $this->zAddChild(
                    $icms,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "$identificador [item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icms, 'pRedBCST', $pRedBCST, false, "$identificador [item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icms, 'vBCST', $vBCST, true, "$identificador [item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icms, 'pICMSST', $pICMSST, true, "$identificador [item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icms, 'vICMSST', $vICMSST, true, "$identificador [item $nItem] Valor do ICMS ST");
                break;
            case '20':
                $icms = $this->dom->createElement("ICMS20");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 20");
                $this->zAddChild($icms, 'modBC', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'pRedBC', $pRedBCST, true, "$identificador [item $nItem] Percentual da Redução de BC");
                $this->zAddChild($icms, 'vBC', $vBC, true, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pICMS', $pICMS, true, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMS', $vICMS, true, "$identificador [item $nItem] Valor do ICMS");
                $this->zAddChild($icms, 'vICMSDeson', $vICMSDeson, false, "$identificador [item $nItem] Valor do ICMS desonerado");
                $this->zAddChild($icms, 'motDesICMS', $motDesICMS, false, "$identificador [item $nItem] Motivo da desoneração do ICMS");
                break;
            case '30':
                $icms = $this->dom->createElement("ICMS30");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 30");
                $this->zAddChild($icms, 'modBCST', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS ST");
                $this->zAddChild(
                    $icms,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "$identificador [item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icms, 'pRedBCST', $pRedBCST, false, "$identificador [item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icms, 'vBCST', $vBCST, true, "$identificador [item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icms, 'pICMSST', $pICMSST, true, "$identificador [item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icms, 'vICMSST', $vICMSST, true, "$identificador [item $nItem] Valor do ICMS ST");
                $this->zAddChild($icms, 'vICMSDeson', $vICMSDeson, false, "$identificador [item $nItem] Valor do ICMS desonerado");
                $this->zAddChild($icms, 'motDesICMS', $motDesICMS, false, "$identificador [item $nItem] Motivo da desoneração do ICMS");
                break;
            case '40':
            case '41':
            case '50':
                $icms = $this->dom->createElement("ICMS40");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS $cst");
                $this->zAddChild($icms, 'vICMSDeson', $vICMSDeson, false, "$identificador [item $nItem] Valor do ICMS desonerado");
                $this->zAddChild($icms, 'motDesICMS', $motDesICMS, false, "$identificador [item $nItem] Motivo da desoneração do ICMS");
                break;
            case '51':
                $icms = $this->dom->createElement("ICMS51");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 51");
                $this->zAddChild($icms, 'modBC', $modBC, false, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'pRedBC', $pRedBCST, false, "$identificador [item $nItem] Percentual da Redução de BC");
                $this->zAddChild($icms, 'vBC', $vBC, false, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pICMS', $pICMS, false, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMSOp', $vICMSOp, false, "$identificador [item $nItem] Valor do ICMS da Operação");
                $this->zAddChild($icms, 'pDif', $pDif, false, "$identificador [item $nItem] Percentual do diferimento");
                $this->zAddChild($icms, 'vICMSDif', $vICMSDif, false, "$identificador [item $nItem] Valor do ICMS diferido");
                break;
            case '60':
                $icms = $this->dom->createElement("ICMS60");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 60");
                $this->zAddChild($icms, 'vBCSTRet', $vBCSTRet, false, "$identificador [item $nItem] Valor da BC do ICMS ST retido");
                $this->zAddChild($icms, 'vICMSSTRet', $vICMSSTRet, false, "$identificador [item $nItem] Valor do ICMS ST retido");
                break;
            case '70':
                $icms = $this->dom->createElement("ICMS70");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 70");
                $this->zAddChild($icms, 'modBC', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'pRedBC', $pRedBCST, true, "$identificador [item $nItem] Percentual da Redução de BC");
                $this->zAddChild($icms, 'vBC', $vBC, true, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pICMS', $pICMS, true, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMS', $vICMS, true, "$identificador [item $nItem] Valor do ICMS");
                $this->zAddChild($icms, 'modBCST', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS ST");
                $this->zAddChild(
                    $icms,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "$identificador [item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icms, 'pRedBCST', $pRedBCST, false, "$identificador [item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icms, 'vBCST', $vBCST, true, "$identificador [item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icms, 'pICMSST', $pICMSST, true, "$identificador [item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icms, 'vICMSST', $vICMSST, true, "$identificador [item $nItem] Valor do ICMS ST");
                $this->zAddChild($icms, 'vICMSDeson', $vICMSDeson, false, "$identificador [item $nItem] Valor do ICMS desonerado");
                $this->zAddChild($icms, 'motDesICMS', $motDesICMS, false, "$identificador [item $nItem] Motivo da desoneração do ICMS");
                break;
            case '90':
                $icms = $this->dom->createElement("ICMS90");
                $this->zAddChild($icms, 'orig', $orig, true, "$identificador [item $nItem] Origem da mercadoria");
                $this->zAddChild($icms, 'CST', $cst, true, "$identificador [item $nItem] Tributação do ICMS = 90");
                $this->zAddChild($icms, 'modBC', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icms, 'vBC', $vBC, true, "$identificador [item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icms, 'pRedBC', $pRedBC, false, "$identificador [item $nItem] Percentual da Redução de BC");
                $this->zAddChild($icms, 'pICMS', $pICMS, true, "$identificador [item $nItem] Alíquota do imposto");
                $this->zAddChild($icms, 'vICMS', $vICMS, true, "$identificador [item $nItem] Valor do ICMS");
                $this->zAddChild($icms, 'modBCST', $modBC, true, "$identificador [item $nItem] Modalidade de determinação da BC do ICMS ST");
                $this->zAddChild(
                    $icms,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "$identificador [item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icms, 'pRedBCST', $pRedBCST, false, "$identificador [item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icms, 'vBCST', $vBCST, true, "$identificador [item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icms, 'pICMSST', $pICMSST, true, "$identificador [item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icms, 'vICMSST', $vICMSST, true, "$identificador [item $nItem] Valor do ICMS ST");
                $this->zAddChild($icms, 'vICMSDeson', $vICMSDeson, false, "$identificador [item $nItem] Valor do ICMS desonerado");
                $this->zAddChild($icms, 'motDesICMS', $motDesICMS, false, "$identificador [item $nItem] Motivo da desoneração do ICMS");
                break;
        }
        $tagIcms = $this->dom->createElement('ICMS');
        $tagIcms->appendChild($icms);
        $this->aICMS[$nItem] = $tagIcms;
        return $tagIcms;
    }
    
    /**
     * tagICMSPart
     * Grupo de Partilha do ICMS entre a UF de origem e UF de destino ou 
     * a UF definida na legislação. N10a pai N01 
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMSPart
     * @param string $nItem
     * @param string $orig
     * @param string $cst
     * @param string $modBC
     * @param string $vBC
     * @param string $pRedBC
     * @param string $pICMS
     * @param string $vICMS
     * @param string $modBCST
     * @param string $pMVAST
     * @param string $pRedBCST
     * @param string $vBCST
     * @param string $pICMSST
     * @param string $vICMSST
     * @param string $pBCOp
     * @param string $ufST
     * @return DOMElement
     */
    public function tagICMSPart(
        $nItem = '',
        $orig = '',
        $cst = '',
        $modBC = '',
        $vBC = '',
        $pRedBC = '',
        $pICMS = '',
        $vICMS = '',
        $modBCST = '',
        $pMVAST = '',
        $pRedBCST = '',
        $vBCST = '',
        $pICMSST = '',
        $vICMSST = '',
        $pBCOp = '',
        $ufST = ''
    ) {
        $icmsPart = $this->dom->createElement("ICMSPart");
        $this->zAddChild($icmsPart, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
        $this->zAddChild($icmsPart, 'CST', $cst, true, "[item $nItem] Tributação do ICMS 10 ou 90");
        $this->zAddChild($icmsPart, 'modBC', $modBC, true, "[item $nItem] Modalidade de determinação da BC do ICMS");
        $this->zAddChild($icmsPart, 'vBC', $vBC, true, "[item $nItem] Valor da BC do ICMS");
        $this->zAddChild($icmsPart, 'pRedBC', $pRedBC, false, "[item $nItem] Percentual da Redução de BC");
        $this->zAddChild($icmsPart, 'pICMS', $pICMS, true, "[item $nItem] Alíquota do imposto");
        $this->zAddChild($icmsPart, 'vICMS', $vICMS, true, "[item $nItem] Valor do ICMS");
        $this->zAddChild($icmsPart, 'modBCST', $modBCST, true, "[item $nItem] Modalidade de determinação da BC do ICMS ST");
        $this->zAddChild(
            $icmsPart,
            'pMVAST',
            $pMVAST,
            false,
            "[item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->zAddChild($icmsPart, 'pRedBCST', $pRedBCST, false, "[item $nItem] Percentual da Redução de BC do ICMS ST");
        $this->zAddChild($icmsPart, 'vBCST', $vBCST, true, "[item $nItem] Valor da BC do ICMS ST");
        $this->zAddChild($icmsPart, 'pICMSST', $pICMSST, true, "[item $nItem] Alíquota do imposto do ICMS ST");
        $this->zAddChild($icmsPart, 'vICMSST', $vICMSST, true, "[item $nItem] Valor do ICMS ST");
        $this->zAddChild($icmsPart, 'pBCOp', $pBCOp, true, "[item $nItem] Percentual da BC operação própria");
        $this->zAddChild($icmsPart, 'UFST', $ufST, true, "[item $nItem] UF para qual é devido o ICMS ST");
        //caso exista a tag aICMS[$nItem] inserir nela caso contrario criar
        if (!empty($this->aICMS[$nItem])) {
            $tagIcms = $this->aICMS[$nItem];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }
        $this->zAppChild($tagIcms, $icmsPart, "Inserindo ICMSPart em ICMS[$nItem]");
        $this->aICMS[$nItem] = $tagIcms;
        return $tagIcms;
    }
    
    /**
     * tagICMSST N10b pai N01
     * Grupo de Repasse de ICMS ST retido anteriormente em operações
     * interestaduais com repasses através do Substituto Tributário
     * @param string $nItem
     * @param string $orig
     * @param string $cst
     * @param string $vBCSTRet
     * @param string $vICMSSTRet
     * @param string $vBCSTDest
     * @param string $vICMSSTDest
     * @return DOMElement
     */
    public function tagICMSST(
        $nItem = '',
        $orig = '',
        $cst = '',
        $vBCSTRet = '',
        $vICMSSTRet = '',
        $vBCSTDest = '',
        $vICMSSTDest = ''
    ) {
        $icmsST = $this->dom->createElement("ICMSST");
        $this->zAddChild($icmsST, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
        $this->zAddChild($icmsST, 'CST', $cst, true, "[item $nItem] Tributação do ICMS 41");
        $this->zAddChild($icmsST, 'vBCSTRet', $vBCSTRet, true, "[item $nItem] Valor do BC do ICMS ST retido na UF remetente");
        $this->zAddChild($icmsST, 'vICMSSTRet', $vICMSSTRet, false, "[item $nItem] Valor do ICMS ST retido na UF remetente");
        $this->zAddChild($icmsST, 'vBCSTDest', $vBCSTDest, true, "[item $nItem] Valor da BC do ICMS ST da UF destino");
        $this->zAddChild($icmsST, 'vICMSSTDest', $vICMSSTDest, true, "[item $nItem] Valor do ICMS ST da UF destino");
        //caso exista a tag aICMS[$nItem] inserir nela caso contrario criar
        if (!empty($this->aICMS[$nItem])) {
            $tagIcms = $this->aICMS[$nItem];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }
        $this->zAppChild($tagIcms, $icmsST, "Inserindo ICMSST em ICMS[$nItem]");
        $this->aICMS[$nItem] = $tagIcms;
        return $tagIcms;
    }
    
    /**
     * tagICMSSN
     * Tributação ICMS pelo Simples Nacional N10c pai N01
     * @param type $nItem
     * @param type $orig
     * @param type $csosn
     * @param type $modBC
     * @param type $vBC
     * @param type $pRedBC
     * @param type $pICMS
     * @param type $vICMS
     * @param type $pCredSN
     * @param type $vCredICMSSN
     * @param type $modBCST
     * @param type $pMVAST
     * @param type $pRedBCST
     * @param type $vBCST
     * @param type $pICMSST
     * @param type $vICMSST
     * @param type $vBCSTRet
     * @param type $vICMSSTRet
     * @return DOMElement
     */
    public function tagICMSSN(
        $nItem = '',
        $orig = '',
        $csosn = '',
        $modBC = '',
        $vBC = '',
        $pRedBC = '',
        $pICMS = '',
        $vICMS = '',
        $pCredSN = '',
        $vCredICMSSN = '',
        $modBCST = '',
        $pMVAST = '',
        $pRedBCST = '',
        $vBCST = '',
        $pICMSST = '',
        $vICMSST = '',
        $vBCSTRet = '',
        $vICMSSTRet = ''
    ) {
        switch ($csosn) {
            case '101':
                $icmsSN = $this->dom->createElement("ICMSSN101");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild(
                    $icmsSN,
                    'CSOSN',
                    $csosn,
                    true,
                    "[item $nItem] Código de Situação da Operação Simples Nacional"
                );
                $this->zAddChild(
                    $icmsSN,
                    'pCredSN',
                    $pCredSN,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $vCredICMSSN,
                    true,
                    "[item $nItem] Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)"
                );
                break;
            case '102':
            case '103':
            case '300':
            case '400':
                $icmsSN = $this->dom->createElement("ICMSSN102");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild(
                    $icmsSN,
                    'CSOSN',
                    $csosn,
                    true,
                    "[item $nItem] Código de Situação da Operação Simples Nacional"
                );
                break;
            case '201':
                $icmsSN = $this->dom->createElement("ICMSSN201");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild(
                    $icmsSN,
                    'CSOSN',
                    $csosn,
                    true,
                    "[item $nItem] Código de Situação da Operação Simples Nacional"
                );
                $this->zAddChild(
                    $icmsSN,
                    'modBCST',
                    $modBCST,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "[item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icmsSN, 'pRedBCST', $pRedBCST, false, "[item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icmsSN, 'vBCST', $vBCST, true, "[item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icmsSN, 'pICMSST', $pICMSST, true, "[item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icmsSN, 'vICMSST', $vICMSST, true, "[item $nItem] Valor do ICMS ST");
                $this->zAddChild(
                    $icmsSN,
                    'pCredSN',
                    $pCredSN,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $vCredICMSSN,
                    true,
                    "[item $nItem] Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)"
                );
                break;
            case '202':
            case '203':
                $icmsSN = $this->dom->createElement("ICMSSN202");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild($icmsSN, 'CSOSN', $csosn, true, "[item $nItem] Código de Situação da Operação Simples Nacional");
                $this->zAddChild(
                    $icmsSN,
                    'modBCST',
                    $modBCST,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "[item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icmsSN, 'pRedBCST', $pRedBCST, false, "[item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icmsSN, 'vBCST', $vBCST, true, "[item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icmsSN, 'pICMSST', $pICMSST, true, "[item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icmsSN, 'vICMSST', $vICMSST, true, "[item $nItem] Valor do ICMS ST");
                break;
            case '500':
                $icmsSN = $this->dom->createElement("ICMSSN500");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild(
                    $icmsSN,
                    'CSOSN',
                    $csosn,
                    true,
                    "[item $nItem] Código de Situação da Operação Simples Nacional"
                );
                $this->zAddChild($icmsSN, 'vBCSTRet', $vBCSTRet, false, "[item $nItem] Valor da BC do ICMS ST retido");
                $this->zAddChild($icmsSN, 'vICMSSTRet', $vICMSSTRet, false, "[item $nItem] Valor do ICMS ST retido");
                break;
            case '900':
                $icmsSN = $this->dom->createElement("ICMSSN900");
                $this->zAddChild($icmsSN, 'orig', $orig, true, "[item $nItem] Origem da mercadoria");
                $this->zAddChild($icmsSN, 'CSOSN', $csosn, true, "[item $nItem] Código de Situação da Operação Simples Nacional");
                $this->zAddChild($icmsSN, 'modBC', $modBC, true, "[item $nItem] Modalidade de determinação da BC do ICMS");
                $this->zAddChild($icmsSN, 'vBC', $vBC, true, "[item $nItem] Valor da BC do ICMS");
                $this->zAddChild($icmsSN, 'pRedBC', $pRedBC, false, "[item $nItem] Percentual da Redução de BC");
                $this->zAddChild($icmsSN, 'pICMS', $pICMS, true, "[item $nItem] Alíquota do imposto");
                $this->zAddChild($icmsSN, 'vICMS', $vICMS, true, "[item $nItem] Valor do ICMS");
                $this->zAddChild(
                    $icmsSN,
                    'modBCST',
                    $modBCST,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'pMVAST',
                    $pMVAST,
                    false,
                    "[item $nItem] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->zAddChild($icmsSN, 'pRedBCST', $pRedBCST, false, "[item $nItem] Percentual da Redução de BC do ICMS ST");
                $this->zAddChild($icmsSN, 'vBCST', $vBCST, true, "[item $nItem] Valor da BC do ICMS ST");
                $this->zAddChild($icmsSN, 'pICMSST', $pICMSST, true, "[item $nItem] Alíquota do imposto do ICMS ST");
                $this->zAddChild($icmsSN, 'vICMSST', $vICMSST, true, "[item $nItem] Valor do ICMS ST");
                $this->zAddChild(
                    $icmsSN,
                    'pCredSN',
                    $pCredSN,
                    true,
                    "[item $nItem] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->zAddChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $vCredICMSSN,
                    true,
                    "[item $nItem] Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)"
                );
                break;
        }
        //caso exista a tag aICMS[$nItem] inserir nela caso contrario criar
        if (!empty($this->aICMS[$nItem])) {
            $tagIcms = $this->aICMS[$nItem];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }
        $this->zAppChild($tagIcms, $icmsSN, "Inserindo ICMSST em ICMS[$nItem]");
        $this->aICMS[$nItem] = $tagIcms;
        return $tagIcms;
    }
    
    /**
     * tagIPI
     * Grupo IPI O01 pai M01
     * tag NFe/infNFe/det[]/imposto/IPI (opcional)
     * @param string $nItem
     * @param string $cst
     * @param string $clEnq
     * @param string $cnpjProd
     * @param string $cSelo
     * @param string $qSelo
     * @param string $cEnq
     * @param string $vBC
     * @param string $pIPI
     * @param string $qUnid
     * @param string $vUnid
     * @param string $vIPI
     * @return DOMElement
     */
    public function tagIPI(
        $nItem = '',
        $cst = '',
        $clEnq = '',
        $cnpjProd = '',
        $cSelo = '',
        $qSelo = '',
        $cEnq = '',
        $vBC = '',
        $pIPI = '',
        $qUnid = '',
        $vUnid = '',
        $vIPI = ''
    ) {
        $ipi = $this->dom->createElement('IPI');
        $this->zAddChild($ipi, "clEnq", $clEnq, false, "[item $nItem] Classe de enquadramento do IPI para Cigarros e Bebidas");
        $this->zAddChild(
            $ipi,
            "CNPJProd",
            $cnpjProd,
            false,
            "[item $nItem] CNPJ do produtor da mercadoria, quando diferente do emitente. "
            . "Somente para os casos de exportação direta ou indireta."
        );
        $this->zAddChild($ipi, "cSelo", $cSelo, false, "[item $nItem] Código do selo de controle IPI");
        $this->zAddChild($ipi, "qSelo", $qSelo, false, "[item $nItem] Quantidade de selo de controle");
        $this->zAddChild($ipi, "cEnq", $cEnq, true, "[item $nItem] Código de Enquadramento Legal do IPI");
        if ($cst == '00' || $cst == '49'|| $cst == '50' || $cst == '99') {
            $ipiTrib = $this->dom->createElement('IPITrib');
            $this->zAddChild($ipiTrib, "CST", $cst, true, "[item $nItem] Código da situação tributária do IPI");
            $this->zAddChild($ipiTrib, "vBC", $vBC, true, "[item $nItem] Valor da BC do IPI");
            $this->zAddChild($ipiTrib, "pIPI", $pIPI, true, "[item $nItem] Alíquota do IPI");
            $this->zAddChild(
                $ipiTrib,
                "qUnid",
                $qUnid,
                true,
                "[item $nItem] Quantidade total na unidade padrão para tributação "
                . "(somente para os produtos tributados por unidade)"
            );
            $this->zAddChild($ipiTrib, "vUnid", $vUnid, true, "[item $nItem] Valor por Unidade Tributável");
            $this->zAddChild($ipiTrib, "vIPI", $vIPI, true, "[item $nItem] Valor do IPI");
            $ipi->appendChild($ipiTrib);
        } else {
            $ipINT = $this->dom->createElement('IPINT');
            $this->zAddChild($ipINT, "CST", $cst, true, "[item $nItem] Código da situação tributária do IPI");
            $ipi->appendChild($ipINT);
        }
        $this->aIPI[$nItem] = $ipi;
        return $ipi;
    }
    
    /**
     * tagII
     * Grupo Imposto de Importação P01 pai M01
     * tag NFe/infNFe/det[]/imposto/II
     * @param string $nItem
     * @param string $vBC
     * @param string $vDespAdu
     * @param string $vII
     * @param string $vIOF
     * @return DOMElement
     */
    public function tagII($nItem = '', $vBC = '', $vDespAdu = '', $vII = '', $vIOF = '')
    {
        $tii = $this->dom->createElement('II');
        $this->zAddChild($tii, "vBC", $vBC, true, "[item $nItem] Valor BC do Imposto de Importação");
        $this->zAddChild($tii, "vDespAdu", $vDespAdu, true, "[item $nItem] Valor despesas aduaneiras");
        $this->zAddChild($tii, "vII", $vII, true, "[item $nItem] Valor Imposto de Importação");
        $this->zAddChild($tii, "vIOF", $vIOF, true, "[item $nItem] Valor Imposto sobre Operações Financeiras");
        $this->aII[$nItem] = $tii;
        return $tii;
    }
    
    /**
     * tagPIS
     * Grupo PIS Q01 pai M01
     * tag NFe/infNFe/det[]/imposto/PIS
     * @param type $nItem
     * @param string $cst
     * @param string $vBC
     * @param string $pPIS
     * @param string $vPIS
     * @param string $qBCProd
     * @param string $vAliqProd
     * @return DOMElement
     */
    public function tagPIS(
        $nItem = '',
        $cst = '',
        $vBC = '',
        $pPIS = '',
        $vPIS = '',
        $qBCProd = '',
        $vAliqProd = ''
    ) {
        switch ($cst) {
            case '01':
            case '02':
                $pisItem = $this->dom->createElement('PISAliq');
                $this->zAddChild($pisItem, 'CST', $cst, true, "[item $nItem] Código de Situação Tributária do PIS");
                $this->zAddChild($pisItem, 'vBC', $vBC, true, "[item $nItem] Valor da Base de Cálculo do PIS");
                $this->zAddChild($pisItem, 'pPIS', $pPIS, true, "[item $nItem] Alíquota do PIS (em percentual)");
                $this->zAddChild($pisItem, 'vPIS', $vPIS, true, "[item $nItem] Valor do PIS");
                break;
            case '03':
                $pisItem = $this->dom->createElement('PISQtde');
                $this->zAddChild($pisItem, 'CST', $cst, true, "[item $nItem] Código de Situação Tributária do PIS");
                $this->zAddChild($pisItem, 'qBCProd', $qBCProd, true, "[item $nItem] Quantidade Vendida");
                $this->zAddChild($pisItem, 'vAliqProd', $vAliqProd, true, "[item $nItem] Alíquota do PIS (em reais)");
                $this->zAddChild($pisItem, 'vPIS', $vPIS, true, "[item $nItem] Valor do PIS");
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $pisItem = $this->dom->createElement('PISNT');
                $this->zAddChild($pisItem, 'CST', $cst, true, "[item $nItem] Código de Situação Tributária do PIS");
                break;
            case '49':
            case '50':
            case '51':
            case '52':
            case '53':
            case '54':
            case '55':
            case '56':
            case '60':
            case '61':
            case '62':
            case '63':
            case '64':
            case '65':
            case '66':
            case '67':
            case '70':
            case '71':
            case '72':
            case '73':
            case '74':
            case '75':
            case '98':
            case '99':
                $pisItem = $this->dom->createElement('PISOutr');
                $this->zAddChild($pisItem, 'CST', $cst, true, "[item $nItem] Código de Situação Tributária do PIS");
                $this->zAddChild($pisItem, 'vBC', $vBC, false, "[item $nItem] Valor da Base de Cálculo do PIS");
                $this->zAddChild($pisItem, 'pPIS', $pPIS, false, "[item $nItem] Alíquota do PIS (em percentual)");
                $this->zAddChild($pisItem, 'qBCProd', $qBCProd, false, "[item $nItem] Quantidade Vendida");
                $this->zAddChild($pisItem, 'vAliqProd', $vAliqProd, false, "[item $nItem] Alíquota do PIS (em reais)");
                $this->zAddChild($pisItem, 'vPIS', $vPIS, true, "[item $nItem] Valor do PIS");
                break;
        }
        $pis = $this->dom->createElement('PIS');
        $pis->appendChild($pisItem);
        $this->aPIS[$nItem] = $pis;
        return $pis;
    }
    
    /**
     * tagPISST
     * Grupo PIS Substituição Tributária R01 pai M01 
     * tag NFe/infNFe/det[]/imposto/PISST (opcional)
     * @param string $nItem
     * @param string $vBC
     * @param string $pPIS
     * @param string $qBCProd
     * @param string $vAliqProd
     * @param string $vPIS
     * @return DOMElement
     */
    public function tagPISST(
        $nItem = '',
        $vBC = '',
        $pPIS = '',
        $qBCProd = '',
        $vAliqProd = '',
        $vPIS = ''
    ) {
        $pisst = $this->dom->createElement('PISST');
        $this->zAddChild($pisst, 'vBC', $vBC, true, "[item $nItem] Valor da Base de Cálculo do PIS");
        $this->zAddChild($pisst, 'pPIS', $pPIS, true, "[item $nItem] Alíquota do PIS (em percentual)");
        $this->zAddChild($pisst, 'qBCProd', $qBCProd, true, "[item $nItem] Quantidade Vendida");
        $this->zAddChild($pisst, 'vAliqProd', $vAliqProd, true, "[item $nItem] Alíquota do PIS (em reais)");
        $this->zAddChild($pisst, 'vPIS', $vPIS, true, "[item $nItem] Valor do PIS");
        $this->aPISST[$nItem] = $pisst;
        return $pisst;
    }

    /**
     * tagCOFINS
     * Grupo COFINS S01 pai M01
     * tag det/imposto/COFINS (opcional)
     * @param string $nItem
     * @param string $cst
     * @param string $vBC
     * @param string $pCOFINS
     * @param string $vCOFINS
     * @param string $qBCProd
     * @param string $vAliqProd
     * @return DOMElement
     */
    public function tagCOFINS(
        $nItem = '',
        $cst = '',
        $vBC = '',
        $pCOFINS = '',
        $vCOFINS = '',
        $qBCProd = '',
        $vAliqProd = ''
    ) {
        switch ($cst) {
            case '01':
            case '02':
                $confinsItem = $this->zTagCOFINSAliq($cst, $vBC, $pCOFINS, $vCOFINS);
                break;
            case '03':
                $confinsItem = $this->dom->createElement('COFINSQtde');
                $this->zAddChild($confinsItem, 'CST', $cst, true, "[item $nItem] Código de Situação Tributária da COFINS");
                $this->zAddChild($confinsItem, 'qBCProd', $qBCProd, true, "[item $nItem] Quantidade Vendida");
                $this->zAddChild($confinsItem, 'vAliqProd', $vAliqProd, true, "[item $nItem] Alíquota do COFINS (em reais)");
                $this->zAddChild($confinsItem, 'vCOFINS', $vCOFINS, true, "[item $nItem] Valor do COFINS");
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $confinsItem = $this->zTagCOFINSNT($cst);
                break;
            case '49':
            case '50':
            case '51':
            case '52':
            case '53':
            case '54':
            case '55':
            case '56':
            case '60':
            case '61':
            case '62':
            case '63':
            case '64':
            case '65':
            case '66':
            case '67':
            case '70':
            case '71':
            case '72':
            case '73':
            case '74':
            case '75':
            case '98':
            case '99':
                $confinsItem = $this->zTagCOFINSoutr($cst, $vBC, $pCOFINS, $qBCProd, $vAliqProd, $vCOFINS);
                break;
        }
        $confins = $this->dom->createElement('COFINS');
        $confins->appendChild($confinsItem);
        $this->aCOFINS[$nItem] = $confins;
        return $confins;
    }
   
    /**
     * tagCOFINSST
     * Grupo COFINS Substituição Tributária T01 pai M01
     * tag NFe/infNFe/det[]/imposto/COFINSST (opcional)
     * @param string $nItem
     * @param string $vBC
     * @param string $pCOFINS
     * @param string $qBCProd
     * @param string $vAliqProd
     * @param string $vCOFINS
     * @return DOMElement
     */
    public function tagCOFINSST(
        $nItem = '',
        $vBC = '',
        $pCOFINS = '',
        $qBCProd = '',
        $vAliqProd = '',
        $vCOFINS = ''
    ) {
        $cofinsst = $this->dom->createElement("COFINSST");
        $this->zAddChild($cofinsst, "vBC", $vBC, true, "[item $nItem] Valor da Base de Cálculo da COFINS");
        $this->zAddChild($cofinsst, "pCOFINS", $pCOFINS, true, "[item $nItem] Alíquota da COFINS (em percentual)");
        $this->zAddChild($cofinsst, "qBCProd", $qBCProd, true, "[item $nItem] Quantidade Vendida");
        $this->zAddChild($cofinsst, "vAliqProd", $vAliqProd, true, "[item $nItem] Alíquota da COFINS (em reais)");
        $this->zAddChild($cofinsst, "vCOFINS", $vCOFINS, true, "[item $nItem] Valor da COFINS");
        $this->aCOFINSST[$nItem] = $cofinsst;
        return $cofinsst;
    }
    
    /**
     * tagISSQN
     * Grupo ISSQN U01 pai M01
     * tag NFe/infNFe/det[]/imposto/ISSQN (opcional)
     * @param string $nItem
     * @param string $vBC
     * @param string $vAliq
     * @param string $vISSQN
     * @param string $cMunFG
     * @param string $cListServ
     * @param string $vDeducao
     * @param string $vOutro
     * @param string $vDescIncond
     * @param string $vDescCond
     * @param string $vISSRet
     * @param string $indISS
     * @param string $cServico
     * @param string $cMun
     * @param string $cPais
     * @param string $nProcesso
     * @param string $indIncentivo
     * @return DOMElement
     */
    public function tagISSQN(
        $nItem = '',
        $vBC = '',
        $vAliq = '',
        $vISSQN = '',
        $cMunFG = '',
        $cListServ = '',
        $vDeducao = '',
        $vOutro = '',
        $vDescIncond = '',
        $vDescCond = '',
        $vISSRet = '',
        $indISS = '',
        $cServico = '',
        $cMun = '',
        $cPais = '',
        $nProcesso = '',
        $indIncentivo = ''
    ) {
        $issqn = $this->dom->createElement("ISSQN");
        $this->zAddChild($issqn, "vBC", $vBC, true, "[item $nItem] Valor da Base de Cálculo do ISSQN");
        $this->zAddChild($issqn, "vAliq", $vAliq, true, "[item $nItem] Alíquota do ISSQN");
        $this->zAddChild($issqn, "vISSQN", $vISSQN, true, "[item $nItem] Valor do ISSQN");
        $this->zAddChild(
            $issqn,
            "cMunFG",
            $cMunFG,
            true,
            "[item $nItem] Código do município de ocorrência do fato gerador do ISSQN"
        );
        $this->zAddChild($issqn, "cListServ", $cListServ, true, "[item $nItem] Item da Lista de Serviços");
        $this->zAddChild(
            $issqn,
            "vDeducao",
            $vDeducao,
            false,
            "[item $nItem] Valor dedução para redução da Base de Cálculo"
        );
        $this->zAddChild($issqn, "vOutro", $vOutro, false, "[item $nItem] Valor outras retenções");
        $this->zAddChild($issqn, "vDescIncond", $vDescIncond, false, "[item $nItem] Valor desconto incondicionado");
        $this->zAddChild($issqn, "vDescCond", $vDescCond, false, "[item $nItem] Valor desconto condicionado");
        $this->zAddChild($issqn, "vISSRet", $vISSRet, false, "[item $nItem] Valor retenção ISS");
        $this->zAddChild($issqn, "indISS", $indISS, true, "[item $nItem] Indicador da exigibilidade do ISS");
        $this->zAddChild($issqn, "cServico", $cServico, false, "[item $nItem] Código do serviço prestado dentro do município");
        $this->zAddChild($issqn, "cMun", $cMun, false, "[item $nItem] Código do Município de incidência do imposto");
        $this->zAddChild($issqn, "cPais", $cPais, false, "[item $nItem] Código do País onde o serviço foi prestado");
        $this->zAddChild(
            $issqn,
            "nProcesso",
            $nProcesso,
            false,
            "[item $nItem] Número do processo judicial ou administrativo de suspensão da exigibilidade"
        );
        $this->zAddChild($issqn, "indIncentivo", $indIncentivo, true, "[item $nItem] Indicador de incentivo Fiscal");
        $this->aISSQN[$nItem] = $issqn;
        return $issqn;
    }
    
    /**
     * tagimpostoDevol
     * Informação do Imposto devolvido U50 pai H01
     * tag NFe/infNFe/det[]/impostoDevol (opcional)
     * @param string $pDevol
     * @param string $vIPIDevol
     * @return DOMElement
     */
    public function tagimpostoDevol($nItem = '', $pDevol = '', $vIPIDevol = '')
    {
        $impostoDevol = $this->dom->createElement("impostoDevol");
        $this->zAddChild(
            $impostoDevol,
            "pDevol",
            $pDevol,
            true,
            "[item $nItem] Percentual da mercadoria devolvida"
        );
        $parent = $this->dom->createElement("IPI");
        $this->zAddChild(
            $parent,
            "vIPIDevol",
            $vIPIDevol,
            true,
            "[item $nItem] Valor do IPI devolvido"
        );
        $impostoDevol->appendChild($parent);
        $this->aImpostoDevol[$nItem] = $impostoDevol;
        return $impostoDevol;
    }
  
    /**
     * tagICMSTot
     * Grupo Totais referentes ao ICMS W02 pai W01
     * tag NFe/infNFe/total/ICMSTot
     * @param string $vBC
     * @param string $vICMS
     * @param string $vICMSDeson
     * @param string $vBCST
     * @param string $vST
     * @param string $vProd
     * @param string $vFrete
     * @param string $vSeg
     * @param string $vDesc
     * @param string $vII
     * @param string $vIPI
     * @param string $vPIS
     * @param string $vCOFINS
     * @param string $vOutro
     * @param string $vNF
     * @param string $vTotTrib
     * @return DOMElement
     */
    public function tagICMSTot(
        $vBC = '',
        $vICMS = '',
        $vICMSDeson = '',
        $vBCST = '',
        $vST = '',
        $vProd = '',
        $vFrete = '',
        $vSeg = '',
        $vDesc = '',
        $vII = '',
        $vIPI = '',
        $vPIS = '',
        $vCOFINS = '',
        $vOutro = '',
        $vNF = '',
        $vTotTrib = ''
    ) {
        $this->zTagtotal();
        $ICMSTot = $this->dom->createElement("ICMSTot");
        $this->zAddChild($ICMSTot, "vBC", $vBC, true, "Base de Cálculo do ICMS");
        $this->zAddChild($ICMSTot, "vICMS", $vICMS, true, "Valor Total do ICMS");
        $this->zAddChild($ICMSTot, "vICMSDeson", $vICMSDeson, true, "Valor Total do ICMS desonerado");
        $this->zAddChild($ICMSTot, "vBCST", $vBCST, true, "Base de Cálculo do ICMS ST");
        $this->zAddChild($ICMSTot, "vST", $vST, true, "Valor Total do ICMS ST");
        $this->zAddChild($ICMSTot, "vProd", $vProd, true, "Valor Total dos produtos e servi�os");
        $this->zAddChild($ICMSTot, "vFrete", $vFrete, true, "Valor Total do Frete");
        $this->zAddChild($ICMSTot, "vSeg", $vSeg, true, "Valor Total do Seguro");
        $this->zAddChild($ICMSTot, "vDesc", $vDesc, true, "Valor Total do Desconto");
        $this->zAddChild($ICMSTot, "vII", $vII, true, "Valor Total do II");
        $this->zAddChild($ICMSTot, "vIPI", $vIPI, true, "Valor Total do IPI");
        $this->zAddChild($ICMSTot, "vPIS", $vPIS, true, "Valor do PIS");
        $this->zAddChild($ICMSTot, "vCOFINS", $vCOFINS, true, "Valor da COFINS");
        $this->zAddChild($ICMSTot, "vOutro", $vOutro, true, "Outras Despesas acessórias");
        $this->zAddChild($ICMSTot, "vNF", $vNF, true, "Valor Total da NF-e");
        $this->zAddChild(
            $ICMSTot,
            "vTotTrib",
            $vTotTrib,
            false,
            "Valor aproximado total de tributos federais, estaduais e municipais."
        );
        $this->zAppChild($this->total, $ICMSTot, '');
        return $ICMSTot;
    }
    
    /**
     * tagISSQNTot
     * Grupo Totais referentes ao ISSQN W17 pai W01
     * tag NFe/infNFe/total/ISSQNTot (opcional)
     * @param string $vServ
     * @param string $vBC
     * @param string $vISS
     * @param string $vPIS
     * @param string $vCOFINS
     * @param string $dCompet
     * @param string $vDeducao
     * @param string $vOutro
     * @param string $vDescIncond
     * @param string $vDescCond
     * @param string $vISSRet
     * @param string $cRegTrib
     * @param string $vOutro
     * @param string $vDescIncond
     * @param string $vDescCond
     * @param string $vISSRet
     * @param string $cRegTrib
     * @return DOMElement
     */
    public function tagISSQNTot(
        $vServ = '',
        $vBC = '',
        $vISS = '',
        $vPIS = '',
        $vCOFINS = '',
        $dCompet = '',
        $vDeducao = '',
        $vOutro = '',
        $vDescIncond = '',
        $vDescCond = '',
        $vISSRet = '',
        $cRegTrib = ''
    ) {
        $this->ztagtotal();
        $ISSQNTot = $this->dom->createElement("ISSQNtot");
        $this->zAddChild(
            $ISSQNTot,
            "vServ",
            $vServ,
            false,
            "Valor total dos Serviços sob não incidência ou não tributados pelo ICMS"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vBC",
            $vBC,
            false,
            "Valor total Base de Cálculo do ISS"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vISS",
            $vISS,
            false,
            "Valor total do ISS"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vPIS",
            $vPIS,
            false,
            "Valor total do PIS sobre serviços"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vCOFINS",
            $vCOFINS,
            false,
            "Valor total da COFINS sobre serviços"
        );
        $this->zAddChild(
            $ISSQNTot,
            "dCompet",
            $dCompet,
            true,
            "Data da prestação do serviço"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vDeducao",
            $vDeducao,
            false,
            "Valor total dedução para redução da Base de Cálculo"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vOutro",
            $vOutro,
            false,
            "Valor total outras retenções"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vDescIncond",
            $vDescIncond,
            false,
            "Valor total desconto incondicionado"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vDescCond",
            $vDescCond,
            false,
            "Valor total desconto condicionado"
        );
        $this->zAddChild(
            $ISSQNTot,
            "vISSRet",
            $vISSRet,
            false,
            "Valor total retenção ISS"
        );
        $this->zAddChild(
            $ISSQNTot,
            "cRegTrib",
            $cRegTrib,
            false,
            "Código do Regime Especial de Tributação"
        );
        $this->zAppChild($this->total, $ISSQNTot, '');
        return $ISSQNTot;
    }
        
    /**
     * tagretTrib
     * Grupo Retenções de Tributos W23 pai W01
     * tag NFe/infNFe/total/reTrib (opcional)
     * @param string $vRetPIS
     * @param string $vRetCOFINS
     * @param string $vRetCSLL
     * @param string $vBCIRRF
     * @param string $vIRRF
     * @param string $vBCRetPrev
     * @param string $vRetPrev
     * @return DOMElement
     */
    public function tagretTrib(
        $vRetPIS = '',
        $vRetCOFINS = '',
        $vRetCSLL = '',
        $vBCIRRF = '',
        $vIRRF = '',
        $vBCRetPrev = '',
        $vRetPrev = ''
    ) {
        $retTrib = $this->dom->createElement("retTrib");
        $this->zAddChild(
            $retTrib,
            "vRetPIS",
            $vRetPIS,
            false,
            "Valor Retido de PIS"
        );
        $this->zAddChild(
            $retTrib,
            "vRetCOFINS",
            $vRetCOFINS,
            false,
            "Valor Retido de COFINS"
        );
        $this->zAddChild(
            $retTrib,
            "vRetCSLL",
            $vRetCSLL,
            false,
            "Valor Retido de CSLL"
        );
        $this->zAddChild(
            $retTrib,
            "vBCIRRF",
            $vBCIRRF,
            false,
            "Base de Cálculo do IRRF"
        );
        $this->zAddChild(
            $retTrib,
            "vIRRF",
            $vIRRF,
            false,
            "Valor Retido do IRRF"
        );
        $this->zAddChild(
            $retTrib,
            "vBCRetPrev",
            $vBCRetPrev,
            false,
            "Base de Cálculo da Retenção da Previdência Social"
        );
        $this->zAddChild(
            $retTrib,
            "vRetPrev",
            $vRetPrev,
            false,
            "Valor da Retenção da Previdência Social"
        );
        $this->zAppChild($this->total, $retTrib, '');
        return $retTrib;
    }
    
    /**
     * tagtransp
     * Grupo Informações do Transporte X01 pai A01
     * tag NFe/infNFe/transp (obrigatório)
     * @param string $modFrete
     * @return DOMElement
     */
    public function tagtransp($modFrete = '')
    {
        $this->transp = $this->dom->createElement("transp");
        $this->zAddChild($this->transp, "modFrete", $modFrete, true, "Modalidade do frete");
        return $this->transp;
    }
    
    /**
     * tagtransporta
     * Grupo Transportador X03 pai X01
     * tag NFe/infNFe/transp/tranporta (opcional)
     * @param string $numCNPJ
     * @param string $numCPF
     * @param string $xNome
     * @param string $numIE
     * @param string $xEnder
     * @param string $xMun
     * @param string $siglaUF
     * @return DOMElement
     */
    public function tagtransporta(
        $numCNPJ = '',
        $numCPF = '',
        $xNome = '',
        $numIE = '',
        $xEnder = '',
        $xMun = '',
        $siglaUF = ''
    ) {
        $transporta = $this->dom->createElement("transporta");
        $this->zAddChild($transporta, "CNPJ", $numCNPJ, false, "CNPJ do Transportador");
        $this->zAddChild($transporta, "CPF", $numCPF, false, "CPF do Transportador");
        $this->zAddChild($transporta, "xNome", $xNome, false, "Razão Social ou nome do Transportador");
        $this->zAddChild($transporta, "IE", $numIE, false, "Inscrição Estadual do Transportador");
        $this->zAddChild($transporta, "xEnder", $xEnder, false, "Endereço Completo do Transportador");
        $this->zAddChild($transporta, "xMun", $xMun, false, "Nome do município do Transportador");
        $this->zAddChild($transporta, "UF", $siglaUF, false, "Sigla da UF do Transportador");
        $this->zAppChild($this->transp, $transporta, 'A tag transp deveria ter sido carregada primeiro.');
        return $transporta;
    }
    
    /**
     * tagveicTransp
     * Grupo Veículo Transporte X18 pai X17.1
     * tag NFe/infNFe/transp/veicTransp (opcional)
     * @param string $placa
     * @param string $siglaUF
     * @param string $rntc
     * @return DOMElement
     */
    public function tagveicTransp(
        $placa = '',
        $siglaUF = '',
        $rntc = ''
    ) {
        $veicTransp = $this->dom->createElement("veicTransp");
        $this->zAddChild($veicTransp, "placa", $placa, true, "Placa do Veículo");
        $this->zAddChild($veicTransp, "UF", $siglaUF, true, "Sigla da UF do Veículo");
        $this->zAddChild(
            $veicTransp,
            "RNTC",
            $rntc,
            false,
            "Registro Nacional de Transportador de Carga (ANTT) do Veículo"
        );
        $this->zAppChild($this->transp, $veicTransp, 'A tag transp deveria ter sido carregada primeiro.');
        return $veicTransp;
    }
    
    /**
     * tagreboque
     * Grupo Reboque X22 pai X17.1
     * tag NFe/infNFe/transp/reboque (opcional)
     * @param string $placa
     * @param string $siglaUF
     * @param string $rntc
     * @param string $vagao
     * @param string $balsa
     * @return DOMElement
     */
    public function tagreboque(
        $placa = '',
        $siglaUF = '',
        $rntc = '',
        $vagao = '',
        $balsa = ''
    ) {
        $reboque = $this->dom->createElement("reboque");
        $this->zAddChild($reboque, "placa", $placa, true, "Placa do Veículo Reboque");
        $this->zAddChild($reboque, "UF", $siglaUF, true, "Sigla da UF do Veículo Reboque");
        $this->zAddChild(
            $reboque,
            "RNTC",
            $rntc,
            false,
            "Registro Nacional de Transportador de Carga (ANTT) do Veículo Reboque"
        );
        $this->zAddChild($reboque, "vagao", $vagao, false, "Identificação do vagão do Veículo Reboque");
        $this->zAddChild($reboque, "balsa", $balsa, false, "Identificação da balsa do Veículo Reboque");
        $this->aReboque[] = $reboque;
        $this->zAppChild($this->transp, $reboque, 'A tag transp deveria ter sido carregada primeiro.');
        return $reboque;
    }
        
    /**
     * tagretTransp
     * Grupo Retenção ICMS transporte X11 pai X01
     * tag NFe/infNFe/transp/retTransp (opcional)
     * @param string $vServ
     * @param string $vBCRet
     * @param string $pICMSRet
     * @param string $vICMSRet
     * @param string $cfop
     * @param string $cMunFG
     * @return DOMElement
     */
    public function tagretTransp(
        $vServ = '',
        $vBCRet = '',
        $pICMSRet = '',
        $vICMSRet = '',
        $cfop = '',
        $cMunFG = ''
    ) {
        $retTransp = $this->dom->createElement("retTransp");
        $this->zAddChild($retTransp, "vServ", $vServ, true, "Valor do Serviço");
        $this->zAddChild($retTransp, "vBCRet", $vBCRet, true, "BC da Retenção do ICMS");
        $this->zAddChild($retTransp, "pICMSRet", $pICMSRet, true, "Alíquota da Retenção");
        $this->zAddChild($retTransp, "vICMSRet", $vICMSRet, true, "Valor do ICMS Retido");
        $this->zAddChild($retTransp, "CFOP", $cfop, true, "CFOP");
        $this->zAddChild(
            $retTransp,
            "cMunFG",
            $cMunFG,
            true,
            "Código do município de ocorrência do fato gerador do ICMS do transporte"
        );
        $this->zAppChild($this->transp, $retTransp, 'A tag transp deveria ter sido carregada primeiro.');
        return $retTransp;
    }
    
    /**
     * tagvol
     * Grupo Volumes X26 pai X01
     * tag NFe/infNFe/transp/vol (opcional)
     * @param string $qVol
     * @param string $esp
     * @param string $marca
     * @param string $nVol
     * @param string $pesoL
     * @param string $pesoB
     * @param array $aLacres
     * @return DOMElement
     */
    public function tagvol(
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
        $this->zAppChild($this->transp, $vol, 'A tag transp deveria ter sido carregada primeiro.');
        return $vol;
    }

    /**
     * tagfat
     * Grupo Fatura Y02 pai Y01
     * tag NFe/infNFe/cobr/fat (opcional)
     * @param string $nFat
     * @param string $vOrig
     * @param string $vDesc
     * @param string $vLiq
     * @return DOMElemente
     */
    public function tagfat(
        $nFat = '',
        $vOrig = '',
        $vDesc = '',
        $vLiq = ''
    ) {
        $this->zTagcobr();
        $fat = $this->dom->createElement("fat");
        $this->zAddChild($fat, "nFat", $nFat, false, "Número da Fatura");
        $this->zAddChild($fat, "vOrig", $vOrig, false, "Valor Original da Fatura");
        $this->zAddChild($fat, "vDesc", $vDesc, false, "Valor do desconto");
        $this->zAddChild($fat, "vLiq", $vLiq, false, "Valor Líquido da Fatura");
        $this->zAppChild($this->cobr, $fat);
        return $fat;
    }
    
    /**
     * tagdup
     * Grupo Duplicata Y07 pai Y02
     * tag NFe/infNFe/cobr/fat/dup (opcional)
     * É necessário criar a tag fat antes de criar as duplicatas
     * @param string $nDup
     * @param string $dVenc
     * @param string $vDup
     * @return DOMElement
     */
    public function tagdup(
        $nDup = '',
        $dVenc = '',
        $vDup = ''
    ) {
        $this->zTagcobr();
        $dup = $this->dom->createElement("dup");
        $this->zAddChild($dup, "nDup", $nDup, false, "Número da Duplicata");
        $this->zAddChild($dup, "dVenc", $dVenc, false, "Data de vencimento");
        $this->zAddChild($dup, "vDup", $vDup, true, "Valor da duplicata");
        $this->zAppChild($this->cobr, $dup, 'Inclui duplicata na tag cobr');
        $this->aDup[] = $dup;
        return $dup;
    }
    
    /**
     * tagpag
     * Grupo de Formas de Pagamento YA01 pai A01
     * tag NFe/infNFe/pag (opcional)
     * Apenas par amodelo 65 NFCe
     * @param string $tPag
     * @param string $vPag
     * @return DOMElement
     */
    public function tagpag(
        $tPag = '',
        $vPag = ''
    ) {
        $this->pag = $this->dom->createElement("pag");
        $this->zAddChild($this->pag, "tPag", $tPag, true, "Forma de pagamento");
        $this->zAddChild($this->pag, "vPag", $vPag, true, "Valor do Pagamento");
        return $this->pag;
    }
    
    /**
     * tagcard
     * Grupo de Cartões YA04 pai YA01
     * tag NFe/infNFe/pag/card
     * @param string $cnpj
     * @param string $tBand
     * @param string $cAut
     * @return DOMElement
     */
    public function tagcard(
        $cnpj = '',
        $tBand = '',
        $cAut = ''
    ) {
        //apenas para modelo 65
        if ($this->mod == '65' && $tBand != '') {
            $card = $this->dom->createElement("card");
            $this->zAddChild(
                $card,
                "CNPJ",
                $cnpj,
                true,
                "CNPJ da Credenciadora de cartão de crédito e/ou débito"
            );
            $this->zAddChild(
                $card,
                "tBand",
                $tBand,
                true,
                "Bandeira da operadora de cartão de crédito e/ou débito"
            );
            $this->zAddChild(
                $card,
                "cAut",
                $cAut,
                true,
                "Número de autorização da operação cartão de crédito e/ou débito"
            );
            $this->zAppChild($this->pag, $card, '');
            return $card;
        }
    }

    /**
     * taginfAdic
     * Grupo de Informações Adicionais Z01 pai A01
     * tag NFe/infNFe/infAdic (opcional)
     * @param string $infAdFisco
     * @param string $infCpl
     * @return DOMElement
     */
    public function taginfAdic(
        $infAdFisco = '',
        $infCpl = ''
    ) {
        $this->zTaginfAdic();
        $this->zAddChild(
            $this->infAdic,
            "infAdFisco",
            $infAdFisco,
            false,
            "Informações Adicionais de Interesse do Fisco"
        );
        $this->zAddChild(
            $this->infAdic,
            "infCpl",
            $infCpl,
            false,
            "Informações Complementares de interesse do Contribuinte"
        );
        return $this->infAdic;
    }
    
    /**
     * tagobsCont
     * Grupo Campo de uso livre do contribuinte Z04 pai Z01
     * tag NFe/infNFe/infAdic/obsCont (opcional)
     * O método taginfAdic deve ter sido carregado antes
     * @param string $xCampo
     * @param string $xTexto
     * @return DOMElement
     */
    public function tagobsCont(
        $xCampo = '',
        $xTexto = ''
    ) {
        $this->zTaginfAdic();
        $obsCont = $this->dom->createElement("obsCont");
        $obsCont->setAttribute("xCampo", $xCampo);
        $this->zAddChild($obsCont, "xTexto", $xTexto, true, "Conteúdo do campo");
        $this->aObsCont[] = $obsCont;
        $this->zAppChild($this->infAdic, $obsCont, '');
        return $obsCont;
    }
    
    /**
     * tagobsFisco
     * Grupo Campo de uso livre do Fisco Z07 pai Z01
     * tag NFe/infNFe/infAdic/obsFisco (opcional)
     * O método taginfAdic deve ter sido carregado antes
     * @param string $xCampo
     * @param string $xTexto
     * @return DOMElement
     */
    public function tagobsFisco(
        $xCampo = '',
        $xTexto = ''
    ) {
        $this->zTaginfAdic();
        $obsFisco = $this->dom->createElement("obsFisco");
        $obsFisco->setAttribute("xCampo", $xCampo);
        $this->zAddChild($obsFisco, "xTexto", $xTexto, true, "Conteúdo do campo");
        $this->aObsFisco[] = $obsFisco;
        $this->zAppChild($this->infAdic, $obsFisco, '');
        return $obsFisco;
    }
    
    /**
     * tagprocRef
     * Grupo Processo referenciado Z10 pai Z01 (NT2012.003)
     * tag NFe/infNFe/procRef (opcional)
     * O método taginfAdic deve ter sido carregado antes
     * @param string $nProc
     * @param string $indProc
     * @return DOMElement
     */
    public function tagprocRef(
        $nProc = '',
        $indProc = ''
    ) {
        $this->zTaginfAdic();
        $procRef = $this->dom->createElement("procRef");
        $this->zAddChild($procRef, "nProc", $nProc, true, "Identificador do processo ou ato concessório");
        $this->zAddChild($procRef, "indProc", $indProc, true, "Indicador da origem do processo");
        $this->aProcRef[] = $procRef;
        $this->zAppChild($this->infAdic, $procRef, '');
        return $procRef;
    }
    
    /**
     * tagexporta
     * Grupo Exportação ZA01 pai A01
     * tag NFe/infNFe/exporta (opcional)
     * @param string $ufSaidaPais
     * @param string $xLocExporta
     * @param string $xLocDespacho
     * @return DOMElement
     */
    public function tagexporta(
        $ufSaidaPais = '',
        $xLocExporta = '',
        $xLocDespacho = ''
    ) {
        $this->exporta = $this->dom->createElement("exporta");
        $this->zAddChild(
            $this->exporta,
            "UFSaidaPais",
            $ufSaidaPais,
            true,
            "Sigla da UF de Embarque ou de transposição de fronteira"
        );
        $this->zAddChild(
            $this->exporta,
            "xLocExporta",
            $xLocExporta,
            true,
            "Descrição do Local de Embarque ou de transposição de fronteira"
        );
        $this->zAddChild($this->exporta, "xLocDespacho", $xLocDespacho, false, "Descrição do local de despacho");
        return $this->exporta;
    }
    
    /**
     * tagcompra
     * Grupo Compra ZB01 pai A01
     * tag NFe/infNFe/compra (opcional)
     * @param string $xNEmp
     * @param string $xPed
     * @param string $xCont
     * @return DOMElement
     */
    public function tagcompra(
        $xNEmp = '',
        $xPed = '',
        $xCont = ''
    ) {
        $this->compra = $this->dom->createElement("compra");
        $this->zAddChild($this->compra, "xNEmp", $xNEmp, false, "Nota de Empenho");
        $this->zAddChild($this->compra, "xPed", $xPed, false, "Pedido");
        $this->zAddChild($this->compra, "xCont", $xCont, false, "Contrato");
        return $this->compra;
    }
    
    /**
     * tagcana
     * Grupo Cana ZC01 pai A01
     * tag NFe/infNFe/cana (opcional)
     * @param string $safra
     * @param string $ref
     * @return DOMELEment
     */
    public function tagcana(
        $safra = '',
        $ref = ''
    ) {
        $this->cana = $this->dom->createElement("cana");
        $this->zAddChild($this->cana, "safra", $safra, true, "Identificação da safra");
        $this->zAddChild($this->cana, "ref", $ref, true, "Mês e ano de referência");
        return $this->cana;
    }
    
    /**
     * tagforDia
     * Grupo Fornecimento diário de cana ZC04 pai ZC01
     * tag NFe/infNFe/cana/forDia
     * @param string $dia
     * @param string $qtde
     * @param string $qTotMes
     * @param string $qTotAnt
     * @param string $qTotGer
     * @return DOMElement
     */
    public function tagforDia(
        $dia = '',
        $qtde = '',
        $qTotMes = '',
        $qTotAnt = '',
        $qTotGer = ''
    ) {
        $forDia = $this->dom->createElement("forDia");
        $forDia->setAttribute("dia", $dia);
        $this->zAddChild($forDia, "qtde", $qtde, true, "Quantidade");
        $this->zAddChild($forDia, "qTotMes", $qTotMes, true, "Quantidade Total do Mês");
        $this->zAddChild($forDia, "qTotAnt", $qTotAnt, true, "Quantidade Total Anterior");
        $this->zAddChild($forDia, "qTotGer", $qTotGer, true, "Quantidade Total Geral");
        $this->aForDia[] = $forDia;
        $this->zAppChild($this->cana, $forDia, 'O metodo tacana deveria ter sido chamado antes. [tagforDia]');
        return $forDia;
    }
    
    /**
     * tagdeduc
     * Grupo Deduções – Taxas e Contribuições ZC10 pai ZC01
     * tag NFe/infNFe/cana/deduc (opcional)
     * @param string $xDed
     * @param string $vDed
     * @param string $vFor
     * @param string $vTotDed
     * @param string $vLiqFor
     * @return DOMElement
     */
    public function tagdeduc(
        $xDed = '',
        $vDed = '',
        $vFor = '',
        $vTotDed = '',
        $vLiqFor = ''
    ) {
        $deduc = $this->dom->createElement("deduc");
        $this->zAddChild($deduc, "xDed", $xDed, true, "Descrição da Dedução");
        $this->zAddChild($deduc, "vDed", $vDed, true, "Valor da Dedução");
        $this->zAddChild($deduc, "vFor", $vFor, true, "Valor dos Fornecimentos");
        $this->zAddChild($deduc, "vTotDed", $vTotDed, true, "Valor Total da Dedução");
        $this->zAddChild($deduc, "vLiqFor", $vLiqFor, true, "Valor Líquido dos Fornecimentos");
        $this->aDeduc[] = $deduc;
        $this->zAppChild($this->cana, $deduc, 'O metodo tagcana deveria ter sido chamado antes. [tagdeduc]');
        return $deduc;
    }

    /**
     * zTagNFe
     * Tag raiz da NFe
     * tag NFe DOMNode
     * Função chamada pelo método [ monta ]
     * @return DOMElement
     */
    private function zTagNFe()
    {
        if (empty($this->NFe)) {
            $this->NFe = $this->dom->createElement("NFe");
            $this->NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
        }
        return $this->NFe;
    }
    
    /**
     * zTagNFref
     * Informação de Documentos Fiscais referenciados BA01 pai B01
     * tag NFe/infNFe/ide/NFref
     * Podem ser criados até 500 desses Nodes por NFe
     * Função chamada pelos métodos 
     * [tagrefNFe] [tagrefNF] [tagrefNFP]  [tagCTeref] [tagrefECF]
     */
    private function zTagNFref()
    {
        $this->aNFref[] = $this->dom->createElement("NFref");
        return count($this->aNFref);
    }
    
    /**
     * zTagImp
     * Insere dentro dentro das tags imposto o ICMS IPI II PIS COFINS ISSQN
     * tag NFe/infNFe/det[]/imposto
     * @return void
     */
    private function zTagImp()
    {
        foreach ($this->aImposto as $nItem => $imposto) {
            if (!empty($this->aICMS[$nItem])) {
                $this->zAppChild($imposto, $this->aICMS[$nItem], "Inclusão do node ICMS");
            }
            if (!empty($this->aIPI[$nItem])) {
                $this->zAppChild($imposto, $this->aIPI[$nItem], "Inclusão do node IPI");
            }
            if (!empty($this->aII[$nItem])) {
                $this->zAppChild($imposto, $this->aII[$nItem], "Inclusão do node II");
            }
            if (!empty($this->aPIS[$nItem])) {
                $this->zAppChild($imposto, $this->aPIS[$nItem], "Inclusão do node PIS");
            }
            if (!empty($this->aPISST[$nItem])) {
                $this->zAppChild($imposto, $this->aPISST[$nItem], "Inclusão do node PISST");
            }
            if (!empty($this->aCOFINS[$nItem])) {
                $this->zAppChild($imposto, $this->aCOFINS[$nItem], "Inclusão do node COFINS");
            }
            if (!empty($this->aCOFINSST[$nItem])) {
                $this->zAppChild($imposto, $this->aCOFINSST[$nItem], "Inclusão do node COFINSST");
            }
            if (!empty($this->aISSQN[$nItem])) {
                $this->zAppChild($imposto, $this->aISSQN[$nItem], "Inclusão do node ISSQN");
            }
            $this->aImposto[$nItem] = $imposto;
        }
    }
    
    /**
     * ztagCOFINSAliq
     * Grupo COFINS tributado pela alíquota S02 pai S01
     * tag det/imposto/COFINS/COFINSAliq (opcional)
     * Função chamada pelo método [ tagCOFINS ]
     * @param string $cst
     * @param string $vBC
     * @param string $pCOFINS
     * @param string $vCOFINS
     * @return DOMElement
     */
    private function zTagCOFINSAliq($cst = '', $vBC = '', $pCOFINS = '', $vCOFINS = '')
    {
        $confinsAliq = $this->dom->createElement('COFINSAliq');
        $this->zAddChild($confinsAliq, 'CST', $cst, true, "Código de Situação Tributária da COFINS");
        $this->zAddChild($confinsAliq, 'vBC', $vBC, true, "Valor da Base de Cálculo da COFINS");
        $this->zAddChild($confinsAliq, 'pCOFINS', $pCOFINS, true, "Alíquota da COFINS (em percentual)");
        $this->zAddChild($confinsAliq, 'vCOFINS', $vCOFINS, true, "Valor da COFINS");
        return $confinsAliq;
    }
    
    /**
     * zTagCOFINSNT
     * Grupo COFINS não tributado S04 pai S01
     * tag NFe/infNFe/det[]/imposto/COFINS/COFINSNT (opcional)
     * Função chamada pelo método [ tagCOFINS ]
     * @param string $cst
     * @return DOMElement
     */
    private function zTagCOFINSNT($cst = '')
    {
        $confinsnt = $this->dom->createElement('COFINSNT');
        $this->zAddChild($confinsnt, "CST", $cst, true, "Código de Situação Tributária da COFINS");
        return $confinsnt;
    }
    
    /**
     * zTagCOFINSoutr
     * Grupo COFINS Outras Operações S05 pai S01
     * tag NFe/infNFe/det[]/imposto/COFINS/COFINSoutr (opcional)
     * Função chamada pelo método [ tagCOFINS ]
     * @param string $cst
     * @param string $vBC
     * @param string $pCOFINS
     * @param string $qBCProd
     * @param string $vAliqProd
     * @param string $vCOFINS
     * @return DOMElement
     */
    private function zTagCOFINSoutr($cst = '', $vBC = '', $pCOFINS = '', $qBCProd = '', $vAliqProd = '', $vCOFINS = '')
    {
        $confinsoutr = $this->dom->createElement('COFINSOutr');
        $this->zAddChild($confinsoutr, "CST", $cst, true, "Código de Situação Tributária da COFINS");
        $this->zAddChild($confinsoutr, "vBC", $vBC, false, "Valor da Base de Cálculo da COFINS");
        $this->zAddChild($confinsoutr, "pCOFINS", $pCOFINS, false, "Alíquota da COFINS (em percentual)");
        $this->zAddChild($confinsoutr, "qBCProd", $qBCProd, false, "Quantidade Vendida");
        $this->zAddChild($confinsoutr, "vAliqProd", $vAliqProd, false, "Alíquota da COFINS (em reais)");
        $this->zAddChild($confinsoutr, "vCOFINS", $vCOFINS, true, "Valor da COFINS");
        return $confinsoutr;
    }

    /**
     * zTagttotal
     * Grupo Totais da NF-e W01 pai A01
     * tag NFe/infNFe/total
     */
    private function zTagtotal()
    {
        if (empty($this->total)) {
            $this->total = $this->dom->createElement("total");
        }
    }
    
    /**
     * zTaglacres
     * Grupo Lacres X33 pai X26
     * tag NFe/infNFe/transp/vol/lacres (opcional)
     * @param string $nLacre
     * @return DOMElement
     */
    protected function zTaglacres($nLacre = '')
    {
        $lacre = $this->dom->createElement("lacres");
        $this->zAddChild($lacre, "nLacre", $nLacre, true, "Número dos Lacres");
        return $lacre;
    }
    
    /**
     * tagcobr
     * Grupo Cobrança Y01 pai A01
     * tag NFe/infNFe/cobr (opcional)
     * Depende de fat
     */
    private function zTagcobr()
    {
        if (empty($this->cobr)) {
            $this->cobr = $this->dom->createElement("cobr");
        }
    }
    
    /**
     * zTaginfAdic
     * Grupo de Informações Adicionais Z01 pai A01
     * tag NFe/infNFe/infAdic (opcional)
     * Função chamada pelos metodos 
     * [taginfAdic] [tagobsCont] [tagobsFisco] [tagprocRef]
     * 
     * @return DOMElement
     */
    private function zTaginfAdic()
    {
        if (empty($this->infAdic)) {
            $this->infAdic = $this->dom->createElement("infAdic");
        }
        return $this->infAdic;
    }
    
    /**
     * zAddChild
     * Adiciona um elemento ao node xml passado como referencia
     * Serão inclusos erros na array $erros[] sempre que a tag for obrigatória e
     * nenhum parâmetro for passado na variável $content e $force for false
     * @param DOMElement $parent
     * @param string $name
     * @param string $content
     * @param boolean $obrigatorio
     * @param string $descricao
     * @param boolean $force força a criação do elemento mesmo sem dados e não considera como erro
     * @return void
     */
    private function zAddChild(&$parent, $name, $content = '', $obrigatorio = false, $descricao = "", $force = false)
    {
        if ($obrigatorio && $content === '' && !$force) {
            $this->erros[] = array(
                "tag" => $name,
                "desc" => $descricao,
                "erro" => "Preenchimento Obrigatório!"
            );
        }
        if ($obrigatorio || $content !== '') {
            $content = trim($content);
            $temp = $this->dom->createElement($name, $content);
            $parent->appendChild($temp);
        }
    }
    
    /**
     * zAppChild
     * Acrescenta DOMElement a pai DOMElement
     * Caso o pai esteja vazio retorna uma exception com a mensagem
     * O parametro "child" pode ser vazio
     * @param DOMElement $parent
     * @param DOMElement $child
     * @param string $mensagem
     * @return void
     * @throws Exception
     */
    private function zAppChild(&$parent, $child, $mensagem = '')
    {
        if (empty($parent)) {
            throw new Exception($mensagem);
        }
        if (!empty($child)) {
            $parent->appendChild($child);
        }
    }
}
