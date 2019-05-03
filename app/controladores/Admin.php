<?php 
/**
 * Clase controlador admin
 */
class Admin extends Controlador
{
	//$2y$12$6X5bEyxxH2vmwl/TwLz0BO5xJUUYagZOHrKCtRbDi03xrK6ZPwhWq
	public function __construct()
	{
		//Clase notificaciones 
		$this->notify = new Notificaciones;
		//Clase crud de empleados
		$this->complemento = new Complemento;
		$this->Admin_Model = $this->modelo('Administrador');
		$this->session = new Session();
		$this->session->init();
		if ($this->session->getStatus() === 1 || empty($this->session->get('user'))) 
			exit('Acceso denegado');
	}
	public function index()
 	{
 		$datos = [
 			'user' => $this->session->getAll(),
 			'total_ars' => $this->Admin_Model->coutnAreas(),
 			'total_epds' => $this->Admin_Model->countEpds(),
 			'JDepartamento' => $this->JDepartamento(),
 			'modal' => '',
 			'notificaciones' => $this->notificacion(),
 			'grafica' => $this->Admin_Model->grafica(),
 		];
 		$this->vista('paginas/administrador/admin',$datos);
 	}
 	//Cerrar session
 	public function logout()
	{
		$this->session->close();
		direccionar('/');

	}
	//metodo para agregar,editar o eliminar registros de acuerdo a la hoja excel
	public function Agregar()
	{
		//metodo de la clase complemento
		$resultado = $this->complemento->CrudEmpleado('/nomina/Admin');
		//datos para recargar vista
		$datos = [
 			'user' => $this->session->getAll(),
 			'total_ars' => $this->Admin_Model->coutnAreas(),
 			'total_epds' => $this->Admin_Model->countEpds(),
 			'JDepartamento' => $this->JDepartamento(),
 			'modal' => $resultado,
 			'notificaciones' =>  $this->notificacion(),
 			'grafica' => $this->Admin_Model->grafica(),
 		];
 		//Recargar vista
		$this->vista('paginas/administrador/admin',$datos);
	}
	//Retornar una lista de los fejes de dapartamento
	public function JDepartamento()
	{
		$resultado = '';
		//Obtener los departamentos y sus jefes
		$jefe = $this->Admin_Model->JDepartamento();
		//Obtener Departamentos
		$depa = $this->Admin_Model->getDepartamento();
		if (sizeof($jefe) != 0) {
			foreach ($jefe as $row) {
			$resultado .= "<tr>
						<td>".$row->area."</td>
						<td>".$row->Jefe."</td>
						</tr>";
			}	
		}else{
			foreach ($depa as $row) {
				$resultado .= "<tr><td>".$row->nombre."</td><td>Actulamente no tiene jefe</td></tr>";
			}
		}
		return $resultado;
	}
	//notificaciones
	public function notificacion()
	{
		$resultado = $this->notify->JD();
		return $resultado;
	}
}