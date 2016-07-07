<?php 
/**
 * Vista historialPaciente, es la encargada de mostrar el historial de un paciente, entiendase por historial las situaciones de enfermería que tenga asociadas.
 *
 * @package aplication/views/paciente
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Historial del paciente"; ?></h1>
		</div>
	</div>
	<?php if(isset($paciente)): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
				<?php $this->load->view('paciente/tablaInfoPaciente', $paciente); ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Situaciones de enfermería</h3>
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
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Localización anatómica de la herida</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_localizacion">
			<p>Debe seleccionar una situacion de enfermería para ver la localizazión anatómica de la herida asociada.</p>
		</div>
		<div class='text-center'>
			<img class="cargando_localizacion" style="display: none;" src="<?php echo asset_url('img/grieeqcargando.gif'); ?>" alt="Cargando..."/>
			<img class="cargando_localizacion" style="display: none;" src="<?php echo asset_url('img/cargando.gif'); ?>" alt="Cargando..."/>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Tipo de herida</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_tipo_de_herida">
			<p>Debe seleccionar una situacion de enfermería para ver el tipo de herida asociado.</p>
		</div>
		<div class='text-center'>
			<img class="cargando_tipo_de_herida" style="display: none;" src="<?php echo asset_url('img/grieeqcargando.gif'); ?>" alt="Cargando..."/>
			<img class="cargando_tipo_de_herida" style="display: none;" src="<?php echo asset_url('img/cargando.gif'); ?>" alt="Cargando..."/>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Factores de riesgo</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_factores_de_riesgo">
			<p>Debe seleccionar una situacion de enfermería para ver los factores de riesgo asociados.</p>
		</div>
		<div class='text-center'>
			<img class="cargando_factores_de_riesgo" style="display: none;" src="<?php echo asset_url('img/grieeqcargando.gif'); ?>" alt="Cargando..."/>
			<img class="cargando_factores_de_riesgo" style="display: none;" src="<?php echo asset_url('img/cargando.gif'); ?>" alt="Cargando..."/>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Actividades sugeridas</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_actividades">
			<p>Debe seleccionar una situacion de enfermería para las actividades sugeridas asociadas.</p>
		</div>
		<div class='text-center'>
			<img class="cargando_actividades" style="display: none;" src="<?php echo asset_url('img/grieeqcargando.gif'); ?>" alt="Cargando..."/>
			<img class="cargando_actividades" style="display: none;" src="<?php echo asset_url('img/cargando.gif'); ?>" alt="Cargando..."/>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h3>Exportar historial completo</h3>
		</div>
	</div>
	<?php if(isset($paciente)): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="contendor_actividades">
				<p>En esta sección usted podrá exportar el historial completo del paciente a un archivo en formato .docx (Word), si el historial del paciente contiene mucha información es posible que la aplicación se demore un poco en procesarla antes de presentar la ventana de descarga, por favor tenga paciencia.</p>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="<?php echo base_url($url_exportarhistorial); ?>/<?php echo $paciente->idPaciente; ?>" id="adicionar" title="Exportar historial completo">Exportar historial completo</a>
			</div>
		</div>
	<?php endif; ?>
</div>
<script type="text/javascript">
	$(".seleccion").change(function(e){
		$("#contendor_localizacion").html("");
		$(".cargando_localizacion").show();
		$("#contendor_tipo_de_herida").html("");
		$(".cargando_tipo_de_herida").show();
		$("#contendor_factores_de_riesgo").html("");
		$(".cargando_factores_de_riesgo").show();
		$("#contendor_actividades").html("");
		$(".cargando_actividades").show();
		var id = $(this).attr('id');
		cargar_informacion(id);
	});
	var peticion_informacion = null;
	function cargar_informacion(id) {
		if(peticion_informacion != null)
			peticion_informacion.abort();
		peticion_informacion = $.ajax({
			url : "<?php echo base_url('SituacionEnfermeria/obtener-informacion') ?>",
			data: {
				'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
				'id' : id
			},
			dataType: "JSON",
			type: "GET",
			success: function(data){
				$("#contendor_localizacion").html(data.localizacion);
				$(".cargando_localizacion").hide();
				$("#contendor_tipo_de_herida").html(data.tipo_herida);
				$(".cargando_tipo_de_herida").hide();
				$("#contendor_factores_de_riesgo").html(data.factor_riesgo);
				$(".cargando_factores_de_riesgo").hide();
				$("#contendor_actividades").html(data.actividad);
				$(".cargando_actividades").hide();
			},
			error: function(xhr, status){
				console.log(status);
			}
		});
	}
</script>