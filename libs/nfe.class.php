<?
/**
 * NF-e - Nota Fiscal eletrônica
 * Classes para geração da NF-e e gravação em DB
 * NFe layout 1.10
 *
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 * @date    27/06/2009
 */


/**
 * O objeto $con a ser passado para NFe é um objeto MDB2 do PEAR
 *  $dsn = array (
 *      'phptype'  => 'mysql',
 *      'username' => 'usuario',
 *      'password' => 'senha',
 *      'hostspec' => '192.168.0.1',
 *      'database' => 'banco_dados'
 *  );
 *  $con =& MDB2::connect($dsn);
 *
 * OBS: o fetchRow do MDB2 retorna o array associativo em lowercase
 */


/**
 * set_error()
 * Você pode definir sua função para tratamento de erros
 */
if (!function_exists('set_error')) {
    $_ERROR = array();
    function set_error($err_msg) {
        global $_ERROR;
        return $_ERROR[] = $err_msg;
    }
}


/* NIVEL 0 ********************************************************************/

include_once('classNFEtools.php');
class situacao {

    var $situacao_id;
    var $descricao;

    function __construct() {
        global $con;
        $this->fetch($con, 5); // 5 = em digitação
    }

    function fetch($con, $situacao_id) {
        $sql = "SELECT * FROM situacao WHERE situacao_id = ".$situacao_id;
        $qry = $con->query($sql);
        if (!MDB2::isError($qry)) {
            $row = $qry->fetchRow(MDB2_FETCHMODE_ASSOC);
            $this->situacao_id = $row['situacao_id'];
            $this->descricao   = $row['descricao'];
        }
    }
}

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



/* NIVEL 1 ********************************************************************/

// A01
class infNFe {

    var $versao;        // A02 - versão do leiaute
    var $Id;            // A03 - identificador da TAG a ser assinada
    var $ide;           // B01 - grupo das informações de identificação da NFe
    var $emit;          // C01 - grupo de identificação do emitente da NFe
    var $avulsa;        // D01 - informações do fisco emitente
    var $dest;          // E01 - grupo de identificação do destinatário da NFe
    var $retirada;      // F01 - grupo de identificação do local de retirada
    var $entrega;       // G01 - grupo de identificação do local de entrega
    var $det;           // H01 - grupo do detalhamento de prod. e serv. da NFe
    var $total;         // W01 - grupo de valores totais da NFe
    var $transp;        // X01 - grupo de informação do transporte da NFe
    var $cobr;          // Y01 - grupo de cobrança
    var $infAdic;       // Z01 - grupo de informações adicionais
    var $exporta;       // ZA01- grupo de exportação
    var $compra;        // ZB01- grupo de compra
    var $Signature;     // ZC01- assinatura XML da NFe segundo padrão digital

    function __construct() {
        $this->versao       = '1.10';
        $this->ide          = new ide;
        $this->emit         = new emit;
        $this->avulsa       = null;
        $this->dest         = new dest;
        $this->retirada     = null;
        $this->entrega      = null;
        $this->det          = array();
        $this->total        = new total;
        $this->transp       = new transp;
        $this->cobr         = null;
        $this->infAdic      = null;
        $this->exporta      = null;
        $this->compra       = null;
        $this->Signature    = new Signature;
    }

    function add_avulsa($obj_avulsa) {
        if (!$this->avulsa) {
            $this->avulsa = $obj_avulsa;
            return true;
        } else {
            return false;
        }
    }

    function add_retirada($obj_retirada) {
        if (!$this->retirada) {
            $this->retirada = $obj_retirada;
            return true;
        } else {
            return false;
        }
    }

    function add_entrega($obj_entrega) {
        if (!$this->entrega) {
            $this->entrega = $obj_entrega;
            return true;
        } else {
            return false;
        }
    }

    function add_det($obj_det) {
        $this->det[] = $obj_det;
        return true;
    }

    function add_cobr($obj_cobr) {
        if (!$this->cobr) {
            $this->cobr = $obj_cobr;
            return true;
        } else {
            return false;
        }
    }

    function add_infAdic($obj_infAdic) {
        if (!$this->infAdic) {
            $this->infAdic = $obj_infAdic;
            return true;
        } else {
            return false;
        }
    }

    function add_exporta($obj_exporta) {
        if (!$this->exporta) {
            $this->exporta = $obj_exporta;
            return true;
        } else {
            return false;
        }
    }

    function add_compra($obj_compra) {
        if (!$this->compra) {
            $this->compra = $obj_compra;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calcula digito verificador para chave de acesso de 43 dígitos
     * conforme manual, pág. 72
     */
    function calcula_dv($chave43) {
        $multiplicadores = array(2,3,4,5,6,7,8,9);
        $i = 42;
        while ($i >= 0) {
            for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
                $i--;
            }
        }
        $resto = $soma_ponderada % 11;
        if ($resto == '0' || $resto == '1') {
            $this->ide->cDV = 0;
        } else {
            $this->ide->cDV = 11 - $resto;
        }
        return $this->ide->cDV;
    }

    function get_chave_acesso() {

        // 02 - cUF  - código da UF do emitente do Documento Fiscal
        $chave = sprintf("%02d", $this->ide->cUF);

        // 04 - AAMM - Ano e Mes de emissão da NF-e
        $chave.= sprintf("%04d", substr($this->ide->dEmi, 2, 2).substr($this->ide->dEmi, 5, 2));

        // 14 - CNPJ - CNPJ do emitente
        $chave.= sprintf("%014s", $this->emit->CNPJ);

        // 02 - mod  - Modelo do Documento Fiscal
        $chave.= sprintf("%02d", $this->ide->mod);

        // 03 - serie - Série do Documento Fiscal
        $chave.= sprintf("%03d", $this->ide->serie);

        // 09 - nNF  - Número do Documento Fiscal
        $chave.= sprintf("%09d", $this->ide->nNF);

        // 09 - cNF  - Código Numérico que compõe a Chave de Acesso
        $chave.= sprintf("%09d", $this->ide->cNF);

        // 01 - cDV  - Dígito Verificador da Chave de Acesso
        $chave.= $this->calcula_dv($chave);

        return $chave;
    }

    function get_xml($dom) {
        $A01 = $dom->appendChild($dom->createElement('infNFe'));
        $A02 = $A01->appendChild($dom->createAttribute('versao'));
               $A02->appendChild($dom->createTextNode($this->versao));
        $A03 = $A01->appendChild($dom->createAttribute('Id'));
               $A03->appendChild($dom->createTextNode($this->Id = "NFe".$this->get_chave_acesso()));

        $B01 = $A01->appendChild($this->ide->get_xml($dom));
        $C01 = $A01->appendChild($this->emit->get_xml($dom));
        $D01 = (is_object($this->avulsa))   ? $A01->appendChild($this->avulsa->get_xml($dom))   : null;
        $E01 = $A01->appendChild($this->dest->get_xml($dom));
        $F01 = (is_object($this->retirada)) ? $A01->appendChild($this->retirada->get_xml($dom)) : null;
        $G01 = (is_object($this->entrega))  ? $A01->appendChild($this->entrega->get_xml($dom))  : null;
        for ($i=0; $i<count($this->det); $i++) {
            $H01 = $A01->appendChild($this->det[$i]->get_xml($dom));
        }
        $W01 = $A01->appendChild($this->total->get_xml($dom));
        $X01 = $A01->appendChild($this->transp->get_xml($dom));
        $Y01 = (is_object($this->cobr))     ? $A01->appendChild($this->cobr->get_xml($dom))     : null;
        $Z01 = (is_object($this->infAdic))  ? $A01->appendChild($this->infAdic->get_xml($dom))  : null;
        $ZA01= (is_object($this->exporta))  ? $A01->appendChild($this->exporta->get_xml($dom))  : null;
        $ZB01= (is_object($this->compra))   ? $A01->appendChild($this->compra->get_xml($dom))   : null;
        // BUG: assinado posteriormente por NFe_utils
        //$ZC01= (is_object($this->Signature) ? $A01->appendChild($this->Signature->get_xml($dom)) : null;
        return $A01;
    }

    function insere($con, $NFe_id) {
        $sql = "INSERT INTO infNFe VALUES (NULL";
        $sql.= ", ".$con->quote($NFe_id);
        $sql.= ", ".$con->quote($this->versao);
        $sql.= ", ".$con->quote($this->Id = $this->get_chave_acesso());
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro infNFe: '.$qry->getMessage());
            return false;
        } else {
            $infNFe_id = $con->lastInsertID("infNFe", "infNFe_id");

            $this->ide->insere($con, $infNFe_id);
            $this->emit->insere($con, $infNFe_id);
            (is_object($this->avulsa)) ? $this->avulsa->insere($con, $infNFe_id) : null;
            $this->dest->insere($con, $infNFe_id);
            (is_object($this->retirada)) ? $this->retirada->insere($con, $infNFe_id) : null;
            (is_object($this->entrega)) ? $this->entrega->insere($con, $infNFe_id) : null;
            for ($i=0; $i<count($this->det); $i++) {
                $this->det[$i]->insere($con, $infNFe_id);
            }
            $this->total->insere($con, $infNFe_id);
            $this->transp->insere($con, $infNFe_id);
            (is_object($this->cobr)) ? $this->cobr->insere($con, $infNFe_id) : null;
            (is_object($this->infAdic)) ? $this->infAdic->insere($con, $infNFe_id) : null;
            (is_object($this->exporta)) ? $this->exporta->insere($con, $infNFe_id) : null;
            (is_object($this->compra)) ? $this->compra->insere($con, $infNFe_id) : null;
        }
    }
}



/* NIVEL 2 ********************************************************************/

// B01
class ide {

    var $cUF;       // B02 - código da UF do emitente
    var $cNF;       // B03 - código numérico que compõe a chave de acesso;
    var $natOp;     // B04 - descrição da natureza da operação
    var $indPag;    // B05 - indicador da forma de pagamento
    var $mod;       // B06 - código do modelo do documento fiscal
    var $serie;     // B07 - série do documento fiscal
    var $nNF;       // B08 - número do documento fiscal
    var $dEmi;      // B09 - data de emissão do documento fiscal
    var $dSaiEnt;   // B10 - data da saída ou da entrada da mercadoria/produto
    var $tpNF;      // B11 - tipo do documento fiscal (0-entrada / 1-saida)
    var $cMunFG;    // B12 - código do município de ocorrência do fato gerador
    var $NFref;     // B12a- informação das NF/NFe referenciadas
    var $tpImp;     // B21 - formato de impressão do DANFE
    var $tpEmis;    // B22 - forma de emissão da NFe
    var $cDV;       // B23 - dígito verificador da chave de acesso
    var $tpAmb;     // B24 - identificação do ambiente
    var $finNFe;    // B25 - finalidade de emissão da NFe
    var $procEmi;   // B26 - processo de emissão da NFe
    var $verProc;   // B27 - versão do processo de emissão da NFe

    function __construct() {
        $this->mod      = 55;               // NFe
        $this->NFref    = array();
        $this->procEmi  = 0;                // emissão de NFe com aplicativo do contribuinte

    }

    // NFe ou NF
    function add_NFref($obj_NFref) {
        $this->NFref[] = $obj_NFref;
        return true;
    }

    function get_xml($dom) {
        $B01 = $dom->appendChild($dom->createElement('ide'));
        $B02 = $B01->appendChild($dom->createElement('cUF',     $this->cUF));
        $B03 = $B01->appendChild($dom->createElement('cNF',     sprintf("%09d", $this->cNF)));
        $B04 = $B01->appendChild($dom->createElement('natOp',   $this->natOp));
        $B05 = $B01->appendChild($dom->createElement('indPag',  $this->indPag));
        $B06 = $B01->appendChild($dom->createElement('mod',     $this->mod));
        $B07 = $B01->appendChild($dom->createElement('serie',   $this->serie));
        $B08 = $B01->appendChild($dom->createElement('nNF',     $this->nNF));
        $B09 = $B01->appendChild($dom->createElement('dEmi',    $this->dEmi));
        $B10 = (!empty($this->dSaiEnt)) ? $B01->appendChild($dom->createElement('dSaiEnt', $this->dSaiEnt)) : '';
        $B11 = $B01->appendChild($dom->createElement('tpNF',    $this->tpNF));
        $B12 = $B01->appendChild($dom->createElement('cMunFG',  $this->cMunFG));
        for ($i=0; $i<count($this->NFref); $i++) {
            $B12a= $B01->appendChild($this->NFref[$i]->get_xml($dom));
        }
        $B21 = $B01->appendChild($dom->createElement('tpImp',   $this->tpImp));
        $B22 = $B01->appendChild($dom->createElement('tpEmis',  $this->tpEmis));
        $B23 = $B01->appendChild($dom->createElement('cDV',     $this->cDV));
        $B24 = $B01->appendChild($dom->createElement('tpAmb',   $this->tpAmb));
        $B25 = $B01->appendChild($dom->createElement('finNFe',  $this->finNFe));
        $B26 = $B01->appendChild($dom->createElement('procEmi', $this->procEmi));
        $B27 = $B01->appendChild($dom->createElement('verProc', $this->verProc));
        return $B01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO ide VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->cUF);
        $sql.= ", ".$con->quote($this->cNF);
        $sql.= ", ".$con->quote($this->natOp);
        $sql.= ", ".$con->quote($this->indPag);
        $sql.= ", ".$con->quote($this->mod);
        $sql.= ", ".$con->quote($this->serie);
        $sql.= ", ".$con->quote($this->nNF);
        $sql.= ", ".$con->quote($this->dEmi);
        $sql.= ", ".$con->quote($this->dSaiEnt);
        $sql.= ", ".$con->quote($this->tpNF);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ", ".$con->quote($this->tpImp);
        $sql.= ", ".$con->quote($this->tpEmis);
        $sql.= ", ".$con->quote($this->cDV);
        $sql.= ", ".$con->quote($this->tpAmb);
        $sql.= ", ".$con->quote($this->finNFe);
        $sql.= ", ".$con->quote($this->procEmi);
        $sql.= ", ".$con->quote($this->verProc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ide: '.$qry->getMessage());
            return false;
        } else {
            $ide_id = $con->lastInsertID("ide", "ide_id");
            for ($i=0; $i<count($this->NFref); $i++) {
                $this->NFref[$i]->insere($con, $ide_id);
            }
        }
    }
}

