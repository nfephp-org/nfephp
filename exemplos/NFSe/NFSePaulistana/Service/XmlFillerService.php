<?php
namespace NFSe\Service;

/**
 * Preenche um XML de template com valores
 *
 * @category   NFePHP
 * @package    NFSe\Service
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Ernesto Amorim <ernesto dot amorim at gmail dot com>, Thiago Colares <thicolares at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

class XmlFillerService
{
    /**
     * @param string $template Caminho para um template
     * @param $data array de variáveis
     * @return mixed
     */
    public function fill($template, $data) {

        $file = \file($template);

        $xml  = implode('', $file);
        $xml = str_replace('	', '', $xml);
        $xml = str_replace(array("\r", "\n"), "", $xml);
        /**
         * @todo limpar compentários. seria útil deixar comentários nos .xmls
         */

        foreach($data as $key=>$value) {
            $match = '{{' . $key . '}}';
            $xml = str_replace($match, $value, $xml);
        }

        // (<[\w:]+>{{[^<>]*<\/[\w:]+>)  \w or : (considerando ns)
        $xml = preg_replace("/(<[\w:]+>{{[^<>]*<\/[\w:]+>)/", '', $xml);
        return $this->removeEmptyTags($xml);
    }

    /**
     * @param $xml
     * @return mixed
     */
    protected function removeEmptyTags($xml) {
        $pattern = "/(<[\w:]+>\s*<\/[\w:]+>)/";
        $res = preg_replace_callback(
            $pattern,
            function () { return ''; },
            $xml);
        if($res != $xml) {
            $res = $this->removeEmptyTags($res);
        }
        return $res;
    }
}

?>