<?php
	//Clase del controlador principal, se encarga de llamar a los modelos y vistas
	class Controlador 
	{
		public function modelo($modelo)
		{
			//Carga
			require_once '../app/modelos/'.$modelo.'.php';
			//Instancia el modelo
			return new $modelo();
		}

		public function vista($vista, $datos = [])
		{
			//Verifica si el archivo vista existe
			if (file_exists('../app/vistas/'.$vista.'.php'))
			{
				require_once '../app/vistas/'.$vista.'.php';
			}
			else
			{
				die('La vista no existe');
			}	
		}
	}