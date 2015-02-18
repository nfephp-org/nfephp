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
    public $aDom = array();
    
    public $limparString = true;
    
    private $counter = -1;
    
    private $version = '3.10';
    
    private $linhaB20a = array();
    
    private $linhaC = array();
    
            
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
    
    public function txt2xml($txt)
    {
        if (is_file($txt)) {
            //extrai cada linha do arquivo em umm campo de matriz
            $aDados = file($txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
        } elseif (is_array($txt)) {
            //carrega a matriz
            $aDados = $txt;
        } else {
            if (strlen($txt) > 0) {
                //carrega a matriz com as linha do arquivo
                $aDados = explode("\n", $txt);
            } else {
                return '';
            }
        }
        $this->zArray2xml($aDados);
    }
    
    protected function zArray2xml($aDados = array())
    {
        if (empty($aDados)) {
            return '';
        }
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
    
    private function notafiscalEntity($aCampos)
    {
        $this->aDom[] = new MakeNFe();
        $this->counter += 1;
    }
    
    private function aEntity($aCampos)
    {
        //A|versão do schema|id
        if ($aCampos[1] != $this->version) {
            $msg = "A conversão somente para a versão $this->version !";
            throw new Exception\RuntimeException($msg);
        }
        $chave = preg_replace('/[^0-9]/', '', $aCampos[2]);
        $this->aDom[$this->counter]->taginfNFe($chave, $aCampos[1]);
    }
    
    private function bEntity($aCampos)
    {
        //B|cUF|cNF|natOp|indPag|mod|serie|nNF|dhEmi|dhSaiEnt
        //|tpNF|idDest|cMunFG|tpImp|tpEmis|cDV|tpAmb|finNFe|indFinal|indPres|procEmi|VerProc|dhCont|xJust
        $this->aDom[$this->counter]->tagide(
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
    
    private function b13Entity($aCampos)
    {
        //B13|chave NFe|
        $this->aDom[$this->counter]->tagrefNFe($aCampos[1]);
    }
    
    private function b14Entity($aCampos)
    {
        //B14|cUF|AAMM(ano mês)|CNPJ|Mod|serie|nNF|
        $this->aDom[$this->counter]->tagrefNF(
            $aCampos[1], //cUF
            $aCampos[2], //aamm
            $aCampos[3], //cnpj
            $aCampos[4], //mod
            $aCampos[5], //serie
            $aCampos[6] //nNF
        );
    }
    
    private function b20aEntity($aCampos)
    {
        //B20a|cUF|AAMM|IE|mod|serie|nNF
        $this->linhaB20a = $aCampos;
    }
    
    private function b20dEntity($aCampos)
    {
        //B20d|CNPJ|
        $aFields[0] = $this->linhaB20a[0];
        $aFields[1] = $this->linhaB20a[1];
        $aFields[2] = $this->linhaB20a[2];
        $aFields[3] = $aCampos[1];
        $aFields[4] = '';
        $aFields[5] = $this->linhaB20a[3];
        $aFields[6] = $this->linhaB20a[4];
        $aFields[7] = $this->linhaB20a[5];
        $aFields[8] = $this->linhaB20a[6];
        $this->zLinhaB20aEntity($aFields);
    }
    
    private function b20eEntity($aCampos)
    {
        //B20d|CPF|
        $aFields[0] = $this->linhaB20a[0];
        $aFields[1] = $this->linhaB20a[1];
        $aFields[2] = $this->linhaB20a[2];
        $aFields[3] = '';
        $aFields[4] = $aCampos[1];
        $aFields[5] = $this->linhaB20a[3];
        $aFields[6] = $this->linhaB20a[4];
        $aFields[7] = $this->linhaB20a[5];
        $aFields[8] = $this->linhaB20a[6];
        $this->zLinhaB20aEntity($aFields);
    }

    private function zLinhaB20aEntity($aCampos)
    {
        //B20a|cUF|AAMM|CNPJ|CPF|IE|mod|serie|nNF
        $this->aDom[$this->counter]->tagrefNFP(
            $aCampos[1], //cUF
            $aCampos[2], //aamm
            $aCampos[3], //cnpj
            $aCampos[4], //cpf
            $aCampos[5], //numIE
            $aCampos[6], //mod
            $aCampos[7], //serie
            $aCampos[8] //nNF
        );
    }
    
    private function b20iEntity($aCampos)
    {
        //B20i|refCTe|
        $this->aDom[$this->counter]->tagrefCTe($aCampos[1]);
    }
    
    private function b20jEntity($aCampos)
    {
        //B20j|mod|nECF|nCOO|
        $this->aDom[$this->counter]->tagrefECF(
            $aCampos[1], //mod
            $aCampos[2], //nECF
            $aCampos[3] //nCOO
        );
    }
    
    private function cEntity($aCampos)
    {
        //C|XNome|XFant|IE|IEST|IM|CNAE|CRT|
        $this->linhaC = $aCampos;
    }
    
    private function c02Entity($aCampos)
    {
        //C|CNPJ|CPF|XNome|XFant|IE|IEST|IM|CNAE|CRT|
        //C02|cnpj|
        $aFields[0] = $this->linhaC[0];
        $aFields[1] = $aCampos[1];
        $aFields[2] = '';
        $aFields[3] = $this->linhaC[1];
        $aFields[4] = $this->linhaC[2];
        $aFields[5] = $this->linhaC[3];
        $aFields[6] = $this->linhaC[4];
        $aFields[7] = $this->linhaC[5];
        $aFields[8] = $this->linhaC[6];
        $aFields[9] = $this->linhaC[7];
        $this->zLinhaCEntity($aFields);
    }
    
    private function c02aEntity($aCampos)
    {
        //C|CNPJ|CPF|XNome|XFant|IE|IEST|IM|CNAE|CRT|
        //C02a|cpf|
        $aFields[0] = $this->linhaC[0];
        $aFields[1] = '';
        $aFields[2] = $aCampos[1];
        $aFields[3] = $this->linhaC[1];
        $aFields[4] = $this->linhaC[2];
        $aFields[5] = $this->linhaC[3];
        $aFields[6] = $this->linhaC[4];
        $aFields[7] = $this->linhaC[5];
        $aFields[8] = $this->linhaC[6];
        $aFields[9] = $this->linhaC[7];
        $this->linhaCEntity($aFields);
    }
    
    private function zLinhaCEntity($aCampos)
    {
        $this->aDom[$this->counter]->tagemit(
            $aCampos[1], //cnpj
            $aCampos[2], //cpf
            $aCampos[3], //xNome
            $aCampos[4], //xFant
            $aCampos[5], //numIE
            $aCampos[6], //numIEST
            $aCampos[7], //numIM
            $aCampos[8], //cnae
            $aCampos[9] //crt
        );
    }
    
    private function c05Entity($aCampos)
    {
        //C05|XLgr|Nro|Cpl|Bairro|CMun|XMun|UF|CEP|cPais|xPais|fone|
        $this->aDom($this->counter)->tagenderEmit(
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
    
    private function eEntity($aCampos)
    {
        //E|xNome|indIEDest|IE|ISUF|IM|email|
        $this->linhaE = $aCampos;
    }
    
    private function e02Entity($aCampos)
    {
        
    }
    
    private function e03Entity($aCampos)
    {
        
    }
    
    private function e03aEntity($aCampos)
    {
        
    }
    
    private function linhaEEntity($aCampos)
    {
        
    }
}
