<?php 
/**
 * modelo reporte
 */
class Reporte
{
	private $db;
	function __construct()
	{
		$this->db = new Base;
	}
	//obtener el sueldo del empleado
	public function empleado($id)
	{
		$this->db->query("SELECT * FROM empleado WHERE id='$id'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//obtener el sueldo del departamento
	public function sueldoDepa($id)
	{
		$this->db->query("SELECT * FROM departamento WHERE id='$id'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//obtener el jefe de departmaneto
	public function jefe($id)
	{
		$this->db->query("SELECT * FROM jefe_dep WHERE empleado='$id'");
		$resultado = $this->db->registro();
		return $resultado;	
	}
	//obtener los empleados con turnos medios
	public function getAllMt()
	{
		$this->db->query("SELECT a.id,b.nombre,a.entrada,a.salida FROM turnomedio AS a,empleado AS b WHERE a.id=b.id");
		$resultado = $this->db->registros();
		return $resultado;
	}
	//obtener los empleados con turnos medios
	public function getMt($id)
	{
		$this->db->query("SELECT * FROM turnomedio WHERE id='$id'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//guardar los datos del reporte
	public function guardarRepor($datos)
	{
		$this->db->query('INSERT INTO reporte (id,fecha,empleado,asistencias,retardos,temprano,extra,sabado,comision,total) values(null,:fecha,:empleado,:asistencias,:retardos,:temprano,:extra,:sabado,:comision,:total)');
		//Vincular valores
		$this->db->bind(':fecha',$datos['fecha']);
		$this->db->bind(':empleado',$datos['empleado']);
		$this->db->bind(':asistencias',$datos['asistencias']);
		$this->db->bind(':retardos',$datos['retardos']);
		$this->db->bind(':temprano',$datos['temprano']);
		$this->db->bind(':extra',$datos['extra']);
		$this->db->bind(':sabado',$datos['sabado']);
		$this->db->bind(':comision',$datos['comision']);
		$this->db->bind(':total',$datos['total']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//consultar los datos de la tabla reporte
	public function getReporte()
	{
		$this->db->query('SELECT * FROM reporte');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//verificar si el reporte se guardo anteriormente de acuerdo a la fecha
	public function verificarR($fecha,$id)
	{
		$this->db->query("SELECT * FROM reporte WHERE fecha='$fecha' AND empleado='$id'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//Editar el el total a pagar de acuendo a la comision
	public function editarR($datos)
	{
		$this->db->query("UPDATE reporte SET comision=:comision, total=:total WHERE id=:id");
		//Vincular valores
		$this->db->bind(':comision',$datos['comision']);
		$this->db->bind(':total',$datos['total']);
		$this->db->bind(':id',$datos['id']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//obtener todo los empleado
	public function getEmpleados()
	{
		$this->db->query('SELECT * FROM empleado');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//obtener el reporte de acuerdo a id
	public function reporteId($id)
	{
		$this->db->query("SELECT * FROM reporte WHERE id='$id'");
		$resultado = $this->db->registros();
		return $resultado;	
	}
	//obtener el reporte de acuerdo a fecha
	public function reporteFecha($fecha)
	{
		$this->db->query("SELECT a.id,a.fecha,b.nombre AS empleado,a.asistencias,a.retardos,a.temprano,a.extra,a.sabado,a.comision,a.total FROM reporte AS a, empleado AS b WHERE fecha='$fecha' AND a.empleado=b.id");
		$resultado = $this->db->registros();
		return $resultado;	
	}
	//obtener el total de los sueldos
	public function totalSueldos($fecha)
	{
		$this->db->query("SELECT total FROM reporte WHERE fecha='$fecha'");
		$resultado = $this->db->registros();
		return $resultado;
	}
}