<?php 
/**
 * Clase para verificar el logeo de nomina
 */
class Logeo
{
	private $db;
	public function __construct()
	{
		$this->db = new Base;
	}
	//Verifica si el parametro entrante existe en la db 
	public function comparar($user)
	{
		$this->db->query('SELECT * FROM usuario WHERE nombre='."'$user'");
		$resultado = $this->db->registro();
		return $resultado;
	}
}