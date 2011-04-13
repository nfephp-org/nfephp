<?php
/**
 TXT para nota 2.0
 Régis Matos
 
 http://www.gestorcustom.com.br
 E-Mail/MSN = regismatos@douradosvirtual.com.br
 skype = regis_matos

**/
class NFeTXT2 {
	
	//função A
	public $versao;
	private $id; // Id é calculado automaticamente	
	// função B
    public $cUF;
	public $cNF;
	public $NatOp;
	public $intPag;
	public $mod;
	public $serie;
	public $nNF;
	public $dEmi;
	public $dSaiEnt;
	public $hSaiEnt;
    public $tpNF; 
	public $cMunFG;
	public $TpImp;
	public $TpEmis;
    private $cDV; // calculado automaticamente
	public $tpAmb;
	public $finNFe; 
	public $procEmi;
	public $VerProc; 
	public $dhCont; 
	public $xJust;	
	
	//função emitente
	public $emi = array();
	
	//função destinatário 
	public $dest = array();
	
	//função retirada
	public $retirada = array(); //Informar apenas quando for diferente do endereço do remetente.
	
	//função entrega
	public $entrega = array(); //Informar apenas quando for diferente do endereço do destinatário.
	
	//função produtos
	public $prod = array();
	
	// apenas para medicamentos
	public $prod_lote = array();
	
	//apenas armamento
	public $prod_armamento = array();
	
	//apenas para combustível 
	public $prod_combust = array();
	
	//Totais da nfe
	public $total = array();
	
	//PIS
	public $pis = array();
	
	//IPI
	public $ipi = array();
	
	//COFINS
	public $cofins = array();
	
	//COFINS ST
	public $cofinsst = array();
	
	//icms
	public $icms = array();
	
	//Dados para transportadora
	public $transp = array();
	
	public $fatura = array();
	
	public $parcela = array();
	
	public $inforAdd = array();
	
		
	
