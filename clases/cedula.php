<?php

class Cedula{

	function getCedula($cnx,$dis){
        $cnx->exec("set names utf8");
        $sql = "select idcedula id,nombre
                from webpsi_criticos.cedula
                WHERE id='$dis'
                and estado='1'
                order by nombre";
        $res = $cnx->query($sql);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row["cedula"];
        
	}

    function getCedulaAll($cnx,$id_empresa){
        $cnx->exec("set names utf8");
        $sql = "select idcedula id,nombre
                from webpsi_criticos.cedula
                where estado='1'
                and idempresa='$id_empresa'
                order by nombre";
        //echo $sql;
        $res = $cnx->query($sql);

        while ($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $arr[] = $row;
        }
        return $arr;
        
    }

}

?>