// C01
class emit {

    var $CNPJ;      // C02 - CNPJ do emitente
    var $CPF;       // C02a- CPF do remetente
    var $xNome;     // C03 - razão social ou nome do emitente
    var $xFant;     // C04 - nome fantasia
    var $enderEmit; // C05 - grupo do endereço do emitente
    var $IE;        // C17 - IE
    var $IEST;      // C18 - IE do substituto tributário
    var $IM;        // C19 - Inscrição Municipal
    var $CNAE;      // C20 - CNAE fiscal

    function __construct() {
        $this->enderEmit = new enderEmit;
    }

    function get_xml($dom) {
        $C01 = $dom->appendChild($dom->createElement('emit'));
        $C02 = (empty($this->CPF)) ? $C01->appendChild($dom->createElement('CNPJ', sprintf("%014s", $this->CNPJ))) : $C01->appendChild($dom->createElement('CPF', sprintf("%011s", $this->CPF)));
        //C02a - ou exclusivo com C02
        $C03 = $C01->appendChild($dom->createElement('xNome',       $this->xNome));
        $C04 = $C01->appendChild($dom->createElement('xFant',       $this->xFant));
        $C05 = $C01->appendChild($this->enderEmit->get_xml($dom));
        $C17 = $C01->appendChild($dom->createElement('IE',          $this->IE));
        $C18 = (!empty($this->IEST)) ? $C01->appendChild($dom->createElement('IEST',    $this->IEST)) : '';
        $C19 = (!empty($this->IM)) ? $C01->appendChild($dom->createElement('IM',        $this->IM)) : '';
        $C20 = (!empty($this->CNAE) && !empty($this->IM)) ? $C01->appendChild($dom->createElement('CNAE',    $this->CNAE)) : '';
        return $C01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO emit VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->CPF);
        $sql.= ", ".$con->quote($this->xNome);
        $sql.= ", ".$con->quote($this->xFant);
        $sql.= ", ".$con->quote($this->IE);
        $sql.= ", ".$con->quote($this->IEST);
        $sql.= ", ".$con->quote($this->IM);
        $sql.= ", ".$con->quote($this->CNAE);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro emit: '.$qry->getMessage());
            return false;
        } else {
            $emit_id = $con->lastInsertID("emit", "emit_id");
            $this->enderEmit->insere($con, $emit_id);
        }
    }
}

// D01
class avulsa {

    var $CNPJ;      // D02 - CNPJ do órgão emitente
    var $xOrgao;    // D03 - órgão emitente
    var $matr;      // D04 - matrícula do agente
    var $xAgente;   // D05 - nome do agente
    var $fone;      // D06 - telefone
    var $UF;        // D07 - sigla da UF
    var $nDAR;      // D08 - número do documento de arrecadação de receita
    var $dEmi;      // D09 - data de emissão do documento de arrecadação
    var $vDAR;      // D10 - valor total no documento de arrecadação de receita
    var $repEmi;    // D11 - repartição fiscal emitente
    var $dPag;      // D12 - data de pagamento do documento de arrecadação

    function __construct() {
    }

    function get_xml($dom) {
        $D01 = $dom->appendChild($dom->createElement('avulsa'));
        $D02 = $D01->appendChild($dom->createElement('CNPJ',    sprintf("%014s", $this->CNPJ)));
        $D03 = $D01->appendChild($dom->createElement('xOrgao',  $this->xOrgao));
        $D04 = $D01->appendChild($dom->createElement('matr',    $this->matr));
        $D05 = $D01->appendChild($dom->createElement('xAgente', $this->xAgente));
        $D06 = $D01->appendChild($dom->createElement('fone',    $this->fone));
        $D07 = $D01->appendChild($dom->createElement('UF',      $this->UF));
        $D08 = $D01->appendChild($dom->createElement('nDAR',    $this->nDAR));
        $D09 = $D01->appendChild($dom->createElement('dEmi',    $this->dEmi));
        $D10 = $D01->appendChild($dom->createElement('vDAR',    number_format($this->vDAR, 2, ".", "")));
        $D11 = $D01->appendChild($dom->createElement('repEmi',  $this->repEmi));
        $D12 = (!empty($this->dPag)) ? $D01->appendChild($dom->createElement('dPag', $this->dPag)) : '';
        return $D01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO avulsa VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->xOrgao);
        $sql.= ", ".$con->quote($this->matr);
        $sql.= ", ".$con->quote($this->xAgente);
        $sql.= ", ".$con->quote($this->fone);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->nDAR);
        $sql.= ", ".$con->quote($this->dEmi);
        $sql.= ", ".$con->quote($this->vDAR);
        $sql.= ", ".$con->quote($this->repEmi);
        $sql.= ", ".$con->quote($this->dPag);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro avulsa: '.$qry->getMessage());
            return false;
        } else {
            $avulsa_id = $con->lastInsertID("avulsa", "avulsa_id");
        }
    }
}

// E01
class dest {

    var $CNPJ;      // E02 - CNPJ do emitente
    var $CPF;       // E02a- CPF do remetente
    var $xNome;     // E03 - razão social ou nome do emitente
    var $enderDest; // E05 - grupo do endereço do emitente
    var $IE;        // E17 - IE
    var $ISUF;      // E18 - Inscrição na SUFRAMA

    function __construct() {
        $this->enderDest = new enderDest;
    }

    function get_xml($dom) {
        $E01 = $dom->appendChild($dom->createElement('dest'));
        $E02 = (empty($this->CPF)) ? $E01->appendChild($dom->createElement('CNPJ', sprintf("%014s", $this->CNPJ))) : $E01->appendChild($dom->createElement('CPF', sprintf("%011s", $this->CPF)));
        //E03 - ou exclusivo com E02
        $E04 = $E01->appendChild($dom->createElement('xNome',       $this->xNome));
        $E05 = $E01->appendChild($this->enderDest->get_xml($dom));
        $E17 = $E01->appendChild($dom->createElement('IE',          $this->IE));
        $E18 = (!empty($this->ISUF)) ? $E01->appendChild($dom->createElement('ISUF',    $this->ISUF)) : '';
        return $E01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO dest VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->CPF);
        $sql.= ", ".$con->quote($this->xNome);
        $sql.= ", ".$con->quote($this->IE);
        $sql.= ", ".$con->quote($this->ISUF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro dest: '.$qry->getMessage());
            return false;
        } else {
            $dest_id = $con->lastInsertID("dest", "dest_id");
            $this->enderDest->insere($con, $dest_id);
        }
    }
}

// F01
class retirada {

    var $CNPJ;      // F02 - CNPJ do local da retirada
    var $xLgr;      // F03 - logradouro do local da retirada
    var $nro;       // F04 - número do local da retirada
    var $xCpl;      // F05 - complemento do local da retirada
    var $xBairro;   // F06 - bairro do local da retirada
    var $cMun;      // F07 - código do município do local da retirada
    var $xMun;      // F08 - nome do município do local da retirada
    var $UF;        // F09 - sigla da UF do local da retirada

    function __construct() {
    }

    function get_xml($dom) {
        $F01 = $dom->appendChild($dom->createElement('retirada'));
        $F02 = $F01->appendChild($dom->createElement('CNPJ',       sprintf("%014s", $this->CNPJ)));
        $F03 = $F01->appendChild($dom->createElement('xLgr',       $this->xLgr));
        $F04 = $F01->appendChild($dom->createElement('nro',        $this->nro));
        $F05 = (!empty($this->xCpl)) ? $F01->appendChild($dom->createElement('xCpl',       $this->xCpl)) : '';
        $F06 = $F01->appendChild($dom->createElement('xBairro',    $this->xBairro));
        $F07 = $F01->appendChild($dom->createElement('cMun',       $this->cMun));
        $F08 = $F01->appendChild($dom->createElement('xMun',       $this->xMun));
        $F09 = $F01->appendChild($dom->createElement('UF',         $this->UF));
        return $F01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO retirada VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro retirada: '.$qry->getMessage());
            return false;
        } else {
            $retirada_id = $con->lastInsertID("retirada", "retirada_id");
        }
    }
}

// G01
class entrega {

    var $CNPJ;      // G02 - CNPJ  do local da entrega
    var $xLgr;      // G03 - logradouro do local da entrega
    var $nro;       // G04 - número do local da entrega
    var $xCpl;      // G05 - complemento do local da entrega
    var $xBairro;   // G06 - bairro do local da entrega
    var $cMun;      // G07 - código do município do local da entrega
    var $xMun;      // G08 - nome do município do local da entrega
    var $UF;        // G09 - sigla da UF do local da entrega

    function __construct() {
    }

    function get_xml($dom) {
        $G01 = $dom->appendChild($dom->createElement('entrega'));
        $G02 = $G01->appendChild($dom->createElement('CNPJ',       sprintf("%014s", $this->CNPJ)));
        $G03 = $G01->appendChild($dom->createElement('xLgr',       $this->xLgr));
        $G04 = $G01->appendChild($dom->createElement('nro',        $this->nro));
        $G05 = (!empty($this->xCpl)) ? $G01->appendChild($dom->createElement('xCpl',       $this->xCpl)) : '';
        $G06 = $G01->appendChild($dom->createElement('xBairro',    $this->xBairro));
        $G07 = $G01->appendChild($dom->createElement('cMun',       $this->cMun));
        $G08 = $G01->appendChild($dom->createElement('xMun',       $this->xMun));
        $G09 = $G01->appendChild($dom->createElement('UF',         $this->UF));
        return $G01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO entrega VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro entrega: '.$qry->getMessage());
            return false;
        } else {
            $entrega_id = $con->lastInsertID("entrega", "entrega_id");
        }
    }
}

// H01
class det {

    var $nItem;     // H02 - número do item
    var $prod;      // I01 - grupo do detalhamento de prod e serv da NFe
    var $imposto;   // M01 - grupo de tributos incidentes no produto ou serviço
    var $infAdProd; // V01 - informações adicionais do produto

    function __construct() {
        $this->prod         = new prod;
        $this->imposto      = new imposto;
        $this->infAdProd    = null;
    }

    function add_infAdProd($obj_infAdProd) {
        if (!$this->infAdProd) {
            $this->infAdProd = $obj_infAdProd;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $H01 = $dom->appendChild($dom->createElement('det'));
        $H02 = $H01->appendChild($dom->createAttribute('nItem'));
               $H02->appendChild($dom->createTextNode($this->nItem));
        $I01 = $H01->appendChild($this->prod->get_xml($dom));
        $M01 = $H01->appendChild($this->imposto->get_xml($dom));
        $V01 = (is_object($this->infAdProd)) ? $H01->appendChild($this->infAdProd->get_xml($dom)) : null;
        return $H01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO det VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->nItem);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro det: '.$qry->getMessage());
            return false;
        } else {
            $det_id = $con->lastInsertID("det", "det_id");
            $this->prod->insere($con, $det_id);
            $this->imposto->insere($con, $det_id);
            (is_object($this->infAdProd)) ? $this->infAdProd->insere($con, $det_id) : null;
        }
    }
}

// W01
class total {

    var $ICMSTot;   // W02 - grupo de valores totais referentes ao ICMS
    var $ISSQNtot;  // W17 - grupo de valores totais referentes ao ISSQN
    var $retTrib;   // W23 - grupo de retenções de tributos

    function __construct() {
        $this->ICMSTot  = new ICMSTot;
        $this->ISSQNtot = null;
        $this->retTrib  = null;
    }

    function add_ISSQNtot($obj_ISSQNtot) {
        if (!$this->ISSQNtot) {
            $this->ISSQNtot = $obj_ISSQNtot;
            return true;
        } else {
            return false;
        }
    }

    function add_retTrib($obj_retTrib) {
        if (!$this->retTrib) {
            $this->retTrib = $obj_retTrib;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $W01 = $dom->appendChild($dom->createElement('total'));
        $W02 = $W01->appendChild($this->ICMSTot->get_xml($dom));
        $W17 = (is_object($this->ISSQNtot)) ? $W01->appendChild($this->ISSQNtot->get_xml($dom)) : null;
        $W23 = (is_object($this->retTrib)) ? $W01->appendChild($this->retTrib->get_xml($dom)) : null;
        return $W01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO total VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro total: '.$qry->getMessage());
            return false;
        } else {
            $total_id = $con->lastInsertID("total", "total_id");
            $this->ICMSTot->insere($con, $total_id);
            (is_object($this->ISSQNtot)) ? $this->ISSQNtot->insere($con, $total_id) : null;
            (is_object($this->retTrib)) ? $this->retTrib->insere($con, $total_id) : null;
        }
    }
}

// X01
class transp {

    var $modFrete;      // X02 - modalidade do frete
    var $transporta;    // X03 - grupo transportador
    var $retTransp;     // X11 - grupo de retenção do ICMS do transporte
    var $veicTransp;    // X18 - grupo veículo
    var $reboque;       // X22 - grupo reboque
    var $vol;           // X26 - grupo volumes

    function __construct() {
        $this->transporta   = null;
        $this->retTransp    = null;
        $this->veicTransp   = null;
        $this->reboque      = array();
        $this->vol          = array();
    }

    function add_transporta($obj_transporta) {
        if (!$this->transporta) {
            $this->transporta = $obj_transporta;
            return true;
        } else {
            return false;
        }
    }
    
    function add_retTransp($obj_retTransp) {
        if (!$this->retTransp) {
            $this->retTransp = $obj_retTransp;
            return true;
        } else {
            return false;
        }
    }
    
    function add_veicTransp($obj_veicTransp) {
        if (!$this->veicTransp) {
            $this->veicTransp = $obj_veicTransp;
            return true;
        } else {
            return false;
        }
    }
    
    function add_reboque($obj_reboque) {
        if (count($this->reboque) < 2) {
            $this->reboque[] = $obj_reboque;
            return true;
        } else {
            return false;
        }
    }

    function add_vol($obj_vol) {
        $this->vol[] = $obj_vol;
        return true;
    }

