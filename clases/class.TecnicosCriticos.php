<?php
//set_include_path(get_include_path() . PATH_SEPARATOR . '/webpsi/clases/');

require_once ("../../../clases/class.Conexion.php");

class TecnicosCriticos{

    public function ListarTecnicosTodos() {
        $db = new Conexion();
        $cnx = $db->conectarBD();

        $cad = "SELECT a.id, a.nombre_tecnico, a.id_empresa, a.ape_paterno, a.ape_materno, a.nombres, 
				e.nombre as empresa, a.activo, a.carnet, a.carnet_critico, a.dni, a.idcedula, c.nombre as cedula, 
				a.officetrack
                FROM webpsi_criticos.tecnicos a, webpsi_criticos.empresa e, webpsi_criticos.cedula c
				WHERE a.id_empresa=e.id AND c.idcedula=a.idcedula
				ORDER BY ape_paterno ASC; ";
		//echo $cad;
		$res = mysql_query($cad, $cnx) or die(mysql_error()) ;
        while ($row = mysql_fetch_array($res))
        {
            $arr[] = $row;
        }
        //var_dump($arr);
        $cnx = NULL;
        $db = NULL;
        return $arr;
		
    }	

	
	public function Deshabilitar($idTecnico) {
        $db = new Conexion();
        $cnx = $db->conectarBD();
        
        $cad = "UPDATE webpsi_criticos.tecnicos SET activo=0 WHERE id=$idTecnico ";
        $res = mysql_query($cad, $cnx) or die(mysql_error());
        if ($res)
			$x = 1;
		else
			$x = 0;
        $cnx = NULL;
        $db = NULL;
        return $x;
    }

	public function Habilitar($idTecnico) {
        $db = new Conexion();
        $cnx = $db->conectarBD();
        
        $cad = "UPDATE webpsi_criticos.tecnicos SET activo=1 WHERE id=$idTecnico ";
        $res = mysql_query($cad, $cnx) or die(mysql_error());
        if ($res)
			$x = 1;
		else
			$x = 0;
        $cnx = NULL;
        $db = NULL;
        return $x;
    }	

    public function BuscarTecnico($id) {
        $db = new Conexion();
        $cnx = $db->conectarBD();

        $cad = "SELECT a.id, a.nombre_tecnico, a.id_empresa, a.ape_paterno, a.ape_materno, a.nombres, 
				e.nombre as empresa, a.activo, a.carnet, a.carnet_critico, a.dni, a.idcedula, a.officetrack,
				c.nombre as cedula
                FROM webpsi_criticos.tecnicos a, webpsi_criticos.empresa e, webpsi_criticos.cedula c
				WHERE a.id_empresa=e.id and a.id=$id AND c.idcedula=a.idcedula
				ORDER BY ape_paterno ASC; ";
		//echo $cad;
		$res = mysql_query($cad, $cnx) ;
        while ($row = mysql_fetch_array($res))
        {
            $arr[] = $row;
        }
        //var_dump($arr);
        $cnx = NULL;
        $db = NULL;
        return $arr;
		
    }		
	
	public function EditarTecnico( $idTecnico, $nombre, $apellidoP, $apellidoM, $idEmpresa, $carnet, $carnetCritico, $officetrack, $idCedula ) {
	
		try {
			$db = new Conexion();
			$cnx = $db->conectarPDO();
			
			$nombreTecnico = $apellidoP." ".$apellidoM." ".$nombre;
			$cad = "UPDATE webpsi_criticos.tecnicos 
					SET id_empresa=?, ape_paterno=?, ape_materno=?, nombres=?, nombre_tecnico=?,
					carnet=?, carnet_critico=?, officetrack=?, idcedula=?
					WHERE id=? ";
			$res = $cnx->prepare($cad);
			$res->bindParam("1", $idEmpresa);
			$res->bindParam("2", $apellidoP);
			$res->bindParam("3", $apellidoM);
			$res->bindParam("4", $nombre);
			$res->bindParam("5", $nombreTecnico);
			$res->bindParam("6", $carnet);
			$res->bindParam("7", $carnetCritico);
			$res->bindParam("8", $officetrack);
			$res->bindParam("9", $idCedula);
			$res->bindParam("10", $idTecnico);
			$res->execute();
			return "1";
		}
        catch (PDOException $e)
        {
			echo $e->getMessage () ;
			return "0";
        }
        $cnx = null;
        $db = null;		
	}

	public function NuevoTecnico( $nombre, $apellidoP, $apellidoM, $idEmpresa, $carnet, $carnetCritico, $officetrack, $idCedula) {
	
		try {
			$db = new Conexion();
			$cnx = $db->conectarPDO();
			
			$nombreTecnico = $apellidoP." ".$apellidoM." ".$nombre;
			$activo="1";

			$cad = "INSERT INTO webpsi_criticos.tecnicos (nombre_tecnico, id_empresa, ape_paterno,
					ape_materno, nombres, activo, carnet, carnet_critico, officetrack, idcedula)
					VALUES (?,?,?,?,?,?,?,?,?,?) ";
			$res = $cnx->prepare($cad);
			$res->bindParam("1", $nombreTecnico);
			$res->bindParam("2", $idEmpresa);
			$res->bindParam("3", $apellidoP);
			$res->bindParam("4", $apellidoM);
			$res->bindParam("5", $nombre);
			$res->bindParam("6", $activo);
			$res->bindParam("7", $carnet);
			$res->bindParam("8", $carnetCritico);
			$res->bindParam("9", $officetrack);
			$res->bindParam("10", $idCedula);
			$res->execute();
			return "1";
		}
        catch (PDOException $e)
        {
			echo $e->getMessage () ;
			return "0";
        }
        $cnx = null;
        $db = null;		
	}
	
	
    public function ListarCelulas($idEmpresa) {
        $db = new Conexion();
        $cnx = $db->conectarBD();

        $cad = "SELECT c.idcedula, c.nombre as cedula, c.estado, c.idempresa
                FROM webpsi_criticos.cedula c
				WHERE c.idempresa=$idEmpresa
				ORDER BY c.nombre ASC; ";
		//echo $cad;
		$res = mysql_query($cad, $cnx) or die(mysql_error()) ;
        while ($row = mysql_fetch_array($res))
        {
            $arr[] = $row;
        }
        //var_dump($arr);
        $cnx = NULL;
        $db = NULL;
        return $arr;
		
    }		
	
	
}

?>