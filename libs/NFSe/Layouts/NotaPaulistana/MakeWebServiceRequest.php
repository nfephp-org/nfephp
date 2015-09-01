<?php

namespace NFSe\Layouts\NotaPaulistana;
use NFSe\Dto\LoteRps;
use NFSe\MakeWebServiceRequestInterface;

/**
 * Classe que faz requisições para a Nota Paulistana
 *
 * @category   NFePHP
 * @package    ${NAMESPACE}${NAME}
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
class MakeWebServiceRequest implements MakeWebServiceRequestInterface
{
    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function enviarLoteRps(LoteRps $loteRps)
    {
        var_dump($loteRps->getRpss());
        return 'sou uma resposta da Nota Paulistana';
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarSituacaoLoteRps(LoteRps $loteRps)
    {
        return null; // não existe isso na nota paulistana
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarLoteRps(LoteRps $loteRps)
    {
        return 'Sou da nota paulistana. vou retornar a nota, assim como nos demais layouts.';
    }

}

?>