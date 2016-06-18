<?php 
/**
 * Vista administracionFactorRiesgo, es la encargada de mostrar las acciones administrativas para los tipos de herida
 *
 * @package aplication/views/admin/factorriesgo
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Administración - Actividad"; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php if(isset($pagination) && $pagination != ""): ?>
			    <?php echo $pagination; ?>    
			    <?php if(isset($table)): ?>
		        	<div class="data table-responsive">
		            	<?php echo $table; ?>
		        	</div>
			    <?php endif; ?>
	    		<?php echo $pagination;?>
	    	<?php else: ?>
				<p style="color: red;">No se encontraron registros.</p>
	    	<?php endif; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Tipos de herida relacionados con la actividad seleccionada</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_tipos_de_herida">
			<p>Debe seleccionar una actividad para ver los tipos de herida asociados.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Factores de riesgo relacionados con la actividad seleccionada</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_factores_de_riesgo">
			<p>Debe seleccionar una actividad para ver los factores de riesgo asociados.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1>Acciones sobre las actividades</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-1em">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="<?php echo base_url('Administrador/formulario-adicionar-actividad'); ?>" id="adicionar" title="Adicionar una nueva actividad">Adicionar una nueva actividad</a>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="" id="editar" title="Editar actividad seleccionada">Editar actividad seleccionada</a>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="" id="eliminar" title="Eliminar la actividad seleccionada">Eliminar la actividad seleccionada</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(".seleccion").change(function(e){
		var id = $(this).attr('id');
		cargar_tipos_de_herida(id);
		cargar_factores_de_riesgo(id);
	});
	var peticion_tipos_de_herida = null;
	function cargar_tipos_de_herida(id) {
		if(peticion_tipos_de_herida != null)
			peticion_tipos_de_herida.abort();
		peticion_tipos_de_herida = $.ajax({
			url : "<?php echo base_url('Administrador/obtener-tipos-de-herida-dada-actividad') ?>",
			data: {
				'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
				'id' : id
			},
			dataType: "HTML",
			type: "GET",
			success: function(data){
				$("#contendor_tipos_de_herida").html(data);
			},
			error: function(xhr, status){
				console.log(status);
			}
		});
	}
	var peticion_factores_de_riesgo = null;
	function cargar_factores_de_riesgo(id) {
		if(peticion_factores_de_riesgo != null)
			peticion_factores_de_riesgo.abort();
		peticion_factores_de_riesgo = $.ajax({
			url : "<?php echo base_url('Administrador/obtener-factores-de-riesgo-dada-actividad') ?>",
			data: {
				'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
				'id' : id
			},
			dataType: "HTML",
			type: "GET",
			success: function(data){
				$("#contendor_factores_de_riesgo").html(data);
			},
			error: function(xhr, status){
				console.log(status);
			}
		});
	}

	$("#eliminar").click(function(e) {
		e.preventDefault();
		var seleccion = $('input:radio[name=seleccionar]:checked').val();
		if(typeof seleccion == "undefined"){
			swal({
				title: "Oops... ¡Ha ocurrido un error!",
				text: "Debe seleccionar una actividad para poderla eliminar.",
				type: "error"
			});
		}else{
			swal({
			  	title: '¿Eliminar actividad?',
			  	text: "No podrás deshacer esta acción, ¿Desea continuar?",
			  	type: 'warning',
			  	showCancelButton: true,
			  	confirmButtonColor: '#3085d6',
			  	cancelButtonColor: '#d33',
			  	confirmButtonText: 'Si, eliminar',
			  	cancelButtonText: 'No, cancelar',
			  	confirmButtonClass: 'margin-bottom-2px',
			  	cancelButtonClass: 'margin-bottom-2px',
			}).then(function(isConfirm) {
			  	if (isConfirm) {
			  		$.ajax({
			  			url: '<?php echo base_url('Administrador/eliminar-actividad'); ?>',
			  			data: {
							'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
			  				'seleccion': seleccion
			  			},
			  			type: 'POST',
			  			dataType: 'JSON',
			  			success: function(data){
			  				if(data.state == "success"){
			  					swal({
						    		title: data.title,
						      		text: data.message,
						      		type: 'success'
						    	}).then(function(isConfirm) {
						    		location.reload();
						    	});
			  				}else if(data.state == "error"){
			  					swal({
									title: "Oops... ¡Ha ocurrido un error!",
									text: data.message,
									type: "error"
								}).then(function(isConfirm) {
						    		//location.reload();
						    	});
			  				}
			  			},
			  			error: function(xhr, status){
			  				swal({
								title: "Oops... ¡Ha ocurrido un error!",
								text: "Ha sucedido un error inesperado, compruebe los datos e inténtelo de nuevo.",
								type: "error"
							}).then(function(isConfirm) {
						    		location.reload();
						    });
			  			}
			  		});
			  	}
			});
		}
	});

	$("#editar").click(function(e){
		e.preventDefault();
		var seleccion = $('input:radio[name=seleccionar]:checked').val();
		if(typeof seleccion == "undefined"){
			swal({
				title: "Oops... ¡Ha ocurrido un error!",
				text: "Debe seleccionar una actividad para poderla editar.",
				type: "error"
			});
		}else{
			window.location = "<?php echo base_url('Administrador/formulario-edicion-de-actividad');?>/"+seleccion;
		}
	});
</script>