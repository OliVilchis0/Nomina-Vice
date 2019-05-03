<?php 
/**
 * Clase para las notificaciones ubicadas en el header
 */
class Notificaciones
{
	function __construct()
	{
		$this->notifyModel = $this->modelo('Notificacion');
	}
	//importar el modelo
	public function modelo($modelo)
	{
		//Carga
		require_once '../app/modelos/'.$modelo.'.php';
		//Instancia el modelo
		return new $modelo();
	}
	//Saber si existen jefes de departamento
	public function JD()
	{	
		//Variable para notificaciones
		$status = false;
		//Variable para card
		$boton = '';
		$modal = '';
		//Variable para numero de notificaciones
		$num = 0;
		//Obtener los registros de jefes de departamento
		$jd = $this->notifyModel->getJD();
		//Obtener los registro de departamento
		$depa = $this->notifyModel->getDepa();
		foreach ($depa as $row) {
			if ($this->notifyModel->getjefeD((int)$row->id) == null) {
				$status = true;
				$boton .= '<button class="departamento dropdown-item" data-toggle="modal" data-target="#depa'.$row->id.'">
						'.$row->nombre.' no tiene jefe</button>';
				$modal .= '<!-- Modal -->
				        <div class="modal fade" id="depa'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="depa" aria-hidden="true">
				          <div class="modal-dialog" role="document">
				            <div class="modal-content">
				              <div class="modal-header">
				                <h4 class="modal-title text-left text-dark" id="exampleModalLabel">Seleccionar Jefe </h4>
				                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				                  <span aria-hidden="true">&times;</span>
				                </button>
				              </div>
				              <div class="modal-body">
				                <form method="POST" action="/nomina/Empleados/insertarJD">
				           		   <div class="form-group row">
										<input type="hidden" id="custId" name="departmen" value="'.$row->id.'">
									    <label for="inputEmail3" class="col-sm-5 col-form-label text-dark">Jefe para '.$row->nombre.'</label>
									    <div class="col-sm-3">
											<select class="form-control form-control-sm" name="empleado">
												'.$this->allEmpleados($row->id).'			  
											</select>
									    </div>
									    <div class="col-sm-4">
									    	<input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Sueldo extra" name="sueldo">
									    </div>
									</div>    
					              <div class="modal-footer">
					                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					                <button type="Submit" class="btn btn-primary">Guardar</button>
					              </div>
				                </form>
				              </div>
				            </div>
				          </div>
				        </div>';
				$num ++;
			}
		}
		$resultado = $this->notifyDepa($status,$num,$boton,$modal);
		
		return $resultado;
	}
	//Listar todos los empleados 
	public function allEmpleados($id)
	{
		//variable de retorno
		$resultado = '';
		//Obtener datos de los empleados
		$empleado = $this->notifyModel->getEmpleadosS();
		//Obtener los departmanetosn
		$depa = $this->notifyModel->getDepa();
		foreach ($empleado as $row) {
			if ($row->departamento == $id) {
				$resultado .= '<option value='.$row->id.'>'.$row->nombre.'</option>'; 
			}elseif($id == ''){
				$resultado .= '<option value='.$row->id.'>'.$row->nombre.'</option>';
			}
		}
		return $resultado;
	}
	//saber si los departamentos tienen sueldo y descuento
	public function notifyDepa($status,$num,$boton,$modal)
	{
		//obtener todos los departamentos
		$depa = $this->notifyModel->getDepa();
		foreach ($depa as $row) {
			/*if ($row->sueldo == 0) {
				$status = true;
				$boton .= '<button class="departamento dropdown-item" data-toggle="modal" data-target="#modal'.$row->id.'">
						'.$row->nombre.' no tiene sueldo</button>';
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
							    <div class="col-md-4 mb-3">
							      <label for="validationCustom01">Sueldo</label>
							      <input type="text" class="form-control" id="validationCustom01" placeholder="Sueldo" name="sueldo" value="'.$row->sueldo.'" required>
							      <div class="invalid-feedback">
							        Ingresa el sueldo
							      </div>
							    </div>
							    <div class="col-md-4 mb-3">
							      <label for="validationCustom02">Descuento</label>
							      <input type="text" class="form-control" id="validationCustom02" placeholder="Descuento" name="descuento" value="'.$row->descuento_sueldo.'" required>
							      <div class="invalid-feedback">
							        Ingresa el descuento
							      </div>
							    </div>
							    <div class="col-md-4 mb-3">
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
				$num++;
			}*/
			if ($row->descuento_sueldo == 0) {
				$status = true;
				$boton .= '<button class="departamento dropdown-item" data-toggle="modal" data-target="#modal'.$row->id.'">
						'.$row->nombre.' no tiene descuento</button>';
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
							      <label for="validationCustom01">Descuento</label>
							      <input type="text" class="form-control" id="validationCustom01" placeholder="Descuento" name="descuento" value="'.$row->descuento_sueldo.'" required>
							      <div class="invalid-feedback">
							        Ingresa el descuento
							      </div>
							    </div>
							    <div class="col-md-6 mb-3">
							      <label for="validationCustom02">Extra</label>
							      <input type="text" class="form-control" id="validationCustom02" placeholder="Extra" name="extra" value="'.$row->extra.'" required>
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
				$num++;
			}
		}
		//Array de datos
		$resultado = [
			'status' => $status,
 			'numero' => $num,
 			'boton' => $boton,
 			'modal' => $modal,
		];
		return $resultado;
	}
}