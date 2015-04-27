<?php

namespace NFePHP\MDFe;

/**
 * Classe para a identificação do documento eletrônico da NFe
 * @category   NFePHP
 * @package    NFePHP\MDFe\IdentifyMDFe
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;
use NFePHP\Common\Identify\Identify;

class IdentifyMDFe
{
    public static function identificar($xml = '', &$aResp = array())
    {
        $aList = array(
            'consReciMDFe' => 'consReciMDFe',
            'consSitMDFe' => 'consSitMDFe',
            'consStatServMDFe' => 'consStatServMDFe',
            'enviMDFe' => 'enviMDFe',
            'evCancMDFe' => 'evCancMDFe',
            'evEncMDFe' => 'evEncMDFe',
            'MDFe' => 'mdfe',
            'eventoMDFe' => 'eventoMDFe',
            'procEventoMDFe' => 'procEventoMDFe',
            'mdfeProc' => 'procMDFe',
            'retConsReciMDFe' => 'retConsReciMDFe',
            'retConsSitMDFe' => 'retConsSitMDFe',
            'retConsStatServMDFe' => 'retConsStatServMDFe',
            'retEnviMDFe' => 'retEnviMDFe',
            'retEventoMDFe' => 'retEventoMDFe'
        );
        Identify::setListSchemesId($aList);
        $schem = Identify::identificacao($xml, $aResp);
        $dom = $aResp['dom'];
        $node = $dom->getElementsByTagName($aResp['tag'])->item(0);
        if ($schem == 'nfe') {
            //se for um nfe então é necessário pegar a versão
            // em outro node infNFe
            $node1 = $dom->getElementsByTagName('infMDFe')->item(0);
            $versao = $node1->getAttribute('versao');
        } else {
            $versao = $node->getAttribute('versao');
        }
        $aResp['versao'] = $versao;
        $aResp['xml'] = $dom->saveXML($node);
        return $schem;
    }
}
