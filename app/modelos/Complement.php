<?php 
/**
 * modelo de complemento
 */
class Complement
{
	private $db;
	function __construct()
	{
		$this->db = new Base;
	}
	//obtener todos los empleados
	public function getEmpleados()
	{
		$this->db->query('SELECT a.id, a.nombre, b.nombre AS departamento, a.sueldo FROM empleado AS a, departamento AS b WHERE a.departamento = b.id');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//obtener el id del departamento
	public function getIdDepa($nombre)
	{
		$this->db->query("SELECT id FROM departamento WHERE nombre='$nombre'");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//Obtener los datos de un empleado
	public function getEmpleado($id)
	{
		$this->db->query("SELECT * FROM empleado WHERE id IN ('$id')");
		$resultado = $this->db->registro();
		return $resultado;
	}
	//Editar un resgitro
	public function editEmpleado($datos)
	{
		$this->db->query('UPDATE empleado SET nombre = :nombre, departamento = :departamento, sueldo = :sueldo WHERE id = :id');

		//vincular valores
		$this->db->bind(':id',$datos['id']);
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':departamento',$datos['departamento']);
		$this->db->bind(':sueldo',0);

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
	//Agregar empleado
	public function agregarEmp($datos)
	{
		$this->db->query('INSERT INTO empleado (id,nombre,departamento,sueldo) VALUES (:id,:nombre,:departamento,:sueldo)');

		//vincular los valores
		$this->db->bind(':id',$datos['id']);
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':departamento',$datos['departamento']);
		$this->db->bind(':sueldo',0);

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
	//Agregar departamento
	public function agregarDep($datos)
	{
		$this->db->query('INSERT INTO departamento (id,nombre,descuento_sueldo,extra) VALUES (null,:nombre,:descuento_sueldo,:extra)');

		//vincular los valores
		$this->db->bind(':nombre',$datos['nombre']);
		$this->db->bind(':descuento_sueldo',$datos['descuento_sueldo']);
		$this->db->bind(':extra',$datos['extra']);

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
}