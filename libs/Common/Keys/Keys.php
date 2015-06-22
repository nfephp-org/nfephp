<?php

namespace NFePHP\Common\Keys;

/**
 * Classe auxiliar para criar, listar e testar os diretórios utilizados pela API
 * @category   NFePHP
 * @package    NFePHP\Common\Keys
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */


class Keys
{
    /**
     * buildKey
     * Monta as chaves de 44 digitos para NFe, NFCe, CTe e MDFe
     * 
     * @param string $cUF
     * @param string $ano
     * @param string $mes
     * @param string $cnpj
     * @param string $mod
     * @param string $serie
     * @param string $numero
     * @param string $tpEmis
     * @param string $codigo
     * @return string
     */
    public static function buildKey($cUF, $ano, $mes, $cnpj, $mod, $serie, $numero, $tpEmis, $codigo = '')
    {
        if ($codigo == '') {
            $codigo = $numero;
        }
        $forma = "%02d%02d%02d%s%02d%03d%09d%01d%08d";
        $chave = sprintf(
            $forma,
            $cUF,
            $ano,
            $mes,
            $cnpj,
            $mod,
            $serie,
            $numero,
            $tpEmis,
            $codigo
        );
        return $chave.self::calculaDV($chave);
    }
    
    /**
     * testaChave
     * Testa a chave com o digito verificador no final
     * 
     * @param string $chave
     * @return boolean
     */
    public static function testaChave($chave = '')
    {
        if (strlen($chave) != 44) {
            return false;
        }
        $cDV = substr($chave, -1);
        $calcDV = self::calculaDV(substr($chave, 0, 43));
        if ($cDV === $calcDV) {
            return true;
        }
        return false;
    }
    
    /**
     * calculaDV
     * Função para o calculo o digito verificador da chave da NFe
     * 
     * @param string $chave43
     * @return string 
     */
    public static function calculaDV($chave43)
    {
        $multiplicadores = array(2, 3, 4, 5, 6, 7, 8, 9);
        $iCount = 42;
        $somaPonderada = 0;
        while ($iCount >= 0) {
            for ($mCount = 0; $mCount < count($multiplicadores) && $iCount >= 0; $mCount++) {
                $num = (int) substr($chave43, $iCount, 1);
                $peso = (int) $multiplicadores[$mCount];
                $somaPonderada += $num * $peso;
                $iCount--;
            }
        }
        $resto = $somaPonderada % 11;
        if ($resto == '0' || $resto == '1') {
            $cDV = 0;
        } else {
            $cDV = 11 - $resto;
        }
        return (string) $cDV;
    }
}
