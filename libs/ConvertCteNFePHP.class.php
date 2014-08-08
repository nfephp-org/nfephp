<?php
/*
	ESTE CÓDIGO ESTA SOB A LICENSA CREATIVE COMMONS BY SA
	DESENVOLVIDO POR - ROBERTO SPADIM <roberto at spadim dot com dot br>
	2012/10/06 - Brasil
	atualizado em 03/06/2014 para usar cte 2.00
	
	
	arquivos gerados pelo software emissor
	
	
*/
class ConvertCteNFePHP{ //implements ConvertCTePHP{
/*
 *	TXT2XML, pode receber uma string com o conteudo TXT, ou uma string com o nome do arquivo, ou um array com o conteudo do TXT ja 'parcialmente' interpretado
 *			o retorno é um array no seguinte formato:
 *			array(
 *				'id da cte no arquivo'=>array(
 *					'XML' => string do xml
 *					'erros'=>array( array de erros fatais que não deixam converter para o XML - erro de schema, neste caso a string XML pode existir ou não, depende do erro )
 *					'avisos'=>array( array de aviso que deixam o XML ser gerado, mas pode ter alterado o dado do TXT original )
 *				)
 */
	public $campos_v200=array(
		"CTE"		=>"CTE|versao|Id|",
		"IDE"		=>"IDE|cUF|cCT|CFOP|natOp|forPag|mod|serie|nCT|dhEmi|tpImp|tpEmis|cDV|tpAmb|tpCTe|procEmi|verProc|refCTE|cMunEnv|xMunEnv|UFEnv|modal|tpServ|cMunIni|xMunIni|UFIni|cMunFim|xMunFim|UFFim|retira|xDetRetira|dhCont|xJust|",
		"TOMA03"	=>"TOMA03|toma|",
		"TOMA4"		=>"TOMA4|toma|CNPJ|CPF|IE|xNome|xFant|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais|fone|",
		"COMPL"		=>"COMPL|xCaracAd|xCaracSer|xEmi|origCalc|destCalc|xObs|",
		"FLUXO"		=>"FLUXO|xOrig|xDest|xRota|",
		"PASS"		=>"PASS|xPass|",
		"ENTREGA"	=>"ENTREGA|",
		"SEMDATA"	=>"SEMDATA|tpPer|",
		"COMDATA"	=>"COMDATA|tpPer|dProg|",
		"NOPERIODO"	=>"NOPERIODO|tpPer|dIni|dFim|",
		"SEMHORA"	=>"SEMHORA|tpHor|",
		"COMHORA"	=>"COMHORA|tpHor|hProg|",
		"NOINTER"	=>"NOINTER|tpHor|hIni|hFim|",
		"OBSCONT"	=>"OBSCONT|xCampo|xTexto|",
		"OBSFISCO"	=>"OBSFISCO|xCampo|xTexto|",
		"EMIT"		=>"EMIT|CNPJ|IE|xNome|xFant|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|fone|",
		"REM"		=>"REM|CNPJ|CPF|IE|xNome|xFant|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais|fone|",
		"LOCRET"	=>"LOCRET|CNPJ|CPF|xNome|xLgr|Nro|xCpl|xBairro|cMun|xMun|UF|",
		"EXPED"		=>"EXPED|CNPJ|CPF|IE|xNome|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais|fone|",
		"RECEB"		=>"RECEB|CNPJ|CPF|IE|xNome|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais|fone|",
		"DEST"		=>"DEST|CNPJ|CPF|IE|xNome|ISUF|xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais|fone|",
		"LOCENT"	=>"LOCENT|CNPJ|CPF|xNome|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|",
		"VPREST"	=>"VPREST|vTPrest|vRec|",
		"COMP"		=>"COMP|xNome|vComp|",
		"IMP"		=>"IMP|infAdFisco|vTotTrib|",	// lei da transparencia
		"ICMS00"	=>"ICMS00|CST|vBC|pICMS|vICMS|",
		"ICMS20"	=>"ICMS20|CST|pRedBC|vBC|pICMS|vICMS|",
		"ICMS45"	=>"ICMS45|CST|",
		"ICMS60"	=>"ICMS60|CST|vBCSTRet|vICMSSTRet|pICMSSTRet|vCred|",
		"ICMS90"	=>"ICMS90|CST|pRedBC|vBC|pICMS|vICMS|vCred|",
		"ICMSOutraUF"	=>"ICMSOutraUF|CST|pRedBCOutraUF|vBCOutraUF|pICMSOutraUF|vICMSOutraUF|",
		"ICMSSN"	=>"ICMSSN|indSN|",
		"INFCTENORM"	=>"INFCTENORM|",
		"INFCARGA"	=>"INFCARGA|vCarga|proPred|xOutCat|",
		"INFQ"		=>"INFQ|cUnid|tpMed|qCarga|",
		"CONTQT"	=>"CONTQT|nCont|dPrev|",
		"LACCONTQT"	=>"LACCONTQT|nLacre|",
		"INFDOC"	=>"INFDOC|",
		"INFNFE"	=>"INFNFE|chave|PIN|",
		"INFOUTROS"	=>"INFOUTROS|tpDoc|descOutros|nDoc|dEmi|vDocFisc|",
		"INFNF"		=>"INFNF|nRoma|nPed|mod|serie|nDoc|dEmi|vBC|vICMS|vBCST|vST|vProd|vNF|nCFOP|nPeso|PIN|",
		"DOCANT"	=>"DOCANT|",
		"EMIDOCANT"	=>"EMIDOCANT|CNPJ|CPF|IE|UF|xNome|",
		"IDDOCANTPAP"	=>"IDDOCANTPAP|tpDoc|serie|subser|nDoc|dEmi|",
		"IDDOCANTELE"	=>"IDDOCANTELE|chave|",
		"SEG"		=>"SEG|respSeg|xSeg|nApol|nAver|vCarga|",
		"INFMODAL"	=>"INFMODAL|versaoModal|",
		// RODOVIÁRIO
			"RODO"		=>"RODO|RNTRC|dPrev|lota|CIOT|",
			"OCC"		=>"OCC|serie|nOcc|dEmi|",
			"EMIOCC"	=>"EMIOCC|CNPJ|cInt|IE|UF|fone|",
			"VALEPED"	=>"VALEPED|CNPJForn|nCompra|CNPJPg|",
			"VEIC"		=>"VEIC|cInt|RENAVAM|placa|tara|capKG|capM3|tpProp|tpVeic|tpRod|tpCar|UF|",
			"PROP"		=>"PROP|CNPJ|CPF|RNTRC|xNome|IE|UF|tpProp|",
			"LACRODO"	=>"LACRODO|nLacre|",
			"MOTO"		=>"MOTO|xNome|CPF|",
			"PERI"		=>"PERI|nONU|xNomeAE|xClaRisco|grEmb|qTotProd|qVoltTipo|pontoFulgor|",
			"VEICNOVOS"	=>"VEICNOVOS|chassi|cCor|xCor|cMod|vUnit|vFrete|",
		//
		"COBR"		=>"COBR|",
		"FAT"		=>"FAT|nFat|vOrig|vDesc|vLiq|",
		"DUP"		=>"DUP|nDup|dVenc|vDup|",

	);
		// quais tags podem precender a tag atual
	protected $campos_v200_lasttag=array(
	//	'IDE'
			'TOMA03'	=>array('IDE'),
			'TOMA4'		=>array('IDE'),
		'COMPL'			=>array('TOMA03','TOMA4'),
		'FLUXO'			=>array('TOMA03','TOMA4','COMPL'),
			'PASS'		=>array('FLUXO'),
		'ENTREGA'		=>array('TOMA03','TOMA4','COMPL','FLUXO','PASS'),
			'SEMDATA'	=>array('ENTREGA'),
			'COMDATA'	=>array('ENTREGA'),
			'NOPERIODO'	=>array('ENTREGA'),
			'SEMHORA'	=>array('SEMDATA','COMDATA','NOPERIODO'),
			'COMHORA'	=>array('SEMDATA','COMDATA','NOPERIODO'),
			'NOINTER'	=>array('SEMDATA','COMDATA','NOPERIODO'),
			'SEMHORA'	=>array('SEMDATA','COMDATA','NOPERIODO'),
		'OBSCONT'		=>array('TOMA03','TOMA4','COMPL','FLUXO','PASS','ENTREGA',
						'SEMDATA','COMDATA','NOPERIODO',
						'SEMHORA','COMHORA','NOINTER'),
		'OBSFISCO'		=>array('TOMA03','TOMA4','COMPL','FLUXO','PASS','ENTREGA',
						'SEMDATA','COMDATA','NOPERIODO',
						'SEMHORA','COMHORA','NOINTER','OBSCONT'),
		'EMIT'			=>array('TOMA03','TOMA4','COMPL','FLUXO','PASS','ENTREGA',
						'SEMDATA','COMDATA','NOPERIODO',
						'SEMHORA','COMHORA','NOINTER','OBSCONT','OBSFISCO'),
		'REM'			=>array('EMIT'),
		'EXPED'			=>array('EMIT','REM'),
		'RECEB'			=>array('EMIT','REM','EXPED'),//,
							//'INFNFE','INFOUTROS','INFNF','LOCRET'),
		'DEST'			=>array('EMIT','REM','EXPED','RECEB'),//,
							//'INFNFE','INFOUTROS','INFNF','LOCRET'),
			'LOCENT'	=>array('DEST'),
		'VPREST'		=>array('EMIT','REM','EXPED','RECEB','DEST','LOCENT'),//,
							//'INFNFE','INFOUTROS','INFNF','LOCRET'),
			'COMP'		=>array('VPREST'),
		'IMP'			=>array('VPREST','COMP'),
			'ICMS00'	=>array('IMP'),
			'ICMS20'	=>array('IMP'),
			'ICMS45'	=>array('IMP'),
			'ICMS60'	=>array('IMP'),
			'ICMS90'	=>array('IMP'),
			'ICMSOutraUF'	=>array('IMP'),
			'ICMSSN'	=>array('IMP'),
		// CTE NORMAL...
		'INFCTENORM'		=>array('IMP',
						'ICMS00','ICMS20','ICMS45','ICMS60','ICMS90','ICMSOutraUF','ICMSSN'),
		'INFCARGA'		=>array('INFCTENORM'),
			'INFQ'		=>array('INFCARGA'),
		'CONTQT'		=>array('INFCARGA','INFQ'),
			'LACCONTQT'	=>array('CONTQT'),

		'INFDOC'		=>array('INFCARGA','INFQ','CONTQT','LACCONTQT'),
			'INFNFE'	=>array('INFDOC','INFNFE'),
			'INFOUTROS'	=>array('INFDOC','INFOUTROS'),
			'INFNF'		=>array('INFDOC','INFNF'),
				'LOCRET'=>array('INFNF'),

		'DOCANT'		=>array('INFDOC','INFCARGA','INFQ','CONTQT','LACCONTQT',
						'INFNFE','INFOUTROS','INFNF','LOCRET'),
			'EMIDOCANT'	=>array('DOCANT'),
				'IDDOCANTPAP'	=>array('EMIDOCANT','IDDOCANTPAP','IDDOCANTELE'),
				'IDDOCANTELE'	=>array('EMIDOCANT','IDDOCANTPAP','IDDOCANTELE'),
		'SEG'			=>array('INFCARGA','INFQ','CONTQT','LACCONTQT',
						'DOCANT','IDDOCANTPAP','IDDOCANTELE'),
		'INFMODAL'		=>array('INFCARGA','INFQ','CONTQT','LACCONTQT',
						'DOCANT','IDDOCANTPAP','IDDOCANTELE','SEG'),
		// RODOVIARIO
			'RODO'		=>array('INFMODAL'),
			'OCC'		=>array('RODO','OCC','EMIOCC'),
			'EMIOCC'	=>array('OCC'),
			'VALEPED'	=>array('RODO','OCC','EMIOCC','VALEPED'),
			'VEIC'		=>array('RODO','OCC','EMIOCC','VALEPED','VEIC'),
			'PROP'		=>array('VEIC'),
			'LACRODO'	=>array('VEIC'),
			'MOTO'		=>array('VEIC','PROP','LACRODO','MOTO'),
		
		// FIM MODAIS
		'PERI'		=>array('RODO','OCC','EMIOCC','VALEPED','VEIC','PROP','LACRODO','MOTO',
					'PERI'),
		'VEICNOVOS'	=>array('RODO','OCC','EMIOCC','VALEPED','VEIC','PROP','LACRODO','MOTO',
					'PERI','VEICNOVOS'),
		'COBR'		=>array('RODO','OCC','EMIOCC','VALEPED','VEIC','PROP','LACRODO','MOTO',
					'PERI','VEICNOVOS'),
		'FAT'		=>array('COBR'),
		'DUP'		=>array('FAT','COBR','DUP')	);
	function __construct(){
	}

