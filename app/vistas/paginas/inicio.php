<?php require RUTA_APP.'/vistas/inc/header.php'; ?>
</head>
<body>

<!-- Navegación -->
    <nav class="navbar-default navbar navbar-expand-lg navbar-light  fixed-top">
        <div class="container">
            <a class="navbar-brand page-scroll" href="#"><img id="logo-vice" src="<?php echo RUTA_URL; ?>/img/vice-o.png"></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="navegacion collapse navbar-collapse" id="navbarSupportedContent">
           <ul class="nav justify-content-end">
              <li class="nav-item">
                <a class="nav-link active" href="#services">Lugares</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#about">Acerca</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#contact">Contacto</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

   
    
    <!-- Cabecera -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in">Bienvenido a la empresa VICE</div>
                <div class="intro-heading">Tenancingo</div>
                <a href="#about" class="page-scroll btn btn-success btn-xl">Saver más</a>
            </div>
        </div>
    </header>
    
    <!-- Sección servicios -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Lugares</h2>
                    <h3 class="section-subheading text-muted">Navega por cada seccion.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                    	<a href="Login">
	                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
	                        <i class="fas fa-money-check-alt fa-stack-1x fa-inverse"></i>
                        </a>
                    </span>
                    <h4 class="service-heading"><a class="text-dark" href="Login">Nomina</a></h4>
                    <p class="text-muted">Documento contable que registra los salarios, prestaciones y adeudos de los empledos de la empresa Vice, representa el cumplimiento del patron hacia los empleados.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                    	<a href="Login_I">
                    		<i class="fa fa-circle fa-stack-2x text-primary"></i>
                       		 <i class="fas fa-warehouse fa-stack-1x fa-inverse"></i>
                    	</a>
                    </span>
                    <h4 class="service-heading"><a class="text-dark" href="Login_I">Inventario</a></h4>
                    <p class="text-muted">Relacion detallada, ordenada y valorada de los elementos que componen el patrimonio de la empresa Vice en un momento determinado.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                    	<a href="">
                    		<i class="fa fa-circle fa-stack-2x text-primary"></i>
                        	<i class="fas fa-cut fa-stack-1x fa-inverse"></i>
                    	</a>
                    </span>
                    <h4 class="service-heading"><a class="text-dark" href="">Corte</a></h4>
                    <p class="text-muted">Registro del total de cortes resultantes de un rollo de tela al final de un dia laboral.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Sección acerca de mi -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                   <h2 class="section-heading">Acerca de VICE</h2>
                    <h3 class="section-subheading text-muted">Empresa de corte y confeccion de playeras.</h3>
                </div>
            </div>
            <div class="row">

                <div class="team-member">
                        <img src="<?php echo RUTA_URL; ?>/img/vice-o.png" class="img-responsive img-circle" alt="">
                        <h4>Eugenio Vilchis Castro</h4>
                        <p class="text-muted">Propietario</p>
                        
                </div>

                <div class="col-lg-4 col-lg-offset-2">
                    <p>
                        Somos una empresa dedicada, a la elaboracion de playeras, 
			            producción y comercialización. Es compromiso nuestro fomentar el desarrollo continuo para
			            innovar en nuestros productos y servicios. 
                    </p>
                </div>
                <div class="col-lg-4">
                    <p>Es así como, aprovechando nuestra
			            experiencia, hemos ampliado nuestro portafolio de productos para ofrecer al mercado mas 
			            y mejores resultado, nuestras prioridades son calidad, creatividad, e innovación, lo que se 
			            traduce en soluciones para cada cliente..</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Sección logos de clientes -->
    <aside class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo RUTA_URL; ?>/img/logos/envato.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo RUTA_URL; ?>/img/logos/designmodo.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo RUTA_URL; ?>/img/logos/themeforest.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="<?php echo RUTA_URL; ?>/img/logos/creative-market.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contacto</h2>
                    <h3 class="section-subheading text-muted">Empresa VICE, San Martin Coapaxtongo,Tenancingo, EdoMex.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Tu nombre *" id="name" required>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Tu Email *" id="email" required>
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Tu Teléfono *" id="phone" required>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Tu Mensaje *" id="message" required></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Enviar Mensaje</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--Pie de página-->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; VICE 2019 - Derechos reservados.</span>
                </div>
                <div class="col-md-4">
                    <ul class="nav justify-content-center social-buttons">
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fab fa-twitter-square"></i></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fab fa-facebook-square"></i></i></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="fab fa-youtube"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="nav quicklinks">
                        <li class="nav-item"><a class="nav-link" href="#">Politicas de privacidad</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">Terminos de uso</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
