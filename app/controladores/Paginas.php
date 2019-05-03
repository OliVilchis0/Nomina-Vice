<?php 
/**
 * clase principal
 */
class Paginas extends Controlador
{
	
	function __construct()
	{

	}
	//funcion principa
	public function index()
	{
		$datos = [
			'mensaje' => 'hola mundo',
		];
		$this->vista('/paginas/inicio');
	}
}