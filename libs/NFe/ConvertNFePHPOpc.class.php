<?php



class ConvertNfeNFePHP
{
    public $campos_v200=array(
                // nomes errados no manual (baseado no nome dos campos XML)
        'A'    =>'A|versao|Id|',
            //'A|versão do schema|id|'
        'B'    =>'B|cUF|cNF|natOp|indPag|mod|serie|nNF|dEmi|dSaiEnt|hSaiEnt|tpNF|cMunFG|tpImp|tpEmis|cDV|tpAmb|finNFe|procEmi|verProc|dhCont|xJust|',
            //'B|cUF|cNF|NatOp|intPag|mod|serie|nNF|dEmi|dSaiEnt|hSaiEnt|tpNF|cMunFG|TpImp|TpEmis|cDV|tpAmb|finNFe|procEmi|VerProc|dhCont|xJust|'
        'B13'    =>'B13|refNFe|',
        'B14'    =>'B14|cUF|AAMM|CNPJ|mod|serie|nNF|',
            //'B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|'
        'B20a'    =>'B20a|cUF|AAMM|IE|mod|serie|nNF|',
        'B20d'    =>'B20d|CNPJ|',
        'B20e'    =>'B20e|CPF|',
        'B20i'    =>'B20i|refCTe|',
        'B20j'    =>'B20j|mod|nECF|nCOO|',
        'C'    =>'C|xNome|xFant|IE|IEST|IM|CNAE|CRT|',
            //'C|XNome|XFant|IE|IEST|IM|CNAE|CRT|'
        'C02'    =>'C02|CNPJ|',
        'C02a'    =>'C02a|CPF|',
        'C05'    =>'C05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|',
            //'C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone|'
        'D'    =>'D|CNPJ|xOrgao|matr|xAgente|fone|UF|nDAR|dEmi|vDAR|repEmi|dPag|',
        'E'    =>'E|xNome|IE|ISUF|email|',
        'E02'    =>'E02|CNPJ|',
        'E03'    =>'E03|CPF|',
        'E05'    =>'E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|',
        'F'    =>'F|CNPJ|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|',
            //'F|CNPJ|XLgr|Nro|XCpl|XBairro|CMun|XMun|UF|'
        'F02'    =>'F02|CNPJ|',
            //'F02|CNPJ'
        'F02a'    =>'F02a|CPF|',
            //'F02a|CPF'
        'G'    =>'G|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|',    // linha errada no manual, campo cnpj a mais - testado com o emissor do governo
            //'G|CNPJ|XLgr|Nro|XCpl|XBairro|CMun|XMun|UF|'
        'G02'    =>'G02|CNPJ|',
            //'G02|CNPJ'
        'G02a'    =>'G02a|CPF|',
            //'G02a|CPF'
        'H'    =>'H|nItem|infAdProd|',
        'I'    =>'I|cProd|cEAN|xProd|NCM|EXTIPI|CFOP|uCom|qCom|vUnCom|vProd|cEANTrib|uTrib|qTrib|vUnTrib|vFrete|vSeg|vDesc|vOutro|indTot|xPed|nItemPed|',
            //'I|CProd|CEAN|XProd|NCM|EXTIPI|CFOP|UCom|QCom|VUnCom|VProd|CEANTrib|UTrib|QTrib|VUnTrib|VFrete|VSeg|VDesc|vOutro|indTot|xPed|nItemPed|'
        'I18'    =>'I18|nDI|dDI|xLocDesemb|UFDesemb|dDesemb|cExportador|',
            //'I18|NDI|DDI|XLocDesemb|UFDesemb|DDesemb|CExportador|'
        'I25'    =>'I25|nAdicao|nSeqAdic|CFabricante|vDescDI|',
            //'I25|NAdicao|NSeqAdic|CFabricante|VDescDI|'
        'J'    =>'J|tpOp|chassi|cCor|xCor|pot|cilin|pesoL|pesoB|nSerie|tpComb|nMotor|CMT|dist|anoMod|anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest|',
            //'J|TpOp|Chassi|CCor|XCor|Pot|cilin|pesoL|pesoB|NSerie|TpComb|NMotor|CMT|Dist|anoMod|anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest|'
        'K'    =>'K|nLote|qLote|dFab|dVal|vPMC|',
            //'K|NLote|QLote|DFab|DVal|VPMC|'
        'L'    =>'L|tpArma|nSerie|nCano|descr|',
            //'L|TpArma|NSerie|NCano|Descr|'
        'L01'    =>'L01|cProdANP|CODIF|qTemp|UFCons|',
            //'L01|CProdANP|CODIF|QTemp|UFCons|'
        'L105'    =>'L105|qBCProd|vAliqProd|vCIDE|',
            //'L105|QBCProd|VAliqProd|VCIDE|'
        'M'    =>'M|vTotTrib|',    // lei da transparencia
        'N'    =>'N|',
        'N02'    =>'N02|orig|CST|modBC|vBC|pICMS|vICMS|',
        'N03'    =>'N03|orig|CST|modBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|',
        'N04'    =>'N04|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|',
        'N05'    =>'N05|orig|CST|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|',
        'N06'    =>'N06|orig|CST|vICMS|motDesICMS|',
        'N07'    =>'N07|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|',
        'N08'    =>'N08|orig|CST|vBCSTRet|vICMSSTRet|',
        //'N08'    =>'N08|orig|CST|vBCST|vICMSST|',
        'N09'    =>'N09|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|',
        'N10'    =>'N10|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|',
        'N10a'    =>'N10a|orig|CST|modBC|pRedBC|vBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pBCOp|UFST|',
        'N10b'    =>'N10b|orig|CST|vBCSTRet|vICMSSTRet|vBCSTDest|vICMSSTDest|',
        'N10c'    =>'N10c|orig|CSOSN|pCredSN|vCredICMSSN|',
        'N10d'    =>'N10d|orig|CSOSN|',
        'N10e'    =>'N10e|orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN|',
        'N10f'    =>'N10f|orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|',
        'N10g'    =>'N10g|orig|CSOSN|modBCST|vBCSTRet|vICMSSTRet|',
        'N10h'    =>'N10h|orig|CSOSN|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN|',
            // GRUPO Nxxx - ESSE DIGITARAM BEMMM DIFERENTE em todos tem diferença... resumo:
            // 'Orig' => 'orig'    ( atenção, no manual do XML (pois é...) esta alguns campos do simples nacional como 'Orig', e estão errados... são 'orig', segundo o aplicativo do governo )
            // 'ModBC' => 'modBC'
            // 'VBC' => 'vBC'
            // 'PICMS' => 'pICMS'
            // 'VICMS' => 'vICMS'
            // 'ModBCST' => 'modBCST'
            // 'PMVAST' => 'pMVAST'
            // 'PRedBCST' => 'pRedBCST'
            // 'VBCST' => 'vBCST'
            // 'PICMSST' => 'pICMSST'
            // 'VICMSST' => 'vICMSST'
        'O'    =>'O|clEnq|CNPJProd|cSelo|qSelo|cEnq|',
            //'O|ClEnq|CNPJProd|CSelo|QSelo|CEnq|'
        'O07'    =>'O07|CST|vIPI|',
            //'O07|CST|VIPI|'
        'O10'    =>'O10|vBC|pIPI|',
            //'O10|VBC|PIPI|'
        'O11'    =>'O11|qUnid|vUnid|',
            //'O11|QUnid|VUnid|'
        'O08'    =>'O08|CST|',
        'P'    =>'P|vBC|vDespAdu|vII|vIOF|',
            //'P|VBC|VDespAdu|VII|VIOF|'
        'U'    =>'U|vBC|vAliq|vISSQN|cMunFG|cListServ|cSitTrib|',
            //'U|VBC|VAliq|VISSQN|CMunFG|CListServ|cSitTrib|'
        'Q'    =>'Q|',
        'Q02'    =>'Q02|CST|vBC|pPIS|vPIS|',
            //'Q02|CST|VBC|PPIS|VPIS|'
        'Q03'    =>'Q03|CST|qBCProd|vAliqProd|vPIS|',
            //'Q03|CST|QBCProd|VAliqProd|VPIS|'
        'Q04'    =>'Q04|CST|',
        'Q05'    =>'Q05|CST|vPIS|',
            //'Q05|CST|VPIS|'
        'Q07'    =>'Q07|VBC|pPIS|',
            //'Q07|VBC|PPIS|'
        'Q10'    =>'Q10|QBCProd|VAliqProd|',
            //'Q10|qBCProd|vAliqProd|'
        'R'    =>'R|vPIS|',
            //'R|VPIS|'
        'R02'    =>'R02|vBC|pPIS|',
            //'R02|VBC|PPIS|'
        'R04'    =>'R04|qBCProd|vAliqProd|',
            //'R04|QBCProd|VAliqProd|'
        'S'    =>'S|',
        'S02'    =>'S02|CST|vBC|pCOFINS|vCOFINS|',
            //'S02|CST|VBC|PCOFINS|VCOFINS|'
        'S03'    =>'S03|CST|qBCProd|vAliqProd|vCOFINS|',
            //'S03|CST|QBCProd|VAliqProd|VCOFINS|'
        'S04'    =>'S04|CST|',
        'S05'    =>'S05|CST|vCOFINS|',
            //'S05|CST|VCOFINS|'
        'S07'    =>'S07|VBC|pCOFINS|',
            //'S07|VBC|PCOFINS|'
        'S09'    =>'S09|qBCProd|vAliqProd|',
            //'S09|QBCProd|VAliqProd|'
        'T'    =>'T|vCOFINS|',
            //'T|VCOFINS|'
        'T02'    =>'T02|vBC|pCOFINS|',
            //'T02|VBC|PCOFINS|'
        'T04'    =>'T04|qBCProd|vAliqProd|',
            //'T04|QBCProd|VAliqProd|'
        'W'    =>'W|',
        'W02'    =>'W02|vBC|vICMS|vBCST|vST|vProd|vFrete|vSeg|vDesc|vII|vIPI|vPIS|vCOFINS|vOutro|vNF|vTotTrib|',    // lei da transparencia
        'W17'    =>'W17|vServ|vBC|vISS|vPIS|vCOFINS|',
            //'W17|VServ|VBC|VISS|VPIS|VCOFINS|'
        'W23'    =>'W23|vRetPIS|vRetCOFINS|vRetCSLL|vBCIRRF|vIRRF|vBCRetPrev|vRetPrev|',
            //'W23|VRetPIS|VRetCOFINS|VRetCSLL|VBCIRRF|VIRRF|VBCRetPrev|VRetPrev|'
        'X'    =>'X|modFrete|',
            //'X|ModFrete|'
        'X03'    =>'X03|xNome|IE|xEnder|UF|xMun|',
            //'X03|XNome|IE|XEnder|UF|XMun|'
                // o certo seria primeiro xmun, depois uf, pois é como está no XML....
        'X04'    =>'X04|CNPJ|',
        'X05'    =>'X05|CPF|',
        'X11'    =>'X11|vServ|vBCRet|pICMSRet|vICMSRet|CFOP|cMunFG|',
            //'X11|VServ|VBCRet|PICMSRet|VICMSRet|CFOP|CMunFG|'
        'X18'    =>'X18|placa|UF|RNTC|',
            //'X18|Placa|UF|RNTC|'
        'X22'    =>'X22|placa|UF|RNTC|',
            //'X22|Placa|UF|RNTC|'
        'X26'    =>'X26|qVol|esp|marca|nVol|pesoL|pesoB|',
            //'X26|QVol|Esp|Marca|NVol|PesoL|PesoB|'
        'X33'    =>'X33|NLacre|',
            //'X33|nLacre|'
        'Y'    =>'Y|',
        'Y02'    =>'Y02|nFat|vOrig|vDesc|vLiq|',
            //'Y02|NFat|VOrig|VDesc|VLiq|'
        'Y07'    =>'Y07|nDup|dVenc|vDup|',
            //'Y07|NDup|DVenc|VDup|'
        'Z'    =>'Z|infAdFisco|infCpl|',
            //'Z|InfAdFisco|InfCpl|'
        'Z04'    =>'Z04|xCampo|xTexto|',
            //'Z04|XCampo|XTexto|'
        'Z07'    =>'Z07|xCampo|xTexto|',
            //'Z07|XCampo|XTexto|'
        'Z10'    =>'Z10|nProc|indProc|',
            //'Z10|NProc|IndProc|'
        'ZA'    =>'ZA|UFEmbarq|xLocEmbarq|',
            //'ZA|UFEmbarq|XLocEmbarq|'
        'ZB'    =>'ZB|xNEmp|xPed|xCont|',
            //'ZB|XNEmp|XPed|XCont|'
        'ZC01'    =>'ZC01|safra|ref|qTotMes|qTotAnt|qTotGer|vFor|vTotDed|vLiqFor|',
        'ZC04'    =>'ZC04|dia|qtde|',
        'ZC10'    =>'ZC10|xDed|vDed|'
        );
        // quais tags podem precender a tag atual
    protected $campos_v200_lasttag=array(
            'A'    =>array('NOTAFISCAL','NOTA FISCAL'),
            'B'    =>array('A'),
            'B13'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'B14'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'B20a'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'B20d'    =>array('B20a'),
            'B20e'    =>array('B20a'),
            'B20i'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'B20j'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'C'    =>array('B','B13','B14','B20a','B20d','B20e','B20i','B20j'),
            'C02'    =>array('C'),
            'C02a'    =>array('C'),
            'C05'    =>array('C02','C02a'),
            'C05'    =>array('C02','C02a'),
            'D'    =>array('C05'),
            'E'    =>array('C05','D'),
            'E02'    =>array('E'),
            'E03'    =>array('E'),
            'E05'    =>array('E02','E03'),
            'F'    =>array('E05'),
            'F02'    =>array('F'),
            'F02a'    =>array('F'),
            'G'    =>array('E05','F02','F02a'),
            'G02'    =>array('G'),
            'G02a'    =>array('G'),
            'H'    =>array('E05','F02','F02a','G02','G02a',
                    'T04','T02','S02','S03','S04','S07','S09'),
            'I'    =>array('H'),
            'I18'    =>array('I','I18'),
            'I25'    =>array('I18','I25'),
            'J'    =>array('I','I18','I25'),
            'K'    =>array('I','I18','I25','K'),
            'L'    =>array('I','I18','I25','L'),
            'L01'    =>array('I','I18','I25','L01','L105'),
            'L105'    =>array('L01','L105'),
            'M'    =>array('I','I18','I25','J','K','L','L01','L105'),
            'N'    =>array('M'),
            'N02'    =>array('N'),
            'N03'    =>array('N'),
            'N04'    =>array('N'),
            'N05'    =>array('N'),
            'N06'    =>array('N'),
            'N07'    =>array('N'),
            'N08'    =>array('N'),
            'N09'    =>array('N'),
            'N10'    =>array('N'),
            'N10a'    =>array('N'),
            'N10b'    =>array('N'),
            'N10c'    =>array('N'),
            'N10d'    =>array('N'),
            'N10e'    =>array('N'),
            'N10f'    =>array('N'),
            'N10g'    =>array('N'),
            'N10h'    =>array('N'),
            'O'    =>array('N02','N03','N04','N05','N06','N07','N08','N09','N10','N10a','N10b','N10c','N10d','N10e','N10f','N10g','N10h'),
            'O07'    =>array('O'),
            'O10'    =>array('O07'),
            'O11'    =>array('O07'),
            'O08'    =>array('O'),
            'P'    =>array('O07','O08','O10','O11',
                    'N02','N03','N04','N05','N06','N07','N08','N09','N10','N10a','N10b','N10c','N10d','N10e','N10f','N10g','N10h'),
            'U'    =>array('O07','O08','O10','O11','P',
                    'N02','N03','N04','N05','N06','N07','N08','N09','N10','N10a','N10b','N10c','N10d','N10e','N10f','N10g','N10h'),
            'Q'    =>array('O07','O08','O10','O11','P','U',
                    'N02','N03','N04','N05','N06','N07','N08','N09','N10','N10a','N10b','N10c','N10d','N10e','N10f','N10g','N10h'),
            'Q02'    =>array('Q'),
            'Q03'    =>array('Q'),
            'Q04'    =>array('Q'),
            'Q05'    =>array('Q'),
            'Q07'    =>array('Q05'),
            'Q10'    =>array('Q05'),
            'R'    =>array('Q10','Q07','Q05','Q04','Q03','Q02'),
            'R02'    =>array('R'),
            'R04'    =>array('R'),
            'S'    =>array('R02','R04','Q02','Q03','Q04','Q07','Q10'),
            'S02'    =>array('S'),
            'S03'    =>array('S'),
            'S04'    =>array('S'),
            'S05'    =>array('S'),
            'S07'    =>array('S05'),
            'S09'    =>array('S05'),
            'T'    =>array('S02','S03','S04','S07','S09'),
            'W'    =>array('T04','T02','S02','S03','S04','S07','S09'),
            'W02'    =>array('W'),
            'W17'    =>array('W'),
            'W23'    =>array('W','W17'),
            'X'    =>array('W02','W17','W23'),
            'X03'    =>array('X'),
            'X04'    =>array('X03'),
            'X05'    =>array('X03'),
            'X11'    =>array('X04','X05'),
            'X18'    =>array('X04','X05','X11'),
            'X22'    =>array('X04','X05','X11','X18'),
            'X26'    =>array('X04','X05','X11','X18','X22','X26','X33'),
            'X33'    =>array('X26','X33'),
            'Y'    =>array('X04','X05','X11','X18','X22','X26','X33'),
            'Y02'    =>array('Y'),
            'Y07'    =>array('Y','Y07'),
            'Z'    =>array('X04','X05','X11','X18','X22','X26','X33',
                    'Y','Y02','Y07'),
            'Z04'    =>array('Z','Z04'),
            'Z07'    =>array('Z','Z07','Z04'),
            'Z10'    =>array('Z','Z07','Z04','Z10'),
            'ZA'    =>array('X04','X05','X11','X18','X22','X26','X33',
                    'Y','Y02','Y07',
                    'Z','Z07','Z04','Z10'),
            'ZB'    =>array('X04','X05','X11','X18','X22','X26','X33',
                    'Y','Y02','Y07',
                    'Z','Z07','Z04','Z10','ZA'),
            'ZC01'    =>array('X04','X05','X11','X18','X22','X26','X33',
                    'Y','Y02','Y07',
                    'Z','Z07','Z04','Z10','ZA','ZB'),
            'ZC04'    =>array('ZC01','ZC04','ZC10'),
            'ZC10'    =>array('ZC01','ZC04','ZC10'));
    function __construct(){
    }
 
