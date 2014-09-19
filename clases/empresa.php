<?php

class Empresa{

	function getIdEmpresa($cnx,$vempresa){

        $sql = "select * from webpsi_criticos.empresa where nombre='$vempresa'";
        $res = $cnx->query($sql);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row["id"];
        
	}

    function getEmpresaxID($cnx,$vempresa){

        $sql = "select * from webpsi_criticos.empresa where id='$vempresa'";
        $res = $cnx->query($sql);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row;
        
    }

	function getEmpresaAll($cnx){

        $sql = "select * from webpsi_criticos.empresa order by nombre";
        $res = $cnx->query($sql);
        while ($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $arr[] = $row;
        }
        return $arr;
        
	}

}

?>