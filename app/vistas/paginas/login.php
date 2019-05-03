<?php require RUTA_APP.'/vistas/inc/header.php'; ?>
<body class="FLogin">
	<div class="container">
  		<div class="ELogin row">
  			<div class="imagen col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 ">
  				<img class="img-login-0 img-responsive" src="<?php echo RUTA_URL; ?>/img/img-login/login.png">
  				<img src="<?php echo RUTA_URL; ?>/img/img-login/login-c.jpg" class="img-login img-fluid" alt="Responsive image">
  			</div>
  			<div class="datos col-xl-8 col-lg-6 col-md-6 col-sm-6 col-12">
  				<img class="img-login-1 img-responsive" src="<?php echo RUTA_URL; ?>/img/img-login/login.png">
  				<h2 class="text-center text-primary"><?php echo $datos['titulo']; ?></h2>
  				<form method="POST" action="<?php echo $datos['dir']; ?>">
				  <div class="form-group">

				    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nombre de usuario" name="user" >
				  </div>
				  <div class="form-group">

				    <input type="password" class="form-control " id="exampleInputPassword1" placeholder="Contraseña" name="pass" >
				    <span class="text-danger" ><?php !empty($datos['error']) ? print($datos['error']) : ''; ?></span>
				  </div>
				  <button type="submit" class="boton btn btn-primary btn-block">Entrar</button>
				  <small id="emailHelp" class="form-text text-muted"><a 
				    class="text-success" href="#">Olvidaste tu contraseña?</a></small>
				</form>
  			</div>
  		</div>
	</div>
<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>