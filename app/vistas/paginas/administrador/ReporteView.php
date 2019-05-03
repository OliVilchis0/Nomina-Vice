<?php require RUTA_APP.'/vistas/inc/header.php'; ?>
<!-- Our Custom CSS -->
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos_admin.css">
    <!--Estilos sola para vista empleados-->
    <link rel="stylesheet" href="<?php echo	RUTA_URL; ?>/css/estilos-empleado.css">
<!--Data Tables-->
	<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">

</head> 
<body>
	<!--********************************************************Navegación*********************************************-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
          <a class="img-principal navbar-brand" href="/nomina/Admin"><img id="logo-vice" src="<?php echo RUTA_URL; ?>/img/vice-o.png"></a>
            <button type="button" id="sidebarCollapse" class="boton-sidebar ">
                <i class="icon-sidebar fas fa-align-left"></i>
            </button>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="submenu collapse navbar-collapse p-3 mb-2 bg-white text-dark" id="navbarNavDropdown">
            <ul class="nav-n navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-user fas fa-bell"><?php ($datos['notificaciones']['status'] == true) ? print('<span class="badge">'.$datos['notificaciones']['numero'].'</span>') : '';?></i>
                </a>
                    <div class="seccion-login dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                             <?php ($datos['notificaciones']['status'] == true) ? print($datos['notificaciones']['boton']) : ''; ?>
                    </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="icon-user fas fa-user"></i>
                </a>
                <div class="seccion-login dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#"><i class="icon-tuto far fa-user"></i> Perfil</a>
                  <a class="dropdown-item" href="#"><i class="icon-tuto fas fa-cog"></i> Configuración</a>
                  <a class="dropdown-item" href="Admin/logout"><i class="icon-tuto fas fa-sign-out-alt"></i> Log Out</a>
                </div>
              </li>
            </ul>
          </div>
        </nav>
        <?php echo $datos['notificaciones']['modal']; ?>
    </header>
     <!--***************************************************************************************************************-->

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="p-3 mb-2 bg-dark text-white">
            <!--*****************************IMAGEN SIDEBAR**************************************-->
            <div class="tutorado">
                <div class="text-center mb-3">
                  <img src="<?php echo RUTA_URL; ?>/img/img-admin/admin.jpg"  class="img-admin rounded" >
                  <h3 class="tutorado-text"><i class="icon-user-n fas fa-user"></i> admin</h5>
                  <p class="tutorado-text-2">
                      <button class="boton-tutorado btn" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="icon-i fas fa-angle-double-down"></i>
                      </button>
                    </p>
                </div>
            </div>
            <div class="menu-img collapse " id="collapseExample">
                <div class="card card-body  bg-dark">
                    <ul class="lista-datos">
                        <li class="mb-1">
                            <a class="lista " href=""><i class="icon-tuto fas fa-user"></i> Perfil</a>
                        </li>
                        <li class="mb-1 p-1">
                            <a class="lista" href=""><i class="icon-tuto fas fa-cog"></i> Configuración</a>
                        </li>
                        <li>
                            <a class="lista" href="Admin/logout"><i class="icon-tuto fas fa-sign-out-alt"></i> Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--***********************************************SIDEBAR*************************************************-->
             <ul class="list-unstyled components mb-3">

                <li>
                    <a href="/nomina/Admin">Inicio</a>
                </li>
                <li class="active">
                    <a href="/nomina/Reportes">Reportes</a>
                </li>
                <li>
                    <a href="/nomina/Empleados">Empleados</a>
                </li>
                <li>
                    <a href="/nomina/Areas">Areas</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs mb-3">
                <li class="mb-2">
                    <!-- Boton para abrir ventana modal de archivo excel -->
                    <button type="button" class="btn btn-light btn-block" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-upload"></i>
                        Reporte excel
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title text-dark" id="exampleModalLabel">Subir Reporte</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form class="was-validated" method="POST" action="/nomina/Reportes/subir" enctype="multipart/form-data">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="validatedCustomFile1" name="archivoR" lang="es" accept=".xls,.xlsx" required>
                                <label class="custom-file-label" data-browse="Elegir" for="validatedCustomFile1">Archivo</label>
                                <div class="invalid-feedback">Elegir excel</div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Subir</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                </li>
            </ul>
        </nav> 
        <!--***********************************CONTENIDO PARTE DERECHA****************************************-->
        <div id="content">
            <!--***********************************Breadcrumb***********************************-->
            <div class="row mb-4">
                <div class="nav-derecha col-lg-12 col-12">
                    <div data-spy="scroll" data-target="#navbar-example3" data-offset="0">
                        <nav aria-label="breadcrumb">
                          <ol class="breadcrumb ">
                            <li class="breadcrumb-item"><a href="/nomina/Admin">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="/nomina/Reportes">Reportes</a></li>
                            <!--<li class="breadcrumb-item active" aria-current="page">Data</li>-->
                          </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="container">
              <?php if ($datos['status']): ?>
                <div class="row">
                  <div class="col-12 text-center bg-white">

                  </div>
                </div>
  			        <div class="p-3 mb-2 bg-white text-dark h-100">
                  <h5 class="text-right text-azul" ><?php echo 'Fecha '.$datos['fecha']; ?></h5>
                  <input type="hidden" id="fecha" value="<?php echo $datos['fecha']; ?>">
                  <table id="table_id" class="table table-sm table-striped table-borderless table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Asistencias</th>
                        <th>Retardos</th>
                        <th>Temprano</th>
                        <th>Extra</th>
                        <th>Sabado</th>
                        <th>Comision</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php echo $datos['tabla']; ?>
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="datos col-8 text-center p-3 mb-3 bg-transparent">
                    <button id="crear_excel" class="btn bg-azul mx-auto w-50">
                      <i class="fas fa-save text-white"> Guardar</i>
                    </button>
                    <a href="javascript:location.reload()" class="btn btn-secondary">
                      <i class="fas fa-sync-alt text-white"></i>
                    </a>
                  </div>
                  <div class="col-4 p-3 mb-3 bg-transparent text-right">
                    <h2 id="totalSueldo" class="text-azul"></h2>
                  </div>
                </div>
              <?php else: ?>
                <div class="text-center">
                  <i class="far fa-question-circle display-1"><br>No existen datos</i>
                </div>
              <?php endif ?>
            </div>      
<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>