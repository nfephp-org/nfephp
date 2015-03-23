<?php
namespace NFe;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\Dom\ReturnNFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;

class ReturnNFe
{
    /**
     * readReturnSefaz
     * Trata o retorno da SEFAZ devolvendo o resultado em um array
     * @param string $method
     * @param string $xmlResp
     * @param mixed $parametro
     * @return array
     */
    public static function readReturnSefaz($method, $xmlResp, $parametro = false)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlResp);
        //para cada $method tem um formato de retorno especifico
        switch ($method) {
            case 'nfeAutorizacaoLote':
                return self::zReadAutorizacaoLote($dom);
                break;
            case 'nfeRetAutorizacaoLote':
                return self::zReadRetAutorizacaoLote($dom);
                break;
            case 'consultaCadastro2':
                return self::zReadConsultaCadastro2($dom);
                break;
            case 'nfeConsultaNF2':
                return self::zReadConsultaNF2($dom);
                break;
            case 'nfeInutilizacaoNF2':
                return self::zReadInutilizacaoNF2($dom);
                break;
            case 'nfeStatusServicoNF2':
                //NOTA: irá ser desativado
                return self::zReadStatusServico($dom);
                break;
            case 'nfeRecepcaoEvento':
                return self::zReadRecepcaoEvento($dom);
                break;
            case 'nfeDistDFeInteresse':
                return self::zReadDistDFeInteresse($dom);
                break;
            case 'nfeDownloadNF':
                return self::zReadDownloadNF($dom);
                break;
        }
        return array();
    }
    
    /**
     * zReadDownloadNF
     * @param DOMDocument $dom
     * @param boolean $parametro
     * @return array
     */
    protected static function zReadDownloadNF($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'verAplic' => '',
            'tpAmb' => '',
            'cStat' => '',
            'xMotivo' => '',
            'dhResp' => '',
            'aRetNFe' => array()
        );
        $tag = $dom->getElementsByTagName('retDownloadNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $retNFe = ! empty($tag->getElementsByTagName('retNFe')->item(0))
                ? $tag->getElementsByTagName('retNFe')->item(0)
                : '';
        if (! empty($retNFe)) {
            $aRetNFe['cStat'] = $retNFe->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aRetNFe['xMotivo'] = $retNFe->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aRetNFe['chNFe'] = $retNFe->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $nfeProc = ! empty($retNFe->getElementsByTagName('nfeProc')->item(0))
                ? $retNFe->getElementsByTagName('nfeProc')->item(0)
                : '';
            if (! empty($nfeProc)) {
                $aRetNFe['nfeProc'] = $dom->saveXML($nfeProc);
            }
        }
        $procNFeZip = !empty($tag->getElementsByTagName('procNFeZip')->item(0)->nodeValue)
            ? $tag->getElementsByTagName('procNFeZip')->item(0)->nodeValue
            : '';
        if (! empty($procNFeZip)) {
            $aRetNFe['procZip'] = gzdecode(base64_decode($procNFeZip));
        }
        $nfeZip = !empty($tag->getElementsByTagName('NFeZip')->item(0)->nodeValue)
            ? $tag->getElementsByTagName('NFeZip')->item(0)->nodeValue
            : '';
        if (! empty($nfeZip)) {
            $aRetNFe['nfeZip'] = gzdecode(base64_decode($nfeZip));
        }
        $protZip = !empty($tag->getElementsByTagName('protNFeZip')->item(0)->nodeValue)
            ? $tag->getElementsByTagName('protNFeZip')->item(0)->nodeValue
            : '';
        if (! empty($protZip)) {
            $aRetNFe['protZip'] = gzdecode(base64_decode($protZip));
        }
        $aResposta['bStat'] = true;
        $aResposta['versao'] = $tag->getAttribute('versao');
        $aResposta['tpAmb'] = $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $aResposta['verAplic'] = $tag->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $aResposta['cStat'] = $tag->getElementsByTagName('cStat')->item(0)->nodeValue;
        $aResposta['xMotivo'] = $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $aResposta['dhResp'] = $tag->getElementsByTagName('dhResp')->item(0)->nodeValue;
        $aResposta['aRetNFe'] = $aRetNFe;
        return $aResposta;
    }
    
    /**
     * zReadAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => '',
            'nRec' => '',
            'prot' => array()
        );
        $tag = $dom->getElementsByTagName('retEnviNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $nRec = !empty($tag->getElementsByTagName('nRec')->item(0))
            ? $tag->getElementsByTagName('nRec')->item(0)->nodeValue
            : '';
        $tMed = !empty($tag->getElementsByTagName('tMed')->item(0))
            ? $tag->getElementsByTagName('tMed')->item(0)->nodeValue
            : '';
        $aProt[] = self::zGetProt($tag);
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'tMed' => $tMed,
            'nRec' => $nRec,
            'prot' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadRetAutorizacaoLote
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadRetAutorizacaoLote($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat'=>false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'nRec' => '',
            'aProt' => array()
        );
        $tag = $dom->getElementsByTagName('retConsReciNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aProt = array();
        $dhRecbto = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $tagProt = $tag->getElementsByTagName('protNFe');
        foreach ($tagProt as $protocol) {
            $aProt[] = self::zGetProt($protocol);
        }
        $aResposta = array(
            'bStat'=>true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $dhRecbto,
            'cUF' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'nRec' => $tag->getElementsByTagName('nRec')->item(0)->nodeValue,
            'aProt' => $aProt
        );
        return $aResposta;
    }
    
    /**
     * zReadConsultaCadastro2
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadConsultaCadastro2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'version' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'UF' => '',
            'IE' => '',
            'CNPJ' => '',
            'CPF' => '',
            'dhCons' => '',
            'cUF' => '',
            'aCad' => array()
        );
        $tag = $dom->getElementsByTagName('retConsCad')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $infCons = $tag->getElementsByTagName('infCons')->item(0);
        $iest = !empty($infCons->getElementsByTagName('IE')->item(0)->nodeValue) ?
                $infCons->getElementsByTagName('IE')->item(0)->nodeValue : '';
        $cnpj = !empty($infCons->getElementsByTagName('CNPJ')->item(0)->nodeValue) ?
                $infCons->getElementsByTagName('CNPJ')->item(0)->nodeValue : '';
        $cpf =  !empty($infCons->getElementsByTagName('CPF')->item(0)->nodeValue) ?
                $infCons->getElementsByTagName('CPF')->item(0)->nodeValue : '';
        $aResposta = array(
            'bStat' => true,
            'version' => $tag->getAttribute('versao'),
            'cStat' => $infCons->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $infCons->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $infCons->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'UF' => $infCons->getElementsByTagName('UF')->item(0)->nodeValue,
            'IE' => $iest,
            'CNPJ' => $cnpj,
            'CPF' => $cpf,
            'dhCons' => $infCons->getElementsByTagName('dhCons')->item(0)->nodeValue,
            'cUF' => $infCons->getElementsByTagName('cUF')->item(0)->nodeValue,
            'aCad' => array()
        );
        $aCad = array();
        $infCad = $tag->getElementsByTagName('infCad');
        if (! isset($infCad)) {
            return $aResposta;
        }
        foreach ($infCad as $cad) {
            $ender = $cad->getElementsByTagName('ender')->item(0);
            $aCad[] = array(
                'IE' => $cad->getElementsByTagName('IE')->item(0)->nodeValue,
                'CNPJ' => $cad->getElementsByTagName('CNPJ')->item(0)->nodeValue,
                'UF' => $cad->getElementsByTagName('UF')->item(0)->nodeValue,
                'cSit' => $cad->getElementsByTagName('cSit')->item(0)->nodeValue,
                'indCredNFe' => $cad->getElementsByTagName('indCredNFe')->item(0)->nodeValue,
                'indCredCTe' => $cad->getElementsByTagName('indCredCTe')->item(0)->nodeValue,
                'xNome' => $cad->getElementsByTagName('xNome')->item(0)->nodeValue,
                'xRegApur' => $cad->getElementsByTagName('xRegApur')->item(0)->nodeValue,
                'CNAE' => $cad->getElementsByTagName('CNAE')->item(0)->nodeValue,
                'dIniAtiv' => $cad->getElementsByTagName('dIniAtiv')->item(0)->nodeValue,
                'dUltSit' => $cad->getElementsByTagName('dUltSit')->item(0)->nodeValue,
                'xLgr' => $ender->getElementsByTagName('xLgr')->item(0)->nodeValue,
                'nro' => $ender->getElementsByTagName('nro')->item(0)->nodeValue,
                'xCpl' => $ender->getElementsByTagName('xCpl')->item(0)->nodeValue,
                'xBairro' => $ender->getElementsByTagName('xBairro')->item(0)->nodeValue,
                'cMun' => $ender->getElementsByTagName('cMun')->item(0)->nodeValue,
                'xMun' => $ender->getElementsByTagName('xMun')->item(0)->nodeValue,
                'CEP' => $ender->getElementsByTagName('CEP')->item(0)->nodeValue
            );
        }
        $aResposta['aCad'] = $aCad;
        return $aResposta;
    }

    /**
     * zReadConsultaNF2
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadConsultaNF2($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'cUF' => '',
            'chNFe' => '',
            'aProt' => array(),
            'aCanc' => array(),
            'aEvent' => array()
        );
        $tag = $dom->getElementsByTagName('retConsSitNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aEvent = array();
        $procEventoNFe = $tag->getElementsByTagName('procEventoNFe');
        if (isset($procEventoNFe)) {
            foreach ($procEventoNFe as $evento) {
                $aEvent[] = self::zGetEvent($evento);
            }
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue,
            'chNFe' => $tag->getElementsByTagName('chNFe')->item(0)->nodeValue,
            'aProt' => self::zGetProt($tag),
            'aCanc' => self::zGetCanc($tag),
            'aEvent' => $aEvent
        );
        return $aResposta;
    }
    
    /**
     * zReadInutilizacaoNF2
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadInutilizacaoNF2($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cStat' => '',
            'xMotivo' => '',
            'cUF' => '',
            'dhRecbto' => '',
            'ano' => '',
            'CNPJ' => '',
            'mod' => '',
            'serie' => '',
            'nNFIni' => '',
            'nNFFin' => '',
            'nProt' => ''
        );
        $tag = $dom->getElementsByTagName('retInutNFe')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta['bStat'] = true;
        $aResposta['versao'] = $tag->getAttribute('versao');
        $aResposta['tpAmb'] = $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue;
        $aResposta['verAplic'] = $tag->getElementsByTagName('verAplic')->item(0)->nodeValue;
        $aResposta['cStat'] = $tag->getElementsByTagName('cStat')->item(0)->nodeValue;
        $aResposta['xMotivo'] = $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue;
        $aResposta['cUF'] = $tag->getElementsByTagName('cUF')->item(0)->nodeValue;
        $aResposta['dhRecbto'] = $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
        $infInut = $tag->getElementsByTagName('infInut')->item(0);
        if (! empty($infInut) && 'ID' !== $infInut->getAttribute('Id')) {
            $aResposta['ano'] = $infInut->getElementsByTagName('ano')->item(0)->nodeValue;
            $aResposta['CNPJ'] = $infInut->getElementsByTagName('CNPJ')->item(0)->nodeValue;
            $aResposta['mod'] = $infInut->getElementsByTagName('mod')->item(0)->nodeValue;
            $aResposta['serie'] = $infInut->getElementsByTagName('serie')->item(0)->nodeValue;
            $aResposta['nNFIni'] = $infInut->getElementsByTagName('nNFIni')->item(0)->nodeValue;
            $aResposta['nNFFin'] = $infInut->getElementsByTagName('nNFFin')->item(0)->nodeValue;
            $aResposta['nProt'] = $infInut->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        return $aResposta;
    }
    
    /**
     * zReadStatusServico
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadStatusServico($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'verAplic' => '',
            'xMotivo' => '',
            'dhRecbto' => '',
            'tMed' => '',
            'cUF' => ''
        );
        $tag = $dom->getElementsByTagName('retConsStatServ')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $tMed = ! empty($tag->getElementsByTagName('tMed')->item(0)->nodeValue) ?
                $tag->getElementsByTagName('tMed')->item(0)->nodeValue : '';
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhRecbto' => $tag->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
            'tMed' => $tMed,
            'cUF' => $tag->getElementsByTagName('cUF')->item(0)->nodeValue
        );
        return $aResposta;
    }

    /**
     * zReadRecepcaoEvento
     * @param DOMDocument $dom
     * @return array
     */
    protected static function zReadRecepcaoEvento($dom)
    {
        //retorno da funçao
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'idLote' => '',
            'tpAmb' => '',
            'verAplic' => '',
            'cOrgao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'evento' => array()
        );
        $tag = $dom->getElementsByTagName('retEnvEvento')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'idLote' => $tag->getElementsByTagName('idLote')->item(0)->nodeValue,
            'tpAmb' => $tag->getElementsByTagName('tpAmb')->item(0)->nodeValue,
            'verAplic' => $tag->getElementsByTagName('verAplic')->item(0)->nodeValue,
            'cOrgao' => $tag->getElementsByTagName('cOrgao')->item(0)->nodeValue,
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'evento' => self::zGetEvent($tag)
        );
        return $aResposta;
    }
    
    /**
     * zReadDistDFeInteresse
     * @param DOMDocument $dom
     * @param boolean $descompactar
     * @return array
     */
    protected static function zReadDistDFeInteresse($dom)
    {
        $aResposta = array(
            'bStat' => false,
            'versao' => '',
            'cStat' => '',
            'xMotivo' => '',
            'dhResp' => '',
            'ultNSU' => 0,
            'maxNSU' => 0,
            'aDoc' => array()
        );
        $tag = $dom->getElementsByTagName('retDistDFeInt')->item(0);
        if (! isset($tag)) {
            return $aResposta;
        }
        $aDocZip = array();
        $docs = $tag->getElementsByTagName('docZip');
        foreach ($docs as $doc) {
            $xml = gzdecode(base64_decode($doc->nodeValue));
            $aDocZip[] = array(
              'NSU' => $doc->getAttribute('NSU'),
              'schema' => $doc->getAttribute('schema'),
              'doc' => $xml
            );
        }
        $aResposta = array(
            'bStat' => true,
            'versao' => $tag->getAttribute('versao'),
            'cStat' => $tag->getElementsByTagName('cStat')->item(0)->nodeValue,
            'xMotivo' => $tag->getElementsByTagName('xMotivo')->item(0)->nodeValue,
            'dhResp' => $tag->getElementsByTagName('dhResp')->item(0)->nodeValue,
            'ultNSU' => $tag->getElementsByTagName('ultNSU')->item(0)->nodeValue,
            'maxNSU' => $tag->getElementsByTagName('maxNSU')->item(0)->nodeValue,
            'aDoc' => $aDocZip
        );
        return $aResposta;
    }
    
    /**
     * zGetProt
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetProt($tag)
    {
        $aProt = array();
        $infProt = $tag->getElementsByTagName('infProt')->item(0);
        if (! empty($infProt)) {
            $nProt = $infProt->getElementsByTagName('nProt')->item(0)
                ? $infProt->getElementsByTagName('nProt')->item(0)->nodeValue
                : '';

            $aProt = array(
                'chNFe' => $infProt->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'dhRecbto' => $infProt->getElementsByTagName('dhRecbto')->item(0)->nodeValue,
                'nProt' => $nProt,
                'digVal' => $infProt->getElementsByTagName('digVal')->item(0)->nodeValue,
                'cStat' => $infProt->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infProt->getElementsByTagName('xMotivo')->item(0)->nodeValue
            );
        }
        return $aProt;
    }
    
    /**
     * zGetEvent
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetEvent($tag)
    {
        $aEvent = array();
        $infEvento = $tag->getElementsByTagName('infEvento')->item(0);
        if (! empty($infEvento)) {
            $cnpjDest = !empty($infEvento->getElementsByTagName('CNPJDest')->item(0))
                ? $infEvento->getElementsByTagName('CNPJDest')->item(0)->nodeValue
                : '';
            $emailDest = !empty($infEvento->getElementsByTagName('emailDest')->item(0))
                ? $infEvento->getElementsByTagName('emailDest')->item(0)->nodeValue
                : '';
            $nProt = !empty($infEvento->getElementsByTagName('nProt')->item(0))
                ? $infEvento->getElementsByTagName('nProt')->item(0)->nodeValue
                : '';

            $aEvent = array(
                'tpAmb' => $infEvento->getElementsByTagName('tpAmb')->item(0)->nodeValue,
                'verAplic' => $infEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
                'cOrgao' => $infEvento->getElementsByTagName('cOrgao')->item(0)->nodeValue,
                'cStat' => $infEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
                'xMotivo' => $infEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
                'chNFe' => $infEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
                'tpEvento' => $infEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
                'xEvento' => $infEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
                'nSeqEvento' => $infEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
                'dhRegEvento' => $infEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
                'CNPJDest' => $cnpjDest,
                'emailDest' => $emailDest,
                'nProt' => $nProt
            );
        }
        return $aEvent;
    }
    
    /**
     * zGetCanc
     * @param DOMDocument $tag
     * @return array
     */
    private static function zGetCanc($tag)
    {
        $aCanc = array();
        $infCanc = $tag->getElementsByTagName('infCanc')->item(0);
        if (! empty($infCanc)) {
            $aCanc['tpAmb'] = $infCanc->getElementsByTagName('tpAmb')->item(0)->nodeValue;
            $aCanc['verAplic'] = $infCanc->getElementsByTagName('verAplic')->item(0)->nodeValue;
            $aCanc['cStat'] = $infCanc->getElementsByTagName('cStat')->item(0)->nodeValue;
            $aCanc['xMotivo'] = $infCanc->getElementsByTagName('xMotivo')->item(0)->nodeValue;
            $aCanc['cUF'] = $infCanc->getElementsByTagName('cUF')->item(0)->nodeValue;
            $aCanc['chNFe'] = $infCanc->getElementsByTagName('chNFe')->item(0)->nodeValue;
            $aCanc['dhRecbto'] = $infCanc->getElementsByTagName('dhRecbto')->item(0)->nodeValue;
            $aCanc['nProt'] = $infCanc->getElementsByTagName('nProt')->item(0)->nodeValue;
        }
        return $aCanc;
    }
}
