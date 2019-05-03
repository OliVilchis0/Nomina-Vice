<?php 
/**
 * Modelo para las notificaciones
 */
class Notificacion
{
	private $db;
	function __construct()
	{
		$this->db = new Base;
	}
	//Obtener los registros de la tabla departamento
	public function getDepa()
	{
		$this->db->query('SELECT * FROM departamento');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Listar jefes de departamento
	public function getJD()
	{
		$this->db->query('SELECT * FROM jefe_dep');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Obtener un registro de la tabla jefe_dep a traves del departamento
	public function getjefeD($dato)
	{
		$this->db->query("SELECT * FROM jefe_dep WHERE departamento='$dato'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//obtener todos los empleados
	public function getEmpleados()
	{
		$this->db->query('SELECT a.id, a.nombre, b.nombre AS departamento FROM empleado AS a, departamento AS b WHERE a.departamento = b.id');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Listar empleados
	public function getEmpleadosS()
	{
		$this->db->query('SELECT * FROM empleado');
		$resultado = $this->db->registros();
		return $resultado;
	}
}