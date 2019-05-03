<?php
	/*Mapea la url ingresada en el navegador 
	 * --> controlador
	 * --> metodo
	 * --> parametro
	*/
	 class Core 
	 {
	 	protected $CActual = 'Paginas';
	 	protected $MActual = 'index';
	 	protected $parametros = [];

	 	public function __construct()
	 	{
	 		//print_r($this->get_url());
	 		$url = $this->get_url();
	 		//Busca en la carpeta controladores si existe tal controlador
	 		if (file_exists('../app/controladores/'.ucwords($url[0]).'.php')) 
	 		{
	 			# si existe se setea el controlador por defecto
	 			$this->CActual=ucwords($url[0]);
	 			unset($url[0]);
	 		}
	 		//Requerir el controlador
	 		require_once '../app/controladores/'.$this->CActual.'.php';
	 		$this->CActual = new $this->CActual;

	 		//Verificar la segunda parte de la url, el metodo
	 		if (isset($url[1]))
	 		{
	 			if (method_exists($this->CActual, $url[1]))
	 			{
	 				#verificamos el metodo
	 				$this->MActual = $url[1];
	 				unset($url[1]);
	 			}
	 		}

	 		#echo $this->MActual;
	 		//Obtiene los parametros
	 		$this->parametros = $url ? array_values($url) : [];
	 		//Llama a la funcion callback con parametros array
	 		call_user_func_array([$this->CActual, $this->MActual], $this->parametros);
	 	}

	 	public function get_url()
	 	{
	 		//echo $_GET['url'];
	 		if (isset($_GET['url'])) 
	 		{
	 			$url = rtrim($_GET['url'],'/');
	 			$url = filter_var($url,FILTER_SANITIZE_URL);
	 			$url = explode('/',$url);
	 			return $url;
	 		}
	 	}
	 } 