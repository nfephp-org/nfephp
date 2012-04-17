<?php
/**
 * Este arquivo é parte do projeto NFePHP - Nota Fiscal eletrônica em PHP.
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela
 * Fundação para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte
 * <http://www.fsfla.org/svnwiki/trad/GPLv3>
 * ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 * 
 * Esta classe atende aos critérios estabelecidos no
 * Manual de Importação/Exportação TXT Notas Fiscais eletrônicas versão 2.0.0
 *
 * @package     NFePHP
 * @name        ConvertNFePHP
 * @version     2.3.5
 * @license     http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @license     http://www.gnu.org/licenses/lgpl.html GNU/LGPL v.3
 * @copyright   2009-2011 &copy; NFePHP
 * @link        http://www.nfephp.org/
 * @author      Roberto L. Machado <linux.rlm at gmail dot com>
 * @author      Daniel Batista Lemes <dlemes at gmail dot com>
 *
 *
 *        CONTRIBUIDORES (em ordem alfabetica):
 *              Alberto  Leal <ees.beto at gmail dot com>
 *              Andre Noel <andrenoel at ubuntu dot com>
 *              Clauber Santos <cload_info at yahoo dot com dot br>
 *              Crercio <crercio at terra dot com dot br>
 *              Diogo Mosela <diego dot caicai at gmail dot com>
 *              Eduardo Gusmão <eduardo dot intrasis at gmail dot com>
 *              Elton Nagai <eltaum at gmail dot com>
 *              Fabio Ananias Silva <binhoouropreto at gmail dot com>
 *              Giovani Paseto <giovaniw2 at gmail dot com>
 *              Giuliano Nascimento <giusoft at hotmail dot com>
 *              Helder Ferreira <helder.mauricicio at gmail dot com>
 *              João Eduardo Silva Corrêa <jscorrea2 at gmail dot com>
 *              Leandro C. Lopez <leandro.castoldi at gmail dot com>
 *              Leandro G. Santana <leandrosantana1 at gmail dot com>
 *              Marcos Diez <marcos at unitron dot com dot br>
 *              Renato Ricci <renatoricci at singlesoftware dot com dot br>
 *              Roberto Spadim <rspadim at gmail dot com>
 *              Rodrigo Rysdyk <rodrigo_rysdyk at hotmail dot com>
 *
 */

class ConvertNFePHP {

    /**
     * xml
     * XML da NFe
     * @var string 
     */
    public $xml='';

    /**
     * chave
     * ID da NFe 44 digitos
     * @var string 
     */
    public $chave='';

    /**
     * txt
     * @var string TXT com NFe
     */
    public $txt='';

    /**
     * errMsg
     * Mensagens de erro do API
     * @var string
     */
    public $errMsg='';

    /**
     * errStatus
     * Status de erro
     * @var boolean
     */
    public $errStatus=false;
 
    /**
     * tpAmb
     * Tipo de ambiente
     * @var string
     */
    public $tpAmb = '';


    /**
     * nfetxt2xml
     * Método de conversão das NFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 2.0.0 (24/08/2010)
     * Referente ao modelo de NFe contido na versão 4.0.1-NT2009.006
     * de Dezembro de 2009 do manual de integração da NFe, incluindo a 
     * Nota Técnica 2011/002 de março de 2011
     *
     * @package NFePHP
     * @name nfetxt2xml
     * @version 2.15
     * @param string $arq Path para o arquivo txt
     * @return string xml construido
     */
    public function nfetxt2xml($arq) {
        if ( !is_file($arq) ){
            return FALSE;
        }
        $arrayComAsLinhasDoArquivo = file($arq);
        return $this->nfetxt2xml_array_com_linhas( $arrayComAsLinhasDoArquivo );
    }//fim nfetxt2xml

    /**
     * nfetxt2xml_string
     * Método de conversão das NFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 2.0.0 (24/08/2010)
     * Referente ao modelo de NFe contido na versão 4.0.1-NT2009.006
     * de Dezembro de 2009 do manual de integração da NFe, incluindo a 
     * Nota Técnica 2011/002 de março de 2011
     *
     * @package NFePHP
     * @name nfetxt2xml
     * @version 2.15
     * @author Marcos Diez 
     * @param string $arq uma string contento o conteudo de um arquivo txt de nota fiscal
     * @return string xml construido
     */
    public function nfetxt2xml_string($contentString) {
        $arrayComAsLinhasDoArquivo = explode("\n", $contentString);
        return $this->nfetxt2xml_array_com_linhas( $arrayComAsLinhasDoArquivo );
    }//fim nfetxt2xml_string
    
