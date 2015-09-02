<?php
namespace NFSe\Layouts\NotaPaulistana\Model;
use NFSe\Model\AbstractAtividadeMunicipal;

/**
 * @category   NFePHP
 * @package    NFSe\Layouts\NotaPaulistana
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */
class AtividadeMunicipal extends AbstractAtividadeMunicipal
{
    /**
     * Codigo do Serviço
     *
     * @var string
     */
    protected $codigoServico;

    /**
     * AtividadeMunicipal constructor.
     * @param string $codigoServico
     */
    public function __construct($codigoServico)
    {
        $this->codigoServico = $codigoServico;
    }

    /**
     * @return string
     */
    public function getCodigoServico()
    {
        return $this->codigoServico;
    }
}

?>