<?php

namespace NFSe\Service;
use NFSe\Model\LoteRps;
use NFSe\Model\Prestador;


/**
 * Usa o DTO LoteRps para fazer requisições ao webservice e retorna XML (por enquanto)
 *
 * @category   NFePHP
 * @package    NFSe\Service
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
interface WebServiceRequesterServiceInterface
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

    /**
     * Consulta NFS-es de um prestador dado um período
     *
     * @param Prestador $prestador
     * @param \DateTime $dataEmissaoInicio
     * @param \DateTime $dataEmissaoFim
     * @return mixed
     */
    public function consultarNfses(Prestador $prestador, \DateTime $dataEmissaoInicio, \DateTime $dataEmissaoFim);

}

?>