    function get_xml($dom) {
        $X01 = $dom->appendChild($dom->createElement('transp'));
        $X02 = $X01->appendChild($dom->createElement('modFrete', $this->modFrete));
        $X03 = (is_object($this->transporta)) ? $X01->appendChild($this->transporta->get_xml($dom)) : null;
        $X11 = (is_object($this->retTransp))  ? $X01->appendChild($this->retTransp->get_xml($dom))  : null;
        $X18 = (is_object($this->veicTransp)) ? $X01->appendChild($this->veicTransp->get_xml($dom)) : null;
        for ($i=0; $i<count($this->reboque); $i++) {
            $X22 = $X01->appendChild($this->reboque[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->vol); $i++) {
            $X26 = $X01->appendChild($this->vol[$i]->get_xml($dom));
        }
        return $X01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO transp VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->modFrete);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro transp: '.$qry->getMessage());
            return false;
        } else {
            $transp_id = $con->lastInsertID("transp", "transp_id");
            (is_object($this->transporta)) ? $this->transporta->insere($con, $transp_id) : null;
            (is_object($this->retTransp)) ? $this->retTransp->insere($con, $transp_id) : null;
            (is_object($this->veicTransp)) ? $this->veicTransp->insere($con, $transp_id) : null;
            for ($i=0; $i<count($this->reboque); $i++) {
                $this->reboque[$i]->insere($con, $transp_id);
            }
            for ($i=0; $i<count($this->vol); $i++) {
                $this->vol[$i]->insere($con, $transp_id);
            }
        }
    }
}

// Y01
class cobr {

    var $fat;       // Y02 - grupo de fatura
    var $dup;       // Y07 - grupo de duplicata

    function __construct() {
        $this->fat = array();
        $this->dup = array();
    }

    function add_fat($obj_fat) {
        $this->fat = $obj_fat;
        return true;
    }

    function add_dup($obj_dup) {
        $this->dup[] = $obj_dup;
        return true;
    }

    function get_xml($dom) {
        $Y01 = $dom->appendChild($dom->createElement('cobr'));
        $Y02 = (is_object($this->fat)) ? $Y01->appendChild($this->fat->get_xml($dom)) : null;
        for ($i=0; $i<count($this->dup); $i++) {
            $Y07 = $Y01->appendChild($this->dup[$i]->get_xml($dom));
        }
        return $Y01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO cobr VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro cobr: '.$qry->getMessage());
            return false;
        } else {
            $cobr_id = $con->lastInsertID("cobr", "cobr_id");
            (is_object($this->fat)) ? $this->fat->insere($con, $cobr_id) : null;
            for ($i=0; $i<count($this->dup); $i++) {
                $this->dup[$i]->insere($con, $cobr_id);
            }
        }
    }
}

// Z01
class infAdic {

    var $infAdFisco;    // Z02 - informações adicionais de interesse do fisco
    var $infCpl;        // Z03 - informações de interesse do contribuinte
    var $obsCont;       // Z04 - grupo de campo de uso livre do contribuinte
    var $obsFisco;      // Z07 - grupo de campo de uso livre do fisco
    var $procRef;       // Z10 - grupo do processo

    function __construct() {
        $this->obsCont  = array();
        $this->obsFisco = array();
        $this->procRef  = array();
    }

    function add_obsCont($obj_obsCont) {
        if (count($this->obsCont) < 10) {
            $this->obsCont[] = $obj_obsCont;
            return true;
        } else {
            return false;
        }
    }

    function add_obsFisco($obj_obsFisco) {
        if (count($this->obsFisco) < 10) {
            $this->obsFisco[] = $obj_obsFisco;
            return true;
        } else {
            return false;
        }
    }

    function add_procRef($obj_procRef) {
        $this->procRef[] = $obj_procRef;
        return true;
    }

    function get_xml($dom) {
        $Z01 = $dom->appendChild($dom->createElement('infAdic'));
        $Z02 = (!empty($this->infAdFisco))  ? $Z01->appendChild($dom->createElement('infAdFisco',  $this->infAdFisco)) : null;
        $Z03 = (!empty($this->infCpl))      ? $Z01->appendChild($dom->createElement('infCpl',      $this->infCpl))     : null;
        for ($i=0; $i<count($this->obsCont); $i++) {
            $Z04 = $Z01->appendChild($this->obsCont[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->obsFisco); $i++) {
            $Z07 = $Z01->appendChild($this->obsFisco[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->procRef); $i++) {
            $Z10 = $Z01->appendChild($this->procRef[$i]->get_xml($dom));
        }
        return $Z01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO infAdic VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->infAdFisco);
        $sql.= ", ".$con->quote($this->infCpl);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro infAdic: '.$qry->getMessage());
            return false;
        } else {
            $infAdic_id = $con->lastInsertID("infAdic", "infAdic_id");

            for ($i=0; $i<count($this->obsCont); $i++) {
                $this->obsCont[$i]->insere($con, $infAdic_id);
            }
            for ($i=0; $i<count($this->obsFisco); $i++) {
                $this->obsFisco[$i]->insere($con, $infAdic_id);
            }
            for ($i=0; $i<count($this->procRef); $i++) {
                $this->procRef[$i]->insere($con, $infAdic_id);
            }
        }
    }
}

// ZA01
class exporta {

    var $UFEmbarq;      // ZA02 - sigla da UF do embarque dos produtos
    var $xLocEmbarq;    // ZA03 - local onde ocorrerá o embarque dos produtos

    function __construct() {
    }

    function get_xml($dom) {
        $ZA01 = $dom->appendChild($dom->createElement('exporta'));
        $ZA02 = $ZA01->appendChild($dom->createElement('UFEmbarq',    $this->UFEmbarq));
        $ZA03 = $ZA01->appendChild($dom->createElement('xLocEmbarq',  $this->xLocEmbarq));
        return $ZA01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO exporta VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->UFEmbarq);
        $sql.= ", ".$con->quote($this->xLocEmbarq);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro exporta: '.$qry->getMessage());
            return false;
        } else {
            $exporta_id = $con->lastInsertID("exporta", "exporta_id");
        }
    }
}

// ZB01
class compra {

    var $xNEmp; // ZB02 - nota de empenho
    var $xPed;  // ZB03 - pedido
    var $xCont; // ZB04 - contrato

    function __construct() {
    }

    function get_xml($dom) {
        $ZB01 = $dom->appendChild($dom->createElement('compra'));
        $ZB02 = (!empty($this->xNEmp))  ? $ZB01->appendChild($dom->createElement('xNEmp',  $this->xNEmp)) : null;
        $ZB02 = (!empty($this->xPed))   ? $ZB01->appendChild($dom->createElement('xPed',   $this->xPed))  : null;
        $ZB02 = (!empty($this->xCont))  ? $ZB01->appendChild($dom->createElement('xCont',  $this->xCont)) : null;
        return $ZB01;
    }

    function insere($con, $infNFe_id) {
        $sql = "INSERT INTO compra VALUES (NULL";
        $sql.= ", ".$con->quote($infNFe_id);
        $sql.= ", ".$con->quote($this->xNEmp);
        $sql.= ", ".$con->quote($this->xPed);
        $sql.= ", ".$con->quote($this->xCont);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro compra: '.$qry->getMessage());
            return false;
        } else {
            $compra_id = $con->lastInsertID("compra", "compra_id");
        }
    }
}

// ZC01
class Signature {
}



/* NIVEL 3 ********************************************************************/

// B12a
/**
 * *** Roberto
 * Estes campos sao para indicar as notas fiscais referenciadas na notas principal
 * por exemplo quando fazemos um beneficiamento industrial para alguem na fatura temos que devolver
 * os materiais enviados para o beneficimento que entraram com NF (eh claro)
 * Esta referencias podem ser a NFe, onde so e necessario indicar o Id da NFe 44 digitos
 * ou NF modelo 1, e neste caso temos que fornecer as outras informaÃ§oes
 */
class NFref {

    var $refNFe;    // B13 - chave de acesso das NFe referenciadas
    var $refNF;     // B14 - informações das NF referenciadas

    function __construct($tipo = 'NFe') {
        if ($tipo == 'NF') {
            $this->refNF  = new refNF;
        }
    }

    function get_xml($dom) {
        $B12a= $dom->appendChild($dom->createElement('NFref'));
        if (!empty($this->refNFe)) {
            $B13 = $B12a->appendChild($dom->createElement('refNFe', $this->refNFe));
        } else if (is_object($this->refNF)) {
            $B14 = $B12a->appendChild($this->refNF->get_xml($dom));
        }
        return $B12a;
    }

    function insere($con, $ide_id) {
        if (!empty($this->refNFe)) {
            $sql = "INSERT INTO refNFe VALUES (NULL";
            $sql.= ", ".$con->quote($ide_id);
            $sql.= ", ".$con->quote($this->refNFe);
            $sql.= ")";

            $qry = $con->query($sql);

            if (MDB2::isError($qry)) {
                set_error('Erro refNFe: '.$qry->getMessage());
                return false;
            } else {
                $refNFe_id = $con->lastInsertID("refNFe", "refNFe_id");
            }

        } else if (is_object($this->refNF)) {
            $this->refNF->insere($con, $ide_id);
        }
    }
}

// C05
class enderEmit {

    var $xLgr;      // C06 - logradouro do emitente
    var $nro;       // C07 - número do emitente
    var $xCpl;      // C08 - complemento do emitente
    var $xBairro;   // C09 - bairro do emitente
    var $cMun;      // C10 - código do município do emitente
    var $xMun;      // C11 - nome do município do emitente
    var $UF;        // C12 - sigla da UF do emitente
    var $CEP;       // C13 - código do CEP do emitente
    var $cPais;     // C14 - código do País (1058 - Brasil) do emitente
    var $xPais;     // C15 - nome do País (Brasil ou BRASIL) do emitente
    var $fone;      // C16 - telefone do emitente

    function __construct() {
        $this->cPais    = 1058;
        $this->xPais    = "BRASIL";
    }

    function get_xml($dom) {
        $C05 = $dom->appendChild($dom->createElement('enderEmit'));
        $C06 = $C05->appendChild($dom->createElement('xLgr',    $this->xLgr));
        $C07 = $C05->appendChild($dom->createElement('nro',     $this->nro));
        $C08 = (!empty($this->xCpl)) ? $C05->appendChild($dom->createElement('xCpl',    $this->xCpl)) : null;
        $C09 = $C05->appendChild($dom->createElement('xBairro', $this->xBairro));
        $C10 = $C05->appendChild($dom->createElement('cMun',    $this->cMun));
        $C11 = $C05->appendChild($dom->createElement('xMun',    $this->xMun));
        $C12 = $C05->appendChild($dom->createElement('UF',      $this->UF));
        $C13 = (!empty($this->CEP))   ? $C05->appendChild($dom->createElement('CEP',     sprintf("%08s", $this->CEP)))   : null;
        $C14 = (!empty($this->cPais)) ? $C05->appendChild($dom->createElement('cPais',   $this->cPais)) : null;
        $C15 = (!empty($this->xPais)) ? $C05->appendChild($dom->createElement('xPais',   $this->xPais)) : null;
        $C16 = (!empty($this->fone))  ? $C05->appendChild($dom->createElement('fone',    $this->fone))  : null;
        return $C05;
    }

    function insere($con, $emit_id) {
        $sql = "INSERT INTO enderEmit VALUES (NULL";
        $sql.= ", ".$con->quote($emit_id);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->CEP);
        $sql.= ", ".$con->quote($this->cPais);
        $sql.= ", ".$con->quote($this->xPais);
        $sql.= ", ".$con->quote($this->fone);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro enderEmit: '.$qry->getMessage());
            return false;
        } else {
            $enderEmit_id = $con->lastInsertID("enderEmit", "enderEmit_id");
        }
    }
}

// E05
class enderDest {

    var $xLgr;      // E06 - logradouro do destinatario
    var $nro;       // E07 - número do destinatario
    var $xCpl;      // E08 - complemento do destinatario
    var $xBairro;   // E09 - bairro do destinatario
    var $cMun;      // E10 - código do município do destinatario
    var $xMun;      // E11 - nome do município do destinatario
    var $UF;        // E12 - sigla da UF do destinatario
    var $CEP;       // E13 - código do CEP do destinatario
    var $cPais;     // E14 - código do País (1058 - Brasil) do destinatario
    var $xPais;     // E15 - nome do País (Brasil ou BRASIL) do destinatario
    var $fone;      // E16 - telefone do destinatario

    function __construct() {
        $this->cPais    = 1058;
        $this->xPais    = "BRASIL";
    }

    function get_xml($dom) {
        $E05 = $dom->appendChild($dom->createElement('enderDest'));
        $E06 = $E05->appendChild($dom->createElement('xLgr',    $this->xLgr));
        $E07 = $E05->appendChild($dom->createElement('nro',     $this->nro));
        $E08 = (!empty($this->xCpl))  ? $E05->appendChild($dom->createElement('xCpl',    $this->xCpl)) : null;
        $E09 = $E05->appendChild($dom->createElement('xBairro', $this->xBairro));
        $E10 = $E05->appendChild($dom->createElement('cMun',    $this->cMun));
        $E11 = $E05->appendChild($dom->createElement('xMun',    $this->xMun));
        $E12 = $E05->appendChild($dom->createElement('UF',      $this->UF));
        $E13 = (!empty($this->CEP))   ? $E05->appendChild($dom->createElement('CEP',     sprintf("%08s", $this->CEP)))   : null;
        $E14 = (!empty($this->cPais)) ? $E05->appendChild($dom->createElement('cPais',   $this->cPais)) : null;
        $E15 = (!empty($this->xPais)) ? $E05->appendChild($dom->createElement('xPais',   $this->xPais)) : null;
        $E16 = (!empty($this->fone))  ? $E05->appendChild($dom->createElement('fone',    $this->fone))  : null;
        return $E05;
    }

    function insere($con, $dest_id) {
        $sql = "INSERT INTO enderDest VALUES (NULL";
        $sql.= ", ".$con->quote($dest_id);
        $sql.= ", ".$con->quote($this->xLgr);
        $sql.= ", ".$con->quote($this->nro);
        $sql.= ", ".$con->quote($this->xCpl);
        $sql.= ", ".$con->quote($this->xBairro);
        $sql.= ", ".$con->quote($this->cMun);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->CEP);
        $sql.= ", ".$con->quote($this->cPais);
        $sql.= ", ".$con->quote($this->xPais);
        $sql.= ", ".$con->quote($this->fone);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro enderDest: '.$qry->getMessage());
            return false;
        } else {
            $enderDest_id = $con->lastInsertID("enderDest", "enderDest_id");
        }
    }
}

// I01
class prod {

