<?php
namespace NFSe\Model;

/**
 * @category   NFePHP
 * @package    NFSe\Model
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

interface XmlFactoryInterface
{
    /**
     * @todo cada layout deve criar o XML dos métodos mais comuns. E fazer by pass, se for o caso.
     *
     * EnviarLoteRps
     * ConsultarLoteRps
     * ConsultarSituacaoLoteRps
     * ConsultarNfsePeriodo
     *
     */

    /**
     * Cria XML para consulta de NFS-es por período
     *
     * @param \NFSe\Model\Prestador $prestador
     * @param \DateTime $dataEmissaoInicial
     * @param \DateTime $dataEmissaoFinal
     * @return mixed
     */
    public function createConsultarNfsePeriod(
        \NFSe\Model\Prestador $prestador, \DateTime $dataEmissaoInicial, \DateTime $dataEmissaoFinal);

}

?>