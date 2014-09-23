<?php
//set_include_path(get_include_path() . PATH_SEPARATOR . '/webpsi/clases/');

require_once ("../../../clases/class.Conexion.php");

class TecnicosCriticos{

    protected $tamano = 10;
    protected $pagina = 1;
    protected $inicio = 0;
    protected $Filtros =array();

    /**
     * @return array
     */
    public function getFiltros()
    {
        return $this->Filtros;
    }

    /**
     * @param array $Filtros
     */
    public function setFiltros($Filtros)
    {
        $this->Filtros = $Filtros;
    }
    /**
     * @return int
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * @param int $inicio
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    /**
     * @return int
     */
    public function getPagina()
    {
        return $this->pagina;
    }

    /**
     * @param int $pagina
     */
    public function setPagina($pagina)
    {
        if(!empty($pagina))
            $this->pagina = $pagina;
        else
            $this->pagina = 1;
    }

    /**
     * @return int
     */
    public function getTamano()
    {
        return $this->tamano;
    }

    /**
     * @param int $tamano
     */
    public function setTamano($tamano)
    {
        $this->tamano = $tamano;
    }



    public function ListarTecnicosTodos() {
        $db = new Conexion();
        $cnx = $db->conectarBD();

        $filtro = $this->Filtros;
        //FILTROS
        $where = "";

        if(!empty($filtro)){
            if(!empty($filtro["idempresa"])){
                $where .= " and a.id_empresa = ".$filtro["idempresa"];
            }
            if(!empty($filtro["idcelula"])){
                $where .= " and a.idcedula = ".$filtro["idcelula"];
            }
            elseif(!empty($filtro["busqueda"])){
                if($filtro["tipo"] == "filtro_nombre")
                    $where .= " and  CONCAT_WS(' ',a.ape_paterno, a.ape_materno, a.nombres)  like '%".$filtro["busqueda"]."%' ";
                elseif($filtro["tipo"] == "filtro_carnet")
                    $where .= " and a.carnet like  '%".$filtro["busqueda"]."%' ";
                elseif($filtro["tipo"] == "filtro_carnet_critico")
                    $where .= " and a.carnet_critico like  '%".$filtro["busqueda"]."%' ";


            }
        }


        //paginacion
        $this->inicio = ($this->pagina - 1) * $this->tamano;


        $cad = "SELECT a.id, a.nombre_tecnico, a.id_empresa, a.ape_paterno, a.ape_materno, a.nombres, 
				e.nombre as empresa, a.activo, a.carnet, a.carnet_critico, a.dni, a.idcedula, c.nombre as cedula, 
				a.officetrack
                FROM webpsi_criticos.tecnicos a, webpsi_criticos.empresa e, webpsi_criticos.cedula c
				WHERE a.id_empresa=e.id AND c.idcedula=a.idcedula
				$where
				ORDER BY ape_paterno ASC
				Limit ".$this->inicio.",".$this->tamano;

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

    public function paginacion($filtro = array()){

        $db = new Conexion();
        $cnx = $db->conectarBD();



        $filtro = $this->Filtros;
        //FILTROS
        $where = "";

        if(!empty($filtro)){

            if(!empty($filtro["idempresa"])){
                $where .= " and a.id_empresa = ".$filtro["idempresa"];
            }
            if(!empty($filtro["idcelula"])){
                $where .= " and a.idcedula = ".$filtro["idcelula"];
            }

            elseif(!empty($filtro["busqueda"])){
                if($filtro["tipo"] == "filtro_nombre")
                    $where .= " and  CONCAT_WS(' ',a.ape_paterno, a.ape_materno, a.nombres)  like '%".$filtro["busqueda"]."%' ";
                elseif($filtro["tipo"] == "filtro_carnet")
                    $where .= " and a.carnet like  '%".$filtro["busqueda"]."%' ";
                elseif($filtro["tipo"] == "filtro_carnet_critico")
                    $where .= " and a.carnet_critico like  '%".$filtro["busqueda"]."%' ";


            }
        }


        $cad = "SELECT a.id, a.nombre_tecnico, a.id_empresa, a.ape_paterno, a.ape_materno, a.nombres,
				e.nombre as empresa, a.activo, a.carnet, a.carnet_critico, a.dni, a.idcedula, c.nombre as cedula,
				a.officetrack
                FROM webpsi_criticos.tecnicos a, webpsi_criticos.empresa e, webpsi_criticos.cedula c
				WHERE a.id_empresa=e.id AND c.idcedula=a.idcedula
				$where
                ";
        $rs = mysql_query($cad, $cnx);
        $num_total_registros = mysql_num_rows($rs);

        //calculo el total de páginas
        $total_paginas = ceil($num_total_registros / $this->tamano);
        $pagina = $this->pagina;
        $url = "";
        $html = "";

        if ($total_paginas > 1) {
            if ($pagina != 1)
                $html .= '<a href="'.$url.'?pagina='.($pagina-1).'"> << </a>';
            for ($i=1;$i<=$total_paginas;$i++) {
                if ($pagina == $i)
                    //si muestro el índice de la página actual, no coloco enlace
                    $html .=  $pagina;
                else
                    //si el índice no corresponde con la página mostrada actualmente,
                    //coloco el enlace para ir a esa página
                    $html .=  '  <a href="'.$url.'?pagina='.$i.'">'.$i.'</a>  ';
            }
            if ($pagina != $total_paginas)
                $html .=  '<a href="'.$url.'?pagina='.($pagina+1).'"> >> </a>';
        }
        return $html;

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