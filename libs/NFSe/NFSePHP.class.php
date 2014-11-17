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
 *
 * @package   NFePHP
 * @name      NFSePHP
 * @version   alfa (não usável)
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2011 &copy; NFePHP
 * @link      http://www.nfephp.org/
 * @author    Giuliano Nascimento <giusoft at hotmail dot com>
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Roberto Leite Machado <linux dot rlm at gamil dot com>
 * 
 * 
 * TODO: Este arquivo está incompleto e deve ser completamente revisado foi postado apenas para permitir as contribuições de todos os interessados
 * O conteúdo foi apenas copiado do email do seu autor datado de 14/02/2012. 
 * 
 * ##### NÃO PRONTO PARA PRODUÇÃO #####
 */

class NFSe {

    public $idRPS='';
    public $idLoteRPS='';
    public $numeroLote='';
    public $nomeFantasia='';
    public $CNPJ='';
    public $IM='';
    public $numNFSe='';
    public $numSerie='';
    public $tipo='';
    public $natOperacao='';
    public $optanteSimplesNacional='';
    public $incentivadorCultural='';
    public $status='';
    public $cMun='';
    public $aItens=array();
    public $tomaCPF='';
    public $tomaCNPJ='';
    public $tomaRazaoSocial='';
    public $tomaEndLogradouro='';
    public $tomaEndNumero='';
    public $tomaEndComplemento='';
    public $tomaEndBairro='';
    public $tomaEndxMun='';
    public $tomaEndcMun='';
    public $tomaEndUF='';
    public $tomaEndCep='';
    public $tomaEmail='';
    public $nfsexml='';
    public $arqtxt='';
    public $errMsg='';
    public $cert='';
    public $errStatus=false;
    public $priKey='';
    public $pubKey='';
    
    
    
    function __construct(){
        
    }
    
    //Conteudo do array aItens
    //valor,valorDeducoes,valorPis,valorCofins,valorIr,valorCsll,issRetido,valorIss,valorIssRetido,
    //outrasRetencoes,baseCalculo,aliquota,valorLiquidoNfse,descontoIncondicionado,descontoCondicionado,
    //itemListaServico,codigoCnae,discriminacao
    
