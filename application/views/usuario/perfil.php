<?php 
/**
 * Vista perfil, es la encargada de mostrar el perfil cuando un usuario ingresa al sistema.
 *
 * @package aplication/views/usuario
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Perfil"; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <?php if(isset($pagination, $table)): ?>
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
	<?php if(isset($rol,$url_adicionarpaciente)): ?>
		<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
					<h1>Acciones sobre los pacientes</h1>
				</div>
			</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_adicionarpaciente; ?>" title="Adicionar paciente">Adicionar paciente</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" id="editar" title="Editar paciente seleccionado">Editar paciente seleccionado</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" id="adicionarsituacionenfermeria" title="Adicionar situación de enfermería a paciente seleccionado">Adicionar situación de enfermería a paciente seleccionado</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" id="eliminar" title="Dar de alta a paciente seleccionado (Eliminar)">Dar de alta a paciente seleccionado (Eliminar)</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" id="historial" title="Ver historial del paciente seleccionado">Ver historial del paciente seleccionado</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if(isset($rol,$url_gestiontiposherida,$url_gestionfactoresriesgo,$url_gestionactividades, $url_gestionusuarios) && $rol == "admin"): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
				<h1>Acciones Administrativas</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block " href="<?php echo base_url().$url_gestiontiposherida; ?>" title="Gestionar información de los tipos de herida">Gestionar información de los tipos de herida</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionfactoresriesgo; ?>" title="Gestionar información de los factores de riesgo">Gestionar información de los factores de riesgo</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionactividades; ?>" title="Gestionar información de las actividades">Gestionar información de las actividades</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionusuarios; ?>" title="Gestionar los usuarios">Gestionar los usuarios</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
<script type="text/javascript">
	$("#eliminar").click(function(e) {
		e.preventDefault();
		var seleccion = $('input:radio[name=seleccionar]:checked').val();
		if(typeof seleccion == "undefined"){
			swal({
				title: "Oops... ¡Ha ocurrido un error!",
				text: "Debe seleccionar un paciente para poderlo eliminar.",
				type: "error"
			});
		}else{
			swal({
			  	title: '¿Eliminar paciente?',
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
			  			url: '<?php echo base_url('Usuario/eliminar-paciente'); ?>',
			  			data: {
							'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
			  				'seleccion': seleccion
			  			},
			  			type: 'POST',
			  			dataType: 'JSON',
			  			success: function(data){
			  				if(data.state == "success"){
			  					swal({
						    		title: '¡Paciente eliminado con éxito!',
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
						    		location.reload();
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
				text: "Debe seleccionar un paciente para poderlo editar.",
				type: "error"
			});
		}else{
			window.location = "<?php echo base_url('Usuario/formulario-edicion-de-paciente');?>/"+seleccion;
		}
	});

	$("#adicionarsituacionenfermeria").click(function(e){
		e.preventDefault();
		var seleccion = $('input:radio[name=seleccionar]:checked').val();
		if(typeof seleccion == "undefined"){
			swal({
				title: "Oops... ¡Ha ocurrido un error!",
				text: "Debe seleccionar un paciente para poder adicionar una situación de enfermería",
				type: "error"
			});
		}else{
			window.location = "<?php echo base_url().$url_adicionarsituacionenfermeria; ?>/"+seleccion;
		}
	});

</script>