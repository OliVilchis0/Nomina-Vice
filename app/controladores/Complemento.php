<?php  
/**
 * notificaciones y acciones en comun
 */
class Complemento 
{
	
	function __construct()
	{
		$this->cpmtModel = $this->modelo('Complement');
	}
	//importar el modelo
	public function modelo($modelo)
	{
		//Carga
		require_once '../app/modelos/'.$modelo.'.php';
		//Instancia el modelo
		return new $modelo();
	}
	//Subir archivo excel
	public function CrudEmpleado($vista)
	{
		//Datos del empleado
		$empleado = $this->cpmtModel->getEmpleados();
		//Ruta del archivo excel
		$dir = $_FILES['archivo']['tmp_name'];
		if ($dir)
		{
			//Crear array para eliminar datos de la DB inexistentes en la hoa excel
			$borrar = array();
			//Cargar ruta del archivo a la clase de PHPExcel
			$objExcel = PHPEXCEL_IOFactory::load($dir);
			//Elegir la hoja
			$objExcel->setActiveSheetIndex(0);
			//Obtener el numero de registros de la hoja excel
			$numRows = $objExcel->setActiveSheetIndex(0)->getHighestRow();
			//Variable para identificar si un registro se a guardado y/o editado correctamente
			$agregarOk = false;
			$editarOk = false;
			$depaOk = false;
			//Variables para informacion
			$infoAgregar = '';
			$infoEditar = '';
			$infoDepa = '';
			//Recorrer hoja excel
			for ($i=5; $i <= $numRows ; $i++) { 
				//Obtener el dato de la columan a,b,c en la posicion determinado por el bucle for
				$id = $objExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
				$nombre = $objExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
				$departamento = $objExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
				//Guardar id en el array
				array_push($borrar, $id);
				//Verificar si el id de la hoja excel existe en la DB
				if ($this->verificarId($id)) {
					//Obtener el id del departamento
					$idDepa = $this->cpmtModel->getIdDepa($departamento);
					//Verificar si los nombres y/o departamentos coiciden en ambos lados
					if ($this->verificarEmp($id,$nombre,(int)$idDepa->id)) {
						$infoEditar .= '<tr><th scope="row">'.$id.'</th><th>'.$nombre.'</th><th>'.$departamento.'</th><th>Editado</th></tr>';
						//Modificar el nombre del registro con el id asignado como parametro
						$editarOk = $this->editar($id,$nombre,(int)$idDepa->id);				
					}
				}else{
					//Verificar si el departamento existe, si no es asi lo crea
					if ($this->verificarDepa($departamento)) {
						//Obtener el id del departamento
						$idDepa = $this->cpmtModel->getIdDepa($departamento);
						//Ingresar datos de empleado a la DB
						$agregarOk = $this->agregarEmp((int)$idDepa->id,$id,$nombre);
						$infoAgregar .= '<tr><th>'.$id.'</th><th>'.$nombre.'</th><th>'.$departamento.'</th><th>Agregado</th></tr>';
					}else{
						//Agregar departamento
						$depaOk = $this->agregarDep($departamento);
						//Obtener id del departamento antes ingresado
						$idDepa = $this->cpmtModel->getIdDepa($departamento);
						//Ingresar Empleado
						$agregarOk = $this->agregarEmp((int)$idDepa->id,$id,$nombre);
					}
				}
			}
			//Eliminar registros sobrantes
			$eliminar = $this->verificarRow($borrar);
			$datos = [
				'agregado' => $infoAgregar,
				'editado' => $infoEditar,
				'eliminado' => $eliminar,
				'vista' => $vista,
			];
			$resutado = $this->infoCrud($datos);
			return $resutado;
		}
		else
		{
			echo "No hay ruta";
		}
	}
	//Metodo para mostrar informacion de las acciones realizadas en el crud
	public function infoCrud($datos = [])
	{	
		if ($datos['agregado'] == '' && $datos['editado'] == '' && $datos['eliminado'] == '') {
			$resultado = '<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" 
	  			data-keyboard="false">
				  <div class="modal-dialog modal-lg">
				    <div class="modal-content">
				    	<div class="modal-header">
					        <h5 class="modal-title">Acciones</h5>
					    </div>
					    <div class="modal-body">
					    	<h5 class="text-center">No se realizaron cambios</h5>
					    </div>
				      <div class="modal-footer">
				        <a class="btn btn-secondary" href="'.$datos['vista'].'">Cerrar</a>
				        <a class="btn btn-primary" href="/nomina/Empleados">Ver más</a>
				      </div>
				    </div>
				  </div>
				</div>';
		}else{
			$resultado = '<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" 
	  			data-keyboard="false">
				  <div class="modal-dialog modal-lg">
				    <div class="modal-content">
				    	<div class="modal-header">
					        <h5 class="modal-title">Acciones</h5>
					    </div>
					    <div class="modal-body">
					    	<table class="table">
							  <thead class="thead-dark">
							    <tr>
							      <th scope="col">#</th>
							      <th scope="col">Nombre</th>
							      <th scope="col">Lugar</th>
							      <th scope="col">Accion</th>
							    </tr>
							  </thead>
							  <tbody>
							    '.$datos['editado'].$datos['agregado'].$datos['eliminado'].'
							  </tbody>
							</table>
					    </div>
				      <div class="modal-footer">
				        <a class="btn btn-secondary" href="'.$datos['vista'].'">Cerrar</a>
				        <a class="btn btn-primary" href="/nomina/Empleados">Ver más</a>
				      </div>
				    </div>
				  </div>
				</div>';
		}
		return $resultado;
	}
	//Verificar si el id del empleado existe en la DB
	public function verificarId($id)
	{
		$resultado = false;
		$empleado = $this->cpmtModel->getEmpleados();
		foreach ($empleado as $row) {
			if ($row->id == $id) {
				return true;
			}
		}
		return $resultado;
	}
	//Verificar si el empleado existe en la DB
	public function verificarEmp($id,$nombre,$depa)
	{
		$resultado = false;
		$empleado = $this->cpmtModel->getEmpleado($id);
		if ($empleado->nombre != $nombre || $empleado->departamento != $depa) {
			return true;
		}
		return $resultado;
	}
	//Editar un registro
	public function editar($id,$nombre,$depa)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			$datos = [
				'id' => $id,
				'nombre' => $nombre,
				'departamento' => $depa,
			];	
			if ($this->cpmtModel->editEmpleado($datos))
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
	}
	//Verificar si el departamento existe en la DB
	public function verificarDepa($nombre)
	{
		$resultado = false;
		$depa = $this->cpmtModel->getDepartamento();
		foreach ($depa as $row) {
			if ($row->nombre == $nombre) {
				$resultado = true;
			}
		}
		return $resultado;
	}
	//Agregar un registro cuando el departamento no existe
	public function agregarDep($nombreDep)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para departamento
			$datos = [
				'nombre' => $nombreDep,
				'descuento_sueldo' => 0,
				'extra' => 0,
			];	
			if ($this->cpmtModel->agregarDep($datos))
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
	}
	//Agregar un registro cuando el departamento existe
	public function agregarEmp($idDepa,$idEmp,$nombreEmp)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para empleado
			$datos_e = [
				'id' => $idEmp,
				'nombre' => $nombreEmp,
				'departamento' => $idDepa,
			];
			if ($this->cpmtModel->agregarEmp($datos_e)) {
				return true;	
			}
			else 
			{
				return false;
			}
		}
		else
		{
			/*$datos = [
				'nombre' => '',
				'apellido_p' => '',
				'apellido_m' => '',
				'direccion' => '',
			];
			$this->vista('ciudadanos/agregar',$datos);*/
		}
	}
	//Verficar si un registro puede borrarse
	public function verificarRow($datos = [])
	{
		//Obtener todos los empleados de la DB
		$empleado = $this->cpmtModel->getEmpleados();
		//Array donde se guardaran el id del empleado que puede borrarse
		$resultado = '';
		foreach ($empleado as $row) {
			if (!in_array($row->id, $datos)) {
				$this->borrar($row->id);
				$resultado .= '<tr><th>'.$row->id.'</th><th>'.$row->nombre.'</th><th>'.$row->departamento.'</th><th>Eliminado</th></tr>';
			}
		}
		return $resultado;
	}
	//Borrar un registro
	public function borrar($id = [])
	{
		$datos = [
			'id' => $id,
		];
		if ($this->cpmtModel->borrar($datos)) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}