    /**
     *__calculaDV
     * Função para o calculo o digito verificador da chave da NFe
     * @param string $chave43
     * @return string 
     */
    private function __calculaDV($chave43) {
        $multiplicadores = array(2,3,4,5,6,7,8,9);
        $i = 42;
        $soma_ponderada=0;
        while ($i >= 0) {
            for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                $soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
                $i--;
            }
        }
        $resto = $soma_ponderada % 11;
        if ($resto == '0' || $resto == '1') {
            $cDV = 0;
        } else {
            $cDV = 11 - $resto;
        }
      return $cDV;
    } //fim __calculaDV
    
    /**
     * __calculaChave
     * 
     * @param object $dom 
     */
    private function __montaChaveXML($dom){
        $ide    =  $dom->getElementsByTagName("ide")->item(0);
        $emit   =  $dom->getElementsByTagName("emit")->item(0);
        $cUF    = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
        $dEmi   = $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
        $CNPJ   = $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue;
        $mod    = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
        $serie  = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
        $nNF    = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
        $cNF    = $ide->getElementsByTagName('cNF')->item(0)->nodeValue;
        if( strlen($cNF) != 8 ){
            $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue = rand( 10000001 , 99999999 ); 
        }        
        $tempData =  $dt = explode("-", $dEmi);
        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";//%01d";
        $tempChave = sprintf($forma , 
            $cUF ,
            $tempData[0] - 2000 ,
            $tempData[1] ,
            $CNPJ ,
            $mod ,
            $serie ,
            $nNF ,
            $tpEmis ,
            $cNF);
            
        $cDV    = $ide->getElementsByTagName('cDV')->item(0)->nodeValue  = $this->__calculaDV($tempChave);
        $chave  = $tempChave .= $cDV;
        $infNFe =  $dom->getElementsByTagName("infNFe")->item(0);
        $infNFe->setAttribute("Id", "NFe" .  $chave);
    } //fim __calculaChave
    
    /**
     * nfetxt2xml_arrayComLinhas
     * Método de conversão das NFe de txt para xml, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas versão 2.0.0 (24/08/2010)
     * Referente ao modelo de NFe contido na versão 4.0.1-NT2009.006
     * de Dezembro de 2009 do manual de integração da NFe, incluindo a 
     * Nota Técnica 2011/002 de março de 2011
     *
     * @package NFePHP
     * @name nfetxt2xml
     * @version 2.15
     * @param string $arrayComAsLinhasDoArquivo Array de Strings onde cada elemento é uma linha do arquivo
     * @return string xml construido
     */
    function nfetxt2xml_array_com_linhas($arrayComAsLinhasDoArquivo) {
        $arquivo = $arrayComAsLinhasDoArquivo;
        //cria o objeto DOM para o xml
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $NFe = $dom->createElement("NFe");
        $NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");

        //lê linha por linha do arquivo txt
        for($l = 0; $l < count($arquivo); $l++) {
            //separa os elementos do arquivo txt usando o pipe "|"
            $dados = explode("|",$arquivo[$l]);
            
            //remove todos os espaços adicionais, tabs, linefeed, e CR
            //de todos os campos de dados retirados do TXT
            for ($x=0; $x < count($dados); $x++) {
                if( !empty($dados[$x]) ) {
                    $dados[$x] = preg_replace('/\s\s+/', " ", $dados[$x]);
                    $dados[$x] = $this->__limpaString(trim($dados[$x]));
                } //end if
            } //end for
            
            //monta o dado conforme o tipo, inicia lendo o primeiro campo da matriz
            switch ( $dados[0] ) {
                case "NOTA FISCAL": // primeiro elemento não faz nada, aqui é informado o número de NF contidas no TXT
                    break;

                case "A":  //atributos da NFe, campos obrigatórios [NFe]
                    //A|versão do schema|id
                    $infNFe = $dom->createElement("infNFe");
                    $infNFe->setAttribute("Id", $dados[2]);
                    $infNFe->setAttribute("versao", $dados[1]);
                    //pega a chave de 44 digitos excluindo o a sigla NFe
                    $this->chave = substr($dados[2],3,44);
                    //$pk_nItem = $dom->createElement("pk_nItem");
                    //$infNFe->appendChild($pk_nItem);
                    break;

                case "B": //identificadores [infNFe]
                    //B|cUF|cNF|NatOp|indPag|mod|serie|nNF|dEmi|dSaiEnt|hSaiEnt|tpNF|cMunFG|TpImp|TpEmis|cDV|tpAmb|finNFe|procEmi|VerProc|dhCont|xJust
                    $ide = $dom->createElement("ide");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $ide->appendChild($cUF);
                    $cNF = $dom->createElement("cNF", $dados[2]);
                    $ide->appendChild($cNF);
                    $NatOp = $dom->createElement("natOp", $dados[3]);
                    $ide->appendChild($NatOp);
                    $indPag = $dom->createElement("indPag", $dados[4]);
                    $ide->appendChild($indPag);
                    $mod = $dom->createElement("mod", $dados[5]);
                    $ide->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[6]);
                    $ide->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[7]);
                    $ide->appendChild($nNF);
                    $dEmi = $dom->createElement("dEmi", $dados[8]);
                    $ide->appendChild($dEmi);
                    if(!empty($dados[9])) {
                        $dSaiEnt = $dom->createElement("dSaiEnt", $dados[9]);
                        $ide->appendChild($dSaiEnt);
                    }
                    if(!empty($dados[10])) {
                        $hSaiEnt = $dom->createElement("hSaiEnt", $dados[10]);
                        $ide->appendChild($hSaiEnt);
                    }
                    $tpNF = $dom->createElement("tpNF", $dados[11]);
                    $ide->appendChild($tpNF);
                    $cMunFG = $dom->createElement("cMunFG", $dados[12]);
                    $ide->appendChild($cMunFG);
                    $tpImp = $dom->createElement("tpImp", $dados[13]);
                    $ide->appendChild($tpImp);
                    $tpEmis = $dom->createElement("tpEmis", $dados[14]);
                    $ide->appendChild($tpEmis);
                    $CDV = $dom->createElement("cDV", $dados[15]);
                    $ide->appendChild($CDV);
                    $tpAmb = $dom->createElement("tpAmb", $dados[16]);
                    //guardar a variavel para uso posterior
                    $this->tpAmb = $dados[16];
                    $ide->appendChild($tpAmb);
                    $finNFe = $dom->createElement("finNFe", $dados[17]);
                    $ide->appendChild($finNFe);
                    $procEmi = $dom->createElement("procEmi", $dados[18]);
                    $ide->appendChild($procEmi);
                    if( empty( $dados[19] ) ){
                        $dados[19]="NfePHP";
                    }
                    $verProc = $dom->createElement("verProc", $dados[19]);
                    $ide->appendChild($verProc);
                    if(!empty($dados[20])) {
                        $dhCont = $dom->createElement("dhCont", $dados[20]);
                        $ide->appendChild($dhCont);
                    }
                    if(!empty($dados[21])) {
                        $xJust = $dom->createElement("xJust", $dados[21]);
                        $ide->appendChild($xJust);
                    }
                    $infNFe->appendChild($ide);
                    break;

                case "B13": //NFe referenciadas [ide]
                    if(!isset($NFref)){
                       $NFref = $dom->createElement("NFref");
                       $ide->insertBefore($ide->appendChild($NFref),$tpImp);
                    }
                    $refNFe = $dom->createElement("refNFe", $dados[1]);
                    $NFref->appendChild($refNFe);
                    break;

                case "B14": //NF referenciadas [NFref]
                    //B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|
                    if(!isset($NFref)){
                       $NFref = $dom->createElement("NFref");
                       $ide->insertBefore($ide->appendChild($NFref),$tpImp);
                    }
                    $refNF = $dom->createElement("refNF");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $refNF->appendChild($cUF);
                    $AAMM = $dom->createElement("AAMM", $dados[2]);
                    $refNF->appendChild($AAMM);
                    $CNPJ = $dom->createElement("CNPJ", $dados[3]);
                    $refNF->appendChild($CNPJ);
                    $mod = $dom->createElement("mod", $dados[4]);
                    $refNF->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[5]);
                    $refNF->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[6]);
                    $refNF->appendChild($nNF);
                    $NFref->appendChild($refNF);
                    break;

                case "B20a": //Grupo de informações da NF [NFref]
                    if(!isset($NFref)){
                       $NFref = $dom->createElement("NFref");
                       $ide->insertBefore($ide->appendChild($NFref),$tpImp);
                    }
                    $refNFP = $dom->createElement("refNFP");
                    $cUF = $dom->createElement("cUF", $dados[1]);
                    $refNFP->appendChild($cUF);
                    $AAMM = $dom->createElement("AAMM", $dados[2]);
                    $refNFP->appendChild($AAMM);
                    $IE = $dom->createElement("IE", $dados[3]);
                    $refNFP->appendChild($IE);
                    $mod = $dom->createElement("mod", $dados[4]);
                    $refNFP->appendChild($mod);
                    $serie = $dom->createElement("serie", $dados[5]);
                    $refNFP->appendChild($serie);
                    $nNF = $dom->createElement("nNF", $dados[6]);
                    $refNFP->appendChild($nNF);
                    $NFref->appendChild($refNFP);
                    break;

                case "B20d": //CNPJ [refNFP]
                    //B20d|CNPJ
                    if(!isset($refNFP)){
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $refNFP->appendChild($CNPJ);
                    }
                    break;

                case "B20e": //CPF [refNFP]
                    //B20e|CPF
                    if(!isset($refNFP)){
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $refNFP->appendChild($CPF);
                    }
                    break;

                case "B20i": // CTE [NFref]
                    if(!isset($NFref)){
                       $NFref = $dom->createElement("NFref");
                       $ide->insertBefore($ide->appendChild($NFref),$tpImp);
                    }
                    //B20i|refCTe|
                    $refCTe = $dom->createElement("refCTe", $dados[1]);
                    $NFref->appendChild($refCTe);
                    break;

                case "B20j": // ECF [NFref]
                    if(!isset($NFref)){
                       $NFref = $dom->createElement("NFref");
                       $ide->insertBefore($ide->appendChild($NFref),$tpImp);
                    }
                    //B20j|mod|nECF|nCOO|
                    $refECF = $dom->createElement("refECF");
                    $mod = $dom->createElement("mod", $dados[1]);
                    $refECF->appendChild($mod);
                    $nECF = $dom->createElement("nECF", $dados[2]);
                    $refECF->appendChild($nECF);
                    $nCOO = $dom->createElement("nCOO", $dados[3]);
                    $refECF->appendChild($nCOO);
                    $NFref->appendChild($refECF);
                    break;

                case "C": //dados do emitente [infNFe]
                    //C|XNome|XFant|IE|IEST|IM|CNAE|CRT|
                    $emit = $dom->createElement("emit");
                    $xNome = $dom->createElement("xNome", $dados[1]);
                    $emit->appendChild($xNome);
                    if(!empty($dados[2])) {
                       $xFant = $dom->createElement("xFant", $dados[2]);
                       $emit->appendChild($xFant);
                    }
                    $IE = $dom->createElement("IE", $dados[3]);
                    $emit->appendChild($IE);
                    if(!empty($dados[4])) {
                       $IEST = $dom->createElement("IEST", $dados[4]);
                       $emit->appendChild($IEST);
                    }
                    if(!empty($dados[5])) {
                        $IM = $dom->createElement("IM", $dados[5]);
                        $emit->appendChild($IM);
                    }
                    if(!empty($dados[6])) {
                        $cnae = $dom->createElement("CNAE", $dados[6]);
                        $emit->appendChild($cnae);
                    }
                    if(!empty($dados[7])) {
                        $CRT = $dom->createElement("CRT", $dados[7]);
                        $emit->appendChild($CRT);
                    }
                    $infNFe->appendChild($emit);
                    break;
                
                case "C02": //CNPJ [emit]
                    $cnpj = $dom->createElement("CNPJ", $dados[1]);
                    $emit->insertBefore($emit->appendChild($cnpj),$xNome);
                    break;

                case "C02a": //CPF [emit]
                    $cpf = $dom->createElement("CPF", $dados[1]);
                    $emit->insertBefore($emit->appendChild($cpf),$xNome);
                    break;

                case "C05"://Grupo do Endereço do emitente [emit]
                    //C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone|
                    $enderEmi = $dom->createElement("enderEmit");
                    $xLgr = $dom->createElement("xLgr", $dados[1]);
                    $enderEmi->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[2]);
                    $enderEmi->appendChild($nro);
                    if(!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $enderEmi->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[4]);
                    $enderEmi->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[5]);
                    $enderEmi->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[6]);
                    $enderEmi->appendChild($xMun);
                    $UF = $dom->createElement("UF", $dados[7]);
                    $enderEmi->appendChild($UF);
                    if(!empty($dados[8])) {
                        $CEP = $dom->createElement("CEP", $dados[8]);
                        $enderEmi->appendChild($CEP);
                    }
                    if(!empty($dados[9])) {
                        $cPais = $dom->createElement("cPais", $dados[9]);
                        $enderEmi->appendChild($cPais);
                    }
                    if(!empty($dados[10])) {
                        $xPais = $dom->createElement("xPais", $dados[10]);
                        $enderEmi->appendChild($xPais);
                    }
                    if(!empty($dados[11])) {
                        $fone = $dom->createElement("fone", $dados[11]);
                        $enderEmi->appendChild($fone);
                    }
                    $emit->insertBefore($emit->appendChild($enderEmi),$IE);
                    break;

                case "E": //Grupo de identificação do Destinatário da NF-e [infNFe]
                    //E|xNome|IE|ISUF|email|
                    $dest = $dom->createElement("dest");
                    //se ambiente homologação preencher conforme NT2011.002
                    //válida a partir de 01/05/2011
                    if ($this->tpAmb == '2'){
                        $xNome = $dom->createElement("xNome", 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL');
                        $dest->appendChild($xNome);
                        $IE = $dom->createElement("IE", '');
                        $dest->appendChild($IE);
                    } else {
                        $xNome = $dom->createElement("xNome", $dados[1]);
                        $dest->appendChild($xNome);
                        $IE = $dom->createElement("IE", $dados[2]);
                        $dest->appendChild($IE);
                    }
                    if(!empty($dados[3])) {
                        $ISUF = $dom->createElement("ISUF", $dados[3]);
                        $dest->appendChild($ISUF);
                    }
                    if(!empty($dados[4])) {
                        $email = $dom->createElement("email", $dados[4]);
                        $dest->appendChild($email);
                    }
                    $infNFe->appendChild($dest);
                    break;

                case "E02": //CNPJ [dest]
                    //se ambiente homologação preencher conforme NT2011.002,
                    //válida a partir de 01/05/2011
                    if ($this->tpAmb == '2'){
                        if ($dados[1] != ''){
                            //operação nacional em ambiente homologação usar 99999999000191
                            $CNPJ = $dom->createElement("CNPJ", '99999999000191');
                        } else {
                            //operação com o exterior CNPJ vazio
                            $CNPJ = $dom->createElement("CNPJ", '');
                        }
                    } else {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                    }//fim teste ambiente
                    $dest->insertBefore($dest->appendChild($CNPJ),$xNome);
                    break;

                case "E03": //CPF [dest]
                    //se ambiente homologação preencher conforme NT2011.002,
                    //válida a partir de 01/05/2011
                    if ($this->tpAmb == '2'){
                        if ($dados[1] != ''){
                            //operação nacional em ambiente homologação usar 99999999000191
                            $CNPJ = $dom->createElement("CNPJ", '99999999000191');
                        } else {
                            //operação com o exterior CNPJ vazio
                            $CNPJ = $dom->createElement("CNPJ", '');
                        }
                        $dest->insertBefore($dest->appendChild($CNPJ),$xNome);
                    } else {    
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $dest->insertBefore($dest->appendChild($CPF),$xNome);
                    } //fim teste ambiente
                    break;

                case "E05": //Grupo de endereço do Destinatário da NF-e [dest]
                    //E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
                    $enderDest = $dom->createElement("enderDest");
                    $xLgr = $dom->createElement("xLgr", $dados[1]);
                    $enderDest->appendChild($xLgr);
                    $nro = $dom->createElement("nro", $dados[2]);
                    $enderDest->appendChild($nro);
                    if(!empty($dados[3])){
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $enderDest->appendChild($xCpl);
                    }
                    $xBairro = $dom->createElement("xBairro", $dados[4]);
                    $enderDest->appendChild($xBairro);
                    $cMun = $dom->createElement("cMun", $dados[5]);
                    $enderDest->appendChild($cMun);
                    $xMun = $dom->createElement("xMun", $dados[6]);
                    $enderDest->appendChild($xMun);
                    $UF = $dom->createElement("UF", $dados[7]);
                    $enderDest->appendChild($UF);
                    if(!empty($dados[8])){
                        $CEP = $dom->createElement("CEP", $dados[8]);
                        $enderDest->appendChild($CEP);
                    }
                    if(!empty($dados[9])){
                        $cPais = $dom->createElement("cPais", $dados[9]);
                        $enderDest->appendChild($cPais);
                    }
                    if(!empty($dados[10])){
                        $xPais = $dom->createElement("xPais", $dados[10]);
                        $enderDest->appendChild($xPais);
                    }
                    if(!empty($dados[11])){
                        $fone = $dom->createElement("fone", $dados[11]);
                        $enderDest->appendChild($fone);
                    }
                    $dest->insertBefore($dest->appendChild($enderDest),$IE);
                    break;

                case "F": //Grupo de identificação do Local de retirada [infNFe]
                    //F|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
                    $retirada = $dom->createElement("retirada");
                    if(!empty($dados[1])) {
                        $xLgr = $dom->createElement("xLgr", $dados[1]);
                        $retirada->appendChild($xLgr);
                    }
                    if(!empty($dados[2])) {
                        $nro = $dom->createElement("nro", $dados[2]);
                        $retirada->appendChild($nro);
                    }
                    if(!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $retirada->appendChild($xCpl);
                    }
                    if(!empty($dados[4])) {
                        $xBairro = $dom->createElement("xBairro", $dados[4]);
                        $retirada->appendChild($xBairro);
                    }
                    if(!empty($dados[5])) {
                        $cMun = $dom->createElement("cMun", $dados[5]);
                        $retirada->appendChild($cMun);
                    }
                    if(!empty($dados[6])) {
                        $xMun = $dom->createElement("xMun", $dados[6]);
                        $retirada->appendChild($xMun);
                    }
                    if(!empty($dados[7])) {
                        $UF = $dom->createElement("UF", $dados[7]);
                        $retirada->appendChild($UF);
                    }
                    $infNFe->appendChild($retirada);
                    break;

                case "F02": //CNPJ [retirada]
                    if(!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $retirada->insertBefore($retirada->appendChild($CNPJ),$xLgr);
                    }
                    break;

                case "F02a": //CPF [retirada]
                    if(!empty($dados[1])) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $retirada->insertBefore($retirada->appendChild($CPF),$xLgr);
                    }
                    break;

                case "G": // Grupo de identificação do Local de entrega [entrega]
                    //G|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
                    $entrega = $dom->createElement("entrega");
                    if(!empty($dados[1])) {
                        $xLgr = $dom->createElement("xLgr", $dados[1]);
                        $entrega->appendChild($xLgr);
                    }
                    if(!empty($dados[2])) {
                        $nro = $dom->createElement("nro", $dados[2]);
                        $entrega->appendChild($nro);
                    }
                    if(!empty($dados[3])) {
                        $xCpl = $dom->createElement("xCpl", $dados[3]);
                        $entrega->appendChild($xCpl);
                    }
                    if(!empty($dados[4])) {
                        $xBairro = $dom->createElement("xBairro", $dados[4]);
                        $entrega->appendChild($xBairro);
                    }
                    if(!empty($dados[5])) {
                        $cMun = $dom->createElement("cMun", $dados[5]);
                        $entrega->appendChild($cMun);
                    }
                    if(!empty($dados[6])) {
                        $xMun = $dom->createElement("xMun", $dados[6]);
                        $entrega->appendChild($xMun);
                    }
                    if(!empty($dados[7])) {
                        $UF = $dom->createElement("UF", $dados[7]);
                        $entrega->appendChild($UF);
                    }
                    $infNFe->appendChild($entrega);
                    break;

                case "G02": // CNPJ [entrega]
                    if(!empty($dados[1])) {
                        $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                        $entrega->insertBefore($entrega->appendChild($CNPJ),$xLgr);
                    }
                    break;

                case "G02a": // CPF [entrega]
                    if(!empty($dados[1])) {
                        $CPF = $dom->createElement("CPF", $dados[1]);
                        $entrega->insertBefore($entrega->appendChild($CPF),$xLgr);
                    }
                    break;

                case "H": // Grupo do detalhamento de Produtos e Serviços da NF-e [infNFe]
                    $det = $dom->createElement("det");
                    $det->setAttribute("nItem", $dados[1]);
                    if(!empty($dados[2])) {
                        $infAdProd = $dom->createElement("infAdProd", $dados[2]);
                        $det->appendChild($infAdProd);
                    }
                    $infNFe->appendChild($det);
                    break;

                case "I": //PRODUTO SERVICO [det]
                    //I|CProd|CEAN|XProd|NCM|EXTIPI|CFOP|UCom|QCom|VUnCom|VProd|CEANTrib|UTrib|QTrib|VUnTrib|VFrete|VSeg|VDesc|vOutro|indTot|xPed|nItemPed|
                    $prod = $dom->createElement("prod");
                    $cProd = $dom->createElement("cProd", $dados[1]);
                    $prod->appendChild($cProd);
                    $cEAN = $dom->createElement("cEAN", $dados[2]);
                    $prod->appendChild($cEAN);
                    $xProd = $dom->createElement("xProd", $dados[3]);
                    $prod->appendChild($xProd);
                    $NCM = $dom->createElement("NCM", $dados[4]);
                    $prod->appendChild($NCM);
                    if(!empty($dados[5])) {
                        $EXTIPI = $dom->createElement("EXTIPI", $dados[5]);
                        $prod->appendChild($EXTIPI);
                    }
                    $CFOP = $dom->createElement("CFOP", $dados[6]);
                    $prod->appendChild($CFOP);
                    $uCom = $dom->createElement("uCom", $dados[7]);
                    $prod->appendChild($uCom);
                    $qCom = $dom->createElement("qCom", $dados[8]);
                    $prod->appendChild($qCom);
                    $vUnCom = $dom->createElement("vUnCom", $dados[9]);
                    $prod->appendChild($vUnCom);
                    $vProd = $dom->createElement("vProd", $dados[10]);
                    $prod->appendChild($vProd);
                    $cEANTrib = $dom->createElement("cEANTrib", $dados[11]);
                    $prod->appendChild($cEANTrib);
                    if(!empty($dados[12])) {
                    $uTrib = $dom->createElement("uTrib", $dados[12]);
                    } else {
                        $uTrib = $dom->createElement("uTrib", $dados[7]);
                    } 
                    $prod->appendChild($uTrib);
                    if(!empty($dados[13])) {
                    $qTrib = $dom->createElement("qTrib", $dados[13]);
                    } else {
                        $qTrib = $dom->createElement("qTrib", $dados[8]);
                    }
                    $prod->appendChild($qTrib);
                    if(!empty($dados[14])) {
                    $vUnTrib = $dom->createElement("vUnTrib", $dados[14]);
                    } else {
                        $vUnTrib = $dom->createElement("vUnTrib", $dados[9]);
                    }
                    $prod->appendChild($vUnTrib);
                    if(!empty($dados[15])) {
                        $vFrete = $dom->createElement("vFrete", $dados[15]);
                        $prod->appendChild($vFrete);
                    }
                    if(!empty($dados[16])) {
                        $vSeg = $dom->createElement("vSeg", $dados[16]);
                        $prod->appendChild($vSeg);
                    }
                    if(!empty($dados[17])) {
                        $vDesc = $dom->createElement("vDesc", $dados[17]);
                        $prod->appendChild($vDesc);
                    }
                    if(!empty($dados[18])) {
                        $vOutro = $dom->createElement("vOutro", $dados[18]);
                        $prod->appendChild($vOutro);
                    }
                    if(!empty($dados[19]) || $dados[19] == 0) {
                        $indTot = $dom->createElement("indTot", $dados[19]);
                        $prod->appendChild($indTot);
                    } else {
                        $indTot = $dom->createElement("indTot", '0');
                        $prod->appendChild($indTot);
                    }
                    if( sizeof($dados) > 19 ){
                        if(!empty($dados[20])) {
                            $xPed = $dom->createElement("xPed", $dados[20]);
                            $prod->appendChild($xPed);
                        }
                        if(!empty($dados[21])) {
                            $nItemPed = $dom->createElement("nItemPed", $dados[21]);
                            $prod->appendChild($nItemPed);
                        }
                    }
                    if (!isset($infAdProd)){
                        $det->appendChild($prod);
                    } else {
                        $det->insertBefore($det->appendChild($prod),$infAdProd);
                    }
                    break;

                case "I18": //Tag da Declaração de Importação [prod]
                    //I18|NDI|DDI|XLocDesemb|UFDesemb|DDesemb|CExportador|
                    $DI = $dom->createElement("DI");
                    if(!empty($dados[1])) {
                        $nDI = $dom->createElement("nDI", $dados[1]);
                        $DI->appendChild($nDI);
                    }
                    if(!empty($dados[2])) {
                        $dDI = $dom->createElement("dDI", $dados[2]);
                        $DI->appendChild($dDI);
                    }
                    if(!empty($dados[3])) {
                        $xLocDesemb = $dom->createElement("xLocDesemb", $dados[3]);
                        $DI->appendChild($xLocDesemb);
                    }
                    if(!empty($dados[4])) {
                        $UFDesemb = $dom->createElement("UFDesemb", $dados[4]);
                        $DI->appendChild($UFDesemb);
                    }
                    if(!empty($dados[5])) {
                        $dDesemb = $dom->createElement("dDesemb", $dados[5]);
                        $DI->appendChild($dDesemb);
                    }
                    if(!empty($dados[6])) {
                        $cExportador = $dom->createElement("cExportador", $dados[6]);
                        $DI->appendChild($cExportador);
                    }
                    if (!isset($xPed)){
                    $prod->appendChild($DI);
                    } else {
                        $prod->insertBefore($prod->appendChild($DI),$xPed);
                    }
                    break;

                case "I25": //Adições [DI]
                    //I25|NAdicao|NSeqAdic|CFabricante|VDescDI|
                    $adi = $dom->createElement("adi");
                    if(!empty($dados[1])) {
                        $nAdicao = $dom->createElement("nAdicao", $dados[1]);
                        $adi->appendChild($nAdicao);
                    }
                    if(!empty($dados[2])) {
                        $nSeqAdic = $dom->createElement("nSeqAdic", $dados[2]);
                        $adi->appendChild($nSeqAdic);
                    }
                    if(!empty($dados[3])) {
                        $cFabricante = $dom->createElement("cFabricante", $dados[3]);
                        $adi->appendChild($cFabricante);
                    }
                    if(!empty($dados[4])) {
                        $vDescDI = $dom->createElement("vDescDI", $dados[4]);
                        $adi->appendChild($vDescDI);
                    }
                    $DI->appendChild($adi);
                    break;

                case "J": //Grupo do detalhamento de veículos novos [prod]
                    //J|TpOp|Chassi|CCor|XCor|Pot|cilin|pesoL|pesoB|NSerie|TpComb|NMotor|CMT|Dist|anoMod|anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest|
                    $veicProd = $dom->createElement("veicProd");
                    if(!empty($dados[1])) {
                        $tpOP = $dom->createElement("tpOp", $dados[1]);
                        $veicProd->appendChild($tpOP);
                    }
                    if(!empty($dados[2])) {
                        $chassi = $dom->createElement("chassi", $dados[2]);
                        $veicProd->appendChild($chassi);
                    }
                    if(!empty($dados[3])) {
                        $cCor = $dom->createElement("cCor", $dados[3]);
                        $veicProd->appendChild($cCor);
                    }
                    if(!empty($dados[4])) {
                        $xCor = $dom->createElement("xCor", $dados[4]);
                        $veicProd->appendChild($dVal);
                    }
                    if(!empty($dados[5])) {
                        $pot = $dom->createElement("pot", $dados[5]);
                        $veicProd->appendChild($pot);
                    }
                    if(!empty($dados[6])) {
                        $cilin = $dom->createElement("cilin", $dados[6]);
                        $veicProd->appendChild($cilin);
                    }
                    if(!empty($dados[7])) {
                        $pesoL = $dom->createElement("pesL", $dados[7]);
                        $veicProd->appendChild($pesoL);
                    }
                    if(!empty($dados[8])) {
                        $pesoB = $dom->createElement("pesoB", $dados[8]);
                        $veicProd->appendChild($pesoB);
                    }
                    if(!empty($dados[9])) {
                        $nSerie = $dom->createElement("nSerie", $dados[9]);
                        $veicProd->appendChild($nSerie);
                    }
                    if(!empty($dados[10])) {
                        $tpComb = $dom->createElement("tpComb", $dados[10]);
                        $veicProd->appendChild($tpComb);
                    }
                    if(!empty($dados[11])) {
                       $nMotor = $dom->createElement("nMotor", $dados[11]);
                       $veicProd->appendChild($nMotor);
                    }
                    if(!empty($dados[12])) {
                        $CMT = $dom->createElement("CMT", $dados[12]);
                        $veicProd->appendChild($CMKG);
                    }
                    if(!empty($dados[13])) {
                         $dist = $dom->createElement("dist", $dados[13]);
                         $veicProd->appendChild($dist);
                    }
                    if(!empty($dados[14])) {
                        $anoMod = $dom->createElement("anoMod", $dados[14]);
                        $veicProd->appendChild($anoMod);
                    }
                    if(!empty($dados[15])) {
                        $anoFab = $dom->createElement("anoFab", $dados[15]);
                        $veicProd->appendChild($anoFab);
                    }
                    if(!empty($dados[16])) {
                         $tpPint = $dom->createElement("tpPint", $dados[16]);
                         $veicProd->appendChild($tpPint);
                    }
                    if(!empty($dados[17])) {
                        $tpVeic = $dom->createElement("tpVeic", $dados[17]);
                        $veicProd->appendChild($tpVeic);
                    }
                    if(!empty($dados[18])) {
                        $espVeic = $dom->createElement("espVeic", $dados[18]);
                        $veicProd->appendChild($espVeic);
                    }
                    if(!empty($dados[19])) {
                        $VIN = $dom->createElement("VIN", $dados[19]);
                        $veicProd->appendChild($VIN);
                    }
                    if(!empty($dados[20])) {
                        $condVeic = $dom->createElement("condVeic", $dados[20]);
                        $veicProd->appendChild($condVeic);
                    }
                    if(!empty($dados[21])) {
                        $cMod = $dom->createElement("cMod", $dados[21]);
                        $veicProd->appendChild($cMod);
                    }
                    if(!empty($dados[22])) {
                        $cCorDENATRAN = $dom->createElement("cCorDENATRAN", $dados[22]);
                        $veicProd->appendChild($cCorDENATRAN);
                    }
                    if(!empty($dados[23])) {
                        $lota = $dom->createElement("lota", $dados[23]);
                        $veicProd->appendChild($lota);
                    }
                    if(!empty($dados[24])) {
                        $tpRest = $dom->createElement("tpRest", $dados[24]);
                        $veicProd->appendChild($tpRest);
                    }
                    $prod->appendChild($veicProd);
                    break;

                case "K": //Grupo do detalhamento de Medicamentos e de matériasprimas farmacêuticas [prod]
                    //K|NLote|QLote|DFab|DVal|VPMC|
                    $med = $dom->createElement("med");
                    if(!empty($dados[1])) {
                        $nLote = $dom->createElement("nLote", $dados[1]);
                        $med->appendChild($nLote);
                    }
                    if(!empty($dados[2])) {
                        $qLote = $dom->createElement("qLote", $dados[2]);
                        $med->appendChild($qLote);
                    }
                    if(!empty($dados[3])) {
                        $dFab = $dom->createElement("dFab", $dados[3]);
                        $med->appendChild($dFab);
                    }
                    $dVal = $dom->createElement("dVal", $dados[4]);
                    $med->appendChild($dVal);
                    if(!empty($dados[5])) {
                        $vPMC = $dom->createElement("vPMC", $dados[5]);
                        $med->appendChild($vPMC);
                    }
                    $prod->appendChild($med);
                    break;

                case "L": //Grupo do detalhamento de Armamento [prod]
                    //L|TpArma|NSerie|NCano|Descr|
                    $arma = $dom->createElement("arma");
                    if(!empty($dados[1])) {
                        $tpArma = $dom->createElement("tpArma", $dados[1]);
                        $arma->appendChild($tpArma);
                    }
                    if(!empty($dados[2])) {
                        $nSerie = $dom->createElement("nSerie", $dados[2]);
                        $arma->appendChild($nSerie);
                    }
                    if(!empty($dados[3])) {
                        $nCano = $dom->createElement("nCano", $dados[3]);
                        $arma->appendChild($nCano);
                    }
                    if(!empty($dados[4])) {
                        $descr = $dom->createElement("descr", $dados[4]);
                        $arma->appendChild($descr);
                    }
                    $prod->appendChild($arma);
                    break;

                case "L101": //Grupo de informações específicas para combustíveis líquidos e lubrificantes [prod]
                        $comb = $dom->createElement("comb");
                        $cProdANP = $dom->createElement("cProdANP", $dados[1]);
                        $comb->appendChild($cProdANP);
                        if(!empty($dados[2])) {
                            $CODIF = $dom->createElement("CODIF", $dados[2]);
                            $comb->appendChild($CODIF);
                        }
                        if(!empty($dados[3])) {
                            $qTemp = $dom->createElement("qTemp", $dados[3]);
                            $comb->appendChild($qTemp);
                        }
                        $UFCons = $dom->createElement("UFCons", $dados[4]);
                        $comb->appendChild($UFCons);
                        $prod->appendChild($comb);
                        break;

                    case "L105": //Grupo da CIDE [comb]
                        $CIDE = $dom->createElement("CIDE");
                        $qBCprod = $dom->createElement("qBCprod", $dados[1]);
                        $CIDE->appendChild($qBCprod);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                        $CIDE->appendChild($vAliqProd);
                        $vCIDE = $dom->createElement("vCIDE", $dados[3]);
                        $CIDE->appendChild($vCIDE);
                        $comb->appendChild($CIDE);
                        break;

                    case "M"://GRUPO DE TRIBUTOS INCIDENTES NO PRODUTO SERVICO
                        $imposto = $dom->createElement("imposto");
                        if (!isset($infAdProd)){
                          $det->appendChild($imposto);
                        } else {
                          $det->insertBefore($det->appendChild($imposto),$infAdProd);
                        }
                        $infAdProd = null;
                        break;

                    case "N"://ICMS
                        $ICMS = $dom->createElement("ICMS");
                        $imposto->appendChild($ICMS);
                        break;

                    case "N02"://CST 00 TRIBUTADO INTEGRALMENTE [ICMS]
                        $ICMS00 = $dom->createElement("ICMS00");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS00->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS00->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS00->appendChild($modBC);
                        $vBC = $dom->createElement("vBC", $dados[4]);
                        $ICMS00->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[5]);
                        $ICMS00->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[6]);
                        $ICMS00->appendChild($vICMS);
                        $ICMS->appendChild($ICMS00);
                        break;

                    case "N03"://CST 010 TRIBUTADO E COM COBRANCAO DE ICMS POR SUBSTUICAO TRIBUTARIA [ICMS]
                        $ICMS10 = $dom->createElement("ICMS10");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS10->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS10->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS10->appendChild($modBC);
                        $vBC = $dom->createElement("vBC", $dados[4]);
                        $ICMS10->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[5]);
                        $ICMS10->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[6]);
                        $ICMS10->appendChild($vICMS);
                        $modBCST = $dom->createElement("modBCST", $dados[7]);
                        $ICMS10->appendChild($modBCST);
                        if(!empty($dados[8])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[8]);
                            $ICMS10->appendChild($pMVAST);
                        }
                        if(!empty($dados[9])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[9]);
                            $ICMS10->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[10]);
                        $ICMS10->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[11]);
                        $ICMS10->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[12]);
                        $ICMS10->appendChild($vICMSST);
                        $ICMS->appendChild($ICMS10);
                        break;

                    case "N04": //CST 020 COM REDUCAO DE BASE DE CALCULO [ICMS]
                        $ICMS20 = $dom->createElement("ICMS20");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS20->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS20->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS20->appendChild($modBC);
                        $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                        $ICMS20->appendChild($pRedBC);
                        $vBC = $dom->createElement("vBC", $dados[5]);
                        $ICMS20->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMS20->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMS20->appendChild($vICMS);
                        $ICMS->appendChild($ICMS20);
                        break;

                    case "N05": //CST 030 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST [ICMS]
                        $ICMS30 = $dom->createElement("ICMS30");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS30->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS30->appendChild($CST);
                        $modBCST = $dom->createElement("modBCST", $dados[3]);
                        $ICMS30->appendChild($modBCST);
                        if(!empty($dados[4])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                            $ICMS30->appendChild($pMVAST);
                        }
                        if(!empty($dados[5])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                            $ICMS30->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[6]);
                        $ICMS30->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                        $ICMS30->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                        $ICMS30->appendChild($vICMSST);
                        $ICMS->appendChild($ICMS30);
                        break;

                    case "N06": //Grupo de Tributação do ICMS 40, 41 ou 50 [ICMS]
                        //N06|Orig|CST|vICMS|motDesICMS|
                        $ICMS40 = $dom->createElement("ICMS40");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS40->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS40->appendChild($CST);
                        if(!empty($dados[3])) {
                            $vICMS = $dom->createElement("vICMS", $dados[3]);
                            $ICMS40->appendChild($vICMS);
                        }
                        if(!empty($dados[4])) {
                            $motDesICMS = $dom->createElement("motDesICMS", $dados[4]);
                            $ICMS40->appendChild($motDesICMS);
                        }
                        $ICMS->appendChild($ICMS40);
                        break;

                    case "N07": //Grupo de Tributação do ICMS = 51 [ICMS]
                        //N07|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS|
                        $ICMS51 = $dom->createElement("ICMS51");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS51->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS51->appendChild($CST);
                        if(!empty($dados[3])) {
                            $modBC = $dom->createElement("modBC", $dados[3]);
                            $ICMS51->appendChild($modBC);
                        }
                        if(!empty($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                            $ICMS51->appendChild($pRedBC);
                        }
                        if(!empty($dados[5])) {
                            $vBC = $dom->createElement("vBC", $dados[5]);
                            $ICMS51->appendChild($vBC);
                        }
                        if(!empty($dados[6])) {
                            $pICMS = $dom->createElement("pICMS", $dados[6]);
                            $ICMS51->appendChild($pICMS);
                        }
                        if(!empty($dados[7])) {
                            $vICMS = $dom->createElement("vICMS", $dados[7]);
                            $ICMS51->appendChild($vICMS);
                        }
                        $ICMS->appendChild($ICMS51);
                        break;

                    case "N08": //Grupo de Tributação do ICMS = 60 [ICMS]
                        $ICMS60 = $dom->createElement("ICMS60");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS60->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS60->appendChild($CST);
                        $vBCST = $dom->createElement("vBCSTRet", $dados[3]);
                        $ICMS60->appendChild($vBCST);
                        $vICMSST = $dom->createElement("vICMSSTRet", $dados[4]);
                        $ICMS60->appendChild($vICMSST);
                        $ICMS->appendChild($ICMS60);
                        break;

                    case "N09": //Grupo de Tributação do ICMS 70 [ICMS]
                        $ICMS70 = $dom->createElement("ICMS70");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS70->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS70->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS70->appendChild($modBC);
                        $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                        $ICMS70->appendChild($pRedBC);
                        $vBC = $dom->createElement("vBC", $dados[5]);
                        $ICMS70->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMS70->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMS70->appendChild($vICMS);
                        $modBCST = $dom->createElement("modBCST", $dados[8]);
                        $ICMS70->appendChild($modBCST);
                        if(!empty($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                            $ICMS70->appendChild($pMVAST);
                        }
                        if(!empty($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                            $ICMS70->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[11]);
                        $ICMS70->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                        $ICMS70->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                        $ICMS70->appendChild($vICMSST);
                        $ICMS->appendChild($ICMS70);
                        break;

                    case "N10": //Grupo de Tributação do ICMS 90 Outros [ICMS]
                        $ICMS90 = $dom->createElement("ICMS90");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMS90->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMS90->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMS90->appendChild($modBC);
                        if(!empty($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                            $ICMS90->appendChild($pRedBC);
                        }
                        $vBC = $dom->createElement("vBC", $dados[5]);
                        $ICMS90->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMS90->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMS90->appendChild($vICMS);
                        $modBCST = $dom->createElement("modBCST", $dados[8]);
                        $ICMS90->appendChild($modBCST);
                        if(!empty($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                            $ICMS90->appendChild($pMVAST);
                        }
                        if(!empty($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                            $ICMS90->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[11]);
                        $ICMS90->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                        $ICMS90->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                        $ICMS90->appendChild($vICMSST);
                        $ICMS->appendChild($ICMS90);
                        break;

                    case "N10a": //Partilha do ICMS entre a UF de origem e UF de destino ou a UF definida na legislação [ICMS]
                        //N10a|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST|pBCOp|UFST|
                        $ICMSPart = $dom->createElement("ICMSPart");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSPart->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMSPart->appendChild($CST);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMSPart->appendChild($modBC);
                        if(!empty($dados[4])) {
                            $pRedBC = $dom->createElement("pRedBC", $dados[4]);
                            $ICMSPart->appendChild($pRedBC);
                        }
                        $vBC = $dom->createElement("vBC", $dados[5]);
                        $ICMSPart->appendChild($vBC);
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMSPart->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMSPart->appendChild($vICMS);
                        $modBCST = $dom->createElement("modBCST", $dados[8]);
                        $ICMSPart->appendChild($modBCST);
                        if(!empty($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                            $ICMSPart->appendChild($pMVAST);
                        }
                        if(!empty($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                            $ICMSPart->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[11]);
                        $ICMSPart->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                        $ICMSPart->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                        $ICMSPart->appendChild($vICMSST);
                        $pBCOp = $dom->createElement("pBCOp", $dados[14]);
                        $ICMSPart->appendChild($pBCOp);
                        $UFST = $dom->createElement("UFST", $dados[15]);
                        $ICMSPart->appendChild($UFST);
                        $ICMS->appendChild($ICMSPart);
                        break;

                    case "N10b": //ICMS ST – repasse de ICMS ST retido anteriormente em operações interestaduais com repasses através do Substituto Tributário [ICMS]
                        //N10b|Orig|CST|vBCSTRet|vICMSSTRet|vBCSTDest|vICMSSTDest|
                        $ICMSST = $dom->createElement("ICMSST");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSST->appendChild($orig);
                        $CST = $dom->createElement("CST", $dados[2]);
                        $ICMSST->appendChild($CST);
                        $vBCSTRet = $dom->createElement("vBCSTRet", $dados[3]);
                        $ICMSST->appendChild($vBCSTRet);
                        $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[4]);
                        $ICMSST->ppendChild($vICMSSTRet);
                        $vBCSTDest = $dom->createElement("vBCSTDest", $dados[5]);
                        $ICMSST->appendChild($vBCSTDest);
                        $vICMSSTDest = $dom->createElement("vICMSSTDest", $dados[6]);
                        $ICMSST->appendChild($vICMSSTDest);
                        $ICMS->appendChild($ICMSST);
                        break;

                    case "N10c": //Grupo CRT=1 – Simples Nacional e CSOSN=101 [ICMS]
                        //N10c|Orig|CSOSN|pCredSN|vCredICMSSN|
                        $ICMSSN101 = $dom->createElement("ICMSSN101");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN101->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN101->appendChild($CSOSN);
                        $pCredSN = $dom->createElement("pCredSN", $dados[3]);
                        $ICMSSN101->appendChild($pCredSN);
                        $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[4]);
                        $ICMSSN101->appendChild($vCredICMSSN);
                        $ICMS->appendChild($ICMSSN101);
                        break;

                    case "N10d": //Grupo CRT=1 – Simples Nacional e CSOSN=102, 103,300 ou 400 [ICMS]
                        //N10d|Orig|CSOSN|
                        $ICMSSN102 = $dom->createElement("ICMSSN102");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN102->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN102->appendChild($CSOSN);
                        $ICMS->appendChild($ICMSSN102);
                        break;

                    case "N10e": //Grupo CRT=1 – Simples Nacional e CSOSN=201 [ICMS]
                        //N10e|Orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN|
                        $ICMSSN201 = $dom->createElement("ICMSSN201");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN201->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN201->appendChild($CSOSN);
                        $modBCST = $dom->createElement("modBCST", $dados[3]);
                        $ICMSSN201->appendChild($modBCST);
                        if(!empty($dados[4])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                            $ICMSSN201->appendChild($pMVAST);
                        }
                        if(!empty($dados[5])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                            $ICMSSN201->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[6]);
                        $ICMSSN201->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                        $ICMSSN201->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                        $ICMSSN201->appendChild($vICMSST);
                        $pCredSN = $dom->createElement("pCredSN", $dados[9]);
                        $ICMSSN201->appendChild($pCredSN);
                        $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[10]);
                        $ICMSSN201->appendChild($vCredICMSSN);
                        $ICMS->appendChild($ICMSSN201);
                        break;

                    case "N10f": //Grupo CRT=1 – Simples Nacional e CSOSN=202 ou 203 [ICMS]
                        //N10f|Orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|
                        $ICMSSN202 = $dom->createElement("ICMSSN202");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN202->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN202->appendChild($CSOSN);
                        $modBCST = $dom->createElement("modBCST", $dados[3]);
                        $ICMSSN202->appendChild($modBCST);
                        if(!empty($dados[4])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[4]);
                            $ICMSSN202->appendChild($pMVAST);
                        }
                        if(!empty($dados[5])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[5]);
                            $ICMSSN202->appendChild($pRedBCST);
                        }
                        $vBCST = $dom->createElement("vBCST", $dados[6]);
                        $ICMSSN202->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[7]);
                        $ICMSSN202->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[8]);
                        $ICMSSN202->appendChild($vICMSST);
                        $ICMS->appendChild($ICMSSN202);
                        break;

                    case "N10g": //Grupo CRT=1 – Simples Nacional e CSOSN = 500 [ICMS]
                        //N10g|orig|CSOSN|vBCSTRet|vICMSSTRet|
                        // todos esses campos sao obrigatorios
                        $ICMSSN500 = $dom->createElement("ICMSSN500");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN500->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN500->appendChild($CSOSN);
                        $vBCSTRet = $dom->createElement("vBCSTRet", $dados[3]);
                        $ICMSSN500->appendChild($vBCSTRet);
                        $vICMSSTRet = $dom->createElement("vICMSSTRet", $dados[4]);
                        $ICMSSN500->appendChild($vICMSSTRet);
                        $ICMS->appendChild($ICMSSN500);
                        break;

                    case "N10h": //TAG de Grupo CRT=1 Simples Nacional e CSOSN=900 [ICMS]
                        //N10h|Orig|CSOSN|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN|
                        $ICMSSN900 = $dom->createElement("ICMSSN900");
                        $orig = $dom->createElement("orig", $dados[1]);
                        $ICMSSN900->appendChild($orig);
                        $CSOSN = $dom->createElement("CSOSN", $dados[2]);
                        $ICMSSN900->appendChild($CSOSN);
                        $modBC = $dom->createElement("modBC", $dados[3]);
                        $ICMSSN900->appendChild($modBC);
                        $vBC = $dom->createElement("vBC", $dados[4]);
                        $ICMSSN900->appendChild($vBC);
                        if(!empty($dados[5])) {
                            $pRedBC = $dom->createElement("pRedBC", $dados[5]);
                            $ICMSSN900->appendChild($pRedBC);
                        }    
                        $pICMS = $dom->createElement("pICMS", $dados[6]);
                        $ICMSSN900->appendChild($pICMS);
                        $vICMS = $dom->createElement("vICMS", $dados[7]);
                        $ICMSSN900->appendChild($vICMS);
                        $modBCST = $dom->createElement("modBCST", $dados[8]);
                        $ICMSSN900->appendChild($modBCST);
                        if(!empty($dados[9])) {
                            $pMVAST = $dom->createElement("pMVAST", $dados[9]);
                            $ICMSSN900->appendChild($pMVAST);
                        }    
                        if(!empty($dados[10])) {
                            $pRedBCST = $dom->createElement("pRedBCST", $dados[10]);
                            $ICMSSN900->appendChild($pRedBCST);
                        }    
                        $vBCST = $dom->createElement("vBCST", $dados[11]);
                        $ICMSSN900->appendChild($vBCST);
                        $pICMSST = $dom->createElement("pICMSST", $dados[12]);
                        $ICMSSN900->appendChild($pICMSST);
                        $vICMSST = $dom->createElement("vICMSST", $dados[13]);
                        $ICMSSN900->appendChild($vICMSST);
                        $pCredSN = $dom->createElement("pCredSN", $dados[14]);
                        $ICMSSN900->appendChild($pCredSN);
                        $vCredICMSSN = $dom->createElement("vCredICMSSN", $dados[15]);
                        $ICMSSN900->appendChild($vCredICMSSN);
                        $ICMS->appendChild($ICMSSN900);
                        break;

                    case "O": //Grupo do IPI 0 ou 1 [imposto]
                        $IPI = $dom->createElement("IPI");
                        if(!empty($dados[1])) {
                            $clEnq = $dom->createElement("clEnq", $dados[1]);
                            $IPI->appendChild($clEnq);
                        }
                        if(!empty($dados[2])) {
                            $CNPJProd = $dom->createElement("CNPJProd", $dados[2]);
                            $IPI->appendChild($CNPJProd);
                        }
                        if(!empty($dados[3])) {
                            $cSelo = $dom->createElement("cSelo", $dados[3]);
                            $IPI->appendChild($cSelo);
                        }
                        if(!empty($dados[4])) {
                            $qSelo = $dom->createElement("qSelo", $dados[4]);
                            $IPI->appendChild($qSelo);
                        }
                        if(!empty($dados[5])) {
                            $cEnq = $dom->createElement("cEnq", $dados[5]);
                            $IPI->appendChild($cEnq);
                        }
                        $imposto->appendChild($IPI);
                        break;

                    case "O07": //Grupo do IPITrib CST 00, 49, 50 e 99 0 ou 1 [IPI]
                        // todos esses campos sao obrigatorios
                        $IPITrib = $dom->createElement("IPITrib");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $IPITrib->appendChild($CST);
                        $vIPI = $dom->createElement("vIPI", $dados[2]);
                        $IPITrib->appendChild($vIPI);
                        $IPI->appendChild($IPITrib);
                        break;

                    case "O10": //BC e Percentagem de IPI 0 ou 1 [IPITrib]
                        // todos esses campos sao obrigatorios
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $IPITrib->insertBefore($IPITrib->appendChild($vBC), $vIPI);
                        $pIPI = $dom->createElement("pIPI", $dados[2]);
                        $IPITrib->insertBefore($IPITrib->appendChild($pIPI), $vIPI);
                        break;

                    case "O11": //Quantidade total e Valor 0 ou 1 [IPITrib]
                        // todos esses campos sao obrigatorios
                        $qUnid = $dom->createElement("qUnid", $dados[1]);
                        $IPITrib->insertBefore($IPITrib->appendChild($qUnid), $vIPI);
                        $vUnid = $dom->createElement("vUnid", $dados[2]);
                        $IPITrib->insertBefore($IPITrib->appendChild($vUnid), $vIPI);
                        break;

                    case "O08": //Grupo IPI Não tributavel 0 ou 1 [IPI]
                        // todos esses campos sao obrigatorios
                        $IPINT = $dom->createElement("IPINT");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $IPINT->appendChild($CST);
                        $IPI->appendChild($IPINT);
                        break;

                    case "P": //Grupo do Imposto de Importação 0 ou 1 [imposto]
                        // todos esses campos sao obrigatorios
                        $II = $dom->createElement("II");
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $II->appendChild($vBC);
                        $vDespAdu = $dom->createElement("vDespAdu", $dados[2]);
                        $II->appendChild($vDespAdu);
                        $vII = $dom->createElement("vII", $dados[3]);
                        $II->appendChild($vII);
                        $vIOF = $dom->createElement("vIOF", $dados[4]);
                        $II->appendChild($vIOF);
                        $imposto->appendChild($II);
                        break;

                    case "Q": //Grupo do PIS obrigatorio [imposto]
                        $PIS = $dom->createElement("PIS");
                        $imposto->appendChild($PIS);
                        break;

                    case "Q02": //Grupo de PIS tributado pela alíquota 0 pou 1 [PIS]
                        // todos esses campos sao obrigatorios
                        $PISAliq = $dom->createElement("PISAliq");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $PISAliq->appendChild($CST);
                        $vBC = $dom->createElement("vBC", $dados[2]);
                        $PISAliq->appendChild($vBC);
                        $pPIS = $dom->createElement("pPIS", $dados[3]);
                        $PISAliq->appendChild($pPIS);
                        $vPIS = $dom->createElement("vPIS", $dados[4]);
                        $PISAliq->appendChild($vPIS);
                        $PIS->appendChild($PISAliq);
                        break;

                    case "Q03": //Grupo de PIS tributado por Qtde 0 ou 1 [PIS]
                        // todos esses campos sao obrigatorios
                        $PISQtde = $dom->createElement("PISQtde");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $PISQtde->appendChild($CST);
                        $qBCProd = $dom->createElement("qBCProd", $dados[2]);
                        $PISQtde->appendChild($qBCProd);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[3]);
                        $PISQtde->appendChild($vAliqProd);
                        $vPIS = $dom->createElement("vPIS", $dados[4]);
                        $PISQtde->appendChild($vPIS);
                        $PIS->appendChild($PISQtde);
                        break;

                    case "Q04": //Grupo de PIS não tributado 0 ou 1 [PIS]
                        // todos esses campos sao obrigatorios
                        $PISNT = $dom->createElement("PISNT");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $PISNT->appendChild($CST);
                        $PIS->appendChild($PISNT);
                        break;

                    case "Q05": //Grupo de PIS Outras Operações 0 ou 1 [PIS]
                        //Q05|CST|vPIS|
                        $PISOutr = $dom->createElement("PISOutr");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $PISOutr->appendChild($CST);
                        $vPIS = $dom->createElement("vPIS", $dados[2]);
                        $PISOutr->appendChild($vPIS);
                        $PIS->appendChild($PISOutr);
                        break;

                    case "Q07": //Valor da Base de Cálculo do PIS e Alíquota do PIS (em percentual) 0 pu 1 [PISOutr]
                        // todos esses campos sao obrigatorios
                        //Q07|vBC|pPIS|
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $PISOutr->insertBefore($vBC,$vPIS);
                        $pPIS = $dom->createElement("pPIS", $dados[2]);
                        $PISOutr->insertBefore($pPIS,$vPIS);
                        break;

                    case "Q10": //Quantidade Vendida e Alíquota do PIS (em reais) 0 ou 1 [PISOutr]
                        // todos esses campos sao obrigatorios
                        //Q10|qBCProd|vAliqProd|
                        $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                        $PISOutr->insertBefore($qBCProd,$vPIS);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                        $PISOutr->insertBefore($vAliqProd,$vPIS);
                        break;

                    case "R": //Grupo de PIS Substituição Tributária 0 ou 1 [imposto]
                        // todos esses campos sao obrigatorios
                        $PISST = $dom->createElement("PISST");
                        $vPIS = $dom->createElement("vPIS", $dados[1]);
                        $PISST->appendChild($vPIS);
                        $imposto->appendChild($PISST);
                        break;

                    case "R02": //Valor da Base de Cálculo do PIS e Alíquota do PIS (em percentual) 0 ou 1 [PISST]
                        // todos esses campos sao obrigatorios
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $PISST->appendChild($vBC);
                        $pPIS = $dom->createElement("pPIS", $dados[2]);
                        $PISST->appendChild($pPIS);
                        break;

                    case "R04": //Quantidade Vendida e Alíquota do PIS (em reais) 0 ou 1 [PISST]
                        // todos esses campos sao obrigatorios
                        $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                        $PISST->appendChild($qBCProd);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                        $PISST->appendChild($vAliqProd);
                        break;

                    case "S": //Grupo do COFINS obrigatório [imposto]
                        $COFINS = $dom->createElement("COFINS");
                        $imposto->appendChild($COFINS);
                        break;

                    case "S02": //Grupo de COFINS tributado pela alíquota 0 ou 1 [COFINS]
                        // todos esses campos sao obrigatorios
                        $COFINSAliq = $dom->createElement("COFINSAliq");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $COFINSAliq->appendChild($CST);
                        $vBC = $dom->createElement("vBC", $dados[2]);
                        $COFINSAliq->appendChild($vBC);
                        $pCOFINS = $dom->createElement("pCOFINS", $dados[3]);
                        $COFINSAliq->appendChild($pCOFINS);
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[4]);
                        $COFINSAliq->appendChild($vCOFINS);
                        $COFINS->appendChild($COFINSAliq);
                        break;

                    case "S03": //Grupo de COFINS tributado por Qtde 0 ou 1 [COFINS]
                        // todos esses campos sao obrigatorios
                        $COFINSQtde = $dom->createElement("COFINSQtde");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $COFINSQtde->appendChild($CST);
                        $qBCProd = $dom->createElement("qBCProd", $dados[2]);
                        $COFINSQtde->appendChild($qBCProd);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[3]);
                        $COFINSQtde->appendChild($vAliqProd);
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[4]);
                        $COFINSQtde->appendChild($vCOFINS);
                        $COFINS->appendChild($COFINSQtde);
                        break;

                    case "S04": //Grupo de COFINS não tributado 0 ou 1 [COFINS]
                        // todos esses campos sao obrigatorios
                        $COFINSNT = $dom->createElement("COFINSNT");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $COFINSNT->appendChild($CST);
                        $COFINS->appendChild($COFINSNT);
                        break;

                    case "S05"://Grupo de COFINS Outras Operações 0 ou 1 [COFINS]
                        //S05|CST|vCOFINS|
                        $COFINSOutr = $dom->createElement("COFINSOutr");
                        $CST = $dom->createElement("CST", $dados[1]);
                        $COFINSOutr->appendChild($CST);
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[2]);
                        $COFINSOutr->appendChild($vCOFINS);
                        $COFINS->appendChild($COFINSOutr);
                        break;

                    case "S07": //Valor da Base de Cálculo da COFINS e Alíquota da COFINS (em percentual) 0 ou 1 [COFINSOutr]
                        // todos esses campos sao obrigatorios
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $COFINSOutr->insertBefore($vBC,$vCOFINS);
                        $pCOFINS = $dom->createElement("pCOFINS", $dados[2]);
                        $COFINSOutr->insertBefore($pCOFINS,$vCOFINS);
                        break;

                    case "S09": //Quantidade Vendida e Alíquota da COFINS (em reais) 0 ou 1 [COFINSOutr]
                        // todos esses campos sao obrigatorios
                        $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                        $COFINSOutr->insertBefore($qBCProd,$vCOFINS);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                        $COFINSOutr->insertBefore($vAliqProd,$vCOFINS);
                        break;

                    case "T": //Grupo de COFINS Substituição Tributária 0 ou 1 [imposto]
                        // todos esses campos sao obrigatorios
                        $COFINSST = $dom->createElement("COFINSST");
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[1]);
                        $COFINSST->appendChild($vCOFINS);
                        $imposto->appendChild($COFINSST);
                        break;

                    case "T02": //Valor da Base de Cálculo da COFINS e Alíquota da COFINS (em percentual) 0 ou 1 [COFINSST]
                        // todos esses campos sao obrigatorios
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $COFINSST->insertBefore($vBC, $vCOFINS);
                        $pCOFINS = $dom->createElement("pCOFINS", $dados[2]);
                        $COFINSST->insertBefore($pCOFINS, $vCOFINS);
                        break;

                    case "T04": //Quantidade Vendida e Alíquota da COFINS (em reais) 0 u 1 [COFINSST]
                        // todos esses campos sao obrigatorios
                        $qBCProd = $dom->createElement("qBCProd", $dados[1]);
                        $COFINSST->appendChild($qBCProd);
                        $vAliqProd = $dom->createElement("vAliqProd", $dados[2]);
                        $COFINSST->appendChild($vAliqProd);
                        break;

                    case "U": //Grupo do ISSQN 0 ou 1 [imposto]
                        // todos esses campos sao obrigatorios
                        $ISSQN = $dom->createElement("ISSQN");
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $ISSQN->appendChild($vBC);
                        $vAliq = $dom->createElement("vAliq", $dados[2]);
                        $ISSQN->appendChild($vAliq);
                        $vISSQN = $dom->createElement("vISSQN", $dados[3]);
                        $ISSQN->appendChild($vISSQN);
                        $cMunFG = $dom->createElement("cMunFG", $dados[4]);
                        $ISSQN->appendChild($cMunFG);
                        $cListServ = $dom->createElement("cListServ",$dados[5]);
                        $ISSQN->appendChild($cListServ);
                        $cSitTrib = $dom->createElement("cSitTrib",$dados[6]);
                        $ISSQN->appendChild($cSitTrib);
                        $imposto->appendChild($ISSQN);
                        break;

                    case "W": // Grupo de Valores Totais da NF-e obrigatorio [infNFe]
                        $total = $dom->createElement("total");
                        $infNFe->appendChild($total);
                        break;

                    case "W02": //Grupo de Valores Totais referentes ao ICMS obrigatorio [total]
                        // todos esses campos sao obrigatorios
                        $ICMSTot = $dom->createElement("ICMSTot");
                        $vBC = $dom->createElement("vBC", $dados[1]);
                        $ICMSTot->appendChild($vBC);
                        $vICMS = $dom->createElement("vICMS", $dados[2]);
                        $ICMSTot->appendChild($vICMS);
                        $vBCST = $dom->createElement("vBCST", $dados[3]);
                        $ICMSTot->appendChild($vBCST);
                        $vST = $dom->createElement("vST", $dados[4]);
                        $ICMSTot->appendChild($vST);
                        $vProd = $dom->createElement("vProd", $dados[5]);
                        $ICMSTot->appendChild($vProd);
                        $vFrete = $dom->createElement("vFrete", $dados[6]);
                        $ICMSTot->appendChild($vFrete);
                        $vSeg = $dom->createElement("vSeg", $dados[7]);
                        $ICMSTot->appendChild($vSeg);
                        $vDesc = $dom->createElement("vDesc", $dados[8]);
                        $ICMSTot->appendChild($vDesc);
                        $vII = $dom->createElement("vII", $dados[9]);
                        $ICMSTot->appendChild($vII);
                        $vIPI = $dom->createElement("vIPI", $dados[10]);
                        $ICMSTot->appendChild($vIPI);
                        $vPIS = $dom->createElement("vPIS", $dados[11]);
                        $ICMSTot->appendChild($vPIS);
                        $vCOFINS = $dom->createElement("vCOFINS", $dados[12]);
                        $ICMSTot->appendChild($vCOFINS);
                        $vOutro = $dom->createElement("vOutro", $dados[13]);
                        $ICMSTot->appendChild($vOutro);
                        $vNF = $dom->createElement("vNF", $dados[14]);
                        $ICMSTot->appendChild($vNF);
                        $total->appendChild($ICMSTot);
                        break;

                    case "W17": // Grupo de Valores Totais referentes ao ISSQN 0 ou 1 [total]
                        $ISSQNtot = $dom->createElement("ISSQNtot");
                        if(!empty($dados[1])) {
                            $vServ = $dom->createElement("vServ", $dados[1]);
                            $ISSQNtot->appendChild($vServ);
                        }
                        if(!empty($dados[2])) {
                            $vBC = $dom->createElement("vBC", $dados[2]);
                            $ISSQNtot->appendChild($vBC);
                        }
                        if(!empty($dados[3])) {
                            $vISS = $dom->createElement("vISS", $dados[3]);
                            $ISSQNtot->appendChild($vISS);
                        }
                        if(!empty($dados[4])) {
                            $vPIS = $dom->createElement("vPIS", $dados[4]);
                            $ISSQNtot->appendChild($vPIS);
                        }
                        if(!empty($dados[5])) {
                            $vCOFINS = $dom->createElement("vCOFINS", $dados[5]);
                            $ISSQNtot->appendChild($vCOFINS);
                        }
                        $total->appendChild($ISSQNtot);
                        break;

                    case "W23": //Grupo de Retenções de Tributos 0 ou 1 [total]
                        $retTrib = $dom->createElement("retTrib");
                        if(!empty($dados[1])) {
                            $vRetPIS = $dom->createElement("vRetPIS", $dados[1]);
                            $retTrib->appendChild($vRetPIS);
                        }
                        if(!empty($dados[2])) {
                            $vRetCOFINS = $dom->createElement("vRetCOFINS", $dados[2]);
                            $retTrib->appendChild($vRetCOFINS);
                        }
                        if(!empty($dados[3])) {
                            $vRetCSLL = $dom->createElement("vRetCSLL", $dados[3]);
                            $retTrib->appendChild($vRetCSLL);
                        }
                        if(!empty($dados[4])) {
                            $vBCIRRF = $dom->createElement("vBCIRRF", $dados[4]);
                            $retTrib->appendChild($vBCIRRF);
                        }
                        if(!empty($dados[5])) {
                            $vIRRF = $dom->createElement("vIRRF", $dados[5]);
                            $retTrib->appendChild($vIRRF);
                        }
                        if(!empty($dados[6])) {
                            $vBCRetPrev = $dom->createElement("vBCRetPrev", $dados[6]);
                            $retTrib->appendChild($vBCRetPrev);
                        }
                        if(!empty($dados[7])) {
                            $vRetPrev = $dom->createElement("vRetPrev", $dados[7]);
                            $retTrib->appendChild($vRetPrev);
                        }
                        $total->appendChild($retTrib);
                        break;

                    case "X": // Grupo de Informações do Transporte da NF-e obrigatorio [infNFe]
                        // todos esses campos são obrigatórios
                        $transp = $dom->createElement("transp");
                        $modFrete = $dom->createElement("modFrete", $dados[1]);
                        $transp->appendChild($modFrete);
                        $infNFe->appendChild($transp);
                        break;

                    case "X03": //Grupo Transportador 0 ou 1 [transp]
                        $transporta = $dom->createElement("transporta");
                        if(!empty($dados[1])) {
                            $xNome = $dom->createElement("xNome", $dados[1]);
                            $transporta->appendChild($xNome);
                        }
                        if(!empty($dados[2])) {
                            $IE = $dom->createElement("IE", $dados[2]);
                            $transporta->appendChild($IE);
                        }
                        if(!empty($dados[3])) {
                            $xEnder = $dom->createElement("xEnder", $dados[3]);
                            $transporta->appendChild($xEnder);
                        }
                        if(!empty($dados[5])) {
                            $xMun = $dom->createElement("xMun", $dados[5]);
                            $transporta->appendChild($xMun);
                        }
                        if(!empty($dados[4])) {
                            $UF = $dom->createElement("UF", $dados[4]);
                            $transporta->appendChild($UF);
                        }
                        $transp->appendChild($transporta);
                        break;

                    case "X04": //CNPJ 0 ou 1 [transporta]
                        if(!empty($dados[1])) {
                            $CNPJ = $dom->createElement("CNPJ", $dados[1]);
                            $transporta->insertBefore($transporta->appendChild($CNPJ),$xNome);
                        }
                        break;

                    case "X05": //CPF 0 ou 1 [transporta]
                        if(!empty($dados[1])) {
                            $CPF = $dom->createElement("CPF", $dados[1]);
                            $transporta->insertBefore($transporta->appendChild($CPF),$xNome);
                        }
                        break;

                    case "X11": //Grupo de Retenção do ICMS do transporte 0 ou 1 [transp]
                        // todos esses campos são obrigatórios
                        $retTransp = $dom->createElement("retTransp");
                        $vServ = $dom->createElement("vServ", $dados[1]);
                        $retTransp->appendChild($vServ);
                        $vBCRet = $dom->createElement("vBCRet", $dados[2]);
                        $retTransp->appendChild($vBCRet);
                        $pICMSRet = $dom->createElement("pICMSRet", $dados[3]);
                        $retTransp->appendChild($pICMSRet);
                        $vICMSRet = $dom->createElement("vICMSRet", $dados[4]);
                        $retTransp->appendChild($vICMSRet);
                        $CFOP = $dom->createElement("CFOP", $dados[5]);
                        $retTransp->appendChild($CFOP);
                        $cMunFG = $dom->createElement("cMunFG", $dados[6]);
                        $retTransp->appendChild($cMunFG);
                        $transp->appendChild($retTransp);
                        break;

                    case "X18": //Grupo Veículo 0 ou 1 [transp]
                        if (!empty($dados[1])){
                            $veicTransp = $dom->createElement("veicTransp");
                            $placa = $dom->createElement("placa", $dados[1]);
                            $veicTransp->appendChild($placa);
                            $UF = $dom->createElement("UF", $dados[2]);
                            $veicTransp->appendChild($UF);
                            if(!empty($dados[3])) {
                                $RNTC = $dom->createElement("RNTC", $dados[3]);
                                $veicTransp->appendChild($RNTC);
                            }
                            $transp->appendChild($veicTransp);
                        }
                        break;

                    case "X22": //Grupo Reboque 0 a 5 [transp]
                        $reboque = $dom->createElement("reboque");
                        $placa = $dom->createElement("placa", $dados[1]);
                        $reboque->appendChild($placa);
                        $UF = $dom->createElement("UF", $dados[2]);
                        $reboque->appendChild($UF);
                        if(!empty($dados[3])) {
                            $RNTC = $dom->createElement("RNTC", $dados[3]);
                            $reboque->appendChild($RNTC);
                        }
                        if(!empty($dados[4])) {
                            $vagao = $dom->createElement("vagao", $dados[4]);
                            $reboque->appendChild($vagao);
                        }
                        if(!empty($dados[5])) {
                            $balsa = $dom->createElement("balsa", $dados[5]);
                            $reboque->appendChild($balsa);
                        }
                        $transp->appendChild($reboque);
                        break;

                    case "X26": //Grupo Volumes 0 a N [transp]
                        $vol = $dom->createElement("vol");
                        if(!empty($dados[1])) {
                            $qVol = $dom->createElement("qVol", $dados[1]);
                            $vol->appendChild($qVol);
                        }
                        if(!empty($dados[2])) {
                            $esp = $dom->createElement("esp", $dados[2]);
                            $vol->appendChild($esp);
                        }
                        if(!empty($dados[3])) {
                            $marca = $dom->createElement("marca", $dados[3]);
                            $vol->appendChild($marca);
                        }
                        if(!empty($dados[4])) {
                            $nVol = $dom->createElement("nVol", $dados[4]);
                            $vol->appendChild($nVol);
                        }
                        if(!empty($dados[5])) {
                            $pesoL = $dom->createElement("pesoL", $dados[5]);
                            $vol->appendChild($pesoL);
                        }
                        if(!empty($dados[6])) {
                            $pesoB = $dom->createElement("pesoB", $dados[6]);
                            $vol->appendChild($pesoB);
                        }
                        $transp->appendChild($vol);
                        break;

                    case "X33": //Grupo de Lacres 0 a N [vol]
                        //todos os campos são obrigatorios
                        $lacres = $dom->createElement("lacres");
                        $nLacre = $dom->createElement("nLacre", $dados[1]);
                        $lacres->appendChild($nLacre);
                        $vol->appendChild($lacres);
                        break;

                    case "Y": //Grupo de Cobrança 0 ou 1 [infNFe]
                        $cobr = $dom->createElement("cobr");
                        $infNFe->appendChild($cobr);
                        break;

                    case "Y02": //Grupo da Fatura 0 ou 1 [cobr]
                       if (!isset($cobr)){
                           $cobr = $dom->createElement("cobr");
                           $infNFe->appendChild($cobr);
                        }
                        $fat = $dom->createElement("fat");
                        if(!empty($dados[1])) {
                            $nFat = $dom->createElement("nFat", $dados[1]);
                            $fat->appendChild($nFat);
                        }
                        if(!empty($dados[2])) {
                            $vOrig = $dom->createElement("vOrig", $dados[2]);
                            $fat->appendChild($vOrig);
                        }
                        if(!empty($dados[3])) {
                            $vDesc = $dom->createElement("vDesc", $dados[3]);
                            $fat->appendChild($vDesc);
                        }
                        if(!empty($dados[4])) {
                            $vLiq = $dom->createElement("vLiq", $dados[4]);
                            $fat->appendChild($vLiq);
                        }
                        $cobr->appendChild($fat);
                        break;

                    case "Y07": //Grupo da Duplicata 0 a N [cobr]
                       if (!isset($cobr)){
                           $cobr = $dom->createElement("cobr");
                           $infNFe->appendChild($cobr);
                        }
                        $dup = $dom->createElement("dup");
                        if(!empty($dados[1])) {
                            $nDup = $dom->createElement("nDup", $dados[1]);
                            $dup->appendChild($nDup);
                        }
                        if(!empty($dados[2])) {
                            $dVenc = $dom->createElement("dVenc", $dados[2]);
                            $dup->appendChild($dVenc);
                        }
                        if(!empty($dados[3])) {
                            $vDup = $dom->createElement("vDup", $dados[3]);
                            $dup->appendChild($vDup);
                        }
                        $cobr->appendChild($dup);
                        break;

                    case "Z": //Grupo de Informações Adicionais 0 ou 1 [infNFe]
                        $infAdic = $dom->createElement("infAdic");
                        if(!empty($dados[1])) {
                            $infAdFisco = $dom->createElement("infAdFisco", $dados[1]);
                            $infAdic->appendChild($infAdFisco);
                        }
                        if(!empty($dados[2])) {
                            $infCpl = $dom->createElement("infCpl", $dados[2]);
                            $infAdic->appendChild($infCpl);
                        }
                        $infNFe->appendChild($infAdic);
                        break;

                    case "Z04": //Grupo do campo de uso livre do contribuinte 0-10 [infAdic]
                        //todos os campos são obrigatorios
                        $obsCont = $dom->createElement("obsCont");
                        $obsCont->setAttribute("xCampo", $dados[1]);
                        $xTexto = $dom->createElement("xTexto", $dados[2]);
                        $obsCont->appendChild($xTexto);
                        $infAdic->appendChild($obsCont);
                        break;

                    case "Z07": //Grupo do campo de uso livre do Fisco 0-10 [infAdic]
                        //todos os campos são obrigatorios
                        $obsFisco = $dom->createElement("obsFisco");
                        $obsFisco->setAttribute("xCampo", $dados[1]);
                        $xTexto = $dom->createElement("xTexto", $dados[2]);
                        $obsFisco->appendChild($xTexto);
                        $infAdic->appendChild($obsFisco);
                        break;

                    case "Z10": //Grupo do processo referenciado 0 ou N [infAdic]
                        //todos os campos são obrigatorios
                        $procRef = $dom->createElement("procRef");
                        $nProc = $dom->createElement("nProc", $dados[1]);
                        $procRef->appendChild($nProc);
                        $procRef = $dom->createElement("indProc", $dados[2]);
                        $procRef->appendChild($indProc);
                        $infAdic->appendChild($proRef);
                        break;

                    case "ZA": //Grupo de Exportação 0 ou 1 [infNFe]
                        //todos os campos são obrigatorios
                        $exporta = $dom->createElement("exporta");
                        $UFEmbarq = $dom->createElement("UFEmbarq", $dados[1]);
                        $exporta->appendChild($UFEmbarq);
                        $xLocEmbarq = $dom->createElement("xLocEmbarq", $dados[2]);
                        $exporta->appendChild($xLocEmbarq);
                        $infNFe->appendChild($exporta);
                        break;

                    case "ZB": //Grupo de Compra 0 ou 1 [infNFe]
                        $compra = $dom->createElement("compra");
                        if(!empty($dados[1])) {
                            $xNEmp = $dom->createElement("xNEmp", $dados[1]);
                            $compra->appendChild($xNEmp);
                        }
                        if(!empty($dados[2])) {
                            $xPed = $dom->createElement("xPed", $dados[2]);
                            $compra->appendChild($xPed);
                        }
                        if(!empty($dados[3])) {
                            $xCont = $dom->createElement("xCont", $dados[3]);
                            $compra->appendChild($xCont);
                        }
                        $infNFe->appendChild($compra);
                        break;
                        
                    case "ZC01": //0 ou 1 Grupo de Cana [infNFe]
                        //todos os campos são obrigatorios
                        //ZC01|safra|ref|qTotMes|qTotAnt|qTotGer|vFor|vTotDed|vLiqFor|
                        $cana = $dom->createElement("cana");
                        $safra = $dom->createElement("safra", $dados[1]);
                        $cana->appendChild($safra);
                        $ref = $dom->createElement("ref", $dados[2]);
                        $cana->appendChild($ref);
                        $qTotMes = $dom->createElement("qTotMes", $dados[3]);
                        $cana->appendChild($qTotMes);
                        $qTotAnt = $dom->createElement("qTotAnt", $dados[4]);
                        $cana->appendChild($qTotAnt);
                        $qTotGer = $dom->createElement("qTotGer", $dados[5]);
                        $cana->appendChild($qTotGer);
                        $vFor = $dom->createElement("vFor", $dados[6]);
                        $cana->appendChild($vFor);
                        $vTotDed = $dom->createElement("vTotDed", $dados[7]);
                        $cana->appendChild($vTotDed);
                        $vLiqFor = $dom->createElement("vLiqFor", $dados[8]);
                        $cana->appendChild($vLiqFor);
                        $infNFe->appendChild($cana);
                        break;
                    
                    case "ZC04": //1 a 31 Grupo de Fornecimento diário de cana [cana]
                        //ZC04|dia|qtde|
                        //todos os campos são obrigatorios
                        $forDia = $dom->createElement("forDia");
                        $dia = $dom->createElement("dia", $dados[1]);
                        $forDia->appendChild($dia);
                        $qtde = $dom->createElement("qtde", $dados[2]);
                        $forDia->appendChild($qtde);
                        $cana->appendChild($forDia);
                        break;
                       
                    case "ZC10": //0 a 10 Grupo de Deduções – Taxas e Contribuições [cana]
                        //ZC10|xDed|vDed|
                        //todos os campos são obrigatorios
                        $deduc = $dom->createElement("deduc");
                        $xDed = $dom->createElement("xDed", $dados[1]);
                        $deduc->appendChild($xDed);
                        $vDed = $dom->createElement("vDed", $dados[2]);
                        $deduc->appendChild($vDed);
                        $cana->appendChild($deduc);
                        break;
            } //end switch
        } //end for

        //salva o xml na variável se o txt não estiver em branco
        if(!empty($infNFe)){
            $NFe->appendChild($infNFe);
            $dom->appendChild($NFe);
            $this->__montaChaveXML($dom);
            $xml = $dom->saveXML();
            $this->xml = $dom->saveXML();
            $xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>','<?xml version="1.0" encoding="UTF-8"?>',$xml);
            //remove linefeed, carriage return, tabs e multiplos espaços
            $xml = preg_replace('/\s\s+/',' ', $xml);
            $xml = str_replace("> <","><",$xml);
            return $xml;
        } else {
            return '';
        }

    } //end function


    /**
     * nfexml2txt
     * Método de conversão das NFe de xml para txt, conforme
     * especificações do Manual de Importação/Exportação TXT
     * Notas Fiscais eletrônicas Versão 2.0.0
     * Referente ao modelo de NFe contido na versão 4.01
     * do manual de integração da NFe
     *
     * @package NFePHP
     * @name nfexml2txt
     * @version xxxx
     * @param string $arq Path do arquivo xml
     * @return string
     * @todo ajustar para v2.00
     */
    public function nfexml2txt($arq) {
        //variavel que irá conter o resultado
        $txt = "";
        //verificar se a string passada como parametro é um arquivo
        if ( is_file($arq) ){
            $matriz[] = $arq;
        } else {
            if ( is_array($arq) ){
                $matriz = $arq;
            } else {
                return FALSE;
            }
        }
        
        $nnfematriz = count($matriz);
        $txt = "NOTA FISCAL|$nnfematriz\r\n";
        //para cada nf passada na matriz
        for ($x = 0; $x < $nnfematriz; $x++ ){
            //carregar o conteúdo do arquivo xml em uma string
            $xml = file_get_contents($matriz[$x]);
            //instanciar o ojeto DOM
            $dom = new DOMDocument();
            //carregar o xml no objeto DOM
            $dom->loadXML($xml);
            //carregar os grupos de dados possíveis da NFe
            $nfeProc    = $dom->getElementsByTagName("nfeProc")->item(0);
            $infNFe     = $dom->getElementsByTagName("infNFe")->item(0);
            $ide        = $dom->getElementsByTagName("ide")->item(0);
            $refNFe     = $dom->getElementsByTagName("refNFe");
            $refNF      = $dom->getElementsByTagName("refNF");
            $emit       = $dom->getElementsByTagName("emit")->item(0);
            $avulsa     = $dom->getElementsByTagName("avulsa")->item(0);
            $dest       = $dom->getElementsByTagName("dest")->item(0);
            $retirada   = $dom->getElementsByTagName("retirada")->item(0);
            $entrega    = $dom->getElementsByTagName("entrega")->item(0);
            $enderEmit  = $dom->getElementsByTagName("enderEmit")->item(0);
            $enderDest  = $dom->getElementsByTagName("enderDest")->item(0);
            $det        = $dom->getElementsByTagName("det");
            $cobr       = $dom->getElementsByTagName("cobr")->item(0);
            $ICMSTot    = $dom->getElementsByTagName("ICMSTot")->item(0);
            $ISSQNtot   = $dom->getElementsByTagName("ISSQNtot")->item(0);
            $retTrib    = $dom->getElementsByTagName("retTrib")->item(0);
            $transp     = $dom->getElementsByTagName("transp")->item(0);
            $infAdic    = $dom->getElementsByTagName("infAdic")->item(0);
            $procRef    = $dom->getElementsByTagName("procRef")->item(0);
            $exporta    = $dom->getElementsByTagName("exporta")->item(0);
            $compra     = $dom->getElementsByTagName("compra")->item(0);

            //A|versão do schema|id|
            $id = $infNFe->getAttribute("Id") ? $infNFe->getAttribute("Id") : '';
            $versao = $infNFe->getAttribute("versao");
            $txt .= "A|$versao|$id\r\n";
            $this->chave = substr($id,-44);

            //B|cUF|cNF|NatOp|intPag|mod|serie|nNF|dEmi|dSaiEnt|tpNF|cMunFG|TpImp|TpEmis|CDV|TpAmb|FinNFe|ProcEmi|VerProc|
            $cUF = $ide->getElementsByTagName('cUF')->item(0)->nodeValue;
            $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue;
            $natOp = $ide->getElementsByTagName('natOp')->item(0)->nodeValue;
            $indPag = $ide->getElementsByTagName('indPag')->item(0)->nodeValue;
            $mod = $ide->getElementsByTagName('mod')->item(0)->nodeValue;
            $serie = $ide->getElementsByTagName('serie')->item(0)->nodeValue;
            $nNF = $ide->getElementsByTagName('nNF')->item(0)->nodeValue;
            $dEmi = $ide->getElementsByTagName('dEmi')->item(0)->nodeValue;
            $dSaiEnt = !empty($ide->getElementsByTagName('dSaiEnt')->item(0)->nodeValue) ? $ide->getElementsByTagName('dSaiEnt')->item(0)->nodeValue : '';
            $tpNF = $ide->getElementsByTagName('tpNF')->item(0)->nodeValue;
            $cMunFG = $ide->getElementsByTagName('cMunFG')->item(0)->nodeValue;
            $tpImp = $ide->getElementsByTagName('tpImp')->item(0)->nodeValue;
            $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0)->nodeValue;
            $cDV = $ide->getElementsByTagName('cDV')->item(0)->nodeValue;
            $tpAmb = $ide->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $finNFe = $ide->getElementsByTagName('finNFe')->item(0)->nodeValue;
            $procEmi = $ide->getElementsByTagName('procEmi')->item(0)->nodeValue;
            $verProc = $ide->getElementsByTagName('verProc')->item(0)->nodeValue;
            $txt .= "B|$cUF|$cNF|$natOp|$indPag|$mod|$serie|$nNF|$dEmi|$dSaiEnt|$tpNF|$cMunFG|$tpImp|$tpEmis|$cDV|$tpAmb|$finNFe|$procEmi|$verProc\r\n";

            //B13|refNFe|
            if ( isset($refNFe) ) {
                foreach ( $refNFe as $n => $r){
                    $ref = !empty($refNFe->item($n)->nodeValue) ? $refNFe->item($n)->nodeValue : '';
                    $txt = "B13|$ref\r\n";
                }
            } //fim refNFe

            //B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|
            if ( isset($refNF) ) {
                foreach ( $refNF as $x => $k){
                    $cUF = !empty($refNF->item($x)->getElementsByTagName('cUF')->nodeValue) ? $refNF->item($x)->getElementsByTagName('cUF')->nodeValue : '';
                    $AAMM = !empty($refNF->item($x)->getElementsByTagName('AAMM')->nodeValue) ? $refNF->item($x)->getElementsByTagName('AAMM')->nodeValue : '';
                    $CNPJ = !empty($refNF->item($x)->getElementsByTagName('CNPJ')->nodeValue) ? $refNF->item($x)->getElementsByTagName('CNPJ')->nodeValue : '';
                    $mod = !empty($refNF->item($x)->getElementsByTagName('mod')->nodeValue) ? $refNF->item($x)->getElementsByTagName('mod')->nodeValue : '';
                    $serie = !empty($refNF->item($x)->getElementsByTagName('serie')->nodeValue) ? $refNF->item($x)->getElementsByTagName('serie')->nodeValue : '';
                    $nUF = !empty($refNF->item($x)->getElementsByTagName('nUF')->nodeValue) ? $refNF->item($x)->getElementsByTagName('nUF')->nodeValue : '';
                    $txt .= "B14|$cUF|$AAMM|$CNPJ|$mod|$serie|$nNF\r\n";
                }
            } //fim refNF

            //C|xNome|xFant|IE|IEST|IM|CNAE|
            $xNome = !empty($emit->getElementsByTagName('xNome')->item(0)->nodeValue) ? $emit->getElementsByTagName('xNome')->item(0)->nodeValue : '';
            $xFant = !empty($emit->getElementsByTagName('xFant')->item(0)->nodeValue) ? $emit->getElementsByTagName('xFant')->item(0)->nodeValue : '';
            $IE = !empty($emit->getElementsByTagName('IE')->item(0)->nodeValue) ? $emit->getElementsByTagName('IE')->item(0)->nodeValue : '';
            $IEST = !empty($emit->getElementsByTagName('IEST')->item(0)->nodeValue) ? $emit->getElementsByTagName('IEST')->item(0)->nodeValue : '';
            $IM = !empty($emit->getElementsByTagName('IM')->item(0)->nodeValue) ? $emit->getElementsByTagName('IM')->item(0)->nodeValue : '';
            $CNAE = !empty($emit->getElementsByTagName('CNAE')->item(0)->nodeValue) ? $emit->getElementsByTagName('CNAE')->item(0)->nodeValue : '';
            $CNPJ = !empty($emit->getElementsByTagName('CNPJ')->item(0)->nodeValue) ? $emit->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
            $CPF = !empty($emit->getElementsByTagName('CPF')->item(0)->nodeValue) ? $emit->getElementsByTagName('CPF')->item(0)->nodeValue : '';
            $txt .= "C|$xNome|$xFant|$IE|$IEST|$IM|$CNAE\r\n";

            //C02|CNPJ|
            //[ou]
            //C02a|CPF|
            if ( $CPF != '' ) {
                $txt .= "C02a|$CPF\r\n";
            }else {
                $txt .= "C02|$CNPJ\r\n";
            } //fim CPF ou CNPJ

            //C05|xLgr|nro|xCpl|bairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
            $xLgr = !empty($enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $nro = !empty($enderEmit->getElementsByTagName("nro")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("nro")->item(0)->nodeValue : '';
            $xCpl = !empty($enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $xBairro = !empty($enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $cMun = !empty($enderEmit->getElementsByTagName("cMun")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("cMun")->item(0)->nodeValue : '';
            $xMun = !empty($enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $UF = !empty($enderEmit->getElementsByTagName("UF")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("UF")->item(0)->nodeValue : '';
            $CEP = !empty($enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("CEP")->item(0)->nodeValue : '';
            $cPais = !empty($enderEmit->getElementsByTagName("cPais")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("cPais")->item(0)->nodeValue : '';
            $fone = !empty($enderEmit->getElementsByTagName("fone")->item(0)->nodeValue) ? $enderEmit->getElementsByTagName("fone")->item(0)->nodeValue : '';
            $txt .= "C05|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF|$CEP|$cPais|$xPais|$fone\r\n";

            //D|CNPJ|xOrgao|matr|xAgente|fone|UF|nDAR|dEmi|vDAR|repEmi|dPag|
            if( isset($avulsa) ){
                $CNPJ = !empty($avulsa->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xOrgao = !empty($avulsa->getElementsByTagName("xOrgao")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("xOrgao")->item(0)->nodeValue : '';
                $matr = !empty($avulsa->getElementsByTagName("matr")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("matr")->item(0)->nodeValue : '';
                $xAgente = !empty($avulsa->getElementsByTagName("xAgente")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("xAgente")->item(0)->nodeValue : '';
                $fone = !empty($avulsa->getElementsByTagName("fone")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("fone")->item(0)->nodeValue : '';
                $UF = !empty($avulsa->getElementsByTagName("UF")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $nDAR = !empty($avulsa->getElementsByTagName("nDAR")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("nDAR")->item(0)->nodeValue : '';
                $dEmi = !empty($avulsa->getElementsByTagName("dEmi")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("dEmi")->item(0)->nodeValue : '';
                $vDAR = !empty($avulsa->getElementsByTagName("vDAR")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("vDAR")->item(0)->nodeValue : '';
                $repEmi = !empty($avulsa->getElementsByTagName("repEmi")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("repEmi")->item(0)->nodeValue : '';
                $dPag = !empty($avulsa->getElementsByTagName("dPag")->item(0)->nodeValue) ? $avulsa->getElementsByTagName("dPag")->item(0)->nodeValue : '';
                $txt .= "D|$CNPJ|$xOrgao|$matr|$xAgente|$fone|$UF|$nDAR|$dEmi|$vDAR|$repEmi|$dPag\r\n";
            } //fim avulsa

            //E|XNome|IE|ISUF|
            $xNome = !empty($dest->getElementsByTagName("xNome")->item(0)->nodeValue) ? $dest->getElementsByTagName("xNome")->item(0)->nodeValue : '';
            $IE = !empty($dest->getElementsByTagName("IE")->item(0)->nodeValue) ? $dest->getElementsByTagName("IE")->item(0)->nodeValue : '';
            $ISUF = !empty($dest->getElementsByTagName("ISUF")->item(0)->nodeValue) ? $dest->getElementsByTagName("ISUF")->item(0)->nodeValue : '';
            $txt .= "E|$xNome|$IE|$ISUF\r\n";
            $CNPJ = !empty($dest->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $dest->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
            $CPF = !empty($dest->getElementsByTagName("CPF")->item(0)->nodeValue) ? $dest->getElementsByTagName("CPF")->item(0)->nodeValue : '';

            //E02|CNPJ|
            //[ou]
            //E03|CPF|
            if ($CPF != '' ) {
                $txt .= "E03|$CPF\r\n";
            } else {
                $txt .= "E02|$CNPJ\r\n";
            } //fim o CPF ou CNPJ

            //E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
            $xLgr = !empty($enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
            $nro = !empty($enderDest->getElementsByTagName("nro")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("nro")->item(0)->nodeValue : '';
            $xCpl = !empty($enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
            $xBairro = !empty($enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
            $cMun = !empty($enderDest->getElementsByTagName("cMun")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("cMun")->item(0)->nodeValue : '';
            $xMun = !empty($enderDest->getElementsByTagName("xMun")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xMun")->item(0)->nodeValue : '';
            $UF = !empty($enderDest->getElementsByTagName("UF")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("UF")->item(0)->nodeValue : '';
            $CEP = !empty($enderDest->getElementsByTagName("CEP")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("CEP")->item(0)->nodeValue : '';
            $cPais = !empty($enderDest->getElementsByTagName("cPais")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("cPais")->item(0)->nodeValue : '';
            $xPais = !empty($enderDest->getElementsByTagName("xPais")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("xPais")->item(0)->nodeValue : '';
            $fone = !empty($enderDest->getElementsByTagName("fone")->item(0)->nodeValue) ? $enderDest->getElementsByTagName("fone")->item(0)->nodeValue : '';
            $txt .= "E05|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF|$CEP|$cPais|$xPais|$fone\r\n";

            //F|CNPJ|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
            if( isset($retirada) ) {
                $CNPJ = !empty($retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $retirada->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xLgr = !empty($retirada->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
                $nro = !empty($retirada->getElementsByTagName("nro")->item(0)->nodeValue) ? $retirada->getElementsByTagName("nro")->item(0)->nodeValue : '';
                $xCpl = !empty($retirada->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
                $xBairro = !empty($retirada->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
                $cMun = !empty($retirada->getElementsByTagName("cMun")->item(0)->nodeValue) ? $retirada->getElementsByTagName("cMun")->item(0)->nodeValue : '';
                $xMun = !empty($retirada->getElementsByTagName("xMun")->item(0)->nodeValue) ? $retirada->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($retirada->getElementsByTagName("UF")->item(0)->nodeValue) ? $retirada->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $txt .= "F|$CNPJ|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF\r\n";
            } //fim da retirada

            //G|CNPJ|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
            if( isset($entrega) ) {
                $CNPJ = !empty($entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $entrega->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $xLgr = !empty($entrega->getElementsByTagName("xLgr")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xLgr")->item(0)->nodeValue : '';
                $nro = !empty($entrega->getElementsByTagName("nro")->item(0)->nodeValue) ? $entrega->getElementsByTagName("nro")->item(0)->nodeValue : '';
                $xCpl = !empty($entrega->getElementsByTagName("xCpl")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xCpl")->item(0)->nodeValue : '';
                $xBairro = !empty($entrega->getElementsByTagName("xBairro")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xBairro")->item(0)->nodeValue : '';
                $cMun = !empty($entrega->getElementsByTagName("cMun")->item(0)->nodeValue) ? $entrega->getElementsByTagName("cMun")->item(0)->nodeValue : '';
                $xMun = !empty($entrega->getElementsByTagName("xMun")->item(0)->nodeValue) ? $entrega->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($entrega->getElementsByTagName("UF")->item(0)->nodeValue) ? $entrega->getElementsByTagName("UF")->item(0)->nodeValue : '';
                $txt .= "G|$CNPJ|$xLgr|$nro|$xCpl|$xBairro|$cMun|$xMun|$UF\r\n";
            } //fim entrega

            //instaciar uma variável para contagem
            $i = 0;
            foreach ($det as $d){
                //H|nItem|infAdProd|
                $nItem = $det->item($i)->getAttribute("nItem");

                $infAdProd = !empty($det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue) ? $det->item($i)->getElementsByTagName("infAdProd")->item(0)->nodeValue : '';
                $txt .= "H|$nItem|$infAdProd\r\n";
                //instanciar os grupos de dados internos da tag det
        	$prod = $det->item($i)->getElementsByTagName("prod")->item(0);
		$imposto = $det->item($i)->getElementsByTagName("imposto")->item(0);
		$ICMS = $imposto->getElementsByTagName("ICMS")->item(0);
		$IPI  = $imposto->getElementsByTagName("IPI")->item(0);
                $DI =  $det->item($i)->getElementsByTagName("DI")->item(0);
                $adi =  $det->item($i)->getElementsByTagName("adi")->item(0);
                $veicProd = $det->item($i)->getElementsByTagName("veicProd")->item(0);
                $med = $det->item($i)->getElementsByTagName("med")->item(0);
                $arma = $det->item($i)->getElementsByTagName("arma")->item(0);
                $comb = $det->item($i)->getElementsByTagName("comb")->item(0);
                $II = $det->item($i)->getElementsByTagName("II")->item(0);
                $PIS = $det->item($i)->getElementsByTagName("PIS")->item(0);
                $PISST = $det->item($i)->getElementsByTagName("PISST")->item(0);
                $COFINS = $det->item($i)->getElementsByTagName("COFINS")->item(0);
                $COFINSST = $det->item($i)->getElementsByTagName("COFINSST")->item(0);
                $ISSQN = $det->item($i)->getElementsByTagName("ISSQN")->item(0);
                $i++;

                //I|cProd|cEAN|xProd|NCM|EXTIPI|genero|CFOP|uCom|qCom|vUnCom|vProd|cEANTrib|uTrib|qTrib|vUnTrib|vFrete|vSeg|vDesc|
                $cProd      =  !empty($prod->getElementsByTagName("cProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("cProd")->item(0)->nodeValue : '';
                $cEAN       =  !empty($prod->getElementsByTagName("cEAN")->item(0)->nodeValue) ? $prod->getElementsByTagName("cEAN")->item(0)->nodeValue : '';
                $xProd      =  !empty($prod->getElementsByTagName("xProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("xProd")->item(0)->nodeValue : '';
                $NCM        =  !empty($prod->getElementsByTagName("NCM")->item(0)->nodeValue) ? $prod->getElementsByTagName("NCM")->item(0)->nodeValue : '';
                $EXTIPI     =  !empty($prod->getElementsByTagName("EXTIPI")->item(0)->nodeValue) ? $prod->getElementsByTagName("EXTIPI")->item(0)->nodeValue : '';
                $genero     =  !empty($prod->getElementsByTagName("genero")->item(0)->nodeValue) ? $prod->getElementsByTagName("genero")->item(0)->nodeValue : '';
                $CFOP       =  !empty($prod->getElementsByTagName("CFOP")->item(0)->nodeValue) ? $prod->getElementsByTagName("CFOP")->item(0)->nodeValue : '';
                $uCom       =  !empty($prod->getElementsByTagName("uCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("uCom")->item(0)->nodeValue : '';
                $qCom       =  !empty($prod->getElementsByTagName("qCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("qCom")->item(0)->nodeValue : '';
                $vUnCom     =  !empty($prod->getElementsByTagName("vUnCom")->item(0)->nodeValue) ? $prod->getElementsByTagName("vUnCom")->item(0)->nodeValue : '';
                $vProd      =  !empty($prod->getElementsByTagName("vProd")->item(0)->nodeValue) ? $prod->getElementsByTagName("vProd")->item(0)->nodeValue : '';
                $cEANTrib   =  !empty($prod->getElementsByTagName("cEANTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("cEANTrib")->item(0)->nodeValue : '';
                $uTrib      =  !empty($prod->getElementsByTagName("uTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("uTrib")->item(0)->nodeValue : '';
                $qTrib      =  !empty($prod->getElementsByTagName("qTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("qTrib")->item(0)->nodeValue : '';
                $vUnTrib    =  !empty($prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue) ? $prod->getElementsByTagName("vUnTrib")->item(0)->nodeValue : '';
                $vFrete     =  !empty($prod->getElementsByTagName("vFrete")->item(0)->nodeValue) ? $prod->getElementsByTagName("vFrete")->item(0)->nodeValue : '';
                $vSeg       =  !empty($prod->getElementsByTagName("vSeg")->item(0)->nodeValue) ? $prod->getElementsByTagName("vSeg")->item(0)->nodeValue : '';
                $vDesc      =  !empty($prod->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $prod->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
                $txt .= "I|$cProd|$cEAN|$xProd|$NCM|$EXTIPI|$genero|$CFOP|$uCom|$qCom|$vUnCom|$vProd|$cEANTrib|$uTrib|$qTrib|$vUnTrib|$vFrete|$vSeg|$vDesc\r\n";

                //I18|nDI|dDI|xLocDesemb|UFDesemb|dDesemb|cExportador|
                if ( isset($DI) ){
                    $nDI = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $dDI = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $xLocDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $UFDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $dDesemb = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $cExportador = !empty($DI->getElementsByTagName("nDI")->item(0)->nodeValue) ? $DI->getElementsByTagName("nDI")->item(0)->nodeValue : '';
                    $txt .= "I18|$nDI|$dDI|$xLocDesemb|$UFDesemb|$dDesemb|$cExportador\r\n";
                } //fim importação

                //I25|nAdicao|nSeqAdic|cFabricante|vDescDI|
                if ( isset($adi) ){
                    $nAdicao = !empty($adi->getElementsByTagName("nAdicao")->item(0)->nodeValue) ? $adi->getElementsByTagName("nAdicao")->item(0)->nodeValue : '';
                    $nSeqAdic = !empty($adi->getElementsByTagName("nSeqAdic")->item(0)->nodeValue) ? $adi->getElementsByTagName("nSeqAdic")->item(0)->nodeValue : '';
                    $cFabricante = !empty($adi->getElementsByTagName("cFabricante")->item(0)->nodeValue) ? $adi->getElementsByTagName("cFabricante")->item(0)->nodeValue : '';
                    $vDescDI = !empty($adi->getElementsByTagName("vDescDI")->item(0)->nodeValue) ? $adi->getElementsByTagName("vDescDI")->item(0)->nodeValue : '';
                    $txt .= "I25|$nAdicao|$nSeqAdic|$cFabricante|$vDescDI\r\n";
                } //fim adição

                //J|tpOp|chassi|cCor|xCor|pot|CM3|pesoL|pesoB|nSerie|tpComb|nMotor|CMKG|dist|RENAVAM|anoMod|anoFab|tpPint|tpVeic|espVeic|vIN|condVeic|cMod|
                if ( isset($veicProd) ){
                    $tpOp = !empty($veicProd->getElementsByTagName("tpOp")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpOp")->item(0)->nodeValue : '';
                    $chassi = !empty($veicProd->getElementsByTagName("chassi")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("chassi")->item(0)->nodeValue : '';
                    $cCor = !empty($veicProd->getElementsByTagName("cCor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("cCor")->item(0)->nodeValue : '';
                    $xCor = !empty($veicProd->getElementsByTagName("xCor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("xCor")->item(0)->nodeValue : '';
                    $pot = !empty($veicProd->getElementsByTagName("pot")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pot")->item(0)->nodeValue : '';
                    $CM3 = !empty($veicProd->getElementsByTagName("CM3")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("CM3")->item(0)->nodeValue : '';
                    $pesoL = !empty($veicProd->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pesoL")->item(0)->nodeValue : '';
                    $pesoB = !empty($veicProd->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("pesoB")->item(0)->nodeValue : '';
                    $nSerie = !empty($veicProd->getElementsByTagName("nSerie")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("nSerie")->item(0)->nodeValue : '';
                    $tpComb = !empty($veicProd->getElementsByTagName("tpComb")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpComb")->item(0)->nodeValue : '';
                    $nMotor = !empty($veicProd->getElementsByTagName("nMotor")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("nMotor")->item(0)->nodeValue : '';
                    $CMKG = !empty($veicProd->getElementsByTagName("CMKG")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("CMKG")->item(0)->nodeValue : '';
                    $dist = !empty($veicProd->getElementsByTagName("dist")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("dist")->item(0)->nodeValue : '';
                    $RENAVAM = !empty($veicProd->getElementsByTagName("RENAVAM")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("RENAVAM")->item(0)->nodeValue : '';
                    $anoMod = !empty($veicProd->getElementsByTagName("anoMod")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("anoMod")->item(0)->nodeValue : '';
                    $anoFab = !empty($veicProd->getElementsByTagName("anoFab")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("anoFab")->item(0)->nodeValue : '';
                    $tpPint = !empty($veicProd->getElementsByTagName("tpPint")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpPint")->item(0)->nodeValue : '';
                    $tpVeic = !empty($veicProd->getElementsByTagName("tpVeic")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("tpVeic")->item(0)->nodeValue : '';
                    $vIN = !empty($veicProd->getElementsByTagName("vIN")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("vIN")->item(0)->nodeValue : '';
                    $condVeic = !empty($veicProd->getElementsByTagName("condVeic")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("condVeic")->item(0)->nodeValue : '';
                    $cMod = !empty($veicProd->getElementsByTagName("cMod")->item(0)->nodeValue) ? $veicProd->getElementsByTagName("cMod")->item(0)->nodeValue : '';
                    $txt .= "J|$tpOp|$chassi|$cCor|$xCor|$pot|$CM3|$pesoL|$pesoB|$nSerie|$tpComb|$nMotor|$CMKG|$dist|$RENAVAM|$anoMod|$anoFab|$tpPint|$tpVeic|$espVeic|$vIN|$condVeic|$cMod\r\n";
                } // fim veiculos novos

                //K|nLote|qLote|dFab|dVal|vPMC|
                if ( isset($med) ){
                    $nLote = !empty($med->getElementsByTagName("nLote")->item(0)->nodeValue) ? $med->getElementsByTagName("nLote")->item(0)->nodeValue : '';
                    $qLote = !empty($med->getElementsByTagName("qLote")->item(0)->nodeValue) ? $med->getElementsByTagName("qLote")->item(0)->nodeValue : '';
                    $dFab = !empty($med->getElementsByTagName("dFab")->item(0)->nodeValue) ? $med->getElementsByTagName("dFab")->item(0)->nodeValue : '';
                    $dVal = !empty($med->getElementsByTagName("dVal")->item(0)->nodeValue) ? $med->getElementsByTagName("dVal")->item(0)->nodeValue : '';
                    $vPMC = !empty($med->getElementsByTagName("vPMC")->item(0)->nodeValue) ? $med->getElementsByTagName("vPMC")->item(0)->nodeValue : '';
                    $txt .= "K|$nLote|$qLote|$dFab|$dVal|$vPMC\r\n";
                } // fim medicamentos

                //L|tpArma|nSerie|nCano|descr|
                if ( isset($arma) ){
                    $tpArma = !empty($arma->getElementsByTagName("tpArma")->item(0)->nodeValue) ? $arma->getElementsByTagName("tpArma")->item(0)->nodeValue : '';
                    $nSerie = !empty($arma->getElementsByTagName("nSerie")->item(0)->nodeValue) ? $arma->getElementsByTagName("nSerie")->item(0)->nodeValue : '';
                    $nCano = !empty($arma->getElementsByTagName("nCano")->item(0)->nodeValue) ? $arma->getElementsByTagName("nCano")->item(0)->nodeValue : '';
                    $descr = !empty($arma->getElementsByTagName("descr")->item(0)->nodeValue) ? $arma->getElementsByTagName("descr")->item(0)->nodeValue : '';
                    $txt .= "L|$tpArma|$nSerie|$nCano|$descr\r\n";
                } // fim armas

                //combustiveis
                if ( isset($comb) ){
                    //instanciar sub grups da tag comb
                    $CIDE = $comb->getElementsByTagName("CIDE")->item(0);
                    $ICMSComb = $comb->getElementsByTagName("ICMSComb")->item(0);
                    $ICMSInter = $comb->getElementsByTagName("ICMSInter")->item(0);
                    $ICMSCons = $comb->getElementsByTagName("ICMSCons")->item(0);

                    $cProdANP = !empty($comb->getElementsByTagName("cProdANP")->item(0)->nodeValue) ? $comb->getElementsByTagName("cProdANP")->item(0)->nodeValue : '';
                    $CODIF = !empty($comb->getElementsByTagName("CODIF")->item(0)->nodeValue) ? $comb->getElementsByTagName("CODIF")->item(0)->nodeValue : '';
                    $qTemp = !empty($comb->getElementsByTagName("qTemp")->item(0)->nodeValue) ? $comb->getElementsByTagName("qTemp")->item(0)->nodeValue : '';
                    //L01|cProdANP|CODIF|qTemp|
                    $txt .= "L01|$cProdANP|$CODIF|$qTemp\r\n";
                    //grupo CIDE
                    if ( isset($CIDE) ){
                        //L105|qBCProd|vAliqProd|vCIDE|
                        $qBCprod = !empty($CIDE->getElementsByTagName("qBCprod")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("qBCprod")->item(0)->nodeValue : '';
                        $vAliqProd = !empty($CIDE->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                        $vCIDE = !empty($CIDE->getElementsByTagName("vCIDE")->item(0)->nodeValue) ? $CIDE->getElementsByTagName("vCIDE")->item(0)->nodeValue : '';
                        $txt .= "L105|$qBCProd|$vAliqProd|$vCIDE\r\n";
                    } // fim grupo CIDE
                    //grupo ICMSComb
                    if ( isset($ICMSComb) ){
                        //L109|VBCICMS|VICMS|VBCICMSST|VICMSST|
                        $vBCICMS = !empty($ICMSComb->getElementsByTagName("vBCICMS")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMS")->item(0)->nodeValue : '';
                        $vICMS = !empty($ICMSComb->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                        $vBCICMSST = !empty($ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                        $vICMSST = !empty($ICMSComb->getElementsByTagName("vICMSST")->item(0)->nodeValue) ? $ICMSComb->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                        $txt .= "L109|$vBCICMS|$vICMS|$vBCICMSST|$vICMSST\r\n";
                    } // fim ICMSComb

                    //grupo ICMSInter
                    if ( isset($ICMSInter) ){
                         //L114|VBCICMSST|VICMSST|
                         $vBCICMSSTDest = !empty($ICMSInter->getElementsByTagName("vBCICMSSTDest")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vBCICMSST")->item(0)->nodeValue : '';
                         $vICMSSTDest = !empty($ICMSInter->getElementsByTagName("vICMSSTDest")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vICMSST")->item(0)->nodeValue : '';
                         $txt .= "L114|$vBCICMSSTDest|$vICMSSTDest|";
                    } //fim ICMSInter

                    //grupo ICMSCons
                    if ( isset($ICMSCons) ){
                        //L117|VBCICMSSTCons|VICMSSTCons|UFCons|
                        $vBCICMSSTCons = !empty($ICMSInter->getElementsByTagName("vBCICMSSTCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vBCICMSSTCons")->item(0)->nodeValue : '';
                        $vICMSSTCons = !empty($ICMSInter->getElementsByTagName("vICMSSTCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("vICMSSTCons")->item(0)->nodeValue : '';
                        $UFCons = !empty($ICMSInter->getElementsByTagName("UFCons")->item(0)->nodeValue) ? $ICMSInter->getElementsByTagName("UFCons")->item(0)->nodeValue : '';
                    } //fim ICMSCons


                } //fim combustiveis

                //M|
                $txt .= "M\r\n";
                //N|
                $txt .= "N\r\n";
                //N02|orig|CST|modBC|vBC|pICMS|vICMS|
                $orig = !empty($ICMS->getElementsByTagName("orig")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("orig")->item(0)->nodeValue : '';
                $CST = (string) !empty($ICMS->getElementsByTagName("CST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                $modBC = !empty($ICMS->getElementsByTagName("modBC")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("modBC")->item(0)->nodeValue : '';
                $vBC = !empty($ICMS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                $pICMS = !empty($ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pICMS")->item(0)->nodeValue : '';
                $vICMS = !empty($ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
                $modBCST = !empty($ICMS->getElementsByTagName("modBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("modBCST")->item(0)->nodeValue : '';
                $pMVAST = !empty($ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pMVAST")->item(0)->nodeValue : '';
                $pRedBCST = !empty($ICMS->getElementsByTagName("pRedBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pRedBCST")->item(0)->nodeValue : '';
                $vBCST = !empty($ICMS->getElementsByTagName("vBCST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vBCST")->item(0)->nodeValue : '';
                $pICMSST = !empty($ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("pICMSST")->item(0)->nodeValue : '';
                $vICMSST = !empty($ICMS->getElementsByTagName("vICMSSTS")->item(0)->nodeValue) ? $ICMS->getElementsByTagName("vICMSST")->item(0)->nodeValue : '';
                switch ($CST) {
                    case '00': //CST 00 TRIBUTADO INTEGRALMENTE
                        $txt .= "N02|$orig|$CST|$modBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '10': //CST 10 TRIBUTADO E COM COBRANCA DE ICMS POR SUBSTUICAO TRIBUTARIA
                        $txt .= "N03|$orig|$CST|$modBC|$vBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '20': //CST 20 COM REDUCAO DE BASE DE CALCULO
                        $txt .= "N04|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '30': //CST 30 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST
                        $txt .= "N05|$orig|$CST|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '40': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '41': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '50': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                        $txt .= "N06|$orig|$CST\r\n";
                        break;
                    case '51': //CST 51 DIFERIMENTO - A EXIGENCIA DO PREECNCHIMENTO DAS INFORMAS DO ICMS DIFERIDO FICA A CRITERIO DE CADA UF
                        $txt .= "N07|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS\r\n";
                        break;
                    case '60': //CST 60 ICMS COBRADO ANTERIORMENTE POR ST
                        $txt .= "N08|$orig|$CST|$vBCST|$vICMSST\r\n";
                        break;
                    case '70': //CST 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                        $txt .= "N09|$orig|$CST|$modBC|$pRedBC|$vBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                    case '90': //CST - 90 Outros
                        $txt .= "N10|$orig|$CST|$modBC|$vBC|$pRedBC|$pICMS|$vICMS|$modBCST|$pMVAST|$pRedBCST|$vBCST|$pICMSST|$vICMSST\r\n";
                        break;
                } // fim switch

                if ( isset($IPI) ){
                    //O|clEnq|CNPJProd|cSelo|qSelo|cEnq|
                    $clEnq = !empty($IPI->getElementsByTagName("clEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("clEnq")->item(0)->nodeValue : '';
                    $CNPJProd = !empty($IPI->getElementsByTagName("CNPJProd")->item(0)->nodeValue) ? $IPI->getElementsByTagName("CNPJProd")->item(0)->nodeValue : '';
                    $cSelo = !empty($IPI->getElementsByTagName("clEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("cSelo")->item(0)->nodeValue : '';
                    $qSelo = !empty($IPI->getElementsByTagName("qSelo")->item(0)->nodeValue) ? $IPI->getElementsByTagName("qSelo")->item(0)->nodeValue : '';
                    $cEnq = !empty($IPI->getElementsByTagName("cEnq")->item(0)->nodeValue) ? $IPI->getElementsByTagName("cEnq")->item(0)->nodeValue : '';
                    $txt .= "O|$clEnq|$CNPJProd|$cSelo|$qSelo|$cEnq\r\n";
                    //grupo de tributação de IPI NAO TRIBUTADO
                    $IPINT = $IPI->getElementsByTagName("IPINT")->item(0);
                    if ( isset($IPINT) ){
                        $CST = (string) !empty($IPINT->getElementsByTagName("CST")->item(0)->nodeValue) ? $IPINT->getElementsByTagName("CST")->item(0)->nodeValue : '';
                        $txtIPI = "O08|$CST\r\n";
                    }
                    //grupo de tributação de IPI
                    $IPITrib = $IPI->getElementsByTagName("IPITrib")->item(0);
                    if ( isset($IPITrib) ){
                        $CST = (string) !empty($IPITrib->getElementsByTagName("CST")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("CST")->item(0)->nodeValue : '';
                        $vIPI = !empty($IPITrib->getElementsByTagName("vIPI")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vIPI")->item(0)->nodeValue : '';
                        $vBC = !empty($IPITrib->getElementsByTagName("vBC")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                        $pIPI = !empty($IPITrib->getElementsByTagName("pIPI")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("pIPI")->item(0)->nodeValue : '';
                        $qUnid = !empty($IPITrib->getElementsByTagName("qUnid")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("qUnid")->item(0)->nodeValue : '';
                        $vUnid = !empty($IPITrib->getElementsByTagName("vUnid")->item(0)->nodeValue) ? $IPITrib->getElementsByTagName("vUnid")->item(0)->nodeValue : '';
                        switch ($CST){
                            case '00': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '49': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '50': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '99': //CST 00, 49, 50 e 99
                                //O07|CST|VIPI|
                                $txtIPI = "O07|$CST|$vIPI\r\n";
                                break;
                            case '01': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '02': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '03': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '04': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '51': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '52': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '53': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                 $txtIPI = "O08|$CST\r\n";
                            break;
                            case '54': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                            case '55': //CST 01, 02, 03,04, 51, 52, 53, 54 e 55
                                //O08|CST|
                                $txtIPI = "O08|$CST\r\n";
                                break;
                        } // fim switch
        		//
                        if (substr($txtIPI,0,3) == 'O07' ) {
                             if ( $pIPI != '' ) {
                                 //O10|VBC|PIPI|
                                 $txtIPI .= "O10|$vBC|$pIPI\r\n";
                             } else {
                                 //O11|QUnid|VUnid|
                                 $txtIPI .= "O11|$qUnid|$vUnid\r\n";
                             } //fim if
                        } //fim if
                    } //fim ipi trib
                } // fim IPI
                $txt .= $txtIPI;

                //P|vBC|vDespAdu|vII|vIOF|
                if ( isset($II) ) {
                    $vBC = !empty($II->getElementsByTagName("vBC")->item(0)->nodeValue) ? $II->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $vDespAdu = !empty($II->getElementsByTagName("vDespAdu")->item(0)->nodeValue) ? $II->getElementsByTagName("vDespAdu")->item(0)->nodeValue : '';
                    $vII = !empty($II->getElementsByTagName("vII")->item(0)->nodeValue) ? $II->getElementsByTagName("vII")->item(0)->nodeValue : '';
                    $vIOF = !empty($II->getElementsByTagName("vIOF")->item(0)->nodeValue) ? $II->getElementsByTagName("vIOF")->item(0)->nodeValue : '';
                    $txt .= "P|$vBC|$vDespAdu|$vII|$vIOF\r\n";
                } // fim II

    		//monta dados do PIS
                if ( isset($PIS) ) {
                    //Q|
                    $txt .= "Q\r\n";
                    $CST = !empty($PIS->getElementsByTagName("CST")->item(0)->nodeValue) ? $PIS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                    $vBC = !empty($PIS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pPIS = !empty($PIS->getElementsByTagName("pPIS")->item(0)->nodeValue) ? $PIS->getElementsByTagName("pPIS")->item(0)->nodeValue : '';
                    $vPIS = !empty($PIS->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($PIS->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $PIS->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($PIS->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $PIS->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    if ( $CST == '01' || $CST == '02'){
                        //Q02|CST|vBC|pPIS|vPIS| // PIS TRIBUTADO PELA ALIQUOTA
                        $txt .= "Q02|$CST|$vBC|$pPIS|$vPIS\r\n";
                    }
                    if ( $CST == '03' ) {
                        //Q03|CST|qBCProd|vAliqProd|vPIS| //PIS TRIBUTADO POR QTDE
                        $txt .= "Q03|$CST|$qBCProd|$vAliqProd|$vPIS\r\n";
                    }
                    if ( $CST == '04' || $CST == '06' || $CST == '07' || $CST == '08' || $CST == '09') {
                        //Q04|CST| //PIS não tributado
                        $txt .= "Q04|$CST\r\n";
                    }
                    if ( $CST == '99' ) {
                        //Q05|CST|vPIS| //PIS OUTRAS OPERACOES
                        $txt .= "Q05|$CST|$vPIS\r\n";
                        //Q07|vBC|pPIS|
                        $txt .= "Q07|$vBC|$pPIS\r\n";
                        //Q10|qBCProd|vAliqProd|
                        $txt .= "Q10|$qBCProd|$vAliqProd\r\n";
                    }
                } //fim PIS

                //monta dados do PIS em Substituição Tributária
                if ( isset($PISST) ) {
                    $vPIS = !empty($PISST->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
                    $vBC = !empty($PISST->getElementsByTagName("vBC")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pPIS = !empty($PISST->getElementsByTagName("pPIS")->item(0)->nodeValue) ? $PISST->getElementsByTagName("pPIS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($PISST->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $PISST->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($PISST->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $PISST->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    //R|vPIS|
                    $txt .= "R|$vPIS\r\n";
                    //R02|vBC|pPIS|
                    $txt .= "R02|$vBC|$pPIS\r\n";
                    //R04|qBCProd|vAliqProd|
                    $txt .= "R04|$qBCProd|$vAliqProd\r\n";
                } //fim PISST

                //monta dados do COFINS
                if ( isset($COFINS) ) {
                    //S|
                    $txt .= "S\r\n";
                    $CST = !empty($COFINS->getElementsByTagName("CST")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("CST")->item(0)->nodeValue : '';
                    $vBC = !empty($COFINS->getElementsByTagName("vBC")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pCOFINS = !empty($COFINS->getElementsByTagName("pCOFINS")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("pCOFINS")->item(0)->nodeValue : '';
                    $vCOFINS = !empty($COFINS->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($COFINS->getElementsByTagName("qBCProdC")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($COFINS->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $COFINS->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    if ($CST == '01' || $CST == '02' ){
                        //S02|CST|VBC|PCOFINS|VCOFINS|
                        $txt .= "S02|$CST|$vBC|$pCOFINS|$vCOFINS\r\n";
                    }
                    if ( $CST == '03'){
                        //S03|CST|QBCProd|VAliqProd|VCOFINS|
                        $txt .= "S03|$CST|$qBCProd|$vAliqProd|$vCOFINS\r\n";
                    }
                    if ( $CST == '04' || $CST == '06' || $CST == '07' || $CST == '08' || $CST == '09' ){
                        //S04|CST|
                        $txt .= "S04|$CST\r\n";
                    }
                    if ( $CST == '99' ){
                        //S05|CST|VCOFINS|
                        $txt .= "S05|$CST|$vCOFINS\r\n";
                        //S07|VBC|PCOFINS|
                        $txt .= "S07|$vBC|$pCOFINS\r\n";
                        //S09|QBCProd|VAliqProd|
                        $txt .= "S09|$qBCProd|$vAliqProd\r\n";
                    }
                } //fim COFINS

                //monta dados do COFINS em Substituição Tributária
                if ( isset($COFINSST) ) {
                    $vCOFINS = !empty($COFINSST->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
                    $vBC = !empty($COFINSST->getElementsByTagName("vBC")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $pCOFINS = !empty($COFINSST->getElementsByTagName("pCOFINS")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("pCOFINS")->item(0)->nodeValue : '';
                    $qBCProd = !empty($COFINSST->getElementsByTagName("qBCProd")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("qBCProd")->item(0)->nodeValue : '';
                    $vAliqProd = !empty($COFINSST->getElementsByTagName("vAliqProd")->item(0)->nodeValue) ? $COFINSST->getElementsByTagName("vAliqProd")->item(0)->nodeValue : '';
                    //T|VCOFINS|
                    $txt .= "T|$vCOFINS\r\n";
                    //T02|VBC|PCOFINS|
                    $txt .= "T02|$vBC|$pCOFINS\r\n";
                    //T04|QBCProd|VAliqProd|
                    $txt .= "T04|$qBCProd|$vAliqProd\r\n";
                } //fim COFINSST

                //monta dados do ISS
                if ( isset($ISSQN) ) {
                    //U|vBC|vAliq|vISSQN|cMunFG|cListServ|
                    $vBC = !empty($ISSQN->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vBC")->item(0)->nodeValue : '';
                    $vAliq = !empty($ISSQN->getElementsByTagName("vAliq")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vAliq")->item(0)->nodeValue : '';
                    $vISSQN = !empty($ISSQN->getElementsByTagName("vISSQN")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("vISSQN")->item(0)->nodeValue : '';
                    $cMunFG = !empty($ISSQN->getElementsByTagName("cMunFG")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("cMunFG")->item(0)->nodeValue : '';
                    $cListServ = !empty($ISSQN->getElementsByTagName("cListServ")->item(0)->nodeValue) ? $ISSQN->getElementsByTagName("cListServ")->item(0)->nodeValue : '';
                    $txt .= "U|$vBC|$vAliq|$vISSQN|$cMunFG|$cListServ\r\n";
                } //fim ISSQN

        } // fim foreach produtos

        //W|
        $txt .= "W\r\n";
        $vBC = !empty($ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vBC")->item(0)->nodeValue : '';
        $vICMS = !empty($ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vICMS")->item(0)->nodeValue : '';
        $vBCST = !empty($ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vBCST")->item(0)->nodeValue : '';
        $vST = !empty($ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vST")->item(0)->nodeValue : '';
        $vProd = !empty($ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vProd")->item(0)->nodeValue : '';
        $vFrete = !empty($ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vFrete")->item(0)->nodeValue : '';
        $vSeg = !empty($ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vSeg")->item(0)->nodeValue : '';
        $vDesc = !empty($ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
        $vII = !empty($ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vII")->item(0)->nodeValue : '';
        $vIPI = !empty($ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vIPI")->item(0)->nodeValue : '';
        $vPIS = !empty($ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
        $vCOFINS = !empty($ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
        $vOutro = !empty($ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vOutro")->item(0)->nodeValue : '';
        $vNF = !empty($ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue) ? $ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue : '';

        //W02|vBC|vICMS|vBCST|vST|vProd|vFrete|vSeg|vDesc|vII|vIPI|vPIS|vCOFINS|vOutro|vNF|
        $txt .= "W02|$vBC|$vICMS|$vBCST|$vST|$vProd|$vFrete|$vSeg|$vDesc|$vII|$vIPI|$vPIS|$vCOFINS|$vOutro|$vNF\r\n";

        // monta dados do total de ISS
        if (isset($ISSQNtot)){
            //W17|vServ|vBC|vISS|vPIS|vCOFINS|
            $vServ = !empty($ISSQNTot->getElementsByTagName("vServ")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vServ")->item(0)->nodeValue : '';
            $vBC = !empty($ISSQNTot->getElementsByTagName("vBC")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vBC")->item(0)->nodeValue : '';
            $vISS = !empty($ISSQNTot->getElementsByTagName("vISS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vISS")->item(0)->nodeValue : '';
            $vPIS = !empty($ISSQNTot->getElementsByTagName("vPIS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vPIS")->item(0)->nodeValue : '';
            $vCOFINS = !empty($ISSQNTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue) ? $ISSQNTot->getElementsByTagName("vCOFINS")->item(0)->nodeValue : '';
            $txt .= "W17|$vServ|$vBC|$vISS|$vPIS|$vCOFINS\r\n";
        } //fim ISSQNtot

        //monta dados da Retenção de tributos
        if ( isset($retTrib) ) {
            //W23|VRetPIS|VRetCOFINS|VRetCSLL|VBCIRRF|VIRRF|VBCRetPrev|VRetPrev|
            $vRetPIS = !empty($retTrib->getElementsByTagName("vRetPIS")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetPIS")->item(0)->nodeValue : '';
            $vRetCOFINS = !empty($retTrib->getElementsByTagName("vRetCOFINS")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetCOFINS")->item(0)->nodeValue : '';
            $vRetCSLL = !empty($retTrib->getElementsByTagName("vRetCSLL")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetCSLL")->item(0)->nodeValue : '';
            $vBCIRRF = !empty($retTrib->getElementsByTagName("vBCIRRF")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vBCIRRF")->item(0)->nodeValue : '';
            $vIRRF = !empty($retTrib->getElementsByTagName("vIRRF")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vIRRF")->item(0)->nodeValue : '';
            $vBCRetPrev = !empty($retTrib->getElementsByTagName("vBCRetPrev")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vBCRetPrev")->item(0)->nodeValue : '';
            $vRetPrev = !empty($retTrib->getElementsByTagName("vRetPrev")->item(0)->nodeValue) ? $retTrib->getElementsByTagName("vRetPrev")->item(0)->nodeValue : '';
            $txt .= "W23|$vRetPIS|$vRetCOFINS|$vRetCSLL|$vBCIRRF|$vIRRF|$vBCRetPrev|$vRetPrev\r\n";
        }

        //monta dados de Transportes
        if ( isset($transp) ) {
            //instancia sub grupos da tag transp
            $transporta = $dom->getElementsByTagName("transporta")->item(0);
            $retTransp  = $dom->getElementsByTagName("retTransp")->item(0);
            $veicTransp = $dom->getElementsByTagName("veicTransp")->item(0);
            $reboque = $dom->getElementsByTagName("reboque");
            $vol = $dom->getElementsByTagName("vol");
            $lacres = $dom->getElementsByTagName("lacres");

            //X|ModFrete|
            $modFrete = !empty($transp->getElementsByTagName("modFrete")->item(0)->nodeValue) ? $transp->getElementsByTagName("modFrete")->item(0)->nodeValue : '';
            $txt .= "X|$modFrete\r\n";
            if ( isset($transporta) ){
                $CNPJ = !empty($transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue) ? $transporta->getElementsByTagName("CNPJ")->item(0)->nodeValue : '';
                $CPF = !empty($transporta->getElementsByTagName("CPF")->item(0)->nodeValue) ? $transporta->getElementsByTagName("CPF")->item(0)->nodeValue : '';
                $IE = !empty($transporta->getElementsByTagName("IE")->item(0)->nodeValue) ? $transporta->getElementsByTagName("IE")->item(0)->nodeValue : '';
                $xNome = !empty($transporta->getElementsByTagName("xNome")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xNome")->item(0)->nodeValue : '';
                $xEnder = !empty($transporta->getElementsByTagName("xEnder")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xEnder")->item(0)->nodeValue : '';
                $xMun = !empty($transporta->getElementsByTagName("xMun")->item(0)->nodeValue) ? $transporta->getElementsByTagName("xMun")->item(0)->nodeValue : '';
                $UF = !empty($transporta->getElementsByTagName("UF")->item(0)->nodeValue) ? $transporta->getElementsByTagName("UF")->item(0)->nodeValue : '';
                //X03|XNome|IE|XEnder|UF|XMun|
                $txt .= "X03|$xNome|$IE|$xEnder|$UF|$xMun\r\n";
                    if ( $CNPJ != '' ) {
                        //X04|CNPJ|
                        $txt .= "X04|$CNPJ\r\n";
                    } else {
                        //X05|CPF|
                        $txt .= "X05|$CPF\r\n";
                    } //fim if cpf ou cnpj
                } // fim transporta

                //monta dados da retenção tributária de transporte
                if ( isset($retTransp) ) {
                    $vServ = !empty($retTransp->getElementsByTagName("vServ")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vServ")->item(0)->nodeValue : '';
                    $vBCRet = !empty($retTransp->getElementsByTagName("vBCRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vBCRet")->item(0)->nodeValue : '';
                    $pICMSRet = !empty($retTransp->getElementsByTagName("pICMSRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("pICMSRet")->item(0)->nodeValue : '';
                    $vICMSRet = !empty($retTransp->getElementsByTagName("vICMSRet")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("vICMSRet")->item(0)->nodeValue : '';
                    $CFOP = !empty($retTransp->getElementsByTagName("CFOP")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("CFOP")->item(0)->nodeValue : '';
                    $cMunFG = !empty($retTransp->getElementsByTagName("cMunFG")->item(0)->nodeValue) ? $retTransp->getElementsByTagName("cMunFG")->item(0)->nodeValue : '';
                    //X11|VServ|VBCRet|PICMSRet|VICMSRet|CFOP|CMunFG|
                    $txt .= "X11|$vServ|$vBCRet|$pICMSRet|$vICMSRet|$CFOP|$cMunFG\r\n";
                } // fim rettransp

                //monta dados de identificação dos veiculos utilizados no transporte
                if ( isset($veicTransp) ) {
                    //X18|Placa|UF|RNTC|
                    $placa = !empty($veicTransp->getElementsByTagName("placa")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("placa")->item(0)->nodeValue : '';
                    $UF = !empty($veicTransp->getElementsByTagName("UF")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("UF")->item(0)->nodeValue : '';
                    $RNTC = !empty($veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $veicTransp->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
                    $txt .= "X18|$placa|$UF|$RNTC\r\n";
                } //fim veicTransp

                //monta dados de identificação dos reboques utilizados no transporte
                if ( isset($reboque) ){
                    foreach ($reboque as $n => $reb){
                        $placa = !empty($reboque->item($n)->getElementsByTagName("placa")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("placa")->item(0)->nodeValue : '';
                        $UF = !empty($reboque->item($n)->getElementsByTagName("UF")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("UF")->item(0)->nodeValue : '';
                        $RNTC = !empty($reboque->item($n)->getElementsByTagName("RNTC")->item(0)->nodeValue) ? $reboque->item($n)->getElementsByTagName("RNTC")->item(0)->nodeValue : '';
                        //X22|Placa|UF|RNTC|
                        $txt .= "X22|$placa|$UF|$RNTC\r\n";
                    } //fim foreach
                } //fim reboque

                //monta dados dos volumes transpotados
                if ( isset($vol) ){
                    foreach ($vol as $n => $volumes) {
                        //X26|QVol|Esp|Marca|NVol|PesoL|PesoB|
                        $qVol = !empty($vol->item($n)->getElementsByTagName("qVol")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("qVol")->item(0)->nodeValue : '';
                        $esp = !empty($vol->item($n)->getElementsByTagName("esp")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("esp")->item(0)->nodeValue : '';
                        $marca = !empty($vol->item($n)->getElementsByTagName("marca")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("marca")->item(0)->nodeValue : '';
                        $nVol = !empty($vol->item($n)->getElementsByTagName("nVol")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("nVol")->item(0)->nodeValue : '';
                        $pesoL = !empty($vol->item($n)->getElementsByTagName("pesoL")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("pesoL")->item(0)->nodeValue : '';
                        $pesoB = !empty($vol->item($n)->getElementsByTagName("pesoB")->item(0)->nodeValue) ? $vol->item($n)->getElementsByTagName("pesoB")->item(0)->nodeValue : '';
                        $txt .= "X26|$qVol|$esp|$marca|$nVol|$pesoL|$pesoB\r\n";
                    } //fim foreach volumes
                } //fim vol

                //monta dados dos lacres utilizados
                if ( isset($lacres) ){
                    foreach ($lacres as $n => $lac){
                        $nLacre = !empty($lacres->item($n)->getElementsByTagName("nLacre")->item(0)->nodeValue) ? $lacres->item($n)->getElementsByTagName("nLacre")->item(0)->nodeValue : '';
                        //X33|NLacre|
                        $txt .= "X33|$nLacre\r\n";
                    } //fim foreach lacre
                } //fim lacres
            } // fim transp

            //monta dados de cobrança
            if ( isset($cobr) ){
                //instancia sub grupos da tag cobr
                $fat = $dom->getElementsByTagName('fat')->item(0);
                $dup = $dom->getElementsByTagName('dup');
                $txt .= "Y\r\n";
                //monta dados da fatura
                if ( isset($fat) ){
                    //Y02|NFat|VOrig|VDesc|VLiq|
                    $nFat = !empty($fat->getElementsByTagName("nFat")->item(0)->nodeValue) ? $fat->getElementsByTagName("nFat")->item(0)->nodeValue : '';
                    $vOrig = !empty($fat->getElementsByTagName("vOrig")->item(0)->nodeValue) ? $fat->getElementsByTagName("vOrig")->item(0)->nodeValue : '';
                    $vDesc = !empty($fat->getElementsByTagName("vDesc")->item(0)->nodeValue) ? $fat->getElementsByTagName("vDesc")->item(0)->nodeValue : '';
                    $vLiq = !empty($fat->getElementsByTagName("vLiq")->item(0)->nodeValue) ? $fat->getElementsByTagName("vLiq")->item(0)->nodeValue : '';
                    $txt .= "Y02|$nFat|$vOrig|$vDesc|$vLiq\r\n";
                } //fim fat

                //monta dados das duplicatas
                if( isset($dup) ){
                   foreach ( $dup as $n => $duplicata ){
                        //Y07|NDup|DVenc|VDup|
                        $nDup = !empty($dup->item($n)->getElementsByTagName("nDup")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("nDup")->item(0)->nodeValue : '';
                        $dVenc = !empty($dup->item($n)->getElementsByTagName("dVenc")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("dVenc")->item(0)->nodeValue : '';
                        $vDup = !empty($dup->item($n)->getElementsByTagName("vDup")->item(0)->nodeValue) ? $dup->item($n)->getElementsByTagName("vDup")->item(0)->nodeValue : '';
                        $txt .= "Y07|$nDup|$dVenc|$vDup\r\n";
                    } //fim foreach
                } //fim dup
            } //fim cobr

            //monta dados das informações adicionais da NFe
            if ( isset($infAdic) ) {
                //instancia sub grupos da tag infAdic
                $obsCont = $dom->getElementsByTagName('obsCont');

                //Z|InfAdFisco|InfCpl|
                $infAdFisco = !empty($infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue) ? $infAdic->getElementsByTagName("infAdFisco")->item(0)->nodeValue : '';
                $infCpl = !empty($infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue) ? $infAdic->getElementsByTagName("infCpl")->item(0)->nodeValue : '';
                $txt .= "Z|$infAdFisco|$infCpl\r\n";

                //monta dados de observaçoes da NFe
                if ( isset($obsCont) ){
                    foreach ($obsCont as $n => $oC){
                        //Z04|XCampo|XTexto|
                        $xCampo = !empty($obsCont->item($n)->getElementsByTagName("xCampo")->item(0)->nodeValue) ? $obsCont->item($n)->getElementsByTagName("xCampo")->item(0)->nodeValue : '';
                        $xTexto = !empty($obsCont->item($n)->getElementsByTagName("xTexto")->item(0)->nodeValue) ? $obsCont->item($n)->getElementsByTagName("xTexto")->item(0)->nodeValue : '';
                        $txt .= "Z04|$xCampo|$xTexto\r\n";
                    } //fim foreach
                } //fim obsCont

            } //fim infAdic

            //monta dados dos processos
            if ( isset($procRef) ){
                foreach ($procRef as $n => $pR){
                    //Z10|NProc|IndProc|
                    $nProc = !empty($procRef->item($n)->getElementsByTagName("nProc")->item(0)->nodeValue) ? $procRef->item($n)->getElementsByTagName("nProc")->item(0)->nodeValue : '';
                    $indProc = !empty($procRef->item($n)->getElementsByTagName("infProc")->item(0)->nodeValue) ? $procRef->item($n)->getElementsByTagName("infProc")->item(0)->nodeValue : '';
                    $txt .= "Z10|$nProc|$indProc\r\n";
                } //fim foreach
            } //fim procRef

            //monta dados de exportação
            if ( isset($exporta) ){
                //ZA|UFEmbarq|XLocEmbarq|
                $UFEmbarq = !empty($exporta->getElementsByTagName("UFEmbarq")->item(0)->nodeValue) ? $exporta->getElementsByTagName("UFEmbarq")->item(0)->nodeValue : '';
                $xLocEmbarq = !empty($exporta->getElementsByTagName("xLocEmbarq")->item(0)->nodeValue) ? $exporta->getElementsByTagName("xLocEmbarq")->item(0)->nodeValue : '';
                $txt .= "ZA|$UFEmbarq|$xLocEmbarq\r\n";
            } //fim exporta

            //monta dados de compra
            if ( isset($compra) ){
                //ZB|XNEmp|XPed|XCont|
                $xNEmp = !empty($compra->getElementsByTagName("xNEmp")->item(0)->nodeValue) ? $compra->getElementsByTagName("xNEmp")->item(0)->nodeValue : '';
                $xPed = !empty($compra->getElementsByTagName("xPed")->item(0)->nodeValue) ? $compra->getElementsByTagName("xPed")->item(0)->nodeValue : '';
                $xCont = !empty($compra->getElementsByTagName("xCont")->item(0)->nodeValue) ? $compra->getElementsByTagName("xCont")->item(0)->nodeValue : '';
                $txt .= "ZB|$xNEmp|$xPed|$xCont\r\n";
            } //fim compra

        } //end for
        $this->txt = $txt;
        return $txt;
    }// fim da função nfexml2txt

    /**
     * __limpaString
     * Remove todos dos caracteres especiais do texto e os acentos
     * preservando apenas letras de A-Z numeros de 0-9 e os caracteres @ , - ; : / _
     *  
     * @version 1.0.4
     * @package NFePHP
     * @author  Roberto L. Machado <linux.rlm at gmail dot com>
     * @return  string Texto sem caractere especiais
     */
     private function __limpaString($texto){
        $aFind = array('&','á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç');
        $aSubs = array('e','a','a','a','a','e','e','i','o','o','o','u','u','c','A','A','A','A','E','E','I','O','O','O','U','U','C');
        $novoTexto = str_replace($aFind,$aSubs,$texto);
        $novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
        return $novoTexto;
    }//fim __limpaString

    
} //fim da classe

?>
