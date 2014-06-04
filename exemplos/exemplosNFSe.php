<?php
/**
 * NFS-e (Nota Fiscal Eletronica de Serviço)
 * 
 * @author Bruno PorKaria <bruno at porkaria dot com dot br> 
 * @author Hugo Cegana <cegana at gmail dot com>
 */
include_once '../libs/NuSOAP/nusoap.php';
require_once "../libs/NFSePHPGinfes.class.php";

$nfse = new NFSePHPGinfes(false, 1, false);

$tarefa = $_GET['t'];
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
// CNPJ E INSCRIÇÃO MUNICIPAL DO EMISSOR
$cnpj         = '00000000000000';
$im           = '000000';
$razaoSocial  = 'EMISSOR DE SERVICOS LTDA';
$nomeFantasia = 'EMISSOR DE SERVICOS ';
//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

$parametro = $_GET['p'];

switch ($tarefa) {
    case "consultar-situacao-rps":

        $retorno = $nfse->consultarSituacaoLoteRps($parametro, $cnpj, $im);
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }
        break;

    case "consultar-lote-protocolo":
        $retorno = $nfse->consultarLoteRps($parametro, $cnpj, $im);
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }

        break;

    case "consultar-nfse-rps":
        $numrps = $parametro;
        $tipo = 1;
        $serie = 1;
        $retorno = $nfse->consultarNfseRps($numrps, $tipo, $serie, $cnpj, $im);
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }

        break;

    case "consultar-nfse-numero":
        $numnfse = $parametro;
        $retorno = $nfse->consultarNfse($numnfse, $cnpj, $im);
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }
        break;

    case "consultar-nfse-datas":
        $numnfse = $parametro;
        $retorno = $nfse->consultarNfse(false, $cnpj, $im, '2012-10-01', date("Y-m-d"));
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }
        break;


    case "cancelar-nfse":
        $numnfse = $parametro;
        $retorno = $nfse->cancelarNfse($numnfse, $cnpj, $im);
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }
        break;

    case "pdf":
        $numnfse = $parametro;
        $nfse->gerarPDF($numnfse);
        break;
    case "enviar":
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=PREENCHENDO DADOS-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        // EXEMPLO COM DUAS NOTAS 
        
        $idLote = $parametro; // número qualquer desde que não exista ainda no ginfes
        
        $rps    = $parametro; // número superior ao ultimo rps enviado
        
        $aLote = array();

        //$this->numeroLote   = date("ym").sprintf("%011s", $idlote);
        // classe para preenchimento dos dados
        $oNF = new NFSePHPGinfesData($rps++);

        //- - - - - - - - - - - - - - DADOS DO EMISSOR - - - - - - - - - - - - - - 
        
        $oNF->set('razaoSocial', $razaoSocial);
        $oNF->set('nomeFantasia', $nomeFantasia);
        $oNF->set('CNPJ', $cnpj);
        $oNF->set('IM', $im);
        

        // série da nota fiscal
        $oNF->set('numSerie', '1');

        // 1 = nota conjugada / 2-mista / 3-cupom
        $oNF->set('tipo', '1');

        /*
          01 – Tributação no municipio;
          02 – Tributação fora do municipio;
          03 – Isenção;
          04 – Imune;
          05 – Exigibilidade suspensa por decisão judicial;
          06 – Exigibilidade suspensa por procedimento administrativo.
         */
        $oNF->set('natOperacao', '1');

        // 1 = sim | 2 = não
        $oNF->set('optanteSimplesNacional', '1');

        // 1 = sim | 2 = não
        $oNF->set('incentivadorCultural', '2');

        // 1 = sim | 2 = não
        $oNF->set('regimeEspecialTributacao', '5');

        // 1 - normal 2 - cancelado (status da nota fiscal)
        $oNF->set('status', '1');

        // código do municipio do prestador segundo tabela do ibge
        $oNF->set('cMun', '3525904'); // jundiaí
        //- - - - - - - - - - - - - - DESCRIÇÃO DOS SERVIÇOS - - - - - - - - - - - - - - 
        $oNF->setItem('valorServicos', 200);
        $oNF->setItem('valorDeducoes', 0);
        $oNF->setItem('valorPis', 0);
        $oNF->setItem('valorCofins', 0);
        $oNF->setItem('valorInss', 0);
        $oNF->setItem('valorIr', 0);
        $oNF->setItem('valorCsll', 0);
        $oNF->setItem('issRetido', '2');
        $oNF->setItem('valorIss', 0);
        $oNF->setItem('valorIssRetido', 0);
        $oNF->setItem('outrasRetencoes', 1);
        $oNF->setItem('aliquota', 0.0350);
        //$oNF->setItem('valorIss', 0);
        $oNF->setItem('descontoIncondicionado', 0);
        $oNF->setItem('descontoCondicionado', 0);
        $oNF->setItem('itemListaServico', '1.03');
        $oNF->setItem('codigoTributacaoMunicipio', '1.03.01 / 670');
        $oNF->setItem('discriminacao', "LICENCA DE USO DE SOFTWARE");

        /*  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *
         * 
         * OBS: 
         * No item, os campos 'itemListaServico'  e 'codigoTributacaoMunicipio' precisam ter o EXATO formato conforme cadastro na prefeitura do municipio         
         * 
         * Para verificar qual o código de tributação referente ao serviço informado no arquivo acesse o Ginfes: http://PREFEITURADASUACIDADE.ginfes.com.br 
         * com o usuário e senha da empresa. 
         *
         * Clique em emitir NFS-e / clique em serviços prestados / clique na lupa ao lado de Código do Serviço/Atividade: informe o código ou a 
         * descrição do serviço na barra de pesquisa e clique em pesquisar / será exibido uma lista com todos os serviços referente ao código / 
         * descrição pesquisado, o código de tributação é a coluna código de atividade copie exatamente como demonstrado no sistema.
         * 
         * *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  */

        //- - - - - - - - - - - - - - DADOS DO TOMADOR DO SERVIÇO - - - - - - - 
        $oNF->set('tomaCPF', '00000000000000');
        //$oNF->set('tomaCNPJ', '');
        $oNF->set('tomaRazaoSocial', 'TOMADOR EXEMPLO 1');
        $oNF->set('tomaEndLogradouro', 'RUA ABILIO FIGUEIREDO 590');
        $oNF->set('tomaEndNumero', '900');
        $oNF->set('tomaEndComplemento', 'SALA 999');
        $oNF->set('tomaEndBairro', 'ANHANBAGAU');
        $oNF->set('tomaEndxMun', 'JUNDIAI');
        $oNF->set('tomaEndcMun', '3525904');
        $oNF->set('tomaEndUF', 'SP');
        $oNF->set('tomaEndCep', '31208140');
        $oNF->set('tomaEmail', 'exemplo@exemplo.com.br');
        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 
        $aLote[] = $oNF;
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=PREENCHENDO DADOS-=-=-=-=-=-=-=-=-=-=-
        //$this->numeroLote   = date("ym").sprintf("%011s", $idlote);
        // classe para preenchimento dos dados
        $oNF = new NFSePHPGinfesData($rps++);
        //- - - - - - - - - - - - - - DADOS DO EMISSOR - - - - - - - - - - - - -
        $oNF->set('razaoSocial', $razaoSocial);
        $oNF->set('nomeFantasia', $nomeFantasia);
        $oNF->set('CNPJ', '00000000000000');
        $oNF->set('IM', '00000');

        // série da nota fiscal
        $oNF->set('numSerie', '1');

        // 1 = nota conjugada / 2-mista / 3-cupom
        $oNF->set('tipo', '1');

        /*
          01 – Tributação no municipio;
          02 – Tributação fora do municipio;
          03 – Isenção;
          04 – Imune;
          05 – Exigibilidade suspensa por decisão judicial;
          06 – Exigibilidade suspensa por procedimento administrativo.
         */
        $oNF->set('natOperacao', '1');

        // 1 = sim | 2 = não
        $oNF->set('optanteSimplesNacional', '1');

        // 1 = sim | 2 = não
        $oNF->set('incentivadorCultural', '2');

        // 1 = sim | 2 = não
        $oNF->set('regimeEspecialTributacao', '5');

        // 1 - normal 2 - cancelado (status da nota fiscal)
        $oNF->set('status', '1');

        // código do municipio do prestador segundo tabela do ibge
        $oNF->set('cMun', '3525904'); // jundiaí
        //- - - - - - - - - - - - - - DESCRIÇÃO DOS SERVIÇOS - - - - - - - - - -
        $oNF->setItem('valorServicos', 100);
        $oNF->setItem('valorDeducoes', 0);
        $oNF->setItem('valorPis', 0);
        $oNF->setItem('valorCofins', 0);
        $oNF->setItem('valorInss', 0);
        $oNF->setItem('valorIr', 0);
        $oNF->setItem('valorCsll', 0);
        $oNF->setItem('issRetido', '2');
        $oNF->setItem('valorIss', 0);
        $oNF->setItem('valorIssRetido', 0);
        $oNF->setItem('outrasRetencoes', 1);
        $oNF->setItem('aliquota', 0.0350);
        $oNF->setItem('valorIss', 0);
        $oNF->setItem('descontoIncondicionado', 0);
        $oNF->setItem('descontoCondicionado', 0);
        $oNF->setItem('itemListaServico', '1.03');
        $oNF->setItem('codigoTributacaoMunicipio', '1.03.01 / 670');
        $oNF->setItem('discriminacao', "LICENCA DE USO DE SOFTWARE");

        /*  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  * 
         * OBS: 
         * No item, os campos 'itemListaServico'  e 'codigoTributacaoMunicipio' precisam ter o EXATO formato conforme cadastro na prefeitura do municipio         
         * 
         * Para verificar qual o código de tributação referente ao serviço informado no arquivo acesse o Ginfes: http://PREFEITURADASUACIDADE.ginfes.com.br 
         * com o usuário e senha da empresa. 
         *
         * Clique em emitir NFS-e / clique em serviços prestados / clique na lupa ao lado de Código do Serviço/Atividade: informe o código ou a 
         * descrição do serviço na barra de pesquisa e clique em pesquisar / será exibido uma lista com todos os serviços referente ao código / 
         * descrição pesquisado, o código de tributação é a coluna código de atividade copie exatamente como demonstrado no sistema.
         * 
         * 
         * *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  *  */

        //- - - - - - - - - - - - - - DADOS DO TOMADOR DO SERVIÇO - - - - - - - 
        $oNF->set('tomaCPF', '0000000000000');
        //$oNF->set('tomaCNPJ', '');

        $oNF->set('tomaRazaoSocial', 'TOMADOR EXEMPLO 2');
        $oNF->set('tomaEndLogradouro', 'RUA ABILIO FIGUEIREDO 600');
        $oNF->set('tomaEndNumero', '990');
        $oNF->set('tomaEndComplemento', 'SALA 144');
        $oNF->set('tomaEndBairro', 'ANHANBAGAU');
        $oNF->set('tomaEndxMun', 'JUNDIAI');
        $oNF->set('tomaEndcMun', '3525904');
        $oNF->set('tomaEndUF', 'SP');
        $oNF->set('tomaEndCep', '31208140');
        $oNF->set('tomaEmail', 'exemplo2@exemplo2.com.br');
        //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

        $aLote[] = $oNF;
        //-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

        $xmlLote = $nfse->montarLoteRps($idLote , $aLote);

        $retorno = $nfse->enviarLoteRps($xmlLote);
        
        if (!$retorno) {
            $msg = mostrarErro($nfse->errMsg);
        } else {
            $msg = mostrarSucesso($retorno);
        }

        break;
}

