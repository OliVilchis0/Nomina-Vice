//funcion para validar input file de empleados
function VEmpleado()
{
	var file = document.getElementById('File1');
	var filePath = file.value;
	var extenciones = /(.xls|.xlsx)$/i;
	if (!extenciones.exec(filePath)) {
		alert('Ingresa solo archivos excel');
		file.value = " ";
		return false;
	}
}
/*function submitEmpleado()
{
	var file = document.getElementById('File1');
	if (file.length == 0) {
		alert('Error');
		return false;
	}
}*/
//Abrir modal largo de Administrador
$(".bd-example-modal-lg").modal("show");
//Iniciar plugin de data table
$(document).ready( function () {
    $('#table_id').DataTable({
    	paging: false,
    	language:{
    		"sProcessing":     "Procesando...",
		    "sLengthMenu":     "Mostrar _MENU_ registros",
		    "sZeroRecords":    "No se encontraron resultados",
		    "sEmptyTable":     "Ningún dato disponible en esta tabla",
		    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		    "sInfoPostFix":    "",
		    "sSearch":         "Buscar:",
		    "sUrl":            "",
		    "sInfoThousands":  ",",
		    "sLoadingRecords": "Cargando...",
		    "oPaginate": {
		        "sFirst":    "Primero",
		        "sLast":     "Último",
		        "sNext":     "Siguiente",
		        "sPrevious": "Anterior"
		    },
		    "oAria": {
		        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
		    }
    	}
    });
} );
// Validar formulario de areas
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
//validar choose file 
$('#validatedCustomFile1').on('change',function(){
    //get the file name
    var fileName = $(this).val().split('\\').pop();
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
    console.log(fileName);
});

//Operacion con la funcion val() para establecer el total de pago
$(document).on('ready',constructor);
function constructor()
{
	sumar();
	total();
	totalS();
}
function totalS()
{
	var total = 0;
	$('.total').each(function(){
		var numero = parseFloat($(this).val());
		total += numero;
	});
	$('#totalSueldo').text('Total:   $ '+total.toFixed(2));
}
function sumar()
{
	$('.monto').on('change',function(){
		var id = $(this).attr('id');
		num = id.substring(3);
		var numero = parseFloat($(this).val());
		var sueldo = parseFloat($('#sueldo'+num).val());
		if (isNaN(numero)) {
			numero=0;
		}
		var total = sueldo + numero;
		$('#resultado'+num).val(total.toFixed(2));
	});
}
//parametros para generar tabla excel
function total()
{
	$('#crear_excel').click(function(){
		var fecha = $('#fecha').val();
		var datos = [ $("input[name='id\\[\\]'").map(function(){return $(this).val();}).get() ];
		datos.push($("input[name='total\\[\\]'").map(function(){return $(this).val();}).get() );
		datos.push(fecha);
		$('.datos').load("/nomina/Reportes/descargar",{datos:datos});


		var total = 0;
	$('.total').each(function(){
		var numero = parseFloat($(this).val());
		total += numero;
	});
	$('#totalSueldo').text('Total:   $ '+total.toFixed(2));
	});	
}
