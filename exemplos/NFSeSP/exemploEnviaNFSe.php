<?php

// Teste de envio NFS-e Prefeitura São Paulo

require('libs/NFSeSP.class.php');
require('libs/NFeRPS.class.php');

$nfse = new NFSeSP();

$rps = new NFeRPS();

$rps->CCM = 'xxxxxxxx';  // inscriçãoo municipal da Empresa
$rps->serie = 'A';       // serie do RPS gerado
$rps->numero = '1';      // numero do RPS gerado

$rps->dataEmissao = date("Y-m-d");
$rps->valorServicos = 1;
$rps->valorDeducoes = 0;
$rps->codigoServico = '07498';   // codigo do serviço executado
$rps->aliquotaServicos = 0.05;
$rps->tributacao = "T";
$rps->discriminacao = 'Teste de geracao de NFS-e via sistema proprio';

$rps->contractorRPS = new ContractorRPS();

$rps->contractorRPS->cnpjTomador = 'cpf_do_destinatario';
$rps->contractorRPS->ccmTomador = '';
$rps->contractorRPS->type = 'F';		// C=Pessoa Juridica, F=Pessoa Fisica
$rps->contractorRPS->name = 'nome_do_destinatario';
$rps->contractorRPS->tipoEndereco = "R";  // Rua
$rps->contractorRPS->endereco = 'endereço_do_destinatario';
$rps->contractorRPS->enderecoNumero = 'numero';
$rps->contractorRPS->complemento = 'complemento';
$rps->contractorRPS->bairro = 'bairro_do_destinatario';
$rps->contractorRPS->cidade = '3550308';
$rps->contractorRPS->estado = 'SP';
$rps->contractorRPS->cep = 'cep_do_destinatario_sem_espaco_ou_traço';
$rps->contractorRPS->email = 'email_do_destinatario';

$rpsArray[] = $rps;

$rangeDate['inicio'] = date("Y-m-d");
$rangeDate['fim']   = date("Y-m-d");
$valorTotal['servicos'] = 1;
$valorTotal['deducoes'] = 0;

$ret = $nfse->sendRPS ($rps);

$docxml = $ret->saveXML();

//print_r($ret);
//echo "<br>\n";
//print_r($docxml);
//echo "<br>\n";

if ($ret->Cabecalho->Sucesso == "true") {
   if ($ret->Cabecalho->Alerta) {
      $errMsg = "Erro " . $ret->Cabecalho->Alerta->Codigo . " - ";
      $errMsg.=  utf8_decode($ret->Cabecalho->Alerta->Descricao);
   }

   if ($ret->Cabecalho->Erro) {
      $errMsg = "Erro " . $ret->Cabecalho->Erro->Codigo . " - ";
      $errMsg.=  utf8_decode($ret->Cabecalho->Erro->Descricao);
   }
} else {
   if ($ret->Cabecalho->Erro) {
      $errMsg = "Erro " . $ret->Cabecalho->Erro->Codigo . " - ";
      $errMsg.=  utf8_decode($ret->Cabecalho->Erro->Descricao);
   } else {
      $errMsg = utf8_decode("Erro no processamento da solicitação");
   }
}

if ($errMsg == "") {
   // obtem dados da Nota Fiscal
   $NumeroNFe = trim($ret->ChaveNFeRPS->ChaveNFe->NumeroNFe);
   $CodVer   = trim($ret->ChaveNFeRPS->ChaveNFe->CodigoVerificacao);

   // Como a Prefeitura de São Paulo desconsidera os dados do destinatario que voce envia
   // e mantêm o que esta cadastrado no banco de dados deles...
   // Consulta NFS-e para acertar data / hora / Endereço do destinatario
   $ret = $nfse->queryNFe($NumeroNFe,0,'');
   if ($ret->Cabecalho->Sucesso) {
      $DtEmi = $ret->NFe->DataEmissaoNFe;
      if (strlen($DtEmi) == 19) {
         $HoraEmi = substr($DtEmi,11,2) . substr($DtEmi,14,2) . substr($DtEmi,17,2);
         $DataEmi = substr($DtEmi,0,4) . substr($DtEmi,5,2) . substr($DtEmi,8,2);
         $Tomador   = utf8_decode($ret->NFe->RazaoSocialTomador);
         $FavEmail  = $ret->NFe->EmailTomador;
         if ($FavEmail == "") {
            $FavEmail = "-----";
         }
         $FavRua    = $ret->NFe->EnderecoTomador->TipoLogradouro . " ";
         $FavRua   .= utf8_decode($ret->NFe->EnderecoTomador->Logradouro);
         $FavRua    = replace("'","`",$FavRua);
         $FavRuaNum = $ret->NFe->EnderecoTomador->NumeroEndereco;
         $FavRuaCpl = $ret->NFe->EnderecoTomador->ComplementoEndereco;
         $FavCep    = $ret->NFe->EnderecoTomador->CEP;
         if (strlen($FavCep) < 8) {
            $FavCep = str_repeat("0", 8 - strlen($FavCep)) . $FavCep;
         }
         $FavBairro = utf8_decode($ret->NFe->EnderecoTomador->Bairro);
         $FavBairro = replace("'","`",$FavBairro);
         $FavCidade = $ret->NFe->EnderecoTomador->Cidade;
         $FavEstado = $ret->NFe->EnderecoTomador->UF;
         $VrCredito = $ret->NFe->ValorCredito;
      }
      //
      // insira aqui sua rotina de atualização do banco de dados
      //
   }
}

?>
