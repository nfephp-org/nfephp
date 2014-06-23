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
 *          NT2013.006 versão 1.00 Agosto de 2013
 * 
 * @package     NFePHP
 * @name        MakeNFePHP
 * @version     1.0.0
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright   2009-2014 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * 
 *        CONTRIBUIDORES (em ordem alfabetica):
 * 
 *              Elias Müller <elias at oxigennio dot com dot br>
 *              Marcos Balbi
 * 
 */

//namespace SpedPHP\NFe;

//use \DOMDocument;
//use \DOMElement;

class MakeNFe
{

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
    public $ECFref; //DOMNode
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
    public $reTrib; //DOMNode
    
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
        // 28 - tag ICMSTot       Obrigatório               Obrigatório
        // 29 - tag ISSQNTot      Opcional (se houver)      Opcional (se houver)
        // 30 - tag retTrib       Opcional (se houver)      Opcional (se houver)
        // 31 - tag transp        Obrigatório               Obrigatório
        // 32 - tag transporta    Opcional (se houver)      Opcional (se houver)
        // 33 - tag retTransp     Opcional (se houver)      Opcional (se houver)
        // 34 - tag veicTransp    Opcional (se houver)      Opcional (se houver)
        // 35 - tag reboque       Opcional (se houver)      Opcional (se houver)
        // 36 - tag lacres        Opcional (se houver)      Opcional (se houver)
        // 37 - tag vol           Opcional (se houver)      Opcional (se houver)
        // 38 - tag fat           Opcional (se houver)      Opcional (se houver)
        // 39 - tag dup           Opcional (se houver)      Opcional (se houver)
        // 40 - tag pag           Opcional (se houver)      Obrigatorio
        // 41 - tag card          Não aplicável             Opcional (se houver)
        // 42 - tag infAdic       Opcional (se houver)      Opcional (se houver)
        // 43 - tag obsCont       Opcional (se houver)      Opcional (se houver)
        // 44 - tag obsFisco      Opcional (se houver)      Opcional (se houver)
        // 45 - tag procRef       Opcional (se houver)      Opcional (se houver)
        // 46 - tag exporta       Opcional (se houver)      Opcional (se houver)
        // 47 - tag compra        Opcional (se houver)      Opcional (se houver)
        // 48 - tag cana          Opcional (se houver)      Não aplicavel
        // 49 - tag forDia        Opcional (se houver)      Não aplicavel
        // 50 - tag deduc         Opcional (se houver)      Não aplicavel

        //tag NFe
        $this->tagNFe();

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
        if (isset($this->ECFref)) {
            $this->tagNFref();
            $this->NFref->appendChild($this->ECFref);
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
        if (isset($this->reTrib)) {
            $this->tagtotal();
            $this->total->appendChild($this->reTrib);
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
        return $this->dom->saveXML();
    }
    
    //tag NFe DOMNode
    protected function tagNFe()
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
    
    //tag NFe/infNFe/ide DOMNode
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
        //cUF
        $this->addChild($ide, "cUF", $cUF);
        //cNF
        $this->addChild($ide, "cNF", $cNF);
        //natOp
        $this->addChild($ide, "natOp", $natOp);
        //indPag
        $this->addChild($ide, "indPag", $indPag);
        //mod
        $this->addChild($ide, "mod", $mod);
        //serie
        $this->addChild($ide, "serie", $serie);
        //nNF
        $this->addChild($ide, "nNF", $nNF);
        //dhEmi nome e formato diferente a partir da versao 3.00
        if ($this->versao > 2.00) {
            $this->addChild($ide, "dhEmi", $dhEmi);
        } else {
            $this->addChild($ide, "dEmi", $dhEmi);
        }
        //$dhSaiEnt (opcional e somente para modelo 55)
        if ($mod == '55' && $dhSaiEnt != '') {
            if ($this->versao > 2.00) {
                $this->addChild($ide, "dhSaiEnt", $dhSaiEnt);
            } else {
                $this->addChild($ide, "dSaiEnt", $dhSaiEnt);
            }
        }
        //tpNF
        $this->addChild($ide, "tpNF", $tpNF);
        //idDest essa tag existe somente a partir da versão 3.00
        if ($this->versao > 2.00) {
            $this->addChild($ide, "idDest", $idDest);
        }
        //cMunFG
        $this->addChild($ide, "cMunFG", $cMunFG);
        //tpImp
        $this->addChild($ide, "tpImp", $tpImp);
        //tpEmis
        $this->addChild($ide, "tpEmis", $tpEmis);
        //cDV
        $this->addChild($ide, "cDV", $cDV);
        //tpAmb
        $this->addChild($ide, "tpAmb", $tpAmb);
        //finNFe
        $this->addChild($ide, "finNFe", $finNFe);
        //indFinal
        if ($this->versao > 2.00) {
            $this->addChild($ide, "indFinal", $indFinal);
        }
        //indPres
        if ($this->versao > 2.00) {
            $this->addChild($ide, "indPres", $indPres);
        }
        //procEmi
        $this->addChild($ide, "procEmi", $procEmi);
        //verProc
        $this->addChild($ide, "verProc", $verProc);
        if ($this->versao > 2.00) {
            //dhCont
            if ($dhCont != '' && $xJust != '') {
                $this->addChild($ide, "dhCont", $dhCont);
                //xJust
                $this->addChild($ide, "xJust", $xJust);
            }
        }
        $this->mod = $mod;
        $this->ide = $ide;
        return $ide;
    }

