<?php
	//Carga de librerias
	require_once 'config/configurar.php';

	require_once 'helpers/url_helpers.php';
	require_once 'controladores/Complemento.php';
	require_once 'controladores/Notificaciones.php';
	require RUTA_APP.'/librerias/PHPExcel/IOFactory.php';
	//require_once 'librerias/Base.php';
	//require_once 'librerias/Controlador.php';
	//require_once 'librerias/Core.php';

	//Autoload php
	spl_autoload_register(function($nombreClase){
		require_once 'librerias/'.$nombreClase.'.php';
	});