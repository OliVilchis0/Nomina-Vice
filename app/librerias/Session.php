<?php 
/**
* Clase para gestionar la session de los usuarios
*/
class Session
{
	//Inicia la session
	public function init()
	{
		session_start();
	}
	//Agrega las llaves a la session
	public function add($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	//Retorna el nombre de la session
	public function get($key)
	{
		return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
	}
	//Retorna todos los valores de la session
	public function getAll()
	{
		return $_SESSION;
	}
	//Remueve un elemento de la session a traves de la llave
	public function remove($key)
	{
		if (!empty($_SESSION[$key])) 
			unset($_SESSION[$key]);
	}
	//Destuye la session
	public function close()
	{
		session_unset();
		session_destroy();
	}
	//Retorna el estatus de la session
	public function getStatus()
	{
		return session_status();
	}
}