<?php 
/**
 * Vista adicionarActividad, es la encargada de mostrar los campos necesarios para la adición de una nueva actividad.
 *
 * @package aplication/views/admin/actividad
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Administración - Adicionar actividad"; ?></h1>
		</div>
	</div>
	<?php if(isset($url_adicionaractividad, $tipos_de_heridas, $factores_de_riesgo)): ?>
		<div class="row">
			<?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
				<?php $atributos = array('class' => 'form-horizontal', 'role' => 'form');?>
				<?php echo form_open_multipart($url_adicionaractividad,$atributos); ?>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
						); ?>
						<?php echo form_label('Nombre:', 'nombre', $atributos); ?>
						<div class="col-lg-10">
							<?php $datos = array(
						        'name'          => 'nombre',
						        'id'            => 'nombre',
						        'maxlength'     => '255',
						        'minlength'		=> '5',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'autofocus'		=> 'autofocus',
						        'value' 		=> set_value('nombre'),
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
						); ?>
						<?php echo form_label('Descripción:','descripcion', $atributos) ?>
						<div class="col-lg-10">
							<?php $datos = array(
						        'name'          => 'descripcion',
						        'id'            => 'descripcion',
						        'maxlength'     => '500',
						        'minlength'		=> '5',
						        'required'		=> 'required',
						        'class' 		=> 'form-control',
						        'rows'			=> '4',
						        'value' 		=> set_value('descripcion'),
							);	?>
							<?php echo form_textarea($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
						); ?>
						<?php echo form_label('Precaución:','precaucion', $atributos) ?>
						<div class="col-lg-10">
							<?php $datos = array(
						        'name'          => 'precaucion',
						        'id'            => 'precaucion',
						        'maxlength'     => '500',
						        'minlength'		=> '5',
						        'class' 		=> 'form-control',
						        'rows'			=> '4',
						        'value' 		=> set_value('precaucion'),
							);	?>
							<?php echo form_textarea($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
						); ?>
						<?php echo form_label('Imagen asociada:','imagen',$atributos) ?>
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
						<?php echo form_label('Tipos de herida asociados:','tipoherida',$atributos) ?>
						<div class="checkbox col-lg-10">
							<?php foreach ($tipos_de_heridas as $th ): ?>
								<label class="checkbox">
									<?php $datos = array(
								        'name'          => 'heridas[]',
								        'id'            => $th->idTipoHerida,
								        'value'         => $th->idTipoHerida,
								        'checked'		=> set_checkbox('heridas[]', $th->idTipoHerida),
									); ?>
									<?php echo form_checkbox($datos); ?>
									<?php echo $th->nombre_tipoherida; ?>
								</label>
							<?php endforeach ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-2 control-label',
							); ?>
						<?php echo form_label('Factores de riesgo asociados:','factorriesgo',$atributos) ?>
						<div class="col-lg-10">
							<?php $i=0; ?>
							<?php foreach ($factores_de_riesgo as $fr ): ?>
								<div class="radio col-lg-2 col-xs-4">
									<label>
										<?php $datos = array(
									        'name'          => "factores_de_riesgo[".$i."]",
									        'id'            => 's'.$fr->idFactorRiesgo,
									        'value'         => 's'.$fr->idFactorRiesgo,
									        'checked'		=> set_radio("factores_de_riesgo[".$i."]", 's'.$fr->idFactorRiesgo, true),
										);	?>
										<?php echo form_radio($datos); ?>
										Sin relación
									</label>
								</div>
								<div class="radio col-lg-2 col-xs-4">
									<label>
										<?php $datos = array(
									        'name'          => "factores_de_riesgo[".$i."]",
									        'id'            => 'i'.$fr->idFactorRiesgo,
									        'value'         => 'i'.$fr->idFactorRiesgo,
									        'checked'		=> set_radio("factores_de_riesgo[".$i."]", 'i'.$fr->idFactorRiesgo),
										);	?>
										<?php echo form_radio($datos); ?>
										¿Incluir?
									</label>
								</div>
								<div class="radio col-lg-2 col-xs-4">
									<label>
										<?php $datos = array(
									        'name'          => "factores_de_riesgo[".$i."]",
									        'id'            => 'e'.$fr->idFactorRiesgo,
									        'value'         => 'e'.$fr->idFactorRiesgo,
									        'checked'		=> set_radio("factores_de_riesgo[".$i."]", 'e'.$fr->idFactorRiesgo),
										);	?>
										<?php echo form_radio($datos); ?>
										¿Excluir?
									</label>
								</div>
								<div class="col-lg-6 col-xs-12 margin-bottom-1em">
									<?php echo $fr->nombre_factorriesgo; ?>
								</div>
							<?php $i++; endforeach ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-5 col-lg-3">
							<?php $datos = array(
								'name' 			=> 'submit',
								'value' 		=> 'Adicionar actividad',
								'class' 		=> 'btn btn-primary col-xs-12'
										); ?>
							<?php echo form_submit($datos); ?>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	<?php endif; ?>
</div>