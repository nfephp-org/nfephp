<?php

/**
 * NFSe
 * Exemplo de envio de RPS de Prefeitura de Campinas
 * Contribuição de Renan Ferreira <rhfphp at gmail dot com>
 * em 12/12/2014
 */

include('libs/NFe/ToolsNFePHP.class.php');
$tools = new ToolsNFePHP();


/* Dados Editaveis */
$InscricaoMunicipalPrestador_ = '1105555';
$ValorServicoSubtraindoDeducao = '100.00';
$ValorDeducao = '0.00';
$CPFCNPJRemetente = '07401432000153';
$RazaoSocialRemetente = 'VERCAN TENOLOGIA';
$DDDPrestador = '19';
$TelefonePrestador = '32910004';

$DataInicioServico = date('Y-m-d');
$DataFimServico = date('Y-m-d');

$ValorTotalServicos = '100.00';
$ValorTotalDeducoes = '0.00';
/* Fim Dados Editaveis */


$body = '<ns1:ReqEnvioLoteRPS xmlns:ns1="http://localhost:8080/WsNFe2/lote" xmlns:tipos="http://localhost:8080/WsNFe2/tp" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://localhost:8080/WsNFe2/lote http://localhost:8080/WsNFe2/xsd/ReqEnvioLoteRPS.xsd">';




	$body .= '
	<Cabecalho>
		<CodCidade>6291</CodCidade>
		<CPFCNPJRemetente>'.$CPFCNPJRemetente.'</CPFCNPJRemetente>
		<RazaoSocialRemetente>'.$RazaoSocialRemetente.'</RazaoSocialRemetente>
		<transacao>true</transacao>
		<dtInicio>'.$DataInicioServico.'</dtInicio>
		<dtFim>'.$DataFimServico.'</dtFim>
		<QtdRPS>1</QtdRPS>
		<ValorTotalServicos>'.$ValorTotalServicos.'</ValorTotalServicos>
		<ValorTotalDeducoes>'.$ValorTotalDeducoes.'</ValorTotalDeducoes>
		<Versao>1</Versao>
		<MetodoEnvio>WS</MetodoEnvio>
	</Cabecalho>
	
	<Lote Id="lote:1">';
		
		/* Dados Tomador Editaveis */
		$NumeroRPS_ = 1;
		$CpfCnpjTomador_ = '25475485423';
		$RazaoSocialTomador = 'RENAN HENRIQUE FERREIRA';
		$EmailTomador = 'rhfphp@gmail.com';
		$DDDTomador = '19';
		$TelefoneTomador = '91240092';
		$TipoLogradouroTomador = 'R';
		$LogradouroTomador = 'Luiz Teste,';
		$NumeroEnderecoTomador = '1000';
		$TipoBairroTomador = 'BAIRRO';
		$BairroTomador = 'Castelo';
		$CidadeTomador = '0007225';
		$CEPTomador = '13070727';
		
		
		$Tributacao_ = 'H';
		$CodAtividade_ = '951180000';
		$AliquotaAtividade = '2.79';
		/* Fim Dados Tomador Editaveis */
		
		
		/* Montagem de assinatura Não mexer */
		$CpfCnpjTomador = str_pad($CpfCnpjTomador_, 14, "0", STR_PAD_LEFT);
		$NumeroRPS = str_pad($NumeroRPS_, 12, "0", STR_PAD_LEFT);
		$CodAtividade = str_pad($CodAtividade_, 10, "0", STR_PAD_LEFT);
		$Tributacao = str_pad($Tributacao_, 2, " ", STR_PAD_RIGHT);
		$InscricaoMunicipalPrestador = str_pad($InscricaoMunicipalPrestador_, 11, "0", STR_PAD_LEFT);
		$SerieRPS = str_pad('NF', 5, " ", STR_PAD_RIGHT);
		$DataEmissao = date('Ymd');
		$SituacaoRPS = 'N';
		$TipoRecolhimento = 'N';
		$ValorServicoSubtraindoDeducao = str_pad(str_replace('.', ''￼, $ValorServicoSubtraindoDeducao), 15, "0", STR_PAD_LEFT);
		$ValorDeducao = str_pad(str_replace('.', ''￼, $ValorDeducao), 15, "0", STR_PAD_LEFT);
		$assinatura = sha1($InscricaoMunicipalPrestador
						.$SerieRPS
						.$NumeroRPS
						.$DataEmissao
						.$Tributacao
						.$SituacaoRPS
						.$TipoRecolhimento
						.$ValorServicoSubtraindoDeducao
						.$ValorDeducao
						.$CodAtividade
						.$CpfCnpjTomador);
		/* Fim Montagem de assinatura Não mexer */
		
				
		$body .= '
		<RPS Id="rps:'.$NumeroRPS_.'">
	
		<Assinatura>'.$assinatura.'</Assinatura>
		<InscricaoMunicipalPrestador>'.$InscricaoMunicipalPrestador_.'</InscricaoMunicipalPrestador>
		<RazaoSocialPrestador>'.$RazaoSocialRemetente.'</RazaoSocialPrestador>
		<TipoRPS>RPS</TipoRPS>
		<SerieRPS>NF</SerieRPS>
		<NumeroRPS>'.$NumeroRPS_.'</NumeroRPS>
		<DataEmissaoRPS>'.date('Y-m-d').'T'.date('H:i:s').'</DataEmissaoRPS>
		<SituacaoRPS>N</SituacaoRPS>
		<SeriePrestacao>99</SeriePrestacao>
		<InscricaoMunicipalTomador>0000000</InscricaoMunicipalTomador>
		<CPFCNPJTomador>'.$CpfCnpjTomador_.'</CPFCNPJTomador>
		<RazaoSocialTomador>'.$RazaoSocialTomador.'</RazaoSocialTomador>
		<TipoLogradouroTomador>'.$TipoLogradouroTomador.'</TipoLogradouroTomador>
		<LogradouroTomador>'.$LogradouroTomador.'</LogradouroTomador>
		<NumeroEnderecoTomador>'.$NumeroEnderecoTomador.'</NumeroEnderecoTomador>
		<TipoBairroTomador>'.$TipoBairroTomador.'</TipoBairroTomador>
		<BairroTomador>'.$BairroTomador.'</BairroTomador>
		<CidadeTomador>'.$CidadeTomador.'</CidadeTomador>
		<CidadeTomadorDescricao/>
		<CEPTomador>'.$CEPTomador.'</CEPTomador>
		<EmailTomador>'.$EmailTomador.'</EmailTomador>
		
		<CodigoAtividade>'.$CodAtividade_.'</CodigoAtividade>
		<AliquotaAtividade>'.$AliquotaAtividade.'</AliquotaAtividade>
		
		<TipoRecolhimento>A</TipoRecolhimento>
		<MunicipioPrestacao>0006291</MunicipioPrestacao>
		<MunicipioPrestacaoDescricao>CAMPINAS</MunicipioPrestacaoDescricao>
		<Operacao>A</Operacao>
		
		<Tributacao>'.$Tributacao_.'</Tributacao>
		
		<ValorPIS>0.00</ValorPIS>
		<ValorCOFINS>0.00</ValorCOFINS>
		<ValorINSS>0.00</ValorINSS>
		<ValorIR>0.00</ValorIR>
		<ValorCSLL>0.00</ValorCSLL>
		<AliquotaPIS>0.00</AliquotaPIS>
		<AliquotaCOFINS>0.00</AliquotaCOFINS>
		<AliquotaINSS>0.00</AliquotaINSS>
		<AliquotaIR>0.00</AliquotaIR>
		<AliquotaCSLL>0.00</AliquotaCSLL>
		<DescricaoRPS>Servicos Prestados por '.$RazaoSocialTomador.'</DescricaoRPS>
		
		<DDDPrestador>'.$DDDPrestador.'</DDDPrestador>
		<TelefonePrestador>'.$TelefonePrestador.'</TelefonePrestador>
		<DDDTomador>'.$DDDTomador.'</DDDTomador>
		<TelefoneTomador>'.$TelefoneTomador.'</TelefoneTomador>
		
		<Deducoes></Deducoes>
		
		<Itens>
			<Item>
			<DiscriminacaoServico>Teste</DiscriminacaoServico>
			<Quantidade>1</Quantidade>
			<ValorUnitario>100.00</ValorUnitario>
			<ValorTotal>100.00</ValorTotal>
			</Item>
		</Itens>
	
		</RPS>
	</Lote>
	';


$body .='</ns1:ReqEnvioLoteRPS>';
$sXmlAssinado = $tools->signXML($body, 'Lote');

$client = new SoapClient('http://issdigital.campinas.sp.gov.br/WsNFe2/LoteRps.jws?wsdl'); 
$result = $client->__soapCall('enviar', array($sXmlAssinado));
 
print_r($result);