    var $cProd;     // I02 - código do produto ou serviço
    var $cEAN;      // I03 - GTIN (Global Trade Item Number)do produto
    var $xProd;     // I04 - descrição do produto ou serviço
    var $NCM;       // I05 - código NCM
    var $EXTIPI;    // I06 - EX_TIPI
    var $genero;    // I07 - gênero do produto ou serviço
    var $CFOP;      // I08 - código fiscal de operações e prestações
    var $uCom;      // I09 - unidade comercial
    var $qCom;      // I10 - quantidade comercial
    var $vUnCom;    // I10a- valor unitário de comercialização
    var $vProd;     // I11 - valor total bruto dos produtos ou serviços
    var $cEANTrib;  // I12 - GTIN da unidade tributável
    var $uTrib;     // I13 - unidade tributável
    var $qTrib;     // I14 - quantidade tributável
    var $vUnTrib;   // I14a- valor unitário de tributação
    var $vFrete;    // I15 - valor total do frete
    var $vSeg;      // I16 - valor total do seguro
    var $vDesc;     // I17 - valor do desconto
    var $DI;        // I18 - declaração de importação
    var $veicProd;  // J01 - grupo do detalhamento de veículos novos
    var $med;       // K01 - grupo do detalhamento de medicamentos
    var $arma;      // L01 - grupo do detalhamento do armamento
    var $comb;      // L101- grupo de informações para combustíveis líquidos

    function __construct() {
        $this->DI       = array();
        $this->veicProd = null;
        $this->med      = array();
        $this->arma     = array();
        $this->comb     = null;
    }

    function add_DI($obj_DI) {
        $this->DI[] = $obj_DI;
        return true;
    }

    function add_veicProd($obj_veicProd) {
        if (!$this->veicProd) {
            $this->veicProd = $obj_veicProd;
            return true;
        } else {
            return false;
        }
    }

    function add_med($obj_med) {
        $this->med[] = $obj_med;
        return true;
    }

    function add_arma($obj_arma) {
        $this->arma[] = $obj_arma;
        return true;
    }

    function add_comb($obj_comb) {
        if (!$this->comb) {
            $this->comb = $obj_comb;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $I01 = $dom->appendChild($dom->createElement('prod'));
        $I02 = $I01->appendChild($dom->createElement('cProd',       $this->cProd));
        $I03 = $I01->appendChild($dom->createElement('cEAN',        $this->cEAN));
        $I04 = $I01->appendChild($dom->createElement('xProd',       $this->xProd));
        $I05 = (!empty($this->NCM))     ? $I01->appendChild($dom->createElement('NCM',     $this->NCM))     : null;
        $I06 = (!empty($this->EXTIPI))  ? $I01->appendChild($dom->createElement('EXTIPI',  $this->EXTIPI))  : null;
        $I07 = (!empty($this->genero))  ? $I01->appendChild($dom->createElement('genero',  $this->genero))  : null;
        $I08 = $I01->appendChild($dom->createElement('CFOP',        $this->CFOP));
        $I09 = $I01->appendChild($dom->createElement('uCom',        $this->uCom));
        $I10 = $I01->appendChild($dom->createElement('qCom',        number_format($this->qCom, 4, ".", "")));
        $I10a= $I01->appendChild($dom->createElement('vUnCom',      number_format($this->vUnCom, 4, ".", "")));
        $I11 = $I01->appendChild($dom->createElement('vProd',       number_format($this->vProd, 2, ".", "")));
        $I12 = $I01->appendChild($dom->createElement('cEANTrib',    $this->cEANTrib));
        $I13 = $I01->appendChild($dom->createElement('uTrib',       $this->uTrib));
        $I14 = $I01->appendChild($dom->createElement('qTrib',       number_format($this->qTrib, 4, ".", "")));
        $I14a= $I01->appendChild($dom->createElement('vUnTrib',     number_format($this->vUnTrib, 4, ".", "")));
        $I15 = ($this->vFrete > 0)  ? $I01->appendChild($dom->createElement('vFrete',  number_format($this->vFrete, 2, ".", "")))  : null;
        $I16 = ($this->vSeg > 0)    ? $I01->appendChild($dom->createElement('vSeg',    number_format($this->vSeg, 2, ".", "")))    : null;
        $I17 = ($this->vDesc > 0)   ? $I01->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")))   : null;
        for ($i=0; $i<count($this->DI); $i++) {
            $I18 = $I01->appendChild($this->DI[$i]->get_xml($dom));
        }
        $J01 = (is_object($this->veicProd)) ? $I01->appendChild($this->veicProd->get_xml($dom)) : null;
        for ($i=0; $i<count($this->med); $i++) {
            $K01 = $I01->appendChild($this->med[$i]->get_xml($dom));
        }
        for ($i=0; $i<count($this->arma); $i++) {
            $L01 = $I01->appendChild($this->arma[$i]->get_xml($dom));
        }
        $L101= (is_object($this->comb)) ? $I01->appendChild($this->comb->get_xml($dom)) : null;
        return $I01;
    }

    function insere($con, $det_id) {
        $sql = "INSERT INTO prod VALUES (NULL";
        $sql.= ", ".$con->quote($det_id);
        $sql.= ", ".$con->quote($this->cProd);
        $sql.= ", ".$con->quote($this->cEAN);
        $sql.= ", ".$con->quote($this->xProd);
        $sql.= ", ".$con->quote($this->NCM);
        $sql.= ", ".$con->quote($this->EXTIPI);
        $sql.= ", ".$con->quote($this->genero);
        $sql.= ", ".$con->quote($this->CFOP);
        $sql.= ", ".$con->quote($this->uCom);
        $sql.= ", ".$con->quote($this->qCom);
        $sql.= ", ".$con->quote($this->vUnCom);
        $sql.= ", ".$con->quote($this->vProd);
        $sql.= ", ".$con->quote($this->cEANTrib);
        $sql.= ", ".$con->quote($this->uTrib);
        $sql.= ", ".$con->quote($this->qTrib);
        $sql.= ", ".$con->quote($this->vUnTrib);
        $sql.= ", ".$con->quote($this->vFrete);
        $sql.= ", ".$con->quote($this->vSeg);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro prod: '.$qry->getMessage());
            return false;
        } else {
            $prod_id = $con->lastInsertID("prod", "prod_id");

            for ($i=0; $i<count($this->DI); $i++) {
                $this->DI[$i]->insere($con, $prod_id);
            }
            (is_object($this->veicProd)) ? $this->veicProd->insere($con, $prod_id) : null;
            for ($i=0; $i<count($this->med); $i++) {
                $this->med[$i]->insere($con, $prod_id);
            }
            for ($i=0; $i<count($this->arma); $i++) {
                $this->arma[$i]->insere($con, $prod_id);
            }
            (is_object($this->comb)) ? $this->comb->insere($con, $prod_id) : null;
        }
    }
}

// M01
class imposto {

    var $ICMS;      // N01 - grupo de ICMS da operação própria e ST
    var $IPI;       // O01 - grupo de IPI
    var $II;        // P01 - grupo de imposto de importação
    var $PIS;       // Q01 - grupo do PIS
    var $PISST;     // R01 - grupo de PIS substituição tributária
    var $COFINS;    // S01 - grupo de COFINS
    var $COFINSST;  // T01 - grupo de COFINS substituição tributária
    var $ISSQN;     // U01 - grupo do ISSQN

    function __construct() {
        $this->ICMS     = new ICMS;
        $this->IPI      = null;
        $this->II       = null;
        $this->PIS      = new PIS;
        $this->PISST    = null;
        $this->COFINS   = new COFINS;
        $this->COFINSST = null;
        $this->ISSQN    = null;
    }

    function add_IPI($obj_IPI) {
        if (!$this->IPI) {
            $this->IPI = $obj_IPI;
            return true;
        } else {
            return false;
        }
    }

    function add_II($obj_II) {
        if (!$this->II) {
            $this->II = $obj_II;
            return true;
        } else {
            return false;
        }
    }

    function add_PISST($obj_PISST) {
        if (!$this->PISST) {
            $this->PISST = $obj_PISST;
            return true;
        } else {
            return false;
        }
    }

    function add_COFINSST($obj_COFINSST) {
        if (!$this->COFINSST) {
            $this->COFINSST = $obj_COFINSST;
            return true;
        } else {
            return false;
        }
    }

    function add_ISSQN($obj_ISSQN) {
        if (!$this->ISSQN) {
            $this->ISSQN = $obj_ISSQN;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $M01 = $dom->appendChild($dom->createElement('imposto'));
        $N01 = $M01->appendChild($this->ICMS->get_xml($dom));
        $O01 = (is_object($this->IPI)) ? $M01->appendChild($this->IPI->get_xml($dom)) : null;
        $P01 = (is_object($this->II)) ? $M01->appendChild($this->II->get_xml($dom)) : null;
        $Q01 = $M01->appendChild($this->PIS->get_xml($dom));
        $R01 = (is_object($this->PISST)) ? $M01->appendChild($this->PISST->get_xml($dom)) : null;
        $S01 = $M01->appendChild($this->COFINS->get_xml($dom));
        $T01 = (is_object($this->COFINSST)) ? $M01->appendChild($this->COFINSST->get_xml($dom)) : null;
        $U01 = (is_object($this->ISSQN)) ? $M01->appendChild($this->ISSQN->get_xml($dom)) : null;
        return $M01;
    }

    function insere($con, $det_id) {
        $sql = "INSERT INTO imposto VALUES (NULL";
        $sql.= ", ".$con->quote($det_id);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro imposto: '.$qry->getMessage());
            return false;
        } else {
            $imposto_id = $con->lastInsertID("imposto", "imposto_id");
            $this->ICMS->insere($con, $imposto_id);
            $this->PIS->insere($con, $imposto_id);
            $this->COFINS->insere($con, $imposto_id);
            (is_object($this->IPI)) ? $this->IPI->insere($con, $imposto_id) : null;
            (is_object($this->II)) ? $this->II->insere($con, $imposto_id) : null;
            (is_object($this->PISST)) ? $this->PISST->insere($con, $imposto_id) : null;
            (is_object($this->COFINSST)) ? $this->COFINSST->insere($con, $imposto_id) : null;
            (is_object($this->ISSQN)) ? $this->ISSQN->insere($con, $imposto_id) : null;
        }
    }
}

// V01
class infAdProd {

    var $infAdProd;

    function __construct() {
    }

    function get_xml($dom) {
        $V01 = $dom->appendChild($dom->createElement('infAdProd',   $this->infAdProd));
        return $V01;
    }

    function insere($con, $det_id) {
        $sql = "INSERT INTO infAdProd VALUES (NULL";
        $sql.= ", ".$con->quote($det_id);
        $sql.= ", ".$con->quote($this->infAdProd);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro infAdProd: '.$qry->getMessage());
            return false;
        } else {
            $infAdProd_id = $con->lastInsertID("infAdProd", "infAdProd_id");
        }
    }
}

// W02
class ICMSTot {

    var $vBC;       // W03 - base de cáculo para ICMS
    var $vICMS;     // W04 - valor total do ICMS
    var $vBCST;     // W05 - base de cálculo para ICMS ST
    var $vST;       // W06 - valor total do ICMS ST
    var $vProd;     // W07 - valor total dos produtos e serviços
    var $vFrete;    // W08 - valor total do frete
    var $vSeg;      // W09 - valor total do seguro
    var $vDesc;     // W10 - valor total do desconto
    var $vII;       // W11 - valor total do II
    var $vIPI;      // W12 - valor total do IPI
    var $vPIS;      // W13 - valor total do PIS
    var $vCOFINS;   // W14 - valor total do COFINS
    var $vOutro;    // W15 - outras despesas acessórias
    var $vNF;       // W16 - valor total da NFe

    function __construct() {
    }

    function get_xml($dom) {
        $W02 = $dom->appendChild($dom->createElement('ICMSTot'));
        $W03 = $W02->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")));
        $W04 = $W02->appendChild($dom->createElement('vICMS',   number_format($this->vICMS, 2, ".", "")));
        $W05 = $W02->appendChild($dom->createElement('vBCST',   number_format($this->vBCST, 2, ".", "")));
        $W06 = $W02->appendChild($dom->createElement('vST',     number_format($this->vST, 2, ".", "")));
        $W07 = $W02->appendChild($dom->createElement('vProd',   number_format($this->vProd, 2, ".", "")));
        $W08 = $W02->appendChild($dom->createElement('vFrete',  number_format($this->vFrete, 2, ".", "")));
        $W09 = $W02->appendChild($dom->createElement('vSeg',    number_format($this->vSeg, 2, ".", "")));
        $W10 = $W02->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")));
        $W11 = $W02->appendChild($dom->createElement('vII',     number_format($this->vII, 2, ".", "")));
        $W12 = $W02->appendChild($dom->createElement('vIPI',    number_format($this->vIPI, 2, ".", "")));
        $W13 = $W02->appendChild($dom->createElement('vPIS',    number_format($this->vPIS, 2, ".", "")));
        $W14 = $W02->appendChild($dom->createElement('vCOFINS', number_format($this->vCOFINS, 2, ".", "")));
        $W15 = $W02->appendChild($dom->createElement('vOutro',  number_format($this->vOutro, 2, ".", "")));
        $W16 = $W02->appendChild($dom->createElement('vNF',     number_format($this->vNF, 2, ".", "")));
        return $W02;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO ICMSTot VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vICMS);
        $sql.= ", ".$con->quote($this->vBCST);
        $sql.= ", ".$con->quote($this->vST);
        $sql.= ", ".$con->quote($this->vProd);
        $sql.= ", ".$con->quote($this->vFrete);
        $sql.= ", ".$con->quote($this->vSeg);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ", ".$con->quote($this->vII);
        $sql.= ", ".$con->quote($this->vIPI);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ", ".$con->quote($this->vOutro);
        $sql.= ", ".$con->quote($this->vNF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ICMSTot: '.$qry->getMessage());
            return false;
        } else {
            $ICMSTot_id = $con->lastInsertID("ICMSTot", "ICMSTot_id");
        }
    }
}

// W17
class ISSQNtot {

    var $vServ;     // W18 - valor total dos serviços não tributados pelo ICMS
    var $vBC;       // W19 - base de cálculo do ISS
    var $vISS;      // W20 - valor total do ISS
    var $vPIS;      // W21 - valor do PIS sobre serviços
    var $vCOFINS;   // W22 - valor do COFINS sobre serviços

