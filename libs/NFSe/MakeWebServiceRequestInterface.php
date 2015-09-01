<?php

namespace NFSe;

use NFSe\Dto\LoteRps;

/**
 * Usa o DTO LoteRps para fazer requisições ao webservice e retorna XML (por enquanto)
 *
 * @category   NFePHP
 * @package    NFSe\MakeWebServiceRequest
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
interface MakeWebServiceRequestInterface
{
    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function enviarLoteRps(LoteRps $loteRps);

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarSituacaoLoteRps(LoteRps $loteRps);

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarLoteRps(LoteRps $loteRps);
}

?>