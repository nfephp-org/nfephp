<?php

namespace NFSe\Layouts\NotaPaulistana\Service;

use NFSe\Layouts\NotaPaulistana\Model\XmlFactory;
use NFSe\Model\LoteRps;
use NFSe\Model\Prestador;
use NFSe\Service\WebServiceRequesterServiceInterface;

/**
 * Classe que faz requisições para a Nota Paulistana
 *
 * @category   NFePHP
 * @package    NFSe\Layouts\NotaPaulistana\Service\WebServiceRequesterService
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
class WebServiceRequesterService implements WebServiceRequesterServiceInterface
{
    /**
     * @param LoteRps $loteRps
     * @return string
     */
    public function enviarLoteRps(LoteRps $loteRps)
    {
        // @todo usar http://symfony.com/doc/current/components/dependency_injection/ ou http://php-di.org/
        // @todo 1 construir XMLcd .
        // @todo 2 faz consulta soap e retorna

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
        // @todo usar http://symfony.com/doc/current/components/dependency_injection/ ou http://php-di.org/
        $xmlFactory = new XmlFactory();

        $xml = $xmlFactory->createConsultarNfsePeriod($prestador, $dataEmissaoInicio, $dataEmissaoFim);
    }


}

?>