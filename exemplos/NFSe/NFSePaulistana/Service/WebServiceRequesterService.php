<?php
namespace NFSe\Service;
use NFSe\Model\City;
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

class WebServiceRequesterService implements WebServiceRequesterServiceInterface
{
    /**
     * @var WebServiceRequesterServiceInterface
     */
    protected $webServiceRequesterService;

    /**
     * MakeWebServiceRequest init
     *
     * @param $codigoMunicipio
     * @return void
     * @throws \Exception
     */
    private function init( $codigoMunicipio )
    {
        switch($codigoMunicipio) {
            case City::SAO_PAULO:
                $this->webServiceRequesterService = new \NFSe\Layouts\NotaPaulistana\Service\WebServiceRequesterService();
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
        $this->init( $loteRps->getCodigoMunicipo() );
        return $this->webServiceRequesterService->enviarLoteRps( $loteRps );
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarSituacaoLoteRps( LoteRps $loteRps )
    {
        $this->init( $loteRps->getCodigoMunicipo() );
        return $this->webServiceRequesterService->consultarSituacaoLoteRps( $loteRps );
    }

    /**
     *
     * @param LoteRps $loteRps
     * @return string
     */
    public function consultarLoteRps(LoteRps $loteRps)
    {
        $this->init( $loteRps->getCodigoMunicipo() );
        return $this->webServiceRequesterService->consultarSituacaoLoteRps($loteRps);
    }


    /**
     * Consulta NFS-es de um prestador dado um período
     *
     * @param Prestador $prestador
     * @param \DateTime $dataEmissaoInicio
     * @param \DateTime $dataEmissaoFim
     * @return mixed
     */
    public function consultarNfses(Prestador $prestador, \DateTime $dataEmissaoInicio, \DateTime $dataEmissaoFim)
    {
        $this->init( $prestador->getCodigoMunicipio() );
        return $this->webServiceRequesterService->consultarNfses($prestador, $dataEmissaoInicio, $dataEmissaoFim);
    }
}

?>