function mostrarErro($msg) {
    return '<div style="width:100%;border:solid 1px #000;background-color:#c0c080">' . nl2br($msg) . '</div>';
}

function mostrarSucesso($msg) {
    return "<textarea rows=50 style='width:100%'>$msg</textarea>";
}

$operacoes = array(
    "consultar-situacao-rps" => "Consultar Situação do Lote Por Protocolo",
    "consultar-lote-protocolo" => "Consultar Lote Por Protocolo",
    "consultar-nfse-rps" => "Consultar Por RPS",
    "consultar-nfse-numero" => "Consultar Por Número da NFS-e",
    "consultar-nfse-datas" => "Consultar Entre Datas",
    "cancelar-nfse" => "Cancelar NFS-e Por Número",
    "pdf" => "Gerar PDF (Por número da NFS-e)",
    "enviar" => "Enviar Lote RPS"
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Pragma" content="no-cache" />
        <title><? echo $_GET['t'] . " - " . $_GET['p']; ?> / TESTADOR</title>
    </head>
    <body>
        <form action="exemplos_nfse.php" method="get">
            Operação: 
            <select name="t">
                <?
                foreach ($operacoes as $v => $r) {
                    $chk = ($v == $_GET['t'] ? "selected" : "");
                    echo "<option $chk value='$v'>$r</option>";
                }
                ?>
            </select>
            Parametro:
            <input type="text" value="<? echo $_GET['p'] ?>" name="p" /> 
            <input type="submit" value="OK" />
        </form>
        <hr size="1" />
            <? echo $msg; ?>
    </body>
</html>