    function __construct() {
    }

    function get_xml($dom) {
        $W17 = $dom->appendChild($dom->createElement('ISSQNtot'));
        $W18 = (isset($this->vServ))   ? $W17->appendChild($dom->createElement('vServ',   number_format($this->vServ, 2, ".", "")))   : null;
        $W19 = (isset($this->vBC))     ? $W17->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")))     : null;
        $W20 = (isset($this->vISS))    ? $W17->appendChild($dom->createElement('vISS',    number_format($this->vISS, 2, ".", "")))    : null;
        $W21 = (isset($this->vPIS))    ? $W17->appendChild($dom->createElement('vPIS',    number_format($this->vPIS, 2, ".", "")))    : null;
        $W22 = (isset($this->vCOFINS)) ? $W17->appendChild($dom->createElement('vCOFINS', number_format($this->vCOFINS, 2, ".", ""))) : null;
        return $W17;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO ISSQNtot VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vServ);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vISS);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ISSQNtot: '.$qry->getMessage());
            return false;
        } else {
            $ISSQNtot_id = $con->lastInsertID("ISSQNtot", "ISSQNtot_id");
        }
    }
}

// W23
class retTrib {

    var $vRetPIS;       // W24 - valor retido do PIS
    var $vRetCOFINS;    // W25 - valor retido de COFINS
    var $vRetCSLL;      // W26 - valor retido de CSLL
    var $vBCIRRF;       // W27 - base de cálculo do IRRF
    var $vIRRF;         // W28 - valor retido do IRRF
    var $vBCRetPrev;    // W29 - base de cálculo da retenção da previdência 
    var $vRetPrev;      // W30 - valor da retenção da previdência social

    function __construct() {
    }

    function get_xml($dom) {
        $W23 = $dom->appendChild($dom->createElement('retTrib'));
        $W24 = (isset($this->vRetPIS))     ? $W23->appendChild($dom->createElement('vRetPIS',      number_format($this->vRetPIS, 2, ".", "")))      : null;
        $W25 = (isset($this->vRetCOFINS))  ? $W23->appendChild($dom->createElement('vRetCOFINS',   number_format($this->vRetCOFINS, 2, ".", "")))   : null;
        $W26 = (isset($this->vRetCSLL))    ? $W23->appendChild($dom->createElement('vRetCSLL',     number_format($this->vRetCSLL, 2, ".", "")))     : null;
        $W27 = (isset($this->vBCIRRF))     ? $W23->appendChild($dom->createElement('vBCIRRF',      number_format($this->vBCIRRF, 2, ".", "")))      : null;
        $W28 = (isset($this->vIRRF))       ? $W23->appendChild($dom->createElement('vIRRF',        number_format($this->vIRRF, 2, ".", "")))        : null;
        $W29 = (isset($this->vBCRetPrev))  ? $W23->appendChild($dom->createElement('vBCRetPrev',   number_format($this->vBCRetPrev, 2, ".", "")))   : null;
        $W30 = (isset($this->vRetPrev))    ? $W23->appendChild($dom->createElement('vRetPrev',     number_format($this->vRetPrev, 2, ".", "")))     : null;
        return $W23;
    }

    function insere($con, $total_id) {
        $sql = "INSERT INTO retTrib VALUES (NULL";
        $sql.= ", ".$con->quote($total_id);
        $sql.= ", ".$con->quote($this->vRetPIS);
        $sql.= ", ".$con->quote($this->vRetCOFINS);
        $sql.= ", ".$con->quote($this->vRetCSLL);
        $sql.= ", ".$con->quote($this->vBCIRRF);
        $sql.= ", ".$con->quote($this->vIRRF);
        $sql.= ", ".$con->quote($this->vBCRetPrev);
        $sql.= ", ".$con->quote($this->vRetPrev);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro retTrib: '.$qry->getMessage());
            return false;
        } else {
            $retTrib_id = $con->lastInsertID("retTrib", "retTrib_id");
        }
    }
}

// X03
class transporta {

    var $CNPJ;      // X04 - CNPJ
    var $CPF;       // X05 - CPF
    var $xNome;     // X06 - razão social ou nome
    var $IE;        // X07 - inscrição estadual
    var $xEnder;    // X08 - endereço completo
    var $xMun;      // X09 - nome do município
    var $UF;        // X10 - sigla da UF

    function __construct() {
    }

    function get_xml($dom) {
        $X03 = $dom->appendChild($dom->createElement('transporta'));
        $X04 = (empty($this->CPF)) ? $X03->appendChild($dom->createElement('CNPJ', sprintf("%014s", $this->CNPJ))) : $X03->appendChild($dom->createElement('CPF', sprintf("%011s", $this->CPF)));
        //X05 - ou exclusivo com X04
        $X06 = (!empty($this->xNome))   ? $X03->appendChild($dom->createElement('xNome',    $this->xNome))  : null;
        $X07 = (!empty($this->IE))      ? $X03->appendChild($dom->createElement('IE',       $this->IE))     : null;
        $X08 = (!empty($this->xEnder))  ? $X03->appendChild($dom->createElement('xEnder',   $this->xEnder)) : null;
        $X09 = (!empty($this->xMun))    ? $X03->appendChild($dom->createElement('xMun',     $this->xMun))   : null;
        $X10 = (!empty($this->UF))      ? $X03->appendChild($dom->createElement('UF',       $this->UF))     : null;
        return $X03;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO transporta VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->CPF);
        $sql.= ", ".$con->quote($this->xNome);
        $sql.= ", ".$con->quote($this->IE);
        $sql.= ", ".$con->quote($this->xEnder);
        $sql.= ", ".$con->quote($this->xMun);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro transporta: '.$qry->getMessage());
            return false;
        } else {
            $transporta_id = $con->lastInsertID("transporta", "transporta_id");
        }
    }
}

// X11
class retTransp {

    var $vServ;     // X12 - valor do serviço
    var $vBCRet;    // X13 - BC da retenção do ICMS
    var $pICMSRet;  // X14 - alíquota da retenção
    var $vICMSRet;  // X15 - valor do ICMS retido
    var $CFOP;      // X16 - CFOP
    var $cMunFG;    // X17 - código do município do fato gerador do ICMS do transp

    function __construct() {
    }

    function get_xml($dom) {
        $X11 = $dom->appendChild($dom->createElement('retTransp'));
        $X12 = $X11->appendChild($dom->createElement('vServ',    number_format($this->vServ, 2, ".", "")));
        $X13 = $X11->appendChild($dom->createElement('vBCRet',   number_format($this->vBCRet, 2, ".", "")));
        $X14 = $X11->appendChild($dom->createElement('pICMSRet', number_format($this->pICMSRet, 2, ".", "")));
        $X15 = $X11->appendChild($dom->createElement('vICMSRet', number_format($this->vICMSRet, 2, ".", "")));
        $X16 = $X11->appendChild($dom->createElement('CFOP',     $this->CFOP));
        $X17 = $X11->appendChild($dom->createElement('cMunFG',   $this->cMunFG));
        return $X11;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO retTransp VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->vServ);
        $sql.= ", ".$con->quote($this->vBCRet);
        $sql.= ", ".$con->quote($this->pICMSRet);
        $sql.= ", ".$con->quote($this->vICMSRet);
        $sql.= ", ".$con->quote($this->CFOP);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro retTransp: '.$qry->getMessage());
            return false;
        } else {
            $retTransp_id = $con->lastInsertID("retTransp", "retTransp_id");
        }
    }
}

// X18
class veicTransp {

    var $placa;     // X19 - placa do veículo
    var $UF;        // X20 - sigla da UF
    var $RNTC;      // X21 - registro nacional de transportador de carga (ANTT)

    function __construct() {
    }

    function get_xml($dom) {
        $X18 = $dom->appendChild($dom->createElement('veicTransp'));
        $X19 = $X18->appendChild($dom->createElement('placa',   $this->placa));
        $X19 = $X18->appendChild($dom->createElement('UF',      $this->UF));
        $X19 = (!empty($this->RNTC)) ? $X18->appendChild($dom->createElement('RNTC', $this->RNTC)) : null;
        return $X18;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO veicTransp VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->placa);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->RNTC);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro veicTransp: '.$qry->getMessage());
            return false;
        } else {
            $veicTransp_id = $con->lastInsertID("veicTransp", "veicTransp_id");
        }
    }
}

// X22
class reboque {

    var $placa;     // X23 - placa do veículo
    var $UF;        // X24 - sigla da UF
    var $RNTC;      // X25 - registro nacional de transportador de carga (ANTT)

    function __construct() {
    }

    function get_xml($dom) {
        $X22 = $dom->appendChild($dom->createElement('reboque'));
        $X23 = $X22->appendChild($dom->createElement('placa',   $this->placa));
        $X24 = $X22->appendChild($dom->createElement('UF',      $this->UF));
        $X25 = (!empty($this->RNTC)) ? $X22->appendChild($dom->createElement('RNTC', $this->RNTC)) : null;
        return $X22;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO reboque VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->placa);
        $sql.= ", ".$con->quote($this->UF);
        $sql.= ", ".$con->quote($this->RNTC);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro reboque: '.$qry->getMessage());
            return false;
        } else {
            $reboque_id = $con->lastInsertID("reboque", "reboque_id");
        }
    }
}

// X26
class vol {

    var $qVol;      // X27 - quantidade de volumes transportados
    var $esp;       // X28 - espécie dos volumes transportados
    var $marca;     // X29 - marca dos volumes transportados
    var $nVol;      // X30 - numeração dos volumes transportados
    var $pesoL;     // X31 - peso líquido (em kg)
    var $pesoB;     // X32 - peso bruto (em kg)
    var $lacres;    // X33 - grupo de lacres

    function __construct() {
        $this->lacres = array();
    }

    function add_lacres($obj_lacres) {
        $this->lacres[] = $obj_lacres;
        return true;
    }

    function get_xml($dom) {
        $X26 = $dom->appendChild($dom->createElement('vol'));
        $X27 = (!empty($this->qVol))    ? $X26->appendChild($dom->createElement('qVol',    $this->qVol))  : null;
        $X27 = (!empty($this->esp))     ? $X26->appendChild($dom->createElement('esp',     $this->esp))   : null;
        $X27 = (!empty($this->marca))   ? $X26->appendChild($dom->createElement('marca',   $this->marca)) : null;
        $X27 = (!empty($this->nVol))    ? $X26->appendChild($dom->createElement('nVol',    $this->nVol))  : null;
        $X27 = (!empty($this->pesoL))   ? $X26->appendChild($dom->createElement('pesoL',   number_format($this->pesoL, 3, ".", ""))) : null;
        $X27 = (!empty($this->pesoB))   ? $X26->appendChild($dom->createElement('pesoB',   number_format($this->pesoB, 3, ".", ""))) : null;
        for ($i=0; $i<count($this->lacres); $i++) {
            $X33 = $X26->appendChild($this->lacres[$i]->get_xml($dom));
        }
        return $X26;
    }

    function insere($con, $transp_id) {
        $sql = "INSERT INTO vol VALUES (NULL";
        $sql.= ", ".$con->quote($transp_id);
        $sql.= ", ".$con->quote($this->qVol);
        $sql.= ", ".$con->quote($this->esp);
        $sql.= ", ".$con->quote($this->marca);
        $sql.= ", ".$con->quote($this->nVol);
        $sql.= ", ".$con->quote($this->pesoL);
        $sql.= ", ".$con->quote($this->pesoB);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro vol: '.$qry->getMessage());
            return false;
        } else {
            $vol_id = $con->lastInsertID("vol", "vol_id");
            for ($i=0; $i<count($this->lacres); $i++) {
                $this->lacres[$i]->insere($con, $vol_id);
            }
        }
    }
}

// Y02
class fat {

    var $nFat;      // Y03 - número da fatura
    var $vOrig;     // Y04 - valor original da fatura
    var $vDesc;     // Y05 - valor do desconto
    var $vLiq;      // Y06 - valor líquido da fatura

    function __construct() {
    }

    function get_xml($dom) {
        $Y02 = $dom->appendChild($dom->createElement('fat'));
        $Y03 = (!isset($this->nFat)) ? $Y02->appendChild($dom->createElement('nFat',    $this->nFat))    : null;
        $Y04 = ($this->vOrig > 0)    ? $Y02->appendChild($dom->createElement('vOrig',   number_format($this->vOrig, 2, ".", "")))   : null;
        $Y05 = ($this->vDesc > 0)    ? $Y02->appendChild($dom->createElement('vDesc',   number_format($this->vDesc, 2, ".", "")))   : null;
        $Y06 = ($this->vLiq > 0)     ? $Y02->appendChild($dom->createElement('vLiq',    number_format($this->vLiq, 2, ".", "")))    : null;
        return $Y02;
    }

    function insere($con, $cobr_id) {
        $sql = "INSERT INTO fat VALUES (NULL";
        $sql.= ", ".$con->quote($cobr_id);
        $sql.= ", ".$con->quote($this->nFat);
        $sql.= ", ".$con->quote($this->vOrig);
        $sql.= ", ".$con->quote($this->vDesc);
        $sql.= ", ".$con->quote($this->vLiq);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro fat: '.$qry->getMessage());
            return false;
        } else {
            $fat_id = $con->lastInsertID("fat", "fat_id");
        }
    }
}

// Y07
class dup {

    var $nDup;      // Y08 - número da duplicata
    var $dVenc;     // Y09 - data de vencimento
    var $vDup;      // Y10 - valor da duplicata

    function __construct() {
    }

    function get_xml($dom) {
        $Y07 = $dom->appendChild($dom->createElement('dup'));
        $Y08 = (isset($this->nDup))  ? $Y07->appendChild($dom->createElement('nDup',    $this->nDup))    : null;
        $Y09 = (!empty($this->dVenc)) ? $Y07->appendChild($dom->createElement('dVenc',   $this->dVenc))   : null;
        $Y10 = ($this->vDup > 0)      ? $Y07->appendChild($dom->createElement('vDup',    number_format($this->vDup, 2, ".", "")))    : null;
        return $Y07;
    }