    public function buildNFSe() {
        $idRps="rps1";
        $idLote="lote1";
        //cria o objeto DOM para o xml
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $Rps=$dom->createElement("Rps");
        $infRps = $dom->createElement("InfRps");
        $infRps->setAttribute("id", $this->idRPS);
        // Identificação
        $IdentificacaoRps = $dom->createElement("IdentificacaoRps");
        $Numero= $dom->createElement("Numero",$this->numNFSe);
        $Serie= $dom->createElement("Serie",$this->numSerie);
        $Tipo= $dom->createElement("Tipo",$this->tipo);
        $IdentificacaoRps->appendChild($Numero);
        $IdentificacaoRps->appendChild($Serie);
        $IdentificacaoRps->appendChild($Tipo);
        $infRps->appendChild($IdentificacaoRps);
        $infRps->appendChild($dom->createElement("DataEmissao",date("Y-m-d")."T".date("H:i:s")));
        $infRps->appendChild($dom->createElement("NaturezaOperacao",$this->natOperacao));
        $infRps->appendChild($dom->createElement("OptanteSimplesNacional",$this->optanteSimplesNacional));
        $infRps->appendChild($dom->createElement("IncentivadorCultural",$this->incentivadorCultural));
        $infRps->appendChild($dom->createElement("Status",$this->status));
        //cria as variáveis zeradas    
        $qtd=$v_total=$total_itens=$t_icms=$t_ipi=$total_pb=$total_pl=0;
        $temIcms=false;
        foreach ($this->aItens as $item) {
            $qtd++;
            $Servico= $dom->createElement("Servico");
            $Valores=$dom->createElement("Valores");
            $ValorServicos=$dom->createElement("ValorServicos",number_format($item['valor'],2, '.', ''));
            $ValorDeducoes=$dom->createElement("ValorDeducoes",number_format($item['valorDeducoes'],2, '.', ''));
            $ValorPis=$dom->createElement("ValorPis",number_format($item['valorPis'],2, '.', ''));
            $ValorCofins=$dom->createElement("ValorCofins",number_format($item['valorCofins'],2, '.', ''));
            $ValorIr=$dom->createElement("ValorIr",number_format($item['valorIr'],2, '.', ''));
            $ValorCsll=$dom->createElement("ValorCsll",number_format($item['valorCsll'],2, '.', ''));
            $IssRetido=$dom->createElement("IssRetido",$item['issRetido']);
            $ValorIss=$dom->createElement("ValorIss",number_format($item['valorIss'],2, '.', ''));
            $ValorIssRetido=$dom->createElement("ValorIssRetido",number_format($item['valorIssRetido'],2, '.', ''));
            $OutrasRetencoes=$dom->createElement("OutrasRetencoes",number_format($item['outrasRetencoes'],2, '.', ''));
            $BaseCalculo=$dom->createElement("BaseCalculo",number_format($item['baseCalculo'],2, '.', ''));
            $Aliquota=$dom->createElement("Aliquota",number_format($item['aliquota'],2, '.', ''));
            $ValorLiquidoNfse=$dom->createElement("ValorLiquidoNfse",number_format($item['valorLiquidoNfse'],2, '.', ''));
            $DescontoIncondicionado=$dom->createElement("DescontoIncondicionado",number_format($item['descontoIncondicionado'],2, '.', ''));
            $DescontoCondicionado=$dom->createElement("DescontoCondicionado",number_format($item['descontoCondicionado'],2, '.', ''));
            $Valores->appendChild($ValorServicos);
            $Valores->appendChild($ValorDeducoes);
            $Valores->appendChild($IssRetido);
            $Valores->appendChild($ValorIss);
            $Valores->appendChild($ValorIssRetido);
            $Valores->appendChild($BaseCalculo);
            $Valores->appendChild($Aliquota);
            // Detalhes do serviço
            $ItemListaServico=$dom->createElement("ItemListaServico",trim($item['itemListaServico']));
            $CodigoTributacaoMunicipio=$dom->createElement("CodigoCnae",trim($item['codigoCnae']));
            $Discriminacao=$dom->createElement("Discriminacao",$this->tiraAcentos($item['discriminacao']));
            $CodigoMunicipio=$dom->createElement("CodigoMunicipio",$this->cMun);
            $Servico->appendChild($Valores);
            $Servico->appendChild($ItemListaServico);
            $Servico->appendChild($CodigoTributacaoMunicipio);
            $Servico->appendChild($Discriminacao);
            $Servico->appendChild($CodigoMunicipio);
            $infRps->appendChild($Servico);
        } //fim foreach aItens
        // Prestador do Serviço
        $Prestador=$dom->createElement("Prestador");
        $Cnpj=$dom->createElement("Cnpj",$this->CNPJ);
        $InscricaoMunicipal=$dom->createElement("InscricaoMunicipal",$this->IM);
        $Prestador->appendChild($Cnpj);
        $Prestador->appendChild($InscricaoMunicipal);
        // Tomador do Serviço
        $Tomador=$dom->createElement("Tomador");
        $IdentificacaoTomador=$dom->createElement("IdentificacaoTomador");
        $CpfCnpj=$dom->createElement("CpfCnpj");
        $TomadorCpf=$dom->createElement("Cpf",$this->tomaCPF);
        $TomadorCnpj=$dom->createElement("Cnpj",$this->tomaCNPJ);
        if ($this->tomaCPF != ''){
            $CpfCnpj->appendChild($TomadorCpf);
        } else {
            $CpfCnpj->appendChild($TomadorCnpj);
        }    
        $IdentificacaoTomador->appendChild($CpfCnpj);
        $RazaoSocial=$dom->createElement("RazaoSocial",$this->tomaRazaoSocial);
        $EEndereco=$dom->createElement("Endereco");
        $Endereco=$dom->createElement("Endereco",$this->tomaEndLogradouro);
        $Numero=$dom->createElement("Numero",$this->tomaEndNumero);
        $Bairro=$dom->createElement("Bairro",$this->tomaEndBairro);
        $CodigoMunicipio=$dom->createElement("CodigoMunicipio",$this->tomaEndcMun);
        $Uf=$dom->createElement("Uf",$this->tomaEndUF);
        $Cep=$dom->createElement("Cep",$this->tomaEndCep);
        $EEndereco->appendChild($Endereco);
        $EEndereco->appendChild($Numero);
        $EEndereco->appendChild($Bairro);
        $EEndereco->appendChild($CodigoMunicipio);
        $EEndereco->appendChild($Uf);
        $EEndereco->appendChild($Cep);
        $Tomador->appendChild($IdentificacaoTomador);
        $Tomador->appendChild($RazaoSocial);
        $Tomador->appendChild($EEndereco);
        if ($this->tomaEmail != '') {
            $Contato=$dom->createElement("Contato");
            $Email=$dom->createElement("Email",$this->tomaEmail);
            $Contato->appendChild($Email);
            $Tomador->appendChild($Contato);
        }
        $infRps->appendChild($Prestador);
        $infRps->appendChild($Tomador);
        // Serviços
        $Rps->appendChild($infRps);
        $ListaRps=$dom->createElement("ListaRps");
        $ListaRps->appendChild($Rps);
        $LoteRps=$dom->createElement("LoteRps");
        $LoteRps->setAttribute("id", $this->idLoteRPS);
        $NumeroLote=$dom->createElement("NumeroLote",$this->numeroLote);
        $QuantidadeRps=$dom->createElement("QuantidadeRps",1);
        $Cnpj=$dom->createElement("Cnpj",$this->CNPJ);
        $InscricaoMunicipal=$dom->createElement("InscricaoMunicipal",$this->IM);
        $EnviarLoteRpsEnvio=$dom->createElement("EnviarLoteRpsEnvio");
        $EnviarLoteRpsEnvio->setAttribute("xmlns", "http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd");
        $LoteRps->appendChild($NumeroLote);
        $LoteRps->appendChild($Cnpj);
        $LoteRps->appendChild($InscricaoMunicipal);
        $LoteRps->appendChild($QuantidadeRps);
        $LoteRps->appendChild($ListaRps);
        $EnviarLoteRpsEnvio->appendChild($LoteRps);
        $dom->appendChild($EnviarLoteRpsEnvio);
        $xml= $dom->saveXML();
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>','<?xml version="1.0" encoding="UTF-8" standalone="no"?>',$xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>','',$xml);
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml);
        $xml = str_replace("\n","",$xml);
        $xml = str_replace("  "," ",$xml);
        $xml = str_replace("  "," ",$xml);
        $xml = str_replace("  "," ",$xml);
        $xml = str_replace("  "," ",$xml);
        $xml = str_replace("  "," ",$xml);
        $xml = str_replace("> <","><",$xml);
        $this->nfsexml = $xml;
        $this->signNFSe("InfRps");
        $this->signNFSe("LoteRps");
        $this->nfsexml=str_replace('<?xml version="1.0"?>','',$this->nfsexml);
    } // fim 

    //Gera arquivo TXT para importação da SEFAZ
    public function geraTxtNFSE() {
        $hoje=date("Ymd");
        $dataHora=date("YmdHis");
        // REGISTRO 1 - Header do arquivo
        $s="1"; // Tipo de Registro
        $s.="103"; // Vesão do Layout
        $s.=padrl($this->IM, 26, "r"); // Insc. Municipal do prestador
        $s.="2"; // Indicador de CPF/CNPJ -> 1 = CPF , 2 = CNPJ
        $s.=padrl($this->CNPJ, 14,"l"); // CPF / CNPJ do prestador
        $s.=$this->optanteSimplesNacional ; // Optante pelo simples
        $s.=$hoje; // Data de início do período
        $s.=$hoje; // Data de fim do período
        $s.=padrl("1", 5, "l", "0"); // Qtd de NFS-e informadas
        $s.=padrl(" ", 324, "l"); // Preencher com 324 espações em branco
        $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
        $s.=NL;
        foreach ($this->aItens as $item) {
            // REGISTRO 2 - Cabeçalho da NFS-e
            $s.="2"; // Tipo de registro
            $s.=padrl($this->nfeid, 20, "l", "0"); // Sequencial da NFS-e
            $s.=$dataHora; // Data e Hora da NFS-e
            $s.=$item['tipoRecolhimento']; // Tipo de recolhimento (N - Normal ou R - Retido na fonte)
            $s.="T"; // Situação da nota fiscal (T - I - F - C - E - J)
            $s.=padrl(" ", 8, "r"," "); // Data de cancelamento
            $s.=$this->cMun; // Codigo IBGE do municipio de prestação do serviço
            $s.=padrl(number_format($item['valor'],2,"",""), 15, "l", "0"); // Valor do serviço
            $s.=padrl(number_format($item['valorDeducoes'],2,"",""), 15, "l", "0"); // valor das deduções
            $s.=padrl(number_format($item['valorIss'],2,"",""), 15, "l", "0"); // Valor da retenção do PIS
            $s.=padrl(number_format($item['valorCofins'],2,"",""), 15, "l", "0"); // Valor da retenção do COFINS
            $s.=padrl(number_format($item['valorInss'],2,"",""), 15, "l", "0"); // Valor da retenção do INSS
            $s.=padrl(number_format($item['valorIr'],2,"",""), 15, "l", "0"); // Valor da retenção do IR
            $s.=padrl(number_format($item['valorRetCsll'],2,"",""), 15, "l", "0"); // Valor da retenção do CSLL
            $s.=padrl(number_format($item['valorIssqn'],2,"",""), 15, "l", "0"); // Valor do ISSQN
            $s.=padrl(" ", 219, "l"); // Preencher com 219 espações em branco
            $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
            $s.=NL;

            // REGISTRO 3 - Identificação do tomador da NFS-e
            $s.="3"; // Tipo de registro
            $s.=padrl($this->nfeid, 20, "r", "0"); // Sequencial da NFS-e
            $s.="2"; // Indicador de CPF/CNPJ do Tomador
            $s.=padrl($this->tomaCNPJ, 14,"l"); // CPF/CNPJ do tomador
            $s.=padrl($this->tomaRazaoSocial, 50, "r"); // Nome do tomador (Nome ou razão social)
            $s.=padrl($this->nomeFantasia, 50, "r"); // Nome fantasia
            $s.=padrl(" ", 3, "l"); // Tipo de endereço do tomador
            $s.=padrl($this->tomaEndLogradouro, 50, "r"); // Endereço do tomador
            $s.=padrl($this->tomaEndNumero, 10, "l","0"); // Número do endereço do tomador
            $s.=padrl($this->tomaEndComplemento, 20, "r"); // Complemento do endereço do tomador
            $s.=padrl($this->tomaEndBairro, 30, "r"); // Bairro do tomador
            $s.=padrl($this->tomaEndxMun, 50, "r"); // Cidade do tomador
            $s.=padrl($this->tomaEndUF, 2, "r");; // UF do tomador
            $s.=padrl($this->tomaEndCep, 8, "r"); // CEP do tomador
            $s.=padrl($this->tomaEmail, 60, "r"); // Email do tomador
            $s.=padrl(" ", 22, "l"); // Preencher com 22 espações em branco
            $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
            $s.=NL;

            // REGISTRO 4 - Descrição da NFS-e
            $s.="4"; // Tipo de registro
            $s.=padrl($this->nfseid, 20, "l", "0"); // Sequencial da NFS-e
            $s.=padrl(" ", 255, "l"); // Descrição da nota
            $s.=padrl(" ", 115, "l"); // Preencher com 22 espações em branco
            $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
            $s.=NL;

            // REGISTRO 5 - Descrição do serviço realizado
            $s.="5"; // Tipo de registro
            $s.=padrl($this->nfseid, 20, "l", "0"); // Sequencial da NFS-e
            $s.=padrl($item['codigo'], 4, "l"); // Codigo do serviço pretasdo
            $s.=padrl($item['codigoMunicipio'], 20, "l"); // Código tributação município
            $s.=padrl(number_format($item['valor'],2,"",""), 15, "l", "0"); // Valor do serviço
            $s.=padrl(number_format($item['valorDeducoes'],2,"",""), 15, "l", "0"); // Valor dedução
            $s.=padrl(number_format($item['aliquota'],2,"",""), 4, "l", "0"); // Alíquota
            $s.=padrl($item['unidade'], 20, "l"); // Unidade
            $s.=padrl(number_format($item['quantidade'],2,"",""),8,"l","0"); // Quantidade
            $s.=padrl($item['discriminacao'], 255, "r"," "); // Descrição do serviço
            $s.=padrl(" ",20,"l"); // Alvará               
            $s.=padrl(" ", 9, "l"); // Preencher com 9 espações em branco
            $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
            $s.=NL;
        }
        
        // REGISTRO 6 - Indicador de final de arquivo
        $s.="6"; // Tipo de registro
        $s.=padrl(" ", 390, "l"); // Preencher com 390 espações em branco
        $s.=padrl($this->numeroLote, 8, "l","0"); // Sequencial do registro
        $s.=NL;
        
        //$sql="update nfse set txt='$s' where id=".$this->nfeid;
        //gQuery($sql);
               
        $this->arqtxt=$s;
    } //fim

    public function envelopa() {
        // Envelopa
        $idLote="1";
        // cria DOM pro nfexml
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        //echo $this->nfexml;exit;
        $dom->loadXML($this->nfsexml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        //cria o objeto DOM para o novo xml
        $dom2 = new DOMDocument('1.0', 'UTF-8');
        $dom2->formatOutput = true;
        $dom2->preserveWhiteSpace = false;
        $node = $dom->getElementsByTagName("Rps")->item(0);
        $Rps=$node->C14N(FALSE,FALSE,NULL,NULL);
        $l.='<LoteRps id="'.$idLote.'">';
        $l.='<NumeroLote>'.$this->numeroLote.'</NumeroLote>';
        $l.='<Cnpj>'.$this->CNPJ.'</Cnpj>';
        $l.='<InscricaoMunicipal>'.$this->IM.'</InscricaoMunicipal>';
        $l.='<QuantidadeRps>1</QuantidadeRps>';
        $l.='<ListaRps>'.$Rps.'</ListaRps>';
        $l.='</LoteRps>';
        $this->nfsexml=$l;
        $this->signNFSe("LoteRps");
        $this->nfsexml=str_replace('<?xml version="1.0"?>','',$this->nfsexml);
        $this->nfsexml='<?xml version="1.0" encoding="UTF-8"?><EnviarLoteRpsEnvio xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">'.$this->nfsexml.'</EnviarLoteRpsEnvio>';

    } //fim 
               
    public function limpaNumero($num){
        return(str_replace(".00","",floatval($num)));
    }

    // Assina XML
    public function signNFSe($tag='infNFe') {
        global $http_lib;
        $sai=false;
        $nfefile=$this->nfsexml;
        $this->nfeTools= new gNFeTools($this->config());
        if ($tag == 'infNFe') {
            if ($this->errMsg=="") {
                if ( $signn = $this->signXML($nfefile, $tag) ) {
                    unlink($this->arqxml);
                    if ( !file_put_contents($this->arqxml , $signn) ) {
                        $this->erros[]=M."Houve uma falha ao salvar a NFe assinada.";
                    } else {
                        $this->nfsexml=$signn;
                        $sai=true;
                    }
                } else {
                    $this->erros[]=M."Houve uma falha ao assinar a NFe.";
                } 
            } else {
                $this->erros[]=M.$this->errMsg." (".$this->cert.")";
            }
        } else {
            $this->nfexml=$this->signNFSe($nfefile, $tag);
        }
        return($sai);
    } //fim signNFSe

    /**
     * signXML
     * Assinador TOTALMENTE baseado em PHP para arquivos XML
     * este assinador somente utiliza comandos nativos do PHP para assinar
     * os arquivos XML
     *
     * @name signXML
     * @param	string $docxml String contendo o arquivo XML a ser assinado
     * @param   string $tagid TAG do XML que devera ser assinada
     * @return	mixed false se houve erro ou string com o XML assinado
     */
    public function signXML($docxml, $tagid=''){
            if ( $tagid == '' ){
                $this->errMsg = "Uma tag deve ser indicada para que seja assinada!!\n";
                $this->errStatus = true;
                return false;
            }
            if ( $docxml == '' ){
                $this->errMsg = "Um xml deve ser passado para que seja assinado!!\n";
                $this->errStatus = true;
                return false;
            }
            // obter o chave privada para a ssinatura
            $fp = fopen($this->priKEY, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            // limpeza do xml com a retirada dos CR, LF e TAB
            $order = array("\r\n", "\n", "\r", "\t");
            $replace = '';
            $docxml = str_replace($order, $replace, $docxml);
            // carrega o documento no DOM
            $xmldoc = new DOMDocument();
            $xmldoc->preservWhiteSpace = false; //elimina espaços em branco
            $xmldoc->formatOutput = false;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            $xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $root = $xmldoc->documentElement;
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/','', $id);
            //extrai os dados da tag para uma string
            $dados = $node->C14N(false,false,NULL,NULL);
            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,true);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS($this->URLdsig,'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //Cannocalization
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
            //SignatureMethod
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);
            //Reference
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$id);
            //Transforms
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
            //DigestMethod
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
            //DigestValue
            $newNode = $xmldoc->createElement('DigestValue',$digValue);
            $Reference->appendChild($newNode);
            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(false,false,NULL,NULL);
            //inicializa a variavel que irá receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados,$signature,$pkeyid);
            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);
            //SignatureValue
            $newNode = $xmldoc->createElement('SignatureValue',$signatureValue);
            $Signature->appendChild($newNode);
            //KeyInfo
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($this->pubKEY);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $docxml = $xmldoc->saveXML();
            // libera a memoria
            openssl_free_key($pkeyid);
            //retorna o documento assinado
            return $docxml;
    } //fim signXML


    // Valida XML assinado
    function valida($tipo="nfe") {
        global $gPathLib,$gPathDefault;
        if ($gPathLib<>""){
            $arq=$gPathLib.NFEPHP."/schemes/";
        } else {
            $arq=$gPathDefault.NFEPHP."/schemes/";
        }

        if ($tipo=="nfe"){
            $xsd=$arq.$this->schemes.'/nfe_v2.00.xsd';
        } else {
            $xsd=$arq.'/NFSE_SSA/nfse.xsd';
        }    
        $sai=$this->nfeTools->validXML($this->nfexml, $xsd);
        if ($sai['error']<>'') {
            $e=explode(";",$sai['error']);
            foreach ($e as $el)
                $this->erros[]=trim($el);
        }
        return($sai);
    } //fim valida


    // Enviar para Sefaz
    function envia($xml="",$numero="") {
        $sai=true;
        //$sql="select * from nfe_numeros where id_armazens=0"; // numero do lote
        //$rs=gQuery($sql);
        //if ($rs->EOF) {
        //    $sql="insert into nfe_numeros (id_armazens,numero) values (0,0)";
        //    gQuery($sql);
            $numeroLote=1;
        //} else {
        //    $numeroLote=intval($rs->fields['numero'])+1;
        //}
        //$sql="update nfe_numeros set numero=$numeroLote where id_armazens=0";
        //gQuery($sql);
        //if ($xml==""){
        //    $xml=$this->nfexml;
        //}
        if ($ret = $this->sendLot(array($xml),$numeroLote)) {
            if ($ret['cStat']=='103') {
                $this->recibo=$ret['nRec'];
                $this->dataRecibo=$ret['dhRecbto'];
            } else {
                $this->erros[]=M.str_pad($ret['cStat'],10,' ')." ".$ret['xMotivo'];
                $sai=false;
            }
        }
        return($sai);
    } //fim envia

    
    public function enviaNFSe($xml="",$numero="") {
        $sai=true;
        if (!is_object($this->nfeTools)) {
            $this->nfeTools= new gNFeTools($this->config());
        }

        if ($xml=="") {
            $xml=$this->nfexml;
        }
        //$xml=file_get_contents('/tmp/envio.xml');
        $xml=str_replace("\n",'',$xml);
        //echo "<textarea rows='20' cols='80'>$xml<textarea><br><br>";
        if ($ret = $this->sendRps($xml,$this->numeroLote)) {
            if ($ret['Protocolo']<>'') {
                // Nova aceita... Salva no banco
                //$sql="update nfse_numeros set numero_nota=".$this->c['numeroNota']." where ambiente=".$this->c['ambiente']." and id_empresa=".$this->id_empresa;
                //gQuery($sql);
                //$sql="update nfse set situacao='Aceita',xml='$xml',data_recibo='".$ret['DataRecebimento']."',protocolo='".$ret['Protocolo']."' where id=".$this->nfeid;
                //gQuery($sql);
                $this->protocolo=$ret['Protocolo'];
                $this->numeroNota=$numeroNota;
                $this->numeroLote=$numeroLote;
            } else {
                //$sql="update nfse set situacao='Rejeitada',xml='$xml',mensagens='Codigo ".$ret['Codigo']."<br>".$ret['Mensagem']."<br>".$ret['Correcao']."' where id=".$this->nfeid;
                //gQuery($sql);
                $this->erros[]=str_pad($ret['Codigo'],10,' ')." ".$ret['Mensagem']."<br>".$ret['Correcao'];
                $sai=false;
            }
        } else {
              //$sql="update nfse set situacao='Nao enviada',xml='$xml',mensagens='Codigo ".$ret['Codigo']."<br>".$ret['Mensagem']."<br>".$ret['Correcao']."' where id=".$this->nfeid;
              //gQuery($sql);
              $this->erros[]=str_pad('E000',10,' ')." Nao foi possivel enviar para a SEFAZ, provavelmente por problema no certificado.";
              $sai=false;
        }
        return($sai);
    } //fim enviaNFSe

    public function consultaRps($protocolo) {
        $sai=$this->__consultRps($this->CNPJ,$this->IM,trim($protocolo));
        return $sai;
    } //fim consultaRps


    public function consultaSituacaoRps($protocolo) {
        $sai=$this->__consultSitRps($this->CNPJ,$this->IM,trim($protocolo));
        //Código de situação de lote de RPS
        //1 – Não Recebido
        //2 – Não Processado
        //3 – Processado com Erro
        //4 – Processado com Sucesso
        $situacao = FALSE;
        if ($sai){
            $situacao = $sai['Situacao'];
        }
        return($situacao);
    } //fim consultaSituacaoRps

    /**
     * sendRps
     * Envia lote de Notas Fiscais de Serviço 
     *
     * @name sendRps
     * @package NFePHP
    **/
    public function sendRps($aNFSe,$idLote) {
        //identificação do serviço
        $servico = 'EnvioLoteRPS';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['URL'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = 'http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd';
        // monta string com todas as NFe enviadas no array
        $sNFSe = str_ireplace('<?xml version="1.0" encoding="utf-8"?>','',$aNFSe);
        $sNFSe = str_ireplace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>','',$sNFSe);
        //envia dados via SOAP
        //montagem dos dados da mensagem SOAP
        $dados = trim(str_replace("\n","",$sNFSe));
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);
        if ( isset($retorno) ) {
            //pega os dados do array retornado pelo NuSoap
            $retorno=str_replace('&lt;','<',$retorno);
            $retorno=str_replace('&gt;','>',$retorno);
            $retorno=str_replace('<?xml version="1.0" encoding="utf-8"?>','',$retorno);
            $xmlresp = utf8_encode($retorno);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $this->errStatus = true;
                $this->errMsg = 'Houve uma falha na comunicação SOAP!!';
                return FALSE;
            }
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = false;
            $doc->preserveWhiteSpace = false;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $aRet['DataRecebimento'] = $doc->getElementsByTagName('DataRecebimento')->item(0)->nodeValue;
            $aRet['Codigo'] = $doc->getElementsByTagName('Codigo')->item(0)->nodeValue;
            $aRet['Mensagem'] = $doc->getElementsByTagName('Mensagem')->item(0)->nodeValue;
            $aRet['Correcao'] = $doc->getElementsByTagName('Correcao')->item(0)->nodeValue;
            $aRet['NumeroLote'] = $doc->getElementsByTagName('NumeroLote')->item(0)->nodeValue;
            $aRet['Protocolo'] = $doc->getElementsByTagName('Protocolo')->item(0)->nodeValue;
         } else {
            $this->errStatus = true;
            $this->errMsg = 'Nao houve retorno do SOAP!';
            return FALSE;
        }
        return $aRet;
    }// fim sendLot


    /**
     * Consulta NFSe enviada po Lote
     *
     * @package NFePHP
    **/
    private function __consultRps($cnpj,$inscricaoMunicipal,$protocolo){
        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultaLoteRPS';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['URL'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = 'http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd';
        $sNFSe = "<ConsultarLoteRpsEnvio xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\"><Prestador><Cnpj>$cnpj</Cnpj><InscricaoMunicipal>$inscricaoMunicipal</InscricaoMunicipal></Prestador><Protocolo>$protocolo</Protocolo></ConsultarLoteRpsEnvio>";
        // monta string com todas as NFe enviadas no array
        //envia dados via SOAP
        //montagem dos dados da mensagem SOAP
        $dados = trim(str_replace("\n","",$sNFSe));
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);
        if ( isset($retorno) || !$retorno ) {
            //pega os dados do array retornado pelo NuSoap
            $retorno=str_replace('&lt;','<',$retorno);
            $retorno=str_replace('&gt;','>',$retorno);
            $retorno=str_replace('<?xml version="1.0" encoding="utf-8"?>','',$retorno);
            $xmlresp = utf8_encode($retorno);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $this->errStatus = TRUE;
                $this->errMsg = 'Houve uma falha na comunicação SOAP!!';
                return FALSE;
            }
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = FALSE;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $aRet['Numero'] = $doc->getElementsByTagName('Numero')->item(0)->nodeValue;
            $aRet['CodigoVerificacao'] = $doc->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue;
            $aRet['DataEmissao'] = $doc->getElementsByTagName('DataEmissao')->item(0)->nodeValue;
         } else {
            $this->errStatus = TRUE;
            $this->errMsg .= 'Nao houve retorno do SOAP!';
            return FALSE;
        }
        return $aRet;
    } //fim consultRps


    /**
     * Consulta NFS enviada po Lote
     *
     * @name 
     * @package NFePHP
    **/
    private function __consultSitRps($cnpj,$inscricaoMunicipal,$protocolo){
        // carga das variaveis da funçao do webservice envio de Ne em lote
        //identificação do serviço
        $servico = 'ConsultaSituacaoLoteRPS';
        //recuperação da versão
        $versao = $this->mURL[$servico]['version'];
        //recuperação da url do serviço
        $urlservico = $this->mURL[$servico]['URL'];
        //recuperação do método
        $metodo = $this->mURL[$servico]['method'];
        //montagem do namespace do serviço
        $namespace = 'http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd';
        $sNFSe = "<ConsultarSituacaoLoteRpsEnvio xmlns=\"http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd\"><Prestador><Cnpj>$cnpj</Cnpj><InscricaoMunicipal>$inscricaoMunicipal</InscricaoMunicipal></Prestador><Protocolo>$protocolo</Protocolo></ConsultarSituacaoLoteRpsEnvio>";
        //envia dados via SOAP
        //montagem do cabeçalho da comunicação SOAP
        $cabec = '<?xml version="1.0" encoding="UTF-8"?><cabecalho xmlns="'. $namespace . '"><versaoDados>' . $versao . '</versaoDados></cabecalho>';
        //montagem dos dados da mensagem SOAP
        $dados = trim(str_replace("\n","",$sNFSe));
        $retorno = $this->__sendSOAPNFSe($urlservico, $cabec, $dados, $metodo);
        //caso tenha ocorrido erro no envio
        if ( isset($retorno) || !$retorno ) {
            //pega os dados do array retornado pelo NuSoap
            $retorno=str_replace('&lt;','<',$retorno);
            $retorno=str_replace('&gt;','>',$retorno);
            $retorno=str_replace('<?xml version="1.0" encoding="utf-8"?>','',$retorno);
            $xmlresp = utf8_encode($retorno);
            if ($xmlresp == ''){
                //houve uma falha na comunicação SOAP
                $this->errStatus = TRUE;
                $this->errMsg = 'Houve uma falha na comunicação SOAP!!';
                return FALSE;
            }
            //tratar dados de retorno
            $doc = new DOMDocument(); //cria objeto DOM
            $doc->formatOutput = FALSE;
            $doc->preserveWhiteSpace = FALSE;
            $doc->loadXML($retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            // status do recebimento ou mensagem de erro
            $aRet['NumeroLote'] = $doc->getElementsByTagName('NumeroLote')->item(0)->nodeValue;
            $aRet['Situacao'] = $doc->getElementsByTagName('Situacao')->item(0)->nodeValue;
         } else {
            $this->errStatus = TRUE;
            $this->errMsg .= 'Nao houve retorno do SOAP!';
            return FALSE;
        }
        return $aRet;
    } //fim consultSitRps


     protected function __sendCURLNFSe($urlsefaz,$namespace,$cabecalho,$dados,$metodo,$ambiente='',$UF=''){

        if ($urlsefaz == ''){
            //não houve retorno
            $this->errMsg = "URL do webservice não disponível.\n";
            $this->errStatus = true;
        }
        if ($ambiente == ''){
            $ambiente = $this->tpAmb;
        }
        $data = '';
        $data .= '<?xml version="1.0" encoding="utf-8"?>';
        $data .= '<soap12:Envelope ';
        $data .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $data .= 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ';
        $data .= 'xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">';
        $data .= '<soap12:Header>';
        $data .= $cabecalho;
        $data .= '</soap12:Header>';
        $data .= '<soap12:Body>';
        $data .= $dados;
        $data .= '</soap12:Body>';
        $data .= '</soap12:Envelope>';
        //[Informational 1xx]
        $cCode['100']="Continue";
        $cCode['101']="Switching Protocols";
        //[Successful 2xx]
        $cCode['200']="OK";
        $cCode['201']="Created";
        $cCode['202']="Accepted";
        $cCode['203']="Non-Authoritative Information";
        $cCode['204']="No Content";
        $cCode['205']="Reset Content";
        $cCode['206']="Partial Content";
        //[Redirection 3xx]
        $cCode['300']="Multiple Choices";
        $cCode['301']="Moved Permanently";
        $cCode['302']="Found";
        $cCode['303']="See Other";
        $cCode['304']="Not Modified";
        $cCode['305']="Use Proxy";
        $cCode['306']="(Unused)";
        $cCode['307']="Temporary Redirect";
        //[Client Error 4xx]
        $cCode['400']="Bad Request";
        $cCode['401']="Unauthorized";
        $cCode['402']="Payment Required";
        $cCode['403']="Forbidden";
        $cCode['404']="Not Found";
        $cCode['405']="Method Not Allowed";
        $cCode['406']="Not Acceptable";
        $cCode['407']="Proxy Authentication Required";
        $cCode['408']="Request Timeout";
        $cCode['409']="Conflict";
        $cCode['410']="Gone";
        $cCode['411']="Length Required";
        $cCode['412']="Precondition Failed";
        $cCode['413']="Request Entity Too Large";
        $cCode['414']="Request-URI Too Long";
        $cCode['415']="Unsupported Media Type";
        $cCode['416']="Requested Range Not Satisfiable";
        $cCode['417']="Expectation Failed";
        //[Server Error 5xx]
        $cCode['500']="Internal Server Error";
        $cCode['501']="Not Implemented";
        $cCode['502']="Bad Gateway";
        $cCode['503']="Service Unavailable";
        $cCode['504']="Gateway Timeout";
        $cCode['505']="HTTP Version Not Supported";
        $tamanho = strlen($data);
        if($this->enableSCAN){
            //monta a terminação do URL
            switch ($metodo){
                case 'nfeRecepcaoLote2':
                    $servico = "NfeRecepcao";
                    break;
                case 'nfeRetRecepcao2':
                    $servico = "NfeRetRecepcao";
                    break;
                case 'nfeCancelamentoNF2':
                    $servico = "NfeCancelamento";
                    break;
                case 'nfeInutilizacaoNF2':
                    $servico = "NfeInutilizacao";
                    break;
                case 'nfeConsultaNF2':
                    $servico = "NfeConsulta";
                    break;
                case 'nfeStatusServicoNF2':
                    $servico = "NfeStatusServico";
                    break;
                case 'consultaCadastro':
                    $servico = "CadConsultaCadastro";
                    break;
            }
            $aURL = $this->loadSEFAZ( $this->raizDir . 'config' . DIRECTORY_SEPARATOR . "def_ws2.xml",$ambiente,'SCAN');
            $urlsefaz = $aURL[$servico]['URL'];
        } 
        $parametros = Array('Content-Type: application/soap+xml;charset=utf-8;action="'.$namespace."/".$metodo.'"','SOAPAction: "'.$metodo.'"',"Content-length: $tamanho");
        $_aspa = '"';
        $oCurl = curl_init();
        if(is_array($this->aProxy)){
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, "CURLPROXY_HTTP");
            curl_setopt($oCurl, CURLOPT_PROXY, $this->aProxy['IP'].':'.$this->aProxy['PORT']);
            if( $this->aProxy['PASS'] != '' ){
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->aProxy['USER'].':'.$this->aProxy['PASS']);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, "CURLAUTH_BASIC");
            } //fim if senha proxy
        }//fim if aProxy
        curl_setopt($oCurl, CURLOPT_URL, $urlsefaz.'');
        curl_setopt($oCurl, CURLOPT_PORT , 443);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1); //retorna o cabeçalho de resposta
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 3);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_SSLCERT, $this->pubKEY);
        curl_setopt($oCurl, CURLOPT_SSLKEY, $this->priKEY);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER,$parametros);
        $__xml = curl_exec($oCurl);
        $info = curl_getinfo($oCurl); //informações da conexão
        $txtInfo ="";
        $txtInfo .= "URL=$info[url]\n";
        $txtInfo .= "Content type=$info[content_type]\n";
        $txtInfo .= "Http Code=$info[http_code]\n";
        $txtInfo .= "Header Size=$info[header_size]\n";
        $txtInfo .= "Request Size=$info[request_size]\n";
        $txtInfo .= "Filetime=$info[filetime]\n";
        $txtInfo .= "SSL Verify Result=$info[ssl_verify_result]\n";
        $txtInfo .= "Redirect Count=$info[redirect_count]\n";
        $txtInfo .= "Total Time=$info[total_time]\n";
        $txtInfo .= "Namelookup=$info[namelookup_time]\n";
        $txtInfo .= "Connect Time=$info[connect_time]\n";
        $txtInfo .= "Pretransfer Time=$info[pretransfer_time]\n";
        $txtInfo .= "Size Upload=$info[size_upload]\n";
        $txtInfo .= "Size Download=$info[size_download]\n";
        $txtInfo .= "Speed Download=$info[speed_download]\n";
        $txtInfo .= "Speed Upload=$info[speed_upload]\n";
        $txtInfo .= "Download Content Length=$info[download_content_length]\n";
        $txtInfo .= "Upload Content Length=$info[upload_content_length]\n";
        $txtInfo .= "Start Transfer Time=$info[starttransfer_time]\n";
        $txtInfo .= "Redirect Time=$info[redirect_time]\n";
        $txtInfo .= "Certinfo=$info[certinfo]\n";
        $n = strlen($__xml);
        $x = stripos($__xml, "<");
        $xml = substr($__xml, $x, $n-$x);
        $this->soapDebug = $data."\n\n".$txtInfo."\n".$__xml;
        if ($__xml === false){
            //não houve retorno
            $this->errMsg = curl_error($oCurl) . $info['http_code'] . $cCode[$info['http_code']]."\n";
            $this->errStatus = true;
        } else {
            //houve retorno mas ainda pode ser uma mensagem de erro do webservice
            $this->errMsg = $info['http_code'] . $cCode[$info['http_code']]."\n";
            $this->errStatus = false;
        }
        curl_close($oCurl);
        return $xml;
        
    }


    /**
     * __sendSOAPNFSe
     * Estabelece comunicaçao com servidor SOAP 1.1 ou 1.2 da SEFAZ,
     * usando as chaves publica e privada parametrizadas na contrução da classe.
     * Conforme Manual de Integração Versão 4.0.1
     *
     * @param $urlwebservice
     * @param string $cabecalho
     * @param string $dados
     * @param string $metodo
     * @param int $ambiente
     * @version 1.0
     * @package NFePHP
     * @return mixed False se houve falha ou o retorno em xml do SEFAZ
     */
    private function __sendSOAPNFSe($urlwebservice,$cabecalho,$dados,$metodo,$ambiente){
        use_soap_error_handler(TRUE);
        if($ambiente == 1){
            $ambiente = 'producao';
            $URL = $urlwebservice.'?wsdl';
        } else {
            $ambiente = 'homologacao';
            $URL = $urlwebservice.'?wsdl';
        }
        $soapver = SOAP_1_1;
        $options = array(
            'encoding'      => 'UTF-8',
            'verifypeer'    => FALSE,
            'verifyhost'    => FALSE,
            'soap_version'  => $soapver,
            'style'         => SOAP_DOCUMENT,
            'use'           => SOAP_LITERAL,
            'local_cert'    => $this->certKEY,
            'trace'         => TRUE,
            'compression'   => 0,
            'exceptions'    => TRUE,
            'cache_wsdl'    => WSDL_CACHE_NONE
        );
        //instancia a classe soap
        try{
            $oSoapClient = new NFSeSOAPClient($URL,$options);
        } catch (Exception $e) {
            $this->errStatus = TRUE;
            $this->errMsg = $e->__toString();
            return FALSE;
        }
        //faz a chamada ao metodo do webservices
        try{
            // não precisa enviar cabecalho...
            $varBody=$dados;
            $aBody = array("","loteXML"=>$varBody);
            $resp = $oSoapClient->__soapCall($metodo, $aBody);
            $resposta = $oSoapClient->__getLastResponse();
            $this->soapRequest=$oSoapClient->soapRequest;
        } catch (Exception $e){
            $this->soapRequest=$oSoapClient->soapRequest;
            $this->errStatus = TRUE;
            $this->errMsg = $e->__toString();
            return FALSE;
        }
        $this->soapDebug = $oSoapClient->__getLastRequestHeaders();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastRequest();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastResponseHeaders();
        $this->soapDebug .= "\n" . $oSoapClient->__getLastResponse();
        return $resposta;
    } //fim __sendSOAPNFSe

} //fim da classe NFSe