    //tag NFe/infNFe/ide/NFref DOMNode
    public function tagNFref()
    {
        if (!isset($this->NFref)) {
            $this->NFref = $this->dom->createElement("NFref");
        }
    }
    
    //tag NFe/infNFe/ide/NFref/refNFe DOMNode
    public function tagrefNFe($refNFe = '')
    {
        $this->refNFe = $this->dom->createElement("refNFe", $refNFe);
        return $this->refNFe;
    }
    
    //tag NFe/infNFe/ide/NFref/NF DOMNode
    public function tagrefNF(
        $cUF = '',
        $AAMM = '',
        $CNPJ = '',
        $mod = '',
        $serie = '',
        $nNF = ''
    ) {
        $this->refNF = $this->dom->createElement("refNF");
        $this->addChild($this->refNF, "cUF", $cUF);
        $this->addChild($this->refNF, "AAMM", $AAMM);
        $this->addChild($this->refNF, "CNPJ", $CNPJ);
        $this->addChild($this->refNF, "mod", $mod);
        $this->addChild($this->refNF, "serie", $serie);
        $this->addChild($this->refNF, "nNF", $nNF);
        return $this->refNF;
    }
    
    //tag NFe/infNFe/ide/NFref/NFPref DOMNode
    public function tagNFPref(
        $cUF = '',
        $AAMM = '',
        $CNPJ = '',
        $CPF = '',
        $IE = '',
        $mod = '',
        $serie = '',
        $nNF = ''
    ) {
        $this->refNFP = $this->dom->createElement("refNFP");
        $this->addChild($this->refNFP, "cUF", $cUF);
        $this->addChild($this->refNFP, "AAMM", $AAMM);
        $this->addChild($this->refNFP, "CNPJ", $CNPJ);
        $this->addChild($this->refNFP, "CPF", $CPF);
        $this->addChild($this->refNFP, "IE", $IE);
        $this->addChild($this->refNFP, "mod", $mod);
        $this->addChild($this->refNFP, "serie", $serie);
        $this->addChild($this->refNFP, "nNF", $nNF);
        return $this->refNFP;
    }
    
    //tag NFe/infNFe/ide/NFref/refCTe DOMNode
    public function tagCTeref($refCTe = '')
    {
        $this->refCTe = $this->dom->createElement("refCTe", $refCTe);
        return $this->refCTe;
    }
    
    //tag NFe/infNFe/ide/NFref/ECFref DOMNode
    public function tagECFref(
        $mod = '',
        $nECF = '',
        $nCOO = ''
    ) {
        $this->ECFref = $this->dom->createElement("ECFref");
        $this->addChild($this->ECFref, "mod", $mod);
        $this->addChild($this->ECFref, "nCOO", $nCOO);
        return $this->ECFref;
    }
    
    //tag NFe/infNFe/emit DOMNode
    public function tagemit(
        $CNPJ = '',
        $CPF = '',
        $xNome = '',
        $xFant = '',
        $IE = '',
        $IEST = '',
        $IM = '',
        $CNAE = '',
        $CRT = ''
    ) {
        $this->emit = $this->dom->createElement("emit");
        if ($CNPJ != '') {
            $this->addChild($this->emit, "CNPJ", $CNPJ);
        } else {
            $this->addChild($this->emit, "CPF", $CPF);
        }
        $this->addChild($this->emit, "xNome", $xNome);
        if ($xFant != '') {
            $this->addChild($this->emit, "xFant", $xFant);
        }
        $this->addChild($this->emit, "IE", $IE);
        if ($IEST != '') {
            $this->addChild($this->emit, "IEST", $IEST);
        }
        if ($IM != '') {
            $this->addChild($this->emit, "IM", $IM);
        }
        if ($CNAE != '') {
            $this->addChild($this->emit, "CNAE", $CNAE);
        }
        if ($CRT != '') {
            $this->addChild($this->emit, "CRT", $CRT);
        }
    }
    
    //tag NFe/infNFe/emit/endEmit DOMNode
    public function tagenderEmit(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $UF = '',
        $CEP = '',
        $cPais = '',
        $xPais = '',
        $fone = ''
    ) {
        $this->enderEmit = $this->dom->createElement("enderEmit");
        $this->addChild($this->enderEmit, "xLgr", $xLgr);
        $this->addChild($this->enderEmit, "nro", $nro);
        if ($xCpl != '') {
            $this->addChild($this->enderEmit, "xCpl", $xCpl);
        }
        $this->addChild($this->enderEmit, "xBairro", $xBairro);
        $this->addChild($this->enderEmit, "cMun", $cMun);
        $this->addChild($this->enderEmit, "xMun", $xMun);
        $this->addChild($this->enderEmit, "UF", $UF);
        $this->addChild($this->enderEmit, "CEP", $CEP);
        if ($cPais != '') {
            $this->addChild($this->enderEmit, "cPais", $cPais);
        }
        if ($xPais != '') {
            $this->addChild($this->enderEmit, "xPais", $xPais);
        }
        if ($fone != '') {
            $this->addChild($this->enderEmit, "fone", $fone);
        }
        return $this->enderEmit;
    }
    
