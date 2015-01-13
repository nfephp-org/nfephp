<?php
/*
 *
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function listDir($dir,$fileType){

    $inName=array();

    if (is_dir($dir)){
        chdir($dir);
        $diretorio = getcwd();

        // abre o diretório
        $ponteiro  = opendir($diretorio);
        $x = 0;
        // monta os vetores com os itens encontrados na pasta
        while (false !== ($file = readdir($ponteiro))) {
            if ($file != "." && $file != ".." ) {
                $aFile = explode(".", $file);
                if ($aFile[1] == $fileType ){
                    $inName[$x] = $file;
                    $x++;
                }
            }
        }
    closedir($ponteiro);
    }
    return $inName;
}
?>
