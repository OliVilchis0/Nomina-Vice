<?php require RUTA_APP.'/vistas/inc/header.php'; ?>
<!-- Our Custom CSS -->
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos_admin.css">
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

                <li class="active">
                    <a href="/nomina/Admin">Inicio</a>
                </li>
                <li>
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
                                <input type="file" class="custom-file-input" id="validatedCustomFile1" name="archivoR" lang="es" accept=".xls,.xlsx"  required>
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
                            <!--<li class="breadcrumb-item"><a href="#">Library</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>-->
                          </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <!--******************************CAJAS DE DATOS*********************************-->
                <div class="row mb-3">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                       <div class="info" id="info-1">
                            <div class="row">
                                <div class="col col-lg-1 col-md-1 col-sm-1 col-2">
                                    <i class="icon-tuto-2 fas fa-pen-square"></i>
                                </div>
                                <div class="col col-10 col-sm-10 col-md-10 text-center">
                                    <h4 class="text-center">Reportes</h4>
                                    <i class="fas fa-file-excel display-4 mb-3"></i>
                                    <!-- Boton para abrir ventana modal de archivo excel -->
                                    <button type="button" class="btn btn-block barra-1 text-light" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fas fa-upload"></i>
                                        Reporte excel
                                    </button>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                        <div class="info" id="info-2">
                            <div class="row">
                                <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
                                    <i class="icon-tuto-3 fas fa-address-book"></i>
                                </div>
                                <div class="col col-10 col-sm-10 col-md-10 ">
                                    <h4 class="text-center">Empleados</h4>
                                    <p class="text-center  mb-2">
                                        Total
                                    </p>
                                    <h1 class="text-center mb-4">
                                        <?php echo $datos['total_epds']; ?>
                                    </h1>
                                    <!--Boton para modal de empleados-->
                                    <button class="barra-2 btn btn-block p-1 text-light p-1" type="button" data-toggle="modal" data-target="#modal_empleados" data-whatever="@mdo">
                                        <i class="fas fa-plus"></i>
                                        Agregar
                                    </button>
                                    <!--Modal empleados-->
                                    <div class="modal fade" id="modal_empleados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">empleados</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <form class="was-validated" method="POST" action="Admin/Agregar" enctype="multipart/form-data">
                                              <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="validatedCustomFile1" name="archivo" lang="es" accept=".xls,.xlsx"  required>
                                               <label class="custom-file-label" data-browse="Elegir" for="validatedCustomFile1">Archivo</label>
                                                <div class="invalid-feedback">Elige el archivo excel</div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button  type="submit" class="btn btn-primary">Subir</button>
                                              </div>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <?php echo $datos['modal']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                        <div class="info" id="info-3">
                            <div class="row">
                                <div class="col col-lg-1 col-md-1 col-sm-1 col-xs-2">
                                    <i class="icon-tuto-4 fas fa-address-book"></i>
                                </div>
                                <div class="col col-10 col-sm-10 col-md-10 ">
                                    <h4 class="text-center">Areas</h4>
                                    <p class="text-center  mb-2">
                                        Total
                                    </p>
                                    <h1 class="text-center mb-4">
                                        <?php echo $datos['total_ars']; ?>
                                    </h1>
                                    <!--Boton para modal de areas-->
                                    <button class="barra-3 btn btn-block p-1 text-light" type="button" data-toggle="modal" data-target="#modal_areas" data-whatever="@mdo">
                                        <i class="far fa-eye"></i>
                                        Ver
                                    </button>
                                    <!--Modal areas-->
                                    <div class="modal fade" id="modal_areas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Areas</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <table class="table">
                                              <thead class="thead-dark">
                                                <tr>
                                                  <th scope="col">Area</th>
                                                  <th scope="col">Encargado</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php echo $datos['JDepartamento']; ?>
                                              </tbody>
                                            </table>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <a type="button" class="btn btn-primary" href="/nomina/empleados">ver mas</a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <!--<div class="barra progress">
                                        <div class="barra-3 progress-bar" role="progressbar" style="width: 90%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 ">
                        <div class="info" id="info-4">
                            <div class="row">
                                <div class="col col-lg-1 col-md-1 col-sm-1 col-2">
                                    <i class="icon-tuto-5 far fa-clock"></i>
                                </div>
                                <div class="col col-10 col-sm-10 col-md-10 ">
                                  <h4 class="texto-caja text-center">Horario</h4>
                                  <p class="text-left">
                                    <?php  echo 'Entrada: 9:00 hrs'; ?>
                                  </p>
                                  <p>
                                    <?php echo " Salida: 19:00 hrs"; ?>
                                  </p>
                                  <p>
                                    <?php echo 'Horas a la semana 54' ?>
                                  </p>
                                  <p>
                                    <?php echo "Comida 14:00 hrs" ?>
                                  </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--*******************************************GRAFICA************************************-->
                <div class="row">
                    <div class="col-lg-6 col-12 bg-white text-center">
                      <img class="img-fluid h-100 w-75 p-5" alt="Responsive image" src="<?php echo RUTA_URL; ?>/img/vice-o.png">
                    </div>
                    <div class="col-lg-6 col-12">
                        <div>
                            <div id="container" style="min-width: 280px; height: 290px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
      $(function () {
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Empleados por area'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                  <?php 
                    foreach ($datos['grafica'] as $key) {
                      ?>
                      ['<?php echo $key->departamento ?>',<?php echo $key->suma ?>],
                      <?php 
                    }
                   ?>
                ]
            }]
        });
    });
    </script>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>