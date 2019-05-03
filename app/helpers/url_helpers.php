<?php 
	//Redireccionamiento de paginas
	function direccionar($pagina)
	{
		header('location:'.RUTA_URL.$pagina);
	}