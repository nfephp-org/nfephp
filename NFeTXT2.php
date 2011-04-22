<?php
/**
 TXT para nota 2.0
 Régis Matos
 Edwin Schissato 


 E-Mail/MSN = regismatos@douradosvirtual.com.br
 skype = regis_matos
 *
**/

class NFeTXT2 {

    //função A
    public $versao;
    private $id; // Id é calculado automaticamente
    // função B
    public $cUF;
    public $cNF;
    public $NatOp;
    public $indPag;
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
	private $msg;
	
		
	
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
		
		//valida sigla estado
	    $codEstados = array("RO", "AC", "AM", "RR", "PA", "AP", "TO", "MA", "PI", "CE", "RN", "PB", "PE", "AL", "SE", "BA", "MG", "ES", "RJ", "SP", "PR", "SC", "RS", "MS", "MT", "GO", "DF");
        for ($i = 0; $i != count($codEstados); $i++){
                if ($this->emi[UF] == $codEstados[$i]){
                        $msg = "OK";
                        break;
                }else{
                        $msg = "Sigla do estado não é valido. ( emi[UF] )";
                }
        }
		
        if (($msg != "OK")){
                return $msg;
                exit;
        }
		
		//valida sigla estado
	    $codEstados = array("RO", "AC", "AM", "RR", "PA", "AP", "TO", "MA", "PI", "CE", "RN", "PB", "PE", "AL", "SE", "BA", "MG", "ES", "RJ", "SP", "PR", "SC", "RS", "MS", "MT", "GO", "DF");
        for ($i = 0; $i != count($codEstados); $i++){
                if ($this->dest[UF] == $codEstados[$i]){
                        $msg = "OK";
                        break;
                }else{
                        $msg = "Sigla do estado não é valido. ( dest[UF] )";
                }
        }
		