	public function TXT2XML($txt,$output_string=true){
		// CARREGA ARQUIVO
		$RETURN=array(	'erros'	=>array(),
				'avisos'=>array(),
				'xml'	=>array());	// erros de interpretação do arquivo
		if(is_file($txt))
			$txt=file_get_contents($txt);
		// PROCESSA STRING E GERA ARRAY
		$txt=$this->_TXT2XML_processa_txt($txt);	// esta função gera erros de interpretação do arquivo e separa os cte do arquivo
#print_r($txt);
		$RETURN['erros']	=array_merge($RETURN['erros'],$txt['erros']);
		$RETURN['avisos']	=array_merge($RETURN['avisos'],$txt['avisos']);
		foreach($txt['docs'] as $k=>$v)		// esta interpreta linha a linha das ctes
			$RETURN['xml'][$k]=$this->_TXT2XML_processa_array($v,$output_string);
		return($RETURN);
	}
	private function _TXT2XML_processa_txt_converte_versao($array){
		return(array());	// atualmente retorna em branco
	}
	private function _TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,$TAG_EMBRANCO,$campos,& $RETURN, & $cur_cte_tags, $cur_cte){
		$RETURN['erros'][]="$MSG_PADRAO TAG informada sem a tag '$TAG_EMBRANCO' ser informada, gerando tag em branco.";
		$tmp_v		=array();
		if(is_array($campos))
			$tmp_campos	=explode("|",$campos[$TAG_EMBRANCO]);
		else
			$tmp_campos	=explode("|",$campos);
		$RETURN['docs'][$cur_cte][]=$this->_TXT2XML_processa_txt_tag($MSG_PADRAO,$tmp_v,$tmp_campos,$RETURN);
		$cur_cte_tags[$TAG_EMBRANCO]=1;
		unset($tmp_campos,$tmp_v);
	}
	private function _TXT2XML_processa_txt_tag(&$MSG_PADRAO,&$v,&$campos,& $RETURN){
		// retorna o array da tag
		$ret	=array('TAG'=>$campos[0]);
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
		if(	!in_array($last_tag,	$this->campos_v200_lasttag[$cur_tag]) && 
			!is_array(		$this->campos_v200_lasttag[$cur_tag]))
			$RETURN['erros'][]="$MSG_PADRAO Ultima tag $last_tag, não esta em: ". 
				implode(', ',	$this->campos_v200_lasttag[$cur_tag]);
		return;
	}
	private function _TXT2XML_processa_txt($string){
		// processa arquivo TXT (string) e gera um array das ctes
		
		$RETURN=array(
			'erros'		=>array(),	// erros de importação independente de qual cte esta...
			'avisos'	=>array(),	// avisos de importação independente de qual cte esta...
			'docs'		=>array()	// ctes
			);
		// o arquivo TXT é feito em latin1, é OBRIGATÓRIO a conversão para UTF-8 que é o padrão do XML
		if (preg_match("/^[\\x00-\\xFF]*$/u", $string) === 1){	// charset do latin1
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
			$RETURN['avisos'].="Atenção arquivo não está na codificação LATIN1, nem UTF-8.";
		}
		$string		=explode("\n",str_replace("\r",'',$string));	// remove \r dos \r\n, ou \n\r e explode arquivo 
		$tot_ctes	=-1;
		$qnt_tag_ctes	=0;
		$cur_cte	=-1;
		$cur_cte_tags	=array();
		$cur_versao	='';
		$cur_linha	=0;
		$last_tag	='';
		foreach($string as $v){
			$cur_linha++;
			if($v==='') continue;
			
			
			
			
			$v=explode("|",$v);	// divide a linha pelos campos, neste caso não existe um arquivo CSV conforme o padrão, e sim o padrão da receita, ou seja não existe encapsulamento, quebra de linha e coisas do genero
			$TAG=$v[0];
			$MSG_PADRAO="[Linha $cur_linha, CTe $cur_cte, TAG $TAG]";
			
			if(isset($cur_cte_tags[$TAG]))	$cur_cte_tags[$TAG]++;
			else				$cur_cte_tags[$TAG]=1;
			if($TAG==='REGISTROSCTE' || $TAG==='REGISTROS CTE'){
				// REGISTROSCTE|qtd ctes fiscais no arquivo| 
				$campos=explode("|","REGISTROSCTE|qtd ctes no arquivo|");
				$MSG_PADRAO="[Linha $cur_linha, TAG $TAG]";
				$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
				if(count($v)!=count($campos))
					$RETURN['avisos'][]="$MSG_PADRAO Quantidade de campos na tag (".count($v).") é diferente de ".count($campos);
				if(!isset($v[1])){
					$RETURN['avisos'][]="$MSG_PADRAO campo 'qtd ctes no arquivo' não informado, considerando como 0";
					$v[1]=0;
				}elseif((double)$v[1]<=0){
					$RETURN['avisos'][]="$MSG_PADRAO campo 'qtd ctes no arquivo' menor igual a 0";
					$v[1]=0;
				}
				if($tot_ctes<0){
					$tot_ctes=(double)$v[1];
				}elseif($qnt_tag_ctes>1){
					$RETURN['erros'][]="$MSG_PADRAO TAG encontrada mais de uma vez no arquivo";
				}
				$qnt_tag_ctes++;
				$last_tag=$TAG;
				continue;
			}elseif($TAG==='CTE'){
				//CTE|versão do schema|id| 
				$campos=explode('|',$this->campos_v200[$TAG]);	// $this->campos_v200[$TAG] é oq tem mais campos
				// cria nova cte
				$cur_cte++;
				$cur_cte_tags	=array();
				$MSG_PADRAO="[Linha $cur_linha, CTe $cur_cte, TAG $TAG]";
				$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
				if(count($v)!=count($campos))
					$RETURN['avisos'][]="$MSG_PADRAO Quantidade de campos na tag é diferente de ".count($campos);
				if(!isset($v[1])){
					$RETURN['erros'][]="$MSG_PADRAO campo 'versao' não informado, considerando como 2.00";
				}elseif($v[1]!=='2.00'){
					$RETURN['aviso'][]="$MSG_PADRAO campo 'versao' diferente de 2.00, esta não é a ultima versão da CTe";
				}
				if(!isset($v[2])){
					$RETURN['avisos'][]="$MSG_PADRAO campo 'id' não informado, considerando como em branco";
					$v[2]='';
				}elseif(strlen($v[2])!=47 && strlen($v[2])!=0){
					$RETURN['avisos'][]="$MSG_PADRAO campo 'id', quantidade de caracteres (".strlen($v[2]).") não é igual a 47, a chave da CTe deverá ser calculada";
				}
				$cur_versao=$v[1];
				$RETURN['docs'][$cur_cte]=array();
				$RETURN['docs'][$cur_cte][]=array(
							'TAG'		=>'CTE',
							'versao'	=>$v[1],
							'Id'		=>$v[2]);
				$last_tag=$TAG;
				continue;
			}elseif($cur_versao==='2.00'){
				///////////////// VERSÃO 2.00
				if(!isset($this->campos_v200[$TAG])){
					$RETURN['erros'][]="$MSG_PADRAO Tag não existe no layout TXT";
					continue;
				}
				$campos=explode("|",$this->campos_v200[$TAG]);
#print_r($campos);
#print_r($v);				
				// TAGS QUE SÓ APARECEM 1 VEZ:
				if(in_array($TAG,array(
					'IDE','TOMA03','TOMA4',
					'COMPL','FLUXO',
					'ENTREGA',	'SEMDATA','COMDATA','NOPERIODO',
							'SEMHORA','COMHORA','NOINTER',
					'EMIT','REM','LOCRET','EXPED','RECEB','DEST','LOCENT',
					'VPREST','IMP','ICMS00','ICMS20','ICMS45','ICMS60','ICMS90','ICMSOutraUF','ICMSSN',
					'INFCTENORM','INFCARGA','DOCANT','SEG','INFMODAL','COBR','FAT',
					'RODO','INFDOC'))){
					if($cur_cte_tags[$TAG]!=1){
						$RETURN['erros'][]="$MSG_PADRAO TAG informada mais de uma vez, ignorando linha. #01";
						continue;
					}
				}
				if(!isset($cur_cte_tags['IDE']) && $TAG!='IDE'){
					$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'IDE', $RETURN);
					$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'IDE', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
					$last_tag='IDE';
				}
				if($TAG=='TOMA03' || $TAG=='TOMA4'){
					if(isset($cur_cte_tags['TOMA4']) && $TAG!='TOMA4'){
						$RETURN['erros'][]="$MSG_PADRAO TAG TOMA4 já informada.";
						continue;
					}
					if(isset($cur_cte_tags['TOMA03']) && $TAG!='TOMA03'){
						$RETURN['erros'][]="$MSG_PADRAO TAG TOMA03 já informada.";
						continue;
					}
				}elseif($TAG=='PASS'){
					if(!isset($cur_cte_tags['FLUXO'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'FLUXO', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'FLUXO', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='FLUXO';
					}
				}elseif($TAG=='SEMDATA' || $TAG=='COMDATA' || $TAG=='NOPERIODO'){
					if(!isset($cur_cte_tags['ENTREGA'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'ENTREGA', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'ENTREGA', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='ENTREGA';
					}
					if(isset($cur_cte_tags['SEMDATA']) && $TAG!='SEMDATA'){
						$RETURN['erros'][]="$MSG_PADRAO TAG SEMDATA já informada.";
						continue;
					}
					if(isset($cur_cte_tags['COMDATA']) && $TAG!='COMDATA'){
						$RETURN['erros'][]="$MSG_PADRAO TAG COMDATA já informada.";
						continue;
					}
					if(isset($cur_cte_tags['NOPERIODO']) && $TAG!='NOPERIODO'){
						$RETURN['erros'][]="$MSG_PADRAO TAG NOPERIODO já informada.";
						continue;
					}
				}elseif($TAG=='SEMHORA' || $TAG=='COMHORA' || $TAG=='NOINTER'){
					if(!isset($cur_cte_tags['ENTREGA'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'ENTREGA', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'ENTREGA', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='ENTREGA';
					}
					if(!isset($cur_cte_tags['SEMDATA']) && !isset($cur_cte_tags['COMDATA']) && !isset($cur_cte_tags['NOPERIODO'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'SEMDATA', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'SEMDATA', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='SEMDATA';
					}
					
					if(isset($cur_cte_tags['SEMHORA']) && $TAG!='SEMHORA'){
						$RETURN['erros'][]="$MSG_PADRAO TAG SEMHORA já informada.";
						continue;
					}
					if(isset($cur_cte_tags['COMHORA']) && $TAG!='COMHORA'){
						$RETURN['erros'][]="$MSG_PADRAO TAG COMHORA já informada.";
						continue;
					}
					if(isset($cur_cte_tags['NOINTER']) && $TAG!='NOINTER'){
						$RETURN['erros'][]="$MSG_PADRAO TAG NOINTER já informada.";
						continue;
					}
				// TAGS OBSCONT/OBSFISCO
				}elseif($TAG=='OBSCONT' || $TAG=='OBSFISCO'){
					if($cur_cte_tags[$TAG]>10){
						$RETURN['erros'][]="$MSG_PADRAO TAG informada mais 10 vezes, ignorando linha.";
						continue;
					}
				}elseif($TAG=='INFNFE' || $TAG=='INFNF' || $TAG=='INFOUTROS'){
					if(!isset($cur_cte_tags['INFDOC'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'INFDOC', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'INFDOC', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='INFDOC';
					}
					if($TAG=='INFNF')
						unset($cur_cte_tags['LOCRET']);
					else
						unset($cur_cte_tags['INFNF']);	// PRA PODER ADICIONAR O 'LOCRET'
				}elseif($TAG=='LOCRET'){
					if(!isset($cur_cte_tags['INFNF'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'INFNF', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'INFNF', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='INFNF';
					}
				}elseif($TAG=='LOCENT'){
					if(!isset($cur_cte_tags['DEST'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'DEST', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'DEST', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='DEST';
					}
				}elseif($TAG=='COMP'){
					if(!isset($cur_cte_tags['VPREST'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'VPREST', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'VPREST', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='VPREST';
					}
				}elseif(in_array($TAG,array(
						'ICMS00','ICMS20','ICMS45','ICMS60','ICMS90','ICMSOutraUF','ICMSSN'))){
					if(!isset($cur_cte_tags['IMP'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'VPREST', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'VPREST', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='VPREST';
					}
					$tmp_arr=array('ICMS00','ICMS20','ICMS45','ICMS60','ICMS90','ICMSOutraUF','ICMSSN');
					foreach($tmp_arr as $vv)
						if(isset($cur_cte_tags[$vv]) && $TAG!=$vv){
							$RETURN['erros'][]="$MSG_PADRAO TAG $vv já informada.";
							continue 2;
						}
					unset($vv,$tmp_arr);
				}elseif($TAG=='INFCARGA' || $TAG=='CONTQT'){
					if(!isset($cur_cte_tags['INFCTENORM'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'INFCTENORM', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'INFCTENORM', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='INFCTENORM';
					}
				}elseif($TAG=='INFQ'){
					if(!isset($cur_cte_tags['INFCARGA'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'INFCARGA', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'INFCARGA', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='INFCARGA';
					}
				
				}elseif($TAG=='LACCONTQT'){
					if(!isset($cur_cte_tags['CONTQT'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'CONTQT', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'CONTQT', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='CONTQT';
					}
				}elseif($TAG=='EMIDOCANT'){
					if(!isset($cur_cte_tags['DOCANT'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'DOCANT', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'DOCANT', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='DOCANT';
					}
				}elseif($TAG=='IDDOCANTPAP' || $TAG=='IDDOCANTELE'){
					if(!isset($cur_cte_tags['EMIDOCANT'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'EMIDOCANT', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'EMIDOCANT', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='EMIDOCANT';
					}
					
				// rodoviário
				}elseif($TAG=='RODO'){
					if(!isset($cur_cte_tags['INFMODAL'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'INFMODAL', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'INFMODAL', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='INFMODAL';
					}
				}elseif($TAG=='OCC'){
					if(!isset($cur_cte_tags['RODO'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'RODO', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'RODO', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='RODO';
					}
				}elseif($TAG=='EMIOCC'){
					if(!isset($cur_cte_tags['EMIOCC'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'EMIOCC', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'EMIOCC', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='EMIOCC';
					}
				}elseif($TAG=='PROP'){
					if(!isset($cur_cte_tags['VEIC'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'VEIC', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'VEIC', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='VEIC';
					}
				
				
				/////
				}elseif($TAG=='FAT' || $TAG=='DUP'){
					if(!isset($cur_cte_tags['COBR'])){
						$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, 'COBR', $RETURN);
						$this->_TXT2XML_processa_txt_tag_embranco($MSG_PADRAO,'COBR', $this->campos_v200, $RETURN, $cur_cte_tags, $cur_cte);
						$last_tag='COBR';
					}
				}
				$this->_TXT2XML_verifica_last_tag($this->campos_v200_lasttag, $last_tag, $TAG, $RETURN);
				$RETURN['docs'][$cur_cte][]=$this->_TXT2XML_processa_txt_tag($MSG_PADRAO,$v,$campos,$RETURN);
				$last_tag=$TAG;
				continue;
			}
			$RETURN['erros'][]="$MSG_PADRAO Não foi possivel interpretar linha, versão atual do schema: $cur_versao";
		}
		if($qnt_tag_ctes!=1)
			$RETURN['erros'][]="TAG REGISTROSCTE não encontrada no arquivo, atenção este arquivo pode não ser um arquivo TXT de CTe";
#var_dump($RETURN);
		return($RETURN);
	}
	private function _TXT2XML_processa_array($array_ctes,$output_string=true){
		// processa array (vindo da função _TXT2XML_processa_txt) e gera varios arquivos XML ($output_string=true) ou varios objetos Dom ($output_string=false)
		if(!is_array($array_ctes))
			return(false);
		if(!is_array($array_ctes[0]))
			return(false);
		if(!isset($array_ctes[0][0]) || !is_array($array_ctes[0][0]))
			$array_ctes=array($array_ctes);
		$RETURN=array(	'erros'	=>array(),
				'avisos'=>array(),
				'xml'	=>'');
		// caso não seja versão 2.00 usar função "_TXT2XML_processa_txt_converte_versao($array)" ou fazer um if ($cur_version pra cada campo...)
		foreach($array_ctes as $kcte=>$v){
			unset(	$dom, $CTe,$infCte, $ide, $COMPL, $FLUXO, $ENTREGA, $REM, $INFNF, $DEST, $VPREST, $IMP, $INFCTENORM, $INFCARGA, $CONTQT, $DOCANT, $INFDOC, $EMIDOCANT, $IDDOCANT, $INFMODAL, $RODO, $RODO_OCC, $RODO_VEIC, $COBR, 
				$IDE_dhCont, $TMP_ADD_COMPL, $FLUXO_xDest,$FLUXO_xRota,$CONTQT_dPrev, $cur_version);
			$dom=new DOMDocument('1.0', 'UTF-8');
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$cur_version='';
			// limpar variaveis utilizadas...
			foreach($v as $v2){
#echo $v2['TAG']."\n";
				if($v2['TAG']=='CTE'){
					// CRIA CTE
					$cur_version=$v2['versao'];
					$CTe	=$dom->createElement("CTe");
					$CTe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/cte");
					$infCte	=$dom->createElement("infCte");
					$infCte->setAttribute("Id", $v2['Id']);
					$infCte->setAttribute("versao", $v2['versao']);
				}elseif($cur_version=='2.00'){
					// versão 2.00
					$MSG_PADRAO="[TAG ".$v2['TAG'].", CTe $kcte]";
					if(!isset($this->campos_v200[$v2['TAG']])){ //tag não existe
						$RETURN['avisos'][$kcte][]="$MSG_PADRAO TAG ".$v2['TAG']." não encontrada nos campos da versão 2.00";
						continue;
					}
					$campos=explode('|',$this->campos_v200[$v2['TAG']]);
					unset($campos[0]);	// campo da tag
					foreach($campos as $k=>$nome_campo){
						if(strlen(trim($nome_campo))==0){
							unset($campos[$k]);
							continue;
						}
						if(!isset($v2[$nome_campo])){
							$RETURN['avisos'][$kcte][]="$MSG_PADRAO Campo $nome_campo não encontrada no array de importação, considerando como em branco";
							$v2[$nome_campo]='';	// cria campo em branco - não deve ocorrer!
						}else{
							$last_len=strlen($v2[$nome_campo]);
							$v2[$nome_campo]=trim($v2[$nome_campo]);
							if($last_len!=strlen($v2[$nome_campo])){
								$RETURN['avisos'][$kcte][]="$MSG_PADRAO Alterado o tamanho do campo $nome_campo após TRIM de $last_len para ".strlen($v2[$nome_campo]);
							}
						}
					}
					// nova cte
					if($v2['TAG']=='IDE' && !isset($ide)){
						$ide = $dom->createElement("ide");
						if($v2['mod']!=57)
							$RETURN['erros'][$kcte][]="$MSG_PADRAO campo 'mod' não é igual a 57";
						foreach($campos as $nome_campo){
							if($nome_campo=='refCTE' || $nome_campo=='xDetRetira'){
								if(empty($v2[$nome_campo]))
									continue;
							}elseif($nome_campo=='xJust' || $nome_campo=='dhCont'){
								if(empty($v2['xJust']) || empty($v2['dhCont']))
									continue;
							}elseif($nome_campo=='VerProc'){
								if(empty($v2[$nome_campo]))
									$v2[$nome_campo]="NfePHP";
							}
							if($nome_campo=='dhCont'){
								$IDE_dhCont=$dom->createElement($nome_campo,$v2[$nome_campo]);
								$ide->appendChild( $IDE_dhCont );
								continue;
							}
							$ide->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$infCte->appendChild($ide);
					}elseif(!isset($ide)){
						$RETURN['avisos'][$kcte][]="$MSG_PADRAO TAG informada sem ter uma tag 'IDE' criada ";
						continue;
					}elseif($v2['TAG']=='TOMA03'){
						$tmp_grupo=$dom->createElement("toma03");
						$tmp_grupo->appendChild( $dom->createElement('toma',$v2['toma']) );
						if(isset($IDE_dhCont)){
							$ide->insertBefore($ide->appendChild($tmp_grupo),$IDE_dhCont);
						}else{
							$ide->appendChild($tmp_grupo);
						}
						if(isset($IDE_dhCont)){
							$ide->insertBefore($ide->appendChild($tmp_grupo),$IDE_dhCont);
						}else{
							$ide->appendChild($tmp_grupo);
						}
					}elseif($v2['TAG']=='TOMA44'){
						$tmp_grupo	=$dom->createElement("toma4");
						$tmp_grupo2	=$dom->createElement("enderToma");
						$cur_grupo	=& $tmp_grupo;
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if(	$nome_campo=='CNPJ' || $nome_campo=='CPF' ||
								$nome_campo=='IE' || $nome_campo=='xFant' || $nome_campo=='xCpl' || 
								$nome_campo=='email'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if(	$nome_campo=='cPais' || $nome_campo=='xPais'){
								if(empty($v2['cPais']) || empty($v2['xPais']))
									continue;
							}
							if($nome_campo=='xLgr'){
								unset($cur_grupo);
								// começa a preenche o 'enderToma'
								// xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais
								$cur_grupo = &$tmp_grupo2;
							}
							if($nome_campo=='fone'){
								// adiciona o fone primeiro....
								if($nome_campo=='fone' && !empty($v2[$nome_campo]))
									$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
								unset($cur_grupo);
								// volta a preencher o 'toma04'
								$tmp_grupo->appendChild( $tmp_grupo2 );
								$cur_grupo = &$tmp_grupo;
								continue;
							}
							if($nome_campo=='fone' && empty($v2[$nome_campo]))
								continue;
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$cur_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						if(isset($IDE_dhCont)){
							$ide->insertBefore($ide->appendChild($tmp_grupo),$IDE_dhCont);
						}else{
							$ide->appendChild($tmp_grupo);
						}
					}elseif($v2['TAG']=='COMPL'){
						$COMPL	=$dom->createElement("compl");
						unset($TMP_ADD_COMPL);
						if(!empty($v2['xCaracAd']))
							$COMPL->appendChild( $dom->createElement('xCaracAd',$v2['xCaracAd']) );
						if(!empty($v2['xCaracSer']))
							$COMPL->appendChild( $dom->createElement('xCaracSer',$v2['xCaracSer']) );
						if(!empty($v2['xEmi']))
							$COMPL->appendChild( $dom->createElement('xEmi',$v2['xEmi']) );
						// FLUXO
							// PASSAGEM
						// ENTREGA
							// DATA
							// HORA
						if(!empty($v2['origCalc'])){
							$TMP_ADD_COMPL= $dom->createElement('origCalc',$v2['origCalc']);
							$COMPL->appendChild( $TMP_ADD_COMPL );
						}
						if(!empty($v2['destCalc'])){
							if(!isset($TMP_ADD_COMPL)){
								$TMP_ADD_COMPL= $dom->createElement('destCalc',$v2['destCalc']);
								$COMPL->appendChild( $TMP_ADD_COMPL );
							}else{
								$COMPL->appendChild( $dom->createElement('destCalc',$v2['destCalc']) );
							}
						}
						if(!empty($v2['xObs'])){
							if(!isset($TMP_ADD_COMPL)){
								$TMP_ADD_COMPL= $dom->createElement('xObs',$v2['xObs']);
								$COMPL->appendChild( $TMP_ADD_COMPL );
							}else{
								$COMPL->appendChild( $dom->createElement('xObs',$v2['xObs']) );
							}
						}
						$infCte->appendChild($COMPL);
					}elseif($v2['TAG']=='FLUXO' && isset($COMPL)){
						$FLUXO	=$dom->createElement("fluxo");
						unset($FLUXO_xDest,$FLUXO_xRota);
						if(!empty($v2['xOrig'])){
							$FLUXO->appendChild( $dom->createElement('xOrig',$v2['xOrig']) );
						}
						// PASSAGENS
						if(!empty($v2['xDest'])){
							$FLUXO_xDest=$dom->createElement('xDest',$v2['xDest']);
							$FLUXO->appendChild( $FLUXO_xDest );
						}
						if(!empty($v2['xRota'])){
							$FLUXO_xRota=$dom->createElement('xRota',$v2['xRota']);
							$FLUXO->appendChild( $FLUXO_xRota );
						}
						if(isset($TMP_ADD_COMPL)){
							$COMPL->insertBefore($COMPL->appendChild($FLUXO),$TMP_ADD_COMPL);
						}else{
							$COMPL->appendChild($FLUXO);
						}
					}elseif($v2['TAG']=='PASS' && isset($FLUXO)){
						$tmp_grupo	=$dom->createElement("pass");
						$tmp_grupo->appendChild( $dom->createElement('xPass',$v2['xPass']) );
						if(isset($FLUXO_xDest)){
							$FLUXO->insertBefore($FLUXO->appendChild($tmp_grupo),$FLUXO_xDest);
						}elseif(isset($FLUXO_xRota)){
							$FLUXO->insertBefore($FLUXO->appendChild($tmp_grupo),$FLUXO_xRota);
						}
					}elseif($v2['TAG']=='ENTREGA' && isset($COMPL)){
						$ENTREGA	=$dom->createElement("Entrega");
						if(isset($TMP_ADD_COMPL)){
							$COMPL->insertBefore($COMPL->appendChild($ENTREGA),$TMP_ADD_COMPL);
						}else{
							$COMPL->appendChild($ENTREGA);
						}
					}elseif(($v2['TAG']=='SEMDATA' || $v2['TAG']=='COMDATA' || $v2['TAG']=='NOPERIODO' || 
						 $v2['TAG']=='SEMHORA' || $v2['TAG']=='COMHORA' || $v2['TAG']=='NOINTER') && 
							isset($ENTREGA)){
						if($v2['TAG']=='SEMDATA')	$tmp_grupo	=$dom->createElement("semData");
						if($v2['TAG']=='COMDATA')	$tmp_grupo	=$dom->createElement("comData");
						if($v2['TAG']=='NOPERIODO')	$tmp_grupo	=$dom->createElement("noPeriodo");
						if($v2['TAG']=='SEMHORA')	$tmp_grupo	=$dom->createElement("semHora");
						if($v2['TAG']=='COMHORA')	$tmp_grupo	=$dom->createElement("comHora");
						if($v2['TAG']=='NOINTER')	$tmp_grupo	=$dom->createElement("noInter");
						foreach($campos as $nome_campo)
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						$ENTREGA->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='OBSCONT'){
						$tmp_grupo = $dom->createElement("obsCont");
						foreach($campos as $nome_campo)
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						$infCte->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='OBSFISCO'){
						$tmp_grupo = $dom->createElement("obsFisco");
						foreach($campos as $nome_campo)
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						$infCte->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='EMIT'){
						$tmp_grupo	=$dom->createElement("emit");
						$tmp_grupo2	=$dom->createElement("enderEmit");
						$cur_grupo	=& $tmp_grupo;
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if(	$nome_campo=='xFant' || $nome_campo=='CEP' || $nome_campo=='xCpl'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if(	$nome_campo=='cPais' || $nome_campo=='xPais'){
								if(empty($v2['cPais']) || empty($v2['xPais']))
									continue;
							}
							if($nome_campo=='xLgr'){
								unset($cur_grupo);
								// começa a preenche o endereço
								// xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|<cPais|xPais>
								$cur_grupo = &$tmp_grupo2;
							}
							#if($nome_campo=='fone'){
							#	// adiciona o fone primeiro....
							#	if($nome_campo=='fone' && !empty($v2[$nome_campo]))
							#		$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
							#	unset($cur_grupo);
							#	// volta a preencher a pessoa
							#	$tmp_grupo->appendChild( $tmp_grupo2 );
							#	$cur_grupo = &$tmp_grupo;
							#	continue;
							#}
							if($nome_campo=='fone' && empty($v2[$nome_campo]))
								continue;
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$cur_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$tmp_grupo->appendChild( $tmp_grupo2 );
						$infCte->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='REM'){
						$REM		=$dom->createElement("rem");
						$tmp_grupo2	=$dom->createElement("enderReme");
						$cur_grupo	=& $REM;
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if(	$nome_campo=='CNPJ' || $nome_campo=='CPF' || 
								$nome_campo=='xFant' || $nome_campo=='CEP' || 
								$nome_campo=='xCpl'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if(	$nome_campo=='cPais' || $nome_campo=='xPais'){
								if(empty($v2['cPais']) || empty($v2['xPais']))
									continue;
							}
							if($nome_campo=='xLgr'){
								unset($cur_grupo);
								// começa a preenche o endereço
								// xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|<cPais|xPais>
								$cur_grupo = &$tmp_grupo2;
							}
							if($nome_campo=='fone'){
								// adiciona o fone primeiro....
								if($nome_campo=='fone' && !empty($v2[$nome_campo]))
									$REM->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
								unset($cur_grupo);
								// volta a preencher a pessoa
								$REM->appendChild( $tmp_grupo2 );
								$cur_grupo = &$REM;
								continue;
							}
							if($nome_campo=='fone' && empty($v2[$nome_campo]))
								continue;
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$cur_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$infCte->appendChild($REM);
					}elseif($v2['TAG']=='INFDOC' && isset($INFCTENORM)){
						$INFDOC=$dom->createElement("infDoc");
						$INFCTENORM->appendChild($INFDOC);
#echo "infdoc criado";
					}elseif(($v2['TAG']=='INFNFE' || $v2['TAG']=='INFOUTROS') && isset($INFDOC)){
						if($v2['TAG']=='INFNFE')	$tmp_grupo	=$dom->createElement("infNFe");
						if($v2['TAG']=='INFOUTROS')	$tmp_grupo	=$dom->createElement("infOutros");
						foreach($campos as $nome_campo){
							if(	$nome_campo=='PIN' || //nfe
								$nome_campo=='descOutros' || 
								$nome_campo=='nDoc' || 
								$nome_campo=='dEmi' || 
								$nome_campo=='vDocFisc'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFDOC->appendChild($tmp_grupo);
					}elseif(($v2['TAG']=='INFNF') && isset($INFDOC)){
						$INFNF	=$dom->createElement("infNF");
						foreach($campos as $nome_campo){
							if($nome_campo=='PIN' || $nome_campo=='nRoma' || $nome_campo=='nPed' || $nome_campo=='nPeso'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$INFNF->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFDOC->appendChild($INFNF);
					}elseif($v2['TAG']=='LOCRET'){
						$tmp_grupo	=$dom->createElement("locRet");
						foreach($campos as $nome_campo){
							if($nome_campo=='xCpl'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFNF->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='EXPED' || $v2['TAG']=='RECEB'){
						if($v2['TAG']=='RECEB'){
							$tmp_grupo	=$dom->createElement("receb");
							$tmp_grupo2	=$dom->createElement("enderReceb");
						}else{
							$tmp_grupo	=$dom->createElement("exped");
							$tmp_grupo2	=$dom->createElement("enderExped");
						}
						$cur_grupo	=& $tmp_grupo;
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if(	$nome_campo=='CNPJ' || $nome_campo=='CPF' || 
								$nome_campo=='xFant' || $nome_campo=='xCpl' || 
								$nome_campo=='email' || $nome_campo=='CEP'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if(	$nome_campo=='cPais' || $nome_campo=='xPais'){
								if(empty($v2['cPais']) || empty($v2['xPais']))
									continue;
							}
							if ($nome_campo=='xLgr'){
								unset($cur_grupo);
								// começa a preenche o 'enderToma'
								// xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais
								$cur_grupo = &$tmp_grupo2;
							}
							if ($nome_campo=='fone'){
								// adiciona o fone primeiro....
								if($nome_campo=='fone' && !empty($v2[$nome_campo]))
									$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
								unset($cur_grupo);
								// volta a preencher o 'toma04'
								$tmp_grupo->appendChild( $tmp_grupo2 );
								$cur_grupo = &$tmp_grupo;
								continue;
							}
							if($nome_campo=='fone' && empty($v2[$nome_campo]))
								continue;
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$cur_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$infCte->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='DEST'){
						$DEST		=$dom->createElement("dest");
						$tmp_grupo2	=$dom->createElement("enderDest");
						$cur_grupo	=& $DEST;
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if(	$nome_campo=='CNPJ' || $nome_campo=='CPF' || 
								$nome_campo=='xFant' || $nome_campo=='xCpl' || 
								$nome_campo=='email' || $nome_campo=='CEP' || 
								$nome_campo=='ISUF' || $nome_campo=='IE'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if(	$nome_campo=='cPais' || $nome_campo=='xPais'){
								if(empty($v2['cPais']) || empty($v2['xPais']))
									continue;
							}
							if ($nome_campo=='xLgr'){
								unset($cur_grupo);
								// começa a preenche o 'enderToma'
								// xLgr|nro|xCpl|xBairro|cMun|xMun|CEP|UF|cPais|xPais
								$cur_grupo = &$tmp_grupo2;
							}
							if ($nome_campo=='fone'){
								// adiciona o fone primeiro....
								if($nome_campo=='fone' && !empty($v2[$nome_campo]))
									$DEST->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
								unset($cur_grupo);
								// volta a preencher o 'toma04'
								$DEST->appendChild( $tmp_grupo2 );
								$cur_grupo = &$DEST;
								continue;
							}
							if($nome_campo=='fone' && empty($v2[$nome_campo]))
								continue;
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$cur_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$infCte->appendChild($DEST);
					}elseif($v2['TAG']=='LOCENT' && isset($DEST)){
						$tmp_grupo	=$dom->createElement("locEnt");
						// campo 'email' não existe no TXT
						foreach($campos as $nome_campo){
							if($nome_campo=='xCpl'){
								if(empty($v2[$nome_campo]))	
									continue;
							}
							if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
								continue;
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$DEST->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='VPREST'){
						$VPREST	=$dom->createElement("vPrest");
						$VPREST->appendChild( $dom->createElement('vTPrest',$v2['vTPrest']) );
						$VPREST->appendChild( $dom->createElement('vRec',$v2['vRec']) );
						$infCte->appendChild($VPREST);
					}elseif($v2['TAG']=='VPREST' && isset($VPREST)){
						$tmp_grupo=$dom->createElement("Comp");
						$tmp_grupo->appendChild( $dom->createElement('xNome',$v2['xNome']) );
						$tmp_grupo->appendChild( $dom->createElement('vComp',$v2['vComp']) );
						$VPREST->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='IMP'){
						$IMP=$dom->createElement("imp");
						$ICMS=$dom->createElement("ICMS");
						$IMP->appendChild( $ICMS );
						if(!empty($v2['infAdFisco']))
							$IMP->appendChild($dom->createElement('infAdFisco',$v2['infAdFisco']));
						// lei da transparencia
						if(!empty($v2['vTotTrib']) && strlen(trim($v2['vTotTrib']))>0)
							$IMP->appendChild($dom->createElement('vTotTrib',$v2['vTotTrib']));
						// lei da transparencia
						$infCte->appendChild($IMP);
					}elseif(in_array($v2['TAG'],array('ICMS00','ICMS20','ICMS45','ICMS60','ICMS90','ICMSOutraUF','ICMSSN') ) && 
						isset($IMP)){
#die('aki!' . $v2['TAG']);
						$tmp_grupo=$dom->createElement($v2['TAG']);
						foreach($campos as $nome_campo){
							#if($nome_campo=='CPF' && !empty($v2['CNPJ']))	// preferencia pelo CNPJ
							#	continue;
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$ICMS->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='INFCTENORM'){
						$INFCTENORM=$dom->createElement("infCTeNorm");
						$infCte->appendChild($INFCTENORM);
					
					// CTE NORMAL:
					}elseif($v2['TAG']=='INFCARGA' && isset($INFCTENORM)){
						$INFCARGA=$dom->createElement("infCarga");
						foreach($campos as $nome_campo){
							if($nome_campo=='xOutCat' || $nome_campo=='vCarga'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$INFCARGA->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCTENORM->appendChild($INFCARGA);
					}elseif($v2['TAG']=='INFQ' && isset($INFCARGA)){
						$tmp_grupo=$dom->createElement("infQ");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCARGA->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='CONTQT' && isset($INFCTENORM)){
						$CONTQT=$dom->createElement("contQt");
						unset($CONTQT_dPrev);
						foreach($campos as $nome_campo){
							if($nome_campo=='dPrev'){
								if(empty($v2[$nome_campo]))
									continue;
								$CONTQT_dPrev=$dom->createElement($nome_campo,$v2[$nome_campo]);
								$CONTQT->appendChild($CONTQT_dPrev);
								continue;
							}
							$CONTQT->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCTENORM->appendChild($CONTQT);
					}elseif($v2['TAG']=='LACCONTQT' && isset($CONTQT)){
						$tmp_grupo=$dom->createElement("lacContQt");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						if(isset($CONTQT_dPrev))
							$CONTQT->insertBefore($infCte->appendChild($tmp_grupo),$CONTQT_dPrev);
						else
							$CONTQT->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='DOCANT' && isset($INFCTENORM)){
						$DOCANT=$dom->createElement("docAnt");
						$INFCTENORM->appendChild($DOCANT);
					}elseif($v2['TAG']=='EMIDOCANT' && isset($DOCANT)){
						$EMIDOCANT=$dom->createElement("emiDocAnt");
						foreach($campos as $nome_campo){
							$EMIDOCANT->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						unset($IDDOCANT);
						$IDDOCANT=$dom->createElement("idDocAnt");
						$EMIDOCANT->appendChild( $IDDOCANT );
						$DOCANT->appendChild( $EMIDOCANT );
					}elseif($v2['TAG']=='IDDOCANTPAP' && isset($IDDOCANT)){
						$tmp_grupo=$dom->createElement("idDocAntPap");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$IDDOCANT->appendChild( $tmp_grupo );
					}elseif($v2['TAG']=='IDDOCANTELE' && isset($IDDOCANT)){
						$tmp_grupo=$dom->createElement("idDocAntEle");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$IDDOCANT->appendChild( $tmp_grupo );
					}elseif($v2['TAG']=='SEG' && isset($INFCTENORM)){
						$tmp_grupo=$dom->createElement("seg");
						foreach($campos as $nome_campo){
							if($nome_campo!='respSeg'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCTENORM->appendChild( $tmp_grupo );
					}elseif($v2['TAG']=='INFMODAL' && isset($INFCTENORM)){
						$INFMODAL=$dom->createElement("infModal");
						#foreach($campos as $nome_campo){
						#	$INFMODAL->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						#}
						$INFMODAL->setAttribute("versaoModal", $v2['versaoModal']);
						$INFCTENORM->appendChild($INFMODAL);
					
					// RODOVIÁRIO
					}elseif($v2['TAG']=='RODO'){
						$RODO=$dom->createElement("rodo");
						foreach($campos as $nome_campo){
							if($nome_campo=='CIOT'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$RODO->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFMODAL->appendChild($RODO);
					}elseif($v2['TAG']=='OCC' && isset($RODO)){
						$RODO_OCC =$dom->createElement("occ");
						foreach($campos as $nome_campo){
							if($nome_campo=='serie'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$RODO_OCC->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO->appendChild($RODO_OCC);
					}elseif($v2['TAG']=='EMIOCC' && isset($RODO_OCC)){
						$tmp_grupo =$dom->createElement("emiOcc");
						foreach($campos as $nome_campo){
							if($nome_campo='cInt' || $nome_campo=='fone'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO_OCC->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='VALEPED' && isset($RODO)){
						$tmp_grupo =$dom->createElement("valePed");
						foreach($campos as $nome_campo){
							if($nome_campo=='CNPJPg'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='VEIC' && isset($RODO)){
						$RODO_VEIC =$dom->createElement("veic");
						foreach($campos as $nome_campo){
							if($nome_campo=='cInt'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$RODO_VEIC->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO->appendChild($RODO_VEIC);
					}elseif($v2['TAG']=='PROP' && isset($RODO_VEIC)){
						$tmp_grupo =$dom->createElement("prop");
						foreach($campos as $nome_campo){
							if($nome_campo=='cInt'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO_VEIC->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='LACRODO' && isset($RODO)){
						$tmp_grupo =$dom->createElement("lacRodo");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='MOTO' && isset($RODO)){
						$tmp_grupo =$dom->createElement("moto");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$RODO->appendChild($tmp_grupo);
						
					/// FIM RODOVIÁRIO
					}elseif($v2['TAG']=='PERI' && isset($INFCTENORM)){
						$tmp_grupo =$dom->createElement("peri");
						foreach($campos as $nome_campo){
							if($nome_campo=='grEmb' || $nome_campo=='qVolTipo' || $nome_campo=='pontoFulgor'){
								if(empty($v2[$nome_campo]))
									continue;
							}
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCTENORM->appendChild($tmp_grupo);
					}elseif($v2['TAG']=='VEICNOVOS' && isset($INFCTENORM)){
						$tmp_grupo =$dom->createElement("veicNovos");
						foreach($campos as $nome_campo){
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$INFCTENORM->appendChild($tmp_grupo);
					
					//COBR
					}elseif($v2['TAG']=='COBR'){
						$COBR = $dom->createElement("cobr");
						$infCte->appendChild($COBR);
					//FAT
					}elseif($v2['TAG']=='FAT' && isset($COBR)){
						$tmp_grupo = $dom->createElement("fat");
						foreach($campos as $nome_campo){
							if(empty($v2[$nome_campo])) continue;	// varsea dnovo
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$COBR->appendChild($tmp_grupo);
					//DUP
					}elseif($v2['TAG']=='DUP' && isset($COBR)){
						$tmp_grupo = $dom->createElement("dup");
						foreach($campos as $nome_campo){
							if(empty($v2[$nome_campo])) continue;	// varse dnovo
							$tmp_grupo->appendChild( $dom->createElement($nome_campo,$v2[$nome_campo]) );
						}
						$COBR->appendChild($tmp_grupo);
					// FIM CTE NORMAL
					
					
					
					}
					
				}
			}
			$CTe->appendChild($infCte);
			$dom->appendChild($CTe);
			////////
			// corrige chave CTE
			$ret=$this->__montaChaveXML($dom);
			if($ret!==true)
				$RETURN['erros'][$kcte][]="$MSG_PADRAO Erro ao calcular chave XML - $ret";
			////////
			if($output_string){
				$RETURN['xml'][$kcte]= $dom->saveXML();
				$RETURN['xml'][$kcte]= str_replace(
								'<?xml version="1.0" encoding="UTF-8  standalone="no"?>',
								'<?xml version="1.0" encoding="UTF-8"?>',
								$RETURN['xml'][$kcte]);
				//remove linefeed, carriage return, tabs e multiplos espaços
				$RETURN['xml'][$kcte]= preg_replace('/\s\s+/',' ', $RETURN['xml'][$kcte]);
				$RETURN['xml'][$kcte]= str_replace("> <","><", $RETURN['xml'][$kcte]);
			}else{
				$RETURN['xml'][$kcte]= $dom;
			}
			unset($dom,$CTe,$infCte);
		}
		return($RETURN);
	}
	private function __montaChaveXML(& $dom){
		$ide    = $dom->getElementsByTagName("ide")->item(0);
		if(empty($ide))		return("'ide' não encontrado");
		$emit   = $dom->getElementsByTagName("emit")->item(0);
		if(empty($emit))	return("'emit' não encontrado");
		$cUF    = $ide->getElementsByTagName('cUF')->item(0);
		if(empty($cUF))		return("'cUF' não encontrado");		$cUF = $cUF->nodeValue;
		$dhEmi   = $ide->getElementsByTagName('dhEmi')->item(0);
		if(empty($dhEmi))	return("'dhEmi' não encontrado");	$dhEmi = $dhEmi->nodeValue;
		$CNPJ   = $emit->getElementsByTagName('CNPJ')->item(0);
		if(empty($CNPJ))	return("'CNPJ' não encontrado");	$CNPJ = $CNPJ->nodeValue;
		$mod    = $ide->getElementsByTagName('mod')->item(0);
		if(empty($mod))		return("'mod' não encontrado");		$mod = $mod->nodeValue;
		$serie  = $ide->getElementsByTagName('serie')->item(0);
		if(empty($serie))	return("'serie' não encontrado");	$serie = $serie->nodeValue;
		$nCT    = $ide->getElementsByTagName('nCT')->item(0);
		if(empty($nCT))		return("'nCT' não encontrado");		$nCT = $nCT->nodeValue;
		$tpEmis = $ide->getElementsByTagName('tpEmis')->item(0);
		if(empty($tpEmis))	return("'tpEmis' não encontrado");	$tpEmis = $tpEmis->nodeValue;
		$cCT    = $ide->getElementsByTagName('cCT')->item(0);
		if(empty($cCT))		return("'cCT' não encontrado");		$cCT = $cCT->nodeValue;
		$cDV    = $ide->getElementsByTagName('cDV')->item(0);
		if(empty($cDV))		return("'cDV' não encontrado");
		
		
		if( strlen($cCT) != 8 ){	// gera o numero aleatório
			$cCT = $ide->getElementsByTagName('cCT')->item(0)->nodeValue = rand( 0 , 99999999 );
		}
		$tempData = explode("-", $dhEmi);
		if(!isset($tempData[0]))	$tempData[0]=0;
		if(!isset($tempData[1]))	$tempData[1]=0;
		
		$CNPJ = preg_replace("/[^0-9]/", "", $CNPJ);
		$tempChave =	substr(str_pad(abs((int)$cUF			), 2,'0',STR_PAD_LEFT),0, 2).
				substr(str_pad(abs((int)$tempData[0] - 2000 	), 2,'0',STR_PAD_LEFT),0, 2).
				substr(str_pad(abs((int)$tempData[1] 		), 2,'0',STR_PAD_LEFT),0, 2).
				substr(str_pad($CNPJ 				 ,14,'0',STR_PAD_LEFT),0,14).
				substr(str_pad(abs((int)$mod 			), 2,'0',STR_PAD_LEFT),0, 2).
				substr(str_pad(abs((int)$serie 			), 3,'0',STR_PAD_LEFT),0, 3).
				substr(str_pad(abs((int)$nCT 			), 9,'0',STR_PAD_LEFT),0, 9).
				substr(str_pad(abs((int)$tpEmis 		), 1,'0',STR_PAD_LEFT),0, 1).
				substr(str_pad(abs((int)$cCT			), 8,'0',STR_PAD_LEFT),0, 8);
		//		00.20.00.00000000000000.00.000.000000000.0.18641952.6
		//$forma = 	"%02d%02d%02d%s%02d%03d%09d%01d%08d";//%01d";
		$cDV    = $ide->getElementsByTagName('cDV')->item(0)->nodeValue  = $this->__calculaDV($tempChave);
		$chave  = $tempChave .= $cDV;
		$infCte = $dom->getElementsByTagName("infCte")->item(0);
		if(empty($infCte))		return("'infCte' não encontrado");
		$infCte->setAttribute("Id", "CTe" . $chave);
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
return(false);
		if(!is_array($xml))
			$xml=array($xml);
		$RETURN=array(	'erros'	=>'',
				'avisos'=>'',
				'txt'	=>'');
		foreach($xml as $kcte=>$tmp_xml){
			$MSG_PADRAO="[CTe $kcte]";
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
							$RETURN['erros'][$kcte][]="$MSG_PADRAO ".
							$e->level." ".$e->code." - ".$e->message;
						else
							$RETURN['avisos'][$kcte][]="$MSG_PADRAO ".
							$e->level." ".$e->code." - ".$e->message;
						
					}
				}
				unset($errors,$e);
			}else{
				$dom=$tmp_xml;
			}
			unset($tmp_xml);
			if(!is_object($dom)){
				$RETURN['erros'][$kcte][]="Não foi possivel criar o objeto DOMDocument para abrir o conteúdo XML";
				continue;
			}
			if(get_class($dom)!='DOMDocument'){
				$RETURN['erros'][$kcte][]="Tipo de objeto não é DOMDocument";
				continue;
			}
			$RETURN['txt'][$kcte]='';
			$CUR_TXT=& $RETURN['txt'][$kcte];
			$infCte = $dom->getElementsByTagName("infCte")->item(0);
			if (!isset($infCte)){
				$RETURN['erros'][$kcte][]="$MSG_PADRAO Tag infCte não encontrada";
				continue;
			}
			$versao = $infCte->getAttribute("versao");
			/// vamos lá.... processo reverso agora
			if($versao=='2.00'){
				// A
				$CAMPOS	=explode('|',$this->campos_v200['A']);
				foreach($CAMPOS as $k=>$v)
					if($k!=0 && strlen(trim($v))>0)
						$CAMPOS[$k]=$infCte->getAttribute($v);	// só atributos
				$CUR_TXT.=implode('|',$CAMPOS)."\n";
				$MSG_PADRAO="[CTe $kcte - ".$CAMPOS[2]."]";
				
				// B
				$ide=$dom->getElementsByTagName("ide")->item(0);
				if (empty($ide)){
					$RETURN['erros'][$kcte][]="$MSG_PADRAO Tag ide não encontrada";
					continue;
				}
				$CAMPOS	=explode('|',$this->campos_v200['B']);
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
					// B13 - refCTe
					$tmp_grupo=$NFref->getElementsByTagName("refCTe");
					if (!empty($tmp_grupo)){
						for($c = 0; $c<$tmp_grupo->length; $c++){
							$CAMPOS	=explode('|',$this->campos_v200['B13']);
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
							$CAMPOS	=explode('|',$this->campos_v200['B14']);
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
							$CAMPOS	=explode('|',$this->campos_v200['B20a']);
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
								$CAMPOS	=explode('|',$this->campos_v200['B20d']);
							}elseif(!empty($tmp2)){
								$CAMPOS	=explode('|',$this->campos_v200['B20e']);
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
							$CAMPOS	=explode('|',$this->campos_v200['B20i']);
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
							$CAMPOS	=explode('|',$this->campos_v200['B20j']);
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
				$infCte=$dom->getElementsByTagName("infCte")->item(0);
				if (empty($infCte)){
					$RETURN['erros'][$kcte][]="$MSG_PADRAO Tag infCte não encontrada";
					continue;
				}
				$emit=$infCte->getElementsByTagName("emit")->item(0);
				if (!empty($emit)){
					$CAMPOS	=explode('|',$this->campos_v200['C']);
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
						$CAMPOS	=explode('|',$this->campos_v200['C02']);
					}elseif(!empty($tmp2)){
						$CAMPOS	=explode('|',$this->campos_v200['C02a']);
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
						$CAMPOS	=explode('|',$this->campos_v200['C05']);
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
				$tmp_grupo=$infCte->getElementsByTagName("avulsa")->item(0);
				if (!empty($tmp_grupo)){
					$CAMPOS	=explode('|',$this->campos_v200['D']);
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
				$dest=$infCte->getElementsByTagName("dest")->item(0);
				if (!empty($dest)){
					$CAMPOS	=explode('|',$this->campos_v200['E']);
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
						$CAMPOS	=explode('|',$this->campos_v200['E02']);
					}elseif(!empty($tmp2)){
						$CAMPOS	=explode('|',$this->campos_v200['E03']);
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
						$CAMPOS	=explode('|',$this->campos_v200['E05']);
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
				$tmp_grupo=$infCte->getElementsByTagName("retirada")->item(0);
				if (!empty($tmp_grupo)){
					$CAMPOS	=explode('|',$this->campos_v200['F']);
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
						$CAMPOS	=explode('|',$this->campos_v200['F02']);
					}elseif(!empty($tmp2)){
						$CAMPOS	=explode('|',$this->campos_v200['F02a']);
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
				$tmp_grupo=$infCte->getElementsByTagName("entrega")->item(0);
				if (!empty($tmp_grupo)){
					$CAMPOS	=explode('|',$this->campos_v200['G']);
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
						$CAMPOS	=explode('|',$this->campos_v200['G02']);
					}elseif(!empty($tmp2)){
						$CAMPOS	=explode('|',$this->campos_v200['G02a']);
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
				$det=$infCte->getElementsByTagName("det");
				for($cur_item=0;$cur_item<$det->length;$cur_item++){
					$item=$det->item($cur_item);
					// H
					$nItem		= $item->getAttribute("nItem");
					$infAdProd	= $item->getElementsByTagName("infAdProd")->item(0);
					if(!empty($infAdProd))
						$infAdProd=$infAdProd->nodeValue;
					$CUR_TXT	.="H|$nItem|$infAdProd|\r\n";

					// I
					$prod=$item->getElementsByTagName("prod")->item(0);
					$CAMPOS	=explode('|',$this->campos_v200['I']);
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
							$CAMPOS	=explode('|',$this->campos_v200['I18']);
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
									$CAMPOS	=explode('|',$this->campos_v200['I25']);
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
						$CAMPOS	=explode('|',$this->campos_v200['J']);
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
							$CAMPOS	=explode('|',$this->campos_v200['K']);
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
							$CAMPOS	=explode('|',$this->campos_v200['L']);
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
							$CAMPOS	=explode('|',$this->campos_v200['L01']);
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
								$CAMPOS	=explode('|',$this->campos_v200['L105']);
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
					$CUR_TXT	.="M|\n";
					// N - ICMS
					$CUR_TXT	.="N|\n";
					// NXXXX
					$imposto=$item->getElementsByTagName("imposto")->item(0);
					$N=NULL;
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS00");	if(!empty($N))$N='N02'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS10");	if(!empty($N))$N='N03'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS20");	if(!empty($N))$N='N04'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS30");	if(!empty($N))$N='N05'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS40");	if(!empty($N))$N='N06'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS51");	if(!empty($N))$N='N07'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS60");	if(!empty($N))$N='N08'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS70");	if(!empty($N))$N='N09'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMS90");	if(!empty($N))$N='N10'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSPart");	if(!empty($N))$N='N10a'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSST");	if(!empty($N))$N='N10b'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN101");	if(!empty($N))$N='N10c'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN102");	if(!empty($N))$N='N10d'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN201");	if(!empty($N))$N='N10e'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN202");	if(!empty($N))$N='N10f'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN500");	if(!empty($N))$N='N10g'; else $N=NULL;	}
					if(empty($N)){	$N=$imposto->getElementsByTagName("ICMSSN900");	if(!empty($N))$N='N10h'; else $N=NULL;	}
					
					$tmp_grupo=$imposto->getElementsByTagName("ICMS")->item(0);
					if (!empty($tmp_grupo)){
						$CAMPOS	=explode('|',$this->campos_v200[$N]);
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
						$CAMPOS	=explode('|',$this->campos_v200['O']);
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
						$CAMPOS	=explode('|',$this->campos_v200['O08']);
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
						$CAMPOS	=explode('|',$this->campos_v200['O07']);
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
							$CAMPOS	=explode('|',$this->campos_v200['O10']);
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
							$CAMPOS	=explode('|',$this->campos_v200['O11']);
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
						$CAMPOS	=explode('|',$this->campos_v200['P']);
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
						$CAMPOS	=explode('|',$this->campos_v200['U']);
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
						$CAMPOS	=explode('|',$this->campos_v200['Q02']);
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
						$CAMPOS	=explode('|',$this->campos_v200['Q03']);
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
						$CAMPOS	=explode('|',$this->campos_v200['Q04']);
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
						$CAMPOS	=explode('|',$this->campos_v200['Q05']);
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
							$CAMPOS	=explode('|',$this->campos_v200['Q07']);
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
							$CAMPOS	=explode('|',$this->campos_v200['Q10']);
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
						$CAMPOS	=explode('|',$this->campos_v200['R']);
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
							$CAMPOS	=explode('|',$this->campos_v200['R02']);
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
							$CAMPOS	=explode('|',$this->campos_v200['R04']);
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
						$CAMPOS	=explode('|',$this->campos_v200['S02']);
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
						$CAMPOS	=explode('|',$this->campos_v200['S03']);
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
						$CAMPOS	=explode('|',$this->campos_v200['S04']);
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
						$CAMPOS	=explode('|',$this->campos_v200['S05']);
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
							$CAMPOS	=explode('|',$this->campos_v200['S07']);
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
							$CAMPOS	=explode('|',$this->campos_v200['S09']);
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
						$CAMPOS	=explode('|',$this->campos_v200['T']);
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
							$CAMPOS	=explode('|',$this->campos_v200['T02']);
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
							$CAMPOS	=explode('|',$this->campos_v200['T04']);
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
				$total=$infCte->getElementsByTagName("total")->item(0);
				if(!empty($total)){
					$CUR_TXT.="W|\n";
					$tmp_grupo=$total->getElementsByTagName("ICMSTot")->item(0);
					if(!empty($tmp_grupo)){
						$CAMPOS	=explode('|',$this->campos_v200['W02']);
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
						$CAMPOS	=explode('|',$this->campos_v200['W17']);
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
						$CAMPOS	=explode('|',$this->campos_v200['W23']);
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
				$transp=$infCte->getElementsByTagName("transp")->item(0);
				if(!empty($transp)){
					$CAMPOS	=explode('|',$this->campos_v200['X']);
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
						$CAMPOS	=explode('|',$this->campos_v200['X03']);
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
							$CAMPOS	=explode('|',$this->campos_v200['X04']);
						}elseif(!empty($tmp2)){
							$CAMPOS	=explode('|',$this->campos_v200['X05']);
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
						$CAMPOS	=explode('|',$this->campos_v200['X11']);
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
						$CAMPOS	=explode('|',$this->campos_v200['X18']);
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
							$CAMPOS	=explode('|',$this->campos_v200['X22']);
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
							$CAMPOS	=explode('|',$this->campos_v200['X26']);
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
									$CAMPOS	=explode('|',$this->campos_v200['X33']);
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
				$cobr=$infCte->getElementsByTagName("cobr")->item(0);
				if(!empty($cobr)){
					$CUR_TXT.="Y|\n";
					$tmp_grupo=$cobr->getElementsByTagName("fat")->item(0);
					if(!empty($tmp_grupo)){
						$CAMPOS	=explode('|',$this->campos_v200['Y02']);
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
							$CAMPOS	=explode('|',$this->campos_v200['Y07']);
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
				$infAdic=$infCte->getElementsByTagName("infAdic")->item(0);
				if(!empty($infAdic)){
					$CAMPOS	=explode('|',$this->campos_v200['Z']);
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
					if(!empty($tmp_grupo)){
						for($c = 0; $c<$tmp_grupo->length; $c++){
							$CAMPOS	=explode('|',$this->campos_v200['Z04']);
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
					$tmp_grupo=$infAdic->getElementsByTagName("obsFisco");
					if(!empty($tmp_grupo)){
						for($c = 0; $c<$tmp_grupo->length; $c++){
							$CAMPOS	=explode('|',$this->campos_v200['Z07']);
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
					$tmp_grupo=$infAdic->getElementsByTagName("procRef");
					if(!empty($tmp_grupo)){
						for($c = 0; $c<$tmp_grupo->length; $c++){
							$CAMPOS	=explode('|',$this->campos_v200['Z10']);
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
				$tmp_grupo=$infCte->getElementsByTagName("exporta")->item(0);
				if(!empty($tmp_grupo)){
					$CAMPOS	=explode('|',$this->campos_v200['ZA']);
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
				$tmp_grupo=$infCte->getElementsByTagName("compra")->item(0);
				if(!empty($tmp_grupo)){
					$CAMPOS	=explode('|',$this->campos_v200['ZB']);
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
				$cana=$infCte->getElementsByTagName("cana")->item(0);
				if(!empty($cana)){
					$CAMPOS	=explode('|',$this->campos_v200['ZC01']);
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
							$CAMPOS	=explode('|',$this->campos_v200['ZC04']);
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
							$CAMPOS	=explode('|',$this->campos_v200['ZC10']);
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
/// teste:
$nfe=new ConvertCteNFePHP();
$TXT_ORI=implode("\n", $nfe->campos_v200 );
#$TXT_ORI=implode("|\n", array_keys($nfe->campos_v200) );$TXT_ORI=str_replace("A|\nB|","A|2.00|ID|\nB|",$TXT_ORI);
$TXT_ORI=str_replace('versao','2.00',$TXT_ORI);
#$TXT_ORI="A|2.00|ID|\nB|\nC|\n";

$XML=$nfe->TXT2XML( $TXT_ORI,true );//$TXT_ORI,true );
var_dump($XML['xml'][0]['xml']);
$TXT=$nfe->XML2TXT( $XML['xml'][0]['xml'] );
var_dump($TXT);
*/