    public function TXT2XML($txt,$output_string=true){
        // CARREGA ARQUIVO
        $RETURN=array(    'erros'    =>array(),
                'avisos'=>array(),
                'xml'    =>array());    // erros de interpretação do arquivo
        if(is_file($txt))
            $txt=file_get_contents($txt);
        // PROCESSA STRING E GERA ARRAY
        $txt=$this->_TXT2XML_processa_txt($txt);    // esta função gera erros de interpretação do arquivo e separa as notas do arquivo
        $RETURN['erros']    =array_merge($RETURN['erros'],$txt['erros']);
        $RETURN['avisos']    =array_merge($RETURN['avisos'],$txt['avisos']);
        foreach($txt['docs'] as $k=>$v)        // esta interpreta linha a linha das notas
            $RETURN['xml'][$k]=$this->_TXT2XML_processa_array($v,$output_string);
        return($RETURN);
    }
    private function _TXT2XML_processa_txt_converte_versao($array){
        // converte o array para o array da ultima versão (exemplo, de 1.00 para 2.00)
        return(array());    // atualmente retorna em branco
    }
    private function _TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,$TAG_EMBRANCO,$campos,& $RETURN, & $cur_nota_tags, $cur_nota){
        $RETURN['erros'][]="$MSG_PADRAO TAG informada sem a tag '$TAG_EMBRANCO' ser informada, gerando tag em branco.";
        $tmp_v        =array();
        if(is_array($campos))
            $tmp_campos    =explode("|",$campos[$TAG_EMBRANCO]);
        else
            $tmp_campos    =explode("|",$campos);
        $RETURN['docs'][$cur_nota][]=$this->_TXT2XML_processa_txt_tag($MSG_PADRAO,$tmp_v,$tmp_campos,$RETURN);
        $cur_nota_tags[$TAG_EMBRANCO]=1;
        unset($tmp_campos,$tmp_v);
    }
    private function _TXT2XML_processa_txt_tag(&$MSG_PADRAO,&$v,&$campos,& $RETURN){
        // retorna o array da tag
        $ret    =array('TAG'=>$campos[0]);
        if(!is_array($v))
            $v=array();
        if(count($v)!=count($campos))
            $RETURN['avisos'][]="$MSG_PADRAO Quantidade de campos na tag (".count($v).") é diferente de ".count($campos);
        for($i=1;$i<count($campos);$i++){
            if(trim($campos[$i])==='')
                continue;
            if(!isset($v[$i])){
                $RETURN['erros'][]="$MSG_PADRAO Campo [$i] '".$campos[$i]."' não informado.";
                $v[$i]='';
            }
            $ret[ $campos[$i] ]=$v[$i];
        }
        return($ret);
    }
    private function _TXT2XML_verifica_last_tag(& $last_tag_array, $last_tag, $cur_tag, & $RETURN){
        if(!isset($last_tag_array[$cur_tag]))
            return;
        if(    !in_array($last_tag,    $this->campos_v200_lasttag[$cur_tag]) && 
            !is_array(        $this->campos_v200_lasttag[$cur_tag]))
            $RETURN['erros'][]="$MSG_PADRAO Ultima tag $last_tag, não esta em: ". 
                implode(', ',    $this->campos_v200_lasttag[$cur_tag]);
        return;
    }
    private function _TXT2XML_processa_txt($string){
        // processa arquivo TXT (string) e gera um array das notas
        
        $RETURN=array(
            'erros'        =>array(),    // erros de importação independente de qual nota esta...
            'avisos'    =>array(),    // avisos de importação independente de qual nota esta...
            'docs'        =>array()    // notas
            );
            
        $campos_v200_tag_itens=array(
            'I','I18','I25','J','K','L','L01','L105','M','N',
            'N02','N03','N04','N05','N06','N07','N08','N09','N10',
            'N10a','N10b','N10c','N10d','N10e','N10f','N10g','N10h',
            'O','O07','O10','O11','O08','P','U',
            'Q','Q02','Q03','Q04','Q05','Q07','Q10',
            'R','R02','R04','S','S02','S03','S04','S05','S07','S09','T','T02','T04');
        
        
        // o arquivo TXT é feito em latin1, é OBRIGATÓRIO a conversão para UTF-8 que é o padrão do XML
        if (preg_match("/^[\\x00-\\xFF]*$/u", $string) === 1){    // charset do latin1
            $string=utf8_encode($string);
        }elseif(preg_match("%(?:".
                "[\xC2-\xDF][\x80-\xBF]".        # non-overlong 2-byte
                "|\xE0[\xA0-\xBF][\x80-\xBF]".               # excluding overlongs
                "|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}".      # straight 3-byte
                "|\xED[\x80-\x9F][\x80-\xBF]".               # excluding surrogates
                "|\xF0[\x90-\xBF][\x80-\xBF]{2}".    # planes 1-3
                "|[\xF1-\xF3][\x80-\xBF]{3}".                  # planes 4-15
                "|\xF4[\x80-\x8F][\x80-\xBF]{2}".    # plane 16
                ")+%xs", $string)!==1){
            $RETURN['avisos'][]="Atenção arquivo não está na codificação LATIN1, nem UTF-8.";
        }
        $string        =explode("\n",str_replace("\r",'',$string));    // remove \r dos \r\n, ou \n\r e explode arquivo 
        $tot_notas    =-1;
        $qnt_tag_notas    =0;
        $cur_nota    =-1;
        $cur_nota_tags    =array();
        $cur_versao    ='';
        $cur_linha    =0;
        $last_tag    ='';
        foreach($string as $v){
            $cur_linha++;
            if($v==='') continue;
            
            
            
            
            $v=explode("|",$v);    // divide a linha pelos campos, neste caso não existe um arquivo CSV conforme o padrão, e sim o padrão da receita, ou seja não existe encapsulamento, quebra de linha e coisas do genero
            $TAG=$v[0];
            $MSG_PADRAO="[Linha $cur_linha, Nota $cur_nota, TAG $TAG]";
            
            if(isset($cur_nota_tags[$TAG]))    $cur_nota_tags[$TAG]++;
            else                $cur_nota_tags[$TAG]=1;
            if($TAG==='NOTAFISCAL' || $TAG==='NOTA FISCAL'){
                // NOTA FISCAL|qtd notas fiscais no arquivo| 
                $campos=explode("|","NOTA FISCAL|qtd notas fiscais no arquivo|");
                $MSG_PADRAO="[Linha $cur_linha, TAG $TAG]";
                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
                if(count($v)!=count($campos))
                    $RETURN['avisos'][]="$MSG_PADRAO Quantidade de campos na tag (".count($v).") é diferente de ".count($campos);
                if(!isset($v[1])){
                    $RETURN['avisos'][]="$MSG_PADRAO campo 'qtd notas fiscais no arquivo' não informado, considerando como 0";
                    $v[1]=0;
                }elseif((double)$v[1]<=0){
                    $RETURN['avisos'][]="$MSG_PADRAO campo 'qtd notas fiscais no arquivo' menor igual a 0";
                    $v[1]=0;
                }
                if($tot_notas<0){
                    $tot_notas=(double)$v[1];
                }elseif($qnt_tag_notas>1){
                    $RETURN['erros'][]="$MSG_PADRAO TAG encontrada mais de uma vez no arquivo";
                }
                $qnt_tag_notas++;
                $last_tag=$TAG;
                continue;
            }elseif($TAG==='A'){
                //A|versão do schema|id| 
                $campos=explode('|',$this->campos_v200[$TAG]);    // $this->campos_v200[$TAG] é oq tem mais campos
                // cria nova nota
                $cur_nota++;
                $cur_nota_tags    =array();
                $cur_item    =-1;
                $MSG_PADRAO="[Linha $cur_linha, Nota $cur_nota, TAG $TAG]";
                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
                if(count($v)!=count($campos))
                    $RETURN['avisos'][]="$MSG_PADRAO Quantidade de campos na tag é diferente de ".count($campos);
                if(!isset($v[1])){
                    $RETURN['erros'][]="$MSG_PADRAO campo 'versao' não informado, considerando como 2.00";
                }elseif($v[1]!=='2.00'){
                    $RETURN['aviso'][]="$MSG_PADRAO campo 'versao' diferente de 2.00, esta não é a ultima versão da NFe";
                    //if($v[1]!=='1.00')    // assim que fizer a conversão de versão 1.00 para 2.00, tirar o comentario desta linha e da inferior
                    //    $RETURN['erros'][]="$MSG_PADRAO campo 'versão do schema' diferente de 1.00, esta versão não é reconhecida para conversão TXT-XML";
                }
                if(!isset($v[2])){
                    $RETURN['avisos'][]="$MSG_PADRAO campo 'id' não informado, considerando como em branco";
                    $v[2]='';
                }elseif(strlen($v[2])!=47 && strlen($v[2])!=0){
                    $RETURN['avisos'][]="$MSG_PADRAO campo 'id', quantidade de caracteres (".strlen($v[2]).") não é igual a 47, a chave da NFe deverá ser calculada";
                }
                $cur_versao=$v[1];
                $RETURN['docs'][$cur_nota]=array();
                $RETURN['docs'][$cur_nota][]=array(
                            'TAG'        =>'A',
                            'versao'    =>$v[1],
                            'Id'        =>$v[2]);
                $last_tag=$TAG;
                continue;
            }elseif($cur_versao==='2.00'){
                ///////////////// VERSÃO 2.00
                if(!isset($this->campos_v200[$TAG])){
                    $RETURN['erros'][]="$MSG_PADRAO Tag não existe no layout TXT";
                    continue;
                }
                $campos=explode("|",$this->campos_v200[$TAG]);
                
                // TAG H - ITENS
                if($TAG=='H'){
                    $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
                    // CRIA NOVO ITEM, ZERA O CONTADOR DE TAGS PARA VERIFICAR QUANTAS TAGS JA FORAM CRIADAS...
                    foreach($campos_v200_tag_itens as $k)
                        unset($cur_nota_tags[$k]);
                    if($cur_nota_tags[$TAG]>990){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de 990 vezes, ignorando linha.";
                        continue;
                    }
                }elseif(in_array($TAG,$campos_v200_tag_itens)){
                    // TAGS DE ITENS
                    if(!isset($cur_nota_tags['H'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'H', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'H', $this->campos_v200,$RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='H';
                        foreach($campos_v200_tag_itens as $k)
                            unset($cur_nota_tags[$k]);
                    }
                    
                    // TAGS 'I'
                    if($TAG=='I18' || $TAG=='I25'){
                        if(!isset($cur_nota_tags['I'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'I', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'I', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='I';
                        }
                    }
                    if($TAG=='I25'){
                        if(!isset($cur_nota_tags['I18'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'I18', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'I18', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='I18';
                        }
                    }
                    // TAGS 'J' - VEICULO SÓ PODE 1 POR ITEM
                    if($TAG=='J'){
                        if($cur_nota_tags[$TAG]!=1){
                            $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #01";
                            continue;
                        }    
                    }
                    // TAGS 'L'
                    if($TAG=='L105'){
                        if(!isset($cur_nota_tags['L01'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'L01', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'L01', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='L01';
                        }
                        if($cur_nota_tags[$TAG]!=1){
                            $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #02";
                            continue;
                        }
                    }
                    // TAGS 'M'/'N'
                    if(in_array(substr($TAG,0,1),array('N','O','P','Q','R','S','T'))){
                        if(!isset($cur_nota_tags['M'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'M', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'M', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='M';
                        }
                        if($cur_nota_tags[$TAG]!=1){
                            $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #03";
                            continue;
                        }
                    }
                    //TAGS N
                    if(substr($TAG,0,1)=='N' && $TAG!='N'){
                        if(!isset($cur_nota_tags['N'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'N', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'N', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='N';
                        }
                        foreach($campos_v200_tag_itens as $k)
                            if(substr($k,0,1)=='N' && $TAG!=$k && $k!='N'){    // tags Nxxx
                                if(isset($cur_nota_tags[$k])){
                                    $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada mais de uma vez, ignorando linha. #10";
                                    continue 2;    // PRECISA SER 'continue 2' PARA IGNORAR O FOR DOS TAGS, E PULAR PARA PROXIMA LINHA
                                }
                            }
                    }
                    // TAGS O
                    if(substr($TAG,0,1)=='O' && $TAG!='O'){
                        if(!isset($cur_nota_tags['O'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'O', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'O', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='O';
                        }
                        if(    ($TAG=='O07' && isset($cur_nota_tags['O08'])) || 
                            ($TAG=='O08' && isset($cur_nota_tags['O07']))){
                            $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #11";
                            continue;
                        }
                        if($TAG=='O10' || $TAG=='O11'){
                            if( isset($cur_nota_tags['O08']) ){
                                $RETURN['erros'][]="$MSG_PADRAO TAG O08 informada, ignorando linha.";
                                continue;
                            }
                            if( !isset($cur_nota_tags['O07']) ){
                                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'O07', $RETURN);
                                $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'O07', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                                $last_tag='O07';
                            }
                        }
                    }
                    // tags Q
                    if(substr($TAG,0,1)=='Q' && $TAG!='Q'){
                        if(!isset($cur_nota_tags['Q'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'Q', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'Q', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='Q';
                        }
                        if($TAG=='Q07' || $TAG=='Q10'){
                            $tmp_tags=array('Q02','Q03','Q04');
                            foreach($tmp_tags as $k)
                                if( isset($cur_nota_tags[$k]) ){
                                    $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #12";
                                    continue 2;    // PRECISA SER 'continue 2' PARA IGNORAR O FOR DOS TAGS, E PULAR PARA PROXIMA LINHA
                                }
                            if( !isset($cur_nota_tags['Q05']) ){
                                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'Q05', $RETURN);
                                $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'Q05', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                                $last_tag='Q05';
                            }
                            if(    ($TAG=='Q07' && isset($cur_nota_tags['Q10'])) || 
                                ($TAG=='Q10' && isset($cur_nota_tags['Q07']))){
                                $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #13";
                                continue;
                            }
                        }else{
                            $tmp_tags=array('Q02','Q03','Q04','Q05');
                            foreach($tmp_tags as $k)
                                if( isset($cur_nota_tags[$k]) && $k!=$TAG ){
                                    $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #14";
                                    continue 2;    // PRECISA SER 'continue 2' PARA IGNORAR O FOR DOS TAGS, E PULAR PARA PROXIMA LINHA
                                }
                        }
                    }
                    // tags R
                    if(substr($TAG,0,1)=='R' && $TAG!='R'){
                        if(!isset($cur_nota_tags['R'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'R', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'R', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='R';
                        }
                        if(    ($TAG=='R02' && isset($cur_nota_tags['R04'])) || 
                            ($TAG=='R04' && isset($cur_nota_tags['R02']))){
                            $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #15";
                            continue;
                        }
                    }
                    // tags S
                    if(substr($TAG,0,1)=='S' && $TAG!='S'){
                        if(!isset($cur_nota_tags['S'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'S', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'S', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='S';
                        }
                        if($TAG=='S07' || $TAG=='S09'){
                            $tmp_tags=array('S02','S03','S04');
                            foreach($tmp_tags as $k)
                                if( isset($cur_nota_tags[$k]) ){
                                    $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #16";
                                    continue 2;    // PRECISA SER 'continue 2' PARA IGNORAR O FOR DOS TAGS, E PULAR PARA PROXIMA LINHA
                                }
                            if( !isset($cur_nota_tags['S05']) ){
                                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'S05', $RETURN);
                                $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'S05', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                                $last_tag='S05';
                            }
                            if(    ($TAG=='S07' && isset($cur_nota_tags['S09'])) || 
                                ($TAG=='S09' && isset($cur_nota_tags['S07']))){
                                $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #17";
                                continue;
                            }
                        }else{
                            $tmp_tags=array('S02','S03','S04','S05');
                            foreach($tmp_tags as $k)
                                if( isset($cur_nota_tags[$k]) && $k!=$TAG ){
                                    $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #18";
                                    continue 2;    // PRECISA SER 'continue 2' PARA IGNORAR O FOR DOS TAGS, E PULAR PARA PROXIMA LINHA
                                }
                        }
                    }
                    // TAGS 'T'
                    if(substr($TAG,0,1)=='T' && $TAG!='T'){
                        if(!isset($cur_nota_tags['T'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'T', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'T', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='T';
                        }
                        if(    ($TAG=='T02' && isset($cur_nota_tags['T04'])) || 
                            ($TAG=='T04' && isset($cur_nota_tags['T02']))){
                            $RETURN['erros'][]="$MSG_PADRAO TAG opcional informada, ignorando linha. #19";
                            continue;
                        }
                    }
                // TAGS DE 'PESSOAS' - EMITENTE, DESTINATARIO, TRANSPORTE, LOCAL ENTREGA/RETIRADA
                }elseif(($TAG==='C02' || $TAG==='C02a') || 
                    ($TAG==='F02' || $TAG==='F02a') || 
                    ($TAG==='G02' || $TAG==='G02a')){
                    // tags de escolha de CNPJ / CPF com final 02 ou 02a
                    if(!isset($cur_nota_tags[ substr($TAG,0,1) ])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, substr($TAG,0,1), $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,substr($TAG,0,1), $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag=substr($TAG,0,1);
                    }
                    if($cur_nota_tags[$TAG]!=1){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #04";
                        continue;
                    }
                    if(    ($TAG===substr($TAG,0,1).'02' && isset($cur_nota_tags[substr($TAG,0,1).'02a'])) || 
                        ($TAG===substr($TAG,0,1).'02a' && isset($cur_nota_tags[substr($TAG,0,1).'02']))    ){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada incorretamente, tags opcionais já informada'.";
                        continue;
                    }
                }elseif($TAG==='E02' || $TAG==='E03'){
                    // tags de escolha de CNPJ/CPF com final 02 ou 03
                    if(!isset($cur_nota_tags['E'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'E', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'E', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='E';
                    }
                    if($cur_nota_tags[$TAG]!=1){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #05";
                        continue;
                    }
                    if(    ($TAG==='E02' && isset($cur_nota_tags['E03'])) || 
                        ($TAG==='E03' && isset($cur_nota_tags['E02']))    ){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada incorretamente, tags opcionais já informada.";
                        continue;
                    }
                // TAGS W
                }elseif(substr($TAG,0,1)=='W'){
                    if(!isset($cur_nota_tags['W'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'W', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'W', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='W';
                    }
                    if($cur_nota_tags[$TAG]!=1){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #06";
                        continue;
                    }
                // TAGS X
                }elseif(substr($TAG,0,1)=='X'){
                    if(!isset($cur_nota_tags['X'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'X', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'X', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='X';
                    }
                    if($TAG=='X22'){
                        if($cur_nota_tags[$TAG]!=1){
                            $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de 2 vezes, ignorando linha.";
                            continue;
                        }
                    }elseif($cur_nota_tags[$TAG]!=1){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #07";
                        continue;
                    }
                    if($TAG=='X04' || $TAG=='X05'){
                        if(!isset($cur_nota_tags['X03'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'X03', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'X03', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='X03';
                        }
                        if(    ($TAG==='X04' && isset($cur_nota_tags['X05'])) || 
                            ($TAG==='X05' && isset($cur_nota_tags['X04']))    ){
                            $RETURN['erros'][]="$MSG_PADRAO TAG informada incorretamente, tags opcionais já informadas.";
                            continue;
                        }
                    }
                    if($TAG=='X33'){
                        if(!isset($cur_nota_tags['X26'])){
                            $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'X26', $RETURN);
                            $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'X26', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                            $last_tag='X26';
                        }
                    }
                // TAGS Y
                }elseif(substr($TAG,0,1)=='Y'){
                    if(!isset($cur_nota_tags['Y'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'Y', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'Y', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='Y';
                    }
                    if($cur_nota_tags[$TAG]!=1 && $TAG=='Y02'){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #08";
                        continue;
                    }
                // TAGS Z
                }elseif(substr($TAG,0,1)=='Z'){
                    if(!isset($cur_nota_tags['Z'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'Z', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'Z', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='Z';
                    }
                    if($cur_nota_tags[$TAG]>10 && ($TAG=='Z04' || $TAG=='Z07')){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais 10 vezes, ignorando linha.";
                        continue;
                    }
                // TAGS ZC
                }elseif(substr($TAG,0,1)=='ZC'){
                    if(!isset($cur_nota_tags['ZC01'])){
                        $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'ZC01', $RETURN);
                        $this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'ZC01', $this->campos_v200, $RETURN, $cur_nota_tags, $cur_nota);
                        $last_tag='ZC01';
                    }
                    if($cur_nota_tags[$TAG]>10 && $TAG=='ZC10'){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais 10 vezes, ignorando linha.";
                        continue;
                    }
                    if($cur_nota_tags[$TAG]>31 && $TAG=='ZC04'){
                        $RETURN['erros'][]="$MSG_PADRAO TAG informada mais 10 vezes, ignorando linha.";
                        continue;
                    }
                // OUTRAS TAGS QUE SÓ PRECISAM VERIFICAR SE NÃO ESTÃO REPETIDAS
                }elseif($cur_nota_tags[$TAG]!=1){
                    $RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #09";
                    continue;
                }
                $this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
                $RETURN['docs'][$cur_nota][]=$this->_TXT2XML_processa_txt_tag($MSG_PADRAO,$v,$campos,$RETURN);
                $last_tag=$TAG;
                continue;
            }
            $RETURN['erros'][]="$MSG_PADRAO Não foi possivel interpretar linha, versão atual do schema: $cur_versao";
        }
        if($qnt_tag_notas!=1)
            $RETURN['erros'][]="TAG NOTAFISCAL não encontrada no arquivo, atenção este arquivo pode não ser um arquivo TXT de NFe";
#var_dump($RETURN);
        return($RETURN);
    }
    private function _TXT2XML_processa_array($array_notas,$output_string=true){
        // processa array (vindo da função _TXT2XML_processa_txt) e gera varios arquivos XML ($output_string=true) ou varios objetos Dom ($output_string=false)
        if(!is_array($array_notas))
            return(false);
        if(!is_array($array_notas[0]))
            return(false);
        if(!isset($array_notas[0][0]) || !is_array($array_notas[0][0]))
            $array_notas=array($array_notas);
        $RETURN=array(    'erros'    =>array(),
                'avisos'=>array(),
                'xml'    =>array());
        // caso não seja versão 2.00 usar função "_TXT2XML_processa_txt_converte_versao($array)" ou fazer um if ($cur_version pra cada campo...)
        foreach($array_notas as $knota=>$v){
#echo "$k => $v\n";
#print_r($v);
            $dom=new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;
            $cur_version='';
            unset(    $NFe, $infNFe, $ZC01_cana, $Z_infAdic, $X26_vol, $X_transp, $W_total, $G_entrega, 
                $F_retirada, $E_dest, $C_emit, $B_refNFP, $B_refNFP_CPF_CNPJ, $B_NFref, $B20a_IE,
                $ide, $H_det, $Y_cobr,
                $C_xNome, $C_IE, $G_xLgr, $X03_transporta,
                
                $M_imposto, $I_prod, $I18_DI, $H_infAdProd, 
                $T_COFINSST, $S05_COFINSOutr, $S_COFINS, $R_PISST, $Q05_PISOutr, $Q_PIS, $P_II, $O_IPI,
                $O07_IPITrib, $N_ICMS, $L01_comb, $I18_DI, $I_xPed);
            foreach($v as $v2){
                if($v2['TAG']=='A'){
                    // CRIA NOTA
                    $cur_version=$v2['versao'];
                    $NFe    =$dom->createElement("NFe");
                    $NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
                    $infNFe    =$dom->createElement("infNFe");
                    $infNFe->setAttribute("Id", $v2['Id']);
                    $infNFe->setAttribute("versao", $v2['versao']);
                }elseif($cur_version=='2.00'){
                    // versão 2.00
                    $MSG_PADRAO="[TAG ".$v2['TAG'].", Nota $knota]";
                    if(!isset($this->campos_v200[$v2['TAG']])){ //tag não existe
                        $RETURN['avisos'][$knota][]="$MSG_PADRAO TAG ".$v2['TAG']." não encontrada nos campos da versão 2.00";
                        continue;
                    }
                    $campos=explode('|',$this->campos_v200[$v2['TAG']]);
                    unset($campos[0]);    // campo da tag
                    foreach($campos as $k=>$nome_campo){
                        if(strlen(trim($nome_campo))==0){
                            unset($campos[$k]);
                            continue;
                        }
                        if(!isset($v2[$nome_campo])){
                            $RETURN['avisos'][$knota][]="$MSG_PADRAO Campo $nome_campo não encontrada no array de importação, considerando como em branco";
                            $v2[$nome_campo]='';    // cria campo em branco - não deve ocorrer!
                        }else{
                            $last_len=strlen($v2[$nome_campo]);
                            $v2[$nome_campo]=trim($v2[$nome_campo]);
                            if($last_len!=strlen($v2[$nome_campo])){
                                $RETURN['avisos'][$knota][]="$MSG_PADRAO Alterado o tamanho do campo $nome_campo após TRIM de $last_len para ".strlen($v2[$nome_campo]);
                            }
                        }
                    }
                    // nova nota
                    if($v2['TAG']=='B' && !isset($ide)){
                        $ide = $dom->createElement("ide");
                        if($v2['mod']!=55)
                            $RETURN['erros'][$knota][]="$MSG_PADRAO campo 'mod' não é igual a 55";
                        foreach($campos as $nome_campo){
                            if($nome_campo=='dSaiEnt' || $nome_campo=='hSaiEnt'){
                                if(empty($v2['dSaiEnt']))
                                    continue;
                                if(empty($v2['hSaiEnt']))
                                    continue;
                            }elseif($nome_campo=='xJust' || $nome_campo=='dhCont'){
                                if(empty($v2['xJust']) || empty($v2['dhCont']))
                                    continue;
                            }elseif($nome_campo=='tpImp'){
                                $B_tpImp=$dom->createElement($nome_campo,$v2[$nome_campo]);
                                $ide->appendChild( $B_tpImp );
                                continue;
                            }elseif($nome_campo=='tpAmb'){
                                $B_tpAmb=$dom->createElement($nome_campo,$v2[$nome_campo]);
                                $ide->appendChild( $B_tpAmb );
                                continue;
                            }elseif($nome_campo=='VerProc'){
                                if(empty($v2[$nome_campo]))
                                    $v2[$nome_campo]="NfePHP";
                            }
                            $ide->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $infNFe->appendChild($ide);
                    }elseif(!isset($ide)){
                        $RETURN['avisos'][$knota][]="$MSG_PADRAO TAG informada sem ter uma tag 'B'/'ide' criada ";
                        continue;
                    // notas referenciadas
                    }elseif(in_array($v2['TAG'],array('B13','B14','B20a','B20d','B20e','B20i','B20j'))){
                        if(!isset($B_NFref)){
                            $B_NFref=$dom->createElement("NFref");
                            $ide->insertBefore($ide->appendChild($B_NFref),$B_tpImp);
                        }
                        if($v2['TAG']=='B20a'){
                            $B_refNFP = $dom->createElement("refNFP");
                            $B_refNFP->appendChild( $dom->createElement("cUF",    $v2['cUF']) );
                            $B_refNFP->appendChild( $dom->createElement("AAMM",    $v2['AAMM']) );
                            // CPF OU CNPJ VEM AKI
                            $B20a_IE = $dom->createElement("IE",    $v2['IE']);
                            $B_refNFP->appendChild( $B20a_IE );
                            $B_refNFP->appendChild( $dom->createElement("mod",    $v2['mod']) );
                            $B_refNFP->appendChild( $dom->createElement("serie",    $v2['serie']) );
                            $B_refNFP->appendChild( $dom->createElement("nNF",    $v2['nNF']) );
                            $B_NFref->appendChild($B_refNFP);
                        }elseif($v2['TAG']=='B20d' && isset($B_refNFP) && !isset($B_refNFP_CPF_CNPJ)){
                            $B_refNFP->insertBefore( $dom->createElement("CNPJ",     $v2['CNPJ']) , $B20a_IE);
                            $B_refNFP_CPF_CNPJ=true;
                        }elseif($v2['TAG']=='B20e' && isset($B_refNFP) && !isset($B_refNFP_CPF_CNPJ)){
                            $B_refNFP->insertBefore( $B_refNFP->appendChild( $dom->createElement("CPF",     $v2['CPF']) ) , $B20a_IE);
                            $B_refNFP_CPF_CNPJ=true;
                        }elseif($v2['TAG']=='B13' || $v2['TAG']=='B20i'){
                            // tags com 1 unico elemento (NFe, CTe)
                            foreach($campos as $nome_campo)
                                $B_NFref->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }else{    
                            // tags com varios elementos
                            if($v2['TAG']=='B14'){
                                $tmp_grupo = $dom->createElement("refNF");
                            }elseif($v2['TAG']=='B20j'){
                                $tmp_grupo = $dom->createElement("refECF");
                            }else{
                                $RETURN['avisos'][$knota][]="$MSG_PADRAO Tag de referencia de nota fiscal não implementada para importação, ou ja processada";
                                continue;
                            }
                            foreach($campos as $nome_campo){
                                $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                            }
                            $B_NFref->appendChild( $tmp_grupo );
                        }
                    // emitente
                    }elseif($v2['TAG']=='C' && !isset($C_emit)){
                        $C_emit = $dom->createElement("emit");
                        $C_xNome = $dom->createElement("xNome",        $v2['xNome']) ;
                        $C_emit->appendChild( $C_xNome );
                        if(!empty($v2['xFant']))
                            $C_emit->appendChild( $dom->createElement("xFant",    $v2['xFant']) );
                        $C_IE    = $dom->createElement("IE",        $v2['IE']);
                        $C_emit->appendChild( $C_IE );
                        if(!empty($v2['IEST']))
                            $C_emit->appendChild( $dom->createElement("IEST",    $v2['IEST']) );
                        if(!empty($v2['IM']))
                            $C_emit->appendChild( $dom->createElement("IM",        $v2['IM']) );
                        if(!empty($v2['CNAE']))
                            $C_emit->appendChild( $dom->createElement("CNAE",    $v2['CNAE']) );
                        $C_emit->appendChild( $dom->createElement("CRT",        $v2['CRT']) );    // campo obrigatório, vide manual de integração (ocorrência 1-1)
                        $infNFe->appendChild($C_emit);
                    }elseif($v2['TAG']=='C02' && isset($C_xNome)){
                        $C_emit->insertBefore( $dom->createElement("CNPJ",        $v2['CNPJ']),    $C_xNome );
                    }elseif($v2['TAG']=='C02a' && isset($C_xNome)){
                        $C_emit->insertBefore( $dom->createElement("CPF",        $v2['CPF']),    $C_xNome );
                    // emitente endereço
                    }elseif($v2['TAG']=='C05' && isset($C_emit)){
                        $tmp_grupo = $dom->createElement("enderEmit");
                        $tmp_grupo->appendChild( $dom->createElement("xLgr",         $v2['xLgr']) );
                        $v2['nro']=abs((int)$v2['nro']);
                        $tmp_grupo->appendChild( $dom->createElement("nro",         $v2['nro']) );
                        if(!empty($v2['xCpl']))
                            $tmp_grupo->appendChild( $dom->createElement("xCpl",     $v2['xCpl']) );
                        $tmp_grupo->appendChild( $dom->createElement("xBairro",     $v2['xBairro']) );
                        $tmp_grupo->appendChild( $dom->createElement("cMun",        $v2['cMun']) );
                        $tmp_grupo->appendChild( $dom->createElement("xMun",         $v2['xMun']) );
                        $tmp_grupo->appendChild( $dom->createElement("UF",         $v2['UF']) );
                        if(!empty($v2['CEP']))
                            $tmp_grupo->appendChild( $dom->createElement("CEP",    $v2['CEP']) );
                        if(!empty($v2['cPais']))
                            $tmp_grupo->appendChild( $dom->createElement("cPais",    $v2['cPais']) );
                        if(!empty($v2['xPais']))
                            $tmp_grupo->appendChild( $dom->createElement("xPais",    $v2['xPais']) );
                        if(!empty($v2['fone']))
                            $tmp_grupo->appendChild( $dom->createElement("fone",    $v2['fone']) );
                        $C_emit->insertBefore( $C_emit->appendChild($tmp_grupo) ,$C_IE);
                    // AVULSA
                    }elseif($v2['TAG']=='D'){
                        $tmp_grupo = $dom->createElement("avulsa");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='dPag' && empty($v2['dPag']))
                                continue;
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $infNFe->appendChild($tmp_grupo);
                    // destinatario
                    }elseif($v2['TAG']=='E'){
                        $E_dest = $dom->createElement("dest");
                        if ($B_tpAmb === '2'){
                            $v2['xNome']= 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL';
                            $v2['IE'] = '';
                            $RETURN['avisos'][$knota][]="$MSG_PADRAO Campo 'xNome', e 'IE' alterados para ambiente de homologação";
                        }
                        $E_xNome=$dom->createElement("xNome",         $v2['xNome']);
                        $E_dest->appendChild( $E_xNome );
                        $E_IE=$dom->createElement("IE",     $v2['IE']);
                        $E_dest->appendChild( $E_IE );        // campo obrigatório, caso esteja vazio deverá ser <IE/> ou <IE></IE>
                        if(!empty($v2['ISUF']))
                            $E_dest->appendChild( $dom->createElement("ISUF",     $v2['ISUF']) );
                        if(!empty($v2['email']))
                            $E_dest->appendChild( $dom->createElement("email",     $v2['email'] ));
                        $infNFe->appendChild($E_dest);
                    }elseif($v2['TAG']=='E02' && isset($E_dest)){
                        if ($B_tpAmb === '2')    $v2['CNPJ']='99999999000191';
                        $E_dest->insertBefore($dom->createElement("CNPJ",        $v2['CNPJ']),$E_xNome);
                    }elseif($v2['TAG']=='E03' && isset($E_dest)){
                        if ($B_tpAmb === '2')    continue;    // usar CNPJ...
                        $E_dest->insertBefore($dom->createElement("CPF",            $v2['CPF']),$E_xNome);
                    // destinatario endereço
                    }elseif($v2['TAG']=='E05'){
                        $tmp_grupo = $dom->createElement("enderDest");
                        $tmp_grupo->appendChild( $dom->createElement("xLgr",         $v2['xLgr']) );
                        $tmp_grupo->appendChild( $dom->createElement("nro",         $v2['nro']) );
                        if(!empty($v2['xCpl']))
                            $tmp_grupo->appendChild( $dom->createElement("xCpl",    $v2['xCpl']) );
                        $tmp_grupo->appendChild( $dom->createElement("xBairro",     $v2['xBairro']) );
                        $tmp_grupo->appendChild( $dom->createElement("cMun",         $v2['cMun']) );
                        $tmp_grupo->appendChild( $dom->createElement("xMun",        $v2['xMun']) );
                        $tmp_grupo->appendChild( $dom->createElement("UF",         $v2['UF']) );
                        if(!empty($v2['CEP']))
                            $tmp_grupo->appendChild($dom->createElement("CEP",     $v2['CEP']) );
                        if(!empty($v2['cPais']))
                            $tmp_grupo->appendChild($dom->createElement("cPais",     $v2['cPais']) );
                        if(!empty($v2['xPais']))
                            $tmp_grupo->appendChild($dom->createElement("xPais",     $v2['xPais']) );
                        if(!empty($v2['fone']))
                            $tmp_grupo->appendChild($dom->createElement("fone",     $v2['fone']) );
                        $E_dest->insertBefore($E_dest->appendChild($tmp_grupo), $E_IE);
                    // retirada
                    }elseif($v2['TAG']=='F'){
                        $F_retirada = $dom->createElement("retirada");
                        $F_xLgr=$F_retirada->appendChild( $dom->createElement("xLgr",     $v2['xLgr']) );
                        $F_retirada->appendChild( $dom->createElement("nro",     $v2['nro']) );
                        if(!empty($v2['xCpl']))
                            $F_retirada->appendChild( $dom->createElement("xCpl",     $v2['xCpl']) );
                        $F_retirada->appendChild( $dom->createElement("xBairro",     $v2['xBairro']) );
                        $F_retirada->appendChild( $dom->createElement("cMun",     $v2['cMun']) );
                        $F_retirada->appendChild( $dom->createElement("xMun",     $v2['xMun']) );
                        $F_retirada->appendChild( $dom->createElement("UF",     $v2['UF']) );
                        $infNFe->appendChild($F_retirada);
                    }elseif($v2['TAG']=='F02'  && isset($F_retirada) && !empty($v2['CNPJ'])){
                        $F_retirada->insertBefore($F_retirada->appendChild( $dom->createElement("CNPJ", $v2['CNPJ']) ),$F_xLgr);
                    }elseif($v2['TAG']=='F02a' && isset($F_retirada) && !empty($v2['CPF'] )){
                        $F_retirada->insertBefore($F_retirada->appendChild( $dom->createElement("CPF", $v2['CPF']) ),$F_xLgr);
                    // entrega
                    }elseif($v2['TAG']=='G'){
                        $G_entrega = $dom->createElement("entrega");
                        $G_xLgr = $dom->createElement("xLgr", $v2['xLgr']);
                        $G_entrega->appendChild($G_xLgr);
                        $G_entrega->appendChild($dom->createElement("nro",     $v2['nro']));
                        if(!empty($v2['xCpl']))
                            $G_entrega->appendChild( $dom->createElement("xCpl", $v2['xCpl']) );
                        $G_entrega->appendChild( $dom->createElement("xBairro",     $v2['xBairro']) );
                        $G_entrega->appendChild( $dom->createElement("cMun",     $v2['cMun']) );
                        $G_entrega->appendChild( $dom->createElement("xMun",     $v2['xMun']) );
                        $G_entrega->appendChild( $dom->createElement("UF",     $v2['UF']) );
                        $infNFe->appendChild($G_entrega);
                    }elseif($v2['TAG']=='G02' && isset($G_entrega) && !empty($v2['CNPJ'])){
                        $G_entrega->insertBefore($G_entrega->appendChild( $dom->createElement("CNPJ", $v2['CNPJ']) ),$G_xLgr);
                    }elseif($v2['TAG']=='G02a' && isset($G_entrega) && !empty($v2['CPF'])){
                        $G_entrega->insertBefore($G_entrega->appendChild( $dom->createElement("CPF", $v2['CPF']) ),$G_xLgr);
                    // ITENS
                    }elseif($v2['TAG']=='H'){
                        // limpa variaveis pra não adiciona informação no item errado (o item anterior por exemplo)
                        unset(    $M_imposto, $I_prod, $I18_DI, $H_infAdProd, 
                            $T_COFINSST, $S05_COFINSOutr, $S_COFINS, $R_PISST, $Q05_PISOutr, $Q_PIS, $P_II, $O_IPI,
                            $O07_IPITrib, $N_ICMS, $L01_comb, $I18_DI, $I_xPed);
                        
                        $H_det = $dom->createElement("det");
                        $H_det->setAttribute("nItem", $v2['nItem']);
                        unset($H_infAdProd);
                        if(!empty($v2['infAdProd'])){
                            $H_infAdProd = $dom->createElement("infAdProd", $v2['infAdProd']);
                            $H_det->appendChild( $H_infAdProd  );
                        }
                        $infNFe->appendChild($H_det);
                    }elseif($v2['TAG']=='I'){
                        $I_prod = $dom->createElement("prod");
                        $I_prod->appendChild( $dom->createElement("cProd",        $v2['cProd']) );
                        $I_prod->appendChild( $dom->createElement("cEAN",            $v2['cEAN']) );
                        $I_prod->appendChild( $dom->createElement("xProd",        $v2['xProd']) );
                        $I_prod->appendChild( $dom->createElement("NCM",            $v2['NCM']) );
                        if(!empty($v2['EXTIPI']))
                            $I_prod->appendChild( $dom->createElement("EXTIPI",    $v2['EXTIPI']) );
                        $I_prod->appendChild( $dom->createElement("CFOP",         $v2['CFOP']) );
                        $I_prod->appendChild( $dom->createElement("uCom",         $v2['uCom']) );
                        $I_prod->appendChild( $dom->createElement("qCom",         $v2['qCom']) );
                        $I_prod->appendChild( $dom->createElement("vUnCom",         $v2['vUnCom']) );
                        $I_prod->appendChild( $dom->createElement("vProd",         $v2['vProd']) );
                        $I_prod->appendChild( $dom->createElement("cEANTrib",        $v2['cEANTrib']) );
                        if(empty($v2['uTrib']) || empty($v2['qTrib']) || empty($v2['vUnTrib'])){
                            $v2['uTrib']=$v2['uCom'];
                            $v2['qTrib']=$v2['qCom'];
                            $v2['vUnTrib']=$v2['vUnCom'];
                        }
                        $I_prod->appendChild( $dom->createElement("uTrib",         $v2['uTrib']) );
                        $I_prod->appendChild( $dom->createElement("qTrib",         $v2['qTrib']) );
                        $I_prod->appendChild( $dom->createElement("vUnTrib",         $v2['vUnTrib']) );
                        if(!empty($v2['vFrete']))
                            $I_prod->appendChild( $dom->createElement("vFrete",     $v2['vFrete']) );
                        if(!empty($v2['vSeg']))
                            $I_prod->appendChild( $dom->createElement("vSeg",     $v2['vSeg']) );
                        if(!empty($v2['vDesc']))
                            $I_prod->appendChild( $dom->createElement("vDesc",     $v2['vDesc']) );
                        if(!empty($v2['vOutro']))
                            $I_prod->appendChild( $dom->createElement("vOutro",     $v2['vOutro']) );
                        if(!empty($v2['indTot']) || $v2['indTot']==0)
                            $v2['indTot']=0;
                        $I_prod->appendChild( $dom->createElement("indTot",         $v2['indTot']) );
                        if(!empty($v2['xPed'])){
                            $I_xPed=$dom->createElement("xPed",         $v2['xPed']);
                            $I_prod->appendChild( $I_xPed );
                        }
                        if(!empty($v2['nItemPed']))
                            $I_prod->appendChild( $dom->createElement("nItemPed",     $v2['nItemPed']) );
                        if (!isset($H_infAdProd)){
                            $H_det->appendChild($I_prod);
                        } else {
                            $H_det->insertBefore($H_det->appendChild($I_prod),$H_infAdProd);
                        }
                    // DI
                    }elseif($v2['TAG']=='I18'){
                        $I18_DI = $dom->createElement("DI");
                        foreach($campos as $nome_campo)
                            $I18_DI->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        if (!isset($I_xPed)){
                            $I_prod->appendChild($I18_DI);
                        }else{
                            $I_prod->insertBefore($I_prod->appendChild($I18_DI),$I_xPed);
                        }
                    }elseif($v2['TAG']=='I25' && isset($I18_DI)){
                        $tmp_grupo = $dom->createElement("adi");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='vDescDI' && empty($v2['vDescDI']))    continue;
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $I18_DI->appendChild($tmp_grupo);
                    // VEICULO
                    }elseif($v2['TAG']=='J' && isset($I_prod)){
                        $tmp_grupo = $dom->createElement("veicProd");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $I_prod->appendChild($tmp_grupo);
                    // REMEDIO
                    }elseif($v2['TAG']=='K' && isset($I_prod)){
                        $tmp_grupo = $dom->createElement("med");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $I_prod->appendChild($tmp_grupo);
                    // ARMA
                    }elseif($v2['TAG']=='L' && isset($I_prod)){
                        $tmp_grupo = $dom->createElement("arma");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $I_prod->appendChild($tmp_grupo);
                    // COMBUSTIVEL
                    }elseif($v2['TAG']=='L01' && isset($I_prod)){
                        $L01_comb = $dom->createElement("comb");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='CODIF' && empty($v2['CODIF']))    continue;
                            if($nome_campo=='qTemp' && empty($v2['qTemp']))    continue;
                            $L01_comb->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $I_prod->appendChild($L01_comb);
                    }elseif($v2['TAG']=='L105' && isset($L01_comb)){
                        $tmp_grupo = $dom->createElement("CIDE");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $L01_comb->appendChild($tmp_grupo);
                    // IMPOSTOS >:/
                    }elseif($v2['TAG']=='M' && isset($I_prod) && !isset($M_imposto)){
                        $M_imposto = $dom->createElement("imposto");
                        // lei da transparencia
                        $vTotTrib=trim($v2['vTotTrib']);
                        if(strlen($vTotTrib)>0){
                            $vTotTrib = $dom->createElement("vTotTrib", $vTotTrib);
                            $M_imposto->appendChild($vTotTrib);
                        }
                        unset($vTotTrib);
                        //
                        
                        if (!isset($H_infAdProd))
                            $H_det->appendChild($M_imposto);
                        else
                            $H_det->insertBefore($H_det->appendChild($M_imposto),$H_infAdProd);
                    // ICMS
                    }elseif($v2['TAG']=='N' && isset($M_imposto) && !isset($N_ICMS)){
                        $N_ICMS = $dom->createElement("ICMS");
                        $M_imposto->appendChild($N_ICMS);
                    }elseif(in_array($v2['TAG'],array(
                            'N02','N03','N04','N05','N06','N07','N08','N09','N10',
                            'N10a','N10b','N10c','N10d','N10e','N10g','N10h')) && isset($N_ICMS)){
                        
                        if($v2['TAG']=='N02')        $tmp_icms=$dom->createElement("ICMS00");
                        elseif($v2['TAG']=='N03')    $tmp_icms=$dom->createElement("ICMS10");
                        elseif($v2['TAG']=='N04')    $tmp_icms=$dom->createElement("ICMS20");
                        elseif($v2['TAG']=='N05')    $tmp_icms=$dom->createElement("ICMS30");
                        elseif($v2['TAG']=='N06')    $tmp_icms=$dom->createElement("ICMS40");
                        elseif($v2['TAG']=='N07')    $tmp_icms=$dom->createElement("ICMS51");
                        elseif($v2['TAG']=='N08')    $tmp_icms=$dom->createElement("ICMS60");
                        elseif($v2['TAG']=='N09')    $tmp_icms=$dom->createElement("ICMS70");
                        elseif($v2['TAG']=='N10')    $tmp_icms=$dom->createElement("ICMS90");
                        elseif($v2['TAG']=='N10a')    $tmp_icms=$dom->createElement("ICMSPart");
                        elseif($v2['TAG']=='N10b')    $tmp_icms=$dom->createElement("ICMSST");
                        elseif($v2['TAG']=='N10c')    $tmp_icms=$dom->createElement("ICMSSN101");
                        elseif($v2['TAG']=='N10d')    $tmp_icms=$dom->createElement("ICMSSN102");
                        elseif($v2['TAG']=='N10e')    $tmp_icms=$dom->createElement("ICMSSN201");
                        elseif($v2['TAG']=='N10f')    $tmp_icms=$dom->createElement("ICMSSN202");
                        elseif($v2['TAG']=='N10g')    $tmp_icms=$dom->createElement("ICMSSN500");
                        elseif($v2['TAG']=='N10h')    $tmp_icms=$dom->createElement("ICMSSN900");
                        
                        
                        foreach($campos as $nome_campo){
                            // CAMPO QUE CASO SEJAM EM BRANCO, NÃO ENTRAM NO XML:
                            if(    $v2['TAG']=='N03' || $v2['TAG']=='N05' || $v2['TAG']=='N09' || 
                                $v2['TAG']=='N10e' || $v2['TAG']=='N10f'){
                                if(in_array($nome_campo,array('pMVAST','pRedBCST'))){    // sacanagem não informar...
                                    if(empty($v2[$nome_campo]))
                                        continue;
                                }
                            }elseif($v2['TAG']=='N06'){
                                if(in_array($nome_campo,array('vICMS','motDesICMS'))){
                                    if(empty($v2[$nome_campo]))
                                        continue;
                                }
                            }elseif($v2['TAG']=='N07'){
                                if(in_array($nome_campo,array('vICMS','motDesICMS','modBC','pRedBC','vBC','pICMS','vICMS'))){
                                    if(empty($v2[$nome_campo]))
                                        continue;
                                }
                            }elseif($v2['TAG']=='N10' || $v2['TAG']=='N10a'){
                                if(in_array($nome_campo,array('pRedBC','pMVAST','pRedBCST'))){
                                    if(empty($v2[$nome_campo]))
                                        continue;
                                }
                            }elseif($v2['TAG']=='N10h'){
                                if(in_array($nome_campo,array('modBC','vBC','pRedBC','pICMS','vICMS','modBCST',
                                    'pMVAST','pRedBCST','vBCST','pICMSST','vICMSST','pCredSN','vCredICMSSN'))){
                                    if(empty($v2[$nome_campo]))
                                        continue;
                                }
                            }
                            ///////////////////////////////////////
                            $tmp_icms->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $N_ICMS->appendChild($tmp_icms);
                    // IPI
                    }elseif($v2['TAG']=='O' && isset($M_imposto) && !isset($O_IPI)){
                        $O_IPI = $dom->createElement("IPI");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='clEnq' || $nome_campo=='CNPJProd' || $nome_campo=='cSelo' || $nome_campo=='qSelo'){
                                if(empty($v2[$nome_campo]))
                                    continue;
                            }
                            $O_IPI->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $M_imposto->appendChild($O_IPI);
                    }elseif($v2['TAG']=='O07' && isset($O_IPI)){
                        $O07_IPITrib = $dom->createElement("IPITrib");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='vIPI'){
                                $O07_vIPI=$dom->createElement($nome_campo,$v2[$nome_campo]);
                                $O07_IPITrib->appendChild( $O07_vIPI );
                                continue;
                            }
                            $O07_IPITrib->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $O_IPI->appendChild($O07_IPITrib);
                    }elseif($v2['TAG']=='O10' && isset($O07_IPITrib) && isset($O07_vIPI)){
                        $O07_IPITrib->insertBefore( $dom->createElement("vBC", $v2['vBC']) ,$O07_vIPI);
                        $O07_IPITrib->insertBefore( $dom->createElement("pIPI", $v2['pIPI']) ,$O07_vIPI);
                    }elseif($v2['TAG']=='O11' && isset($O07_IPITrib) && isset($O07_vIPI)){
                        $O07_IPITrib->insertBefore( $dom->createElement("qUnid", $v2['qUnid']) ,$O07_vIPI);
                        $O07_IPITrib->insertBefore( $dom->createElement("vUnid", $v2['vUnid']) ,$O07_vIPI);
                    }elseif($v2['TAG']=='O08' && isset($O_IPI)){
                        $tmp_grupo = $dom->createElement("IPINT");
                        $tmp_grupo->appendChild($dom->createElement("CST", $v2['CST']));
                        $O_IPI->appendChild($tmp_grupo);
                    // II
                    }elseif($v2['TAG']=='P' && isset($M_imposto) && !isset($P_II)){
                        $P_II = $dom->createElement("II");
                        foreach($campos as $nome_campo)
                            $P_II->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $M_imposto->appendChild($P_II);
                    // PIS
                    }elseif($v2['TAG']=='Q' && isset($M_imposto) && !isset($Q_PIS)){
                        $Q_PIS = $dom->createElement("PIS");
                        $M_imposto->appendChild($Q_PIS);
                    }elseif($v2['TAG']=='Q02' && isset($Q_PIS)){
                        $tmp_grupo = $dom->createElement("PISAliq");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $Q_PIS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Q03' && isset($Q_PIS)){
                        $tmp_grupo = $dom->createElement("PISQtde");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $Q_PIS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Q04' && isset($Q_PIS)){
                        $tmp_grupo = $dom->createElement("PISNT");
                        $tmp_grupo->appendChild($dom->createElement("CST", $v2['CST']));
                        $Q_PIS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Q05' && isset($Q_PIS)){
                        $Q05_PISOutr = $dom->createElement("PISOutr");
                        foreach($campos as $nome_campo)
                            $Q05_PISOutr->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $Q_PIS->appendChild($Q05_PISOutr);
                    }elseif(($v2['TAG']=='Q07' || $v2['TAG']=='Q10') && isset($Q05_PISOutr)){
                        foreach($campos as $nome_campo)
                            $Q05_PISOutr->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                    // PIS ST
                    }elseif($v2['TAG']=='R' && isset($M_imposto) && !isset($R_PISST)){
                        $R_PISST = $dom->createElement("PISST");
                        $R_PISST->appendChild($dom->createElement("vPIS", $v2['vPIS']));
                        $M_imposto->appendChild($R_PISST);
                    }elseif(($v2['TAG']=='R02' || $v2['TAG']=='R04') && isset($R_PISST)){
                        foreach($campos as $nome_campo)
                            $R_PISST->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                    // COFINS
                    }elseif($v2['TAG']=='S' && isset($M_imposto) && !isset($S_COFINS)){
                        $S_COFINS = $dom->createElement("COFINS");
                        $M_imposto->appendChild($S_COFINS);
                    }elseif($v2['TAG']=='S02' && isset($S_COFINS)){
                        $tmp_grupo = $dom->createElement("COFINSAliq");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $S_COFINS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='S03' && isset($S_COFINS)){
                        $tmp_grupo = $dom->createElement("COFINSQtde");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $S_COFINS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='S04' && isset($S_COFINS)){
                        $tmp_grupo = $dom->createElement("COFINSNT");
                        $tmp_grupo->appendChild($dom->createElement("CST", $v2['CST']));
                        $S_COFINS->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='S05' && isset($S_COFINS)){
                        $S05_COFINSOutr = $dom->createElement("COFINSOutr");
                        foreach($campos as $nome_campo)
                            $S05_COFINSOutr->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $S_COFINS->appendChild($S05_COFINSOutr);
                    }elseif(($v2['TAG']=='S07' || $v2['TAG']=='S09') && isset($S05_COFINSOutr)){
                        foreach($campos as $nome_campo)
                            $S05_COFINSOutr->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                    // COFINS ST
                    }elseif($v2['TAG']=='T' && isset($M_imposto) && !isset($T_COFINSST)){
                        $T_COFINSST = $dom->createElement("COFINSST");
                        $T_COFINSST->appendChild($dom->createElement("vCOFINS", $v2['vCOFINS']));
                        $M_imposto->appendChild($T_COFINSST);
                    }elseif(($v2['TAG']=='T02' || $v2['TAG']=='T04') && isset($T_COFINSST)){
                        foreach($campos as $nome_campo)
                            $T_COFINSST->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                    // ISSQN
                    }elseif($v2['TAG']=='U'){
                        $tmp_grupo = $dom->createElement("ISSQN");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $M_imposto->appendChild($tmp_grupo);
                    // TOTAL
                    }elseif($v2['TAG']=='W' && !isset($W_total)){
                        $W_total = $dom->createElement("total");
                        $infNFe->appendChild($W_total);
                    }elseif($v2['TAG']=='W02' && isset($W_total)){
                        $tmp_grupo = $dom->createElement("ICMSTot");    // porque diabos é 'icmstot', se é o total da nota
                        foreach($campos as $nome_campo){
                            if(($nome_campo=='vTotTrib' && $v2[$nome_campo]!='') || $nome_campo!='vTotTrib')
                                $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $W_total->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='W17' && isset($W_total)){
                        $tmp_grupo = $dom->createElement("ISSQNtot");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo]))        // impressionante pode ser tudo opcional... q varsea
                                continue;
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $W_total->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='W23' && isset($W_total)){
                        $tmp_grupo = $dom->createElement("retTrib");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo]))         // dinovo...
                                continue;
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $W_total->appendChild($tmp_grupo);
                    // TRANSPORTE
                    }elseif($v2['TAG']=='X'){
                        $X_transp = $dom->createElement("transp");
                        $X_transp->appendChild($dom->createElement("modFrete", $v2['modFrete']));
                        $infNFe->appendChild($X_transp);
                    }elseif($v2['TAG']=='X03' && isset($X_transp)){
                        $X03_transporta = $dom->createElement("transporta");
                        $campos=explode('|','xNome|IE|xEnder|xMun|UF');    // o uf esta trocado no TXT!!!!
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) continue;    // varzea dinovo....
                            if($nome_campo=='xNome'){
                                $X03_xNome=$dom->createElement($nome_campo,$v2[$nome_campo]);
                                $X03_transporta->appendChild($X03_xNome);
                                continue;
                            }
                            $X03_transporta->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $X_transp->appendChild($X03_transporta);
                    }elseif($v2['TAG']=='X04' && isset($X03_transporta) && isset($X03_xNome) && !empty($v2['CNPJ']) ){
                        $X03_transporta->insertBefore($X03_transporta->appendChild( $dom->createElement("CNPJ", $v2['CNPJ']) ),$X03_xNome);
                    }elseif($v2['TAG']=='X05' && isset($X03_transporta) && isset($X03_xNome) && !empty($v2['CPF']) ){
                        $X03_transporta->insertBefore($X03_transporta->appendChild( $dom->createElement("CPF", $v2['CPF']) ),$X03_xNome);
                    // retenção transporte
                    }elseif($v2['TAG']=='X11' && isset($X_transp)){
                        $tmp_grupo = $dom->createElement("retTransp");
                        foreach($campos as $nome_campo)    // tudo obrigatorio
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $X_transp->appendChild($tmp_grupo);
                    // veiculo e reboque
                    }elseif($v2['TAG']=='X18' && isset($X_transp) && !empty($v2['placa'])){
                        $tmp_grupo = $dom->createElement("veicTransp");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='RNTC'){
                                if(empty($v2[$nome_campo])) 
                                    continue;
                            }
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $X_transp->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='X22' && isset($X_transp)){
                        $tmp_grupo = $dom->createElement("reboque");
                        foreach($campos as $nome_campo){
                            if($nome_campo=='RNTC' || $nome_campo=='vagao' || $nome_campo=='balsa'){
                                if(empty($v2[$nome_campo])) 
                                    continue;
                            }
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $X_transp->appendChild($tmp_grupo);
                    // volumes
                    }elseif($v2['TAG']=='X26' && isset($X_transp)){
                        $X26_vol = $dom->createElement("vol");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) // varzea dinvoo
                                continue;
                            $X26_vol->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $X_transp->appendChild($X26_vol);
                    }elseif($v2['TAG']=='X33' && isset($X26_vol)){
                        $tmp_grupo = $dom->createElement("lacres");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $X26_vol->appendChild($tmp_grupo);
                    // COBRANÇA
                    }elseif($v2['TAG']=='Y'){
                        $Y_cobr = $dom->createElement("cobr");
                        $infNFe->appendChild($Y_cobr);
                    }elseif($v2['TAG']=='Y02' && isset($Y_cobr)){
                        $tmp_grupo = $dom->createElement("fat");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) continue;    // varsea dnovo
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $Y_cobr->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Y07' && isset($Y_cobr)){
                        $tmp_grupo = $dom->createElement("dup");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) continue;    // varse dnovo
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $Y_cobr->appendChild($tmp_grupo);
                    // INFORMAÇÕES ADICIONAIS
                    }elseif($v2['TAG']=='Z'){
                        $Z_infAdic = $dom->createElement("infAdic");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) continue;    // ok...
                            $Z_infAdic->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $infNFe->appendChild($Z_infAdic);
                    }elseif($v2['TAG']=='Z04'){
                        $tmp_grupo = $dom->createElement("obsCont");
                        $tmp_grupo->setAttribute("xCampo", $v2['xCampo']);
                        $tmp_grupo->appendChild( $dom->createElement('xTexto',$v2['xTexto']) );
                        $Z_infAdic->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Z07'){
                        $tmp_grupo = $dom->createElement("obsFisco");
                        $tmp_grupo->setAttribute("xCampo", $v2['xCampo']);
                        $tmp_grupo->appendChild( $dom->createElement('xTexto',$v2['xTexto']) );
                        $Z_infAdic->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='Z10'){
                        $tmp_grupo = $dom->createElement("procRef");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $Z_infAdic->appendChild($tmp_grupo);
                    // exportação
                    }elseif($v2['TAG']=='ZA'){
                        $tmp_grupo = $dom->createElement("exporta");
                        foreach($campos as $nome_campo)
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        $infNFe->appendChild($tmp_grupo);
                    // compra
                    }elseif($v2['TAG']=='ZB'){
                        $tmp_grupo = $dom->createElement("compra");
                        foreach($campos as $nome_campo){
                            if(empty($v2[$nome_campo])) continue;
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $infNFe->appendChild($tmp_grupo);
                    // cana
                    }elseif($v2['TAG']=='ZC01'){
                        $ZC01_cana = $dom->createElement("cana");
                        foreach($campos as $nome_campo){
                            $ZC01_cana->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $infNFe->appendChild($ZC01_cana);
                    }elseif($v2['TAG']=='ZC04' && isset($ZC01_cana)){
                        $tmp_grupo = $dom->createElement("forDia");
                        foreach($campos as $nome_campo){
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $ZC01_cana->appendChild($tmp_grupo);
                    }elseif($v2['TAG']=='ZC10' && isset($ZC01_cana)){
                        $tmp_grupo = $dom->createElement("deduc");
                        foreach($campos as $nome_campo){
                            $tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
                        }
                        $ZC01_cana->appendChild($tmp_grupo);
                    }
                }
            }
            $NFe->appendChild($infNFe);
            $dom->appendChild($NFe);
            ////////
            // corrige chave nfe
            $ret=$this->__montaChaveXML($dom);
            if($ret!==true)
                $RETURN['erros'][$knota][]="$MSG_PADRAO Erro ao calcular chave XML - $ret";
            ////////
            if($output_string){
                $RETURN['xml'][$knota]= $dom->saveXML();
#var_dump($dom->saveXML());
                $RETURN['xml'][$knota]= str_replace(
                                '<?xml version="1.0" encoding="UTF-8  standalone="no"?>',
                                '<?xml version="1.0" encoding="UTF-8"?>',
                                $RETURN['xml'][$knota]);
                //remove linefeed, carriage return, tabs e multiplos espaços
                $RETURN['xml'][$knota]= preg_replace('/\s\s+/',' ', $RETURN['xml'][$knota]);
                $RETURN['xml'][$knota]= str_replace("> <","><", $RETURN['xml'][$knota]);
            }else{
                $RETURN['xml'][$knota]= $dom;
            }
            unset($dom,$NFe,$infNFe);
        }
        return($RETURN);
    }
    private function __montaChaveXML(& $dom){
        $ide    = $dom->getElementsByTagName("ide")->item(0);
        if(empty($ide))        return("'ide' não encontrado");
        $emit   = $dom->getElementsByTagName("emit")->item(0);
        if(empty($emit))    return("'emit' não encontrado");
        $cUF    = $ide->getElementsByTagName('cUF')->item(0);
        if(empty($cUF))        return("'cUF' não encontrado");        $cUF = $cUF->nodeValue;
        $dEmi   = $ide->getElementsByTagName('dEmi')->item(0);
        if(empty($dEmi))    return("'dEmi' não encontrado");    $dEmi = $dEmi->nodeValue;
        $CNPJ   = $emit->getElementsByTagName('CNPJ')->item(0);
        if(empty($CNPJ))    return("'CNPJ' não encontrado");    $CNPJ = $CNPJ->nodeValue;
        $mod    = $ide->getElementsByTagName('mod')->item(0);
        if(empty($mod))        return("'mod' não encontrado");        $mod = $mod->nodeValue;
        $serie  = $ide->getElementsByTagName('serie')->item(0);
        if(empty($serie))    return("'serie' não encontrado");    $serie = $serie->nodeValue;
        $nNF    = $ide->getElementsByTagName('nNF')->item(0);
        if(empty($nNF))        return("'nNF' não encontrado");        $nNF = $nNF->nodeValue;
        $tpEmis = $ide->getElementsByTagName('tpEmis')->item(0);
        if(empty($tpEmis))    return("'tpEmis' não encontrado");    $tpEmis = $tpEmis->nodeValue;
        $cNF    = $ide->getElementsByTagName('cNF')->item(0);
        if(empty($cNF))        return("'cNF' não encontrado");        $cNF = $cNF->nodeValue;
        $cDV    = $ide->getElementsByTagName('cDV')->item(0);
        if(empty($cDV))        return("'cDV' não encontrado");
        
        
        if( strlen($cNF) != 8 ){    // gera o numero aleatório
            $cNF = $ide->getElementsByTagName('cNF')->item(0)->nodeValue = rand( 0 , 99999999 );
        }
        $tempData = explode("-", $dEmi);
        if(!isset($tempData[0]))    $tempData[0]=0;
        if(!isset($tempData[1]))    $tempData[1]=0;
        
        $CNPJ = preg_replace("/[^0-9]/", "", $CNPJ);
        $tempChave =    substr(str_pad(abs((int)$cUF            ), 2,'0',STR_PAD_LEFT),0, 2).
                substr(str_pad(abs((int)$tempData[0] - 2000     ), 2,'0',STR_PAD_LEFT),0, 2).
                substr(str_pad(abs((int)$tempData[1]         ), 2,'0',STR_PAD_LEFT),0, 2).
                substr(str_pad($CNPJ                  ,14,'0',STR_PAD_LEFT),0,14).
                substr(str_pad(abs((int)$mod             ), 2,'0',STR_PAD_LEFT),0, 2).
                substr(str_pad(abs((int)$serie             ), 3,'0',STR_PAD_LEFT),0, 3).
                substr(str_pad(abs((int)$nNF             ), 9,'0',STR_PAD_LEFT),0, 9).
                substr(str_pad(abs((int)$tpEmis         ), 1,'0',STR_PAD_LEFT),0, 1).
                substr(str_pad(abs((int)$cNF            ), 8,'0',STR_PAD_LEFT),0, 8);
        //        00.20.00.00000000000000.00.000.000000000.0.18641952.6
        //$forma =     "%02d%02d%02d%s%02d%03d%09d%01d%08d";//%01d";
        $cDV    = $ide->getElementsByTagName('cDV')->item(0)->nodeValue  = $this->__calculaDV($tempChave);
        $chave  = $tempChave .= $cDV;
        $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
        if(empty($infNFe))        return("'infNFe' não encontrado");
        $infNFe->setAttribute("Id", "NFe" . $chave);
        return(true);
    } //fim __calculaChave
    
    private function __calculaDV($chave43) {
        $chave43=str_pad($chave43,'0',STR_PAD_LEFT);
        $multiplicadores = array(2,3,4,5,6,7,8,9);
        $i = 42;
        $soma_ponderada=0;
        while ($i >= 0) {
            for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
                $soma_ponderada+= ((int)substr($chave43,$i,1)) * $multiplicadores[$m];
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

    
    
    public function XML2TXT($xml){
        if(!is_array($xml))
            $xml=array($xml);
        $RETURN=array(    'erros'    =>array(),
                'avisos'=>array(),
                'txt'    =>'');
        foreach($xml as $knota=>$tmp_xml){
            $MSG_PADRAO="[Nota $knota]";
#echo "\n$MSG_PADRAO\n";
            if(is_string($tmp_xml)){
                $dom = new DOMDocument('1.0', 'utf-8');
                libxml_clear_errors();
                if (is_file($tmp_xml)){
                    $dom->load($tmp_xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                } else {
#echo "XML: \n$tmp_xml";
                    $dom->loadXML($tmp_xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
                }
                $errors = libxml_get_errors(); 
                if (!empty($errors)) { 
                    foreach($errors as $e){
                        if($e->level==LIBXML_ERR_ERROR || $e->level==LIBXML_ERR_FATAL)
                            $RETURN['erros'][$knota][]="$MSG_PADRAO ".
                            $e->level." ".$e->code." - ".$e->message;
                        else
                            $RETURN['avisos'][$knota][]="$MSG_PADRAO ".
                            $e->level." ".$e->code." - ".$e->message;
                        
                    }
                }
                unset($errors,$e);
            }else{
                $dom=$tmp_xml;
            }
            unset($tmp_xml);
            if(!is_object($dom)){
                $RETURN['erros'][$knota][]="Não foi possivel criar o objeto DOMDocument para abrir o conteúdo XML";
                continue;
            }
            if(get_class($dom)!='DOMDocument'){
                $RETURN['erros'][$knota][]="Tipo de objeto não é DOMDocument";
                continue;
            }
            $RETURN['txt'][$knota]='';
            $CUR_TXT=& $RETURN['txt'][$knota];
            $infNFe = $dom->getElementsByTagName("infNFe")->item(0);
            if (!isset($infNFe)){
                $RETURN['erros'][$knota][]="$MSG_PADRAO Tag infNFe não encontrada";
                continue;
            }
            $versao = $infNFe->getAttribute("versao");
            /// vamos lá.... processo reverso agora
            if($versao=='2.00'){
                // A
                $CAMPOS    =explode('|',$this->campos_v200['A']);
                foreach($CAMPOS as $k=>$v)
                    if($k!=0 && strlen(trim($v))>0)
                        $CAMPOS[$k]=$infNFe->getAttribute($v);    // só atributos
                $CUR_TXT.=implode('|',$CAMPOS)."\n";
                $MSG_PADRAO="[Nota $knota - ".$CAMPOS[2]."]";
                
                // B
                $ide=$dom->getElementsByTagName("ide")->item(0);
                if (empty($ide)){
                    $RETURN['erros'][$knota][]="$MSG_PADRAO Tag ide não encontrada";
                    continue;
                }
                $CAMPOS    =explode('|',$this->campos_v200['B']);
                foreach($CAMPOS as $k=>$v){
                    if($k!=0 && strlen(trim($v))>0){
#var_dump($ide);echo "v=>$v\n";
                        $CAMPOS[$k]=$ide->getElementsByTagName($v)->item(0);
#var_dump($CAMPOS[$k]);
                        if( !empty($CAMPOS[$k]) ){
                            $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                            $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                        }
                    }
                }
#die();
                $CUR_TXT.=implode('|',$CAMPOS)."\n";
                
                // Bxxxxx
                $NFref=$ide->getElementsByTagName("NFref")->item(0);
                if (!empty($NFref)){
                    // B13 - refNFe
                    $tmp_grupo=$NFref->getElementsByTagName("refNFe");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['B13']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // B14 - refNF
                    $tmp_grupo=$NFref->getElementsByTagName("refNF");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['B14']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // B20a, B20d, B20e
                    $tmp_grupo=$NFref->getElementsByTagName("refNFP");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['B20a']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            $tmp1=$tmp_grupo->item($c)->getElementsByTagName('CNPJ');
                            $tmp2=$tmp_grupo->item($c)->getElementsByTagName('CPF');
                            $CAMPOS=false;
                            if(!empty($tmp1)){
                                $CAMPOS    =explode('|',$this->campos_v200['B20d']);
                            }elseif(!empty($tmp2)){
                                $CAMPOS    =explode('|',$this->campos_v200['B20e']);
                            }
                            if ($CAMPOS!==false){
                                foreach($CAMPOS as $k=>$v){
                                    if($k!=0 && strlen(trim($v))>0){
                                        $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                        if( !empty($CAMPOS[$k]) ){
                                            $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                            $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                        }
                                    }
                                }
                                $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            }
                        }
                    }
                    // B20i
                    $tmp_grupo=$NFref->getElementsByTagName("refCTe");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['B20i']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // B20j
                    $tmp_grupo=$NFref->getElementsByTagName("refECF");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['B20j']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                }
                // C, C02, C02a
                $infNFe=$dom->getElementsByTagName("infNFe")->item(0);
                if (empty($infNFe)){
                    $RETURN['erros'][$knota][]="$MSG_PADRAO Tag infNFe não encontrada";
                    continue;
                }
                $emit=$infNFe->getElementsByTagName("emit")->item(0);
                if (!empty($emit)){
                    $CAMPOS    =explode('|',$this->campos_v200['C']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$emit->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    $tmp1=$emit->getElementsByTagName('CNPJ')->item(0);
                    $tmp2=$emit->getElementsByTagName('CPF')->item(0);
                    $CAMPOS=false;
                    if(!empty($tmp1)){
                        $CAMPOS    =explode('|',$this->campos_v200['C02']);
                    }elseif(!empty($tmp2)){
                        $CAMPOS    =explode('|',$this->campos_v200['C02a']);
                    }
                    if($CAMPOS!==false){
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$emit->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    
                    // C05
                    $tmp_grupo=$emit->getElementsByTagName("enderEmit")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['C05']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                }
                // D
                $tmp_grupo=$infNFe->getElementsByTagName("avulsa")->item(0);
                if (!empty($tmp_grupo)){
                    $CAMPOS    =explode('|',$this->campos_v200['D']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                }
                
                // E, E02, E03
                $dest=$infNFe->getElementsByTagName("dest")->item(0);
                if (!empty($dest)){
                    $CAMPOS    =explode('|',$this->campos_v200['E']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$dest->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    $tmp1=$dest->getElementsByTagName('CNPJ')->item(0);
                    $tmp2=$dest->getElementsByTagName('CPF')->item(0);
                    $CAMPOS=false;
                    if(!empty($tmp1)){
                        $CAMPOS    =explode('|',$this->campos_v200['E02']);
                    }elseif(!empty($tmp2)){
                        $CAMPOS    =explode('|',$this->campos_v200['E03']);
                    }
                    if($CAMPOS!==false){
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$dest->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // E05
                    $tmp_grupo=$dest->getElementsByTagName("enderDest")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['E05']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                }
                //F, F02, F02a
                $tmp_grupo=$infNFe->getElementsByTagName("retirada")->item(0);
                if (!empty($tmp_grupo)){
                    $CAMPOS    =explode('|',$this->campos_v200['F']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    $tmp1=$dest->getElementsByTagName('CNPJ')->item(0);
                    $tmp2=$dest->getElementsByTagName('CPF')->item(0);
                    $CAMPOS=false;
                    if(!empty($tmp1)){
                        $CAMPOS    =explode('|',$this->campos_v200['F02']);
                    }elseif(!empty($tmp2)){
                        $CAMPOS    =explode('|',$this->campos_v200['F02a']);
                    }
                    if($CAMPOS!==false){
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$dest->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                }
                //G, G02, G02a
                $tmp_grupo=$infNFe->getElementsByTagName("entrega")->item(0);
                if (!empty($tmp_grupo)){
                    $CAMPOS    =explode('|',$this->campos_v200['G']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    $tmp1=$tmp_grupo->getElementsByTagName('CNPJ')->item(0);
                    $tmp2=$tmp_grupo->getElementsByTagName('CPF')->item(0);
                    $CAMPOS=false;
                    if(!empty($tmp1)){
                        $CAMPOS    =explode('|',$this->campos_v200['G02']);
                    }elseif(!empty($tmp2)){
                        $CAMPOS    =explode('|',$this->campos_v200['G02a']);
                    }
                    if($CAMPOS!==false){
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                }
                // ÍTENS.....
                $det=$infNFe->getElementsByTagName("det");
                for($cur_item=0;$cur_item<$det->length;$cur_item++){
                    $item=$det->item($cur_item);
                    // H
                    $nItem        = $item->getAttribute("nItem");
                    $infAdProd    = $item->getElementsByTagName("infAdProd")->item(0);
                    if(!empty($infAdProd))
                        $infAdProd=$infAdProd->nodeValue;
                    $CUR_TXT    .="H|$nItem|$infAdProd|\r\n";

                    // I
                    $prod=$item->getElementsByTagName("prod")->item(0);
                    $CAMPOS    =explode('|',$this->campos_v200['I']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$prod->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    
                    // I18
                    $tmp_grupo=$prod->getElementsByTagName("DI");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['I18']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            // I25
                            $tmp_grupo2=$tmp_grupo->item($c)->getElementsByTagName("adi");
                            if (!empty($tmp_grupo2)){
                                for($c2 = 0; $c2<$tmp_grupo2->length; $c2++){
                                    $CAMPOS    =explode('|',$this->campos_v200['I25']);
                                    foreach($CAMPOS as $k=>$v){
                                        if($k!=0 && strlen(trim($v))>0){
                                            $CAMPOS[$k]=$tmp_grupo2->item($c2)->getElementsByTagName($v);
                                            if( !empty($CAMPOS[$k]) ){
                                                $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                            }
                                        }
                                    }
                                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                                }
                            }
                        }
                    }
                    // J
                    $tmp_grupo=$prod->getElementsByTagName("veicProd")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['J']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$prod->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // K
                    $tmp_grupo=$prod->getElementsByTagName("med");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['K']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // L
                    $tmp_grupo=$prod->getElementsByTagName("arma");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['L']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // L01
                    // L105
                    $tmp_grupo=$prod->getElementsByTagName("comb");
                    if (!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $tmp_comb=$tmp_grupo->item($c);
                            $CAMPOS    =explode('|',$this->campos_v200['L01']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_comb->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            // L105
                            $tmp_grupo2=$tmp_comb->getElementsByTagName("CIDE")->item(0);
                            if (!empty($tmp_grupo2)){
                                $CAMPOS    =explode('|',$this->campos_v200['L105']);
                                foreach($CAMPOS as $k=>$v){
                                    if($k!=0 && strlen(trim($v))>0){
                                        $CAMPOS[$k]=$tmp_grupo2->getElementsByTagName($v);
                                        if( !empty($CAMPOS[$k]) ){
                                            $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                            $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                        }
                                    }
                                }
                                $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            }
                        }
                    }
                    // M - impostos
                    
                    // lei da transparencia
                    $imposto=$item->getElementsByTagName("imposto")->item(0);
                    $vTotTrib=trim($imposto->getElementsByTagName('vTotTrib')->item(0));
                    if(strlen($vTotTrib)>0){
                        $CUR_TXT    .="M|$vTotTrib|\n";
                    }else{
                        $CUR_TXT    .="M|\n";
                    }
                    // N - ICMS
                    $CUR_TXT    .="N|\n";
                    // NXXXX
                    $N=NULL;
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS00");    if(!empty($N))$N='N02'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS10");    if(!empty($N))$N='N03'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS20");    if(!empty($N))$N='N04'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS30");    if(!empty($N))$N='N05'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS40");    if(!empty($N))$N='N06'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS51");    if(!empty($N))$N='N07'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS60");    if(!empty($N))$N='N08'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS70");    if(!empty($N))$N='N09'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMS90");    if(!empty($N))$N='N10'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSPart");    if(!empty($N))$N='N10a'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSST");    if(!empty($N))$N='N10b'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN101");    if(!empty($N))$N='N10c'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN102");    if(!empty($N))$N='N10d'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN201");    if(!empty($N))$N='N10e'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN202");    if(!empty($N))$N='N10f'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN500");    if(!empty($N))$N='N10g'; else $N=NULL;    }
                    if(empty($N)){    $N=$imposto->getElementsByTagName("ICMSSN900");    if(!empty($N))$N='N10h'; else $N=NULL;    }
                    
                    $tmp_grupo=$imposto->getElementsByTagName("ICMS")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200[$N]);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // O
                    $tmp_grupo=$imposto->getElementsByTagName("IPI")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['O']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // O08
                    $tmp_grupo=$imposto->getElementsByTagName("IPINT")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['O08']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // O07
                    $tmp_grupo=$imposto->getElementsByTagName("IPITrib")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['O07']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        // O10
                        $tmp_test=$tmp_grupo->getElementsByTagName("vBC")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['O10']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                        // O11
                        $tmp_test=$tmp_grupo->getElementsByTagName("QUnid")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['O11']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    //P
                    $tmp_grupo=$imposto->getElementsByTagName("II")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['P']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    //U
                    $tmp_grupo=$imposto->getElementsByTagName("ISSQN")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['U']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    //Q
                    $CUR_TXT.="Q|\n";
                    // Q02
                    $tmp_grupo=$imposto->getElementsByTagName("PISAliq")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['Q02']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // Q03
                    $tmp_grupo=$imposto->getElementsByTagName("PISQtde")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['Q03']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // Q04
                    $tmp_grupo=$imposto->getElementsByTagName("PISNT")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['Q04']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // Q05
                    $tmp_grupo=$imposto->getElementsByTagName("PISOutr")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['Q05']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        // vBC -> Q07
                        $tmp_test=$tmp_grupo->getElementsByTagName("vBC")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['Q07']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                        // Q10
                        $tmp_test=$tmp_grupo->getElementsByTagName("qBCProd")->item(0);
                    
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['Q10']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    //R
                    $tmp_grupo=$imposto->getElementsByTagName("PISST")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['R']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        // vBC -> R02
                        $tmp_test=$tmp_grupo->getElementsByTagName("vBC")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['R02']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                        // qBCProd -> R04
                        $tmp_test=$tmp_grupo->getElementsByTagName("qBCProd")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['R04']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    //S
                    $CUR_TXT.="S|\n";
                    // S02
                    $tmp_grupo=$imposto->getElementsByTagName("COFINSAliq")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['S02']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // S03
                    $tmp_grupo=$imposto->getElementsByTagName("COFINSQtde")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['S03']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // S04
                    $tmp_grupo=$imposto->getElementsByTagName("COFINSNT")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['S04']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // S05
                    $tmp_grupo=$imposto->getElementsByTagName("COFINSOutr")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['S05']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        // vBC -> S07
                        $tmp_test=$imposto->getElementsByTagName("vBC")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['S07']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }    
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                        // S09
                        $tmp_test=$imposto->getElementsByTagName("qBCProd")->item(0);
                        if (!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['S09']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    //T
                    $tmp_grupo=$imposto->getElementsByTagName("COFINSST")->item(0);
                    if (!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['T']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                            
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        // vBC -> T02
                        $tmp_test=$tmp_grupo->getElementsByTagName('vBC')->item(0);
                        if(!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['T02']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                        // qBCProd -> T04
                        $tmp_test=$tmp_grupo->getElementsByTagName('qBCProd')->item(0);
                        if(!empty($tmp_test)){
                            $CAMPOS    =explode('|',$this->campos_v200['T04']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                                
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                }
                
                // TOTAL
                // W
                // W02
                $total=$infNFe->getElementsByTagName("total")->item(0);
                if(!empty($total)){
                    $CUR_TXT.="W|\n";
                    $tmp_grupo=$total->getElementsByTagName("ICMSTot")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['W02']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // W17
                    $tmp_grupo=$total->getElementsByTagName("ISSQNtot")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['W17']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    // W23
                    $tmp_grupo=$total->getElementsByTagName("retTrib")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['W23']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                }
                // TRANSPORTE
                // X
                $transp=$infNFe->getElementsByTagName("transp")->item(0);
                if(!empty($transp)){
                    $CAMPOS    =explode('|',$this->campos_v200['X']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$transp->getElementsByTagName($v);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    
                    // X03, X04, X05
                    $tmp_grupo=$transp->getElementsByTagName("transporta")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['X03']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        $tmp1=$tmp_grupo->getElementsByTagName('CNPJ')->item(0);
                        $tmp2=$tmp_grupo->getElementsByTagName('CPF')->item(0);
                        $CAMPOS=false;
                        if(!empty($tmp1)){
                            $CAMPOS    =explode('|',$this->campos_v200['X04']);
                        }elseif(!empty($tmp2)){
                            $CAMPOS    =explode('|',$this->campos_v200['X05']);
                        }
                        if($CAMPOS!==false){
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // X11
                    $tmp_grupo=$transp->getElementsByTagName("retTransp")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['X11']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    //X18
                    $tmp_grupo=$transp->getElementsByTagName("veicTransp")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['X18']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                        $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    }
                    //X22
                    $tmp_grupo=$transp->getElementsByTagName("reboque");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['X22']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v)->item(0);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    //X26
                    $tmp_grupo=$transp->getElementsByTagName("vol");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['X26']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v)->item(0);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                            
                            $tmp_grupo2=$tmp_grupo->item($c)->getElementsByTagName("lacres");
                            if(!empty($tmp_grupo2)){
                                for($c2 = 0; $c2<$tmp_grupo2->length; $c2++){
                                    $CAMPOS    =explode('|',$this->campos_v200['X33']);
                                    foreach($CAMPOS as $k=>$v){
                                        if($k!=0 && strlen(trim($v))>0){
                                            $CAMPOS[$k]=$tmp_grupo2->item($c2)->getElementsByTagName($v);
                                            if( !empty($CAMPOS[$k]) ){
                                                $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                            }
                                        }
                                    }
                                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                                }
                            }
                        }
                    }
                }
                // COBRANÇA
                // Y02
                $cobr=$infNFe->getElementsByTagName("cobr")->item(0);
                if(!empty($cobr)){
                    $CUR_TXT.="Y|\n";
                    $tmp_grupo=$cobr->getElementsByTagName("fat")->item(0);
                    if(!empty($tmp_grupo)){
                        $CAMPOS    =explode('|',$this->campos_v200['Y02']);
                        foreach($CAMPOS as $k=>$v){
                            if($k!=0 && strlen(trim($v))>0){
                                $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                                if( !empty($CAMPOS[$k]) ){
                                    $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                    $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                }
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    //Y07
                    $tmp_grupo=$cobr->getElementsByTagName("dup");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['Y07']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v)->item(0);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                }
                // INF ADICIONAL
                // Z
                $infAdic=$infNFe->getElementsByTagName("infAdic")->item(0);
                if(!empty($infAdic)){
                    $CAMPOS    =explode('|',$this->campos_v200['Z']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$infAdic->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    //Z04, Z07, Z10
                    $tmp_grupo=$infAdic->getElementsByTagName("obsCont");
                    // BUGGGGGGGGGGGGGG!!!!
                    // obsFisco e obsCont -> campo "xCampo" deve vim do atributo e não do valor do tagname!!! mudar depois!!!
                        
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $xCampo=$tmp_grupo->item($c)->getAttribute('xCampo');
                            $xTexto=$tmp_grupo->item($c)->getElementsByTagName('xTexto');
                            if( !empty($xTexto) ){
                                $xTexto=$CAMPOS[$k]->item(0)->nodeValue;
                                $xTexto=str_replace('|','',$xTexto);
                            }
                            $CUR_TXT.="Z04|$xCampo|$xTexto|\n";
                        }
                    }
                    $tmp_grupo=$infAdic->getElementsByTagName("obsFisco");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $xCampo=$tmp_grupo->item($c)->getAttribute('xCampo');
                            $xTexto=$tmp_grupo->item($c)->getElementsByTagName('xTexto');
                            if( !empty($xTexto) ){
                                $xTexto=$CAMPOS[$k]->item(0)->nodeValue;
                                $xTexto=str_replace('|','',$xTexto);
                            }
                            $CUR_TXT.="Z07|$xCampo|$xTexto|\n";
                        }
                    }
                    $tmp_grupo=$infAdic->getElementsByTagName("procRef");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['Z10']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                }
                // EXPORTAÇÃO
                // ZA
                $tmp_grupo=$infNFe->getElementsByTagName("exporta")->item(0);
                if(!empty($tmp_grupo)){
                    $CAMPOS    =explode('|',$this->campos_v200['ZA']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                }
                // COMPRA
                // ZB
                $tmp_grupo=$infNFe->getElementsByTagName("compra")->item(0);
                if(!empty($tmp_grupo)){
                    $CAMPOS    =explode('|',$this->campos_v200['ZB']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$tmp_grupo->getElementsByTagName($v)->item(0);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                }
                // CANA
                // ZC01
                $cana=$infNFe->getElementsByTagName("cana")->item(0);
                if(!empty($cana)){
                    $CAMPOS    =explode('|',$this->campos_v200['ZC01']);
                    foreach($CAMPOS as $k=>$v){
                        if($k!=0 && strlen(trim($v))>0){
                            $CAMPOS[$k]=$cana->getElementsByTagName($v);
                            if( !empty($CAMPOS[$k]) ){
                                $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                            }
                        }
                    }
                    $CUR_TXT.=implode('|',$CAMPOS)."\n";
                    // ZC04
                    $tmp_grupo=$cana->getElementsByTagName("forDia");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['ZC04']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                    // ZC10
                    $tmp_grupo=$cana->getElementsByTagName("deduc");
                    if(!empty($tmp_grupo)){
                        for($c = 0; $c<$tmp_grupo->length; $c++){
                            $CAMPOS    =explode('|',$this->campos_v200['ZC10']);
                            foreach($CAMPOS as $k=>$v){
                                if($k!=0 && strlen(trim($v))>0){
                                    $CAMPOS[$k]=$tmp_grupo->item($c)->getElementsByTagName($v);
                                    if( !empty($CAMPOS[$k]) ){
                                        $CAMPOS[$k]=$CAMPOS[$k]->item(0)->nodeValue;
                                        $CAMPOS[$k]=str_replace('|','',$CAMPOS[$k]);
                                    }
                                }
                            }
                            $CUR_TXT.=implode('|',$CAMPOS)."\n";
                        }
                    }
                }
            }
            unset($dom);
        }
        return $RETURN;
    }
}


/*
FORMATO TXT:

--------------------------------------------------------------------------------------------------------------------------------------------
VERSÃO 2.00
    NOTA FISCAL|qtd notas fiscais no arquivo| 
    A|versão do schema|id| 
    B|cUF|cNF|NatOp|intPag|mod|serie|nNF|dEmi|dSaiEnt|hSaiEnt|tpNF|cMunFG|TpImp|TpEmis|cDV|tpAmb|finNFe|procEmi|VerProc|dhCont|xJust| 
        [0 a N] { 
            [seleção entre B13 ou B14 ou B20a ou B20i ou B20j]{
                B13|refNFe| 
                [ou] 
                B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF| 
                [ou] 
                B20a|cUF|AAMM|IE|mod|serie|nNF| 
                    [seleção entre B20d ou B20e]{ 
                        B20d|CNPJ| 
                        [ou] 
                        B20e|CPF| 
                    } 
                [ou] 
                B20i|refCTe| 
                [ou] 
                B20j|mod|nECF|nCOO| 
            } 
        } 
    C|XNome|XFant|IE|IEST|IM|CNAE|CRT| 
        [seleção entre C02 ou C02a]{
            C02|CNPJ| 
            [ou] 
            C02a|CPF| 
        } 
    C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone| 
        [0 ou 1]{ 
            D|CNPJ|xOrgao|matr|xAgente|fone|UF|nDAR|dEmi|vDAR|repEmi|dPag| 
        }
    E|xNome|IE|ISUF|email| 
        [seleção entre E02 ou E03]{
            E02|CNPJ| 
            [ou] 
            E03|CPF| 
        } 
    E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
        [0 ou 1]{ 
            F|CNPJ|XLgr|Nro|XCpl|XBairro|CMun|XMun|UF| 
                [seleção entre F02 ou F02a]{
                    F02|CNPJ 
                    [ou] 
                    F02a|CPF 
                }
        }
        [0 ou 1]{
            G|CNPJ|XLgr|Nro|XCpl|XBairro|CMun|XMun|UF|     // esta linha é do manual, mas está errada!!!!
            G|XLgr|Nro|XCpl|XBairro|CMun|XMun|UF|         // esta linha é a correta
                [seleção entre G02 ou G02a]{
                    G02|CNPJ 
                    [ou] 
                    G02a|CPF 
                } 
        }

    [1 a 990]{ 
        H|nItem|infAdProd| 
        I|CProd|CEAN|XProd|NCM|EXTIPI|CFOP|UCom|QCom|VUnCom|VProd|CEANTrib|UTrib|QTrib|VUnTrib|VFrete|VSeg|VDesc|vOutro|indTot|xPed|nItemPed| 
            [0 a N]{ 
                I18|NDI|DDI|XLocDesemb|UFDesemb|DDesemb|CExportador| 
                    [1 a N]{ 
                        I25|NAdicao|NSeqAdic|CFabricante|VDescDI| 
                    } 
            } 
            [0 ou 1 – apenas se veículo]{ 
                J|TpOp|Chassi|CCor|XCor|Pot|cilin|pesoL|pesoB|NSerie|TpComb|NMotor|CMT|Dist|anoMod|anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest| 
            } 
            [0 a N – apenas se medicamento]{ 
                K|NLote|QLote|DFab|DVal|VPMC| 
            } 
            [0 a N – apenas se armamento]{ 
                L|TpArma|NSerie|NCano|Descr| 
            } 
            [0 a N – apenas se combustível]{ 
                L01|CProdANP|CODIF|QTemp|UFCons| 
                [0 ou 1]{ 
                    L105|QBCProd|VAliqProd|VCIDE| 
                }
            } 
        M| 
            N| 
                [Seleção entre N02 ou N03 ou N04 ou N05 ou N06 ou N07 ou N08 ou N09 ou N10 ou N10a ou N10b ou N10c ou N10d ou N10e ou N10f ou N10g ou N10h]{
                    N02|Orig|CST|ModBC|VBC|PICMS|VICMS| 
                    [ou]
                    N03|Orig|CST|ModBC|VBC|PICMS|VICMS|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST| 
                    [ou]
                    N04|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS| 
                    [ou]
                    N05|Orig|CST|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST| 
                    [ou]
                    N06|Orig|CST|vICMS|motDesICMS| 
                    [ou]
                    N07|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS| 
                    [ou]
                    N08|Orig|CST|VBCST|VICMSST| 
                    [ou]
                    N09|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST| 
                    [ou]
                    N10|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST| 
                    [ou]
                    N10a|Orig|CST|ModBC|PRedBC|VBC|PICMS|VICMS|ModBCST|PMVAST|PRedBCST|VBCST|PICMSST|VICMSST|pBCOp|UFST| 
                    [ou]
                    N10b|Orig|CST|vBCSTRet|vICMSSTRet|vBCSTDest|vICMSSTDest| 
                    [ou]
                    N10c|Orig|CSOSN|pCredSN|vCredICMSSN| 
                    [ou]
                    N10d|Orig|CSOSN| 
                    [ou]
                    N10e|Orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN| 
                    [ou]
                    N10f|Orig|CSOSN|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST| 
                    [ou]
                    N10g|Orig|CSOSN|modBCST|vBCSTRet|vICMSSTRet| 
                    [ou]
                    N10h|Orig|CSOSN|modBC|vBC|pRedBC|pICMS|vICMS|modBCST|pMVAST|pRedBCST|vBCST|pICMSST|vICMSST|pCredSN|vCredICMSSN| 
                }
                [0 ou 1]{
                    O|ClEnq|CNPJProd|CSelo|QSelo|CEnq| 
                        [seleção entre O07 ou O08]{
                            O07|CST|VIPI| 
                            [seleção entre O010 ou O11]{ 
                                O10|VBC|PIPI| 
                                [ou] 
                                O11|QUnid|VUnid| 
                            } 
                            [ou] 
                            O08|CST| 
                        }
                } 
                [0 ou 1]{
                    P|VBC|VDespAdu|VII|VIOF| 
                } 
                [0 ou 1]{ 
                    U|VBC|VAliq|VISSQN|CMunFG|CListServ|cSitTrib| 
                }         
                Q| 
                    [Seleção entre Q02 ou Q03 ou Q04 ou Q05]{
                        Q02|CST|VBC|PPIS|VPIS| 
                        [ou] 
                        Q03|CST|QBCProd|VAliqProd|VPIS| 
                        [ou] 
                        Q04|CST| 
                        [ou] 
                        Q05|CST|VPIS| 
                        [Seleção entre Q07 ou Q010]{
                            Q07|VBC|PPIS| 
                            [ou] 
                            Q10|QBCProd|VAliqProd| 
                        }
                    } 
                R|VPIS| 
                    [Seleção entre R02 ou R04]{ 
                        R02|VBC|PPIS| 
                        [ou]
                        R04|QBCProd|VAliqProd| 
                S| 
                    [Seleção entre S02 ou S03 ou S04 ou S05]{
                        S02|CST|VBC|PCOFINS|VCOFINS| 
                        [ou]
                        S03|CST|QBCProd|VAliqProd|VCOFINS| 
                        [ou]
                        S04|CST| 
                        [ou]
                        S05|CST|VCOFINS| 
                        [Seleção entre S07 ou S09]{
                            S07|VBC|PCOFINS| 
                            [ou]
                            S09|QBCProd|VAliqProd| 
                        } 
                    } 
                [0 ou 1]{ 
                    T|VCOFINS| 
                        [Seleção entre T02 ou T04]{ 
                            T02|VBC|PCOFINS| 
                            [ou] 
                            T04|QBCProd|VAliqProd|
                        } 
                } 
            }
    }    // esta linha não esta no TXT, esta errado no manual....
    W| 
    W02|vBC|vICMS|vBCST|vST|vProd|vFrete|vSeg|vDesc|vII|vIPI|vPIS|vCOFINS|vOutro|vNF|vTotTrib|
    [0 ou 1]{
        W17|VServ|VBC|VISS|VPIS|VCOFINS| 
    } 
    W23|VRetPIS|VRetCOFINS|VRetCSLL|VBCIRRF|VIRRF|VBCRetPrev|VRetPrev| 
    X|ModFrete| 
    X03|XNome|IE|XEnder|UF|XMun| 
    [Seleção entre X04 ou X05]{ 
        X04|CNPJ|
        [ou] 
        X05|CPF|
    } 
    [0 ou 1]{ 
        X11|VServ|VBCRet|PICMSRet|VICMSRet|CFOP|CMunFG| 
    } 
    [0 ou 1]{ 
        X18|Placa|UF|RNTC|
    }
    [0 a 2]{ 
        X22|Placa|UF|RNTC|
    } 
    [0 a N]{ 
        X26|QVol|Esp|Marca|NVol|PesoL|PesoB| 
        [0 a N]{ 
            X33|NLacre| 
        } 
    }
    [0 ou 1]{ 
        Y| 
        [0 ou 1]{
            Y02|NFat|VOrig|VDesc|VLiq| 
        } 
        [0 a N]{
            Y07|NDup|DVenc|VDup| 
        } 
    }    // esta linha não tem no txt - esta errado no manual...
    [0 ou 1]{ 
        Z|InfAdFisco|InfCpl| 
        [0 a 10]{
            Z04|XCampo|XTexto| 
        } 
        [0 a 10]{
            Z07|XCampo|XTexto| 
        } 
        [0 a N]{ 
            Z10|NProc|IndProc| 
        } 
    } 
    [0 ou 1]{ 
        ZA|UFEmbarq|XLocEmbarq|
    } 
    [0 ou 1]{ 
        ZB|XNEmp|XPed|XCont|
    } 
    [0 ou 1]{ 
        ZC01|safra|ref|qTotMes|qTotAnt|qTotGer|vFor|vTotDed|vLiqFor| 
        [1 a 31]{ 
            ZC04|dia|qtde|
        } 
        [0 a 10]{    
            ZC10|xDed|vDed|
        }
    }
    
    
--------------------------------------------------------------------------------------------------------------------------------------------
*/
// teste:
#$nfe=new ConvertNfeNFePHP();
#$TXT_ORI=implode("\n", $nfe->campos_v200 );
##$TXT_ORI=implode("|\n", array_keys($nfe->campos_v200) );$TXT_ORI=str_replace("A|\nB|","A|2.00|ID|\nB|",$TXT_ORI);
#$TXT_ORI=str_replace('versao','2.00',$TXT_ORI);
#$TXT_ORI="A|2.00|ID|\nB|\nC|\n";
#
#$XML=$nfe->TXT2XML( $TXT_ORI,true );//$TXT_ORI,true );
#var_dump($XML['xml'][0]['xml']);
#$TXT=$nfe->XML2TXT( $XML['xml'][0]['xml'] );
#var_dump($TXT);

?>
