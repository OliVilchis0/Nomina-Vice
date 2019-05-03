	<!-- Script pagina de inicio -->
	<script src="<?php echo RUTA_URL; ?>/js/classie.js"></script>
    <script src="<?php echo RUTA_URL; ?>/js/cbpAnimatedHeader.js"></script>
	<!-- Bootstrap Javascript CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<!-- JS Graficas -->
    <script src="<?php echo RUTA_URL; ?>/js/grafica.js"></script>
    <script src="<?php echo RUTA_URL; ?>/js/highcharts.js"></script>
    <script src="<?php echo RUTA_URL; ?>/js/modules/exporting.js"></script>	
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
    <!--CDN Data Tables-->
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <!--Scripts personales-->
    <script src="<?php echo RUTA_URL; ?>/js/main.js"></script>
</body>
</html>