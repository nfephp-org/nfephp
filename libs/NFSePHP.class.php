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
 *
 * 
 * 
 * TODO: Este arquivo está incompleto e deve ser completamente revisado foi postado apenas para permitir as contribuições de todos os interessados
 * O conteúdo foi apenas copiado do email do seu autor datado de 14/02/2012. 
 * 
 * ##### NÃO PRONTO PARA PRODUÇÃO #####
 */


function geraNfse()
    {
        // Preparando...

        $idRps="rps1";
        $idLote="lote1";
        //cria o objeto DOM para o xml
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

        $Rps=$dom->createElement("Rps");
        //$Rps->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
        //$Rps->setAttribute("xmlns", "http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd");

        $infRps = $dom->createElement("InfRps");
        $infRps->setAttribute("id", $idRps);

        // Identificação
        $IdentificacaoRps = $dom->createElement("IdentificacaoRps");
        $Numero= $dom->createElement("Numero",$this->c['numeroNota']);
        $Serie= $dom->createElement("Serie",$this->c['serie']);
        $Tipo= $dom->createElement("Tipo",$this->c['tipo']);
        $IdentificacaoRps->appendChild($Numero);
        $IdentificacaoRps->appendChild($Serie);
        $IdentificacaoRps->appendChild($Tipo);

        $infRps->appendChild($IdentificacaoRps);
        $infRps->appendChild($dom->createElement("DataEmissao",date("Y-m-d")."T".date("H:i:s")));
        $infRps->appendChild($dom->createElement("NaturezaOperacao",$this->c['naturezaOperacao']));
        $infRps->appendChild($dom->createElement("OptanteSimplesNacional",$this->c['optanteSimplesNacional']));
        $infRps->appendChild($dom->createElement("IncentivadorCultural",$this->c['incentivadorCultural']));
        $infRps->appendChild($dom->createElement("Status",$this->c['status']));


        $qtd=$v_total=$total_itens=$t_icms=$t_ipi=$total_pb=$total_pl=0;
        $temIcms=false;
        foreach ($this->i as $item)
        {
            $qtd++;
            $Servico= $dom->createElement("Servico");

            // Valores

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
            //$Valores->appendChild($ValorPis);
            //$Valores->appendChild($ValorCofins);
            //$Valores->appendChild($ValorIr);
            //$Valores->appendChild($ValorCsll);
            $Valores->appendChild($IssRetido);
            $Valores->appendChild($ValorIss);
            $Valores->appendChild($ValorIssRetido);
            //$Valores->appendChild($OutrasRetencoes);
            $Valores->appendChild($BaseCalculo);
            $Valores->appendChild($Aliquota);
            //$Valores->appendChild($ValorLiquidoNfse);
            //$Valores->appendChild($DescontoIncondicionado);
            //$Valores->appendChild($DescontoCondicionado);


            // Detalhes do serviço

            $ItemListaServico=$dom->createElement("ItemListaServico",trim($item['itemListaServico']));
            //$ItemListaServico=$dom->createElement("ItemListaServico",'11.4');

            $CodigoTributacaoMunicipio=$dom->createElement("CodigoCnae",trim($item['codigoCnae']));
            //$CodigoTributacaoMunicipio=$dom->createElement("CodigoTributacaoMunicipio",$this->c['propriaEnderecoIbgeMunicipio']);
            //$CodigoTributacaoMunicipio=$dom->createElement("CodigoTributacaoMunicipio","Codigo Tributario");
            $Discriminacao=$dom->createElement("Discriminacao",$this->tiraAcentos($item['discriminacao']));
            //$CodigoMunicipio=$dom->createElement("CodigoMunicipio",$this->c['propriaEnderecoIbgeMunicipio']);
            $CodigoMunicipio=$dom->createElement("CodigoMunicipio",'2927408');

            $Servico->appendChild($Valores);
            $Servico->appendChild($ItemListaServico);
            $Servico->appendChild($CodigoTributacaoMunicipio);
            $Servico->appendChild($Discriminacao);
            $Servico->appendChild($CodigoMunicipio);
            $infRps->appendChild($Servico);

        }

        // Prestador

        $Prestador=$dom->createElement("Prestador");
        $Cnpj=$dom->createElement("Cnpj",$this->c['propriaCnpj']);
        $InscricaoMunicipal=$dom->createElement("InscricaoMunicipal",$this->soNumerosIsento($this->tiraPontos($this->c['propriaInscricaoMunicipal'])));

        $Prestador->appendChild($Cnpj);
        $Prestador->appendChild($InscricaoMunicipal);

        // TSomador
        $Tomador=$dom->createElement("Tomador");
        $IdentificacaoTomador=$dom->createElement("IdentificacaoTomador");
        $CpfCnpj=$dom->createElement("CpfCnpj");

        $TomadorCpf=$dom->createElement("Cpf",$this->soNumeros($this->c['cpf']));
        $TomadorCnpj=$dom->createElement("Cnpj",$this->soNumeros($this->c['cnpj']));
        if ($this->c['cpf']<>"")
            $CpfCnpj->appendChild($TomadorCpf);
        else
            $CpfCnpj->appendChild($TomadorCnpj);
        $IdentificacaoTomador->appendChild($CpfCnpj);

        $RazaoSocial=$dom->createElement("RazaoSocial",$this->tiraAcentos($this->c['razaoSocial']));
        $EEndereco=$dom->createElement("Endereco");
        $Endereco=$dom->createElement("Endereco",$this->tiraAcentos(trim($this->c['endereco'])));
        $Numero=$dom->createElement("Numero",$this->tiraAcentos(trim($this->c['enderecoNumero'])));
        $Bairro=$dom->createElement("Bairro",$this->tiraAcentos(trim($this->c['enderecoBairro'])));
        $CodigoMunicipio=$dom->createElement("CodigoMunicipio",$this->soNumeros($this->c['enderecoIbgeMunicipio']));
        $Uf=$dom->createElement("Uf",trim($this->c['enderecoUf']));
        $Cep=$dom->createElement("Cep",$this->soNumeros($this->c['enderecoCep']));

        $EEndereco->appendChild($Endereco);
        $EEndereco->appendChild($Numero);
        $EEndereco->appendChild($Bairro);
        $EEndereco->appendChild($CodigoMunicipio);
        $EEndereco->appendChild($Uf);
        $EEndereco->appendChild($Cep);

        $Tomador->appendChild($IdentificacaoTomador);
        $Tomador->appendChild($RazaoSocial);
        $Tomador->appendChild($EEndereco);
        if ($this->c['email']<>"")
        {
            $Contato=$dom->createElement("Contato");
            $Email=$dom->createElement("Email",$this->tiraAcentos($this->c['email']));
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
        $LoteRps->setAttribute("id", $idLote);

        $NumeroLote=$dom->createElement("NumeroLote",$this->c['numeroLote']);
        $QuantidadeRps=$dom->createElement("QuantidadeRps",1);
        $Cnpj=$dom->createElement("Cnpj",$this->c['propriaCnpj']);
        $InscricaoMunicipal=$dom->createElement("InscricaoMunicipal",$this->soNumerosIsento($this->tiraPontos($this->c['propriaInscricaoMunicipal'])));

        $EnviarLoteRpsEnvio=$dom->createElement("EnviarLoteRpsEnvio");
        //$EnviarLoteRpsEnvio->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $EnviarLoteRpsEnvio->setAttribute("xmlns", "http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd");

        $LoteRps->appendChild($NumeroLote);
        $LoteRps->appendChild($Cnpj);
        $LoteRps->appendChild($InscricaoMunicipal);
        $LoteRps->appendChild($QuantidadeRps);
        $LoteRps->appendChild($ListaRps);

        $EnviarLoteRpsEnvio->appendChild($LoteRps);

        $dom->appendChild($EnviarLoteRpsEnvio);


        //$dom->appendChild($Rps);
        //$dom->appendChild($infRps);

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

        $this->nfexml =$xml;
        $this->assina("InfRps");
        $this->assina("LoteRps");
        $this->nfexml=str_replace('<?xml version="1.0"?>','',$this->nfexml);
//echo "\n\n".$this->nfexml."\n\n";exit;
        /*
        $this->assina("InfRps");
        $this->nfexml=str_replace('<?xml version="1.0"?>','',$this->nfexml);
        $this->nfexml = '<?xml version="1.0" encoding="UTF-8"?><Rps>'.trim($this->nfexml)."</Rps>";
         *
         */
    }

        //Gera arquivo TXT para importação da SEFAZ
        function geraTxtNFSE()
        {
                $hoje=date("Ymd");
                $dataHora=date("YmdHis");
               
                // REGISTRO 1 - Header do arquivo
                $s="1"; // Tipo de Registro
                $s.="103"; // Vesão do Layout
                $s.=padrl($this->c['propriaInscricaoMunicipal'], 26, "r"); // Insc. Municipal do prestador
                $s.="2"; // Indicador de CPF/CNPJ -> 1 = CPF , 2 = CNPJ
                $s.=padrl($this->c['propriaCnpj'], 14,"l"); // CPF / CNPJ do prestador
                $s.=$this->c['propriaOptante'] ; // Optante pelo simples
                $s.=$hoje; // Data de início do período
                $s.=$hoje; // Data de fim do período
                $s.=padrl("1", 5, "l", "0"); // Qtd de NFS-e informadas
                $s.=padrl(" ", 324, "l"); // Preencher com 324 espações em branco
                $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                $s.=NL;
                //var_dump($this->i);
                foreach ($this->i as $item)
        {
                        // REGISTRO 2 - Cabeçalho da NFS-e
                        $s.="2"; // Tipo de registro
                        $s.=padrl($this->nfeid, 20, "l", "0"); // Sequencial da NFS-e
                        $s.=$dataHora; // Data e Hora da NFS-e
                        $s.=$item['tipoRecolhimento']; // Tipo de recolhimento (N - Normal ou R - Retido na fonte)
                        $s.="T"; // Situação da nota fiscal (T - I - F - C - E - J)
                        $s.=padrl(" ", 8, "r"," "); // Data de cancelamento
                        $s.=$this->c['propriaEnderecoIbgeMunicipio']; // Codigo IBGE do municipio de prestação do serviço
                        $s.=padrl(number_format($item['valor'],2,"",""), 15, "l", "0"); // Valor do serviço
                        $s.=padrl(number_format($item['valorDeducoes'],2,"",""), 15, "l", "0"); // valor das deduções
                        $s.=padrl(number_format($item['valorIss'],2,"",""), 15, "l", "0"); // Valor da retenção do PIS
                        $s.=padrl(number_format($item['valorCofins'],2,"",""), 15, "l", "0"); // Valor da retenção do COFINS
                        $s.=padrl(number_format($item['valorInss'],2,"",""), 15, "l", "0"); // Valor da retenção do INSS
                        $s.=padrl(number_format($item['valorIr'],2,"",""), 15, "l", "0"); // Valor da retenção do IR
                        $s.=padrl(number_format($item['valorRetCsll'],2,"",""), 15, "l", "0"); // Valor da retenção do CSLL
                        $s.=padrl(number_format($item['valorIssqn'],2,"",""), 15, "l", "0"); // Valor do ISSQN
                        $s.=padrl(" ", 219, "l"); // Preencher com 219 espações em branco
                        $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                        $s.=NL;

                        // REGISTRO 3 - Identificação do tomador da NFS-e
                        $s.="3"; // Tipo de registro
                        $s.=padrl($this->nfeid, 20, "r", "0"); // Sequencial da NFS-e
                        $s.="2"; // Indicador de CPF/CNPJ do Tomador
                        $s.=padrl($this->soNumeros($this->c['cnpj']), 14,"l"); // CPF/CNPJ do tomador
                        $s.=padrl($this->tiraAcentos($this->c['razaoSocial']), 50, "r"); // Nome do tomador (Nome ou razão social)
                        $s.=padrl($this->tiraAcentos($this->c['empresa']), 50, "r"); // Nome fantasia
                        $s.=padrl(" ", 3, "l"); // Tipo de endereço do tomador
                        $s.=padrl($this->tiraAcentos($this->c['endereco']), 50, "r"); // Endereço do tomador
                        $s.=padrl($this->c['enderecoNumero'], 10, "l","0"); // Número do endereço do tomador
                        $s.=padrl($this->tiraAcentos($this->c['enderecoComplemento']), 20, "r"); // Complemento do endereço do tomador
                        $s.=padrl($this->tiraAcentos($this->c['enderecoBairro']), 30, "r"); // Bairro do tomador
                        $s.=padrl($this->tiraAcentos($this->c['enderecoCidade']), 50, "r"); // Cidade do tomador
                        $s.=padrl($this->tiraAcentos($this->c['enderecoUf']), 2, "r");; // UF do tomador
                        $s.=padrl($this->soNumeros($this->c['enderecoCep']), 8, "r"); // CEP do tomador
                        $s.=padrl($this->tiraAcentos($this->c['email']), 60, "r"); // Email do tomador
                        $s.=padrl(" ", 22, "l"); // Preencher com 22 espações em branco
                        $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                        $s.=NL;

                        // REGISTRO 4 - Descrição da NFS-e
                        $s.="4"; // Tipo de registro
                        $s.=padrl($this->nfeid, 20, "l", "0"); // Sequencial da NFS-e
                        $s.=padrl(" ", 255, "l"); // Descrição da nota
                        $s.=padrl(" ", 115, "l"); // Preencher com 22 espações em branco
                        $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                        $s.=NL;

                        // REGISTRO 5 - Descrição do serviço realizado
                        $s.="5"; // Tipo de registro
                        $s.=padrl($this->nfeid, 20, "l", "0"); // Sequencial da NFS-e
                        $s.=padrl($this->soNumeros($item['codigo']), 4, "l"); // Codigo do serviço pretasdo
                        $s.=padrl($this->soNumeros($item['codigoMunicipio']), 20, "l"); // Código tributação município
                        $s.=padrl(number_format($item['valor'],2,"",""), 15, "l", "0"); // Valor do serviço
                        $s.=padrl(number_format($item['valorDeducoes'],2,"",""), 15, "l", "0"); // Valor dedução
                        $s.=padrl(number_format($item['aliquota'],2,"",""), 4, "l", "0"); // Alíquota
                        $s.=padrl($item['unidade'], 20, "l"); // Unidade
                        $s.=padrl(number_format($item['quantidade'],2,"",""),8,"l","0"); // Quantidade
                        $s.=padrl($this->tiraAcentos($item['discriminacao']), 255, "r"," "); // Descrição do serviço
                        $s.=padrl(" ",20,"l"); // Alvará               
                        $s.=padrl(" ", 9, "l"); // Preencher com 9 espações em branco
                        $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                        $s.=NL;
                }
                // REGISTRO 6 - Indicador de final de arquivo
                $s.="6"; // Tipo de registro
                $s.=padrl(" ", 390, "l"); // Preencher com 390 espações em branco
                $s.=padrl($this->c['numeroLote'], 8, "l","0"); // Sequencial do registro
                $s.=NL;
               
                $sql="update nfse set txt='$s' where id=".$this->nfeid;
                gQuery($sql);
               
                $this->arqtxt=$s;
        }

    function envelopa()
    {
        // Envelopa
        $idLote="1";

        // cria DOM pro nfexml
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        //echo $this->nfexml;exit;
        $dom->loadXML($this->nfexml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

        //cria o objeto DOM para o novo xml
        $dom2 = new DOMDocument('1.0', 'UTF-8');
        $dom2->formatOutput = true;
        $dom2->preserveWhiteSpace = false;


        $node = $dom->getElementsByTagName("Rps")->item(0);
        $Rps=$node->C14N(FALSE,FALSE,NULL,NULL);
       
        $l.='<LoteRps id="'.$idLote.'">';
        $l.='<NumeroLote>'.$this->c['numero'].'</NumeroLote>';
        $l.='<Cnpj>'.$this->c['propriaCnpj'].'</Cnpj>';
        $l.='<InscricaoMunicipal>'.$this->soNumerosIsento($this->tiraPontos($this->c['propriaInscricaoMunicipal'])).'</InscricaoMunicipal>';
        $l.='<QuantidadeRps>1</QuantidadeRps>';
        $l.='<ListaRps>'.$Rps.'</ListaRps>';
        $l.='</LoteRps>';
        $this->nfexml=$l;
        $this->assina("LoteRps");
        $this->nfexml=str_replace('<?xml version="1.0"?>','',$this->nfexml);
        $this->nfexml='<?xml version="1.0" encoding="UTF-8"?><EnviarLoteRpsEnvio xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">'.$this->nfexml.'</EnviarLoteRpsEnvio>';

    }
               
    function limpaNumero($num)
    {
        return(str_replace(".00","",floatval($num)));
    }

    // Assina XML
    function assina($tag='infNFe')
    {
        global $http_lib;
        $sai=false;
        $nfefile=$this->nfexml;
        //$nfefile = file_get_contents($this->arqxml);
        $this->nfeTools= new gNFeTools($this->config());
        if ($tag=='infNFe')
        {
            if ($this->nfeTools->errMsg=="")
            {
                if ( $signn = $this->nfeTools->signXML($nfefile, $tag) )
                {
                    unlink($this->arqxml);
                    if ( !file_put_contents($this->arqxml , $signn) )
                    {
                        $this->erros[]=M."Houve uma falha ao salvar a NFe assinada.";
                    } else
                    {
                        $this->nfexml=$signn;
                        $sai=true;
                    }
                } else
                {
                    $this->erros[]=M."Houve uma falha ao assinar a NFe.";
                }
            } else
            {
                $this->erros[]=M.$this->nfeTools->errMsg." (".$this->nfeTools->cert.")";
            }
        } else
        {
            $this->nfexml=$this->nfeTools->__signXMLNFSe($nfefile, $tag);
        }
        return($sai);
    }


    // Valida XML assinado
    function valida($tipo="nfe")
    {
        global $gPathLib,$gPathDefault;
        /*
        $this->nfeTools= new gNFeTools($this->c['propriaCnpj'].".pfx");
        $this->nfeTools->tpAmb=$this->c['ambiente'];
        $this->nfeTools->empName=$this->c['propriaRazaoSocial'];
        $this->nfeTools->cUF=$this->c['propriaEnderecoIbgeMunicipio'];
        $this->nfeTools->UF=$this->c['propriaEnderecoMunicipio'];
        $this->nfeTools->cnpj=$this->c['propriaCnpj'];
        $this->nfeTools->keyPass=$this->c['senhaCertificado'];
        */

        if ($gPathLib<>"")
        {
            $arq=$gPathLib.NFEPHP."/schemes/";
        } else
        {
            $arq=$gPathDefault.NFEPHP."/schemes/";
        }

        if ($tipo=="nfe")
            $xsd=$arq.$this->schemes.'/nfe_v2.00.xsd';
        else
            $xsd=$arq.'/NFSE_SSA/nfse.xsd';
        $sai=$this->nfeTools->validXML($this->nfexml, $xsd);
                //echo $xsd;exit;
        //$sai=$nfe->validXML($this->nfexml, $xsd);
                //echo var_dump($sai);exit;
        if ($sai['error']<>'')
        {
            $e=explode(";",$sai['error']);
            foreach ($e as $el)
                $this->erros[]=trim($el);
        }
        return($sai);
    }


    // Enviar para Sefaz
    function envia($xml="",$numero="")
    {
        $sai=true;
        if (!is_object($this->nfeTools))
        {
            $this->nfeTools= new gNFeTools($this->config());
        }
        $sql="select * from nfe_numeros where id_armazens=0"; // numero do lote
        $rs=gQuery($sql);
        if ($rs->EOF)
        {
            $sql="insert into nfe_numeros (id_armazens,numero) values (0,0)";
            gQuery($sql);
            $numeroLote=1;
        } else
        {
            $numeroLote=intval($rs->fields['numero'])+1;
        }
        $sql="update nfe_numeros set numero=$numeroLote where id_armazens=0";
        gQuery($sql);
       
        if ($xml=="")
        {
            $xml=$this->nfexml;
           
        }
        if ($ret = $this->nfeTools->sendLot(array($xml),$numeroLote))
        {
            if ($ret['cStat']=='103')
            {
                $this->recibo=$ret['nRec'];
                $this->dataRecibo=$ret['dhRecbto'];
            } else
            {
                $this->erros[]=M.str_pad($ret['cStat'],10,' ')." ".$ret['xMotivo'];
                $sai=false;
            }
        }
        return($sai);
    }

    // Enviar para Sefaz
    function enviaNfse($xml="",$numero="")
    {
        $sai=true;
        if (!is_object($this->nfeTools))
        {
            $this->nfeTools= new gNFeTools($this->config());
        }

        if ($xml=="")
        {
            $xml=$this->nfexml;

        }
        //$xml=file_get_contents('/tmp/envio.xml');
        $xml=str_replace("\n",'',$xml);
                //echo "<textarea rows='20' cols='80'>$xml<textarea><br><br>";
        if ($ret = $this->nfeTools->sendRps($xml,$this->c['numeroLote']))
        {
                   
            if ($ret['Protocolo']<>'')
            {
                // Nova aceita... Salva no banco
                $sql="update nfse_numeros set numero_nota=".$this->c['numeroNota']." where ambiente=".$this->c['ambiente']." and id_empresa=".$this->id_empresa;
                gQuery($sql);
                $sql="update nfse set situacao='Aceita',xml='$xml',data_recibo='".$ret['DataRecebimento']."',protocolo='".$ret['Protocolo']."' where id=".$this->nfeid;
                gQuery($sql);
                $this->protocolo=$ret['Protocolo'];
                $this->numeroNota=$numeroNota;
                $this->numeroLote=$numeroLote;
            } else
            {
                $sql="update nfse set situacao='Rejeitada',xml='$xml',mensagens='Codigo ".$ret['Codigo']."<br>".$ret['Mensagem']."<br>".$ret['Correcao']."' where id=".$this->nfeid;
                gQuery($sql);
                $this->erros[]=str_pad($ret['Codigo'],10,' ')." ".$ret['Mensagem']."<br>".$ret['Correcao'];
                $sai=false;
            }
        } else
                {
                    $sql="update nfse set situacao='Nao enviada',xml='$xml',mensagens='Codigo ".$ret['Codigo']."<br>".$ret['Mensagem']."<br>".$ret['Correcao']."' where id=".$this->nfeid;
                    gQuery($sql);
                    $this->erros[]=str_pad('E000',10,' ')." Nao foi possivel enviar para a SEFAZ, provavelmente por problema no certificado.";
                    $sai=false;
                }
        return($sai);
    }

    function consultaRps($protocolo)
    {
        $sai=true;
        if (!is_object($this->nfeTools))
        {
            $this->nfeTools= new gNFeTools($this->config());
        }
        $sai=$this->nfeTools->consultRps($this->c['propriaCnpj'],$this->soNumerosIsento($this->tiraPontos($this->c['propriaInscricaoMunicipal'])),trim($protocolo));
        return($sai);
    }

    function consultaSituacaoRps($protocolo)
    {
        $sai=true;
        if (!is_object($this->nfeTools))
        {
            $this->nfeTools= new gNFeTools($this->config());
        }
        $sai=$this->nfeTools->consultSitRps($this->c['propriaCnpj'],$this->soNumerosIsento($this->tiraPontos($this->c['propriaInscricaoMunicipal'])),trim($protocolo));
/*
Código de situação de lote de RPS
1 – Não Recebido
2 – Não Processado
3 – Processado com Erro
4 – Processado com Sucesso
 */
        return($sai['Situacao']);
    }







?>
