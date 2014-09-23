<?php
header('Content-Type: text/html; charset=UTF-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


require_once("../../../cabecera.php");
require_once("../clases/class.TecnicosCriticos.php");

if (isset($_POST["deshabilitar_tecnico"])) {
	$idTecnico = $_POST["idtecnico"];

	$tecnico = new TecnicosCriticos();
	if ( $tecnico->Deshabilitar($idTecnico)=="1" ) 
		echo "ok";
	else
		echo "error";
}

if (isset($_POST["habilitar_tecnico"])) {
	$idTecnico = $_POST["idtecnico"];
	$tecnico = new TecnicosCriticos();
	if ( $tecnico->Habilitar($idTecnico)=="1" ) 
		echo "ok";
	else
		echo "error";
}

?>
