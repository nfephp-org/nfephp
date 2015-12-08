<?php
namespace NFSe\Layouts\NotaPaulistana\Model;
use NFSe\Model\XmlFactoryInterface;
use NFSe\Service\XmlFillerService;

/**
 * Classe responsável por criar os XMLs de São Paulo
 *
 * @category   NFePHP
 * @package    NFSe\Layouts\NotaPaulistana\XmlFactory
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class XmlFactory implements XmlFactoryInterface
{
    /**
     * Cria XML para consulta de NFS-es por período
     *
     * @param \NFSe\Model\Prestador $prestador
     * @param \DateTime $dataEmissaoInicial
     * @param \DateTime $dataEmissaoFinal
     * @return mixed
     */
    public function createConsultarNfsePeriod(
        \NFSe\Model\Prestador $prestador, \DateTime $dataEmissaoInicial, \DateTime $dataEmissaoFinal)
    {
        $xmlFillerService = new XmlFillerService();
        print __DIR__ . 'libs/NFSe/Layouts/NotaPaulistana/Resources/Templates/ConsultarNfsePeriodo.xml';
        $xmlFillerService->fill( __DIR__ . 'libs/NFSe/Layouts/NotaPaulistana/Resources/Templates/ConsultarNfsePeriodo.xml', array());
        print "criei um xml para São Paulo <nfse>São Paulo</nfse>\n";
        return "<nfse>São Paulo</nfse>";
        // TODO: Implement createConsultarNfsePeriod() method.
    }

}

?>