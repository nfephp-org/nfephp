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
 * @version     1.3.1
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * 
 *        CONTRIBUIDORES (em ordem alfabetica):
 * 
 *              Elias Müller <elias at oxigennio dot com dot br>
 *              Cleiton Perin <cperin20 at gmail dot com>
 *              Marcos Balbi
 * 
 */

//namespace SpedPHP\NFe;

//use \DOMDocument;
//use \DOMElement;

class MakeNFe
{

    private $erros = array();
    public $errmsg='';
    public $versao;
    public $mod;
    public $dom; //DOMDocument
    public $NFe; //DOMNode
    public $infNFe; //DOMNode
    public $ide; //DOMNode
    public $NFref; //DOMNode
    public $refNFe; //DOMNode
    public $refNF; //DOMNode
    public $refNFP; //DOMNode
    public $refCTe; //DOMNode
    public $refECF; //DOMNode
    public $impostoDevol; //DOMNode
    public $emit; //DOMNode
    public $enderEmit; //DOMNode
    public $dest; //DOMNode
    public $enderDest; //DOMNode
    public $retirada; //DOMNode
    public $aAutXML = array(); //array de DOMNodes
    public $aDet; //array de DOMNodes
    public $aProd; //array de DOMNodes
    public $aDetExport; //array de DOMNodes
    public $aDI; //array de DOMNodes
    public $aAdi; //array de DOMNodes
    public $detExport; //array de DOMNodes
    public $aVeicProd; //array de DOMNodes
    public $aMed; //array de DOMNodes
    public $aArma; //array de DOMNodes
    public $aComb; //array de DOMNodes
    public $aImposto; //array de DOMNodes
    public $aICMS; //array de DOMNodes
    public $aIPI; //array de DOMNodes
    public $aII; //array de DOMNodes
    public $aISSQN; //array de DOMNodes
    public $aPIS; //array de DOMNodes
    public $aPISST; //array de DOMNodes
    public $aCOFINS; //array de DOMNodes
    public $aCOFINSST; //array de DOMNodes
    public $total; //DOMNode
    public $ICMSTot; //DOMNode
    public $ISSQNTot; //DOMNode
    public $retTrib; //DOMNode
    public $pag; //DOMNode
    public $card; //DOMNOde
    public $cobr; //DOMNode
    public $fat; //DOMNode
    public $aDup = array(); //array de DOMNodes
    public $transp; //DOMNode
    public $transporta; //DOMNode
    public $veicTransp; //DOMNode
    public $aReboque = array(); //array de DOMNodes
    public $aVol = array(); //array de DOMNodes
    public $infAdic; //DOMNode
    public $aObsCont = array(); //array de DOMNodes
    public $aObsFisco = array(); //array de DOMNodes
    public $aProcRef = array(); //array de DOMNodes
    public $exporta; //DOMNode
    public $compra; //DOMNode
    public $cana; //DOMNode
    public $aForDia = array(); //array de DOMNodes
    public $aDeduc = array(); //array de DOMNodes
    
    //cria DOM document
    public function __construct()
    {
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->preserveWhiteSpace = false;
    }

