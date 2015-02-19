<?php

namespace NFe;

/**
 * Classe principal para a comunicação com a SEFAZ
 * @category   NFePHP
 * @package    NFePHP\NFe\Convert
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use Common\Strings\Strings;
use Common\Exception\RuntimeException;
use NFe\MakeNFe;

class ConvertNFe
{
    
    public $limparString = true;
  
    private $version = '3.10';
    private $make;
    private $linhaB20a = array();
    private $linhaC = array();
    private $linhaE = array();
    private $linhaF = array();
    private $linhaG = array();
    
            
    /**
     * contruct
     * Método contrutor da classe
     * @param boolean $limparString Ativa flag para limpar os caracteres especiais e acentos
     * @return none
     */
    public function __construct($limparString = true)
    {
        $this->limparString = $limparString;
    }
    
    /**
     * txt2xml
     * Converte uma ou multiplas NF em formato txt em xml
     * @param mixed $txt Path para txt, txt ou array de txt
     * @return string
     */
    public function txt2xml($txt)
    {
        $aNF = array();
        if (is_file($txt)) {
            //extrai cada linha do arquivo em um campo de matriz
            $aDados = file($txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
        } elseif (is_array($txt)) {
            //carrega a matriz
            $aDados = $txt;
        } else {
            if (strlen($txt) > 0) {
                //carrega a matriz com as linha do arquivo
                $aDados = explode("\n", $txt);
            } else {
                return $aNF;
            }
        }
        //verificar se existem mais de uma NF
        $aNotas = $this->zSliceNotas($aDados);
        foreach ($aNotas as $nota) {
            $this->zArray2xml($nota);
            if ($this->make->montaNFe()) {
                $aNF[] = $this->make->getXML();
            }
        }
        return $aNF;
    }
    
    /**
     * zSliceNotas
     * Separa as notas em um array 
     * @param array $array
     * @return array
     */
    private function zSliceNotas($array)
    {
        $iCount = 0;
        $xCount = 0;
        $resp = array();
        foreach ($array as $linha) {
            if (substr($linha, 0, 4) == 'NOTA') {
                $resp[$xCount]['init'] = $iCount;
                if ($xCount > 0) {
                    $resp[$xCount -1]['fim'] = $iCount;
                }
                $xCount += 1;
            }
            $iCount += 1;
        }
        $resp[$xCount-1]['fim'] = $iCount;
        foreach ($resp as $marc) {
            $length = $marc['fim']-$marc['init'];
            $aNotas[] = array_slice($array, $marc['init'], $length, false);
        }
        return $aNotas;
    }
    
    /**
     * zArray2xml
     * Converte uma Nota Fiscal em um array de txt em um xml
     * @param array $aDados
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function zArray2xml($aDados = array())
    {
        foreach ($aDados as $dado) {
            $aCampos = $this->zClean(explode("|", $dado));
            $metodo = strtolower(str_replace(' ', '', $aCampos[0])).'Entity';
            if (! method_exists($this, $metodo)) {
                $msg = "O txt tem um metodo não definido!! $dado";
                throw new Exception\RuntimeException($msg);
            }
            $this->$metodo($aCampos);
        }
    }
    
    /**
     * zClean
     * Efetua limpeza dos campos
     * @param array $aCampos
     * @return array
     */
    private function zClean($aCampos = array())
    {
        foreach ($aCampos as $campo) {
            $campo = trim(preg_replace('/\s+/', ' ', $campo));
            if ($this->limparString) {
                $campo = Strings::cleanString($campo);
            }
        }
        return $aCampos;
    }
    
    /**
     * notafiscalEntity
     * Cria a entidade nota fiscal
     * @param array $aCampos
     */
    private function notafiscalEntity($aCampos)
    {
        $this->make = null;
        $this->make = new MakeNFe();
    }
    
    /**
     * aEntity
     * Cria a tag infNFe
     * @param array $aCampos
     * @throws Exception\RuntimeException
     */
    private function aEntity($aCampos)
    {
        //A|versao|Id|pk_nItem|
        if ($aCampos[1] != $this->version) {
            $msg = "A conversão somente para a versão $this->version !";
            throw new Exception\RuntimeException($msg);
        }
        $chave = preg_replace('/[^0-9]/', '', $aCampos[2]);
        $this->make->taginfNFe($chave, $aCampos[1]);
    }
    
    /**
     * bEntity
     * Cria a tag ide
     * @param array $aCampos
     */
    private function bEntity($aCampos)
    {
        //B|cUF|cNF|natOp|indPag|mod|serie|nNF|dhEmi
        // |dhSaiEnt|tpNF|idDest|cMunFG|tpImp|tpEmis
        // |cDV|tp Amb|finNFe|indFinal
        // |indPres|procEmi|verProc|dhCont|xJust|
        $this->make->tagide(
            $aCampos[1], //cUF
            $aCampos[2], //cNF
            $aCampos[3], //natOp
            $aCampos[4], //indPag
            $aCampos[5], //mod
            $aCampos[6], //serie
            $aCampos[7], //nNF
            $aCampos[8], //dhEmi
            $aCampos[9], //dhSaiEnt
            $aCampos[10], //tpNF
            $aCampos[11], //idDest
            $aCampos[12], //cMunFG
            $aCampos[13], //tpImp
            $aCampos[14], //tpEmis
            $aCampos[15], //cDV
            $aCampos[16], //tpAmb
            $aCampos[17], //finNFe
            $aCampos[18], //indFinal
            $aCampos[19], //indPres
            $aCampos[20], //procEmi
            $aCampos[21], //verProc
            $aCampos[22], //dhCont
            $aCampos[23] //xJust
        );
    }
    
    /**
     * ba02Entity
     * Cria a tag refNFe
     * @param array $aCampos
     */
    private function ba02Entity($aCampos)
    {
        //BA02|refNFe|
        $this->make->tagrefNFe($aCampos[1]);
    }
    
    /**
     * ba03Entity
     * Cria a tag refNF
     * @param array $aCampos
     */
    private function ba03Entity($aCampos)
    {
        //BA10|cUF|AAMM|IE|mod|serie|nNF|refCTe
        $this->make->tagrefNF(
            $aCampos[1], //cUF
            $aCampos[2], //aamm
            $aCampos[3], //cnpj
            $aCampos[4], //mod
            $aCampos[5], //serie
            $aCampos[6] //nNF
        );
    }
    
    private function ba13Entity($aCampos)
    {
        //BA13|CNPJ|
    }

    private function ba14Entity($aCampos)
    {
        //BA14|CPF|
    }

    
    /**
     * b20aEntity
     * @param array $aCampos
     */
    private function b20aEntity($aCampos)
    {
        //B20a|cUF|AAMM|IE|mod|serie|nNF
        $this->linhaB20a = $aCampos;
    }
    
    /**
     * b20dEntity
     * @param array $aCampos
     */
    private function b20dEntity($aCampos)
    {
        //B20d|CNPJ|
        $this->linhaB20a[] = $aCampos[1]; //CNPJ
        $this->linhaB20a[] = ''; //CPF
        $this->zLinhaB20aEntity($this->linhaB20a);
    }
    
    /**
     * b20eEntity
     * @param array $aCampos
     */
    private function b20eEntity($aCampos)
    {
        //B20d|CPF|
        $this->linhaB20a[] = ''; //CNPJ
        $this->linhaB20a[] = $aCampos[1]; //CPF
        $this->zLinhaB20aEntity($this->linhaB20a);
    }
    
    /**
     * zLinhaB20aEntity
     * Cria a tag refNFP
     * @param array $aCampos
     */
    private function zLinhaB20aEntity($aCampos)
    {
        //B20a|cUF|AAMM|IE|mod|serie|nNF|CNPJ|CPF
        $this->make->tagrefNFP(
            $aCampos[1], //cUF
            $aCampos[2], //aamm
            $aCampos[7], //cnpj
            $aCampos[8], //cpf
            $aCampos[3], //IE
            $aCampos[4], //mod
            $aCampos[5], //serie
            $aCampos[6] //nNF
        );
    }
    
    /**
     * b20iEntity
     * Cria a tag refCTe
     * @param array $aCampos
     */
    private function b20iEntity($aCampos)
    {
        //B20i|refCTe|
        $this->make->tagrefCTe($aCampos[1]);
    }
    
    /**
     * b20Entity
     * Cria a tag refECF
     * @param array $aCampos
     */
    private function b20Entity($aCampos)
    {
        //BA20|mod|nECF|nCOO|
        $this->make->tagrefECF(
            $aCampos[1], //mod
            $aCampos[2], //nECF
            $aCampos[3] //nCOO
        );
    }
    
    /**
     * cEntity
     * @param array $aCampos
     */
    private function cEntity($aCampos)
    {
        //C|XNome|XFant|IE|IEST|IM|CNAE|CRT|
        $this->linhaC = $aCampos;
    }
    
    /**
     * c02Entity
     * @param array $aCampos
     */
    private function c02Entity($aCampos)
    {
        //C02|cnpj|
        $this->linhaC[] = $aCampos[1]; //CNPJ
        $this->linhaC[] = '';//CPF
        $this->zLinhaCEntity($this->linhaC);
    }
    
    /**
     * c02aEntity
     * @param array $aCampos
     */
    private function c02aEntity($aCampos)
    {
        //C02a|cpf|
        $this->linhaC[] = ''; //CNPJ
        $this->linhaC[] = $aCampos[1];//CPF
        $this->linhaCEntity($this->linhaC);
    }
    
    /**
     * zLinhaCEntity
     * Cria a tag emit
     * @param array $aCampos
     */
    private function zLinhaCEntity($aCampos)
    {
        //C|XNome|XFant|IE|IEST|IM|CNAE|CRT|CNPJ|CPF|
        $this->make->tagemit(
            $aCampos[8], //cnpj
            $aCampos[9], //cpf
            $aCampos[1], //xNome
            $aCampos[2], //xFant
            $aCampos[3], //numIE
            $aCampos[4], //numIEST
            $aCampos[5], //numIM
            $aCampos[6], //cnae
            $aCampos[7] //crt
        );
    }
    
    /**
     * c05Entity
     * Cria a tag enderEmit
     * @param array $aCampos
     */
    private function c05Entity($aCampos)
    {
        //C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone|
        $this->make->tagenderEmit(
            $aCampos[1], //xLgr
            $aCampos[2], //nro
            $aCampos[3], //xCpl
            $aCampos[4], //xBairro
            $aCampos[5], //cMun
            $aCampos[6], //xMun
            $aCampos[7], //siglaUF
            $aCampos[8], //cep
            $aCampos[9], //cPais
            $aCampos[10], //xPais
            $aCampos[11] //fone
        );
    }
    
    /**
     * eEntity
     * @param array $aCampos
     */
    private function eEntity($aCampos)
    {
        //E|xNome|indIEDest|IE|ISUF|IM|email|
        $this->linhaE = $aCampos;
    }
    
    /**
     * e02Entity
     * @param array $aCampos
     */
    private function e02Entity($aCampos)
    {
        //CNPJ [dest]
        $this->linhaE[] = $aCampos[1]; //CNPJ
        $this->linhaE[] = ''; //CPF
        $this->linhaE[] = ''; //idExtrangeiro
        $this->zLinhaEEntity($this->linhaE);
    }
    
    /**
     * e03Entity
     * @param array $aCampos
     */
    private function e03Entity($aCampos)
    {
        //CPF [dest]
        $this->linhaE[] = ''; //CNPJ
        $this->linhaE[] = $aCampos[1]; //CPF
        $this->linhaE[] = ''; //idExtrangeiro
        $this->zLinhaEEntity($this->linhaE);
    }
    
    /**
     * e03aEntity
     * @param array $aCampos
     */
    private function e03aEntity($aCampos)
    {
        //idEstrangeiro [dest]
        $this->linhaE[] = ''; //CNPJ
        $this->linhaE[] = ''; //CPF
        $this->linhaE[] = $aCampos[1];  //idExtrangeiro
        $this->zLinhaEEntity($this->linhaE);
    }
    
    /**
     * zLinhaEEntity
     * Cria a tag dest
     * @param array $aCampos
     */
    private function zLinhaEEntity($aCampos)
    {
        //E|xNome|indIEDest|IE|ISUF|IM|email|CNPJ/CPF/idExtrangeiro
        $this->make->tagdest(
            $aCampos[7], //cnpj
            $aCampos[8], //cpf
            $aCampos[9], //idEstrangeiro
            $aCampos[1], //xNome
            $aCampos[2], //indIEDest
            $aCampos[3], //IE
            $aCampos[4], //ISUF
            $aCampos[5], //IM
            $aCampos[6] //email
        );
    }
    
    /**
     * e05Entity
     * Cria a tag enderDest
     * @param array $aCampos
     */
    private function e05Entity($aCampos)
    {
        //E05|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CEP|cPais|xPais|fone|
        $this->make->tagenderDest(
            $aCampos[1], //xLgr
            $aCampos[2], //nro
            $aCampos[3], //xCpl
            $aCampos[4], //xBairro
            $aCampos[5], //cMun
            $aCampos[6], //xMun
            $aCampos[7], //siglaUF
            $aCampos[8], //cep
            $aCampos[9], //cPais
            $aCampos[10], //xPais
            $aCampos[11] //fone
        );
    }
    
    /**
     * fEntity
     * @param array $aCampos
     */
    private function fEntity($aCampos)
    {
        //F|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
        $this->linhaF = $aCampos;
    }
    
    /**
     * f02Entity
     * @param array $aCampos
     */
    private function f02Entity($aCampos)
    {
        //CNPJ [retirada]
        $this->linhaF[] = $aCampos[1];
        $this->linhaF[] = '';
        $this->zLinhaF($this->linhaF);
    }
    
    /**
     * f02aEntity
     * @param array $aCampos
     */
    private function f02aEntity($aCampos)
    {
        //CPF [retirada]
        $this->linhaF[] = '';
        $this->linhaF[] = $aCampos[1];
        $this->zLinhaF($this->linhaF);
    }
    
    /**
     * zLinhaF
     * Cria a tag retirada
     * @param array $aCampos
     */
    private function zLinhaF($aCampos)
    {
        //F|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CNPJ|CPF
        $this->make->tagretirada(
            $aCampos[8], //cnpj
            $aCampos[9], //cpf
            $aCampos[1], //xLgr
            $aCampos[2], //nro
            $aCampos[3], //xCpl
            $aCampos[4], //xBairro
            $aCampos[5], //cMun
            $aCampos[6], //xMun
            $aCampos[7] //siglaUF
        );
    }
    
    /**
     * gEntity
     * @param array $aCampos
     */
    private function gEntity($aCampos)
    {
        //G|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|
        $this->linhaG = $aCampos;
    }
        
    /**
     * g02Entity
     * @param array $aCampos
     */
    private function g02Entity($aCampos)
    {
        //G02|CNPJ
        $this->linhaG[] = $aCampos[1];
        $this->linhaG[] = '';
        $this->zLinhaG($this->linhaG);
    }
    
    /**
     * g02aEntity
     * @param array $aCampos
     */
    private function g02aEntity($aCampos)
    {
        //G02a|CPF
        $this->linhaG[] = '';
        $this->linhaG[] = $aCampos[1];
        $this->zLinhaG($this->linhaG);
    }
    
    /**
     * zLinhaG
     * Cria tag entrega
     * @param array $aCampos
     */
    private function zLinhaG($aCampos)
    {
        //G|xLgr|nro|xCpl|xBairro|cMun|xMun|UF|CNPJ|CPF
        $this->make->tagentrega(
            $aCampos[8], //cnpj
            $aCampos[9], //cpf
            $aCampos[1], //xLgr
            $aCampos[2], //nro
            $aCampos[3], //xCpl
            $aCampos[4], //xBairro
            $aCampos[5], //cMun
            $aCampos[6], //xMun
            $aCampos[7] //siglaUF
        );
    }
    
    private function gaEntity($aCampos)
    {
        //GA02
        //fake não faz nada
    }
    
    private function ga02Entity($aCampos)
    {
        //GA02|CNPJ|
        $this->make->tagautXML($aCampos[1], '');
    }
        
    private function ga03Entity($aCampos)
    {
        //GA02|CPF|
        $this->make->tagautXML('', $aCampos[1]);
    }
    
    private function hEntity($aCampos)
    {
        //H|item|infAdProd
        
    }
        
    private function iEntity($aCampos)
    {
        //I|cProd|cEAN|xProd|NCM|EXTIPI|CFOP|uCom|qCom|vUnCom
        // |vProd|cEANTrib|uTrib|qTrib|vUnTrib
        // |vFrete|vSeg|vDesc|vOutro|indTot|xPed|nItemPed|nFCI|
        
    }
        
    private function i18Entity($aCampos)
    {
        //I18|nDI|dDI|xLocDesemb|UFDesemb|dDesemb|tpViaTransp|vAFRMM|tpIntermedio|CNPJ|UFTerceiro|cExportador|
    }
        
    private function i25Entity($aCampos)
    {
        //I25|nAdicao|nSeqAdicC|cFabricante|vDescDI|nDraw|
    }
        
    private function i50Entity($aCampos)
    {
        //I50|nDraw|
    }
        
    private function i52Entity($aCampos)
    {
        //I52|nRE|chNFe|qExport|
    }
        
    private function jEntity($aCampos)
    {
        //J|TpOp|Chassi|CCor|XCor|Pot|cilin|pesoL|pesoB|NSerie|TpComb|NMotor|CMT|Dist|anoMod
        // |anoFab|tpPint|tpVeic|espVeic|VIN|condVeic|cMod|cCorDENATRAN|lota|tpRest|
    }
        
    private function kEntity($aCampos)
    {
        //K|NLote|QLote|DFab|DVal|VPMC|
    }
        
    private function lEntity($aCampos)
    {
    }
        
    private function laEntity($aCampos)
    {
    }
        
    private function la07Entity($aCampos)
    {
    }
        
    private function lbEntity($aCampos)
    {
    }
        
    private function mEntity($aCampos)
    {
    }
        
    private function nEntity($aCampos)
    {
    }
        
    private function n02Entity($aCampos)
    {
    }
        
    private function n03Entity($aCampos)
    {
    }
        
    private function n04Entity($aCampos)
    {
    }
        
    private function n05Entity($aCampos)
    {
    }
        
    private function n06Entity($aCampos)
    {
    }
        
    private function n07Entity($aCampos)
    {
    }
        
    private function n08Entity($aCampos)
    {
    }
        
    private function n09Entity($aCampos)
    {
    }
        
    private function n10Entity($aCampos)
    {
    }
        
    private function n10aEntity($aCampos)
    {
    }
        
    private function n10bEntity($aCampos)
    {
    }
        
    private function n10cEntity($aCampos)
    {
    }
        
    private function n10dEntity($aCampos)
    {
    }
        
    private function n10eEntity($aCampos)
    {
    }
        
    private function n10fEntity($aCampos)
    {
    }
        
    private function n10gEntity($aCampos)
    {
    }
        
    private function n10hEntity($aCampos)
    {
    }
        
    private function oEntity($aCampos)
    {
    }
        
    private function o07Entity($aCampos)
    {
    }
        
    private function o10Entity($aCampos)
    {
    }
        
    private function o11Entity($aCampos)
    {
    }
        
    private function o08Entity($aCampos)
    {
    }
        
    private function pEntity($aCampos)
    {
    }
         
    private function qEntity($aCampos)
    {
    }
        
    private function q02Entity($aCampos)
    {
    }
        
    private function q03Entity($aCampos)
    {
    }
        
    private function q04Entity($aCampos)
    {
    }
        
    private function q05Entity($aCampos)
    {
    }
        
    private function q07Entity($aCampos)
    {
    }
        
    private function q10Entity($aCampos)
    {
    }
        
    private function rEntity($aCampos)
    {
    }
        
    private function r02Entity($aCampos)
    {
    }
        
    private function r04Entity($aCampos)
    {
    }
        
    private function sEntity($aCampos)
    {
    }
        
    private function s02Entity($aCampos)
    {
    }
        
    private function s03Entity($aCampos)
    {
    }
        
    private function s04Entity($aCampos)
    {
    }
        
    private function s05Entity($aCampos)
    {
    }
        
    private function s07Entity($aCampos)
    {
    }
        
    private function s09Entity($aCampos)
    {
    }
        
    private function tEntity($aCampos)
    {
    }
         
    private function t02Entity($aCampos)
    {
    }
        
    private function t04Entity($aCampos)
    {
    }
        
    private function uEntity($aCampos)
    {
    }
        
    private function uaEntity($aCampos)
    {
    }
        
    private function wEntity($aCampos)
    {
    }
        
    private function w02Entity($aCampos)
    {
    }
        
    private function w17Entity($aCampos)
    {
    }
        
    private function w23Entity($aCampos)
    {
    }
        
    private function xEntity($aCampos)
    {
    }
        
    private function x03Entity($aCampos)
    {
    }
        
    private function x04Entity($aCampos)
    {
    }
        
    private function x05Entity($aCampos)
    {
    }
        
    private function x11Entity($aCampos)
    {
    }
        
    private function x18Entity($aCampos)
    {
    }
        
    private function x22Entity($aCampos)
    {
    }
        
    private function x26Entity($aCampos)
    {
    }
        
    private function x33Entity($aCampos)
    {
    }
        
    private function yEntity($aCampos)
    {
    }
        
    private function y02Entity($aCampos)
    {
    }
        
    private function y07Entity($aCampos)
    {
    }
        
    private function yaEntity($aCampos)
    {
    }
        
    private function zEntity($aCampos)
    {
    }
        
    private function z04Entity($aCampos)
    {
    }
        
    private function z07Entity($aCampos)
    {
    }
        
    private function z10Entity($aCampos)
    {
    }
        
    private function zaEntity($aCampos)
    {
    }
        
    private function zbEntity($aCampos)
    {
    }
        
    private function zc01Entity($aCampos)
    {
    }
        
    private function zc04Entity($aCampos)
    {
    }
        
    private function zc10Entity($aCampos)
    {
    }
}
