<?php
class Login extends Controlador
{
	private $session;
	public function __construct()
	{
		$this->Login_Model = $this->modelo('Logeo');
		$this->session = new Session();
	}

	public function index()
	{
		$datos = [
			'titulo' => 'nomina',
			'dir' => 'Login/signin',
		];
		$this->vista('paginas/login',$datos);
	}

	public function signin()
	{
		if ($this->verificar($_POST)) 
			return $this->mensaje("* El usuario y password estan vacios");

		$datos = $this->Login_Model->comparar($_POST['user']);
		if (!$datos) 
			return $this->mensaje("* El usuario ".$_POST['user']." no fue encontrado");

		//$pass = password_hash('oli',PASSWORD_DEFAULT,['cost' => 12]);

		if (!password_verify($_POST['pass'],$datos->password))
			return $this->mensaje("* El password del usuario ".$_POST['user']." es incorrecto"); 
		

		//Iniciar session
		$this->session->init();
		$this->session->add('user',$datos);

		direccionar('/Admin');
	}

	public function verificar()
	{
		return empty($_POST['user']) or empty($_POST['pass']);
	}

	public function mensaje($mensaje)
	{
		$param = [
			'titulo' => 'nomina',
			'dir' => 'signin',
			'error' => $mensaje,
		];
		$this->vista('paginas/login',$param);
	}
} 