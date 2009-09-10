<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação 
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; sem mesmo a garantia explícita do VALOR COMERCIAL ou ADEQUAÇÃO PARA
 * UM PROPÓSITO EM PARTICULAR, veja a Licença Pública Geral GNU para mais
 * detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU junto com este
 * programa. Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3>.
 *
 * @package   NFePHP
 * @name      NFe extends NFEtools
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 */

/**
 * NFe 
 *
 * @author  Djalma Fadel Junior <dfadel@ferasoft.com.br>
 */
class NFe extends NFEtools {
    var $infNFe;        // A01 - grupo das informações da NFe
    var $situacao;      // objeto de situação da NF-e

    function __construct() {
        $this->infNFe   = new infNFe;
        $this->situacao = new situacao;
    }

    function get_xml() {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;
        
        $raiz = $dom->appendChild($dom->createElement('NFe'));
        $raiz_att = $raiz->appendChild($dom->createAttribute('xmlns'));
                    $raiz_att->appendChild($dom->createTextNode('http://www.portalfiscal.inf.br/nfe'));

        $raiz->appendChild($this->infNFe->get_xml($dom));
        return $dom->saveXML();
    }

    // BUG: é feio. é gambi. mas funciona.
    function fetch($con, $NFe_id) {
        $res_NFe = $con->queryAll('SELECT * FROM NFe WHERE NFe_id = '.$con->quote($NFe_id), null, MDB2_FETCHMODE_ASSOC);
        if (count($res_NFe)) {

            //NFe
            //$this->situacao_id = $res_NFe[0]['situacao_id'];
            $this->situacao->fetch($con, $res_NFe[0]['situacao_id']);

            //infNFe
            $res_infNFe = $con->queryAll('SELECT * FROM infNFe WHERE NFe_id = '.$res_NFe[0]['nfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_infNFe)) {
                $this->infNFe->versao = $res_infNFe[0]['versao'];
                $this->infNFe->Id     = $res_infNFe[0]['id'];
            }

            //ide
            $res_ide = $con->queryAll('SELECT * FROM ide WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_ide)) {
                $this->infNFe->ide->cUF       = $res_ide[0]['cuf'];
                $this->infNFe->ide->cNF       = $res_ide[0]['cnf'];
                $this->infNFe->ide->natOp     = $res_ide[0]['natop'];
                $this->infNFe->ide->indPag    = $res_ide[0]['indpag'];
                $this->infNFe->ide->mod       = $res_ide[0]['modelo'];
                $this->infNFe->ide->serie     = $res_ide[0]['serie'];
                $this->infNFe->ide->nNF       = $res_ide[0]['nnf'];
                $this->infNFe->ide->dEmi      = $res_ide[0]['demi'];
                $this->infNFe->ide->dSaiEnt   = $res_ide[0]['dsaient'];
                $this->infNFe->ide->tpNF      = $res_ide[0]['tpnf'];
                $this->infNFe->ide->cMunFG    = $res_ide[0]['cmunfg'];
                $this->infNFe->ide->tpImp     = $res_ide[0]['tpimp'];
                $this->infNFe->ide->tpEmis    = $res_ide[0]['tpemis'];
                $this->infNFe->ide->cDV       = $res_ide[0]['cdv'];
                $this->infNFe->ide->tpAmb     = $res_ide[0]['tpamb'];
                $this->infNFe->ide->finNFe    = $res_ide[0]['finnfe'];
                $this->infNFe->ide->procEmi   = $res_ide[0]['procemi'];
                $this->infNFe->ide->verProc   = $res_ide[0]['verproc'];

                // refNFe
                $res_refNFe = $con->queryAll('SELECT * FROM refNFe WHERE ide_id = '.$res_ide[0]['ide_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_refNFe)) {
                    for ($i=0; $i<count($res_refNFe); $i++) {
                        $this->infNFe->ide->add_NFref(new NFref);
                        $this->infNFe->ide->NFref[$i]->refNFe = $res_refNFe[$i]['refnfe'];
                    }
                }

                // refNF
                $res_refNF = $con->queryAll('SELECT * FROM refNF WHERE ide_id = '.$res_ide[0]['ide_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_refNF)) {
                    for ($j=$i; $j-$i<count($res_refNF); $j++) {
                        $this->infNFe->ide->add_NFref(new NFref('NF'));
                        $this->infNFe->ide->NFref[$j]->refNF->cUF   = $res_refNF[$j-$i]['cuf'];
                        $this->infNFe->ide->NFref[$j]->refNF->AAMM  = $res_refNF[$j-$i]['aamm'];
                        $this->infNFe->ide->NFref[$j]->refNF->CNPJ  = $res_refNF[$j-$i]['cnpj'];
                        $this->infNFe->ide->NFref[$j]->refNF->mod   = $res_refNF[$j-$i]['modelo'];
                        $this->infNFe->ide->NFref[$j]->refNF->serie = $res_refNF[$j-$i]['serie'];
                        $this->infNFe->ide->NFref[$j]->refNF->nNF   = $res_refNF[$j-$i]['nnf'];
                    }
                }

            }

            //emit
            $res_emit = $con->queryAll('SELECT * FROM emit WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_emit)) {
                $this->infNFe->emit->CNPJ      = $res_emit[0]['cnpj'];
                $this->infNFe->emit->CPF       = $res_emit[0]['cpf'];
                $this->infNFe->emit->xNome     = $res_emit[0]['xnome'];
                $this->infNFe->emit->xFant     = $res_emit[0]['xfant'];
                $this->infNFe->emit->IE        = $res_emit[0]['ie'];
                $this->infNFe->emit->IEST      = $res_emit[0]['iest'];
                $this->infNFe->emit->IM        = $res_emit[0]['im'];
                $this->infNFe->emit->CNAE      = $res_emit[0]['cnae'];
            }

            //avulsa
            $res_avulsa = $con->queryAll('SELECT * FROM avulsa WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_avulsa)) {
               $this->infNFe->add_avulsa (new avulsa);
               $this->infNFe->avulsa->CNPJ      = $res_avulsa[0]['cnpj'];
               $this->infNFe->avulsa->xOrgao    = $res_avulsa[0]['xorgao'];
               $this->infNFe->avulsa->matr      = $res_avulsa[0]['matr'];
               $this->infNFe->avulsa->xAgente   = $res_avulsa[0]['xagente'];
               $this->infNFe->avulsa->fone      = $res_avulsa[0]['fone'];
               $this->infNFe->avulsa->UF        = $res_avulsa[0]['uf'];
               $this->infNFe->avulsa->nDAR      = $res_avulsa[0]['ndar'];
               $this->infNFe->avulsa->dEmi      = $res_avulsa[0]['demi'];
               $this->infNFe->avulsa->vDAR      = $res_avulsa[0]['vdar'];
               $this->infNFe->avulsa->repEmi    = $res_avulsa[0]['repemi'];
               $this->infNFe->avulsa->dPag      = $res_avulsa[0]['dpag'];
            }

            //dest
            $res_dest = $con->queryAll('SELECT * FROM dest WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_dest)) {
                $this->infNFe->dest->CNPJ       = $res_dest[0]['cnpj'];
                $this->infNFe->dest->CPF        = $res_dest[0]['cpf'];
                $this->infNFe->dest->xNome      = $res_dest[0]['xnome'];
                $this->infNFe->dest->IE         = $res_dest[0]['ie'];
                $this->infNFe->dest->ISUF       = $res_dest[0]['isuf'];
            }

            //retirada
            $res_retirada = $con->queryAll('SELECT * FROM retirada WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_retirada)) {
                $this->infNFe->add_retirada (new retirada);
                $this->infNFe->retirada->CNPJ   = $res_retirada[0]['cnpj'];
                $this->infNFe->retirada->xLgr   = $res_retirada[0]['xlgr'];
                $this->infNFe->retirada->nro    = $res_retirada[0]['nro'];
                $this->infNFe->retirada->xCpl   = $res_retirada[0]['xcpl'];
                $this->infNFe->retirada->xBairro= $res_retirada[0]['xbairro'];
                $this->infNFe->retirada->cMun   = $res_retirada[0]['cmun'];
                $this->infNFe->retirada->xMun   = $res_retirada[0]['xmun'];
                $this->infNFe->retirada->UF     = $res_retirada[0]['uf'];
            }

            //entrega
            $res_entrega = $con->queryAll('SELECT * FROM entrega WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_entrega)) {
                $this->infNFe->add_entrega (new entrega);
                $this->infNFe->entrega->CNPJ    = $res_entrega[0]['cnpj'];
                $this->infNFe->entrega->xLgr    = $res_entrega[0]['xlgr'];
                $this->infNFe->entrega->nro     = $res_entrega[0]['nro'];
                $this->infNFe->entrega->xCpl    = $res_entrega[0]['xcpl'];
                $this->infNFe->entrega->xBairro = $res_entrega[0]['xbairro'];
                $this->infNFe->entrega->cMun    = $res_entrega[0]['cmun'];
                $this->infNFe->entrega->xMun    = $res_entrega[0]['xmun'];
                $this->infNFe->entrega->UF      = $res_entrega[0]['uf'];
            }

            //det
            $res_det = $con->queryAll('SELECT * FROM det WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_det)) {
                for ($i=0; $i<count($res_det); $i++) {
                    $this->infNFe->add_det(new det);
                    $this->infNFe->det[$i]->nItem     = $res_det[$i]['nitem'];

                    // prod
                    $res_prod = $con->queryAll('SELECT * FROM prod WHERE det_id = '.$res_det[$i]['det_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_prod)) {
                        
                        $this->infNFe->det[$i]->prod->cProd     = $res_prod[0]['cprod'];
                        $this->infNFe->det[$i]->prod->cEAN      = $res_prod[0]['cean'];
                        $this->infNFe->det[$i]->prod->xProd     = $res_prod[0]['xprod'];
                        $this->infNFe->det[$i]->prod->NCM       = $res_prod[0]['ncm'];
                        $this->infNFe->det[$i]->prod->EXTIPI    = $res_prod[0]['extipi'];
                        $this->infNFe->det[$i]->prod->genero    = $res_prod[0]['genero'];
                        $this->infNFe->det[$i]->prod->CFOP      = $res_prod[0]['cfop'];
                        $this->infNFe->det[$i]->prod->uCom      = $res_prod[0]['ucom'];
                        $this->infNFe->det[$i]->prod->qCom      = $res_prod[0]['qcom'];
                        $this->infNFe->det[$i]->prod->vUnCom    = $res_prod[0]['vuncom'];
                        $this->infNFe->det[$i]->prod->vProd     = $res_prod[0]['vprod'];
                        $this->infNFe->det[$i]->prod->cEANTrib  = $res_prod[0]['ceantrib'];
                        $this->infNFe->det[$i]->prod->uTrib     = $res_prod[0]['utrib'];
                        $this->infNFe->det[$i]->prod->qTrib     = $res_prod[0]['qtrib'];
                        $this->infNFe->det[$i]->prod->vUnTrib   = $res_prod[0]['vuntrib'];
                        $this->infNFe->det[$i]->prod->vFrete    = $res_prod[0]['vfrete'];
                        $this->infNFe->det[$i]->prod->vSeg      = $res_prod[0]['vseg'];
                        $this->infNFe->det[$i]->prod->vDesc     = $res_prod[0]['vdesc'];

                         //DI
                         $res_DI = $con->queryAll('SELECT * FROM DI WHERE prod_id = '.$res_prod[0]['prod_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_DI)) {
                             for ($j=0; $j<count($res_DI); $j++) {
                                  $this->infNFe->det[$i]->prod->add_DI          = (new DI);
                                  $this->infNFe->det[$i]->prod->DI->nDI         = $res_DI[$j]['ndi'];
                                  $this->infNFe->det[$i]->prod->DI->dDi         = $res_DI[$j]['ddi'];
                                  $this->infNFe->det[$i]->prod->DI->xLocDesemb  = $res_DI[$j]['xlocdesemb'];
                                  $this->infNFe->det[$i]->prod->DI->UFDesemb    = $res_DI[$j]['ufdesemb'];
                                  $this->infNFe->det[$i]->prod->DI->dDesemb     = $res_DI[$j]['ddesemb'];
                                  $this->infNFe->det[$i]->prod->DI->cExportador = $res_DI[$j]['cexportador'];

                                  // adi
                                  $res_adi = $con->queryAll('SELECT * FROM adi WHERE DI_id = '.$res_DI[$i]['DI_id'], null, MDB2_FETCHMODE_ASSOC);
                                  if (count($res_adi)) {
                                      for ($x=0; $x<count($res_adi); $x++) {
                                           $this->infNFe->det[$i]->prod->DI->adi->nAdicao     = $res_adi[$x]['nadicao'];
                                           $this->infNFe->det[$i]->prod->DI->adi->nSeqAdic    = $res_adi[$x]['nseqadic'];
                                           $this->infNFe->det[$i]->prod->DI->adi->cFabricante = $res_adi[$x]['cfabricante'];
                                           $this->infNFe->det[$i]->prod->DI->adi->vDescDI     = $res_adi[$x]['vdescdi'];
                                      }
                                  }
                             }
                         }

                         //veicProd
                         $res_veicProd = $con->queryAll('SELECT * FROM veicProd WHERE prod_id = '.$res_prod[0]['prod_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_veicProc)) {
                            $this->infNFe->det[$i]->prod->add_veicProd= (new veicProd);
                            $this->infNFe->det[$i]->prod->veicProd->tpOp     = $res_veicProd[$i]['tpop'];
                            $this->infNFe->det[$i]->prod->veicProd->chassi   = $res_veicProd[$i]['chassi'];
                            $this->infNFe->det[$i]->prod->veicProd->cCor     = $res_veicProd[$i]['ccor'];
                            $this->infNFe->det[$i]->prod->veicProd->xCor     = $res_veicProd[$i]['xcor'];
                            $this->infNFe->det[$i]->prod->veicProd->pot      = $res_veicProd[$i]['pot'];
                            $this->infNFe->det[$i]->prod->veicProd->CM3      = $res_veicProd[$i]['cm3'];
                            $this->infNFe->det[$i]->prod->veicProd->pesoL    = $res_veicProd[$i]['pesol'];
                            $this->infNFe->det[$i]->prod->veicProd->pesoB    = $res_veicProd[$i]['pesob'];
                            $this->infNFe->det[$i]->prod->veicProd->nSerie   = $res_veicProd[$i]['nserie'];
                            $this->infNFe->det[$i]->prod->veicProd->tpComb   = $res_veicProd[$i]['tpcomb'];
                            $this->infNFe->det[$i]->prod->veicProd->NMotor   = $res_veicProd[$i]['nmotor'];
                            $this->infNFe->det[$i]->prod->veicProd->CMKG     = $res_veicProd[$i]['cmkg'];
                            $this->infNFe->det[$i]->prod->veicProd->dist     = $res_veicProd[$i]['dist'];
                            $this->infNFe->det[$i]->prod->veicProd->RENAVAM  = $res_veicProd[$i]['renavam'];
                            $this->infNFe->det[$i]->prod->veicProd->anoMod   = $res_veicProd[$i]['anomod'];
                            $this->infNFe->det[$i]->prod->veicProd->anoFab   = $res_veicProd[$i]['anofab'];
                            $this->infNFe->det[$i]->prod->veicProd->tpPint   = $res_veicProd[$i]['tppint'];
                            $this->infNFe->det[$i]->prod->veicProd->tpVeic   = $res_veicProd[$i]['tpveic'];
                            $this->infNFe->det[$i]->prod->veicProd->espVeic  = $res_veicProd[$i]['espveic'];
                            $this->infNFe->det[$i]->prod->veicProd->VIN      = $res_veicProd[$i]['vin'];
                            $this->infNFe->det[$i]->prod->veicProd->condVeic = $res_veicProd[$i]['condveic'];
                            $this->infNFe->det[$i]->prod->veicProd->cMod     = $res_veicProd[$i]['cmod'];
                         }

                         //med
                         $res_med = $con->queryAll('SELECT * FROM med WHERE prod_id = '.$res_prod[0]['prod_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_med)) {
                            $this->infNFe->det[$i]->prod->add_med    = (new med);
                            $this->infNFe->det[$i]->prod->med->nLote = $res_med[$i]['nlote'];
                            $this->infNFe->det[$i]->prod->med->qLote = $res_med[$i]['qlote'];
                            $this->infNFe->det[$i]->prod->med->dFab  = $res_med[$i]['dfab'];
                            $this->infNFe->det[$i]->prod->med->dVal  = $res_med[$i]['dval'];
                            $this->infNFe->det[$i]->prod->med->vPMC  = $res_med[$i]['vpmc'];
                         }

                         //arma
                         $res_arma = $con->queryAll('SELECT * FROM arma WHERE prod_id = '.$res_prod[0]['prod_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_arma)) {
                            $this->infNFe->det[$i]->prod->add_arma     = (new med);
                            $this->infNFe->det[$i]->prod->arma->tpArma = $res_arma[$i]['tparma'];
                            $this->infNFe->det[$i]->prod->arma->nSerie = $res_arma[$i]['nserie'];
                            $this->infNFe->det[$i]->prod->arma->nCano  = $res_arma[$i]['ncano'];
                            $this->infNFe->det[$i]->prod->arma->descr  = $res_arma[$i]['desc'];
                         }

                         //comb
                         $res_comb = $con->queryAll('SELECT * FROM comb WHERE prod_id = '.$res_prod[0]['prod_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_comb)) {
                            $this->infNFe->det[$i]->prod->add_comb            = (new comb);
                            $this->infNFe->det[$i]->prod->comb->cProdANP      = $res_comb[$i]['cprodanp'];
                            $this->infNFe->det[$i]->prod->comb->CODIF         = $res_comb[$i]['codif'];
                            $this->infNFe->det[$i]->prod->comb->qTemp         = $res_comb[$i]['qtemp'];
                            $this->infNFe->det[$i]->prod->comb->qBCprod       = $res_comb[$i]['qbcprod'];
                            $this->infNFe->det[$i]->prod->comb->vAliqProd     = $res_comb[$i]['aliqprod'];
                            $this->infNFe->det[$i]->prod->comb->vCIDE         = $res_comb[$i]['vcide'];
                            $this->infNFe->det[$i]->prod->comb->vBCICMS       = $res_comb[$i]['vbcicms'];
                            $this->infNFe->det[$i]->prod->comb->vICMS         = $res_comb[$i]['vicms'];
                            $this->infNFe->det[$i]->prod->comb->vBCICMSST     = $res_comb[$i]['vbcicmsst'];
                            $this->infNFe->det[$i]->prod->comb->vICMSST       = $res_comb[$i]['vicmsst'];
                            $this->infNFe->det[$i]->prod->comb->vBCICMSSTDest = $res_comb[$i]['vbcicmsstdest'];
                            $this->infNFe->det[$i]->prod->comb->vICMSSTDest   = $res_comb[$i]['vicmsstdest'];
                            $this->infNFe->det[$i]->prod->comb->vBCICMSSTCons = $res_comb[$i]['vbcicmsstcons'];
                            $this->infNFe->det[$i]->prod->comb->vICMSSTCons   = $res_comb[$i]['vicmsstcons'];
                            $this->infNFe->det[$i]->prod->comb->UFcons        = $res_comb[$i]['ufcons'];

                         }

                    } // fim prod

                    //imposto
                    $res_imposto = $con->queryAll('SELECT * FROM imposto WHERE det_id = '.$res_det[$i]['det_id'], null, MDB2_FETCHMODE_ASSOC);

                    //ICMS
                    $res_ICMS = $con->queryAll('SELECT * FROM ICMS WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_ICMS)) {
                        $this->infNFe->det[$i]->imposto->ICMS->orig      = $res_ICMS[$i]['orig'];
                        $this->infNFe->det[$i]->imposto->ICMS->CST       = $res_ICMS[$i]['cst'];
                        $this->infNFe->det[$i]->imposto->ICMS->modBC     = $res_ICMS[$i]['modbc'];
                        $this->infNFe->det[$i]->imposto->ICMS->pRedBC    = $res_ICMS[$i]['predbc'];
                        $this->infNFe->det[$i]->imposto->ICMS->vBC       = $res_ICMS[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->ICMS->pICMS     = $res_ICMS[$i]['picms'];
                        $this->infNFe->det[$i]->imposto->ICMS->vICMS     = $res_ICMS[$i]['vicms'];
                        $this->infNFe->det[$i]->imposto->ICMS->modBCST   = $res_ICMS[$i]['modbcst'];
                        $this->infNFe->det[$i]->imposto->ICMS->pMVAST    = $res_ICMS[$i]['pmvast'];
                        $this->infNFe->det[$i]->imposto->ICMS->pRedBCST  = $res_ICMS[$i]['predbcst'];
                        $this->infNFe->det[$i]->imposto->ICMS->vBCST     = $res_ICMS[$i]['vbcst'];
                        $this->infNFe->det[$i]->imposto->ICMS->pICMSST   = $res_ICMS[$i]['picmsst'];
                        $this->infNFe->det[$i]->imposto->ICMS->vICMSST   = $res_ICMS[$i]['vicmsst'];


                    }

                    //IPI
                    $res_IPI = $con->queryAll('SELECT * FROM IPI WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_IPI)) {
                        $this->infNFe->det[$i]->imposto->add_IPI(new IPI);
                        $this->infNFe->det[$i]->imposto->IPI->cIEnq    = $res_IPI[$i]['cienq'];
                        $this->infNFe->det[$i]->imposto->IPI->CNPJProd = $res_IPI[$i]['cnpjprod'];
                        $this->infNFe->det[$i]->imposto->IPI->cSelo    = $res_IPI[$i]['cselo'];
                        $this->infNFe->det[$i]->imposto->IPI->qSelo    = $res_IPI[$i]['qselo'];
                        $this->infNFe->det[$i]->imposto->IPI->cEnq     = $res_IPI[$i]['cenq'];
                        $this->infNFe->det[$i]->imposto->IPI->CST      = $res_IPI[$i]['cst'];
                        $this->infNFe->det[$i]->imposto->IPI->vBC      = $res_IPI[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->IPI->qUnid    = $res_IPI[$i]['qunid'];
                        $this->infNFe->det[$i]->imposto->IPI->vUnid    = $res_IPI[$i]['vunid'];
                        $this->infNFe->det[$i]->imposto->IPI->pIPI     = $res_IPI[$i]['pipi'];
                        $this->infNFe->det[$i]->imposto->IPI->vIPI     = $res_IPI[$i]['vipi'];
                    }

                    //II
                    $res_II = $con->queryAll('SELECT * FROM II WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_II)) {
                        $this->infNFe->det[$i]->imposto->add_II(new II);
                        $this->infNFe->det[$i]->imposto->II->vBC      = $res_II[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->II->vDespAdu = $res_II[$i]['vdespadu'];
                        $this->infNFe->det[$i]->imposto->II->vII      = $res_II[$i]['vii'];
                        $this->infNFe->det[$i]->imposto->II->vIOF     = $res_II[$i]['viof'];
                    }
 

                    //PIS
                    $res_PIS = $con->queryAll('SELECT * FROM PIS WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_PIS)) {
                        $this->infNFe->det[$i]->imposto->PIS->CST       = $res_PIS[$i]['cst'];
                        $this->infNFe->det[$i]->imposto->PIS->vBC       = $res_PIS[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->PIS->pPIS      = $res_PIS[$i]['ppis'];
                        $this->infNFe->det[$i]->imposto->PIS->vPIS      = $res_PIS[$i]['vpis'];
                        $this->infNFe->det[$i]->imposto->PIS->qBCProd   = $res_PIS[$i]['qbcprod'];
                        $this->infNFe->det[$i]->imposto->PIS->vAliqProd = $res_PIS[$i]['valiqprod'];
                    }

                    //PISST
                    $res_PISST = $con->queryAll('SELECT * FROM PISST WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_PISST)) {
                        $this->infNFe->det[$i]->imposto->add_PISST(new PISST);
                        $this->infNFe->det[$i]->imposto->PISST->vBC       = $res_PISST[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->PISST->pPIS      = $res_PISST[$i]['ppis'];
                        $this->infNFe->det[$i]->imposto->PISST->qBCProd   = $res_PISST[$i]['qbcprod'];
                        $this->infNFe->det[$i]->imposto->PISST->vAliqProd = $res_PISST[$i]['valiqprod'];
                        $this->infNFe->det[$i]->imposto->PISST->vPIS      = $res_PISST[$i]['vpis'];
                    }
                    
                    //COFINS
                    $res_COFINS = $con->queryAll('SELECT * FROM COFINS WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_COFINS)) {
                        $this->infNFe->det[$i]->imposto->COFINS->CST       = $res_COFINS[$i]['cst'];
                        $this->infNFe->det[$i]->imposto->COFINS->vBC       = $res_COFINS[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->COFINS->pCOFINS   = $res_COFINS[$i]['pcofins'];
                        $this->infNFe->det[$i]->imposto->COFINS->qBCProd   = $res_COFINS[$i]['qbcprod'];
                        $this->infNFe->det[$i]->imposto->COFINS->vAliqProd = $res_COFINS[$i]['valiqprod'];
                        $this->infNFe->det[$i]->imposto->COFINS->vCOFINS   = $res_COFINS[$i]['vcofins'];
                    }
                    
                    //COFINSST
                    $res_COFINSST = $con->queryAll('SELECT * FROM COFINSST WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_COFINSST)) {
                        $this->infNFe->det[$i]->imposto->add_COFINSST(new COFINSST);
                        $this->infNFe->det[$i]->imposto->COFINSST->vBC       = $res_COFINSST[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->COFINSST->pCOFINS   = $res_COFINSST[$i]['pcofins'];
                        $this->infNFe->det[$i]->imposto->COFINSST->qBCProd   = $res_COFINSST[$i]['qbcprod'];
                        $this->infNFe->det[$i]->imposto->COFINSST->vAliqProd = $res_COFINSST[$i]['valiqprod'];
                        $this->infNFe->det[$i]->imposto->COFINSST->vCOFINS   = $res_COFINSST[$i]['vcofins'];
                    }
                    
                    //ISSQN
                    $res_ISSQN = $con->queryAll('SELECT * FROM ISSQN WHERE imposto_id = '.$res_imposto[0]['imposto_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_ISSQN)) {
                        $this->infNFe->det[$i]->imposto->add_ISSQN(new ISSQN);
                        $this->infNFe->det[$i]->imposto->ISSQN->vBC       = $res_ISSQN[$i]['vbc'];
                        $this->infNFe->det[$i]->imposto->ISSQN->vAliq     = $res_ISSQN[$i]['valiq'];
                        $this->infNFe->det[$i]->imposto->ISSQN->vISSQN    = $res_ISSQN[$i]['vissqn'];
                        $this->infNFe->det[$i]->imposto->ISSQN->cMunFG    = $res_ISSQN[$i]['cmunfg'];
                        $this->infNFe->det[$i]->imposto->ISSQN->cListServ = $res_ISSQN[$i]['clistserv'];
                    }

                    //infAdProd
                    $res_infAdProd = $con->queryAll('SELECT * FROM infAdProd WHERE det_id = '.$res_det[$i]['det_id'], null, MDB2_FETCHMODE_ASSOC);
                    if (count($res_infAdProd)) {
                        $this->infNFe->det[$i]->add_infAdProd(new infAdProd);
                        $this->infNFe->det[$i]->infAdProd->infAdProd = $res_infAdProd[0]['infadprod'];
                    }
                }
            }

            //total
            $res_total = $con->queryAll('SELECT * FROM total WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);

            //enderEmit
            $res_enderEmit = $con->queryAll('SELECT * FROM enderEmit WHERE emit_id = '.$res_emit[0]['emit_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_enderEmit)) {
                $this->infNFe->emit->enderEmit->xLgr    = $res_enderEmit[0]['xlgr'];
                $this->infNFe->emit->enderEmit->nro     = $res_enderEmit[0]['nro'];
                $this->infNFe->emit->enderEmit->xCpl    = $res_enderEmit[0]['xcpl'];
                $this->infNFe->emit->enderEmit->xBairro = $res_enderEmit[0]['xbairro'];
                $this->infNFe->emit->enderEmit->cMun    = $res_enderEmit[0]['cmun'];
                $this->infNFe->emit->enderEmit->xMun    = $res_enderEmit[0]['xmun'];
                $this->infNFe->emit->enderEmit->UF      = $res_enderEmit[0]['uf'];
                $this->infNFe->emit->enderEmit->CEP     = $res_enderEmit[0]['cep'];
                $this->infNFe->emit->enderEmit->cPais   = $res_enderEmit[0]['cpais'];
                $this->infNFe->emit->enderEmit->xPais   = $res_enderEmit[0]['xpais'];
                $this->infNFe->emit->enderEmit->fone    = $res_enderEmit[0]['fone'];
            }

            //enderDest
            $res_enderDest = $con->queryAll('SELECT * FROM enderDest WHERE dest_id = '.$res_dest[0]['dest_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_enderDest)) {
                $this->infNFe->dest->enderDest->xLgr    = $res_enderDest[0]['xlgr'];
                $this->infNFe->dest->enderDest->nro     = $res_enderDest[0]['nro'];
                $this->infNFe->dest->enderDest->xCpl    = $res_enderDest[0]['xcpl'];
                $this->infNFe->dest->enderDest->xBairro = $res_enderDest[0]['xbairro'];
                $this->infNFe->dest->enderDest->cMun    = $res_enderDest[0]['cmun'];
                $this->infNFe->dest->enderDest->xMun    = $res_enderDest[0]['xmun'];
                $this->infNFe->dest->enderDest->UF      = $res_enderDest[0]['uf'];
                $this->infNFe->dest->enderDest->CEP     = $res_enderDest[0]['cep'];
                $this->infNFe->dest->enderDest->cPais   = $res_enderDest[0]['cpais'];
                $this->infNFe->dest->enderDest->xPais   = $res_enderDest[0]['xpais'];
                $this->infNFe->dest->enderDest->fone    = $res_enderDest[0]['fone'];
            }


            //ICMSTot
            $res_ICMSTot = $con->queryAll('SELECT * FROM ICMSTot WHERE total_id = '.$res_total[0]['total_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_ICMSTot)) {
                $this->infNFe->total->ICMSTot->vBC     = $res_ICMSTot[0]['vbc'];
                $this->infNFe->total->ICMSTot->vICMS   = $res_ICMSTot[0]['vicms'];
                $this->infNFe->total->ICMSTot->vBCST   = $res_ICMSTot[0]['vbcst'];
                $this->infNFe->total->ICMSTot->vST     = $res_ICMSTot[0]['vst'];
                $this->infNFe->total->ICMSTot->vProd   = $res_ICMSTot[0]['vprod'];
                $this->infNFe->total->ICMSTot->vFrete  = $res_ICMSTot[0]['vfrete'];
                $this->infNFe->total->ICMSTot->vSeg    = $res_ICMSTot[0]['vseg'];
                $this->infNFe->total->ICMSTot->vDesc   = $res_ICMSTot[0]['vdesc'];
                $this->infNFe->total->ICMSTot->vII     = $res_ICMSTot[0]['vii'];
                $this->infNFe->total->ICMSTot->vIPI    = $res_ICMSTot[0]['vipi'];
                $this->infNFe->total->ICMSTot->vPIS    = $res_ICMSTot[0]['vpis'];
                $this->infNFe->total->ICMSTot->vCOFINS = $res_ICMSTot[0]['vcofins'];
                $this->infNFe->total->ICMSTot->vOutro  = $res_ICMSTot[0]['voutro'];
                $this->infNFe->total->ICMSTot->vNF     = $res_ICMSTot[0]['vnf'];
            }

            //ISSQNtot
            $res_ISSQNtot = $con->queryAll('SELECT * FROM ISSQNtot WHERE total_id = '.$res_total[0]['total_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_ISSQNtot)) {
                $this->infNFe->total->ISSQNtot->vServ   = $res_ISSQNtot[0]['vserv'];
                $this->infNFe->total->ISSQNtot->vBC     = $res_ISSQNtot[0]['vbc'];
                $this->infNFe->total->ISSQNtot->vISS    = $res_ISSQNtot[0]['viss'];
                $this->infNFe->total->ISSQNtot->vPIS    = $res_ISSQNtot[0]['vpis'];
                $this->infNFe->total->ISSQNtot->vCOFINS = $res_ISSQNtot[0]['vcofins'];
             }

            //retTrib
            $res_retTrib = $con->queryAll('SELECT * FROM retTrib WHERE total_id = '.$res_total[0]['total_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_retTrib)) {
                $this->infNFe->total->retTrib->vRetPIS    = $res_retTrib[0]['vretpis'];
                $this->infNFe->total->retTrib->vRetCOFINS = $res_retTrib[0]['vretcofins'];
                $this->infNFe->total->retTrib->vRetCSLL   = $res_retTrib[0]['vretcsll'];
                $this->infNFe->total->retTrib->vBCIRRF    = $res_retTrib[0]['vbcirrf'];
                $this->infNFe->total->retTrib->vIRRF      = $res_retTrib[0]['virrf'];
                $this->infNFe->total->retTrib->vBCRetPrev = $res_retTrib[0]['vbcretprev'];
                $this->infNFe->total->retTrib->vRetPrev   = $res_retTrib[0]['vretprev'];
             }

            //transp
            $res_transp = $con->queryAll('SELECT * FROM transp WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_transp)) {
                $this->infNFe->transp->modFrete = $res_transp[0]['modfrete'];
            
                //transporta
                $res_transporta = $con->queryAll('SELECT * FROM transporta WHERE transp_id = '.$res_transp[0]['transp_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_transporta)) {
                    $this->infNFe->transp->add_transporta(new transporta);
                    $this->infNFe->transp->transporta->CNPJ   = $res_transporta[0]['cnpj'];
                    $this->infNFe->transp->transporta->CPF    = $res_transporta[0]['cpf'];
                    $this->infNFe->transp->transporta->xNome  = $res_transporta[0]['xnome'];
                    $this->infNFe->transp->transporta->IE     = $res_transporta[0]['ie'];
                    $this->infNFe->transp->transporta->xEnder = $res_transporta[0]['xender'];
                    $this->infNFe->transp->transporta->xMun   = $res_transporta[0]['xmun'];
                    $this->infNFe->transp->transporta->UF     = $res_transporta[0]['uf'];
                }

                //retTransp
                $res_retTransp = $con->queryAll('SELECT * FROM retTransp WHERE transp_id = '.$res_transp[0]['transp_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_retTransp)) {
                    $this->infNFe->transp->add_retTransp (new retTransp);
                    $this->infNFe->transp->retTransp->vServ    = $res_retTransp[0]['vserv'];
                    $this->infNFe->transp->retTransp->vBCRet   = $res_retTransp[0]['vbcret'];
                    $this->infNFe->transp->retTransp->pICMSRet = $res_retTransp[0]['picmsret'];
                    $this->infNFe->transp->retTransp->vICMSRet = $res_retTransp[0]['vicmsret'];
                    $this->infNFe->transp->retTransp->CFOP     = $res_retTransp[0]['cfop'];
                    $this->infNFe->transp->retTransp->cMunFG   = $res_retTransp[0]['cmunfg'];
                }

                //veicTransp
                $res_veicTransp = $con->queryAll('SELECT * FROM veicTransp WHERE transp_id = '.$res_transp[0]['transp_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_veicTransp)) {
                    $this->infNFe->transp->add_veicTransp (new veicTransp);
                    $this->infNFe->transp->veicTransp->placa = $res_veicTransp[0]['placa'];
                    $this->infNFe->transp->veicTransp->UF    = $res_veicTransp[0]['uf'];
                    $this->infNFe->transp->veicTransp->RNTC  = $res_veicTransp[0]['rntc'];
                }

                //reboque
                $res_reboque = $con->queryAll('SELECT * FROM reboque WHERE transp_id = '.$res_transp[0]['transp_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_reboque)) {
                    for ($cont_reb=0; $cont_reb<count($res_reboque); $cont_reb++) {
                        $this->infNFe->transp->add_reboque (new reboque);
                        $this->infNFe->transp->reboque[$cont_reb]->placa = $res_reboque[$cont_reb]['placa'];
                        $this->infNFe->transp->reboque[$cont_reb]->UF    = $res_reboque[$cont_reb]['uf'];
                        $this->infNFe->transp->reboque[$cont_reb]->RNTC  = $res_reboque[$cont_reb]['rntc'];
                    }
                }

                //vol
                $res_vol = $con->queryAll('SELECT * FROM vol WHERE transp_id = '.$res_transp[0]['transp_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_vol)) {
                    for ($y=0; $y<count($res_vol); $y++) {
                         $this->infNFe->transp->add_vol (new vol);
                         $this->infNFe->transp->vol[$y]->qVol  = $res_vol[$y]['qvol'];
                         $this->infNFe->transp->vol[$y]->esp   = $res_vol[$y]['esp'];
                         $this->infNFe->transp->vol[$y]->marca = $res_vol[$y]['marca'];
                         $this->infNFe->transp->vol[$y]->nVol  = $res_vol[$y]['nvol'];
                         $this->infNFe->transp->vol[$y]->pesoL = $res_vol[$y]['pesol'];
                         $this->infNFe->transp->vol[$y]->pesoB = $res_vol[$y]['pesob'];

                         //lacres
                         $res_lacres = $con->queryAll('SELECT * FROM lacres WHERE vol_id = '.$res_vol[$y]['vol_id'], null, MDB2_FETCHMODE_ASSOC);
                         if (count($res_lacres)) {
                             for ($w=0; $w<count($res_lacres); $w++) {
                                  $this->infNFe->transp->vol[$y]->add_lacres(new lacres);
                                  $this->infNFe->transp->vol[$y]->lacres[$w]->nLacre = $res_lacres[$w]['nlacre'];
                             }
                         }
                    }
                }

            }

            // cobr
            $res_cobr = $con->queryAll('SELECT * FROM cobr WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_cobr)) {
                $this->infNFe->add_cobr(new cobr);

                // fat
                $res_fat = $con->queryAll('SELECT * FROM fat WHERE cobr_id = '.$res_cobr[0]['cobr_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_fat)) {
                    $this->infNFe->cobr->add_fat(new fat);
                    $this->infNFe->cobr->fat->nFat  = $res_fat[0]['nfat'];
                    $this->infNFe->cobr->fat->vOrig = $res_fat[0]['vorig'];
                    $this->infNFe->cobr->fat->vDesc = $res_fat[0]['vdesc'];
                    $this->infNFe->cobr->fat->vLiq  = $res_fat[0]['vliq'];
                } 

                // dup
                $res_dup = $con->queryAll('SELECT * FROM dup WHERE cobr_id = '.$res_cobr[0]['cobr_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_dup)) {
                    for ($a=0;$a<count($res_dup); $a++) {
                        $this->infNFe->cobr->add_dup(new dup);
                        $this->infNFe->cobr->dup[$a]->nDup  = $res_dup[$a]['ndup'];
                        $this->infNFe->cobr->dup[$a]->dVenc = $res_dup[$a]['dvenc'];
                        $this->infNFe->cobr->dup[$a]->vDup  = $res_dup[$a]['vdup'];
                    }
                } 
            }

            //infAdic
            $res_infAdic = $con->queryAll('SELECT * FROM infAdic WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_infAdic)) {
                $this->infNFe->add_infAdic (new infAdic);
                $this->infNFe->infAdic->infAdFisco = $res_infAdic[0]['infadfisco'];
                $this->infNFe->infAdic->infCpl       = $res_infAdic[0]['infcpl'];

                //obsCont
                $res_obsCont = $con->queryAll('SELECT * FROM obsCont WHERE infAdic_id = '.$res_infAdic[0]['infadic_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_obsCont)) {
                    for ($b=0;$b<count($res_obsCont); $b++) {
                        $this->infNFe->infAdic->add_obsCont (new obsCont);
                        $this->infNFe->infAdic->obsCont[$b]->xCampo = $res_obsCont[$b]['xcampo'];
                        $this->infNFe->infAdic->obsCont[$b]->xTexto = $res_obsCont[$b]['xtexto'];
                    }
                }

                //obsFisco
                $res_obsFisco = $con->queryAll('SELECT * FROM obsFisco WHERE infAdic_id = '.$res_infAdic[0]['infadic_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_obsFisco)) {
                    for ($c=0;$c<count($res_obsFisco); $c++) {
                        $this->infNFe->infAdic->add_obsFisco (new obsFisco);
                        $this->infNFe->infAdic->obsFisco[$c]->xCampo = $res_obsFisco[$c]['xcampo'];
                        $this->infNFe->infAdic->obsFisco[$c]->xTexto = $res_obsFisco[$c]['xtexto'];
                    }
                }

                //procRef
                $res_procRef = $con->queryAll('SELECT * FROM procRef WHERE infAdic_id = '.$res_infAdic[0]['infadic_id'], null, MDB2_FETCHMODE_ASSOC);
                if (count($res_procRef)) {
                    for ($d=0;$d<count($res_procRef); $d++) {
                        $this->infNFe->infAdic->add_procRef (new procRef);
                        $this->infNFe->infAdic->procRef[$d]->nProc   = $res_procRef[$d]['nproc'];
                        $this->infNFe->infAdic->procRef[$d]->indProc = $res_procRef[$d]['indproc'];
                    }
                }
            }

            //exporta
            $res_exporta = $con->queryAll('SELECT * FROM exporta WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_exporta)) {
                $this->infNFe->add_exporta (new exporta);
                $this->infNFe->exporta->UFEmbarq   = $res_exporta[0]['ufembarq'];
                $this->infNFe->exporta->xLocEmbarq = $res_exporta[0]['xlocembarq'];
            } 

            //compra
            $res_compra = $con->queryAll('SELECT * FROM compra WHERE infNFe_id = '.$res_infNFe[0]['infnfe_id'], null, MDB2_FETCHMODE_ASSOC);
            if (count($res_compra)) {
                $this->infNFe->add_compra (new compra);
                $this->infNFe->compra->xNEmp = $res_compra[0]['xnemp'];
                $this->infNFe->compra->xPed  = $res_compra[0]['xped'];
                $this->infNFe->compra->xCont = $res_compra[0]['xcont'];
            } 
        } else {
            return false;
        }
    }

    function insere($con) {
        $sql = 'INSERT INTO NFe VALUES (NULL';
        $sql.= ', '.$con->quote($this->situacao->situacao_id);
        $sql.= ')';

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro NFe::insere: '.$qry->getMessage());
            return false;
        } else {
            $NFe_id = $con->lastInsertID("NFe", "NFe_id");
            $this->infNFe->insere($con, $NFe_id);
            return $NFe_id;
        }
    }

    // demais tabelas terão registros apagados ON CASCADE
    function remove($con, $NFe_id) {
        $sql = 'DELETE FROM NFe WHERE NFe_id = '.$con->quote($NFe_id);
        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro NFe::remove: '.$qry->getMessage());
            return false;
        } else {
            return true;
        }
    }
}
