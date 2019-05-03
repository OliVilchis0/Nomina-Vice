<?php 
/**
 * Controlador para empleados
 */
class Empleados extends Controlador
{
	function __construct()
	{
		$this->notify = new Notificaciones;
		$this->complemento = new Complemento;
		$this->EmpleadoModel = $this->modelo('Empleado');
		$this->session = new Session();
		$this->session->init();
		if ($this->session->getStatus() === 1 || empty($this->session->get('user'))) 
			exit('Acceso denegado');
	}
	//Metodo principal
	public function index()
	{
		$datos = [
 			'empleados' => $this->getEmpleados(),
 			'verjefe' => $this->verJD(),
 			'modal' => '',
 			'notificaciones' =>  $this->notificacion(),
 			'sueldos' => $this->sueldos_emp(),
 			'turnos' => $this->turnosM(),
 			'nombresTurnos' => $this->empleadosTabla(),
 		];
		$this->vista('paginas/administrador/empleados',$datos);
	}
	//notificaciones
	public function notificacion()
	{
		$resultado = $this->notify->JD();
		return $resultado;
	}
	//metodo para agregar,editar o eliminar registros de acuerdo a la hoja excel
	public function Agregar()
	{
		//metodo de la clase complemento
		$resultado = $this->complemento->CrudEmpleado('/nomina/Empleados');
		//datos para recargar vista
		$datos = [
 			'empleados' => $this->getEmpleados(),
 			'notificaciones' =>  $this->notify->JD(),
 			'verjefe' => $this->verJD(),
 			'modal' => $resultado,
 			'notificaciones' =>  $this->notificacion(),
 			'sueldos' => $this->sueldos_emp(),
 			'turnos' => $this->turnosM(),
 			'nombresTurnos' => $this->empleadosTabla(),
 		];
 		//Recargar vista
		$this->vista('paginas/administrador/empleados',$datos);
	}
	//Cerrar session
 	public function logout()
	{
		$this->session->close();
		direccionar('/');
	}
	//Obtener Empleados
	public function getEmpleados()
	{
		//Obtener registros de empleados
		$empleados = $this->EmpleadoModel->getEmpleados();
		//obtener los empleados con medio turno
		$MT = [];
		//Obtener los registro de jefes de departamento con tipo de dato string
		$JD = [];
		foreach ($this->EmpleadoModel->getJD() as $row1) {
			array_push($JD, $row1->empleado);
		}
		foreach ($this->EmpleadoModel->getEmpleadosMT() as $key ) {
			array_push($MT, $key->id);
		}
		//Variable para listar empleados en formato de tabla
		$resultado = '';
		foreach ($empleados as $row) {
			if (in_array($row->id, $JD)) {
				$resultado .= '<tr><th class="bg-azul text-white">'.$row->id.'</th><th class="bg-azul text-white">'.$row->nombre.'  <i class="fas fa-user-tie text-white"></i></th><th class="bg-azul text-white">'.$row->departamento.'</th><th class="bg-azul text-white">'.$row->sueldo.'</th></tr>';
			}if (in_array($row->id, $MT)) {
				$resultado .= '<tr class="bg-warning text-white"><th>'.$row->id.'</th><th>'.$row->nombre.'</th><th>'.$row->departamento.'</th><th>$ '.$row->sueldo.'</th></tr>';
			}else{
				$resultado .= '<tr><th>'.$row->id.'</th><th>'.$row->nombre.'</th><th>'.$row->departamento.'</th><th>$ '.$row->sueldo.'</th></tr>';
			}
		}
		return $resultado;
	}
	//Establecer sueldos de empleados
	public function sueldos_emp()
	{
		//variable para formularios
		$datos = '';
		//Obtener todos los empleados
		$empleados = $this->EmpleadoModel->getEmpleados();
		foreach ($empleados as $row) {
			$datos .= '<div class="form-row">
	                  <div class="col-md-4 mb-3 p-1 text-left">
	                  	<input type="hidden" name="'.$row->id.'" value="'.$row->id.'">
	                    <label class="border-bottom w-100">'.$row->id.'. '.$row->nombre.'</label>
	                  </div>
	                  <div class="input-group col-md-4 mb-3">
				        <div class="input-group-prepend">
				          <span class="input-group-text" id="validationTooltipUsernamePrepend">$</span>
				        </div>
				        <input type="text" class="form-control" id="validationTooltipUsername" placeholder="Sueldo" aria-describedby="validationTooltipUsernamePrepend" name="sueldo'.$row->id.'" value="'.$row->sueldo.'" required>
				        <div class="invalid-tooltip">
				          Ingresa el sueldo.
				        </div>
				      </div>
				      <div class="input-group col-md-4 mb-3">
				        <div class="input-group-prepend">
				          <span class="input-group-text" id="validationTooltipUsernamePrepend">%</span>
				        </div>
				        <input type="text" class="form-control" id="validationTooltipUsername" placeholder="Aumento" aria-describedby="validationTooltipUsernamePrepend" name="aumento'.$row->id.'" value="0" required>
				        <div class="invalid-tooltip">
				          Ingresa el descuento.
				        </div>
				      </div>
	                </div>';
		}
        return $datos;
	}
	//agregar sueldos
	public function sueldos()
	{
		//obtener el total de empleados
		$totalEmp = sizeof($this->EmpleadoModel->getEmpleados());
		//variabla para id y sueldo
		$id = 0;
		$sueldo = 0;
		$aumento = 0;
		$suma = 0;
		//Varieble booleana
		$estado = false;
		for ($i=1; $i <= $totalEmp ; $i++) { 
			$id = $_POST[$i];
			$sueldo = $_POST['sueldo'.$i];
			$aumento = $_POST['aumento'.$i];
			if ($aumento != 0) {
				$suma = ($aumento * $sueldo) / 100;
				$sueldo += $suma;
				if ($this->editarSueldo($id,$sueldo)) {
					$estado = true;
				}
			}else{
				if ($this->editarSueldo($id,$sueldo)) {
					$estado = true;
				}
			}
		}
		if ($estado) {
			direccionar('/Empleados');
		}
	}
	//Editar el sueldo de un empleado
	//Editar un registro
	public function editarSueldo($id,$sueldo)
	{
		//Datos para empleado
		$datos = [
			'id' => (int)$id,
			'sueldo' => (double)$sueldo,
		];
		if ($this->EmpleadoModel->editarSueldo($datos)) {
			return true;	
		}
		else 
		{
			return false;
		}
	}
	//Listar todos los empleados 
	public function allEmpleados($id)
	{
		//variable de retorno
		$resultado = '';
		//Obtener datos de los empleados
		$empleado = $this->EmpleadoModel->getEmpleadosS();
		//Obtener los departmanetosn
		$depa = $this->EmpleadoModel->getDepa();
		foreach ($empleado as $row) {
			if ($row->departamento == $id) {
				$resultado .= '<option value='.$row->id.'>'.$row->nombre.'</option>'; 
			}elseif($id == ''){
				$resultado .= '<option value='.$row->id.'>'.$row->nombre.'</option>';
			}
		}
		return $resultado;
	}
	//Insertar datos del jefe del departamento
	public function insertarJD()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para empleado
			$datos = [
				'departamento' => trim($_POST['departmen']),
				'empleado' => trim($_POST['empleado']),
				'sueldo' => trim($_POST['sueldo']),
			];
			if ($this->EmpleadoModel->agregarJD($datos)) {
				direccionar('/Empleados');	
			}
			else 
			{
				die('Error!!!');
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
	//Visualizar jefes de depertamento en la seccion dedicada
	public function verJD()
	{
		//Obtener los jefes de departamento
		$jefes = $this->EmpleadoModel->getJDM();
		//Variable para retornar
		$boton = '';
			$modal = '';
		foreach ($jefes as $row) {
			$boton .= '<li class="list-group-item">
							<button class="departamento btn btn-sm btn-block" data-toggle="modal" data-target="#modal'.$row->id.'">
								'.$row->empleado.'
							</button>
						</li>';
			$modal .= '<!-- Modal -->
	        <div class="modal fade" id="modal'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="depa" aria-hidden="true">
	          <div class="modal-dialog" role="document">
	            <div class="modal-content">
	              <div class="modal-header">
	                <h4 class="modal-title text-dark" id="exampleModalLabel">'.$row->empleado.' es el jefe de '.$row->departamento.'</h4>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                  <span aria-hidden="true">&times;</span>
	                </button>
	              </div>
	              <div class="modal-body">
	                <form method="POST" action="/nomina/Empleados/editarJD/'.$row->id.'">
	                	<input type="hidden" id="custId" name="depa" value="'.$row->departamento.'">
	           		   <div class="form-group row">
						    <label for="inputEmail3" class="col-sm-5 col-form-label text-dark">Cambiar Jefe</label>
						    <div class="col-sm-3">
								<select class="form-control form-control-sm" name="empleado">
									'.$this->allEmpleados('').'			  
								</select>
						    </div>
						    <div class="col-sm-4">
						    	<input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Sueldo extra" name="sueldo" value="'.$row->sueldo_extra.'">
						    </div>
						</div>    
		              <div class="modal-footer">
		              	<a class="btn btn-danger" href="/nomina/Empleados/EliminarJD/'.$row->id.'">Eliminar</a>
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		                <button type="Submit" class="btn btn-primary">Modificar</button>
		              </div>
	                </form>
	              </div>
	            </div>
	          </div>
	        </div>';
		}
		$resultado = [
			'boton' => $boton,
			'modal' => $modal,
		];
		return $resultado;
	}
	//Eliminar un registro
	public function EliminarJD($id)
	{
		$datos = [
			'id' => $id,
		];
		if ($this->EmpleadoModel->borrar($datos)) 
		{
			direccionar("/Empleados");
		}
		else
		{
			die("Error!!!!");
		}
	}
	//Editar un registro
	public function editarJD($id)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para empleado
			$datos = [
				'id' => (int)$id,
				'empleado' => trim((int)$_POST['empleado']),
				'sueldo' => trim((double)$_POST['sueldo']),
			];
			if ($this->EmpleadoModel->editarJD($datos)) {
				direccionar('/Empleados');	
			}
			else 
			{
				die('Error!!!');
			}
		}
	}
	//metodo para los turnos especiales
	public function turnosM()
	{
		//obtener todos los empleados
		$empleados = $this->EmpleadoModel->getEmpleados();
		//variable para obtener empleados en forma de listado
		$listEmp = '';
		foreach ($empleados as $row) {
			$listEmp .= '<option  value="'.$row->id.'">'.$row->nombre.'</option>';
		}
		$resultado = '<div class="form-row">
                      <div class="col-md-4 mb-3 p-1">
                        <select class="custom-select" name="idturno">
                          '.$listEmp.'
                        </select>
                      </div>
                      <div class="input-group col-md-4 mb-3 p-1">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fas fa-stopwatch"></i></span>
                        </div>
                        <input type="text" class="form-control" id="validationTooltipUsername" placeholder="Entrada" aria-describedby="validationTooltipUsernamePrepend" name="entrada" required>
                        <div class="invalid-tooltip">
                          Ingresa el horario de entrada.
                        </div>
                      </div>
                      <div class="input-group col-md-4 mb-3 p-1">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="validationTooltipUsernamePrepend"><i class="fas fa-stopwatch"></i></span>
                        </div>
                        <input type="text" class="form-control" id="validationTooltipUsername1" placeholder="Salida" aria-describedby="validationTooltipUsernamePrepend" name="salida"  required>
                        <div class="invalid-tooltip">
                          Ingresa el horario de salida.
                        </div>
                      </div>
                    </div>';
		return $resultado;
	}
	//listar en forma de tabla a los empleados que trabajan medio turno
	public function empleadosTabla()
	{
		$resultado = '';
		//obtener los empleados con medio turno
		$mt = $this->EmpleadoModel->getEmpleadosMT();
		if (!empty($mt)) {
			foreach ($mt as $row) {
				$resultado .= '<tr><th>'.$row->nombre.'</th><th>'.$row->entrada.' hrs</th><th>'.$row->salida.' hrs</th><th><a href="/nomina/Empleados/borrarMT/'.$row->id.'" class="btn btn-danger btn-sm btn-block"><i class="fas fa-trash-alt text-white"></i></a></th></tr>';
			}
		}
		return $resultado;
	}
	//guardar un empleado con medio turno
	public function turnosMedios()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para empleado
			$datos = [
				'id' => trim($_POST['idturno']),
				'entrada' => trim((int)$_POST['entrada']),
				'salida' => trim((int)$_POST['salida']),
			];
			if ($this->EmpleadoModel->insertarTM($datos)) {
				direccionar('/Empleados');	
			}
			else 
			{
				die('Error!!!');
			}
		}	
	}
	//Borrar empleado de medio turno
	public function borrarMT($id)
	{
		$datos = [
			'id' => $id,
		];
		if ($this->EmpleadoModel->borrarMT($datos)) 
		{
			direccionar("/Empleados");
		}
		else
		{
			die("Error!!!!");
		}
	}
}	