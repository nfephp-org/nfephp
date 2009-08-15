<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function xml_parse_into_assoc() {

    $p = xml_parser_create();
    $data = file_get_contents('/var/www/NFe/xmlexample/retConsCad.xml');
    xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);

    xml_parse_into_struct($p, $data, $vals, $index);
    xml_parser_free($p);

    $levels = array(null);

    foreach ($vals as $val) {
        if ($val['type'] == 'open' || $val['type'] == 'complete') {
            if (!array_key_exists($val['level'], $levels)) {
                $levels[$val['level']] = array();
            }
        }

        $prevLevel =& $levels[$val['level'] - 1];
        $parent = $prevLevel[sizeof($prevLevel)-1];

        if ($val['type'] == 'open') {
            $val['children'] = array();
            array_push(&$levels[$val['level']], $val);
            continue;
        } else if ($val['type'] == 'complete') {
            $parent['children'][$val['tag']] = $val['value'];
        } else if ($val['type'] == 'close') {
            $pop = array_pop($levels[$val['level']]);
            $tag = $pop['tag'];

            if ($parent) {
                if (!array_key_exists($tag, $parent['children'])) {
                    $parent['children'][$tag] = $pop['children'];
                } else if (is_array($parent['children'][$tag])) {
                    $parent['children'][$tag][] = $pop['children'];
                }
            } else {
                return(array($pop['tag'] => $pop['children']));
            }
        }

        $prevLevel[sizeof($prevLevel)-1] = $parent;
    }
}

$resp = xml_parse_into_assoc();
echo '<pre>';
print_r($resp);
echo '</pre>';
?>
