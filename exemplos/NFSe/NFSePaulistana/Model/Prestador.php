<?php
namespace NFSe\Model;

/**
 * DTO que transporta os dados de um prestador
 *
 * @category   NFePHP
 * @package    NFSe\Dto
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class Prestador
{
    /**
     * Código da município de origem do prestador
     *
     * @var string
     */
    protected $codigoMunicipio;

    /**
     * Prestador constructor.
     * @param string $codigoMunicipio
     */
    public function __construct($codigoMunicipio)
    {
        $this->codigoMunicipio = $codigoMunicipio;
    }

    /**
     * @return string
     */
    public function getCodigoMunicipio()
    {
        return $this->codigoMunicipio;
    }
}

?>