<?php 
/**
 * Clase para la vista adim
 */
class Administrador
{
	private $db;
	public function __construct()
	{
		$this->db = new Base;
	}
	//Obtener cantidad de areas
	public function coutnAreas()
	{
		$this->db->query('SELECT * FROM departamento');
		$resultado = $this->db->rowCount();
		return $resultado;
	}
	//Obtener los datos de un empleado
	public function getEmpleado($id)
	{
		$this->db->query("SELECT * FROM empleado WHERE id IN ('$id')");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//Obtener todos los datos de empleados
	public function allEmpleados()
	{
		$this->db->query('SELECT a.id, a.nombre, b.nombre AS departamento FROM empleado AS a, departamento AS b WHERE a.departamento = b.id');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Obtener cantidad de empleados
	public function countEpds()
	{
		$this->db->query('SELECT * FROM empleado');
		$resultado = $this->db->rowCount();
		return $resultado;
	}
	//Obtener el nombre del departamento y el su jefe
	public function JDepartamento()
	{
		$this->db->query("SELECT a.nombre AS area, b.nombre AS 'Jefe' FROM departamento AS a, empleado AS b, jefe_dep AS c WHERE a.id = c.departamento AND b.id=c.empleado");
		$resutlado = $this->db->registros();
		return $resutlado;
	}
	//Agregar departamento
	public function agregar($datos)
	{
		$this->db->query('INSERT INTO departamento (id,nombre,sueldo,descuento_sueldo) VALUES (null,:nombre,:sueldo,:descuento_sueldo)');

		//vincular los valores
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':sueldo',$datos['sueldo']);
		$this->db->bind(':descuento_sueldo',$datos['descuento_sueldo']);

		//Ejecutar
		if ($this->db->execute()) 
		{
			direccionar('/Empleados');
		}
		else
		{
			die('Error!!!');
		}
	}
	//Agregar empleado
	public function agregarEmp($datos)
	{
		$this->db->query('INSERT INTO empleado (id,nombre,departamento) VALUES (:id,:nombre,:departamento');

		//vincular los valores
		$this->db->bind(':id',$datos['id']);
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':departamento',$datos['departamento']);

		//Ejecutar
		if ($this->db->execute()) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Obtener departamentos
	public function getDepartamento()
	{
		$this->db->query('SELECT * FROM departamento');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Obtener id del departamento recibido como argumento
	public function getIdDepa($nombre)
	{
		$this->db->query("SELECT id FROM departamento WHERE nombre='$nombre'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//Editar un resgitro
	public function editEmpleado($datos)
	{
		$this->db->query('UPDATE empleado SET nombre = :nombre, departamento = :departamento WHERE id = :id');

		//vincular valores
		$this->db->bind(':id',$datos['id']);
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':departamento',$datos['departamento']);

		//Ejecutar
		if ($this->db->execute()) 
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	//Eliminar un registro
	public function borrar($datos)
	{
		$this->db->query('DELETE FROM empleado WHERE id = :id');

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
	//Listar jefes de departamento
	public function getJD()
	{
		$this->db->query('SELECT * FROM jefe_dep');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Obtener un registro de la tabla jefe_dep
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
	//datos para la agrafica de pastel
	public function grafica()
	{
		$this->db->query('SELECT count(a.departamento) AS suma,b.nombre AS departamento FROM empleado AS a, departamento AS b WHERE b.id =a.departamento GROUP BY a.departamento');
		$resultado = $this->db->registros();
		return $resultado;
	}
}