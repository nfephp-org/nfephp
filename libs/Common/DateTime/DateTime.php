<?php

namespace NFePHP\Common\DateTime;

/**
 * Classe auxiliar para tratar datas
 * @category   NFePHP
 * @package    NFePHP\Common\DateTime
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class DateTime
{
    /**
     * tzdBR
     * Para esta função funcionar corretamente é importante 
     * que os pacotes referentes ao Horario de verão estejam
     * atualizados instalados e ativos no sistema operacional
     * @param string $siglaUF
     * @return string com o TZD (Time Zone Designator)
     */
    public static function tzdBR($siglaUF = '')
    {
        if ($siglaUF == '') {
            return '';
        }
        $tzUFlist = array(
            'AC'=>'America/Rio_Branco',
            'AL'=>'America/Maceio',
            'AM'=>'America/Manaus',
            'AP'=>'America/Belem',
            'BA'=>'America/Bahia',
            'CE'=>'America/Fortaleza',
            'DF'=>'America/Sao_Paulo',
            'ES'=>'America/Sao_Paulo',
            'GO'=>'America/Sao_Paulo',
            'MA'=>'America/Fortaleza',
            'MG'=>'America/Sao_Paulo',
            'MS'=>'America/Campo_Grande',
            'MT'=>'America/Cuiaba',
            'PA'=>'America/Belem',
            'PB'=>'America/Fortaleza',
            'PE'=>'America/Recife',
            'PI'=>'America/Fortaleza',
            'PR'=>'America/Sao_Paulo',
            'RJ'=>'America/Sao_Paulo',
            'RN'=>'America/Fortaleza',
            'RO'=>'America/Porto_Velho',
            'RR'=>'America/Boa_Vista',
            'RS'=>'America/Sao_Paulo',
            'SC'=>'America/Sao_Paulo',
            'SE'=>'America/Maceio',
            'SP'=>'America/Sao_Paulo',
            'TO'=>'America/Araguaina');

        if (! isset($tzUFlist[$siglaUF])) {
            return '';
        }
        //seta a zona de tempo
        date_default_timezone_set($tzUFlist[$siglaUF]);
        return (string) date('P');
    }
    
    /**
     * convertSefazTimeToTimestamp
     * Converte a imformação de data e tempo contida na NFe
     * 
     * @param string $DH Informação de data e tempo extraida da NFe
     * @return timestamp UNIX Para uso com a funçao date do php
     */
    public static function convertSefazTimeToTimestamp($dataHora = '')
    {
        if ($dataHora == '') {
            return '';
        }
        //inserido devido a casos de má formação do xml com
        //TZD +00:00 por exemplo
        $dataHora = str_replace('+', '-', $dataHora);
        $aDH = explode('T', $dataHora);
        $adDH = explode('-', $aDH[0]);
        $atDH = array('0','0','0');
        if (count($aDH) == 2) {
            $inter = explode('-', $aDH[1]);
            $atDH = explode(':', $inter[0]);
        }
        $timestamp = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
        return $timestamp;
    }
    
    /**
     * convertTimestampToSefazTime
     * Converte um timestamp php em data/hora no formato usado
     * pela SEFAZ 2014-12-17T13:22:33-02:00
     * @param int $timestamp
     * @return string
     */
    public static function convertTimestampToSefazTime($timestamp = 0)
    {
        if ($timestamp == 0) {
            return (string) str_replace(' ', 'T', date('Y-m-d H:i:sP'));
        }
        return (string) str_replace(' ', 'T', date('Y-m-d H:i:sP', $timestamp));
    }
}