    //tag NFe/infNFe/dest (opcional para modelo 65) DOMNode
    public function tagdest(
        $CNPJ = '',
        $CPF = '',
        $idEstrangeiro = '',
        $xNome = '',
        $indIEDest = '',
        $IE = '',
        $ISUF = '',
        $IM = '',
        $email = ''
    ) {
        $this->dest = $this->dom->createElement("dest");
        if ($CNPJ != '') {
            $this->addChild($this->dest, "CNPJ", $CNPJ);
        } elseif ($CPF != '') {
            $this->addChild($this->dest, "CPF", $CPF);
        } else {
            $this->addChild($this->dest, "idEstrangeiro", $idEstrangeiro);
        }
        if ($xNome != '') {
            $this->addChild($this->dest, "xNome", $xNome);
        }
        if ($this->versao > 2.00) {
            if ($this->mod == '65') {
                $indIEDest = '9';
                $this->addChild($this->dest, "indIEDest", $indIEDest);
            } else {
                $this->addChild($this->dest, "indIEDest", $indIEDest);
            }
        }
        if ($this->versao > 2.00) {
            if ($indIEDest != '9' && $indIEDest != '2') {
                $this->addChild($this->dest, "IE", $IE);
            }
        } else {
            $this->addChild($this->dest, "IE", $IE);
        }
        if ($ISUF != '') {
            $this->addChild($this->dest, "ISUF", $ISUF);
        }
        if ($IM != '') {
            $this->addChild($this->dest, "IM", $IM);
        }
        if ($email != '') {
            $this->addChild($this->dest, "email", $email);
        }
        return $this->dest;
    }
    
    //tag NFe/infNFe/dest/enderDest DOMNode
    public function tagenderDest(
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $UF = '',
        $CEP = '',
        $cPais = '',
        $xPais = '',
        $fone = ''
    ) {
        $this->enderDest = $this->dom->createElement("enderDest");
        $this->addChild($this->enderDest, "xLgr", $xLgr);
        $this->addChild($this->enderDest, "nro", $nro);
        if ($xCpl != '') {
            $this->addChild($this->enderDest, "xCpl", $xCpl);
        }
        $this->addChild($this->enderDest, "xBairro", $xBairro);
        $this->addChild($this->enderDest, "cMun", $cMun);
        $this->addChild($this->enderDest, "xMun", $xMun);
        $this->addChild($this->enderDest, "UF", $UF);
        if ($CEP != '') {
            $this->addChild($this->enderDest, "CEP", $CEP);
        }
        if ($cPais != '') {
            $this->addChild($this->enderDest, "cPais", $cPais);
        }
        if ($xPais != '') {
            $this->addChild($this->enderDest, "xPais", $xPais);
        }
        if ($fone != '') {
            $this->addChild($this->enderDest, "fone", $fone);
        }
        return $this->enderDest;
    }
    
    //tag NFe/infNFe/retirada (opcional) DOMNode
    public function tagretirada(
        $CNPJ = '',
        $CPF = '',
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $UF = ''
    ) {
        $this->retirada = $this->dom->createElement("retirada");
        if ($CNPJ != '') {
            $this->addChild($this->retirada, "CNPJ", $CNPJ);
        } else {
            $this->addChild($this->retirada, "CPF", $CPF);
        }
        $this->addChild($this->retirada, "xLgr", $xLgr);
        $this->addChild($this->retirada, "nro", $nro);
        if ($xCpl != '') {
            $this->addChild($this->retirada, "xCpl", $xCpl);
        }
        $this->addChild($this->retirada, "xBairro", $xBairro);
        $this->addChild($this->retirada, "cMun", $cMun);
        $this->addChild($this->retirada, "xMun", $xMun);
        $this->addChild($this->retirada, "UF", $UF);
        return $this->retirada;
    }
    
    //tag NFe/infNFe/entrega (opcional) DOMNode
    public function tagentrega(
        $CNPJ = '',
        $CPF = '',
        $xLgr = '',
        $nro = '',
        $xCpl = '',
        $xBairro = '',
        $cMun = '',
        $xMun = '',
        $UF = ''
    ) {
        $this->entrega = $this->dom->createElement("entrega");
        if ($CNPJ != '') {
            $this->addChild($this->entrega, "CNPJ", $CNPJ);
        } else {
            $this->addChild($this->entrega, "CPF", $CPF);
        }
        $this->addChild($this->entrega, "xLgr", $xLgr);
        $this->addChild($this->entrega, "nro", $nro);
        if ($xCpl != '') {
            $this->addChild($this->entrega, "xCpl", $xCpl);
        }
        $this->addChild($this->entrega, "xBairro", $xBairro);
        $this->addChild($this->entrega, "cMun", $cMun);
        $this->addChild($this->entrega, "xMun", $xMun);
        $this->addChild($this->entrega, "UF", $UF);
        return $this->entrega;
    }
    
