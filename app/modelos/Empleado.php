<?php 
/**
 * Modelo de empleado
 */
class Empleado 
{
	private $db;
	function __construct()
	{
		$this->db = new Base;
	}
	//Listar Empleados con consulta multiple
	public function getEmpleados()
	{
		$this->db->query('SELECT a.id, a.nombre, b.nombre AS departamento,a.sueldo FROM empleado AS a, departamento AS b WHERE a.departamento = b.id');
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
	//Listar jefes de departamento
	public function getJD()
	{
		$this->db->query('SELECT * FROM jefe_dep');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Lista de jefes de departamento con consulta multiple
	public function getJDM()
	{
		$this->db->query('SELECT a.id,b.nombre AS departamento,c.nombre AS empleado,sueldo_extra FROM jefe_dep AS a,departamento AS b, empleado AS c WHERE a.departamento=b.id AND a.empleado=c.id');
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
	//Obtener los registros de la tabla departamento
	public function getDepa()
	{
		$this->db->query('SELECT * FROM departamento');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Insertar Jefe de Departamento
	public function agregarJD($datos)
	{
		$this->db->query('INSERT INTO jefe_dep (id,departamento,empleado,sueldo_extra) VALUES(null, :departamento,:empleado,:sueldo_extra)');
		//Vincular valores
		$this->db->bind(':departamento',$datos['departamento']);
		$this->db->bind(':empleado',$datos['empleado']);
		$this->db->bind(':sueldo_extra',$datos['sueldo']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//Editar Jefe de Departamento
	public function editarJD($datos)
	{
		$this->db->query('UPDATE jefe_dep SET empleado = :empleado,sueldo_extra = :sueldo WHERE id = :id');
		//Vincular valores
		$this->db->bind(':empleado',$datos['empleado']);
		$this->db->bind(':sueldo',$datos['sueldo']);
		$this->db->bind(':id',$datos['id']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//Eliminar un registro
	public function borrar($datos)
	{
		$this->db->query('DELETE FROM jefe_dep WHERE id = :id');

		//vincula el valor
		$this->db->bind(':id',$datos['id']);
		//Ejecuta
		if ($this->db->execute()) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Editar el sueldo de un empleado
	public function editarSueldo($datos)
	{
		$this->db->query('UPDATE empleado SET sueldo = :sueldo WHERE id = :id');
		//Vincular valores
		$this->db->bind(':sueldo',$datos['sueldo']);
		$this->db->bind(':id',$datos['id']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//Insertar empleado con turno medio
	public function insertarTM($datos)
	{
		$this->db->query('INSERT INTO turnomedio (ids,id,entrada,salida) VALUES(null,:id,:entrada,:salida)');
		//Vincular valores
		$this->db->bind(':id',$datos['id']);
		$this->db->bind(':entrada',$datos['entrada']);
		$this->db->bind(':salida',$datos['salida']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
	//listar los empleados con medio turno
	public function getEmpleadosMT()
	{
		$this->db->query('SELECT a.id,b.nombre,a.entrada,a.salida FROM turnomedio AS a,empleado AS b WHERE a.id=b.id');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Borrar un empleado de medio turno
	public function borrarMT($datos)
	{
		$this->db->query('DELETE FROM turnomedio WHERE id = :id');

		//vincula el valor
		$this->db->bind(':id',$datos['id']);
		//Ejecuta
		if ($this->db->execute()) 
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
}