<?php
	//error_reporting(0);
	//Clase para conectarse a la BD con PDO
	class Base
	{	
		private $host = BD_HOST;
		private $usuario = BD_USUARIO;
		private $password = BD_PASSWORD;
		private $nombre_db = BD_NOMBRE;

	  	private $dbh;
	  	private $stmt;
		private $error;
		
		public function __construct()
		{
			//Configurar conexion
			$dsn = 'mysql:host='.$this->host.';dbname='.$this->nombre_db;
			$opciones = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
			//Crear una instancia de PDO
			try 
			{
				$this->dbh = new PDO($dsn,$this->usuario, $this->password, $opciones);
				$this->dbh->exec('set names utf8');	
			}
			catch (PDOException $e) 
			{
				$this->error = $e->getMessage();
				echo $this->error;
			}
		}
		//Prepara una consulta sql
		public function query($sql)
		{
			$this->stmt = $this->dbh->prepare($sql);
		}
		//Vincula la consulta con el metodo bind
		public function bind($parametro, $valor, $tipo = null)
		{
			if (is_null($tipo))
			{
				switch (true)
				{
					case is_int($valor):
						$tipo = PDO::PARAM_INT;	
					break;

					case is_bool($valor):
						$tipo = PDO::PARAM_BOOL;	
					break;

					case is_null($valor):
						$tipo = PDO::PARAM_NULL;	
					break;
					
					default:
						$tipo = PDO::PARAM_STR;
					break;
				}
			}
			$this->stmt->bindValue($parametro, $valor, $tipo);
		}
		//Ejecuta la consulta
		public function execute()
		{
			return $this->stmt->execute();
		}
		//Obtiene los registros
		public function registros()
		{
			$this->execute();
			return $this->stmt->fetchALL(PDO::FETCH_OBJ);
		}
		//Obtener un solo registro
		public function registro()
		{
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		}
		//Obtener cantidad de filas con el metodo rowCont
		public function rowCount()
		{
			$this->execute();
			return $this->stmt->rowCount();
		}
		//Obtener cantidad de filas con el metodo columncount()
		public function columnCount()
		{
			$this->execute();
			return $this->stmt->columnCount();
		}
	}