	public function validaTxt(){		
	
		//valida código do estado
		$codEstados = array("11", "12", "13", "14", "15", "16", "17", "21", "22", "23", "24", "25", "26", "27", "28", "29", "31", "32", "33", "35", "41", "42", "43", "50", "51", "52", "53"); 
		for ($i = 0; $i != count($codEstados); $i++){
			if ($this->cUF == $codEstados[$i]){
				$msg = "OK";
				break;
			}else{
				$msg = "Código do estado não é valido. ( cUF )";
			}
		}
		
		if (($msg != "OK")){
			return $msg;
			exit;			
		}		

		if (!preg_match( '/^[0-9]+$/', $this->cNF )){                        
			$msg  = "Campo ( cNF ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->cNF) > 8){
			$msg  = "Campo ( cNF ) excedeu  o tamanho permitido";
			return $msg;
			exit;			
		}
		
		if (strlen($this->NatOp) > 60){
			$msg  = "Campo ( NatOp ) excedeu  o tamanho permitido";
			return $msg;
			exit;			
		}
		if (strlen($this->NatOp) < 1){
			$msg  = "Campo ( NatOp ) é obrigatorio";
			return $msg;
			exit;			
		}

		if ($this->intPag != "0" && $this->intPag != "1" && $this->intPag != "2" ){
			$msg  = "Campo ( intPag ) não é valido";
			return $msg;
			exit;			
		}
		
		if ($this->mod != "55"){
			$msg  = "Campo ( mod ) não é valido";
			return $msg;
			exit;			
		}
		
		if (strlen($this->serie) > 3){
			$msg  = "Campo ( serie ) excedeu  o tamanho permitido";
			return $msg;
			exit;			
		}
		if (strlen($this->serie) < 1){
			$msg  = "Campo ( serie ) é obrigatória!";
			return $msg;
			exit;
		}
		if (!preg_match( '/^[0-9]+$/', $this->serie )){                        
			$msg  = "Campo ( serie ) com caracteres invalidas";
			return $msg;
			exit;
		}

		if (strlen($this->nNF) > 9){
			$msg  = "Campo ( serie ) excedeu  o tamanho permitido";
			return $msg;
			exit;
		}
		if (strlen($this->nNF) < 1){
			$msg  = "Campo ( nNF ) é obrigatorio";
			return $msg;
			exit;
		}

		if (!preg_match( '/^[0-9]+$/', $this->nNF )){                        
			$msg  = "Campo ( nNF ) com caracteres invalidas";
			return $msg;
			exit;
		}

		if ($this->validateDate( $this->dEmi, $format='YYYY-MM-DD') == False){
			$msg  = "Campo ( dEmi ) não é uma data avalida (aaaa-mm-dd)"; 
			return $msg;
			exit;
		} 
			
		if (strlen($this->dSaiEnt) > 0){
			if ($this->validateDate( $this->dSaiEnt, $format='YYYY-MM-DD') == False){
				$msg  = "Campo ( dSaiEnt ) não é uma data avalida (aaaa-mm-dd)"; 
				return $msg;
				exit;
			} 
		}
		if (strlen($this->hSaiEnt) > 0){
			if ($this->validateDate( $this->hSaiEnt, $format='YYYY-MM-DD') == False){
				$msg  = "Campo ( hSaiEnt ) não é uma data avalida (aaaa-mm-dd)"; 
				return $msg;
		    	exit;
			} 
		}
		
		if (!preg_match( '/^[0-1]+$/', $this->tpNF )){                        
			$msg  = "Campo ( tpNF ) com caracteres invalidas";
			return $msg;
			exit;
		}    	
		if (strlen($this->tpNF) > 1){
			$msg  = "Campo ( tpNF ) excedeu a quantidade de caracteres";		
			return $msg;
			exit;
		}

		if (!preg_match( '/^[0-9]+$/', $this->cMunFG )){                        
			$msg  = "Campo ( cMunFG ) com caracteres invalidas";
			return $msg;
			exit;
		}    	
		if (strlen($this->cMunFG) != 7){
			$msg  = "Campo ( cMunFG ) não confere a quantidade de caracteres - (7)";
			return $msg;
			exit;			
		}
		
		if (!preg_match( '/^[1-2]+$/', $this->TpImp )){                        
			$msg  = "Campo ( TpImp ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->TpImp) != 1){
			$msg  = "Campo ( TpImp ) não confere a quantidade de caracteres - (1)";	
			return $msg;
			exit;			
		}
		
		if (!preg_match( '/^[1-5]+$/', $this->TpEmis )){                        
			$msg  = "Campo ( TpEmis ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->TpEmis) != 1){
			$msg  = "Campo ( TpEmis ) não confere a quantidade de caracteres - (1)";
			return $msg;
			exit;			
		}

		if (!preg_match( '/^[1-2]+$/', $this->tpAmb )){                        
			$msg  = "Campo ( tpAmb ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->tpAmb) != 1){
			$msg  = "Campo ( tpAmb ) não confere a quantidade de caracteres - (1)";	
			return $msg;
			exit;			
		}		

		if (!preg_match( '/^[1-3]+$/', $this->finNFe )){                        
			$msg  = "Campo ( finNFe ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->finNFe) != 1){
			$msg  = "Campo ( finNFe ) não confere a quantidade de caracteres - (1)";		
			return $msg;
			exit;
		}		
		
		if (!preg_match( '/^[0-3]+$/', $this->procEmi )){                        
			$msg  = "Campo ( procEmi ) com caracteres invalidas";
			return $msg;
			exit;
		}
		if (strlen($this->procEmi) != 1){
			$msg  = "Campo ( procEmi ) não confere a quantidade de caracteres - (1)";
			return $msg;
			exit;			
		}		
		
		if (strlen($this->VerProc) < 1){
			$msg  = "Campo ( VerProc ) é obrigatorio";		
			return $msg;
			exit;
		}		
	
		return $msg;
	} // fim valida txt
	
	
	// monta id nfe		
	private function chave(){

		$cNF2   = str_pad($this->cNF, 8, '0',STR_PAD_LEFT);
		$nNF2   = str_pad($this->nNF, 9, '0',STR_PAD_LEFT);
		$serie2 = str_pad($this->serie, 3, '0',STR_PAD_LEFT);
		$aamm = substr($this->dEmi,2,2) . substr($this->dEmi,5,2);
	
		//monta chave sem digito	
		$this->id = $this->cUF . $aamm . $this->emi[CNPJ] . $this->mod . $serie2 . $nNF2 . $this->TpEmis . $cNF2;
		
		//cancula o digito verificador
		$multiplicadores = array(2,3,4,5,6,7,8,9);
		$i = 42;
		while ($i >= 0) {
			for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
				$soma_ponderada+= $this->id[$i] * $multiplicadores[$m];
				$i--;
			}
		}
		$resto = $soma_ponderada % 11;
		if ($resto == '0' || $resto == '1') {
			$this->cDV = 0;
			$this->id =  "NFe" . $this->id . 0;
		} else {
			$this->cDV = (11 - $resto);
			$this->id = "NFe" . $this->id . (11 - $resto);
	   }

	}//fim monta chave
	
	
	private function A(){
		$a  = "NOTAFISCAL|1|" .PHP_EOL;
		$a .= "A|" . $this->versao . "|" .  $this->id ."|". PHP_EOL;
		return $a;
    }
	
	private function B(){	
		$b = "B|{$this->cUF}|{$this->cNF}|{$this->NatOp}|{$this->intPag}|{$this->mod}|{$this->serie}|{$this->nNF}|{$this->dEmi}|{$this->dSaiEnt}|{$this->hSaiEnt}|{$this->tpNF}|{$this->cMunFG}|{$this->TpImp}|{$this->TpEmis}|{$this->cDV}|{$this->tpAmb}|{$this->finNFe}|{$this->procEmi}|{$this->VerProc}|{$this->this->dhCont}|{$this->xJust}|" . PHP_EOL;		
		return $b;
	}
	
	private function C(){

		$c  = "C|{$this->emi["XNome"]}|{$this->emi["XFant"]}|{$this->emi["IE"]}|{$this->emi["IEST"]}|{$this->emi["IM"]}|{$this->emi["CNAE"]}|{$this->emi["CRT"]}|" . PHP_EOL;
		
		if(!empty($this->emi[CNPJ]))
            $c .= "C02|{$this->emi["CNPJ"]}|" . PHP_EOL;
        
		if(!empty($this->emi[CPF]))
            $c .= "C02a|{$this->emi["CPF"]}|" . PHP_EOL;
		
		$c .= "C05|{$this->emi[XLgr]}|{$this->emi[Nro]}|{$this->emi[Cpl]}|{$this->emi[Bairro]}|{$this->emi[CMun]}|{$this->emi[XMun]}|{$this->emi[UF]}|{$this->emi[CEP]}|{$this->emi[cPais]}|{$this->emi[xPais]}|{$this->emi[fone]}|" . PHP_EOL;
					
		return $c;
	}
	
	    //Avulsa: Informações do fisco emitente, GRUPO DE USO EXCLUSIVO DO FISCO - *NÃO UTILIZAR*
    private function D(){
        return null;
    }
	
	private function E(){
		$e = "E|{$this->dest[xNome]}|{$this->dest[IE]}|{$this->dest[ISUF]}|{$this->dest[email]}|" . PHP_EOL;
		
		if(!empty($this->dest[CNPJ]))
            $e .= "E02|{$this->dest["CNPJ"]}|" . PHP_EOL;

        if(!empty($this->dest[CPF]))
            $e .= "E03|{$this->dest[CPF]}|" . PHP_EOL;
			
		$e .= "E05|{$this->dest[xLgr]}|{$this->dest[nro]}|{$this->dest[xCpl]}|{$this->dest[xBairro]}|{$this->dest[cMun]}|{$this->dest[xMun]}|{$this->dest[UF]}|{$this->dest[CEP]}|{$this->dest[cPais]}|{$this->dest[xPais]}|{$this->dest[fone]}|" . PHP_EOL;
		return $e;
	}
		
	private function F(){
		$f = "F|{$this->retirada[CNPJ]}|{$this->retirada[XLgr]}|{$this->retirada[Nro]}|{$this->retirada[XCpl]}|{$this->retirada[XBairro]}|{$this->retirada[CMun]}|{$this->retirada[XMun]}|{$this->retirada[UF]}|" . PHP_EOL;
	
		if(!empty($this->retirada[CNPJ]))
            $f .= "F02|{$this->retirada[CNPJ]}|" . PHP_EOL;

		if(!empty($this->retirada["CPF"]))
			$f .= "F02a|{$this->retirada[CPF]}|" . PHP_EOL;
		
		if(empty($this->retirada))
           return "";
		else	   
		   return $f;
	}	
	
	private function G(){
		
		$g = "G|{$this->entrega[CNPJ]}|{$this->entrega[XLgr]}|{$this->entrega[Nro]}|{$this->entrega[XCpl]}|{$this->entrega[XBairro]}|{$this->entrega[CMun]}|{$this->entrega[XMun]}|{$this->entrega[UF]}|" . PHP_EOL;
	
		if(!empty($this->entrega[CNPJ]))
            $g .= "G02|{$this->entrega[CNPJ]}|" . PHP_EOL;

		if(!empty($this->entrega["CPF"]))
			$g .= "G02a|{$this->entrega[CPF]}|" . PHP_EOL;
	
		if(empty($this->entrega))
           return "";
		else	   
		   return $g;
	}
	
	private function produtos(){
		
		$p = '';
		for ($i = 0; $i < count($this->prod); $i++){
			$ii = $i+1;
			$p .= "H|{$ii}|{$this->prod[$i][infAdProd]}|" . PHP_EOL; 
			$p .= "I|{$this->prod[$i][CProd]}|{$this->prod[$i][CEAN]}|{$this->prod[$i][XProd]}|{$this->prod[$i][NCM]}|{$this->prod[$i][EXTIPI]}|{$this->prod[$i][CFOP]}|{$this->prod[$i][UCom]}|{$this->prod[$i][QCom]}|{$this->prod[$i][VUnCom]}|{$this->prod[$i][VProd]}|{$this->prod[$i][CEANTrib]}|{$this->prod[$i][UTrib]}|{$this->prod[$i][QTrib]}|{$this->prod[$i][VUnTrib]}|{$this->prod[$i][VFrete]}|{$this->prod[$i][VSeg]}|{$this->prod[$i][VDesc]}|{$this->prod[$i][vOutro]}|{$this->prod[$i][indTot]}|{$this->prod[$i][xPed]}|{$this->prod[$i][nItemPed]}|" . PHP_EOL; 
			
			if(!empty($this->prod[$i][NDI])){
				$p .= "I18|{$this->prod[$i][NDI]}|{$this->prod[$i][DDI]}|{$this->prod[$i][XLocDesemb]}|{$this->prod[$i][UFDesemb]}|{$this->prod[$i][DDesemb]}|{$this->prod[$i][CExportador]}|" . PHP_EOL;
				$p .= "I25|{$this->prod[$i][NAdicao]}|{$this->prod[$i][NSeqAdic]}|{$this->prod[$i][CFabricante]}|{$this->prod[$i][VDescDI]}|" . PHP_EOL;
			}
			//somente se veiculo
			if(!empty($this->prod[$i][TpOp]))
				$p .= "J|{$this->prod[$i][TpOp]}|{$this->prod[$i][Chassi]}|{$this->prod[$i][CCor]}|{$this->prod[$i][XCor]}|{$this->prod[$i][Pot]}|{$this->prod[$i][cilin]}|{$this->prod[$i][pesoL]}|{$this->prod[$i][pesoB]}|{$this->prod[$i][NSerie]}|{$this->prod[$i][TpComb]}|{$this->prod[$i][NMotor]}|{$this->prod[$i][CMT]}|{$this->prod[$i][Dist]}|{$this->prod[$i][anoMod]}|{$this->prod[$i][anoFab]}|{$this->prod[$i][tpPint]}|{$this->prod[$i][tpVeic]}|{$this->prod[$i][espVeic]}|{$this->prod[$i][VIN]}|{$this->prod[$i][condVeic]}|{$this->prod[$i][cMod]}|{$this->prod[$i][cCorDENATRAN]}|{$this->prod[$i][lota]}|{$this->prod[$i][tpRest]}|" . PHP_EOL; 
		
			if(!empty($this->prod_lote)){
				for ($l = 0; $l < count($this->prod_lote); $l++){
					$p .= "K|{$this->prod_lote[$l][NLote]}|{$this->prod_lote[$l][QLote]}|{$this->prod_lote[$l][DFab]}|{$this->prod_lote[$l][DVal]}|{$this->prod_lote[$l][VPMC]}|" . PHP_EOL;
				}
			}
			
			//apenas armamento
			if(!empty($this->prod_armamento)){
				for ($f = 0; $f < count($this->prod_armamento); $f++){
					$p .= "L|{$this->prod_armamento[$f][TpArma]}|{$this->prod_armamento[$f][NSerie]}|{$this->prod_armamento[$f][NCano]}|{$this->prod_armamento[$f][Descr]}|" . PHP_EOL;
				}
			}
			
			//apenas para combustível
			if(!empty($this->prod_combust)){
				for ($t = 0; $t < count($this->prod_combust); $t++){
					$p .= "L01|{$this->prod_combust[$t][CProdANP]}|{$this->prod_combust[$t][CODIF]}|{$this->prod_combust[$t][QTemp]}|{$this->prod_combust[$t][UFCons]}|" . PHP_EOL;
					
					if(!empty($this->prod_combust[$t][QBCProd]))
						$p .= "L105|{$this->prod_combust[$t][QBCProd]}|{$this->prod_combust[$t][VAliqProd]}|{$this->prod_combust[$t][VCIDE]}|" . PHP_EOL;
					
				}
			}
			$p .= "M|" . PHP_EOL;;
			$p .= "N|" . PHP_EOL;;
			
			switch ($this->icms[$i][CST]) {
				case '00': //CST 00 TRIBUTADO INTEGRALMENTE
                    $p .= "N02|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|" . PHP_EOL; 
                    break;
				case '10': //CST 10 TRIBUTADO E COM COBRANCA DE ICMS POR SUBSTUICAO TRIBUTARIA
                    $p .= "N03|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|{$this->icms[$i][ModBCST]}|{$this->icms[$i][PMVAST]}|{$this->icms[$i][PRedBCST]}|{$this->icms[$i][VBCST]}|{$this->icms[$i][PICMSST]}|{$this->icms[$i][VICMSST]}|" . PHP_EOL; 
                    break;
				case '20': //CST 20 COM REDUCAO DE BASE DE CALCULO
                    $p .= "N04|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][PRedBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|" . PHP_EOL; 
                    break;
				case '30': //CST 30 ISENTA OU NAO TRIBUTADO E COM COBRANCA DO ICMS POR ST
                    $p .= "N05|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBCST]}|{$this->icms[$i][PMVAST]}|{$this->icms[$i][PRedBCST]}|{$this->icms[$i][VBCST]}|{$this->icms[$i][PICMSST]}|{$this->icms[$i][VICMSST]}|" . PHP_EOL; 
                    break;
				case '40': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                    $p .= "N06|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][vICMS]}|{$this->icms[$i][motDesICMS]}|" . PHP_EOL; 
                    break;
				case '41': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                    $p .= "N06|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][vICMS]}|{$this->icms[$i][motDesICMS]}|" . PHP_EOL; 
                    break;
				case '50': //CST 40-ISENTA 41-NAO TRIBUTADO E 50-SUSPENSAO
                    $p .= "N06|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][vICMS]}|{$this->icms[$i][motDesICMS]}|" . PHP_EOL; 
                    break;
				case '51': //CST 51 DIFERIMENTO - A EXIGENCIA DO PREECNCHIMENTO DAS INFORMAS DO ICMS DIFERIDO FICA A CRITERIO DE CADA UF
                    $p .= "N07|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][PRedBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|" . PHP_EOL; 
                    break;
				case '60': //CST 60 ICMS COBRADO ANTERIORMENTE POR ST
                    $p .= "N08|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][VBCST]}|{$this->icms[$i][VICMSST]}|" . PHP_EOL; 
                    break;
				case '70': //CST 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                    $p .= "N09|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][PRedBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|{$this->icms[$i][ModBCST]}|{$this->icms[$i][PMVAST]}|{$this->icms[$i][PRedBCST]}|{$this->icms[$i][VBCST]}|{$this->icms[$i][PICMSST]}|{$this->icms[$i][VICMSST]}|" . PHP_EOL; 
                    break;
				case '90': //CST - 90 Outros
                    $p .= "N10|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][PRedBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|{$this->icms[$i][ModBCST]}|{$this->icms[$i][PMVAST]}|{$this->icms[$i][PRedBCST]}|{$this->icms[$i][VBCST]}|{$this->icms[$i][PICMSST]}|{$this->icms[$i][VICMSST]}|" . PHP_EOL; 
                    break;
			
			
				case 'N10a': ////por enquanto sem saber o que fazer
                    $p .= "N10a|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][ModBC]}|{$this->icms[$i][VBC]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][VICMS]}|{$this->icms[$i][pBCOp]}|{$this->icms[$i][UFST]}|" . PHP_EOL; 
                    break;
			    
				case 'N10b': ////por enquanto sem saber o que fazer
                    $p .= "N10b|{$this->icms[$i][Orig]}|{$this->icms[$i][CST]}|{$this->icms[$i][vBCSTRet]}|{$this->icms[$i][vICMSSTRet]}|{$this->icms[$i][vBCSTDest]}|{$this->icms[$i][vICMSSTDest]}|" . PHP_EOL; 
                    break;
			
			
			}
			
			switch ($this->icms[$i][CSOSN]) {
				case '101': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=101 (v.2.0)
                    $p .= "N10c|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][pCredSN]}|{$this->icms[$i][vCredICMSSN]}|" . PHP_EOL; 
                    break;
				case '102': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=102, 103, 300 ou 400 (v.2.0)
                    $p .= "N10d|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|" . PHP_EOL; 
                    break;
				case '103': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=102, 103, 300 ou 400 (v.2.0)
                    $p .= "N10d|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|" . PHP_EOL; 
                    break;
				case '300': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=102, 103, 300 ou 400 (v.2.0)
                    $p .= "N10d|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|" . PHP_EOL; 
                    break;
				case '400': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=102, 103, 300 ou 400 (v.2.0)
                    $p .= "N10d|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|" . PHP_EOL; 
                    break;				
				case '201': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=201 (v.2.0)
                    $p .= "N10e|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][modBCST]}|{$this->icms[$ipMVAST]}|{$this->icms[$i][pRedBCST]}|{$this->icms[$i][vBCST]}|{$this->icms[$i][pICMSST]}|{$this->icms[$i][vICMSST]}|{$this->icms[$i][pCredSN]}|{$this->icms[$i][vCredICMSSN]}|" . PHP_EOL; 
                    break;
				case '202': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=202 ou 203 (v.2.0)
                    $p .= "N10f|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][modBCST]}|{$this->icms[$i][pMVAST]}|{$this->icms[$i][pRedBCST]}|{$this->icms[$i][vBCST]}|{$this->icms[$i][pICMSST]}|{$this->icms[$i][vICMSST]}|" . PHP_EOL; 
                    break;
				case '203': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=202 ou 203 (v.2.0)
                    $p .= "N10f|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][modBCST]}|{$this->icms[$i][pMVAST]}|{$this->icms[$i][pRedBCST]}|{$this->icms[$i][vBCST]}|{$this->icms[$i][pICMSST]}|{$this->icms[$i][vICMSST]}|" . PHP_EOL; 
                    break;				
				case '500': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=500 (v.2.0)
                    $p .= "N10g|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][modBCST]}|{$this->icms[$i][vBCSTRet]}|{$this->icms[$i][PICMS]}|{$this->icms[$i][vICMSSTRet]}|" . PHP_EOL; 
                    break;
				case '900': //Tributação do ICMS pelo SIMPLES NACIONAL e CSOSN=900 (v2.0)
                    $p .= "N10h|{$this->icms[$i][Orig]}|{$this->icms[$i][CSOSN]}|{$this->icms[$i][modBC]}|{$this->icms[$i][vBC]}|{$this->icms[$i][pRedBC]}|{$this->icms[$i][pICMS]}|{$this->icms[$i][vICMS]}|{$this->icms[$i][modBCST]}|{$this->icms[$i][pMVAST]}|{$this->icms[$i][pRedBCST]}|{$this->icms[$i][vBCST]}|{$this->icms[$i][pICMSST]}|{$this->icms[$i][vICMSST]}|{$this->icms[$i][pCredSN]}|{$this->icms[$i][vCredICMSSN]}|" . PHP_EOL; 
                    break;
			
			}
		
        	$p .= "O|{$this->ipi[$i][ClEnq]}|{$this->ipi[$i][CNPJProd]}|{$this->ipi[$i][CSelo]}|{$this->ipi[$i][QSelo]}|{$this->ipi[$i][CEnq]}|" . PHP_EOL; 
			if(!empty($this->ipi[$i][VIPI])){
				$p .= "O07|{$this->ipi[$i][CST]}|{$this->ipi[$i][VIPI]}|" . PHP_EOL; 
				if(!empty($this->ipi[$i][VBC])){
					$p .= "O10|{$this->ipi[$i][VBC]}|{$this->ipi[$i][PIPI]}|" . PHP_EOL; 
				}else{
					$p .= "O11|{$this->ipi[$i][QUnid]}|{$this->ipi[$i][VUnid]}|" . PHP_EOL; 
				}
					
			}else{
				$p .= "O08|{$this->ipi[$i][CST]}|" . PHP_EOL;
			}
		
			if(!empty($this->prod[$i][VII])){
						$p .= "P|{$this->prod[$i][VBC]}|{$this->prod[$i][VDespAdu]}|{$this->prod[$i][VII]}|{$this->prod[$i][VIOF]}|" . PHP_EOL; 
			}
		
			if(!empty($this->prod[$i][vISSQN])){
						$p .= "U|{$this->prod[$i][VBC]}|{$this->prod[$i][VAliq]}|{$this->prod[$i][VISSQN]}|{$this->prod[$i][CMunFG]}|{$this->prod[$i][CListServ]}|{$this->prod[$i][cSitTrib]}|" . PHP_EOL; 
			}
			
			$p .= "Q|" . PHP_EOL; 
			
			if ($this->pis[$i][CST] == "01" || $this->pis[$i][CST] == "02"){
            	$p .= "Q02|{$this->pis[$i][CST]}|{$this->pis[$i][VBC]}|{$this->pis[$i][PPIS]}|{$this->pis[$i][VPIS]}|" . PHP_EOL; 
			}
			
			if ($this->pis[$i][CST] == "03"){
            	$p .= "Q03|{$this->pis[$i][CST]}|{$this->pis[$i][QBCProd]}|{$this->pis[$i][VAliqProd]}|{$this->pis[$i][VPIS]}|" . PHP_EOL; 
			}
					
			if ($this->pis[$i][CST] == "04" || $this->pis[$i][CST] == "06" || $this->pis[$i][CST] == "07" || $this->pis[$i][CST] == "08" || $this->pis[$i][CST] == "09"){
            	$p .= "Q04|{$this->pis[$i][CST]}|" . PHP_EOL; 
			}
			
			if ($this->pis[$i][CST] == "99"){
            	$p .= "Q05|{$this->pis[$i][CST]}|Q05|{$this->pis[$i][VPIS]}|" . PHP_EOL; 
				$p .= "Q07|{$this->pis[$i][VBC]}|Q05|{$this->pis[$i][PPIS]}|" . PHP_EOL; 
				$p .= "Q05|{$this->pis[$i][QBCProd]}|Q05|{$this->pis[$i][VAliqProd]}|" . PHP_EOL; 
			}
			
			if(!empty($this->prod[$i][VII])){
			    $p .= "R|{$this->prod[$i][VPIS]}|" . PHP_EOL; 
				$p .= "R02|{$this->prod[$i][VBC]}|Q05|{$this->prod[$i][PPIS]}|" . PHP_EOL; 
				$p .= "R04|{$this->prod[$i][QBCProd]}|Q05|{$this->prod[$i][VAliqProd]}|" . PHP_EOL; 
			}
			
			$p .= "S|" . PHP_EOL; 
			if ($this->cofins[$i][CST] == "01" || $this->cofins[$i][CST] == "02"){
                $p .= "S02|{$this->cofins[$i][CST]}|{$this->cofins[$i][VBC]}|{$this->cofins[$i][PCOFINS]}|{$this->cofins[$i][VCOFINS]}|" . PHP_EOL; 
			}	
				
			
			if ($this->cofins[$i][CST] == "03"){
                $p .= "S03|{$this->cofins[$i][CST]}|{$this->cofins[$i][QBCProd]}|{$this->cofins[$i][VAliqProd]}|{$this->cofins[$i][VCOFINS]}|" . PHP_EOL; 
			}	
			
			if ($this->cofins[$i][CST] == "04" || $this->cofins[$i][CST] == "06"|| $this->cofins[$i][CST] == "07"|| $this->cofins[$i][CST] == "08" || $this->cofins[$i][CST] == "09"){
                $p .= "S04|{$this->cofins[$i][CST]}|" . PHP_EOL; 
			}	
			
			if ($this->cofins[$i][CST] == "99"){
                $p .= "S05|{$this->cofins[$i][CST]}|{$this->cofins[$i][VCOFINS]}|" . PHP_EOL; 
			    
				if(empty($this->cofins[$i][QBCProd]))
					$p .= "S07|{$this->cofins[$i][VBC]}|{$this->cofins[$i][PCOFINS]}|" . PHP_EOL; 
				else
					$p .= "S09|{$this->cofins[$i][VCOFINS]}|{$this->cofins[$i][VAliqProd]}|" . PHP_EOL; 
			}	
			
			if(!empty($this->cofinsst[$i][VCOFINS])){
				$p .= "T|{$this->cofinsst[$i][VCOFINS]}|" . PHP_EOL; 
			
				if(empty($this->cofinsst[$i][QBCProd]))
					$p .= "T02|{$this->cofinsst[$i][VBC]}|{$this->cofinsst[$i][PCOFINS]}|" . PHP_EOL; 
				else
					$p .= "T04|{$this->cofinsst[$i][QBCProd]}|{$this->cofinsst[$i][VAliqProd]}|" . PHP_EOL; 
			
			}
		
		}//fim for
			
		return $p;
	}//fim produtos
	
	private function w(){	
		$w .= "W|" . PHP_EOL; //totais da nfe
		$w .= "W02|{$this->total[vBC]}|{$this->total[vICMS]}|{$this->total[vBCST]}|{$this->total[vST]}|{$this->total[vProd]}|{$this->total[vFrete]}|{$this->total[vSeg]}|{$this->total[vDesc]}|{$this->total[vII]}|{$this->total[vIPI]}|{$this->total[vPIS]}|{$this->total[vCOFINS]}|{$this->total[vOutro]}|{$this->total[vNF]}|" . PHP_EOL; 
				
		if(!empty($this->total[VServ]))
			$w .= "W17|{$this->total[VServ]}|{$this->total[VBC]}|{$this->total[VISS]}|{$this->total[VISS]}|{$this->total[VCOFINS]}|" . PHP_EOL; 
		
		if ($this->total[VRetPIS] > 0 || $this->total[VRetCOFINS] > 0 || $this->total[VRetCSLL] > 0 || $this->total[VBCIRRF] > 0 || $this->total[VIRRF] > 0 || $this->total[VBCRetPrev] > 0 || $this->total[VRetPrev] > 0 )
			$w .= "W23|{$this->total[VRetPIS]}|{$this->total[VRetCOFINS]}|{$this->total[VRetCSLL]}|{$this->total[VBCIRRF]}|{$this->total[VIRRF]}|{$this->total[VBCRetPrev]}|{$this->total[VRetPrev]}|" . PHP_EOL; 
		return $w;
	}
	
	//Transporte
	private function x(){	
		$x .= "X|{$this->transp[ModFrete]}|" . PHP_EOL; 
		$x .= "X03|{$this->transp[XNome]}|{$this->transp[IE]}|{$this->transp[XEnder]}|{$this->transp[UF]}|{$this->transp[XMun]}|" . PHP_EOL; 

		if(!empty($this->transp[CNPJ]))
			$x .= "X04|{$this->transp[CNPJ]}|" . PHP_EOL;		
		
		if(!empty($this->transp[CPF]))
			$x .= "X05|{$this->transp[CPF]}|" . PHP_EOL; 
		
		if(!empty($this->transp[VServ]))
			$x .= "X11|{$this->transp[VServ]}|{$this->transp[VBCRet]}|{$this->transp[PICMSRet]}|{$this->transp[VICMSRet]}|{$this->transp[CFOP]}|{$this->transp[CMunFG]}|" . PHP_EOL; 

		if(!empty($this->transp[Placa]))
			$x .= "X18|{$this->transp[Placa]}|{$this->transp[UF]}|{$this->transp[RNTC]}|" . PHP_EOL; 
		
		$x .= "X26|{$this->transp[QVol]}|{$this->transp[Esp]}|{$this->transp[Marca]}|{$this->transp[NVol]}|{$this->transp[PesoL]}|{$this->transp[PesoB]}|" . PHP_EOL; 
		
		if(!empty($this->transp[NLacre]))
			$x .= "X33|{$this->transp[NLacre]}|" . PHP_EOL; 		
		
		return $x;
	}	
		
	private function y(){	
		$y  = "Y|" . PHP_EOL; 	
		if(!empty($this->fatura[NFat])){
		
			$y .= "Y02|{$this->fatura[NFat]}|{$this->fatura[VOrig]}|{$this->fatura[VDesc]}|{$this->fatura[VLiq]}|" . PHP_EOL; 		
		
			//parcelas
			for ($i = 0; $i < count($this->parcela); $i++){
				if(!empty($this->parcela[$i][NDup]))
					$y .= "Y07|{$this->parcela[$i][NDup]}|{$this->parcela[$i][DVenc]}|{$this->parcela[$i][VDup]}|" . PHP_EOL; 		
			}
		}
		return $y;
	}
		
	private function Z(){	
		$z 	= "Z|{$this->infoAdd[InfAdFisco]}|{$this->fatura[InfCpl]}|" . PHP_EOL; 		
			
		return $z;
	}
		
	public function montaTxt(){
        return $this->chave() .  $this->A() . $this->B() . $this->C() . $this->e() . $this->f() . $this->g() . $this->produtos() . $this->w() . $this->x() . $this->y() . $this->z();
    }
		
	public function geraArquivo($path){
	    $temp = substr($path,-1);
		if ($temp != '/' )
			$path .= '/';
		
		$path .= $this->id . "-nfe.txt";
		
		$txt = $this->montaTxt();
		
		$fp = fopen($path, "w");
		fwrite($fp,  $txt);
		fclose($fp);
	}
	
//função para validar data
function validateDate( $date, $format='YYYY-MM-DD')
    {
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
            list( $y, $m, $d ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
            list( $y, $d, $m ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
            list( $d, $m, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
            list( $m, $d, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYYMMDD':
            $y = substr( $date, 0, 4 );
            $m = substr( $date, 4, 2 );
            $d = substr( $date, 6, 2 );
            break;

            case 'YYYYDDMM':
            $y = substr( $date, 0, 4 );
            $d = substr( $date, 4, 2 );
            $m = substr( $date, 6, 2 );
            break;

            default:
            throw new Exception( "Invalid Date Format" );
        }
        return checkdate( $m, $d, $y );
    }
	
}

?>