    function insere($con, $cobr_id) {
        $sql = "INSERT INTO dup VALUES (NULL";
        $sql.= ", ".$con->quote($cobr_id);
        $sql.= ", ".$con->quote($this->nDup);
        $sql.= ", ".$con->quote($this->dVenc);
        $sql.= ", ".$con->quote($this->vDup);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro dup: '.$qry->getMessage());
            return false;
        } else {
            $dup_id = $con->lastInsertID("dup", "dup_id");
        }
    }
}

// Z04
class obsCont {

    var $xCampo;    // Z05 - identificação do campo
    var $xTexto;    // Z06 - conteúdo do campo

    function __construct() {
    }

    function get_xml($dom) {
        $Z04 = $dom->appendChild($dom->createElement('obsCont'));
        $Z05 = $Z04->appendChild($dom->createElement('xCampo', $this->xCampo));
        $Z06 = $Z04->appendChild($dom->createElement('xTexto', $this->xTexto));
        return $Z04;
    }

    function insere($con, $infAdic_id) {
        $sql = "INSERT INTO obsCont VALUES (NULL";
        $sql.= ", ".$con->quote($infAdic_id);
        $sql.= ", ".$con->quote($this->xCampo);
        $sql.= ", ".$con->quote($this->xTexto);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro obsCont: '.$qry->getMessage());
            return false;
        } else {
            $obsCont_id = $con->lastInsertID("obsCont", "obsCont_id");
        }
    }
}

// Z07
class obsFisco {

    var $xCampo;    // Z08 - identificação do campo
    var $xTexto;    // Z09 - conteúdo do campo

    function __construct() {
    }

    function get_xml($dom) {
        $Z07 = $dom->appendChild($dom->createElement('obsFisco'));
        $Z08 = $Z07->appendChild($dom->createElement('xCampo', $this->xCampo));
        $Z09 = $Z07->appendChild($dom->createElement('xTexto', $this->xTexto));
        return $Z07;
    }

    function insere($con, $infAdic_id) {
        $sql = "INSERT INTO obsFisco VALUES (NULL";
        $sql.= ", ".$con->quote($infAdic_id);
        $sql.= ", ".$con->quote($this->xCampo);
        $sql.= ", ".$con->quote($this->xTexto);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro obsFisco: '.$qry->getMessage());
            return false;
        } else {
            $obsFisco_id = $con->lastInsertID("obsFisco", "obsFisco_id");
        }
    }
}

// Z10
class procRef {

    var $nProc;     // Z11 - identificador do processo ou ato concessório
    var $indProc;   // Z12 - indicador da origem do processo

    function __construct() {
    }

    function get_xml($dom) {
        $Z10 = $dom->appendChild($dom->createElement('procRef'));
        $Z11 = $Z10->appendChild($dom->createElement('nProc',   $this->nProc));
        $Z12 = $Z10->appendChild($dom->createElement('indProc', $this->indProc));
        return $Z10;
    }

    function insere($con, $infAdic_id) {
        $sql = "INSERT INTO procRef VALUES (NULL";
        $sql.= ", ".$con->quote($infAdic_id);
        $sql.= ", ".$con->quote($this->nProc);
        $sql.= ", ".$con->quote($this->indProc);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro procRef: '.$qry->getMessage());
            return false;
        } else {
            $procRef_id = $con->lastInsertID("procRef", "procRef_id");
        }
    }
}



/* NIVEL 4 ********************************************************************/


// B14
class refNF {

    var $cUF;       // B15 - código da UF do emitente do documento fiscal
    var $AAMM;      // B16 - ano e mês de emissão da NFe
    var $CNPJ;      // B17 - CNPJ do emitente
    var $mod;       // B18 - modelo do documento fiscal
    var $serie;     // B19 - série do documento fiscal
    var $nNF;       // B20 - número do documento fiscal

    function __construct() {
    }

    function get_xml($dom) {
        $B14 = $dom->appendChild($dom->createElement('refNF'));
        $B15 = $B14->appendChild($dom->createElement('cUF',   $this->cUF));
        $B16 = $B14->appendChild($dom->createElement('AAMM',  $this->AAMM));
        $B17 = $B14->appendChild($dom->createElement('CNPJ',  sprintf("%014s", $this->CNPJ)));
        $B18 = $B14->appendChild($dom->createElement('mod',   $this->mod));
        $B19 = $B14->appendChild($dom->createElement('serie', $this->serie));
        $B20 = $B14->appendChild($dom->createElement('nNF',   $this->nNF));
        return $B14;
    }

    function insere($con, $ide_id) {
        $sql = "INSERT INTO refNF VALUES (NULL";
        $sql.= ", ".$con->quote($ide_id);
        $sql.= ", ".$con->quote($this->cUF);
        $sql.= ", ".$con->quote($this->AAMM);
        $sql.= ", ".$con->quote($this->CNPJ);
        $sql.= ", ".$con->quote($this->mod);
        $sql.= ", ".$con->quote($this->serie);
        $sql.= ", ".$con->quote($this->nNF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro refNF: '.$qry->getMessage());
            return false;
        } else {
            $refNF_id = $con->lastInsertID("refNF", "refNF_id");
        }
    }
}

// I18
class DI {

    var $nDI;           // I19 - número do documento de importação DI/DSI/DA
    var $dDi;           // I20 - data da registro da DI/DSI/DA
    var $xLocDesemb;    // I21 - local de desembaraço
    var $UFDesemb;      // I22 - UF onde ocorreu o desembaraço aduaneiro
    var $dDesemb;       // I23 - data do desembaraço aduaneiro
    var $cExportador;   // I24 - código do exportador
    var $adi;           // I25 - adições

    function __construct() {
        $this->adi = array();
    }

    function add_adi($obj_adi) {
        $this->adi[] = $obj_adi;
        return true;
    }

    function get_xml($dom) {
        $I18 = $dom->appendChild($dom->createElement('DI'));
        $I19 = $I18->appendChild($dom->createElement('nDI',         $this->nDi));
        $I20 = $I18->appendChild($dom->createElement('dDi',         $this->dDi));
        $I21 = $I18->appendChild($dom->createElement('xLocDesemb',  $this->xLocDesemb));
        $I22 = $I18->appendChild($dom->createElement('UFDesemb',    $this->UFDesemb));
        $I23 = $I18->appendChild($dom->createElement('dDesemb',     $this->dDesemb));
        $I24 = $I18->appendChild($dom->createElement('cExportador', $this->cExportador));
        for ($i=0; $i<count($this->adi); $i++) {
            $I25 = $I18->appendChild($this->adi[$i]->get_xml($dom));
        }
        return $I18;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO DI VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->nDI);
        $sql.= ", ".$con->quote($this->dDi);
        $sql.= ", ".$con->quote($this->xLocDesemb);
        $sql.= ", ".$con->quote($this->UFDesemb);
        $sql.= ", ".$con->quote($this->dDesemb);
        $sql.= ", ".$con->quote($this->cExportador);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro DI: '.$qry->getMessage());
            return false;
        } else {
            $DI_id = $con->lastInsertID("DI", "DI_id");
            for ($i=0; $i<count($this->adi); $i++) {
                $this->adi[$i]->insere($con, $DI_id);
            }
        }
    }
}

// J01
class veicProd {

    var $tpOp;      // J02 - tipo da operação
    var $chassi;    // J03 - chassi do veículo
    var $cCor;      // J04 - cor
    var $xCor;      // J05 - descrição da cor
    var $pot;       // J06 - potência do motor
    var $CM3;       // J07 - CM3 (potência)
    var $pesoL;     // J08 - peso líquido
    var $pesoB;     // J09 - peso bruto
    var $nSerie;    // J10 - serial
    var $tpComb;    // J11 - tipo de combustível
    var $nMotor;    // J12 - número do motor
    var $CMKG;      // J13 - CMKG
    var $dist;      // J14 - distância entre eixos
    var $RENAVAM;   // J15 - RENAVAM
    var $anoMod;    // J16 - ano modelo de fabricação
    var $anoFab;    // J17 - ano de fabricação
    var $tpPint;    // J18 - tipo de pintura
    var $tpVeic;    // J19 - tipo de veículo
    var $espVeic;   // J20 - espécie de veículo
    var $VIN;       // J21 - condição do VIN
    var $condVeic;  // J22 - condição do veículo
    var $cMod;      // J23 - código marca modelo

    function __construct() {
    }

    function get_xml($dom) {
        $J01 = $dom->appendChild($dom->createElement('veicProd'));
        $J02 = $J01->appendChild($dom->createElement('tpOp',        $this->tpOp));
        $J03 = $J01->appendChild($dom->createElement('chassi',      $this->chassi));
        $J04 = $J01->appendChild($dom->createElement('cCor',        $this->cCor));
        $J05 = $J01->appendChild($dom->createElement('xCor',        $this->xCor));
        $J06 = $J01->appendChild($dom->createElement('pot',         $this->pot));
        $J07 = $J01->appendChild($dom->createElement('CM3',         $this->CM3));
        $J08 = $J01->appendChild($dom->createElement('pesoL',       $this->pesoL));
        $J09 = $J01->appendChild($dom->createElement('pesoB',       $this->pesoB));
        $J10 = $J01->appendChild($dom->createElement('nSerie',      $this->nSerie));
        $J11 = $J01->appendChild($dom->createElement('tpComb',      $this->tpComb));
        $J12 = $J01->appendChild($dom->createElement('nMotor',      $this->nMotor));
        $J13 = $J01->appendChild($dom->createElement('CMKG',        $this->CMKG));
        $J14 = $J01->appendChild($dom->createElement('dist',        $this->dist));
        $J15 = (!empty($this->RENAVAM)) ? $J01->appendChild($dom->createElement('RENAVAM',     $this->RENAVAM)) : null;
        $J16 = $J01->appendChild($dom->createElement('anoMod',      $this->anoMod));
        $J17 = $J01->appendChild($dom->createElement('anoFab',      $this->anoFab));
        $J18 = $J01->appendChild($dom->createElement('tpPint',      $this->tpPint));
        $J19 = $J01->appendChild($dom->createElement('tpVeic',      $this->tpVeic));
        $J20 = $J01->appendChild($dom->createElement('espVeic',     $this->espVeic));
        $J21 = $J01->appendChild($dom->createElement('VIN',         $this->VIN));
        $J22 = $J01->appendChild($dom->createElement('condVeic',    $this->condVeic));
        $J23 = $J01->appendChild($dom->createElement('cMod',        $this->cMod));
        return $J01;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO veicProd VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->tpOp);
        $sql.= ", ".$con->quote($this->chassi);
        $sql.= ", ".$con->quote($this->cCor);
        $sql.= ", ".$con->quote($this->xCor);
        $sql.= ", ".$con->quote($this->pot);
        $sql.= ", ".$con->quote($this->CM3);
        $sql.= ", ".$con->quote($this->pesoL);
        $sql.= ", ".$con->quote($this->pesoB);
        $sql.= ", ".$con->quote($this->nSerie);
        $sql.= ", ".$con->quote($this->tpComb);
        $sql.= ", ".$con->quote($this->nMotor);
        $sql.= ", ".$con->quote($this->CMKG);
        $sql.= ", ".$con->quote($this->dist);
        $sql.= ", ".$con->quote($this->RENAVAM);
        $sql.= ", ".$con->quote($this->anoMod);
        $sql.= ", ".$con->quote($this->anoFab);
        $sql.= ", ".$con->quote($this->tpPint);
        $sql.= ", ".$con->quote($this->tpVeic);
        $sql.= ", ".$con->quote($this->espVeic);
        $sql.= ", ".$con->quote($this->VIN);
        $sql.= ", ".$con->quote($this->condVeic);
        $sql.= ", ".$con->quote($this->cMod);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro veicProd: '.$qry->getMessage());
            return false;
        } else {
            $veicProd_id = $con->lastInsertID("veicProd", "veicProd_id");
        }
    }
}

// K01
class med {

    var $nLote;     // K02 - número do lote do medicamento
    var $qLote;     // K03 - quantidade de produto no lote do medicamento
    var $dFab;      // K04 - data de fabricação
    var $dVal;      // K05 - data de validade
    var $vPMC;      // K06 - preço máximo consumidor

    function __construct() {
    }

    function get_xml($dom) {
        $K01 = $dom->appendChild($dom->createElement('med'));
        $K02 = $K01->appendChild($dom->createElement('nLote',   $this->nLote));
        $K03 = $K01->appendChild($dom->createElement('qLote',   $this->qLote));
        $K04 = $K01->appendChild($dom->createElement('dFab',    $this->dFab));
        $K05 = $K01->appendChild($dom->createElement('dVal',    $this->dVal));
        $K06 = $K01->appendChild($dom->createElement('vPMC',    $this->vPMC));
        return $K01;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO med VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->nLote);
        $sql.= ", ".$con->quote($this->qLote);
        $sql.= ", ".$con->quote($this->dFab);
        $sql.= ", ".$con->quote($this->dVal);
        $sql.= ", ".$con->quote($this->vPMC);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro med: '.$qry->getMessage());
            return false;
        } else {
            $med_id = $con->lastInsertID("med", "med_id");
        }
    }
}

// L01
class arma {

    var $tpArma;    // L02 - indicador do tipo de arama de fogo
    var $nSerie;    // L03 - número de série da arma
    var $nCano;     // L04 - número de série do cano
    var $descr;     // L05 - descrição completa da arma

    function __construct() {
    }

    function get_xml($dom) {
        $L01 = $dom->appendChild($dom->createElement('arma'));
        $L02 = $L01->appendChild($dom->createElement('tpArma',  $this->tpArma));
        $L03 = $L01->appendChild($dom->createElement('nSerie',  $this->nSerie));
        $L04 = $L01->appendChild($dom->createElement('nCano',   $this->nCano));
        $L05 = $L01->appendChild($dom->createElement('descr',   $this->descr));
        return $L01;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO arma VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->tpArma);
        $sql.= ", ".$con->quote($this->nSerie);
        $sql.= ", ".$con->quote($this->nCano);
        $sql.= ", ".$con->quote($this->descr);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro arma: '.$qry->getMessage());
            return false;
        } else {
            $arma_id = $con->lastInsertID("arma", "arma_id");
        }
    }
}

// L101
class comb {

    var $cProdANP;      // L102 - código de produto da ANP
    var $CODIF;         // L103 - código de autorização / registro do CODIF
    var $qTemp;         // L104 - quantidade de combustível faturada
    var $CIDE;          // L105 - grupo da CIDE
    var $ICMSComb;      // L109 - grupo do ICMSComb
    var $ICMSInter;     // L114 - grupo do ICMSST de operação interestadual
    var $ICMSCons;      // L117 - ICMS consumo em UF diferente da UF do destinat

