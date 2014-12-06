<?php

namespace Common\DateTime;

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
        $aDH = explode('T', $dataHora);
        $adDH = explode('-', $aDH[0]);
        $inter = explode('-', $aDH[1]);
        $atDH = explode(':', $inter[0]);
        $timestamp = mktime($atDH[0], $atDH[1], $atDH[2], $adDH[1], $adDH[2], $adDH[0]);
        return $timestamp;
    }
    
    /**
     * convertTimestampToSefazTime
     * Converte um timestamp php em data/hora no formato usado
     * pela SEFAZ 2014-12-17T13:22:33-02:00
     * @param double $timestamp
     * @return string
     */
    public static function convertTimestampToSefazTime($timestamp = '')
    {
        if ($timestamp == '') {
            return (string) str_replace(' ', 'T', date('Y-m-d H:i:sP'));
        }
        return (string) str_replace(' ', 'T', date('Y-m-d H:i:sP', $timestamp));
    }
}
