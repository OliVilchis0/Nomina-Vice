<?php 
/**
 * 
 */
class Area 
{
	private $db;
	function __construct()
	{
		$this->db = new Base;
	}
	//Listar todas las areas
	public function areas()
	{
		$this->db->query('SELECT * FROM departamento');
		$resultado = $this->db->registros();
		return $resultado;
	}
	//Editar departamento
	public function editar($datos)
	{
		$this->db->query('UPDATE departamento SET descuento_sueldo = :descuento, extra = :extra WHERE id = :id');
		//Vincular valores
		$this->db->bind(':descuento',$datos['descuento']);
		$this->db->bind(':extra',$datos['extra']);
		$this->db->bind(':id',$datos['id']);
		//Ejecutar
		if ($this->db->execute()) {
			return true;
		}else{
			return false;
		}
	}
}