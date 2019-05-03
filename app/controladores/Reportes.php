<?php error_reporting(0);
class Reportes extends Controlador
{
	public function __construct()
	{
		//modelo del Reportes
		$this->reporModel = $this->modelo('Reporte');
		//Clase notificaciones 
		$this->notify = new Notificaciones;
		//Clase crud de empleados
		$this->complemento = new Complemento;
		$this->session = new Session();
		$this->session->init();
		if ($this->session->getStatus() === 1 || empty($this->session->get('user'))) 
			exit('Acceso denegado');	
	}
	public function index()
	{
		$datos = [
			'notificaciones' => $this->notificacion(),
			'status' => false,
		];
		$this->vista('paginas/administrador/ReporteView',$datos);
	}
	//Cerrar session
 	public function logout()
	{
		$this->session->close();
		direccionar('/');

	}
	//notificaciones
	public function notificacion()
	{
		$resultado = $this->notify->JD();
		return $resultado;
	}
	//subir archivo
	public function subir()
	{
		//Direccion del archivo excel
		$dir = $_FILES['archivoR']['tmp_name'];
		if ($dir)
		{
			//obtener los empleados con medio turno
			$mt = [];
			foreach ($this->reporModel->getAllMt() as $row) 
			{
				array_push($mt, $row->id);
			}	
			//variable de datos
			$tabla = '';
			//Cargar ruta del archivo a la clase de PHPExcel
			$objExcel = PHPEXCEL_IOFactory::load($dir);
			//Elegir la hoja
			$objExcel->setActiveSheetIndex(2);
			//Obtener el numero de registros de la hoja excel
			$numRows = $objExcel->setActiveSheetIndex(2)->getHighestRow();
			//obtener la fecha del reporte
			$fecha = $objExcel->getActiveSheet()->getCell('S3')->getCalculatedValue();

			for ($i=5; $i <= $numRows; $i++) { 
				if ($i%2 == 1) {
					$id = $objExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
					$nombre = $objExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();
					$depa = $objExcel->getActiveSheet()->getCell('V'.$i)->getCalculatedValue();
				}else{
					$a1 = $objExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
					$a2 = $objExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
					$a3 = $objExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
					$a4 = $objExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
					$a5 = $objExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
					$a6 = $objExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
					//array para identificar los dias de la semana
					$dias = [
						'lunes' => $a3,
						'martes' => $a4,
						'miercoles' => $a5,
						'jueves' => $a6,
						'viernes' => $a1,
					];
					//obtener el total de faltas
					$faltas = $this->faltas($dias);
					$sabado = $this->asisSabado($a2);
					$HSabado = $this->horasSabado($a2);
					//obtener las asistencias 
					$asistencias = 5-$faltas;
					//conocer si el id de excel es igual al de medio turno
					if (in_array($id, $mt)) {
						//obtener los retardos
						$retardo = $this->retardosMt($dias,$id);
						//obtener los minutos que el empleado salio temprano
						$salida = $this->salidasTMt($dias,$id);	
						//obtener las horas extras 
						$extra = $this->extrasMt($dias,$id);
					}else{
						//obtener los retardos
						$retardo = $this->retardos($dias);
						//obtener los minutos que el empleado salio temprano
						$salida = $this->salidasT($dias,$asistencias);	
						//obtener las horas extras 
						$extra = $this->extras($dias);
					}
					//obtener el total de sueldo y los minutos que no se trabajaron
					$minutos = $retardo + $salida;
					$total = $this->totalSueldo($id,$asistencias,$minutos,$extra,$HSabado['minTotal']);	
					//guardar los datos en la DB
					if ($this->reporModel->verificarR($fecha,$id) == null) {
						$this->guardarReporte($fecha,$id,$asistencias,$retardo,$salida,$extra,$HSabado['hrs'].$HSabado['min'].'00','0',$total);
					}
					//determinar si el sueldo es menor a 0
					if ($total <= 0) {
						if ($total == 0) {
							$tabla .= ' <tr class="bg-warning text-white">
									      <th scope="row">'.$id.'</th>
									      <td>'.$nombre.'</td>
									      <td>'.$asistencias.' dias</td>
									      <td>'.$this->horasMin($retardos).'.</td>
									      <td>'.$this->horasMin($salida).'.</td>
									      <td>'.$extra.' hrs.</td>
									      <td>'.$HSabado['hrs'].':'.$HSabado['min'].' hrs</td>
									      <td>
									    	<input type="text" class="monto form-control bg-transparent text-white border-0" id="sum'.$i.'" style="width:100px;height:15px" placeholder="Extra" name="sueldo">
									      </td>
									      <td>
									      	<input type="hidden"  value="'.$id.'"/> 
									      	<input type="hidden" id="sueldo'.$i.'" value="'.$total.' style="height:10px"/>
											<input type="text" class="total form-control bg-transparent text-white border-0" id="resultado'.$i.'" style="width:115px;height:5px" value="'.$total.'" name="total[]" disabled/>
									      </td>
									    </tr>';
						}else{
							$tabla .= ' <tr class="bg-danger text-white">
									      <th scope="row">'.$id.'</th>
									      <td>'.$nombre.'</td>
									      <td>'.$asistencias.' dias</td>
									      <td>'.$this->horasMin($retardos).'.</td>
									      <td>'.$this->horasMin($salida).'.</td>
									      <td>'.$extra.' hrs.</td>
									      <td>'.$HSabado['hrs'].':'.$HSabado['min'].' hrs</td>
									      <td>
									    	<input type="text" class="monto form-control bg-transparent text-white border-0" id="sum'.$i.'" style="width:100px;height:15px" placeholder="Extra" name="sueldo">
									      </td>
									      <td>
									      <input type="hidden"  value="'.$id.'"/>
									      	<input type="hidden" id="sueldo'.$i.'" value="'.$total.' style="height:10px"/>
											<input type="text" class="total form-control bg-transparent text-white border-0" id="resultado'.$i.'" name="total" style="width:115px;height:5px" value="'.$total.'" title="Parece que el empleado no registro correctamente su entrada. Deberias revisarlo manualmente!!" name="total[]" disabled/>
									      </td>
									    </tr>';
						}		    
					}else{
						$tabla .= ' <tr>
								      <th scope="row">'.$id.'</th>
								      <td>'.$nombre.'</td>
								      <td>'.$asistencias.' dias</td>
								      <td>'.$this->horasMin($retardo).'.</td>
								      <td>'.$this->horasMin($salida).'.</td>
								      <td>'.$extra.' hrs.</td>
								      <td>'.$HSabado['hrs'].':'.$HSabado['min'].' hrs</td>
								      <td>
								    	<input type="text" class="monto form-control bg-transparent border-0" id="sum'.$i.'" style="width:100px;height:15px" placeholder="Extra" name="sueldo">
								      </td>
								      <td>
								      	<input type="hidden" name="id[]" value="'.$id.'"/>
								      	<input type="hidden" id="sueldo'.$i.'" value="'.$total.' style="height:10px"/>
										<input type="text" class="total form-control bg-transparent border-0" id="resultado'.$i.'" style="width:108px;height:5px" value="'.$total.'" name="total[]" disabled/>
								      </td>
								    </tr>';
					}
				}		    
			}
			$datos = [
				'notificaciones' => $this->notificacion(),
				'status' => true,
				'tabla' => $tabla,
				'fecha' => $fecha,
			];
			$this->vista('paginas/administrador/ReporteView',$datos);
		}else{
			die('error!!');
		}
	}
	//Obtener el monto total del empleado
	public function totalSueldo($id,$asistencias,$minutos,$extra,$sabado)
	{
		//obtener MD
		$md = [];
		foreach ($this->reporModel->getAllMt() as $row) {
			array_push($md, $row->id);
		}
		//obtener el jefe de departamento
		$jefe = $this->reporModel->jefe($id);
		$dias = $asistencias;
		//obtener el empleado
		$empleado = $this->reporModel->empleado($id);
		//Establecer sueldo del empleado por dia
		$sueldo = $empleado->sueldo / 5;
		if (in_array($id, $md)) {
			$empMD = $this->reporModel->getMt($id);
			$numH = $empMD->salida - $empMD->entrada;
			$extraSa = $sueldo / $numH;
		}else{
			$extraSa = $sueldo / 9;
		}
		$extraSa /= 60; 
		//obtener el departamento
		$depa = $this->reporModel->sueldoDepa($empleado->departamento);
		//convertir las horas extras a minutos
		$extra *= 60;
		//obtener el monto total semanal
		$sueldo = ($sueldo * $dias) - ($depa->descuento_sueldo * $minutos) + ($extraSa * $extra);
		//aumentar las horas hechas en sabado
		$sabadoMin = $sabado * $extraSa;
		$sueldo += $sabadoMin; 

		if (!empty($jefe)) {
			$sueldo += $jefe->sueldo_extra;
		}
		return bcdiv($sueldo, '1','2');
	}
	//separar cadena
	public function separar($cadena)
	{
		$resultado = explode(" ", $cadena);
		return $resultado;
	}
	//obtener la cantidad de faltas
	public function faltas($dias)
	{
		$cont = 0;
		foreach ($dias as $key) {
			if (empty($key)) 
				$cont += 1;
		}
		return $cont;
	}
	//Saber si el empleado asistio en sabado
	public function asisSabado($sabado)
	{
		$resultado = 0;
		if (!empty($sabado)) {
			$resultado ++;
		}
		return $resultado;
	}
	//obtener el total de horas echas el sabado
	public function horasSabado($sabado)
	{
		//obtener la hora de entrada
		$entrada = substr($sabado, 0,5);
		$horaE = explode(':', $entrada);
		//obtener la hora de salida
		$salida = substr($sabado, -5);
		$horaS = explode(':', $salida);
		//Variable para la salida a comida
		$horasC = 0;
		//obtener la hora de comida
		for ($i=0; $i < strlen($sabado); $i += 5) { 
			$comidaS = substr($sabado, $i,5);
			$horaComida = explode(':', $comidaS);
			if ($horaComida[0] == 14) {
				for ($i=0; $i < strlen($sabado); $i += 5) { 
					$comidaE = substr($sabado, $i,5);
					$comidaEM = explode(':', $comidaE);
					if ($comidaEM[0] == 15) {
						$horasC = (($comidaEM[0] - $horaComida[0]) * 60) + ($comidaEM[1] - $horaComida[1]);
					}
				}
			}
		}
		//obtener el total de horas
		$horasT = (($horaS[0] - $horaE[0]) * 60) + ($horaS[1] - $horaE[1]);
		$horasT -= $horasC;
		$hrs = $horasT / 60;
		$min = $horasT % 60;
		//concatenar un 0 si el tamaño de la cadena es 1
		if (strlen($min) == 1) {
			(string)$min = '0'.$min;
		}
		$resultado = ['hrs' => floor($hrs), 'min' => $min, 'minTotal' => $horasT];
		return $resultado;
	}
	//obtener los retardos
	public function retardos($dias)
	{	
		$resultado = 0;
		foreach ($dias as $key) {
			$entrada = substr($key, 0,5);	
			$retardo = explode(":", $entrada);
			if ($retardo[0] >= 9){ 
				if ($retardo[0] == 9) {
					$resultado += $retardo[1];
				}
				if ($retardo[0] > 9) {
					$horas = ($retardo[0] - 9) * 60;
					$resultado += $horas + $retardo[1]; 
				}
			}
			$entradaC = substr($key, -10,-5);
			$retardoC = explode(":", $entradaC);
			if ($retardoC[0] == 15) {
				$resultado += $retardoC[1];
			}
		}
		return $resultado;
	}
	//obtener los minutos en que un empleado salio temprano
	public function salidasT($dias,$asistencias)
	{
		$resultado = 0;
		$min = 60;
		$horas = 0;
		foreach ($dias as $key) {
			if ($asistencias != 0) {
				$salida = substr($key, -5);
				$hora = explode(":", $salida);
				if ($hora[0] < 19 && $hora[0] > 0){
					$horas = 19 - $hora[0];
					if ($horas != 1){
						$horas *= 60;
						$horas += $min-$hora[1];
						$resultado = $horas;
					}	
					else{
						$resultado += $min-$hora[1];	
					}
				} 
			}
		}
		return $resultado;
	}
	//Obtener las horas extra
	public function extras($dias)
	{
		$resultado = 0;
		//horas sumadas
		$extraH = 0;
		foreach ($dias as $key) {
			$salida = substr($key, -5);
			$hora = explode(":", $salida);
			if ($hora[0] >= 19) {
				if ($hora[0] > 19) {
					$extraH = $hora[0] - 19;
					$extraH *= 60;
					$extraH += $hora[1];
					$extraH /= 60;
					$resultado = $extraH;
				}
			}
		}
		return floor($resultado);
	}
	//obtener los retardos para empleados de medio tiempo
	public function retardosMt($dias,$id)
	{
		$resultado = 0;
		//obtener el empleado con medio turno
		$mt = $this->reporModel->getMt($id);
		foreach ($dias as $row) {
			$entrada = substr($row, 0,5);
			$hora = explode(':', $entrada);
			if ($hora[0] >= substr($mt->entrada, 0,2)) {
				$retardo = $hora[0] - substr($mt->entrada, 0,2);
				if ($retardo != 0) {
					$retardo *= 60;
					$resultado = $retardo + $hora[1];
				}else{
					$resultado += $hora[1];
				}
			}
		}
		return $resultado;
	}
	//obtener las salidas temprano para empleadod de medio tiempo
	public function salidasTMt($dias,$id)
	{
		$resultado = 0;
		//obtener el empleado con medio turno
		$mt = $this->reporModel->getMt($id);
		foreach ($dias as $row) {
			$salida = substr($row, -5);
			$hora = explode(':', $salida);
			if ($hora[0] < substr($mt->salida, 0,2) && $hora[0] > 0) {
				$temprano = substr($mt->salida, 0,2) - $hora[0];
				if ($temprano != 0 && $temprano > 1) {
					$temprano *= 60;
					$resultado = $temprano + $hora[1];
				}else{
					$temprano *= 60;
					$temprano -= $hora[1];
					$resultado += $temprano;
				}
			}
		}
		return $resultado;
	}
	//obtener las horas extras para empleados con medio tiempo
	public function extrasMt($dias,$id)
	{
		$resultado = 0;
		//obtener el empleado con medio turno
		$mt = $this->reporModel->getMt($id);
		foreach ($dias as $row) {
			$salida = substr($row, -5);
			$hora = explode(':', $salida);
			if ($hora[0] > substr($mt->salida, 0,2)) {
				$extra = $hora[0] - substr($mt->salida, 0,2);
				$extra *= 60;
				$resultado += $extra;
				$resultado /= 60;
			}
		}
		return floor($resultado);
	}
	//Guardar los datos de el reporte
	public function guardarReporte($fecha,$id,$asistencias,$retardo,$salida,$extra,$sabado,$comision,$total)
	{
		//Datos para reporte
		$datos = [
			'fecha' => $fecha,
			'empleado' => $id,
			'asistencias' => $asistencias,
			'retardos' => $retardo,
			'temprano' => $salida,
			'extra' => $extra,
			'sabado' => $sabado,
			'comision' => $comision,
			'total'	 => $total,
		];
		if ($this->reporModel->guardarRepor($datos)) 
		{
			return true;	
		}
		else 
		{
			return false;
		}
	}
	//
	//descargar archivo excel de reportes
	public function descargar()
	{
		//obtener el total de empleados
		$datos =  $_REQUEST['datos'];
		for ($i=0; $i <= sizeof($datos[0]) ; $i++) { 
			//obtener el reporte con la fecha y el id 
			$resultado = $this->reporModel->verificarR($datos[2],$datos[0][$i]);
			//obtener la comision 
			$comision = $datos[1][$i] - $resultado->total;
			if ($comision < 0) {
				$comision = 0;
			}
			$this->editarR($resultado->id,$comision,$datos[1][$i]);	
		}
		echo '<a href="/nomina/Reportes/excel/'.$datos[2].'" class="btn btn-success mx-auto w-50">
			<i class="fas fa-file-download text-white"> Descargar</i>
		</a>
		<a href="javascript:location.reload()" class="btn btn-secondary">
            <i class="fas fa-sync-alt text-white"></i>
        </a>';
	}
	//descargar el archivo excel
	public function excel($fecha)
	{
		//obtener el total de sueldos 
		$sueldoTotal = $this->totalS($fecha);
		//Obtener los datos de reporte
		$getReporte = $this->reporModel->reporteFecha($fecha);
		//Instanciar el objeto PHPExcel
		$objexcel = new PHPExcel();
		//Establecer las propiedades
		$objexcel->getProperties()
		->setCreator('Oliver Vilchis')
		->setTitle('Suledos')
		->setDescription('Documento de sueldos')
		->setKeywords('Excel')
		->setCategory('Nomina');
		//establecer hoja 1
		$objexcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
		//Nombre de la hoja
		$objexcel->getActiveSheet()->setTitle('Nomina');
		//Establecer color de cabercera
		$objexcel->getActiveSheet()
	    ->getStyle('A2:G2')
	    ->applyFromArray(
	        array(
	            'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => '46678D'),
	            ),
	            'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => 'FFFFFF'),
		        'size'  => 12,
		        'name'  => 'Verdana'
		    ),
	            'alignment' => array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        )
	        )
	    );
	    $objexcel->getActiveSheet()
	    ->getStyle('A1:G1')
	    ->applyFromArray(
	        array(
		            'font'  => array(
			        'bold'  => true,
			        'color' => array('rgb' => '46678D'),
			        'size'  => 14,
			        'name'  => 'Verdana'
			    ),
		            'alignment' => array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        )
	        )
	    );
	    //Titulo
	    $objexcel->getActiveSheet()->setCellValue('A1','Rerorte de  Sueldos     Fecha: '.$fecha);
		//Establecer Tamaño de celdas
		//columna A
		$objexcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('A')->setWidth("15");
		//columna B
		$objexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
		//columna C
		$objexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('C')->setWidth("15");
		//columna D
		$objexcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('D')->setWidth("15");
		//columna E
		$objexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('E')->setWidth("15");
		//columna F
		$objexcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('F')->setWidth("15");
		//columna G
		$objexcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('G')->setWidth("15");
		//establecer datos		
		$objexcel->getActiveSheet()->setCellValue('A2','Empleado');
		$objexcel->getActiveSheet()->setCellValue('B2','Asistencias');
		$objexcel->getActiveSheet()->setCellValue('C2','Retardos');
		$objexcel->getActiveSheet()->setCellValue('D2','Temprano');
		$objexcel->getActiveSheet()->setCellValue('E2','Extra');
		$objexcel->getActiveSheet()->setCellValue('F2','Comision');
		$objexcel->getActiveSheet()->setCellValue('G2','Total');
		$i = 3;
		foreach ($getReporte as $row) {
			$objexcel->getActiveSheet()
		    ->getStyle('A'.$i.':G'.$i)
		    ->applyFromArray(
		        array(
			            'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        )
		        )
		    );
			$objexcel->getActiveSheet()->setCellValue('A'.$i,$row->empleado);
			$objexcel->getActiveSheet()->setCellValue('B'.$i,$row->asistencias);
			$objexcel->getActiveSheet()->setCellValue('C'.$i,$row->retardos);
			$objexcel->getActiveSheet()->setCellValue('D'.$i,$row->temprano);
			$objexcel->getActiveSheet()->setCellValue('E'.$i,$row->extra);
			$objexcel->getActiveSheet()->setCellValue('F'.$i,$row->comision);
			$objexcel->getActiveSheet()->setCellValue('G'.$i,$row->total);
			$i++;
		}
		$objexcel->getActiveSheet()
		    ->getStyle('A'.$i.':G'.$i)
		    ->applyFromArray(
		        array(
			            'font'  => array(
				        'bold'  => true,
				        'color' => array('rgb' => '46678D'),
				        'size'  => 12,
				        'name'  => 'Verdana'
				    ),
			            'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        )
		        )
		    );
		$objexcel->getActiveSheet()->setCellValue('F'.$i,'Total: ');
		$objexcel->getActiveSheet()->setCellValue('G'.$i,'$ '.$sueldoTotal);




		//Establecer la hoja 2 para imprimir sobres
		$objexcel->createSheet(1);
		$objexcel->setActiveSheetIndex(1);
		//espaciado a las columnas
		//columna B
		$objexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('B')->setWidth("15");
		//columna C
		$objexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(false);
		$objexcel->getActiveSheet()->getColumnDimension('C')->setWidth("15");
		//Nombre de la hoja
		$objexcel->getActiveSheet()->setTitle('Sobres');
		$j = 1;
		foreach ($getReporte as $key) {
			$objexcel->getActiveSheet()
		    ->getStyle('A'.$j.':D'.$j)
		    ->applyFromArray(
		        array(
			            'fill' => array(
		                'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                'color' => array('rgb' => '46678D'),
		            ),
			            'font'  => array(
				        'bold'  => true,
				        'color' => array('rgb' => 'FFFFFF'),
				        'size'  => 11,
				        'name'  => 'Verdana'
				    ),
		            	'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        )
		        )
		    );
			$objexcel->getActiveSheet()->setCellValue('A'.$j,'ID:  '.$key->id);
			$objexcel->getActiveSheet()->setCellValue('B'.$j,'NOMBRE');
			$objexcel->getActiveSheet()->setCellValue('C'.$j,$key->empleado);
			$objexcel->getActiveSheet()->setCellValue('A'.($j+1),'1');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+1),'Asistencias');
			$objexcel->getActiveSheet()->setCellValue('C'.($j+1),$key->asistencias.' Días');
			$objexcel->getActiveSheet()->setCellValue('D'.($j+1),$this->sueldoDias($key->id,$key->asistencias));
			$objexcel->getActiveSheet()->setCellValue('A'.($j+2),'2');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+2),'Retardos');
			$objexcel->getActiveSheet()->setCellValue('C'.($j+2),$this->horasMin($key->retardos));
			$objexcel->getActiveSheet()->setCellValue('D'.($j+2),$this->retardoSue($key->id,$key->retardos));
			$objexcel->getActiveSheet()->setCellValue('A'.($j+3),'3');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+3),'Temprano');
			$objexcel->getActiveSheet()->setCellValue('C'.($j+3),$this->horasMin($key->temprano));
			$objexcel->getActiveSheet()->setCellValue('D'.($j+3),$this->retardoSue($key->id,$key->temprano));
			$objexcel->getActiveSheet()->setCellValue('A'.($j+4),'4');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+4),'Extra');
			$objexcel->getActiveSheet()->setCellValue('C'.($j+4),$this->horasMin($key->extra*60));
			$objexcel->getActiveSheet()->setCellValue('D'.($j+4),$this->extraSue($key->id,$key->extra));
			$objexcel->getActiveSheet()->setCellValue('A'.($j+5),'5');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+5),'Sabado');
			$objexcel->getActiveSheet()->setCellValue('C'.($j+5),$key->sabado.' Hrs');
			$objexcel->getActiveSheet()->setCellValue('D'.($j+5),$this->sueldoSab($key->id,$key->sabado));
			$objexcel->getActiveSheet()->setCellValue('A'.($j+6),'6');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+6),'Comision');
			$objexcel->getActiveSheet()->setCellValue('D'.($j+6),$key->comision);
			$objexcel->getActiveSheet()->setCellValue('A'.($j+7),'7');
			$objexcel->getActiveSheet()->setCellValue('B'.($j+7),'Total');
			$objexcel->getActiveSheet()->setCellValue('D'.($j+7),$key->total);
			$j += 12;
		}




		//configurar cabeceras
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Sueldos_'.$fecha.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objexcel,'Excel2007');
		$objWriter->save('php://output');
	}
	//Editar el reporte para establecer la comision y el total
	public function editarR($id,$comision,$total)
	{
		$datos = [
			'id' => (int)$id,
			'comision' => trim((double)$comision),
			'total' => trim((double)$total),
		];
		if ($this->reporModel->editarR($datos)) {
			return true;
		}else{
			return false;
		}
	}
	//obtener el total de suldos
	public function totalS($fecha)
	{
		$resultado = 0;
		$totalSueldo = $this->reporModel->totalSueldos($fecha);
		foreach ($totalSueldo as $row) {
			$resultado += $row->total; 
		}
		return $resultado;
	}
	//obtener horas a partir del parametro minutos
	public function horasMin($minutos)
	{
		//obtener las horas
		$horas = $minutos / 60;
		//obtener los minutos
		$minutos %= 60;
		//retornar minuros si no hay horas
		if (floor($horas) == 0) {
			$resultado = $minutos.' Min';
		}else{
			if ($minutos <= 10) {
				$resultado = floor($horas).':0'.$minutos.' Hrs';
			}else{
				$resultado = floor($horas).':'.$minutos.' Hrs';
			}
		}
		return $resultado;
	}
	//obtener sueldo de acuerdo a los dias
	public function sueldoDias($id,$asistencias)
	{
		//obtener el empleado
		$empleado = $this->reporModel->empleado($id);
		$sueldoDia = $empleado->sueldo;
		$sueldoDia /= 5;
		$sueldo = $sueldoDia * $asistencias;
		return $sueldo;
	}
	//obtener sueldo de sabado
	public function sueldoSab($id,$horas)
	{
		$resultado = 0;
		$sab = substr($horas, 0,5);
		$horasSa = explode(':', $sab);
		//obtener el empleado de medio turno si existe
		$mt = $this->reporModel->getMt($id);
		if (!empty($mt)) {
			//obtener el empleado de medio turno
			$empleado = $this->reporModel->empleado($id);
			$sueldoDia = $empleado->sueldo;
			$horasT = $mt->salida - $mt->entrada;
			$sueldoDia /= 5;
			$sueldoDia /= $horasT;
			$resultado = ($sueldoDia * $horasSa[0]) + (($sueldoDia /= 60)*$horasSa[1]);
		}else{
			//obtener el empleado
			$empleado = $this->reporModel->empleado($id);
			$sueldoDia = $empleado->sueldo;
			$sueldoDia /= 5;
			$sueldoDia /= 9;
			$sueldomin = $sueldoDia / 60;
			$resultado = ($sueldoDia * $horasSa[0]) + ($sueldomin * $horasSa[1]);
		}
		return bcdiv($resultado,'1','2');
	}
	//obtener retardo en efectivo
	public function retardoSue($id,$min)
	{

		$sueldo = $min * .50;
		return bcdiv($sueldo, '1','2');
	}
	//obtener las horas extras en efectivo
	public function extraSue($id,$min)
	{
		//obtener el empleado
		$empleado = $this->reporModel->empleado($id);
		$sueldo = $empleado->sueldo;
		$sueldo /= 5;
		$sueldo /= 9;
		$sueldo *= $min;
		return bcdiv($sueldo, '1','2');
	}
}