    function __construct() {
        $this->CIDE         = null;
        $this->ICMSComb     = new ICMSComb;
        $this->ICMSInter    = null;
        $this->ICMSCons     = null;
    }

    function add_CIDE($obj_CIDE) {
        if (!$this->CIDE) {
            $this->CIDE = $obj_CIDE;
            return true;
        } else {
            return false;
        }
    }

    function add_ICMSInter($obj_ICMSInter) {
        if (!$this->ICMSInter) {
            $this->ICMSInter = $obj_ICMSInter;
            return true;
        } else {
            return false;
        }
    }

    function add_ICMSCons($obj_ICMSCons) {
        if (!$this->ICMSCons) {
            $this->ICMSCons = $obj_ICMSCons;
            return true;
        } else {
            return false;
        }
    }

    function get_xml($dom) {
        $L101 = $dom->appendChild($dom->createElement('comb'));
        $L102 = $L101->appendChild($dom->createElement('cProdANP',  $this->cProdANP));
        $L103 = $L101->appendChild($dom->createElement('CODIF',     $this->CODIF));
        $L104 = $L101->appendChild($dom->createElement('qTemp',     $this->qTemp));
        $L105 = (is_object($this->CIDE)) ? $L101->appendChild($this->CIDE->get_xml($dom)) : null;
        $L109 = $L101->appendChild($this->ICMSComb->get_xml($dom));
        $L114 = (is_object($this->ICMSInter)) ? $L101->appendChild($this->ICMSInter->get_xml($dom)) : null;
        $L117 = (is_object($this->ICMSCons)) ? $L101->appendChild($this->ICMSCons->get_xml($dom)) : null;
        return $L101;
    }

    function insere($con, $prod_id) {
        $sql = "INSERT INTO comb VALUES (NULL";
        $sql.= ", ".$con->quote($prod_id);
        $sql.= ", ".$con->quote($this->cProdANP);
        $sql.= ", ".$con->quote($this->CODIF);
        $sql.= ", ".$con->quote($this->qTemp);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro comb: '.$qry->getMessage());
            return false;
        } else {
            $comb_id = $con->lastInsertID("comb", "comb_id");
        }
    }
}

// N01
class ICMS {

    var $orig;      // N11 - origem da mercadoria
    var $CST;       // N12 - tributação do ICMS
    var $modBC;     // N13 - modalidade de determinação da BC do ICMS
    var $pRedBC;    // N14 - percentual da redução de BC
    var $vBC;       // N15 - valor da BC do ICMS
    var $pICMS;     // N16 - alíquota do imposto
    var $vICMS;     // N17 - valor do ICMS
    var $modBCST;   // N18 - modalidade de determinação da BC do ICMS ST
    var $pMVAST;    // N19 - percentual da margem de valor adicionado do ICMS ST
    var $pRedBCST;  // N20 - percentual da redução de BC do ICMS ST
    var $vBCST;     // N21 - valor da BC do ICMS ST
    var $pICMSST;   // N22 - alíquota do imposto do ICMS ST
    var $vICMSST;   // N23 - valor do ICMS ST

    function __construct() {
    }

    function get_xml($dom) {

        $N01 = $dom->appendChild($dom->createElement('ICMS'));

        switch ($this->CST) {

            case '00' :
                $N02 = $N01->appendChild($dom->createElement('ICMS00'));
                $N11 = $N02->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N02->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = $N02->appendChild($dom->createElement('modBC',       $this->modBC));
                $N15 = $N02->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $N16 = $N02->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")));
                $N17 = $N02->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")));
                break;

            case '10' :
                $N03 = $N01->appendChild($dom->createElement('ICMS10'));
                $N11 = $N03->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N03->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = $N03->appendChild($dom->createElement('modBC',       $this->modBC));
                $N15 = $N03->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $N16 = $N03->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")));
                $N17 = $N03->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")));
                $N18 = $N03->appendChild($dom->createElement('modBCST',     $this->modBCST));
                $N19 = (isset($this->pMVAST))   ? $N03->appendChild($dom->createElement('pMVAST',      number_format($this->pMVAST, 2, ".", "")))   : null;
                $N20 = (isset($this->pRedBCST)) ? $N03->appendChild($dom->createElement('pRedBCST',    number_format($this->pRedBCST, 2, ".", ""))) : null;
                $N21 = $N03->appendChild($dom->createElement('vBCST',       number_format($this->vBCST, 2, ".", "")));
                $N22 = $N03->appendChild($dom->createElement('pICMSST',     number_format($this->pICMSST, 2, ".", "")));
                $N23 = $N03->appendChild($dom->createElement('vICMSST',     number_format($this->vICMSST, 2, ".", "")));
                break;

            case '20' :
                $N04 = $N01->appendChild($dom->createElement('ICMS20'));
                $N11 = $N04->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N04->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = $N04->appendChild($dom->createElement('modBC',       $this->modBC));
                $N14 = $N04->appendChild($dom->createElement('pRedBC',      number_format($this->pRedBC, 2, ".", "")));
                $N15 = $N04->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $N16 = $N04->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")));
                $N17 = $N04->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")));
                break;

            case '30' :
                $N05 = $N01->appendChild($dom->createElement('ICMS30'));
                $N11 = $N05->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N05->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N18 = $N05->appendChild($dom->createElement('modBCST',     $this->modBCST));
                $N19 = $N05->appendChild($dom->createElement('pMVAST',      number_format($this->pMVAST, 2, ".", "")));
                $N20 = $N05->appendChild($dom->createElement('pRedBCST',    number_format($this->pRedBCST, 2, ".", "")));
                $N21 = $N05->appendChild($dom->createElement('vBCST',       number_format($this->vBCST, 2, ".", "")));
                $N22 = $N05->appendChild($dom->createElement('pICMSST',     number_format($this->pICMSST, 2, ".", "")));
                $N23 = $N05->appendChild($dom->createElement('vICMSST',     number_format($this->vICMSST, 2, ".", "")));
                break;

            case '40' :
            case '41' :
            case '50' :
                $N06 = $N01->appendChild($dom->createElement('ICMS40'));
                $N11 = $N06->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N06->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                break;

            case '51' :
                $N07 = $N01->appendChild($dom->createElement('ICMS51'));
                $N11 = $N07->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N07->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = (isset($this->modBC))  ? $N07->appendChild($dom->createElement('modBC',       $this->modBC))                    : null;
                $N14 = (isset($this->pRedBC)) ? $N07->appendChild($dom->createElement('pRedBC',      number_format($this->pRedBC, 2, ".", ""))) : null;
                $N15 = (isset($this->vBC))    ? $N07->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")))    : null;
                $N16 = (isset($this->pICMS))  ? $N07->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")))  : null;
                $N17 = (isset($this->vICMS))  ? $N07->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")))  : null;
                break;

            case '60' :
                $N08 = $N01->appendChild($dom->createElement('ICMS60'));
                $N11 = $N08->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N08->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N21 = $N08->appendChild($dom->createElement('vBCST',       number_format($this->vBCST, 2, ".", "")));
                $N23 = $N08->appendChild($dom->createElement('vICMSST',     number_format($this->vICMSST, 2, ".", "")));
                break;

            case '70' :
                $N09 = $N01->appendChild($dom->createElement('ICMS70'));
                $N11 = $N09->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N09->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = $N09->appendChild($dom->createElement('modBC',       $this->modBC));
                $N14 = $N09->appendChild($dom->createElement('pRedBC',      number_format($this->pRedBC, 2, ".", "")));
                $N15 = $N09->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $N16 = $N09->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")));
                $N17 = $N09->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")));
                $N18 = $N09->appendChild($dom->createElement('modBCST',     $this->modBCST));
                $N19 = (isset($this->pMVAST))   ? $N09->appendChild($dom->createElement('pMVAST',      number_format($this->pMVAST, 2, ".", "")))   : null;
                $N20 = (isset($this->pRedBCST)) ? $N09->appendChild($dom->createElement('pRedBCST',    number_format($this->pRedBCST, 2, ".", ""))) : null;
                $N21 = $N09->appendChild($dom->createElement('vBCST',       number_format($this->vBCST, 2, ".", "")));
                $N22 = $N09->appendChild($dom->createElement('pICMSST',     number_format($this->pICMSST, 2, ".", "")));
                $N23 = $N09->appendChild($dom->createElement('vICMSST',     number_format($this->vICMSST, 2, ".", "")));
                break;

            case '90' :
                $N10 = $N01->appendChild($dom->createElement('ICMS90'));
                $N11 = $N10->appendChild($dom->createElement('orig',        $this->orig));
                $N12 = $N10->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $N13 = $N10->appendChild($dom->createElement('modBC',       $this->modBC));
                $N15 = $N10->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $N14 = (isset($this->pRedBC)) ? $N10->appendChild($dom->createElement('pRedBC',      number_format($this->pRedBC, 2, ".", ""))) : null;
                $N16 = $N10->appendChild($dom->createElement('pICMS',       number_format($this->pICMS, 2, ".", "")));
                $N17 = $N10->appendChild($dom->createElement('vICMS',       number_format($this->vICMS, 2, ".", "")));
                $N18 = $N10->appendChild($dom->createElement('modBCST',     $this->modBCST));
                $N19 = (isset($this->pMVAST))   ? $N10->appendChild($dom->createElement('pMVAST',      number_format($this->pMVAST, 2, ".", "")))   : null;
                $N20 = (isset($this->pRedBCST)) ? $N10->appendChild($dom->createElement('pRedBCST',    number_format($this->pRedBCST, 2, ".", ""))) : null;
                $N21 = $N10->appendChild($dom->createElement('vBCST',       number_format($this->vBCST, 2, ".", "")));
                $N22 = $N10->appendChild($dom->createElement('pICMSST',     number_format($this->pICMSST, 2, ".", "")));
                $N23 = $N10->appendChild($dom->createElement('vICMSST',     number_format($this->vICMSST, 2, ".", "")));
                break;

        } // fim switch

        return $N01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO ICMS VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->orig);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->modBC);
        $sql.= ", ".$con->quote($this->pRedBC);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pICMS);
        $sql.= ", ".$con->quote($this->vICMS);
        $sql.= ", ".$con->quote($this->modBCST);
        $sql.= ", ".$con->quote($this->pMVAST);
        $sql.= ", ".$con->quote($this->pRedBCST);
        $sql.= ", ".$con->quote($this->vBCST);
        $sql.= ", ".$con->quote($this->pICMSST);
        $sql.= ", ".$con->quote($this->vICMSST);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ICMS: '.$qry->getMessage());
            return false;
        } else {
            $ICMS_id = $con->lastInsertID("ICMS", "ICMS_id");
        }
    }
}

// O01
class IPI {

    var $cIEnq;     // O02 - classe de IPI para cigarros e bebidas
    var $CNPJProd;  // O03 - CNPJ do produtor quando diferente do emitente
    var $cSelo;     // O04 - código do selo de controle do IPI
    var $qSelo;     // O05 - quantidade de selo de controle
    var $cEnq;      // O06 - código de enquadramento legal do IPI
    var $CST;       // O09 - código da situação tributária do IPI
    var $vBC;       // O10 - valor da BC do IPI
    var $qUnid;     // O11 - quantidade total na unidade padrão para tributação
    var $vUnid;     // O12 - valor por unidade tributável
    var $pIPI;      // O13 - alíquota do IPI
    var $vIPI;      // O14 - valor do IPI

    function __construct() {
    }

    function get_xml($dom) {

        $O01 = $dom->appendChild($dom->createElement('IPI'));
        $O02 = (!empty($this->cIEnq))    ? $O01->appendChild($dom->createElement('cIEnq',       $this->cIEnq))      : null;
        $O03 = (!empty($this->CNPJProd)) ? $O01->appendChild($dom->createElement('CNPJProd',    sprintf("%014s", $this->CNPJProd)))   : null;
        $O04 = (!empty($this->cSelo))    ? $O01->appendChild($dom->createElement('cSelo',       $this->cSelo))      : null;
        $O05 = (isset($this->qSelo))    ? $O01->appendChild($dom->createElement('qSelo',       $this->qSelo))      : null;
        $O06 = $O01->appendChild($dom->createElement('cEnq',        $this->cEnq));

        switch ($this->CST) {

            case '00' :
            case '49' :
            case '50' :
            case '99' :
                // O07 - grupo do CST 00,49,50 e 99
                $O07 = $O01->appendChild($dom->createElement('IPITrib'));
                $O09 = $O07->appendChild($dom->createElement('CST',     sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pIPI)) {
                    $O10 = $O07->appendChild($dom->createElement('vBC',     number_format($this->vBC, 2, ".", "")));
                    $O13 = $O07->appendChild($dom->createElement('pIPI',    number_format($this->pIPI, 2, ".", "")));
                } else {
                    $O11 = $O07->appendChild($dom->createElement('qUnid',   number_format($this->qUnid, 4, ".", "")));
                    $O12 = $O07->appendChild($dom->createElement('vUnid',   number_format($this->vUnid, 4, ".", "")));
                }
                $O14 = $O07->appendChild($dom->createElement('vIPI',    number_format($this->vIPI, 2, ".", "")));
                break;

            case '01' :
            case '02' :
            case '03' :
            case '04' :
            case '05' :
            case '51' :
            case '52' :
            case '53' :
            case '54' :
            case '55' :
                // O08 - grupo do CST 01,02,03,04,05,51,52,53,54 e 55
                $O08 = $O01->appendChild($dom->createElement('IPINT'));
                $O09 = $O08->appendChild($dom->createElement('CST',     sprintf("%02d", $this->CST)));
                break;
        }

        return $O01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO IPI VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->cIEnq);
        $sql.= ", ".$con->quote($this->CNPJProd);
        $sql.= ", ".$con->quote($this->cSelo);
        $sql.= ", ".$con->quote($this->qSelo);
        $sql.= ", ".$con->quote($this->cEnq);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->qUnid);
        $sql.= ", ".$con->quote($this->vUnid);
        $sql.= ", ".$con->quote($this->pIPI);
        $sql.= ", ".$con->quote($this->vIPI);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro IPI: '.$qry->getMessage());
            return false;
        } else {
            $IPI_id = $con->lastInsertID("IPI", "IPI_id");
        }
    }
}

