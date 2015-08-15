<?php
namespace NFSe\Dto;

/**
 * Um DTO que transporta os dados de um LoteRps
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
     * @var string
     * @see \NFSe\Layouts
     */
    protected $layout;

    /**
     * LoteRps constructor.
     * @param int $numero
     * @param string $layout
     */
    public function __construct($numero, $layout)
    {
        $this->numero = $numero;
        $this->layout = $layout;
    }

    /**
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

}

?>