    public function montaNFe()
    {
        //as tags devem ser montadas e inseridas umas nas outras de dentro para fora
        //tags em ordem de montagem por método:
        //                            Modelo 55                 Modelo 65
        //  1 - tag infNFe        Obrigatório               Obrigatório
        //  2 - tag ide           Obrigatório               Obrigatório
        //  3 - tag refNFe        Opcional (se houver)      Opcional (se houver)
        //  4 - tag refNF         Opcional (se houver)      Opcional (se houver)
        //  5 - tag refNFP        Opcional (se houver)      Opcional (se houver)
        //  6 - tag refCTe        Opcional (se houver)      Opcional (se houver)
        //  7 - tag ECFref        Opcional (se houver)      Opcional (se houver)
        //  8 - tag emit          Obrigatório               Obrigatório
        //  9 - tag enderEmit     Obrigatório               Obrigatório
        // 10 - tag dest          Obrigatório               Opcional (se houver)
        // 11 - tag enderDest     Obrigatório               Opcional (se houver)
        // 12 - tag retirada      Opcional (se houver)      Opcional (se houver)
        // 13 - tag entrega       Opcional (se houver)      Opcional (se houver)
        // 14 - tag autXML        Opcional (se houver)      Opcional (se houver)
        // 15 - tag prod          Obrigatório               Obrigatório
        // 16 - tag DI            Opcional (se houver)      Opcional (se houver)
        // 17 - tag adi           Opcional (se houver)      Opcional (se houver)
        // 18 - tag veicProd      Opcional (se houver)      Opcional (se houver)
        // 19 - tag med           Opcional (se houver)      Opcional (se houver)
        // 20 - tag arma          Opcional (se houver)      Opcional (se houver)
        // 21 - tag comb          Opcional (se houver)      Opcional (se houver)
        // 22 - tag ICMS          Obrigatório               Obrigatório
        // 23 - tag IPI           Obrigatório               Obrigatório
        // 24 - tag II            Opcional (se houver)      Opcional (se houver)
        // 25 - tag PIS           Opcional (se houver)      Opcional (se houver)
        // 26 - tag COFINS        Opcional (se houver)      Opcional (se houver)
        // 27 - tag ISSQN         Opcional (se houver)      Opcional (se houver)
        // 28 - tag impostoDevol  Opcional (se houver)      Opcional (se houver)
        // 29 - tag ICMSTot       Obrigatório               Obrigatório
        // 30 - tag ISSQNTot      Opcional (se houver)      Opcional (se houver)
        // 31 - tag retTrib       Opcional (se houver)      Opcional (se houver)
        // 32 - tag transp        Obrigatório               Obrigatório
        // 33 - tag transporta    Opcional (se houver)      Opcional (se houver)
        // 34 - tag retTransp     Opcional (se houver)      Opcional (se houver)
        // 35 - tag veicTransp    Opcional (se houver)      Opcional (se houver)
        // 37 - tag reboque       Opcional (se houver)      Opcional (se houver)
        // 38 - tag lacres        Opcional (se houver)      Opcional (se houver)
        // 39 - tag vol           Opcional (se houver)      Opcional (se houver)
        // 40 - tag fat           Opcional (se houver)      Opcional (se houver)
        // 41 - tag dup           Opcional (se houver)      Opcional (se houver)
        // 42 - tag pag           Opcional (se houver)      Obrigatorio
        // 43 - tag card          Não aplicável             Opcional (se houver)
        // 44 - tag infAdic       Opcional (se houver)      Opcional (se houver)
        // 45 - tag obsCont       Opcional (se houver)      Opcional (se houver)
        // 46 - tag obsFisco      Opcional (se houver)      Opcional (se houver)
        // 47 - tag procRef       Opcional (se houver)      Opcional (se houver)
        // 48 - tag exporta       Opcional (se houver)      Opcional (se houver)
        // 49 - tag compra        Opcional (se houver)      Opcional (se houver)
        // 50 - tag cana          Opcional (se houver)      Não aplicavel
        // 51 - tag forDia        Opcional (se houver)      Não aplicavel
        // 52 - tag deduc         Opcional (se houver)      Não aplicavel

        //tag NFe
        $this->zTagNFe();

        //tag NFe/infNFe
        if (!isset($this->infNFe)) {
            return false;
        }

        //tag NFe/infNFe/ide
        if (isset($this->refNFe)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->refNFe);
        }
        if (isset($this->refNF)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->refNF);
        }
        if (isset($this->refNFP)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->refNFP);
        }
        if (isset($this->refCTe)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->refCTe);
        }
        if (isset($this->refECF)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->refECF);
        }
        if (isset($this->ide)) {
            if (isset($this->NFref)) {
                $this->ide->appendChild($this->NFref);
            }
        }
        $this->infNFe->appendChild($this->ide);

        //tag NFe/infNFe/emit
        if (isset($this->emit) && isset($this->enderEmit)) {
            $node = $this->emit->getElementsByTagName("IE")->item(0);
            $this->emit->insertBefore($this->enderEmit, $node);
        }
        $this->infNFe->appendChild($this->emit);

        //tag NFe/infNFe/dest
        if (isset($this->dest) && isset($this->enderDest)) {
            $node = $this->dest->getElementsByTagName("indIEDest")->item(0);
            if (!isset($node)) {
                $node = $this->dest->getElementsByTagName("IE")->item(0);
            }
            $this->dest->insertBefore($this->enderDest, $node);
        }
        if (isset($this->dest)) {
            $this->infNFe->appendChild($this->dest);
        }
        
        //tag NFe/infNFe/retirada
        if (isset($this->retirada)) {
            $this->infNFe->appendChild($this->retirada);
        }
        
        //tag NFe/infNFe/entrega
        if (isset($this->entrega)) {
            $this->infNFe->appendChild($this->entrega);
        }

        //tag NFe/infNFe/autXML
        if (isset($this->aAutXML)  && $this->versao > 2.00) {
            foreach ($this->aAutXML as $aut) {
                $this->infNFe->appendChild($aut);
            }
        }

        //tag NFe/infNFe/det/DI/adi
        if (isset($this->aAdi)) {
            
        }
        //tag NFe/infNFe/det/DI
        if (isset($this->aDI)) {
            
        }
        
        //tag NFe/infNFe/det
        if (isset($this->aProd)) {
            $this->tagdet();
        }

        if (isset($this->aDet)) {
            foreach ($this->aDet as $det) {
                $this->infNFe->appendChild($det);
            }
        }
        
        if (isset($this->aImposto) && isset($this->aDet)) {
            $this->tagImp();
        }
        
        //tag NFe/infNFe/total
        if (isset($this->ICMSTot)) {
            $this->tagtotal();
            $this->total->appendChild($this->ICMSTot);
        }
        if (isset($this->ISSQNTot)) {
            $this->tagtotal();
            $this->total->appendChild($this->ISSQNTot);
        }
        if (isset($this->retTrib)) {
            $this->tagtotal();
            $this->total->appendChild($this->retTrib);
        }
        if (isset($this->total)) {
            $this->infNFe->appendChild($this->total);
        }

        //tag NFe/infNFe/transp
        if (isset($this->transp) && isset($this->transporta)) {
            $this->transp->appendChild($this->transporta);
        }
        if (isset($this->transp) && isset($this->retTransp)) {
            $this->transp->appendChild($this->retTransp);
        }
        if (isset($this->transp) && isset($this->veicTransp)) {
            $this->transp->appendChild($this->veicTransp);
        }
        if (isset($this->transp) && isset($this->aReboque)) {
            foreach ($this->aReboque as $reboque) {
                $this->transp->appendChild($reboque);
            }
        }
        if (isset($this->aVol) && isset($this->transp)) {
            foreach ($this->aVol as $vol) {
                $this->transp->appendChild($vol);
            }
        }
        if (isset($this->transp)) {
            $this->infNFe->appendChild($this->transp);
        }
        
        //tag NFe/infNFe/cobr
        if (isset($this->fat)) {
            $this->tagcobr();
            $this->cobr->appendChild($this->fat);
        }
        if (isset($this->aDup)) {
            $this->tagcobr();
            foreach ($this->aDup as $dup) {
                $this->cobr->appendChild($dup);
            }
        }
        if (isset($this->cobr)) {
            $this->infNFe->appendChild($this->cobr);
        }
        
        //tag NFe/infNFe/pag
        if (isset($this->card)) {
            if (isset($this->pag)) {
                $this->pag->appendChild($this->card);
            }
        }
        if (isset($this->pag)) {
            $this->infNFe->appendChild($this->pag);
        }

        //tag NFe/infNFe/infAdic
        if (isset($this->aObsCont)) {
            foreach ($this->aObsCont as $obsCont) {
                if (!isset($this->infAdic)) {
                    $this->taginfAdic();
                }
                $this->infAdic->appendChild($obsCont);
            }
        }
        if (isset($this->aObsFisco)) {
            foreach ($this->aObsFisco as $obsFisco) {
                if (!isset($this->infAdic)) {
                    $this->taginfAdic();
                }
                $this->infAdic->appendChild($obsFisco);
            }
        }
        if (isset($this->aProcRef)) {
            foreach ($this->aProcRef as $procRef) {
                if (!isset($this->infAdic)) {
                    $this->taginfAdic();
                }
                $this->infAdic->appendChild($procRef);
            }
        }
        if (isset($this->infAdic)) {
            $this->infNFe->appendChild($this->infAdic);
        }

        //tag NFe/infNFe/exporta
        if (isset($this->exporta)) {
            $this->infNFe->appendChild($this->exporta);
        }

        //tag NFe/infNFe/compra
        if (isset($this->compra)) {
            $this->infNFe->appendChild($this->compra);
        }

        //tag NFe/infNFe/cana
        if (isset($this->cana) && isset($this->aForDia)) {
            foreach ($this->aForDia as $forDia) {
                $this->cana->appendChild($forDia);
            }
        }
        if (isset($this->cana) && isset($this->aDeduc)) {
            foreach ($this->aDeduc as $deduc) {
                $this->cana->appendChild($deduc);
            }
        }
        if (isset($this->cana)) {
            $this->infNFe->appendChild($this->cana);
        }
        
        //tag NFe/infNFe
        $this->NFe->appendChild($this->infNFe);
        //tag NFe
        $this->dom->appendChild($this->NFe);
        if (count($this->erros) > 0) {
            return json_encode($this->erros);
        }
        return $this->dom->saveXML();
    }
    
    //tag NFe DOMNode
    protected function zTagNFe()
    {
        if (!isset($this->NFe)) {
            $this->NFe = $this->dom->createElement("NFe");
            $this->NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
        }
    }
    
    //tag NFe/infNFe DOMNode
    public function taginfNFe($chave = '', $versao = '')
    {
        if (!ereg('[0-9]{44}', $chave)) {
            $this->errmsg = 'Passe a chave de 44 digitos para esse método. '.$chave;
            return false;
        }
        if (!ereg('^[0-9]{1}[.][0-9]{2}$', $versao)) {
            $this->errmsg = 'Versão incorreta de NFe. '.$chave;
            return false;
        }
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
     * tag NFe/infNFe/ide DOMNode
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
        $ide = $this->dom->createElement("ide");
        $this->zAddChild($ide, "cUF", $cUF, true, "Código da UF do emitente do Documento Fiscal");
        $this->zAddChild($ide, "cNF", $cNF, true, "Código Numérico que compõe a Chave de Acesso");
        $this->zAddChild($ide, "natOp", $natOp, true, "Descrição da Natureza da Operaçãoo");
        $this->zAddChild($ide, "indPag", $indPag, true, "Indicador da forma de pagamento");
        $this->zAddChild($ide, "mod", $mod, true, "Código do Modelo do Documento Fiscal");
        $this->zAddChild($ide, "serie", $serie, true, "Série do Documento Fiscal");
        $this->zAddChild($ide, "nNF", $nNF, true, "Número do Documento Fiscal");
        if ($this->versao > 2.00) {
            $this->zAddChild($ide, "dhEmi", $dhEmi, true, "Data e hora de emissão do Documento Fiscal");
        } else {
            $this->zAddChild($ide, "dEmi", $dhEmi, true, "Data de emissão do Documento Fiscal");
        }
        if ($mod == '55' && $dhSaiEnt != '') {
            if ($this->versao > 2.00) {
                $this->zAddChild(
                    $ide,
                    "dhSaiEnt",
                    $dhSaiEnt,
                    false,
                    "Data e hora de Saída ou da Entrada da Mercadoria/Produto"
                );
            } else {
                $this->zAddChild(
                    $ide,
                    "dSaiEnt",
                    $dhSaiEnt,
                    false,
                    "Data de Saída ou da Entrada da Mercadoria/Produto"
                );
            }
        }
        $this->zAddChild($ide, "tpNF", $tpNF, true, "Tipo de Operação");
        if ($this->versao > 2.00) {
            $this->zAddChild($ide, "idDest", $idDest, true, "Identificador de local de destino da operação");
        }
        $this->zAddChild($ide, "cMunFG", $cMunFG, true, "Código do Município de Ocorrência do Fato Gerador");
        $this->zAddChild($ide, "tpImp", $tpImp, true, "Formato de Impressão do DANFE");
        $this->zAddChild($ide, "tpEmis", $tpEmis, true, "Tipo de Emissão da NF-e");
        $this->zAddChild($ide, "cDV", $cDV, true, "Dígito Verificador da Chave de Acesso da NF-e");
        $this->zAddChild($ide, "tpAmb", $tpAmb, true, "Identificação do Ambiente");
        $this->zAddChild($ide, "finNFe", $finNFe, true, "Finalidade de emissão da NF-e");
        if ($this->versao > 2.00) {
            $this->zAddChild($ide, "indFinal", $indFinal, true, "Indica operação com Consumidor final");
        }
        if ($this->versao > 2.00) {
            $this->zAddChild(
                $ide,
                "indPres",
                $indPres,
                true,
                "Indicador de presença do comprador no estabelecimento comercial no momento da operação"
            );
        }
        $this->zAddChild($ide, "procEmi", $procEmi, true, "Processo de emiss�o da NF-e");
        $this->zAddChild($ide, "verProc", $verProc, true, "Vers�o do Processo de emiss�o da NF-e");
        if ($this->versao > 2.00) {
            if ($dhCont != '' && $xJust != '') {
                $this->zAddChild($ide, "dhCont", $dhCont, true, "Data e Hora da entrada em conting�ncia");
                $this->zAddChild($ide, "xJust", $xJust, true, "Justificativa da entrada em conting�ncia");
            }
        }
        $this->mod = $mod;
        $this->ide = $ide;
        return $ide;
    }
    
    /**
     * tagNFref
     * Informação de Documentos Fiscais referenciados BA01 pai B01
     * tag NFe/infNFe/ide/NFref
     */
    public function tagNFref()
    {
        if (!isset($this->NFref)) {
            $this->NFref = $this->dom->createElement("NFref");
        }
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
        if (! isset($this->NFref)) {
            $this->tagNFref();
        }
        $this->refNFe = $this->dom->createElement("refNFe", $refNFe);
        return $this->refNFe;
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
        if (! isset($this->NFref)) {
            $this->tagNFref();
        }
        $this->refNF = $this->dom->createElement("refNF");
        $this->zAddChild($this->refNF, "cUF", $cUF, true, "Código da UF do emitente");
        $this->zAddChild($this->refNF, "AAMM", $aamm, true, "Ano e Mês de emissão da NF-e");
        $this->zAddChild($this->refNF, "CNPJ", $cnpj, true, "CNPJ do emitente");
        $this->zAddChild($this->refNF, "mod", $mod, true, "Modelo do Documento Fiscal");
        $this->zAddChild($this->refNF, "serie", $serie, true, "Série do Documento Fiscal");
        $this->zAddChild($this->refNF, "nNF", $nNF, true, "Número do Documento Fiscal");
        return $this->refNF;
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
        if (! isset($this->NFref)) {
            $this->tagNFref();
        }
        $this->refNFP = $this->dom->createElement("refNFP");
        $this->zAddChild($this->refNFP, "cUF", $cUF, true, "Código da UF do emitente");
        $this->zAddChild($this->refNFP, "AAMM", $aamm, true, "AAMM da emissão da NF de produtor");
        $this->zAddChild($this->refNFP, "CNPJ", $cnpj, true, "Informar o CNPJ do emitente da NF de produtor");
        $this->zAddChild($this->refNFP, "CPF", $cpf, true, "Informar o CPF do emitente da NF de produtor");
        $this->zAddChild(
            $this->refNFP,
            "IE",
            $numIE,
            true,
            "Informar a IE do emitente da NF de Produtor ou o literal 'ISENTO'"
        );
        $this->zAddChild($this->refNFP, "mod", $mod, true, "Modelo do Documento Fiscal");
        $this->zAddChild($this->refNFP, "serie", $serie, true, "Série do Documento Fiscal");
        $this->zAddChild($this->refNFP, "nNF", $nNF, true, "Número do Documento Fiscal");
        return $this->refNFP;
    }
    
    /**
     * 
     * Chave de acesso do CT-e referenciada BA19 pai BA01
     * tag NFe/infNFe/ide/NFref/refCTe
     * @param type $refCTe
     * @return type
     */
    public function tagCTeref($refCTe = '')
    {
        if (! isset($this->NFref)) {
            $this->tagNFref();
        }
        $this->refCTe = $this->dom->createElement("refCTe", $refCTe);
        return $this->refCTe;
    }
    
    /**
     * tagECFref
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
        if (! isset($this->NFref)) {
            $this->tagNFref();
        }
        $this->refECF = $this->dom->createElement("refECF");
        $this->zAddChild($this->refECF, "mod", $mod, true, "Modelo do Documento Fiscal");
        $this->zAddChild($this->refECF, "nECF", $nECF, true, "Número de ordem sequencial do ECF");
        $this->zAddChild($this->refECF, "nCOO", $nCOO, true, "Número do Contador de Ordem de Operação - COO");
        return $this->refECF;
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
        $this->emit = $this->dom->createElement("emit");
        if ($cnpj != '') {
            $this->zAddChild($this->emit, "CNPJ", $cnpj, true, "CNPJ do emitente");
        } else {
            $this->zAddChild($this->emit, "CPF", $cpf, true, "CPF do remetente");
        }
        $this->zAddChild($this->emit, "xNome", $xNome, true, "Razão Social ou Nome do emitente");
        $this->zAddChild($this->emit, "xFant", $xFant, false, "Nome fantasia do emitente");
        $this->zAddChild($this->emit, "IE", $numIE, true, "Inscrição Estadual do emitente");
        $this->zAddChild($this->emit, "IEST", $numIEST, false, "IE do Substituto Tributário do emitente");
        $this->zAddChild(
            $this->emit,
            "IM",
            $numIM,
            false,
            "Inscrição Municipal do Prestador de Serviço do emitente"
        );
        $this->zAddChild($this->emit, "CNAE", $cnae, false, "CNAE fiscal do emitente");
        $this->zAddChild($this->emit, "CRT", $crt, true, "Código de Regime Tributário do emitente");
        return $this->emit;
    }
    
    //tag NFe/infNFe/emit/endEmit DOMNode
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
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->zAddChild($this->enderEmit, "xLgr", $xLgr, true, "Logradouro do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "nro", $nro, true, "Número do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xCpl", $xCpl, false, "Complemento do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xBairro", $xBairro, true, "Bairro do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "cMun", $cMun, true, "Código do município do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xMun", $xMun, true, "Nome do município do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "UF", $siglaUF, true, "Sigla da UF do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "CEP", $cep, true, "Código do CEP do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "cPais", $cPais, false, "Código do País do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "xPais", $xPais, false, "Nome do País do Endereço do emitente");
        $this->zAddChild($this->enderEmit, "fone", $fone, false, "Telefone do Endereço do emitente");
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
        $this->dest = $this->dom->createElement("dest");
        if ($cnpj != '') {
            $this->zAddChild($this->dest, "CNPJ", $cnpj, true, "CNPJ do destinatário");
        } elseif ($cpf != '') {
            $this->zAddChild($this->dest, "CPF", $cpf, true, "CPF do destinatário");
        } else {
            $this->zAddChild(
                $this->dest,
                "idEstrangeiro",
                $idEstrangeiro,
                true,
                "Identificação do destinatário no caso de comprador estrangeiro"
            );
        }
        $this->zAddChild($this->dest, "xNome", $xNome, true, "Razão Social ou nome do destinatário");
        if ($this->versao > 2.00) {
            if ($this->mod == '65') {
                $indIEDest = '9';
                $this->zAddChild($this->dest, "indIEDest", $indIEDest, true, "Indicador da IE do Destinatário");
            } else {
                $this->zAddChild($this->dest, "indIEDest", $indIEDest, true, "Indicador da IE do Destinatário");
            }
        }
        if ($this->versao > 2.00) {
            if ($indIEDest != '9' && $indIEDest != '2') {
                $this->zAddChild($this->dest, "IE", $numIE, true, "Inscrição Estadual do Destinatário");
            }
        } else {
            $this->zAddChild($this->dest, "IE", $numIE, false, "Inscrição Estadual do Destinatário");
        }
        $this->zAddChild($this->dest, "ISUF", $isUF, false, "Inscrição na SUFRAMA do destinatário");
        $this->zAddChild(
            $this->dest,
            "IM",
            $numIM,
            false,
            "Inscrição Municipal do Tomador do Serviço do destinatário"
        );
        $this->zAddChild($this->dest, "email", $email, false, "Email do destinatário");
        return $this->dest;
    }
    
    /**
     * tagenderDest
     * Endereço do Destinatário da NF-e E05 pai E01 
     * tag NFe/infNFe/dest/enderDest  (opcional para modelo 65)
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
        $this->enderDest = $this->dom->createElement("enderDest");
        $this->zAddChild($this->enderDest, "xLgr", $xLgr, true, "Logradouro do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "nro", $nro, true, "Número do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xCpl", $xCpl, false, "Complemento do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xBairro", $xBairro, true, "Bairro do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "cMun", $cMun, true, "Código do município do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xMun", $xMun, true, "Nome do município do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "UF", $siglaUF, true, "Sigla da UF do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "CEP", $cep, false, "Código do CEP do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "cPais", $cPais, false, "Código do País do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "xPais", $xPais, false, "Nome do País do Endereço do Destinatário");
        $this->zAddChild($this->enderDest, "fone", $fone, false, "Telefone do Endereço do Destinatário");
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
        $this->retirada = $this->dom->createElement("retirada");
        if ($cnpj != '') {
            $this->zAddChild($this->retirada, "CNPJ", $cnpj, true, "CNPJ do Cliente da Retirada");
        } else {
            $this->zAddChild($this->retirada, "CPF", $cpf, true, "CPF do Cliente da Retirada");
        }
        $this->zAddChild($this->retirada, "xLgr", $xLgr, true, "Logradouro do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "nro", $nro, true, "Número do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "xCpl", $xCpl, false, "Complemento do Endereco do Cliente da Retirada");
        $this->zAddChild($this->retirada, "xBairro", $xBairro, true, "Bairro do Endereco do Cliente da Retirada");
        $this->zAddChild(
            $this->retirada,
            "cMun",
            $cMun,
            true,
            "Código do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild(
            $this->retirada,
            "xMun",
            $xMun,
            true,
            "Nome do município do Endereco do Cliente da Retirada"
        );
        $this->zAddChild($this->retirada, "UF", $siglaUF, true, "Sigla da UF do Endereco do Cliente da Retirada");
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
        $this->entrega = $this->dom->createElement("entrega");
        if ($cnpj != '') {
            $this->zAddChild($this->entrega, "CNPJ", $cnpj, true, "CNPJ do Cliente da Entrega");
        } else {
            $this->zAddChild($this->entrega, "CPF", $cpf, true, "CPF do Cliente da Entrega");
        }
        $this->zAddChild($this->entrega, "xLgr", $xLgr, true, "Logradouro do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "nro", $nro, true, "Número do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "xCpl", $xCpl, false, "Complemento do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "xBairro", $xBairro, true, "Bairro do Endereco do Cliente da Entrega");
        $this->zAddChild(
            $this->entrega,
            "cMun",
            $cMun,
            true,
            "Código do município do Endereco do Cliente da Entrega"
        );
        $this->zAddChild($this->entrega, "xMun", $xMun, true, "Nome do município do Endereco do Cliente da Entrega");
        $this->zAddChild($this->entrega, "UF", $siglaUF, true, "Sigla da UF do Endereco do Cliente da Entrega");
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
        if ($this->versao > 2) {
            $autXML = $this->dom->createElement("autXML");
            if ($cnpj != '') {
                $this->zAddChild($autXML, "CNPJ", $cnpj, true, "CNPJ do Cliente Autorizado");
            } else {
                 $this->zAddChild($autXML, "CPF", $cpf, true, "CPF do Cliente Autorizado");
            }
            $this->aAutXML[]=$autXML;
            return $autXML;
        } else {
            return array();
        }
    }
    
    //tag NFe/infNFe/det array de DOMNodes
    public function tagdet()
    {
        if (isset($this->aProd)) {
            foreach ($this->aProd as $key => $prod) {
                $det = $this->dom->createElement("det");
                $nItem = $key;
                $det->setAttribute("nItem", $nItem);
                $det->appendChild($prod);
                $this->aDet[] = $det;
                $det = null;
            }
        }
    }

    //tag NFe/infNFe/det/imposto
    /**
     *  Insere dentro dentro das tags det os seus respectivos impostos
     * 
     */
    public function tagImp()
    {
        foreach ($this->aImposto as $key => $imp) {
            $nItem = $key;
            $imp->appendChild($this->aICMS[$nItem]);
            $imp->appendChild($this->aIPI[$nItem]);
            $imp->appendChild($this->aPIS[$nItem]);
            $imp->appendChild($this->aCOFINS[$nItem]);
            $imp->appendChild($this->aISSQN[$nItem]);
        }
        // COLOCA TAG imposto dentro do DET
        foreach ($this->aDet as $det) {
            $det->appendChild($this->aImposto[$det->getAttribute('nItem')]);
        }
    }

    //tag NFe/infNFe/det/prod array de DOMNodes
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
        $prod = $this->dom->createElement("prod");
        $this->zAddChild($prod, "cProd", $cProd, true, "C�digo do produto ou servi�o");
        $this->zAddChild($prod, "cEAN", $cEAN, true, "GTIN (Global Trade Item Number) do produto, antigo c�digo EAN ou c�digo de barras");
        $this->zAddChild($prod, "xProd", $xProd, true, "Descri��o do produto ou servi�o");
        $this->zAddChild($prod, "NCM", $NCM, true, "C�digo NCM com 8 d�gitos ou 2 d�gitos (g�nero)");
        if ($NVE != '') {
            $this->zAddChild($prod, "NVE", $NVE, false, "Codifica��o NVE - Nomenclatura de Valor Aduaneiro e Estat�stica");
        }
        if ($EXTIPI != '') {
            $this->zAddChild($prod, "EXTIPI", $EXTIPI, false, "Preencher de acordo com o c�digo EX da TIPI");
        }
        $this->zAddChild($prod, "CFOP", $CFOP, true, "C�digo Fiscal de Opera��es e Presta��es");
        $this->zAddChild($prod, "uCom", $uCom, true, "Unidade Comercial do produto");
        $this->zAddChild($prod, "qCom", $qCom, true, "Quantidade Comercial do produto");
        $this->zAddChild($prod, "vUnCom", $vUnCom, true, "Valor Unit�rio de Comercializa��o do produto");
        $this->zAddChild($prod, "vProd", $vProd, true, "Valor Total Bruto dos Produtos ou Servi�os");
        $this->zAddChild($prod, "cEANTrib", $cEANTrib, true, "GTIN (Global Trade Item Number) da unidade tribut�vel, antigo c�digo EAN ou c�digo de barras");
        $this->zAddChild($prod, "uTrib", $uTrib, true, "Unidade Tribut�vel do produto");
        $this->zAddChild($prod, "qTrib", $qTrib, true, "Quantidade Tribut�vel do produto");
        $this->zAddChild($prod, "vUnTrib", $vUnTrib, true, "Valor Unit�rio de tributa��o do produto");
        if ($vFrete != '') {
            $this->zAddChild($prod, "vFrete", $vFrete, false, "Valor Total do Frete");
        }
        if ($vSeg != '') {
            $this->zAddChild($prod, "vSeg", $vSeg, false, "Valor Total do Seguro");
        }
        if ($vDesc != '') {
            $this->zAddChild($prod, "vDesc", $vDesc, false, "Valor do Desconto");
        }
        if ($vOutro != '') {
            $this->zAddChild($prod, "vOutro", $vOutro, false, "Outras despesas acess�rias");
        }
        $this->zAddChild($prod, "indTot", $indTot, true, "Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)");
        if ($xPed != '') {
            $this->zAddChild($prod, "xPed", $xPed, false, "N�mero do Pedido de Compra");
        }
        if ($nItemPed != '') {
            $this->zAddChild($prod, "nItemPed", $nItemPed, false, "Item do Pedido de Compra");
        }
        if ($nFCI != '') {
            $this->zAddChild($prod, "nFCI", $nFCI, false, "N�mero de controle da FCI - Ficha de Conte�do de Importa��o");
        }
        if ($nRECOPI != '') {
            $this->zAddChild($prod, "nRECOPI", $nRECOPI);
        }
        $this->aProd[$nItem] = $prod;
        return $prod;
    }
    
    //tag NFe/infNFe/det/prod/DI array de DOMNodes
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
        $DI = $this->dom->createElement("DI");
        $this->zAddChild($DI, "nDI", $nDI, true, "N�mero do Documento de Importa��o (DI, DSI, DIRE, ...)");
        $this->zAddChild($DI, "dDI", $dDI, true, "Data de Registro do documento");
        $this->zAddChild($DI, "xLocDesemb", $xLocDesemb, true, "Local de desembara�o");
        $this->zAddChild($DI, "UFDesemb", $UFDesemb, true, "Sigla da UF onde ocorreu o Desembara�o Aduaneiro");
        $this->zAddChild($DI, "dDesemb", $dDesemb, true, "Data do Desembara�o Aduaneiro");
        $this->zAddChild($DI, "tpViaTransp", $tpViaTransp, true, "Via de transporte internacional informada na Declara��o de Importa��o (DI)");
        if ($vAFRMM != '') {
            $this->zAddChild($DI, "vAFRMM", $vAFRMM, false, "Valor da AFRMM - Adicional ao Frete para Renova��o da Marinha Mercante");
        }
        $this->zAddChild($DI, "tpIntermedio", $tpIntermedio, true, "Forma de importa��o quanto a intermedia��o");
        if ($CNPJ != '') {
            $this->zAddChild($DI, "CNPJ", $CNPJ, false, "CNPJ do adquirente ou do encomendante");
        }
        if ($UFTerceiro != '') {
            $this->zAddChild($DI, "UFTerceiro", $UFTerceiro, false, "Sigla da UF do adquirente ou do encomendante");
        }
        $this->zAddChild($DI, "cExportador", $cExportador, true, "C�digo do Exportador");
        if (isset($this->aAdi)) {
            foreach ($this->aAdi as $key => $nadi) {
                if ($key == $nItem) {
                    foreach ($nadi as $n => $jadi) {
                        if ($n == $nDI) {
                            $DI->appendChild($jadi[0]);
                        }
                    }
                }
            }
        }
        $this->aDI[$nItem][$nDI] = $DI;
        return $DI;
    }
    
    //tag NFe/infNFe/det/prod/DI/adi array de DOMNodes
    public function tagadi(
        $nItem = '',
        $nDI = '',
        $nAdicao = '',
        $nSeqAdicC = '',
        $cFabricante = '',
        $vDescDI = '',
        $nDraw = ''
    ) {
        $adi = $this->dom->createElement("adi");
        $this->zAddChild($adi, "nAdicao", $nAdicao, true, "Numero da Adi��o");
        $this->zAddChild($adi, "nSeqAdicC", $nSeqAdicC, true, "Numero sequencial do item dentro da Adi��o");
        $this->zAddChild($adi, "cFabricante", $cFabricante, true, "C�digo do fabricante estrangeiro");
        if ($vDescDI != '') {
            $this->zAddChild($adi, "vDescDI", $vDescDI, false, "Valor do desconto do item da DI � Adi��o");
        }
        if ($nDraw != '') {
            $this->zAddChild($adi, "nDraw", $nDraw, false, "N�mero do ato concess�rio de Drawback");
        }
        $this->aAdi[$nItem][$nDI][] = $adi;
        return $adi;
    }
    
    //tag NFe/infNFe/det/prod/detExport array de DOMNodes
    public function tagdetExport(
        $nItem = '',
        $nDraw = '',
        $exportInd = '',
        $nRE = '',
        $chNFe = '',
        $qExport = ''
    ) {
        if ($this->versao > 2.00) {
            $detExport = $this->dom->createElement("detExport");
            if ($nDraw != '') {
                $this->zAddChild($detExport, "nDraw", $nDraw, false, "N�mero do ato concess�rio de Drawback");
            }
            if ($exportInd != '') {
                $this->zAddChild($detExport, "exportInd", $exportInd, false, "Grupo sobre exporta��o indireta");
            }
            $this->zAddChild($detExport, "nRE", $nRE, true, "N�mero do Registro de Exporta��o");
            $this->zAddChild($detExport, "chNFe", $chNFe, true, "Chave de Acesso da NF-e recebida para exporta��o");
            $this->zAddChild($detExport, "qExport", $qExport, true, "Quantidade do item realmente exportado");
            $this->aDetExport[$nItem] = $detExport;
        }
    }
    
    //tag NFe/infNFe/det/prod/veicProd (opcional) array de DOMNodes
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
        $CMT = '',
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
        $veicProd = $this->dom->createElement("veicProd");
        $this->zAddChild($veicProd, "tpOp", $tpOp, true, "Tipo da opera��o do ve�culo");
        $this->zAddChild($veicProd, "chassi", $chassi, true, "Chassi do ve�culo");
        $this->zAddChild($veicProd, "cCor", $cCor, true, "Cor do ve�culo");
        $this->zAddChild($veicProd, "xCor", $xCor, true, "Descri��o da Cor do ve�culo");
        $this->zAddChild($veicProd, "pot", $pot, true, "Pot�ncia Motor (CV) do ve�culo");
        $this->zAddChild($veicProd, "cilin", $cilin, true, "Cilindradas do ve�culo");
        $this->zAddChild($veicProd, "pesoL", $pesoL, true, "Peso L�quido do ve�culo");
        $this->zAddChild($veicProd, "pesoB", $pesoB, true, "Peso Bruto do ve�culo");
        $this->zAddChild($veicProd, "nSerie", $nSerie, true, "Serial (s�rie) do ve�culo");
        $this->zAddChild($veicProd, "tpCpmb", $tpComb, true, "Tipo de combust�vel do ve�culo");
        $this->zAddChild($veicProd, "nMotor", $nMotor, true, "N�mero de Motor do ve�culo");
        $this->zAddChild($veicProd, "CMT", $CMT, true, "Capacidade M�xima de Tra��o do ve�culo");
        $this->zAddChild($veicProd, "dist", $dist, true, "Dist�ncia entre eixos do ve�culo");
        $this->zAddChild($veicProd, "anoMd", $anoMod, true, "Ano Modelo de Fabrica��o do ve�culo");
        $this->zAddChild($veicProd, "anoFab", $anoFab, true, "Ano de Fabrica��o do ve�culo");
        $this->zAddChild($veicProd, "tpPint", $tpPint, true, "Tipo de Pintura do ve�culo");
        $this->zAddChild($veicProd, "tpVeic", $tpVeic, true, "Tipo de Ve�culo");
        $this->zAddChild($veicProd, "espVeic", $espVeic, true, "Esp�cie de Ve�culo");
        $this->zAddChild($veicProd, "VIN", $VIN, true, "Condi��o do VIN do ve�culo");
        $this->zAddChild($veicProd, "condVeic", $condVeic, true, "Condi��o do Ve�culo");
        $this->zAddChild($veicProd, "cMod", $cMod, true, "C�digo Marca Modelo do ve�culo");
        $this->zAddChild($veicProd, "cCorDENATRAN", $cCorDENATRAN, true, "C�digo da Cor do ve�culo");
        $this->zAddChild($veicProd, "lota", $lota, true, "Capacidade m�xima de lota��o do ve�culo");
        $this->zAddChild($veicProd, "tpResp", $tpRest, true, "Restri��o do ve�culo");
        $this->aVeicProd[$nItem] = $veicProd;
        return $veicProd;
    }
    
    //tag NFe/infNFe/det/prod/med (opcional) array de DOMNodes
    public function tagmed(
        $nItem = '',
        $nLote = '',
        $qLote = '',
        $dFab = '',
        $dVal = '',
        $vPMC = ''
    ) {
        $med = $this->dom->createElement("med");
        $this->zAddChild($med, "nLote", $nLote, true, "N�mero do Lote de medicamentos ou de mat�rias-primas farmac�uticas");
        $this->zAddChild($med, "qLote", $qLote, true, "Quantidade de produto no Lote de medicamentos ou de mat�rias-primas farmac�uticas");
        $this->zAddChild($med, "dFab", $dFab, true, "Data de fabrica��o");
        $this->zAddChild($med, "dVal", $dVal, true, "Data de validade");
        $this->zAddChild($med, "vPMC", $vPMC, true, "Pre�o m�ximo consumidor");
        $this->aMed[$nItem] = $med;
        return $med;
    }
    
    //tag NFe/infNFe/det/prod/arma (opcional) array de DOMNodes
    public function tagarma(
        $nItem = '',
        $tpArma = '',
        $nSerie = '',
        $nCano = '',
        $descr = ''
    ) {
        $arma = $this->dom->createElement("arma");
        $this->zAddChild($arma, "tpArma", $tpArma, true, "Indicador do tipo de arma de fogo");
        $this->zAddChild($arma, "nSerie", $nSerie, true, "N�mero de s�rie da arma");
        $this->zAddChild($arma, "nCano", $nCano, true, "N�mero de s�rie do cano");
        $this->zAddChild($arma, "descr", $descr, true, "Descri��o completa da arma, compreendendo: calibre, marca, capacidade, tipo de funcionamento, comprimento e demais elementos que permitam a sua perfeita identifica��o.");
        $this->aArma[$nItem] = $arma;
        return $arma;
    }
    
    //tag NFe/infNFe/det/prod/comb (opcional) array de DOMNodes
    public function tagcomb(
        $nItem = '',
        $cProdANP = '',
        $pMixGN = '',
        $CODIF = '',
        $qTemp = '',
        $UFCons = '',
        $qBCProd = '',
        $vAliqProd = '',
        $vCIDE = ''
    ) {
        $comb = $this->dom->createElement("comb");
        $this->zAddChild($comb, "cProdANP", $cProdANP, true, "C�digo de produto da ANP");
        $this->zAddChild($comb, "pMixGN", $pMixGN, false, "Percentual de G�s Natural para o produto GLP (cProdANP=210203001)");
        $this->zAddChild($comb, "CODIF", $CODIF, false, "C�digo de autoriza��o / registro do CODIF");
        $this->zAddChild($comb, "qTemp", $qTemp, false, "Quantidade de combust�vel faturada � temperatura ambiente.");
        $this->zAddChild($comb, "UFCons", $UFCons, true, "Sigla da UF de consumo");
        if ($qBCProd != "") {
            $CIDE = $this->dom->createElement("CIDE");
            $this->zAddChild($CIDE, "qBCProd", $qBCProd, true, "BC da CIDE");
            $this->zAddChild($CIDE, "vAliqProd", $vAliqProd, true, "Valor da al�quota da CIDE");
            $this->zAddChild($CIDE, "vCIDE", $vCIDE, true, "Valor da CIDE");
            $comb->appendChild($CIDE);
        }
        $this->aComb[$nItem] = $comb;
        return $comb;
    }

    //tag NFe/infNFe/det/imposto array de DOMNodes
    public function tagimposto($nItem = '', $vTotTrib = '')
    {
        $imposto = $this->dom->createElement("imposto");
        $this->zAddChild($imposto, "vTotTrib", $vTotTrib, false, "Valor aproximado total de tributos federais, estaduais e municipais.");
        $this->aImposto[$nItem] = $imposto;
        return $imposto;
    }
    
    //tag det/imposto/ICMS array de DOMNodes
    public function tagICMS(
        $nItem = '',
        $orig = '',
        $CST = '',
        $modBC = '',
        $vBC = '',
        $pICMS = '',
        $vICMS = '',
        $modBCST = '',
        $pMVAST = '',
        $pRedBCST = '',
        $vBCST = '',
        $pICMSST = '',
        $vICMSST = '',
          $pDif = '',
        $vICMSDif = '',
        $vICMSOp = '',
        $pBCOp = '',
        $UFST = '',
        $pCredSN = '',
        $vCredICMSSN = ''
    ) {
        switch ($CST) {
            case '00':
                $ICMS = $this->dom->createElement("ICMS00");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 00");
                $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
                $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
                $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
                $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
                break;
            case '10':
                $ICMS = $this->dom->createElement("ICMS10");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 10");
                $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
                $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
                $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
                $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
                $this->zAddChild($ICMS, 'modBCST', $modBCST, true, "Modalidade de determina��o da BC do ICMS ST");
                if ($pMVAST != '') {
                    $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
                }
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
                }
                $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
                $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
                break;
            case '20':
                $ICMS = $this->dom->createElement("ICMS20");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 20");
                $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
                $this->zAddChild($ICMS, 'pRedBC', $pRedBCST, true, "Percentual da Redu��o de BC");
                $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
                $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
                $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
                break;
            case '30':
                $ICMS = $this->dom->createElement("ICMS30");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 30");
                $this->zAddChild($ICMS, 'modBCST', $modBC, true, "Modalidade de determina��o da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
                $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
                $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
                $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
                break;
            case '40':
                $ICMS = $this->dom->createElement("ICMS40");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 40");
               case '41':
                   $ICMS = $this->dom->createElement("ICMS41");
                   $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                   $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 41");
               case '50':
                $ICMS = $this->dom->createElement("ICMS50");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 50");
                break;
            case '51':
                $ICMS = $this->dom->createElement("ICMS51");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 51");
                if ($modBC != '') {
                    $this->zAddChild($ICMS, 'modBC', $modBC, false, "Modalidade de determina��o da BC do ICMS");
                }
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBC', $pRedBCST, false, "Percentual da Redu��o de BC");
                }
                if ($vBC != '') {
                    $this->zAddChild($ICMS, 'vBC', $vBC, false, "Valor da BC do ICMS");
                }
                if ($pICMS != '') {
                    $this->zAddChild($ICMS, 'pICMS', $pICMS, false, "Al�quota do imposto");
                }
                if ($vICMSOp != '') {
                    $this->zAddChild($ICMS, 'vICMSOp', $vICMSOp, false, "Valor do ICMS da Opera��o");
                }
                if ($pDif != '') {
                    $this->zAddChild($ICMS, 'pDif', $pDif, false, "Percentual do diferimento");
                }
                if ($vICMSDif != '') {
                    $this->zAddChild($ICMS, 'vICMSDif', $vICMSDif, false, "Valor do ICMS diferido");
                }
                if ($vICMS != '') {
                    $this->zAddChild($ICMS, 'vICMS', $vICMS, false, "Valor do ICMS");
                }
                break;
            case '60':
                $ICMS = $this->dom->createElement("ICMS60");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 60");
                break;
            case '70':
                $ICMS = $this->dom->createElement("ICMS70");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 70");
                $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
                $this->zAddChild($ICMS, 'pRedBC', $pRedBCST, true, "Percentual da Redu��o de BC");
                $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
                $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
                $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
                $this->zAddChild($ICMS, 'modBCST', $modBC, true, "Modalidade de determina��o da BC do ICMS ST");
                if ($pMVAST != '') {
                    $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
                }
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
                }
                $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
                $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
                break;
            case '90':
                $ICMS = $this->dom->createElement("ICMS90");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS = 90");
                $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
                $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBC', $pRedBCST, false, "Percentual da Redu��o de BC");
                }
                $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
                $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
                break;
