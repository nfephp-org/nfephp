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

class MakeWebServiceRequest implements MakeWebServiceRequestInterface
{
    /**
     * @var MakeWebServiceRequestInterface
     */
    protected $makeWebServiceRequest;

    /**
     * MakeWebServiceRequest constructor.
     */
    private function factory( $codigoMunicipio )
    {
        switch($codigoMunicipio) {
            case City::SAO_PAULO:
                $this->makeWebServiceRequest = new \NFSe\Layouts\NotaPaulistana\MakeWebServiceRequest();
                break;
            default:
                throw new \Exception("Layout não implementado para o município $codigoMunicipio ou vazio.", 1439599943);
        }
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function enviarLoteRps( LoteRps $loteRps )
    {
        $this->factory( $loteRps->getCodigoMunicipo() );
        return $this->makeWebServiceRequest->enviarLoteRps( $loteRps );
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarSituacaoLoteRps( LoteRps $loteRps )
    {
        $this->factory( $loteRps->getCodigoMunicipo() );
        return $this->makeWebServiceRequest->consultarSituacaoLoteRps( $loteRps );
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarLoteRps(LoteRps $loteRps)
    {
        $this->factory( $loteRps->getCodigoMunicipo() );
        return $this->makeWebServiceRequest->consultarSituacaoLoteRps($loteRps);
    }
}

?>