/**
 * Classe complementar
 * necessária para a comunicação SOAP 
 * Remove algumas tags para adequar a comunicação
 * ao padrão Ruindows utilizado
 */
class NFSeSOAPClient extends SoapClient {
    public $soapRequest;
    public function __doRequest($request, $location, $action, $version) {
        $request = str_replace(':ns1', '', $request);
        $request = str_replace('ns1:', '', $request);
        $request = str_replace("\n", '', $request);
        $request = str_replace("\r", '', $request);
        if (strpos($request,"EnviarLoteRpsEnvio") !== FALSE) {
            $request=str_replace("<EnviarLoteRPS/><param1>",'<EnviarLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
            $request=str_replace("</param1>","</loteXML></EnviarLoteRPS>",$request);
        }
        if (strpos($request,"ConsultarLoteRps") !== FALSE) {
            $request=str_replace("<ConsultarLoteRPS/><param1>",'<ConsultarLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
            $request=str_replace("</param1>","</loteXML></ConsultarLoteRPS>",$request);
        }
        if (strpos($request,"ConsultarSituacaoLoteRps") !== FALSE) {
            $request=str_replace("<ConsultarSituacaoLoteRPS/><param1>",'<ConsultarSituacaoLoteRPS xmlns="http://tempuri.org/"><loteXML>',$request);
            $request=str_replace("</param1>","</loteXML></ConsultarSituacaoLoteRPS>",$request);
        }
        $this->soapRequest=$request;
        return (parent::__doRequest($request, $location, $action, $version));
    }
} //fim da classe NFSeSOAPClient
