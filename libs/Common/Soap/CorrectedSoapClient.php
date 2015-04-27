<?php

namespace NFePHP\Common\Soap;

/**
 * Classe auxiliar para o envio de mesagens SOAP usando o SOAP nativo do PHP
 * @category   NFePHP
 * @package    NfePHP\Common\Soap
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux dot rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \SoapClient;

class CorrectedSoapClient extends \SoapClient
{
    /**
     * __construct
     * 
     * @param mixed $wsdl
     * @param array $options
     */
    public function __construct($wsdl, $options)
    {
        parent::SoapClient($wsdl, $options);
    }
    
    /**
     * __doRequest
     * @param  string $request
     * @param  string$location
     * @param  string $action
     * @param  int $version
     * @param  int $oneWay 
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        $aFind = array(":ns1","ns1:","\n","\r");
        $sReplace = '';
        $newrequest = str_replace($aFind, $sReplace, $request);
        return parent::__doRequest($newrequest, $location, $action, $version, $oneWay);
    }
}//fim da classe
