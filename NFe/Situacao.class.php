<?php
/**
 * NFePHP - Nota Fiscal eletrônica em PHP
 *
 * @package NFePHP
 * @name    Situacao
 * @author  Djalma Fadel Junior <dfadel at ferasoft dot com dot br>
 * @author  {@link http://www.walkeralencar.com Walker de Alencar} <contato@walkeralencar.com>
 * @since   27/06/2009
 */

/**
 * Situação da NFe
 */
class NFe_Situacao {

    var $situacao_id;
    var $descricao;

    function __construct() {
        global $con;
        $this->fetch($con, 5); // 5 = em digitação
    }

    function fetch($con, $situacao_id) {
        $sql = "SELECT * FROM situacao WHERE situacao_id = ".$situacao_id;
        $qry = $con->query($sql);
        if (!MDB2::isError($qry)) {
            $row = $qry->fetchRow(MDB2_FETCHMODE_ASSOC);
            $this->situacao_id = $row['situacao_id'];
            $this->descricao   = $row['descricao'];
        }
    }
}