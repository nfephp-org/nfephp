<?php

/**
 * Class MakeNFeTest
 * @author Roberto L. Machado <linux.rlm at gmail dot com>
 * @author Jean Carlo Machado <contato@jeancarlomachado.com.br>
 */
use NFePHP\NFe\MakeNFe;

class MakeNFeTest extends PHPUnit_Framework_TestCase
{
    public $nfe;

    public function testeResultadoXml()
    {
        $this->nfe = new MakeNFe();

        $chave = '32160600445335300113550990000007921962533314';
        $tpAmb = '2';
        $finNFe = '1';
        $indFinal = '0';
        $indPres = '9';
        $procEmi = '0';
        $verProc = '4.0.43';
        $dhCont = '';
        $xJust = '';
        $versao = '3.10';

        $CNPJ = '02544316000170';
        $CPF = '';
        $xNome = '';
        $xFant = '';
        $IE = '';
        $IEST = '';
        $IM = '';
        $CNAE = '';
        $CRT = '';

        $cUF = '52';
        $cNF = '00000010';
        $natOp = 'Venda de Produto';
        $indPag = '1';
        $mod = '55';
        $serie = '1';
        $nNF = '10';
        $dhEmi = date("Y-m-d\TH:i:sP");
        $dhSaiEnt = date("Y-m-d\TH:i:sP");
        $tpNF = '1';
        $idDest = '1';
        $cMunFG = '5200258';
        $tpImp = '1';
        $tpEmis = '1';
        $cDV = substr($chave, -1);


        $this->nfe->tagide($cUF,
            $cNF,
            $natOp,
            $indPag,
            $mod,
            $serie,
            $nNF,
            $dhEmi,
            $dhSaiEnt,
            $tpNF,
            $idDest,
            $cMunFG,
            $tpImp,
            $tpEmis,
            $cDV,
            $tpAmb,
            $finNFe,
            $indFinal,
            $indPres,
            $procEmi,
            $verProc,
            $dhCont,
            $xJust
        );

        $resp = $this->nfe->taginfNFe($chave, $versao);
        $resp = $this->nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);

        $this->nfe->montaNFE();
        $xmlResult = $this->nfe->getXML();


        $xml = new \SimpleXMLElement($xmlResult);
        $xml->registerXPathNamespace('c', 'http://www.portalfiscal.inf.br/nfe');

        $this->assertEquals('02544316000170', $xml->xpath('//c:CNPJ')[0]->__toString());
    }
}
