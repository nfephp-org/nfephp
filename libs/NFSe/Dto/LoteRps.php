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
     * @see \NFSe\LayoutType
     */
    protected $layout;

    /**
     * @var array
     */
    protected $rpss;

    /**
     * LoteRps constructor.
     * @param int $numero
     * @param string $layout
     * @param AbstractRps[] $rpss
     */
    public function __construct($numero, $layout, $rpss)
    {
        $this->numero = $numero;
        $this->layout = $layout;
        $this->rpss = $rpss;
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