        if (($msg != "OK")){
                return $msg;
                exit;
        }
		
		
		
		
		
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->cNF, 1, 8, "[cNF]")){
		    return $this->msg;
            exit;		
		}		
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->cNF, "0123456789", "[cNF]")){
		    return $this->msg;
            exit;		
		}
       
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->NatOp, 1, 60, "[NatOp]")){
		    return $this->msg;
            exit;		
		}
		if (!$this->caracterInvalidas($this->NatOp, "|", "[NatOp]")){
		    return $this->msg;
            exit;		
		}
		   


		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->indPag, 1, 1, "[intPag]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->indPag, "012", "[indPag]")){
		    return $this->msg;
            exit;		
		}
		

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->mod, 2, 2, "[mod]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->mod, "5", "[mod]")){
		    return $this->msg;
            exit;		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->serie, 1, 3, "[serie]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->serie, "0123456789", "[serie]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->nNF, 1, 9, "[nNF]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->nNF, "0123456789", "[nNF]")){
		    return $this->msg;
            exit;		
		}

        if ($this->validateDate( $this->dEmi, $format='YYYY-MM-DD') == False){
                $this->msg  = "Campo ( dEmi ) não é uma data avalida (aaaa-mm-dd)";
                return $this->msg;
                exit;
        }

        if (strlen($this->dSaiEnt) > 0){
                if ($this->validateDate( $this->dSaiEnt, $format='YYYY-MM-DD') == False){
                        $this->msg  = "Campo ( dSaiEnt ) não é uma data avalida (aaaa-mm-dd)";
                        return $this->msg;
                        exit;
                }
        }
        if (strlen($this->hSaiEnt) > 0){
                if ($this->validateDate( $this->hSaiEnt, $format='YYYY-MM-DD') == False){
                        $this->msg  = "Campo ( hSaiEnt ) não é uma data avalida (aaaa-mm-dd)";
                        return $this->msg;
                exit;
                }
        }

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->tpNF, 1, 1, "[tpNF]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->tpNF, "01", "[tpNF]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->cMunFG, 7, 7, "[tpNF]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->cMunFG, "0123456789", "[cMunFG]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->TpImp, 1, 1, "[TpImp]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->TpImp, "12", "[TpImp]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->TpEmis, 1, 1, "[TpEmis]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->TpEmis, "12345", "[TpEmis]")){
		    return $this->msg;
            exit;		
		}
 
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->tpAmb, 1, 1, "[tpAmb]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->tpAmb, "12", "[tpAmb]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->finNFe, 1, 1, "[finNFe]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->finNFe, "123", "[finNFe]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->procEmi, 1, 1, "[procEmi]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->procEmi, "123", "[procEmi]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->VerProc, 1, 20, "[VerProc]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->VerProc, "|", "[VerProc]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[XNome], 2, 60, "emi[XNome]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[XNome], "|", "emi[XNome]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[XFant], 0, 60, "emi[XFant]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[XFant], "|", "emi[XFant]")){
		    return $this->msg;
            exit;		
		}
		
		if (strlen($this->emi[IE]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->emi[IE], 1, 14, "emi[IE]")){
				return $this->msg;
				exit;		
			}
			//caracteres invalido (valor/invalido/referencia mensagem)
			if (!$this->caracterInvalidas($this->emi[IE], "|", "emi[IE]")){
				return $this->msg;
				exit;		
			}
			
			if (!$this->CheckIE($this->emi[IE], $this->emi[UF])){
				$this->msg = "Campo ( emi[IE] ) não é uma Inscrição Estadual Valida.";
				return $this->msg;
				exit;		
			}			
			
		}		
 		
		if (!$this->validaCNPJ($this->emi[CNPJ])){
			$this->msg  = "Campo ( emi[CNPJ] ) não é valido)";
			return $this->msg;
			exit;
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[xLgr], 2, 60, "emi[xLgr]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[xLgr], "|", "emi[xLgr]")){
		    return $this->msg;
            exit;		
		}
		
    	//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[nro], 1, 60, "emi[nro]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[nro], "|", "emi[nro]")){
		    return $this->msg;
            exit;		
		}
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[Cpl], 0, 60, "emi[Cpl]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[Cpl], "|", "emi[Cpl]")){
		    return $this->msg;
            exit;		
		}
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[Bairro], 2, 60, "emi[Bairro]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[Bairro], "|", "emi[Bairro]")){
		    return $this->msg;
            exit;		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[CMun], 7, 7, "emi[CMun]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->emi[CMun], "0123456789", "emi[CMun]")){
		    return $this->msg;
            exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[XMun], 2, 60, "emi[XMun]")){
		    return $this->msg;
            exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->emi[XMun], "|", "emi[XMun]")){
		    return $this->msg;
            exit;		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->emi[CEP], 8, 8, "emi[CEP]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->emi[CEP], "0123456789", "emi[CEP]")){
		    return $this->msg;
            exit;		
		}
			
		if (strlen($this->emi[cPais]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->emi[cPais], 4, 4, "emi[cPais]")){
				return $this->msg;
				exit;		
			}
			// Caracteres permitidas (valor/campos permitido/referencia mensagem)
			if (!$this->caracterValidas($this->emi[cPais], "0123456789", "emi[cPais]")){
				return $this->msg;
				exit;		
			}
		}
	
		if (strlen($this->emi[xPais]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->emi[xPais], 1, 60, "emi[xPais]")){
				return $this->msg;
				exit;		
			}
			//caracteres invalido (valor/invalido/referencia mensagem)
			if (!$this->caracterInvalidas($this->emi[xPais], "|", "emi[xPais]")){
				return $this->msg;
				exit;		
			}
		}

		if (strlen($this->emi[fone]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->emi[fone], 6, 14, "emi[fone]")){
				return $this->msg;
				exit;		
			}
			// Caracteres permitidas (valor/campos permitido/referencia mensagem)
			if (!$this->caracterValidas($this->emi[fone], "0123456789", "emi[fone]")){
				return $this->msg;
				exit;		
			}			
		}		
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[xNome], 2, 60, "dest[xNome]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[xNome], "|", "dest[xNome]")){
			return $this->msg;
			exit;		
		}

		if (strlen($this->dest[IE]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->dest[IE], 1, 14, "dest[IE]")){
				return $this->msg;
				exit;		
			}
			//caracteres invalido (valor/invalido/referencia mensagem)
			if (!$this->caracterInvalidas($this->dest[IE], "|", "dest[IE]")){
				return $this->msg;
				exit;		
			}
			
			if (!$this->CheckIE($this->dest[IE], $this->dest[UF])){
				$this->msg = "Campo ( dest[IE] ) não é uma Inscrição Estadual Valida.";
				return $this->msg;
				exit;		
			}			
			
		}

		if (strlen($this->dest[ISUF]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->dest[ISUF], 8, 9, "dest[ISUF]")){
				return $this->msg;
				exit;		
			}
			//caracteres invalido (valor/invalido/referencia mensagem)
			if (!$this->caracterInvalidas($this->dest[ISUF], "|", "dest[ISUF]")){
				return $this->msg;
				exit;		
			}
		}
		
		if (strlen($this->dest[email]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->dest[email], 1, 60, "dest[email]")){
				return $this->msg;
				exit;		
			}
			
			//caracteres invalido (valor/invalido/referencia mensagem)
			if (!$this->caracterInvalidas($this->dest[email], "|", "dest[email]")){
				return $this->msg;
				exit;		
			}
			
			if (!$this->validaEMail($this->dest[email], "dest[email]")){
				return $this->msg;
				exit;		
			}		
		}		
		

		if (strlen($this->dest[CNPJ]) >> 0 && strlen($this->dest[CPF]) > 0){
			$this->msg  = "Selecione entre ( dest[CNPJ] ) ou ( dest[CPF] )";
			return $this->msg;
			exit;
		}

		if (!$this->validaCNPJ($this->dest[CNPJ])){
			$this->msg  = "Campo ( dest[CNPJ] ) = ( {$this->dest[CNPJ]} )não é um cnpj valido";
			return $this->msg;
			exit;
		}
		if (strlen($this->dest[CPF]) > 0){
			if(!$this->validaCPF($this->dest[CPF])){
				$this->msg  = "Campo ( dest[CPF] ) = ( {$this->dest[CPF]} ) não é um CPF valido";
				return $this->msg;
				exit;
			}
		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[xLgr], 2, 60, "dest[xLgr]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[xLgr], "|", "dest[xLgr]")){
			return $this->msg;
			exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[nro], 2, 60, "dest[nro]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[nro], "|", "dest[nro]")){
			return $this->msg;
			exit;		
		}
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[xCpl], 0, 60, "dest[xCpl]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[xCpl], "|", "dest[xCpl]")){
			return $this->msg;
			exit;		
		}

		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[xBairro], 1, 60, "dest[xBairro]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[xBairro], "|", "dest[xBairro]")){
			return $this->msg;
			exit;		
		}
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[cMun], 7, 7, "dest[cMun]")){
			return $this->msg;
			exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
		if (!$this->caracterValidas($this->dest[cMun], "0123456789", "dest[cMun]")){
			return $this->msg;
			exit;		
		}			
	
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[CEP], 8, 8, "dest[CEP]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->dest[CEP], "0123456789", "dest[CEP]")){
		    return $this->msg;
            exit;		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[cPais], 2, 4, "dest[cPais]")){
		    return $this->msg;
            exit;		
		}
		// Caracteres permitidas (valor/campos permitido/referencia mensagem)
    	if (!$this->caracterValidas($this->dest[cPais], "0123456789", "dest[cPais]")){
		    return $this->msg;
            exit;		
		}
		
		//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
		if (!$this->validaMinimoMaximo($this->dest[xPais], 2, 60, "dest[xPais]")){
			return $this->msg;
			exit;		
		}
		//caracteres invalido (valor/invalido/referencia mensagem)
		if (!$this->caracterInvalidas($this->dest[xPais], "|", "dest[xPais]")){
			return $this->msg;
			exit;		
		}
	
		if (strlen($this->dest[fone]) > 0){
			//valida minimo e maximo (Valor/Minimo/Maximo/Referencia)
			if (!$this->validaMinimoMaximo($this->dest[fone], 6, 14, "emi[fone]")){
				return $this->msg;
				exit;		
			}
			// Caracteres permitidas (valor/campos permitido/referencia mensagem)
			if (!$this->caracterValidas($this->dest[fone], "0123456789", "emi[fone]")){
				return $this->msg;
				exit;		
			}			
		}		
	

		return $this->msg;
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
		$b = "B|{$this->cUF}|{$this->cNF}|{$this->NatOp}|{$this->indPag}|{$this->mod}|{$this->serie}|{$this->nNF}|{$this->dEmi}|{$this->dSaiEnt}|{$this->hSaiEnt}|{$this->tpNF}|{$this->cMunFG}|{$this->TpImp}|{$this->TpEmis}|{$this->cDV}|{$this->tpAmb}|{$this->finNFe}|{$this->procEmi}|{$this->VerProc}|{$this->this->dhCont}|{$this->xJust}|" . PHP_EOL;		
		return $b;
	}
	
	private function C(){

		$c  = "C|{$this->emi["XNome"]}|{$this->emi["XFant"]}|{$this->emi["IE"]}|{$this->emi["IEST"]}|{$this->emi["IM"]}|{$this->emi["CNAE"]}|{$this->emi["CRT"]}|" . PHP_EOL;
		
		if(!empty($this->emi[CNPJ]))
            $c .= "C02|{$this->emi["CNPJ"]}|" . PHP_EOL;
        
		if(!empty($this->emi[CPF]))
            $c .= "C02a|{$this->emi["CPF"]}|" . PHP_EOL;
		
		$c .= "C05|{$this->emi[xLgr]}|{$this->emi[nro]}|{$this->emi[Cpl]}|{$this->emi[Bairro]}|{$this->emi[CMun]}|{$this->emi[XMun]}|{$this->emi[UF]}|{$this->emi[CEP]}|{$this->emi[cPais]}|{$this->emi[xPais]}|{$this->emi[fone]}|" . PHP_EOL;
					
		return $c;
	}
	
	    //Avulsa: Informações do fisco emitente, GRUPO DE USO EXCLUSIVO DO FISCO - *NÃO UTILIZAR*
    private function D(){
        return null;
    }
	
	private function E(){
		$e = "E|{$this->dest[xNome]}|{$this->dest[IE]}|{$this->dest[ISUF]}|{$this->dest[email]}|" . PHP_EOL;
		
		if(!empty($this->dest[CNPJ]))
            $e .= "E02|{$this->dest[CNPJ]}|" . PHP_EOL;

        if(!empty($this->dest[CPF]))
            $e .= "E03|{$this->dest[CPF]}|" . PHP_EOL;
			
		$e .= "E05|{$this->dest[xLgr]}|{$this->dest[nro]}|{$this->dest[xCpl]}|{$this->dest[xBairro]}|{$this->dest[cMun]}|{$this->dest[xMun]}|{$this->dest[UF]}|{$this->dest[CEP]}|{$this->dest[cPais]}|{$this->dest[xPais]}|{$this->dest[fone]}|" . PHP_EOL;
		return $e;
	}
		
	private function F(){
		$f = "F|{$this->retirada[xLgr]}|{$this->retirada[nro]}|{$this->retirada[XCpl]}|{$this->retirada[XBairro]}|{$this->retirada[CMun]}|{$this->retirada[XMun]}|{$this->retirada[UF]}|" . PHP_EOL;
	
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
		$g = "G|{$this->entrega[xLgr]}|{$this->entrega[nro]}|{$this->entrega[XCpl]}|{$this->entrega[XBairro]}|{$this->entrega[CMun]}|{$this->entrega[XMun]}|{$this->entrega[UF]}|" . PHP_EOL;
	
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

	//caracteres validas
	function caracterValidas($valor, $validos, $texto){
		for ($i = 0; $i < strlen($valor); $i++){				
			for($ii = 0; $ii < strlen($validos); $ii++){
				if ($valor{$i} != $validos{$ii} ){
					$this->msg  = "Campo ( {$texto} ) = ( {$valor} ) contém caracter inválida - ( {$valor{$i}} )";
												
				}else{
					$this->msg = "OK";						
					break;
				}					
			}			
			if ($this->msg != "OK"){
					return False;
                    exit;	
			}
		}	
		return true;	
	}
	
	//caracteres invalidas
	function caracterInvalidas($valor, $invalida, $texto){
        for ($i = 0; $i < strlen($valor); $i++){
            for($ii = 0; $ii < strlen($invalida); $ii++){
                if ($valor{$i} == $invalida{$ii} ){
                    $this->msg = "Campo ( {$texto} ) = ( {$valor}) contém caracter inválida = ( {$invalida{$ii}} )";
					return False;
                    exit;					
                }
				
            }
        }		
		return True;
	}
	
	//valida minimo e maximo
	function validaMinimoMaximo($valor, $min, $max, $texto){
		if (strlen($valor) > $max){
                $this->msg  = "Campo ( {$texto} ) = ( {$valor} ) excedeu  o tamanho permitido ( {$max} )";
                return false;                
        }else		
        if (strlen($valor) < $min){
                $this->msg  = "Campo ( {$texto} ) = ( {$valor} ) não confere a quantidade de caracteres minima ( {$min} )";
                return false;                
        }else{
			return true;		
		}
	}
	
	function validaEMail($mail, $texto) { 
		if($mail !== "") { 
			if(ereg("^[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,5}$", $mail)) { 
				return true; 
			} else { 
				$this->msg = "Campo ( {$texto} ) não é um E-Mail valido"; 
				return $this->msg;
			}			
		} else { 
				$this->msg = "Campo ( {$texto} ) não é um E-Mail valido"; 
				return $this->msg;
		} 
	} 
	
	//valida cpf
function validaCPF($cpf)
{	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}
	
	
	
	//valida cnpj
function validaCNPJ($cnpj) {
   
      if (strlen($cnpj) <> 14)
         return false; 

      $soma = 0;
      
      $soma += ($cnpj[0] * 5);
      $soma += ($cnpj[1] * 4);
      $soma += ($cnpj[2] * 3);
      $soma += ($cnpj[3] * 2);
      $soma += ($cnpj[4] * 9); 
      $soma += ($cnpj[5] * 8);
      $soma += ($cnpj[6] * 7);
      $soma += ($cnpj[7] * 6);
      $soma += ($cnpj[8] * 5);
      $soma += ($cnpj[9] * 4);
      $soma += ($cnpj[10] * 3);
      $soma += ($cnpj[11] * 2); 

      $d1 = $soma % 11; 
      $d1 = $d1 < 2 ? 0 : 11 - $d1; 

      $soma = 0;
      $soma += ($cnpj[0] * 6); 
      $soma += ($cnpj[1] * 5);
      $soma += ($cnpj[2] * 4);
      $soma += ($cnpj[3] * 3);
      $soma += ($cnpj[4] * 2);
      $soma += ($cnpj[5] * 9);
      $soma += ($cnpj[6] * 8);
      $soma += ($cnpj[7] * 7);
      $soma += ($cnpj[8] * 6);
      $soma += ($cnpj[9] * 5);
      $soma += ($cnpj[10] * 4);
      $soma += ($cnpj[11] * 3);
      $soma += ($cnpj[12] * 2); 
      
      $d2 = $soma % 11; 
      $d2 = $d2 < 2 ? 0 : 11 - $d2; 
      
      if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
         return true;
      }
      else {
         return false;
      }
   } 
   
	###################################################Validação de inscrições estaduais a seguir##############################################
	// Contribuição do ### Edwin Schissato ### 
	//Acre
	function CheckIEAC($ie){
		if (strlen($ie) != 13){return 0;}
		else{
			if(substr($ie, 0, 2) != '01'){return 0;}
			else{
				$b = 4;
				$soma = 0;
				for ($i=0;$i<=10;$i++){
					$soma += $ie[$i] * $b;
					$b--;
					if($b == 1){$b = 9;}
				}
				$dig = 11 - ($soma % 11);
				if($dig >= 10){$dig = 0;}
				if( !($dig == $ie[11]) ){return 0;}
				else{
					$b = 5;
					$soma = 0;
					for($i=0;$i<=11;$i++){
						$soma += $ie[$i] * $b;
						$b--;
						if($b == 1){$b = 9;}
					}
					$dig = 11 - ($soma % 11);
					if($dig >= 10){$dig = 0;}

					return ($dig == $ie[12]);
				}
			}
		}
	}

	// Alagoas
	function CheckIEAL($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != '24'){return 0;}
			else{
				$b = 9;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;

				}
				$soma *= 10;
				$dig = $soma - ( ( (int)($soma / 11) ) * 11 );
				if($dig == 10){$dig = 0;}

				return ($dig == $ie[8]);
			}
		}
	}

	//Amazonas
	function CheckIEAM($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			if($soma <= 11){$dig = 11 - $soma;}
			else{
				$r = $soma % 11;
				if($r <= 1){$dig = 0;}
				else{$dig = 11 - $r;}
			}

			return ($dig == $ie[8]);
		}
	}

	//Amapá
	function CheckIEAP($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != '03'){return 0;}
			else{
				$i = substr($ie, 0, -1);
				if( ($i >= 3000001) && ($i <= 3017000) ){$p = 5; $d = 0;}
				elseif( ($i >= 3017001) && ($i <= 3019022) ){$p = 9; $d = 1;}
				elseif ($i >= 3019023){$p = 0; $d = 0;}

				$b = 9;
				$soma = $p;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$dig = 11 - ($soma % 11);
				if($dig == 10){$dig = 0;}
				elseif($dig == 11){$dig = $d;}

				return ($dig == $ie[8]);
			}
		}
	}

	//Bahia
	function CheckIEBA($ie){
		if (strlen($ie) != 8){return 0;}
		else{

			$arr1 = array('0','1','2','3','4','5','8');
			$arr2 = array('6','7','9');

			$i = substr($ie, 0, 1);

			if(in_array($i, $arr1)){$modulo = 10;}
			elseif(in_array($i, $arr2)){$modulo = 11;}

			$b = 7;
			$soma = 0;
			for($i=0;$i<=5;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}

			$i = $soma % $modulo;
			if ($modulo == 10){
				if ($i == 0) { $dig = 0; } else { $dig = $modulo - $i; }
			}else{
				if ($i <= 1) { $dig = 0; } else { $dig = $modulo - $i; }
			}
			if( !($dig == $ie[7]) ){return 0;}
			else{
				$b = 8;
				$soma = 0;
				for($i=0;$i<=5;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$soma += $ie[7] * 2;
				$i = $soma % $modulo;
				if ($modulo == 10){
					if ($i == 0) { $dig = 0; } else { $dig = $modulo - $i; }
				}else{
					if ($i <= 1) { $dig = 0; } else { $dig = $modulo - $i; }
				}

				return ($dig == $ie[6]);
			}
		}
	}

	//Ceará
	function CheckIECE($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$dig = 11 - ($soma % 11);

			if ($dig >= 10){$dig = 0;}

			return ($dig == $ie[8]);
		}
	}

	// Distrito Federal
	function CheckIEDF($ie){
		if (strlen($ie) != 13){return 0;}
		else{
			if( substr($ie, 0, 2) != '07' ){return 0;}
			else{
				$b = 4;
				$soma = 0;
				for ($i=0;$i<=10;$i++){
					$soma += $ie[$i] * $b;
					$b--;
					if($b == 1){$b = 9;}
				}
				$dig = 11 - ($soma % 11);
				if($dig >= 10){$dig = 0;}

				if( !($dig == $ie[11]) ){return 0;}
				else{
					$b = 5;
					$soma = 0;
					for($i=0;$i<=11;$i++){
						$soma += $ie[$i] * $b;
						$b--;
						if($b == 1){$b = 9;}
					}
					$dig = 11 - ($soma % 11);
					if($dig >= 10){$dig = 0;}

					return ($dig == $ie[12]);
				}
			}
		}
	}

	//Espirito Santo
	function CheckIEES($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$i = $soma % 11;
			if ($i < 2){$dig = 0;}
			else{$dig = 11 - $i;}

			return ($dig == $ie[8]);
		}
	}

	//Goias
	function CheckIEGO($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$s = substr($ie, 0, 2);

			if( !( ($s == 10) || ($s == 11) || ($s == 15) ) ){return 0;}
			else{
				$n = substr($ie, 0, 7);

				if($n == 11094402){
					if($ie[8] != 0){
						if($ie[8] != 1){
							return 0;
						}else{return 1;}
					}else{return 1;}
				}else{
					$b = 9;
					$soma = 0;
					for($i=0;$i<=7;$i++){
						$soma += $ie[$i] * $b;
						$b--;
					}
					$i = $soma % 11;
					if ($i == 0){$dig = 0;}
					else{
						if($i == 1){
							if(($n >= 10103105) && ($n <= 10119997)){$dig = 1;}
							else{$dig = 0;}
						}else{$dig = 11 - $i;}
					}

					return ($dig == $ie[8]);
				}
			}
		}
	}

	// Maranhão
	function CheckIEMA($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != 12){return 0;}
			else{
				$b = 9;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$i = $soma % 11;
				if ($i <= 1){$dig = 0;}
				else{$dig = 11 - $i;}

				return ($dig == $ie[8]);
			}
		}
	}

	// Mato Grosso
	function CheckIEMT($ie){
		if (strlen($ie) != 11){return 0;}
		else{
			$b = 3;
			$soma = 0;
			for($i=0;$i<=9;$i++){
				$soma += $ie[$i] * $b;
				$b--;
				if($b == 1){$b = 9;}
			}
			$i = $soma % 11;
			if ($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}

			return ($dig == $ie[10]);
		}
	}

	// Mato Grosso do Sul
	function CheckIEMS($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != 28){return 0;}
			else{
				$b = 9;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$i = $soma % 11;
				if ($i == 0){$dig = 0;}
				else{$dig = 11 - $i;}

				if($dig > 9){$dig = 0;}

				return ($dig == $ie[8]);
			}
		}
	}

	//Minas Gerais
	function CheckIEMG($ie){
		if (strlen($ie) != 13){return 0;}
		else{
			
			$ie2 = substr($ie, 0, 3) . '0' . substr($ie, 3);
			
			$b = 1;
			$soma = "";
			for($i=0;$i<=11;$i++){
				$soma .= $ie2[$i] * $b;
				$b++;
				if($b == 3){$b = 1;}
			}
			
			$s = 0;
			for($i=0;$i<strlen($soma);$i++){
				$s += $soma[$i];
			}
			$i = substr($ie2, 9, 2);
			$dig = $i - $s;
			if($dig != $ie[11]){return 0;}
			else{
				$b = 3;
				$soma = 0;
				for($i=0;$i<=11;$i++){
					$soma += $ie[$i] * $b;
					$b--;
					if($b == 1){$b = 11;}
				}
				$i = $soma % 11;
				if($i < 2){$dig = 0;}
				else{$dig = 11 - $i;};

				return ($dig == $ie[12]);
			}
		}
	}

	//Pará
	function CheckIEPA($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != 15){return 0;}
			else{
				$b = 9;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$i = $soma % 11;
				if ($i <= 1){$dig = 0;}
				else{$dig = 11 - $i;}

				return ($dig == $ie[8]);
			}
		}
	}

	//Paraíba
	function CheckIEPB($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$i = $soma % 11;
			if ($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}

			if($dig > 9){$dig = 0;}

			return ($dig == $ie[8]);
		}
	}

	//Paraná
	function CheckIEPR($ie){
		if (strlen($ie) != 10){return 0;}
		else{
			$b = 3;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
				if($b == 1){$b = 7;}
			}
			$i = $soma % 11;
			if ($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}

			if ( !($dig == $ie[8]) ){return 0;}
			else{
				$b = 4;
				$soma = 0;
				for($i=0;$i<=8;$i++){
					$soma += $ie[$i] * $b;
					$b--;
					if($b == 1){$b = 7;}
				}
				$i = $soma % 11;
				if($i <= 1){$dig = 0;}
				else{$dig = 11 - $i;}

				return ($dig == $ie[9]);
			}
		}
	}

	//Pernambuco
	function CheckIEPE($ie){
		if (strlen($ie) == 9){
			$b = 8;
			$soma = 0;
			for($i=0;$i<=6;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$i = $soma % 11;
			if ($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}

			if ( !($dig == $ie[7]) ){return 0;}
			else{
				$b = 9;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b--;
				}
				$i = $soma % 11;
				if ($i <= 1){$dig = 0;}
				else{$dig = 11 - $i;}

				return ($dig == $ie[8]);
			}
		}
		elseif(strlen($ie) == 14){
			$b = 5;
			$soma = 0;
			for($i=0;$i<=12;$i++){
				$soma += $ie[$i] * $b;
				$b--;
				if($b == 0){$b = 9;}
			}
			$dig = 11 - ($soma % 11);
			if($dig > 9){$dig = $dig - 10;}

			return ($dig == $ie[13]);
		}
		else{return 0;}
	}

	//Piauí
	function CheckIEPI($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$i = $soma % 11;
			if($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}
			if($dig >= 10){$dig = 0;}

			return ($dig == $ie[8]);
		}
	}

	// Rio de Janeiro
	function CheckIERJ($ie){
		if (strlen($ie) != 8){return 0;}
		else{
			$b = 2;
			$soma = 0;
			for($i=0;$i<=6;$i++){
				$soma += $ie[$i] * $b;
				$b--;
				if($b == 1){$b = 7;}
			}
			$i = $soma % 11;
			if ($i <= 1){$dig = 0;}
			else{$dig = 11 - $i;}

			return ($dig == $ie[7]);
		}
	}

	//Rio Grande do Norte
	function CheckIERN($ie){
		if( !( (strlen($ie) == 9) || (strlen($ie) == 10) ) ){return 0;}
		else{
			$b = strlen($ie);
			if($b == 9){$s = 7;}
			else{$s = 8;}
			$soma = 0;
			for($i=0;$i<=$s;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$soma *= 10;
			$dig = $soma % 11;
			if($dig == 10){$dig = 0;}

			$s += 1;
			return ($dig == $ie[$s]);
		}
	}

	// Rio Grande do Sul
	function CheckIERS($ie){
		if (strlen($ie) != 10){return 0;}
		else{
			$b = 2;
			$soma = 0;
			for($i=0;$i<=8;$i++){
				$soma += $ie[$i] * $b;
				$b--;
				if ($b == 1){$b = 9;}
			}
			$dig = 11 - ($soma % 11);
			if($dig >= 10){$dig = 0;}

			return ($dig == $ie[9]);
		}
	}

	// Rondônia
	function CheckIERO($ie){
		if (strlen($ie) == 9){
			$b=6;
			$soma =0;
			for($i=3;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$dig = 11 - ($soma % 11);
			if($dig >= 10){$dig = $dig - 10;}

			return ($dig == $ie[8]);
		}
		elseif(strlen($ie) == 14){
			$b=6;
			$soma=0;
			for($i=0;$i<=12;$i++) {
				$soma += $ie[$i] * $b;
				$b--;
				if($b == 1){$b = 9;}
			}
			$dig = 11 - ( $soma % 11);
			if ($dig > 9){$dig = $dig - 10;}

			return ($dig == $ie[13]);
		}
		else{return 0;}
	}

	//Roraima
	function CheckIERR($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			if(substr($ie, 0, 2) != 24){return 0;}
			else{
				$b = 1;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b++;
				}
				$dig = $soma % 9;

				return ($dig == $ie[8]);
			}
		}
	}

	//Santa Catarina
	function CheckIESC($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$dig = 11 - ($soma % 11);
			if ($dig <= 1){$dig = 0;}

			return ($dig == $ie[8]);
		}
	}

	//São Paulo
	function CheckIESP($ie){
		if( strtoupper( substr($ie, 0, 1) )  == 'P' ){
			if (strlen($ie) != 13){return 0;}
			else{
				$b = 1;
				$soma = 0;
				for($i=1;$i<=8;$i++){
					$soma += $ie[$i] * $b;
					$b++;
					if($b == 2){$b = 3;}
					if($b == 9){$b = 10;}
				}
				$dig = $soma % 11;
				return ($dig == $ie[9]);
			}
		}else{
			if (strlen($ie) != 12){return 0;}
			else{
				$b = 1;
				$soma = 0;
				for($i=0;$i<=7;$i++){
					$soma += $ie[$i] * $b;
					$b++;
					if($b == 2){$b = 3;}
					if($b == 9){$b = 10;}
				}
				$dig = $soma % 11;
				if($dig > 9){$dig = 0;}

				if($dig != $ie[8]){return 0;}
				else{
					$b = 3;
					$soma = 0;
					for($i=0;$i<=10;$i++){
						$soma += $ie[$i] * $b;
						$b--;
						if($b == 1){$b = 10;}
					}
					$dig = $soma % 11;
					if($dig > 9){$dig = 0;}
					return ($dig == $ie[11]);
				}
			}
		}
	}

	//Sergipe
	function CheckIESE($ie){
		if (strlen($ie) != 9){return 0;}
		else{
			$b = 9;
			$soma = 0;
			for($i=0;$i<=7;$i++){
				$soma += $ie[$i] * $b;
				$b--;
			}
			$dig = 11 - ($soma % 11);
			if ($dig > 9){$dig = 0;}

			return ($dig == $ie[8]);
		}
	}

	//Tocantins
	function CheckIETO($ie){
		if (strlen($ie) != 11){return 0;}
		else{
			$s = substr($ie, 2, 2);
			if( !( ($s=='01') || ($s=='02') || ($s=='03') || ($s=='99') ) ){return 0;}
			else{
				$b=9;
				$soma=0;
				for($i=0;$i<=9;$i++){
					if( !(($i == 2) || ($i == 3)) ){
						$soma += $ie[$i] * $b;
						$b--;
					}
				}
				$i = $soma % 11;
				if($i < 2){$dig = 0;}
				else{$dig = 11 - $i;}

				return ($dig == $ie[10]);
			}
		}
	}

	function CheckIE($ie, $uf){
		if( strtoupper($ie) == 'ISENTO' ){
			return True;
		}else{
			$uf = strtoupper($uf);
			
			$ie = ereg_replace("[()-./,:]", "", $ie);
			$comando = '$valida = $this->CheckIE'.$uf.'("'.$ie.'");';
			eval($comando);
			return $valida;			
		}
	}
	#######################################validações de inscrições estaduais FIM################################################################
   
   
   
   
   
	
}

?>

