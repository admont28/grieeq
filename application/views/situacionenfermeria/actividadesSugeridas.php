<?php 
/**
 * Vista actividadesSugeridas, es la encargada de mostrar las actividades a realizar de forma ordenada, con
 * responsive y si se incluyen actividades relacionadas con los factores de riesgo se separa de las
 * demás actividades.
 *
 * @package aplication/views/situacionenfermeria
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-center">
			<h1>4. Actividades a realizar</h1>
		</div>
		<?php if (isset($paciente)): ?>
			<div class="row">
				<?php $this->load->view('paciente/tablaInfoPaciente', $paciente); ?>
			</div>
		<?php endif ?>
		<?php if(isset($actividades_finales)): ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>A continuación se presentan las actividades sugeridas para el tratamiento de la herida, estas actividades son acorde al tipo de herida y los factores de riesgo seleccionados. Si no seleccionó algún factor de riesgo, solo se mostrarán las actividades relacionadas con el tipo de herida escogido.</p>
			</div>
		<?php else: ?>
			<p class="text-danger">Por el momento no existen actividades asociadas, por favor inténtalo más tarde.</p>
		<?php endif; ?>
	</div>
	<?php 
		if(isset($actividades_finales, $url_reinicio) && is_array($actividades_finales) && sizeof($actividades_finales) > 0): ?>
			<?php $cont = 2; ?>
			<?php $size = sizeof($actividades_finales); ?>
			<?php $impreso = false; ?>
			<?php foreach($actividades_finales as $row): ?>
				<?php if($cont%2==0): ?>
					<div class="row margin-bottom-2em">
				<?php endif; // Endif cont ?>
				<?php if(isset($row->FactorRiesgo_idFactorRiesgo) && !$impreso): ?>
					<?php $impreso = true; ?>
					<?php $cont = 2; ?>
					</div>
					<div class="row margin-bottom-2em">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							<legend><strong>Actividades relacionadas con los factores de riesgo.</strong></legend>
						</div>
					</div>
					<div class="row margin-bottom-2em">
				<?php endif; ?>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center margin-bottom-2em">
							<legend><?php echo ($impreso) ? "" : ($cont-1)."." ?> Actividad - <?php echo $row->nombre_actividad ?></legend>
							<p class="text-justify">
								<?php echo $row->descripcion_actividad ?>
							</p>
							<p>
								<strong>Precaución: </strong>
								<?php echo ($row->precaucion_actividad == '') ? 'Ninguna': $row->precaucion_actividad; ?>
							</p>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
								<img style="max-width: 100%; height: auto;" src="<?php echo asset_url('img/'.$row->imagen_actividad); ?>" alt="Imagen actividad <?php echo $row->nombre_actividad?>">
							</div>
						</div>
				<?php $cont++; ?>
				<?php if($cont%2==0 || (($cont - 2) == $size)): ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php if (isset($paciente, $url_guardarsituacionenfermeria)): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
				<h1>Observaciones</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>Si lo desea, puede proporcionar una observación para almacenar la situación de enfermería del paciente en cuestión y una imagen de la herida.</p>
			</div>
		</div>
		<div class="row">
			<?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12  col-xs-12 ">
				<?php $atributos = array('class' => 'form-horizontal', 'role' => 'form');?>
				<?php echo form_open_multipart($url_guardarsituacionenfermeria,$atributos); ?>
				<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
						); ?>
						<?php echo form_label('Imagen asociada','imagen',$atributos) ?>
						<div class="col-lg-10">
							<?php $datos = array(
						        'name'          => 'imagen',
						        'id'            => 'imagen',
						        'value' 		=> set_value('imagen'),
							);	?>
							<?php echo form_upload($datos); ?>
							<p style="color: green;">Peso máximo: 2MB, formatos: gif, png, jpg, ancho máximo: 1300, altura máxima: 800.</p>
						</div>
					</div>
				<div class="form-group">
					<?php $atributos = array(
							'class'			=> 'col-lg-2 control-label',
					); ?>
					<?php echo form_label('Observaciones:','observaciones', $atributos) ?>
					<div class="col-lg-10">
						<?php $datos = array(
					        'name'          => 'observaciones',
					        'id'            => 'observaciones',
					        'max'     		=> '1000',
					        'min'			=> '5',
					        'class' 		=> 'form-control',
					        'rows'			=> '4',
					        'value' 		=> set_value('observaciones'),
						);	?>
						<?php echo form_textarea($datos); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-8 col-lg-4">
						<?php $datos = array(
							'name' 			=> 'submit',
							'value' 		=> 'Guardar situación de enfermería',
							'class' 		=> 'btn btn-primary col-xs-12'
									); ?>
						<?php echo form_submit($datos); ?>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	<?php endif ?>
	<div class="row margin-bottom-2em">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<legend>¿Desea usar de nuevo la aplicación?</legend>
			<p class="text-center"><a class="btn btn-primary btn-block" href="<?php if(isset($url_reinicio)) echo $url_reinicio?>" title="Usar de nuevo la aplicación">Usar de nuevo la aplicación</a></p>
		</div>
	</div>
</div>