//             default:
//                 $ICMS = $this->dom->createElement("ICMSPart");
//                 $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
//                 $this->zAddChild($ICMS, 'CST', $CST, true, "Tributa��o do ICMS");
//                 $this->zAddChild($ICMS, 'modBC', $modBC, true, "Modalidade de determina��o da BC do ICMS");
//                 $this->zAddChild($ICMS, 'vBC', $vBC, true, "Valor da BC do ICMS");
//                     $this->zAddChild($ICMS, 'pRedBC', $pRedBCST, false, "Percentual da Redu��o de BC");
//                 }
//                 $this->zAddChild($ICMS, 'pICMS', $pICMS, true, "Al�quota do imposto");
//                 $this->zAddChild($ICMS, 'vICMS', $vICMS, true, "Valor do ICMS");
//                 $this->zAddChild($ICMS, 'modBCST', $modBC, true, "Modalidade de determina��o da BC do ICMS ST");
//                 if ($pMVAST != '') {
//                     $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
//                 }
//                 if ($pRedBCST != '') {
//                     $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
//                 }
//                 $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
//                 $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
//                 $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
//                 $this->zAddChild($ICMS, 'pBCOp', $pBCOp, true, "Percentual da BC opera��o pr�pria");
//                 $this->zAddChild($ICMS, 'UFST', $UFST, true, "UF para qual � devido o ICMS ST");
//                 break;
            case '101':
                $ICMS = $this->dom->createElement("ICMSSN101");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                $this->zAddChild($ICMS, 'pCredSN', $pCredSN, true, "Al�quota aplic�vel de c�lculo do cr�dito (Simples Nacional).");
                $this->zAddChild($ICMS, 'vCredICMSSN', $vCredICMSSN, true, "Valor cr�dito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)");
                break;
            case '102':
                $ICMS = $this->dom->createElement("ICMSSN102");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                break;
            case '201':
                $ICMS = $this->dom->createElement("ICMSSN201");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                $this->zAddChild($ICMS, 'modBCST', $modBCST, true, "Al�quota aplic�vel de c�lculo do cr�dito (Simples Nacional).");
                if ($pMVAST != '') {
                    $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
                }
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
                }
                $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
                $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
                $this->zAddChild($ICMS, 'pCredSN', $pCredSN, true, "Al�quota aplic�vel de c�lculo do cr�dito (Simples Nacional).");
                $this->zAddChild($ICMS, 'vCredICMSSN', $vCredICMSSN, true, "Valor cr�dito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)");
                break;
            case '202':
                $ICMS = $this->dom->createElement("ICMSSN202");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                $this->zAddChild($ICMS, 'modBCST', $modBCST, true, "Al�quota aplic�vel de c�lculo do cr�dito (Simples Nacional).");
                if ($pMVAST != '') {
                    $this->zAddChild($ICMS, 'pMVAST', $pMVAST, false, "Percentual da margem de valor Adicionado do ICMS ST");
                }
                if ($pRedBCST != '') {
                    $this->zAddChild($ICMS, 'pRedBCST', $pRedBCST, false, "Percentual da Redu��o de BC do ICMS ST");
                }
                $this->zAddChild($ICMS, 'vBCST', $vBCST, true, "Valor da BC do ICMS ST");
                $this->zAddChild($ICMS, 'pICMSST', $pICMSST, true, "Al�quota do imposto do ICMS ST");
                $this->zAddChild($ICMS, 'vICMSST', $vICMSST, true, "Valor do ICMS ST");
                break;
            case '500':
                $ICMS = $this->dom->createElement("ICMSSN500");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                break;
            case '900':
                $ICMS = $this->dom->createElement("ICMSSN900");
                $this->zAddChild($ICMS, 'orig', $orig, true, "Origem da mercadoria");
                $this->zAddChild($ICMS, 'CSOSN', $CST, true, "C�digo de Situa��o da Opera��o � Simples Nacional");
                break;
        }
        $tagIcms = $this->dom->createElement('ICMS');
        $tagIcms->appendChild($ICMS);
        $this->aICMS[$nItem] = $tagIcms;
            
        return $tagIcms;
    }
    
    //tag det/imposto/ISSQN (opcional) array de DOMNodes
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
        $issqn = $this->dom->createElement('ISSQN');
        $this->zAddChild($issqn, 'vBC', $vBC, true, "Valor da Base de C�lculo do ISSQN");
        $this->zAddChild($issqn, 'vAliq', $vAliq, true, "Al�quota do ISSQN");
        $this->zAddChild($issqn, 'vISSQN', $vISSQN, true, "Valor do ISSQN");
        $this->zAddChild($issqn, 'cMunFG', $cMunFG, true, "C�digo do munic�pio de ocorr�ncia do fato gerador do ISSQN");
        $this->zAddChild($issqn, 'cListServ', $cListServ, true, "Item da Lista de Servi�os");
        if ($vDeducao != '') {
            $this->zAddChild($issqn, 'vDeducao', $vDeducao, false, "Valor dedu��o para redu��o da Base de C�lculo");
        }
        if ($vOutro != '') {
            $this->zAddChild($issqn, 'vOutro', $vOutro, false, "Valor outras reten��es");
        }
        if ($vDescIncond != '') {
            $this->zAddChild($issqn, 'vDescIncond', $vDescIncond, false, "Valor desconto incondicionado");
        }
        if ($vDescCond != '') {
            $this->zAddChild($issqn, 'vDescCond', $vDescCond, false, "Valor desconto condicionado");
        }
        if ($vISSRet != '') {
            $this->zAddChild($issqn, 'vISSRet', $vISSRet, false, "Valor reten��o ISS");
        }
        $this->zAddChild($issqn, 'indISS', $indISS, true, "Indicador da exigibilidade do ISS");
        if ($cServico != '') {
            $this->zAddChild($issqn, 'cServico', $cServico, false, "C�digo do servi�o prestado dentro do munic�pio");
        }
        if ($cMun != '') {
            $this->zAddChild($issqn, 'cMun', $cMun, false, "C�digo do Munic�pio de incid�ncia do imposto");
        }
        if ($cPais != '') {
            $this->zAddChild($issqn, 'cPais', $cPais, false, "C�digo do Pa�s onde o servi�o foi prestado");
        }
        if ($nProcesso != '') {
            $this->zAddChild($issqn, 'nProcesso', $nProcesso, false, "N�mero do processo judicial ou administrativo de suspens�o da exigibilidade");
        }
        $this->zAddChild($issqn, 'indIncentivo', $indIncentivo, true, "Indicador de incentivo Fiscal");
        $this->aICMS[$nItem] = $issqn;
        
        return $issqn;
    }

    //tag det/imposto/IPI (opcional) array de DOMNodes
    public function tagIPI(
        $nItem = '',
        $clEnq = '',
        $CNPJProd = '',
        $cSelo = '',
        $qSelo = '',
        $cEnq = '',
        $CSTTrib = '',
        $CSTInt = '',
        $vBC = '',
        $vDespAdu = '',
        $vII = '',
        $vIOF = ''
    ) {
        $ipi = $this->dom->createElement('IPI');
        if ($clEnq != '') {
            $this->zAddChild($ipi, "clEnq", $clEnq, false, "Classe de enquadramento do IPI para Cigarros e Bebidas");
        }
        if ($CNPJProd != '') {
            $this->zAddChild($ipi, "CNPJProd", $CNPJProd, false, "CNPJ do produtor da mercadoria, quando diferente do emitente. Somente para os casos de exporta��o direta ou indireta.");
        }
        if ($cSelo != '') {
            $this->zAddChild($ipi, "cSelo", $cSelo, false, "C�digo do selo de controle IPI");
        }
        if ($qSelo != '') {
            $this->zAddChild($ipi, "qSelo", $qSelo, false, "Quantidade de selo de controle");
        }
        $this->zAddChild($ipi, "cEnq", $cEnq, true, "C�digo de Enquadramento Legal do IPI");
        if ($CSTTrib != '') {
            $ipiTrib = $this->dom->createElement('IPITrib');
            $this->zAddChild($ipiTrib, "CST", $CSTTrib, true, "C�digo da situa��o tribut�ria do IPI");
            $ipi->appendChild($ipiTrib);
        }
        if ($CSTInt != '') {
            $ipINT = $this->dom->createElement('IPINT');
            $this->zAddChild($ipINT, "CST", $CSTTrib, true, "C�digo da situa��o tribut�ria do IPI");
            $ipi->appendChild($ipINT);
        }
        if ($vBC != '') {
            $ii = $this->dom->createElement('II');
            $this->zAddChild($ii, "vBC", $vBC, true, "Valor BC do Imposto de Importa��o");
            $this->zAddChild($ii, "vDespAdu", $vDespAdu, true, "Valor despesas aduaneiras");
            $this->zAddChild($ii, "vII", $vII, true, "Valor Imposto de Importa��o");
            $this->zAddChild($ii, "vIOF", $vIOF, true, "Valor Imposto sobre Opera��es Financeiras");
            $ipi->appendChild($ii);
        }
        
        $this->aIPI[$nItem] = $ipi;
        
        return $ipi;
    }
    
    //tag det/imposto/PIS array de DOMNodes
    public function tagPIS(
        $nItem = '',
        $CST = '',
        $vBC = '',
        $pPIS = '',
        $vPIS = '',
        $qBCProd = '',
        $vAliqProd = ''
    ) {
        switch ($CST) {
            case '01':
            case '02':
                $pisItem = $this->dom->createElement('PISAliq');
                $this->zAddChild($pisItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria do PIS");
                $this->zAddChild($pisItem, 'vBC', $vBC, true, "Valor da Base de C�lculo do PIS");
                $this->zAddChild($pisItem, 'pPIS', $pPIS, true, "Al�quota do PIS (em percentual)");
                $this->zAddChild($pisItem, 'vPIS', $vPIS, true, "Valor do PIS");
                break;
            case '03':
                $pisItem = $this->dom->createElement('PISQtde');
                $this->zAddChild($pisItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria do PIS");
                $this->zAddChild($pisItem, 'qBCProd', $qBCProd, true, "Quantidade Vendida");
                $this->zAddChild($pisItem, 'vAliqProd', $vAliqProd, true, "Al�quota do PIS (em reais)");
                $this->zAddChild($pisItem, 'vPIS', $vPIS, true, "Valor do PIS");
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $pisItem = $this->dom->createElement('PISNT');
                $this->zAddChild($pisItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria do PIS");
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
                $this->zAddChild($pisItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria do PIS");
                break;
        }
        
        $pis = $this->dom->createElement('PIS');
        $pis->appendChild($pisItem);
        $this->aPIS[$nItem] = $pis;
        
        return $pis;
    }
    
    //tag det/imposto/PISST (opcional) array de DOMNodes
    public function tagPISST()
    {
        
    }
    
    //tag det/imposto/COFINS array de DOMNodes
    public function tagCOFINS(
        $nItem = '',
        $CST = '',
        $vBC = '',
        $pCOFINS = '',
        $vCOFINS = '',
        $qBCProd = '',
        $vAliqProd = ''
    ) {
        switch ($CST) {
            case '01':
            case '02':
                $confinsItem = $this->dom->createElement('COFINSAliq');
                $this->zAddChild($confinsItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria da COFINS");
                $this->zAddChild($confinsItem, 'vBC', $vBC, true, "Valor da Base de C�lculo da COFINS");
                $this->zAddChild($confinsItem, 'pCOFINS', $pCOFINS, true, "Al�quota da COFINS (em percentual)");
                $this->zAddChild($confinsItem, 'vCOFINS', $vCOFINS, true, "Valor da COFINS");
                break;
            case '03':
                $confinsItem = $this->dom->createElement('COFINSQtde');
                $this->zAddChild($confinsItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria da COFINS");
                $this->zAddChild($confinsItem, 'qBCProd', $qBCProd, true, "Quantidade Vendida");
                $this->zAddChild($confinsItem, 'vAliqProd', $vAliqProd, true, "Al�quota do COFINS (em reais)");
                $this->zAddChild($confinsItem, 'vCOFINS', $vCOFINS, true, "Valor do COFINS");
                break;
            case '04':
            case '05':
            case '06':
            case '07':
            case '08':
            case '09':
                $confinsItem = $this->dom->createElement('COFINSNT');
                $this->zAddChild($confinsItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria da COFINS");
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
                $confinsItem = $this->dom->createElement('COFINSOutr');
                $this->zAddChild($confinsItem, 'CST', $CST, true, "C�digo de Situa��o Tribut�ria da COFINS");
                break;
        }
        
        $confins = $this->dom->createElement('COFINS');
        $confins->appendChild($confinsItem);
        $this->aCOFINS[$nItem] = $confins;
        
        return $confins;
    }
    
    //tag det/imposto/COFINSST (opcional) array de DOMNodes
    public function tagCOFINSST()
    {
        
    }
    
    /**
     * tagimpostoDevol
     * Informação do Imposto devolvido U50 pai H01
     * tag NFe/infNFe/det/impostoDevol (opcional)
     * @param string $pDevol
     * @param string $vIPIDevol
     * @return DOMElement
     */
    public function tagimpostoDevol($pDevol = '', $vIPIDevol = '')
    {
        $this->impostoDevol = $this->dom->createElement("impostoDevol");
        $this->zAddChild(
            $this->impostoDevol,
            "pDevol",
            $pDevol,
            true,
            "Percentual da mercadoria devolvida"
        );
        if ($vIPIDevol != '') {
            $parent = $this->dom->createElement("IPI");
            $this->zAddChild(
                $parent,
                "vIPIDevol",
                $vIPIDevol,
                true,
                "Valor do IPI devolvido"
            );
            $this->impostoDevol->appendChild($parent);
        }
        return $this->impostoDevol;
    }
    
    /**
     * tagttotal
     * Grupo Totais da NF-e W01 pai A01
     * tag NFe/infNFe/total
     */
    public function tagtotal()
    {
        if (!isset($this->total)) {
            $this->total = $this->dom->createElement("total");
        }
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
        if (! isset($this->total)) {
            $this->tagtotal();
        }
        $this->ICMSTot = $this->dom->createElement("ICMSTot");
        $this->zAddChild($this->ICMSTot, "vBC", $vBC, true, "Base de Cálculo do ICMS");
        $this->zAddChild($this->ICMSTot, "vICMS", $vICMS, true, "Valor Total do ICMS");
        if ($this->versao > 2.00) {
            $this->zAddChild($this->ICMSTot, "vICMSDeson", $vICMSDeson, true, "Valor Total do ICMS desonerado");
        }
        $this->zAddChild($this->ICMSTot, "vBCST", $vBCST, true, "Base de Cálculo do ICMS ST");
        $this->zAddChild($this->ICMSTot, "vST", $vST, true, "Valor Total do ICMS ST");
        $this->zAddChild($this->ICMSTot, "vProd", $vProd, true, "Valor Total dos produtos e servi�os");
        $this->zAddChild($this->ICMSTot, "vFrete", $vFrete, true, "Valor Total do Frete");
        $this->zAddChild($this->ICMSTot, "vSeg", $vSeg, true, "Valor Total do Seguro");
        $this->zAddChild($this->ICMSTot, "vDesc", $vDesc, true, "Valor Total do Desconto");
        $this->zAddChild($this->ICMSTot, "vII", $vII, true, "Valor Total do II");
        $this->zAddChild($this->ICMSTot, "vIPI", $vIPI, true, "Valor Total do IPI");
        $this->zAddChild($this->ICMSTot, "vPIS", $vPIS, true, "Valor do PIS");
        $this->zAddChild($this->ICMSTot, "vCOFINS", $vCOFINS, true, "Valor da COFINS");
        $this->zAddChild($this->ICMSTot, "vOutro", $vOutro, true, "Outras Despesas acessórias");
        $this->zAddChild($this->ICMSTot, "vNF", $vNF, true, "Valor Total da NF-e");
        $this->zAddChild(
            $this->ICMSTot,
            "vTotTrib",
            $vTotTrib,
            true,
            "Valor aproximado total de tributos federais, estaduais e municipais."
        );
        return $this->ICMSTot;
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
        $cRegTrib = '',
        $vOutro = '',
        $vDescIncond = '',
        $vDescCond = '',
        $vISSRet = '',
        $cRegTrib = ''
    ) {
        $this->ISSQNTot = $this->dom->createElement("ISSQNtot");
        $this->zAddChild(
            $this->ISSQNTot,
            "vServ",
            $vServ,
            false,
            "Valor total dos Serviços sob não incidência ou não tributados pelo ICMS"
        );
        $this->zAddChild(
            $this->ISSQNTot,
            "vBC",
            $vBC,
            false,
            "Valor total Base de Cálculo do ISS"
        );
        $this->zAddChild(
            $this->ISSQNTot,
            "vISS",
            $vISS,
            false,
            "Valor total do ISS"
        );
        $this->zAddChild(
            $this->ISSQNTot,
            "vPIS",
            $vPIS,
            false,
            "Valor total do PIS sobre serviços"
        );
        if ($this->versao > 2.00) {
            $this->zAddChild(
                $this->ISSQNTot,
                "vCOFINS",
                $vCOFINS,
                false,
                "Valor total da COFINS sobre serviços"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "dCompet",
                $dCompet,
                true,
                "Data da prestação do serviço"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "vDeducao",
                $vDeducao,
                false,
                "Valor total dedução para redução da Base de Cálculo"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "vOutro",
                $vOutro,
                false,
                "Valor total outras retenções"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "vDescIncond",
                $vDescIncond,
                false,
                "Valor total desconto incondicionado"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "vDescCond",
                $vDescCond,
                false,
                "Valor total desconto condicionado"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "vISSRet",
                $vISSRet,
                false,
                "Valor total retenção ISS"
            );
            $this->zAddChild(
                $this->ISSQNTot,
                "cRegTrib",
                $cRegTrib,
                false,
                "Código do Regime Especial de Tributação"
            );
        }
        return $this->ISSQNTot;
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
        $this->retTrib = $this->dom->createElement("retTrib");
        $this->zAddChild(
            $this->retTrib,
            "vRetPIS",
            $vRetPIS,
            false,
            "Valor Retido de PIS"
        );
        $this->zAddChild(
            $this->retTrib,
            "vRetCOFINS",
            $vRetCOFINS,
            false,
            "Valor Retido de COFINS"
        );
        $this->zAddChild(
            $this->retTrib,
            "vRetCSLL",
            $vRetCSLL,
            false,
            "Valor Retido de CSLL"
        );
        $this->zAddChild(
            $this->retTrib,
            "vBCIRRF",
            $vBCIRRF,
            false,
            "Base de Cálculo do IRRF"
        );
        $this->zAddChild(
            $this->retTrib,
            "vIRRF",
            $vIRRF,
            false,
            "Valor Retido do IRRF"
        );
        $this->zAddChild(
            $this->retTrib,
            "vBCRetPrev",
            $vBCRetPrev,
            false,
            "Base de Cálculo da Retenção da Previdência Social"
        );
        $this->zAddChild(
            $this->retTrib,
            "vRetPrev",
            $vRetPrev,
            false,
            "Valor da Retenção da Previdência Social"
        );
        return $this->retTrib;
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
        $this->transporta = $this->dom->createElement("transporta");
        $this->zAddChild($this->transporta, "CNPJ", $numCNPJ, false, "CNPJ do Transportador");
        $this->zAddChild($this->transporta, "CPF", $numCPF, false, "CPF do Transportador");
        $this->zAddChild($this->transporta, "xNome", $xNome, false, "Razão Social ou nome do Transportador");
        $this->zAddChild($this->transporta, "IE", $numIE, false, "Inscrição Estadual do Transportador");
        $this->zAddChild($this->transporta, "xEnder", $xEnder, false, "Endereço Completo do Transportador");
        $this->zAddChild($this->transporta, "xMun", $xMun, false, "Nome do município do Transportador");
        $this->zAddChild($this->transporta, "UF", $siglaUF, false, "Sigla da UF do Transportador");
        return $this->transporta;
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
        $this->veicTransp = $this->dom->createElement("veicTransp");
        $this->zAddChild($this->veicTransp, "placa", $placa, true, "Placa do Veículo");
        $this->zAddChild($this->veicTransp, "UF", $siglaUF, true, "Sigla da UF do Veículo");
        $this->zAddChild($this->veicTransp, "RNTC", $rntc, false, "Registro Nacional de Transportador de Carga (ANTT) do Veículo");
        return $this->veicTransp;
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
        $this->retTransp = $this->dom->createElement("retTransp");
        $this->zAddChild($this->retTransp, "vServ", $vServ, true, "Valor do Serviço");
        $this->zAddChild($this->retTransp, "vBCRet", $vBCRet, true, "BC da Retenção do ICMS");
        $this->zAddChild($this->retTransp, "pICMSRet", $pICMSRet, true, "Alíquota da Retenção");
        $this->zAddChild($this->retTransp, "vICMSRet", $vICMSRet, true, "Valor do ICMS Retido");
        $this->zAddChild($this->retTransp, "CFOP", $cfop, true, "CFOP");
        $this->zAddChild(
            $this->retTransp,
            "cMunFG",
            $cMunFG,
            true,
            "Código do município de ocorrência do fato gerador do ICMS do transporte"
        );
        return $this->retTransp;
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
        if (! empty($aLacres)) {
            //tag transp/vol/lacres (opcional)
            foreach ($aLacres as $nLacre) {
                $lacre = $this->zTaglacres($nLacre);
                $vol->appendChild($lacre);
                $lacre - null;
            }
        }
        $this->aVol[] = $vol;
        return $vol;
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
    public function tagcobr()
    {
        if (!isset($this->cobr)) {
            $this->cobr = $this->dom->createElement("cobr");
        }
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
        if (!isset($this->cobr)) {
            $this->tagcobr();
        }
        $this->fat = $this->dom->createElement("fat");
        $this->zAddChild($this->fat, "nFat", $nFat, false, "Número da Fatura");
        $this->zAddChild($this->fat, "vOrig", $vOrig, false, "Valor Original da Fatura");
        $this->zAddChild($this->fat, "vDesc", $vDesc, false, "Valor do desconto");
        $this->zAddChild($this->fat, "vLiq", $vLiq, false, "Valor Líquido da Fatura");
        return $this->fat;
    }
    
    /**
     * tagdup
     * Grupo Duplicata Y07 pai Y02
     * tag NFe/infNFe/cobr/fat/dup (opcional)
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
        if (!isset($this->cobr)) {
            $this->tagcobr();
        }
        if (!isset($this->fat)) {
            $this->tagfat();
        }
        $dup = $this->dom->createElement("dup");
        $this->zAddChild($dup, "nDup", $nDup, false, "Número da Duplicata");
        $this->zAddChild($dup, "dVenc", $dVenc, false, "Data de vencimento");
        $this->zAddChild($dup, "vDup", $vDup, true, "Valor da duplicata");
        $this->aDup[] = $dup;
        return $dup;
    }
    
    /**
     * tagpag
     * Grupo de Formas de Pagamento YA01 pai A01
     * tag NFe/infNFe/pag (opcional)
     * @param string $tPag
     * @param string $vPag
     * @return DOMElement
     */
    public function tagpag(
        $tPag = '',
        $vPag = ''
    ) {
        $this->pag = $this->dom->createElement("pag");
        if ($this->mod == '65') {
            $this->zAddChild($this->pag, "tPag", $tPag, true, "Forma de pagamento");
            $this->zAddChild($this->pag, "vPag", $vPag, true, "Valor do Pagamento");
        }
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
        $this->card = $this->dom->createElement("card");
        if ($this->mod == '65' && $tBand != '') {
            $this->zAddChild(
                $this->card,
                "CNPJ",
                $cnpj,
                true,
                "CNPJ da Credenciadora de cartão de crédito e/ou débito"
            );
            $this->zAddChild(
                $this->card,
                "tBand",
                $tBand,
                true,
                "Bandeira da operadora de cartão de crédito e/ou débito"
            );
            $this->zAddChild(
                $this->card,
                "cAut",
                $cAut,
                true,
                "Número de autorização da operação cartão de crédito e/ou débito"
            );
        }
        return $this->card;
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
        if (!isset($this->infAdic)) {
            $this->infAdic = $this->dom->createElement("infAdic");
        }
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
     * @param string $xCampo
     * @param string $xTexto
     * @return DOMElement
     */
    public function tagobsCont(
        $xCampo = '',
        $xTexto = ''
    ) {
        $obsCont = $this->dom->createElement("obsCont");
        $obsCont->setAttribute("xCampo", $xCampo);
        $this->zAddChild($obsCont, "xTexto", $xTexto, true, "Conteúdo do campo");
        $this->aObsCont[]=$obsCont;
        return $obsCont;
    }
    
    /**
     * tagobsFisco
     * Grupo Campo de uso livre do Fisco Z07 pai Z01
     * tag NFe/infNFe/infAdic/obsFisco (opcional)
     * @param string $xCampo
     * @param string $xTexto
     * @return DOMElement
     */
    public function tagobsFisco(
        $xCampo = '',
        $xTexto = ''
    ) {
        $obsFisco = $this->dom->createElement("obsFisco");
        $obsFisco->setAttribute("xCampo", $xCampo);
        $this->zAddChild($obsFisco, "xTexto", $xTexto, true, "Conteúdo do campo");
        $this->aObsFisco[]=$obsFisco;
        return $obsFisco;
    }
    
    /**
     * tagprocRef
     * Grupo Processo referenciado Z10 pai Z01
     * tag NFe/infNFe/procRef (opcional)
     * @param string $nProc
     * @param string $indProc
     * @return DOMElement
     */
    public function tagprocRef(
        $nProc = '',
        $indProc = ''
    ) {
        $procRef = $this->dom->createElement("procRef");
        $this->zAddChild($procRef, "nProc", $nProc, true, "Identificador do processo ou ato concessório");
        $this->zAddChild($procRef, "indProc", $indProc, true, "Indicador da origem do processo");
        $this->aProcRef[]=$procRef;
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
        return $deduc;
    }
    
    public function validChave($chave)
    {
        
    }
    
    /**
     * zAddChild
     * Adiciona um elemento ao node xml passado como referencia
     * 
     * @param DOMElement $parent
     * @param string $name
     * @param string $content
     * @param boolean $obrigatorio
     * @param string $descricao
     */
    private function zAddChild(&$parent, $name, $content, $obrigatorio = false, $descricao = "")
    {
        if ($obrigatorio && $content === "") {
            $this->erros[] = array(
                "tag" => $name,
                "desc" => $descricao,
                "erro" => "Preenchimento Obrigatório!"
            );
        }
        if (! empty($content)) {
            $temp = $this->dom->createElement($name, $content);
            $parent->appendChild($temp);
        }
    }
}