// P01
class II {

    var $vBC;       // P02 - valor da BC do imposto de importação
    var $vDespAdu;  // P03 - valor das despesas aduaneiras
    var $vII;       // P04 - valor do imposto de importação
    var $vIOF;      // P05 - valor do imposto sobre operações financeiras

    function __construct() {
    }

    function get_xml($dom) {
        $P01 = $dom->appendChild($dom->createElement('II'));
        $P02 = $P01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
        $P03 = $P01->appendChild($dom->createElement('vDespAdu',    number_format($this->vDespAdu, 2, ".", "")));
        $P04 = $P01->appendChild($dom->createElement('vII',         number_format($this->vII, 2, ".", "")));
        $P05 = $P01->appendChild($dom->createElement('vIOF',        number_format($this->vIOF, 2, ".", "")));
        return $P01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO II VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vDespAdu);
        $sql.= ", ".$con->quote($this->vII);
        $sql.= ", ".$con->quote($this->vIOF);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro II: '.$qry->getMessage());
            return false;
        } else {
            $II_id = $con->lastInsertID("II", "II_id");
        }
    }
}

// Q01
class PIS {

    var $CST;       // Q06 - código de situação tributária do PIS
    var $vBC;       // Q07 - valor da BC do PIS
    var $pPIS;      // Q08 - alíquota do PIS
    var $vPIS;      // Q09 - valor do PIS
    var $qBCProd;   // Q10 - quantidade vendida
    var $vAliqProd; // Q11 - alíquota do PIS (em reais)

    function __construct() {
    }

    function get_xml($dom) {

        $Q01 = $dom->appendChild($dom->createElement('PIS'));

        switch ($this->CST) {

            case '01' :
            case '02' :
                $Q02 = $Q01->appendChild($dom->createElement('PISAliq'));
                $Q06 = $Q02->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $Q07 = $Q02->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $Q08 = $Q02->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
                $Q09 = $Q02->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;

            case '03' :
                $Q03 = $Q01->appendChild($dom->createElement('PISQtde'));
                $Q06 = $Q03->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $Q10 = $Q03->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                $Q11 = $Q03->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                $Q09 = $Q03->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;

            case '04' :
            case '06' :
            case '07' :
            case '08' :
            case '09' :
                $Q04 = $Q01->appendChild($dom->createElement('PISNT'));
                $Q06 = $Q04->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                break;

            case '99' :
                $Q05 = $Q01->appendChild($dom->createElement('PISOutr'));
                $Q06 = $Q05->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pPIS)) {
                    $Q07 = $Q05->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                    $Q08 = $Q05->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
                } else {
                    $Q10 = $Q05->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                    $Q11 = $Q05->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                }
                $Q09 = $Q05->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
                break;        

        } // fim switch

        return $Q01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO PIS VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pPIS);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro PIS: '.$qry->getMessage());
            return false;
        } else {
            $PIS_id = $con->lastInsertID("PIS", "PIS_id");
        }
    }
}

// R01
class PISST {

    var $vBC;       // R02 - valor da BC do PIS
    var $pPIS;      // R03 - alíquota do PIS
    var $qBCProd;   // R04 - quantidade vendida
    var $vAliqProd; // R05 - alíquota do PIS (em reais)
    var $vPIS;      // R06 - valor do PIS

    function __construct() {
    }

    function get_xml($dom) {
        $R01 = $dom->appendChild($dom->createElement('PISST'));
        if (isset($this->vBC) && isset($this->pPIS)) {
            $R02 = $R01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
            $R03 = $R01->appendChild($dom->createElement('pPIS',        number_format($this->pPIS, 2, ".", "")));
        } else {
            $R04 = $R01->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
            $R05 = $R01->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
        }
        $R06 = $R01->appendChild($dom->createElement('vPIS',        number_format($this->vPIS, 2, ".", "")));
        return $R01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO PISST VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pPIS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vPIS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro PISST: '.$qry->getMessage());
            return false;
        } else {
            $PISST_id = $con->lastInsertID("PISST", "PISST_id");
        }
    }
}

// S01
class COFINS {

    var $CST;       // S06 - código de situação tributária do COFINS
    var $vBC;       // S07 - valor da BC do COFINS
    var $pCOFINS;   // S08 - alíquota do COFINS (em percentual)
    var $qBCProd;   // S09 - quantidade vendida
    var $vAliqProd; // S10 - alíquota do COFINS (em reais)
    var $vCOFINS;   // S11 - valor do COFINS

    function __construct() {
    }

    function get_xml($dom) {
        $S01 = $dom->appendChild($dom->createElement('COFINS'));

        switch ($this->CST) {

            case '01' :
            case '02' :
                $S02 = $S01->appendChild($dom->createElement('COFINSAliq'));
                $S06 = $S02->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $S07 = $S02->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                $S08 = $S02->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
                $S11 = $S02->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

            case '03' :
                $S03 = $S01->appendChild($dom->createElement('COFINSQtde'));
                $S06 = $S03->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                $S09 = $S03->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                $S10 = $S03->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                $S11 = $S03->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

            case '04' :
            case '06' :
            case '07' :
            case '08' :
            case '09' :
                $S04 = $S01->appendChild($dom->createElement('COFINSNT'));
                $S06 = $S04->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                break;

            case '99' :
                $S05 = $S01->appendChild($dom->createElement('COFINSOutr'));
                $S06 = $S05->appendChild($dom->createElement('CST',         sprintf("%02d", $this->CST)));
                if (isset($this->vBC) && isset($this->pCOFINS)) {
                    $S07 = $S05->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
                    $S08 = $S05->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
                } else {
                    $S09 = $S05->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
                    $S10 = $S05->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
                }
                $S11 = $S05->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
                break;

        } // fim switch

        return $S01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO COFINS VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->CST);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pCOFINS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro COFINS: '.$qry->getMessage());
            return false;
        } else {
            $COFINS_id = $con->lastInsertID("COFINS", "COFINS_id");
        }
    }
}

// T01
class COFINSST {

    var $vBC;       // T02 - valor da BC do COFINS
    var $pCOFINS;   // T03 - alíquota do COFINS (em percentual)
    var $qBCProd;   // T04 - quantidade vendida
    var $vAliqProd; // T05 - alíquota do COFINS (em reias)
    var $vCOFINS;   // T06 - valor do COFINS

    function __construct() {
    }

    function get_xml($dom) {
        $T01 = $dom->appendChild($dom->createElement('COFINSST'));
        if (isset($this->vBC) && isset($this->pCOFINS)) {
            $T02 = $T01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
            $T03 = $T01->appendChild($dom->createElement('pCOFINS',     number_format($this->pCOFINS, 2, ".", "")));
        } else {
            $T04 = $T01->appendChild($dom->createElement('qBCProd',     number_format($this->qBCProd, 4, ".", "")));
            $T05 = $T01->appendChild($dom->createElement('vAliqProd',   number_format($this->vAliqProd, 4, ".", "")));
        }
        $T06 = $T01->appendChild($dom->createElement('vCOFINS',     number_format($this->vCOFINS, 2, ".", "")));
        return $T01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO COFINSST VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->pCOFINS);
        $sql.= ", ".$con->quote($this->qBCProd);
        $sql.= ", ".$con->quote($this->vAliqProd);
        $sql.= ", ".$con->quote($this->vCOFINS);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro COFINSST: '.$qry->getMessage());
            return false;
        } else {
            $COFINSST_id = $con->lastInsertID("COFINSST", "COFINSST_id");
        }
    }
}

// U01
class ISSQN {

    var $vBC;       // U02 - valor da BC do ISSQN
    var $vAliq;     // U03 - alíquota do ISSQN
    var $vISSQN;    // U04 - valor do ISSQN
    var $cMunFG;    // U05 - código do município do fato gerador do ISSQN
    var $cListServ; // U06 - código da lista de serviços

    function __construct() {
    }

    function get_xml($dom) {
        $U01 = $dom->appendChild($dom->createElement('ISSQN'));
        $U02 = $U01->appendChild($dom->createElement('vBC',         number_format($this->vBC, 2, ".", "")));
        $U03 = $U01->appendChild($dom->createElement('vAliq',       number_format($this->vAliq, 2, ".", "")));
        $U04 = $U01->appendChild($dom->createElement('vISSQN',      number_format($this->vISSQN, 2, ".", "")));
        $U05 = $U01->appendChild($dom->createElement('cMunFG',      $this->cMunFG));
        $U06 = $U01->appendChild($dom->createElement('cListServ',   $this->cListServ));
        return $U01;
    }

    function insere($con, $imposto_id) {
        $sql = "INSERT INTO ISSQN VALUES (NULL";
        $sql.= ", ".$con->quote($imposto_id);
        $sql.= ", ".$con->quote($this->vBC);
        $sql.= ", ".$con->quote($this->vAliq);
        $sql.= ", ".$con->quote($this->vISSQN);
        $sql.= ", ".$con->quote($this->cMunFG);
        $sql.= ", ".$con->quote($this->cListServ);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro ISSQN: '.$qry->getMessage());
            return false;
        } else {
            $ISSQN_id = $con->lastInsertID("ISSQN", "ISSQN_id");
        }
    }
}

// X33
class lacres {

    var $nLacre;    // X33 - grupo de lacres

    function __construct() {
    }

    function get_xml($dom) {
        $X33 = $dom->appendChild($dom->createElement('lacres'));
        $X34 = $X33->appendChild($dom->createElement('nLacre', $this->nLacre));
        return $X33;
    }

    function insere($con, $vol_id) {
        $sql = "INSERT INTO lacres VALUES (NULL";
        $sql.= ", ".$con->quote($vol_id);
        $sql.= ", ".$con->quote($this->nLacre);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro lacres: '.$qry->getMessage());
            return false;
        } else {
            $lacres_id = $con->lastInsertID("lacres", "lacres_id");
        }
    }
}



/* NIVEL 5 ********************************************************************/

// I25
class adi {

    var $nAdicao;       // I26 - número da adição
    var $nSeqAdic;      // I27 - número sequencial do item dentro da adição
    var $cFabricante;   // I28 - código do fabricante estrangeiro
    var $vDescDI;       // I29 - valor do desconto do item da DI - adição

    function __construct() {
    }

    function get_xml($dom) {
        $I25 = $dom->appendChild($dom->createElement('adi'));
        $I26 = $I25->appendChild($dom->createElement('nAdicao',     $this->nAdicao));
        $I27 = $I25->appendChild($dom->createElement('nSeqAdic',    $this->nSeqAdic));
        $I28 = $I25->appendChild($dom->createElement('cFabricante', $this->cFabricante));
        $I29 = (isset($this->vDescDI)) ? $I25->appendChild($dom->createElement('vDescDI',     number_format($this->vDescDI, 2, ".", ""))) : null;
        return $I25;
    }

    function insere($con, $DI_id) {
        $sql = "INSERT INTO adi VALUES (NULL";
        $sql.= ", ".$con->quote($DI_id);
        $sql.= ", ".$con->quote($this->nAdicao);
        $sql.= ", ".$con->quote($this->nSeqAdic);
        $sql.= ", ".$con->quote($this->cFabricante);
        $sql.= ", ".$con->quote($this->vDescDI);
        $sql.= ")";

        $qry = $con->query($sql);

        if (MDB2::isError($qry)) {
            set_error('Erro adi: '.$qry->getMessage());
            return false;
        } else {
            $adi_id = $con->lastInsertID("adi", "adi_id");
        }
    }
}

// L105
class CIDE {

    var $qBCprod;   // L106 - BC da CIDE
    var $vAliqProd; // L107 - valor da alíquota da CIDE
    var $vCIDE;     // L108 - valor da CIDE

    function __construct() {
    }

    function get_xml($dom) {
        $L105 = $dom->appendChild($dom->createElement('CIDE'));
        $L106 = $L105->appendChild($dom->createElement('qBCprod',   $this->qBCprod));
        $L107 = $L105->appendChild($dom->createElement('vAliqProd', $this->vAliqProd));
        $L108 = $L105->appendChild($dom->createElement('vCIDE',     $this->vCIDE));
        return $L105;
    }
}

// L109
class ICMSComb {

    var $vBCIMCS;   // L110 - BC do ICMS
    var $vICMS;     // L111 - valor do ICMS
    var $vBCICMSST; // L112 - BC do ICMS ST retido
    var $vICMSST;   // L113 - valor do ICMS ST retido

    function __construct() {
    }

    function get_xml($dom) {
        $L109 = $dom->appendChild($dom->createElement('ICMSComb'));
        $L110 = $L109->appendChild($dom->createElement('vBCIMCS',   $this->vBCICMS));
        $L111 = $L109->appendChild($dom->createElement('vICMS',     $this->vICMS));
        $L112 = $L109->appendChild($dom->createElement('vBCICMSST', $this->vBCICMSST));
        $L113 = $L109->appendChild($dom->createElement('vICMSST',   $this->vICMSST));
        return $L109;
    }
}

// L114
class ICMSInter {

    var $vBCICMSSTDest; // L115 - BC do ICMS ST da UF de destino
    var $vICMSSTDest;   // L116 - valor do ICMS ST da UF de destino

    function __construct() {
    }

    function get_xml($dom) {
        $L114 = $dom->appendChild($dom->createElement('ICMSInter'));
        $L115 = $L114->appendChild($dom->createElement('vBCICMSSTDest', $this->vBCICMSSTDest));
        $L116 = $L114->appendChild($dom->createElement('vICMSSTDest',   $this->vICMSSTDest));
        return $L114;
    }
}

// L117
class ICMSCons {

    var $vBCICMSSTCons; // L118 - BC do ICMS ST da UF de consumo
    var $vICMSSTCons;   // L119 - valor do ICMS ST da UF de consumo
    var $UFcons;        // L120 - sigla da UF de consumo

    function __construct() {
    }

    function get_xml($dom) {
        $L117 = $dom->appendChild($dom->createElement('ICMSCons'));
        $L118 = $L114->appendChild($dom->createElement('vBCICMSSTCons', $this->vBCICMSSTCons));
        $L119 = $L114->appendChild($dom->createElement('vICMSSTCons',   $this->vICMSSTCons));
        $L120 = $L114->appendChild($dom->createElement('UFcons',        $this->UFcons));
        return $L117;
    }
}


?>