    //tag NFe/infNFe/autXML (somente versão 3.1) array de DOMNodes
    public function tagautoXML($CNPJ = '', $CPF = '')
    {
        $autXML = $this->dom->createElement("autXML");
        if ($CNPJ != '') {
            $this->addChild($autXML, "CNPJ", $CNPJ);
        } else {
             $this->addChild($autXML, "CPF", $CPF);
        }
        $this->aAutXML[]=$autXML;
        return $autXML;
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
            $imp->appendChild($this->aPIS[$nItem]);
            $imp->appendChild($this->aCOFINS[$nItem]);
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
        $this->addChild($prod, "cProd", $cProd);
        $this->addChild($prod, "cEAN", $cEAN);
        $this->addChild($prod, "xProd", $xProd);
        $this->addChild($prod, "NCM", $NCM);
        if ($NVE != '') {
            $this->addChild($prod, "NVE", $NVE);
        }
        if ($EXTIPI != '') {
            $this->addChild($prod, "EXTIPI", $EXTIPI);
        }
        $this->addChild($prod, "CFOP", $CFOP);
        $this->addChild($prod, "uCom", $uCom);
        $this->addChild($prod, "qCom", $qCom);
        $this->addChild($prod, "vUnCom", $vUnCom);
        $this->addChild($prod, "vProd", $vProd);
        $this->addChild($prod, "cEANTrib", $cEANTrib);
        $this->addChild($prod, "uTrib", $uTrib);
        $this->addChild($prod, "qTrib", $qTrib);
        $this->addChild($prod, "vUnTrib", $vUnTrib);
        if ($vFrete != '') {
            $this->addChild($prod, "vFrete", $vFrete);
        }
        if ($vSeg != '') {
            $this->addChild($prod, "vSeg", $vSeg);
        }
        if ($vDesc != '') {
            $this->addChild($prod, "vDesc", $vDesc);
        }
        if ($vOutro != '') {
            $this->addChild($prod, "vOutro", $vOutro);
        }
        $this->addChild($prod, "indTot", $indTot);
        if ($xPed != '') {
            $this->addChild($prod, "xPed", $xPed);
        }
        if ($nItemPed != '') {
            $this->addChild($prod, "nItemPed", $nItemPed);
        }
        if ($nFCI != '') {
            $this->addChild($prod, "nFCI", $nFCI);
        }
        if ($nRECOPI != '') {
            $this->addChild($prod, "nRECOPI", $nRECOPI);
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
        $this->addChild($DI, "nDI", $nDI);
        $this->addChild($DI, "dDI", $dDI);
        $this->addChild($DI, "xLocDesemb", $xLocDesemb);
        $this->addChild($DI, "UFDesemb", $UFDesemb);
        $this->addChild($DI, "dDesemb", $dDesemb);
        $this->addChild($DI, "tpViaTransp", $tpViaTransp);
        if ($vAFRMM != '') {
            $this->addChild($DI, "vAFRMM", $vAFRMM);
        }
        $this->addChild($DI, "tpIntermedio", $tpIntermedio);
        if ($CNPJ != '') {
            $this->addChild($DI, "CNPJ", $CNPJ);
        }
        if ($UFTerceiro != '') {
            $this->addChild($DI, "UFTerceiro", $UFTerceiro);
        }
        $this->addChild($DI, "cExportador", $cExportador);
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
        $this->addChild($adi, "nAdicao", $nAdicao);
        $this->addChild($adi, "nSeqAdicC", $nSeqAdicC);
        $this->addChild($adi, "cFabricante", $cFabricante);
        if ($vDescDI != '') {
            $this->addChild($adi, "vDescDI", $vDescDI);
        }
        if ($nDraw != '') {
            $this->addChild($adi, "nDraw", $nDraw);
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
                $this->addChild($detExport, "nDraw", $nDraw);
            }
            if ($exportInd != '') {
                $this->addChild($detExport, "exportInd", $exportInd);
            }
            $this->addChild($detExport, "nRE", $nRE);
            $this->addChild($detExport, "chNFe", $chNFe);
            $this->addChild($detExport, "qExport", $qExport);
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
        $this->addChild($veicProd, "tpOp", $tpOp);
        $this->addChild($veicProd, "chassi", $chassi);
        $this->addChild($veicProd, "cCor", $cCor);
        $this->addChild($veicProd, "xCor", $xCor);
        $this->addChild($veicProd, "pot", $pot);
        $this->addChild($veicProd, "cilin", $cilin);
        $this->addChild($veicProd, "pesoL", $pesoL);
        $this->addChild($veicProd, "pesoB", $pesoB);
        $this->addChild($veicProd, "nSerie", $nSerie);
        $this->addChild($veicProd, "tpCpmb", $tpComb);
        $this->addChild($veicProd, "nMotor", $nMotor);
        $this->addChild($veicProd, "CMT", $CMT);
        $this->addChild($veicProd, "dist", $dist);
        $this->addChild($veicProd, "anoMd", $anoMod);
        $this->addChild($veicProd, "anoFab", $anoFab);
        $this->addChild($veicProd, "tpPint", $tpPint);
        $this->addChild($veicProd, "tpVeic", $tpVeic);
        $this->addChild($veicProd, "espVeic", $espVeic);
        $this->addChild($veicProd, "VIN", $VIN);
        $this->addChild($veicProd, "condVeic", $condVeic);
        $this->addChild($veicProd, "cMod", $cMod);
        $this->addChild($veicProd, "cCorDENATRAN", $cCorDENATRAN);
        $this->addChild($veicProd, "lota", $lota);
        $this->addChild($veicProd, "tpResp", $tpRest);
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
        $this->addChild($med, "nLote", $nLote);
        $this->addChild($med, "qLote", $qLote);
        $this->addChild($med, "dFab", $dFab);
        $this->addChild($med, "dVal", $dVal);
        $this->addChild($med, "vPMC", $vPMC);
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
        $this->addChild($arma, "tpArma", $tpArma);
        $this->addChild($arma, "nSerie", $nSerie);
        $this->addChild($arma, "nCano", $nCano);
        $this->addChild($arma, "descr", $descr);
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
        $CIDE = '',
        $qBCProd = '',
        $vAliqProd = '',
        $vCIDE = ''
    ) {
        $comb = $this->dom->createElement("comb");
        $this->addChild($comb, "cProdANP", $cProdANP);
        $this->addChild($comb, "pMixGN", $pMixGN);
        $this->addChild($comb, "CODIF", $CODIF);
        $this->addChild($comb, "qTemp", $qTemp);
        $this->addChild($comb, "UFCons", $UFCons);
        $this->addChild($comb, "CIDE", $CIDE);
        $this->addChild($comb, "qBCProd", $qBCProd);
        $this->addChild($comb, "vAliqProd", $vAliqProd);
        $this->addChild($comb, "vCIDE", $vCIDE);
        $this->aComb[$nItem] = $comb;
        return $comb;
    }

    //tag NFe/infNFe/det/imposto array de DOMNodes
    public function tagimposto($nItem = '', $vTotTrib = '')
    {
        $imposto = $this->dom->createElement("imposto");
        $this->addChild($imposto, "vTotTrib", $vTotTrib);
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
        $vICMS = ''
    ) {
        switch ($CST) {
            case '00':
                $ICMS = $this->dom->createElement("ICMS00");
                break;
            case '10':
                $ICMS = $this->dom->createElement("ICMS10");
                break;
            case '30':
                $ICMS = $this->dom->createElement("ICMS30");
                break;
            case '40':
                $ICMS = $this->dom->createElement("ICMS40");
                break;
            case '41':
                $ICMS = $this->dom->createElement("ICMS40");
                break;
            case '50':
                $ICMS = $this->dom->createElement("ICMS40");
                break;
            case '51':
                $ICMS = $this->dom->createElement("ICMS51");
                break;
            case '60':
                $ICMS = $this->dom->createElement("ICMS60");
                break;
            case '70':
                $ICMS = $this->dom->createElement("ICMS70");
                break;
            case '90':
                $ICMS = $this->dom->createElement("ICMS40");
                break;
        }
        
            $this->addChild($ICMS, 'orig', $orig);
            $this->addChild($ICMS, 'CST', $CST);
            $this->addChild($ICMS, 'modBC', $modBC);
            $this->addChild($ICMS, 'vBC', $vBC);
            $this->addChild($ICMS, 'pICMS', $pICMS);
            $this->addChild($ICMS, 'vICMS', $vICMS);
        
            $tagIcms = $this->dom->createElement('ICMS');
            $tagIcms->appendChild($ICMS);
            $this->aICMS[$nItem] = $tagIcms;
            
            return $tagIcms;
    }

    //tag det/imposto/IPI (opcional) array de DOMNodes
    public function tagIPI()
    {
        
    }
    
    //tag det/imposto/II (opcional) array de DOMNodes
    public function tagII()
    {
        
    }
    
    //tag det/imposto/ISSQN (opcional) array de DOMNodes
    public function tagISSQN()
    {
        
    }
    
    //tag det/imposto/PIS array de DOMNodes
    public function tagPIS(
        $nItem = '',
        $CST = '',
        $vBC = '',
        $pPIS = '',
        $vPIS = ''
    ) {
        $PISAliq = $this->dom->createElement('PISAliq');
        
        $this->addChild($PISAliq, 'CST', $CST);
        $this->addChild($PISAliq, 'vBC', $vBC);
        $this->addChild($PISAliq, 'pPIS', $pPIS);
        $this->addChild($PISAliq, 'vPIS', $vPIS);
        
        $pis = $this->dom->createElement('PIS');
        $pis->appendChild($PISAliq);
        $this->aPIS[$nItem] = $pis;
        
        return $pis;
    }
    
    //tag det/imposto/PISST (opcional) array de DOMNodes
      
    //tag det/imposto/COFINS array de DOMNodes
    public function tagCOFINS(
        $nItem = '',
        $CST = '',
        $vBC = '',
        $pCOFINS = '',
        $vCOFINS = ''
    ) {
        $COFINSAliq = $this->dom->createElement('COFINSAliq');
        $this->addChild($COFINSAliq, 'CST', $CST);
        $this->addChild($COFINSAliq, 'vBC', $vBC);
        $this->addChild($COFINSAliq, 'pCOFINS', $pCOFINS);
        $this->addChild($COFINSAliq, 'vCOFINS', $vCOFINS);
        $confins = $this->dom->createElement('COFINS');
        $confins->appendChild($COFINSAliq);
        $this->aCOFINS[$nItem] = $confins;
        return $confins;
    }
    
    //tag det/imposto/COFINSST (opcional) array de DOMNodes
    
    //tag NFe/infNFe/total DOMNode
    public function tagtotal()
    {
        if (!isset($this->total)) {
            $this->total = $this->dom->createElement("total");
        }
    }
    
    //tag NFe/infNFe/total/ICMSTot DOMNode
    public function tagICMSTot(
        $vBC = '',
        $vICMS = '',
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
        $vNF = ''
    ) {
        $this->ICMSTot = $this->dom->createElement("ICMSTot");
        $this->addChild($this->ICMSTot, "vBC", $vBC);
        $this->addChild($this->ICMSTot, "vICMS", $vICMS);
        $this->addChild($this->ICMSTot, "vBCST", $vBCST);
        $this->addChild($this->ICMSTot, "vST", $vST);
        $this->addChild($this->ICMSTot, "vProd", $vProd);
        $this->addChild($this->ICMSTot, "vFrete", $vFrete);
        $this->addChild($this->ICMSTot, "vSeg", $vSeg);
        $this->addChild($this->ICMSTot, "vDesc", $vDesc);
        $this->addChild($this->ICMSTot, "vII", $vII);
        $this->addChild($this->ICMSTot, "vIPI", $vIPI);
        $this->addChild($this->ICMSTot, "vPIS", $vPIS);
        $this->addChild($this->ICMSTot, "vCOFINS", $vCOFINS);
        $this->addChild($this->ICMSTot, "vOutro", $vOutro);
        $this->addChild($this->ICMSTot, "vNF", $vNF);
        return $this->ICMSTot;
    }
    
    //tag NFe/infNFe/total/ISSQNTot (opcional) DOMNode
    public function tagISSQNTot($vServ = '', $vBC = '', $vISS = '', $vPIS = '', $vCOFINS = '')
    {
        $this->ISSQNTot = $this->dom->createElement("ISSQNTot");
        $this->addChild($this->ISSQNTot, "vServ", $vServ);
        $this->addChild($this->ISSQNTot, "vBC", $vBC);
        $this->addChild($this->ISSQNTot, "vISS", $vISS);
        $this->addChild($this->ISSQNTot, "vPIS", $vPIS);
        $this->addChild($this->ISSQNTot, "vCOFINS", $vCOFINS);
        return $this->ISSQNTot;
    }
    
    //tag NFe/infNFe/total/reTrib (opcional) DOMNode
    public function tagreTrib(
        $vRetPIS = '',
        $vRetCOFINS = '',
        $vRetCSLL = '',
        $vBCIRRF = '',
        $vIRRF = '',
        $vBCRetPrev = '',
        $vRetPrev = ''
    ) {
        $this->reTrib = $this->dom->createElement("reTrib");
        $this->addChild($this->reTrib, "vRetPIS", $vRetPIS);
        $this->addChild($this->reTrib, "vRetCOFINS", $vRetCOFINS);
        $this->addChild($this->reTrib, "vRetCSLL", $vRetCSLL);
        $this->addChild($this->reTrib, "vBCIRRF", $vBCIRRF);
        $this->addChild($this->reTrib, "vIRRF", $vIRRF);
        $this->addChild($this->reTrib, "vBCRetPrev", $vBCRetPrev);
        $this->addChild($this->reTrib, "vRetPrev", $vRetPrev);
        return $this->reTrib;
    }
    
    //tag NFe/infNFe/transp
    public function tagtransp($modFrete = '')
    {
        $this->transp = $this->dom->createElement("transp");
        $this->addChild($this->transp, "modFrete", $modFrete);
        return $this->transp;
    }
    
    //tag transp/tranporta (opcional)
    public function tagtransporta($CNPJ = '', $CPF = '', $xNome = '', $IE = '', $xEnder = '', $xMun = '', $UF = '')
    {
        $this->transporta = $this->dom->createElement("transporta");
        if ($CNPJ != '') {
            $this->addChild($this->transporta, "CNPJ", $CNPJ);
        }
        if ($CPF != '') {
            $this->addChild($this->transporta, "CPF", $CPF);
        }
        if ($xNome != '') {
            $this->addChild($this->transporta, "xNome", $xNome);
        }
        if ($IE != '') {
            $this->addChild($this->transporta, "IE", $IE);
        }
        if ($xEnder != '') {
            $this->addChild($this->transporta, "xEnder", $xEnder);
        }
        if ($xMun != '') {
            $this->addChild($this->transporta, "xMun", $xMun);
        }
        if ($UF != '') {
            $this->addChild($this->transporta, "UF", $UF);
        }
        return $this->transporta;
    }

    //tag NFe/infNFe/transp/veicTransp (opcional)
    public function tagveicTransp($placa = '', $UF = '', $RNTC = '')
    {
        $this->veicTransp = $this->dom->createElement("veicTransp");
        $this->addChild($this->veicTransp, "placa", $placa);
        $this->addChild($this->veicTransp, "UF", $UF);
        if ($RNTC != '') {
            $this->addChild($this->veicTransp, "RNTC", $RNTC);
        }
        return $this->veicTransp;
    }
    
    //tag NFe/infNFe/transp/reboque (opcional)
    public function tagreboque($placa = '', $UF = '', $RNTC = '', $vagao = '', $balsa = '')
    {
        $reboque = $this->dom->createElement("reboque");
        $this->addChild($reboque, "placa", $placa);
        $this->addChild($reboque, "UF", $UF);
        if ($RNTC != '') {
            $this->addChild($reboque, "RNTC", $RNTC);
        }
        if ($vagao != '') {
            $this->addChild($reboque, "vagao", $vagao);
        }
        if ($balsa != '') {
            $this->addChild($reboque, "balsa", $balsa);
        }
        $this->aReboque[] = $reboque;
        return $reboque;
    }
    
    //tag NFe/infNFe/transp/retTransp (opcional)
    public function tagretTransp($vServ = '', $vBCRet = '', $pICMSRet = '', $vICMSRet = '', $CFOP = '', $cMunFG = '')
    {
        $this->retTransp = $this->dom->createElement("retTransp");
        $this->addChild($this->retTransp, "vServ", $vServ);
        $this->addChild($this->retTransp, "vBCRet", $vBCRet);
        $this->addChild($this->retTransp, "pICMSRet", $pICMSRet);
        $this->addChild($this->retTransp, "vICMSRet", $vICMSRet);
        $this->addChild($this->retTransp, "CFOP", $CFOP);
        $this->addChild($this->retTransp, "cMunFG", $cMunFG);
        return $this->retTransp;
    }
    
    //tag NFe/infNFe/transp/vol (opcional)
    public function tagvol($qVol = '', $esp = '', $marca = '', $nVol = '', $pesoL = '', $pesoB = '', $aLacres = '')
    {
        $vol = $this->dom->createElement("vol");
        if ($qVol != '') {
            $this->addChild($vol, "qVol", $qVol);
        }
        if ($esp != '') {
            $this->addChild($vol, "esp", $esp);
        }
        if ($marca != '') {
            $this->addChild($vol, "marca", $marca);
        }
        if ($nVol != '') {
            $this->addChild($vol, "nVol", $nVol);
        }
        if ($pesoL != '') {
            $this->addChild($vol, "pesoL", $pesoL);
        }
        if ($pesoB != '') {
            $this->addChild($vol, "pesoB", $pesoB);
        }
        if ($aLacres != '') {
            if (is_array($aLacres)) {
                //tag transp/vol/lacres (opcional)
                foreach ($aLacres as $nLacre) {
                    $lacre = $this->taglacres($nLacre);
                    $vol->appendChild($lacre);
                    $lacre - null;
                }
            }
        }
        $this->aVol[] = $vol;
        return $vol;
    }
    
    //tag NFe/infNFe/transp/vol/lacres (opcional)
    public function taglacres($nLacre = '')
    {
        $lacre = $this->dom->createElement("lacres");
        $this->addChild($lacre, "nLacre", $nLacre);
        return $lacre;
    }
    
    
    //tag NFe/infNFe/cobr (opcional)
    public function tagcobr()
    {
        if (!isset($this->cobr)) {
            $this->cobr = $this->dom->createElement("cobr");
        }
    }
    
    //tag NFe/infNFe/cobr/fat (opcional)
    public function tagfat($nFat = '', $vOrig = '', $vDesc = '', $vLiq = '')
    {
        $this->fat = $this->dom->createElement("fat");
        if ($nFat != '') {
            $this->addChild($this->fat, "nFat", $nFat);
        }
        if ($vOrig != '') {
            $this->addChild($this->fat, "vOrig", $vOrig);
        }
        if ($vDesc != '') {
            $this->addChild($this->fat, "vDesc", $vDesc);
        }
        if ($vLiq != '') {
            $this->addChild($this->fat, "vLiq", $vLiq);
        }
        return $this->fat;
    }
    
    //tag NFe/infNFe/cobr/fat/dup (opcional)
    public function tagdup($nDup = '', $dVenc = '', $vDup = '')
    {
        $dup = $this->dom->createElement("dup");
        if ($nDup != '') {
            $this->addChild($dup, "nDup", $nDup);
        }
        if ($dVenc != '') {
            $this->addChild($dup, "dVenc", $dVenc);
        }
        $this->addChild($dup, "vDup", $vDup);
        $this->aDup[] = $dup;
        return $dup;
    }
    
    //tag NFe/infNFe/pag (opcional)
    public function tagpag(
        $tPag = '',
        $vPag = ''
    ) {
        if ($this->mod == '65') {
            $this->pag = $this->dom->createElement("pag");
            $this->addChild($this->pag, "tPag", $tPag);
            $this->addChild($this->pag, "vPag", $vPag);
        }
        return $this->pag;
    }
    
    //tag NFe/infNFe/pag/card
    public function tagcard(
        $CNPJ = '',
        $tBand = '',
        $cAut = ''
    ) {
        if ($this->mod == '65' && $tBand != '') {
            $this->card = $this->dom->createElement("card");
            $this->addChild($this->card, "CNPJ", $CNPJ);
            $this->addChild($this->card, "tBand", $tBand);
            $this->addChild($this->card, "cAut", $cAut);
        }
        return $this->card;
    }
    
    //tag NFe/infNFe/infAdic (opcional)
    public function taginfAdic(
        $infAdFisco = '',
        $infCpl = ''
    ) {
        if (!isset($this->infAdic)) {
            $this->infAdic = $this->dom->createElement("infAdic");
        }
        if ($infAdFisco != '') {
            $this->addChild($this->infAdic, "infAdFisco", $infAdFisco);
        }
        if ($infCpl != '') {
            $this->addChild($this->infAdic, "infCpl", $infCpl);
        }
        return $this->infAdic;
    }
    
    //tag NFe/infNFe/infAdic/obsCont (opcional)
    public function tagobsCont(
        $xCampo = '',
        $xTexto = ''
    ) {
        $obsCont = $this->dom->createElement("obsCont");
        $this->addChild($obsCont, "xCampo", $xCampo);
        $this->addChild($obsCont, "xTexto", $xTexto);
        $this->aObsCont[]=$obsCont;
        return $obsCont;
    }
    
    //tag NFe/infNFe/infAdic/obsFisco (opcional)
    public function tagobsFisco(
        $xCampo = '',
        $xTexto = ''
    ) {
        $obsFisco = $this->dom->createElement("obsFisco");
        $this->addChild($obsFisco, "xCampo", $xCampo);
        $this->addChild($obsFisco, "xTexto", $xTexto);
        $this->aObsFisco[]=$obsFisco;
        return $obsFisco;
    }
    
    //tag NFe/infNFe/procRef (opcional)
    public function tagprocRef(
        $nProc = '',
        $indProc = ''
    ) {
        $procRef = $this->dom->createElement("procRef");
        $this->addChild($procRef, "nProc", $nProc);
        $this->addChild($procRef, "indProc", $indProc);
        $this->aProcRef[]=$procRef;
        return $procRef;
        
    }
    
    //tag NFe/infNFe/exporta (opcional)
    public function tagexporta(
        $UFSaidaPais = '',
        $xLocExporta = '',
        $xLocDespacho = ''
    ) {
        $this->exporta = $this->dom->createElement("exporta");
        $this->addChild($this->exporta, "UFSaidaPais", $UFSaidaPais);
        $this->addChild($this->exporta, "xLocExporta", $xLocExporta);
        $this->addChild($this->exporta, "xLocDespacho", $xLocDespacho);
        return $this->exporta;
    }
    
    //tag NFe/infNFe/compra (opcional)
    public function tagcompra(
        $xNEmp = '',
        $xPed = '',
        $xCont = ''
    ) {
        if (($xNEmp.$xPed.$xCont) == '') {
            return false;
        }
        $this->compra = $this->dom->createElement("compra");
        if ($NEmp != '') {
            $this->addChild($this->compra, "xNEmp", $NEmp);
        }
        if ($xPed != '') {
            $this->addChild($this->compra, "xPed", $xPed);
        }
        if ($xCont != '') {
            $this->addChild($this->compra, "xCont", $xCont);
        }
        return $this->compra;
    }
    
    //tag NFe/infNFe/cana (opcional)
    public function tagcana(
        $safra = '',
        $ref = ''
    ) {
        $this->cana = $this->dom->createElement("cana");
        $this->addChild($this->cana, "safra", $safra);
        $this->addChild($this->cana, "ref", $ref);
        return $this->cana;
    }
    
    //tag NFe/infNFe/cana/forDia
    public function tagforDia(
        $dia = '',
        $qtde = '',
        $qTotMes = '',
        $qTotAnt = '',
        $qTotGer = ''
    ) {
        $forDia = $this->dom->createElement("forDia");
        $forDia->setAttribute("dia", $dia);
        $this->addChild($forDia, "qtde", $qtde);
        $this->addChild($forDia, "qTotMes", $qTotMes);
        $this->addChild($forDia, "qTotAnt", $qTotAnt);
        $this->addChild($forDia, "qTotGer", $qTotGer);
        $this->aForDia[] = $forDia;
        return $forDia;
    }
    
    //tag NFe/infNFe/cana/deduc (opcional)
    public function tagdeduc(
        $xDed = '',
        $vDed = '',
        $vFor = '',
        $vTotDed = '',
        $vLiqFor = ''
    ) {
        $deduc = $this->dom->createElement("deduc");
        $this->addChild($deduc, "xDed", $xDed);
        $this->addChild($deduc, "vDed", $vDed);
        $this->addChild($deduc, "vFor", $vFor);
        $this->addChild($deduc, "vTotDed", $vTotDed);
        $this->addChild($deduc, "vLiqFor", $vLiqFor);
        $this->aDeduc[] = $deduc;
        return $deduc;
    }
    
    public function validChave($chave)
    {
        
    }
    
    private function addChild(&$parent, $name, $content)
    {
        $temp = $this->dom->createElement($name, $content);
        $parent->appendChild($temp);
    }
}
