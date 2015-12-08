<?php
namespace NFSe\Model;

/**
 * DTO que transporta os dados de um LoteRps
 *
 * @category   NFePHP
 * @package    NFSe\Layouts\Dto\LoteRps
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 * @see http://martinfowler.com/eaaCatalog/dataTransferObject.html
 */

class LoteRps
{
    /**
     * @var integer
     */
    protected $numero;

    /**
     * @var array
     */
    protected $rpss;

    /**
     * @var string
     */
    protected $codigoMunicipo;

    /**
     * LoteRps constructor.
     * @param int $numero
     * @param array $rpss
     * @param string $codigoMunicipo
     */
    public function __construct($numero, array $rpss, $codigoMunicipo)
    {
        $this->numero = $numero;
        $this->rpss = $rpss;
        $this->codigoMunicipo = $codigoMunicipo;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return array
     */
    public function getRpss()
    {
        return $this->rpss;
    }

    /**
     * @return string
     */
    public function getCodigoMunicipo()
    {
        return $this->codigoMunicipo;
    }
}

?>