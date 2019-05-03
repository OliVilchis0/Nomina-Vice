<?php 
/**
 * 
 */
class Areas extends Controlador
{
	
	function __construct()
	{
		$this->notify = new Notificaciones;
		$this->AreaModel = $this->modelo('Area');
		$this->session = new Session();
		$this->session->init();
		if ($this->session->getStatus() === 1 || empty($this->session->get('user'))) 
			exit('Acceso denegado');
	}
	//Funcion Principal
	public function index()
	{
		$datos = [
 			'areas' => $this->getAreas(),
 			'notificaciones' => $this->notificacion(),
 		];
		$this->vista('paginas/administrador/AreaView',$datos);
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
	//Todas las areas
	public function getAreas()
	{
		//Obtener registros de departamento
		$areas = $this->AreaModel->areas();
		//Variable para listar departamento en formato de tabla
		$boton = '';
		$modal = '';
		foreach ($areas as $row) {
			$boton .= '<tr><th>'.$row->id.'</th><th>'.$row->nombre.'</th><th>'.$row->descuento_sueldo.'</th><th>'.$row->extra.'</th><th><button class="btn btn-warning btn-sm btn-block" data-toggle="modal" data-target="#modal'.$row->id.'"><i class="fas fa-edit text-white"></i></button></th></tr>';
			$modal .= '<!-- Modal -->
					<div class="modal fade" id="modal'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">'.$row->nombre.'</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        <form class="needs-validation" novalidate method="POST" action="/nomina/Areas/editar/'.$row->id.'">
							  <div class="form-row">
							    <div class="col-md-6 mb-3">
							      <label for="validationCustom02">Descuento</label>
							      <input type="text" class="form-control" id="validationCustom02" placeholder="Descuento" name="descuento" value="'.$row->descuento_sueldo.'" required>
							      <div class="invalid-feedback">
							        Ingresa el descuento
							      </div>
							    </div>
							    <div class="col-md-6 mb-3">
							      <label for="validationCustom03">Extra</label>
							      <input type="text" class="form-control" id="validationCustom03" placeholder="Extra" name="extra" value="'.$row->extra.'" required>
							      <div class="invalid-feedback">
							        Ingresa sueldo extra
							      </div>
							    </div>
							  </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						        <button type="submit" class="btn btn-primary">Guardar</button>
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
	//Editar un registro
	public function editar($id)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') 
		{
			//Datos para empleado
			$datos = [
				'id' => (int)$id,
				'descuento' => trim((double)$_POST['descuento']),
				'extra' => trim((double)$_POST['extra']),
			];
			if ($this->AreaModel->editar($datos)) {
				direccionar('/Areas');	
			}
			else 
			{
				die('Error!!!');
			}
		}
	}
}