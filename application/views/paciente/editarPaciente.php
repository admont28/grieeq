<?php 
/**
 * Vista editarPaciente, es la encargada de mostrar los campos necesarios para la edición de un  paciente al usuario que ha iniciado sesión.
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
			<h1><?php echo (isset($titulo))? $titulo: "Editar paciente"; ?></h1>
		</div>
	</div>
	<?php if(isset($url_editarpaciente, $paciente)): ?>
		<div class="row">
			<?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
		</div>
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2  col-sm-8 col-sm-offset-2 col-xs-12 ">
				<?php $atributos = array('class' => 'form-horizontal', 'role' => 'form');?>
				<?php echo form_open($url_editarpaciente,$atributos); ?>
					<?php echo form_hidden('idPaciente', $paciente->idPaciente); ?>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-3 control-label',
						); ?>
						<?php echo form_label('Nombre:', 'nombre', $atributos); ?>
						<div class="col-lg-9">
							<?php $datos = array(
						        'name'          => 'nombre',
						        'id'            => 'nombre',
						        'maxlength'     => '100',
						        'minlength'		=> '5',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'autofocus'		=> 'autofocus',
						        'value' 		=> (isset($paciente->nombre_paciente))? $paciente->nombre_paciente: set_value('nombre'),
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-3 control-label',
						); ?>
						<?php echo form_label('Identificación:','identificacion', $atributos) ?>
						<div class="col-lg-9">
							<?php $datos = array(
						        'name'          => 'identificacion',
						        'id'            => 'identificacion',
						        'maxlength'     => '100',
						        'minlength'		=> '5',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'value' 		=> (isset($paciente->identificacion_paciente))? $paciente->identificacion_paciente: set_value('identificacion'),
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-3 control-label',
						); ?>
						<?php echo form_label('Edad:','edad', $atributos) ?>
						<div class="col-lg-9">
							<?php $datos = array(
						        'name'          => 'edad',
						        'id'            => 'edad',
						        'max'     		=> '200',
						        'min'			=> '0',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'value' 		=> (isset($paciente->edad_paciente))? $paciente->edad_paciente: set_value('edad'),
						        'type'			=> 'number',
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-3 control-label',
						); ?>
						<?php echo form_label('Sexo:','sexo',$atributos) ?>
						<div class="radio col-lg-9">
							<label>
								<?php $datos = array(
							        'name'          => "sexo",
							        'value'         => "M",
							        'required'		=> "required",
							        'checked'		=> (isset($paciente->sexo_paciente) && $paciente->sexo_paciente == "M") ? "checked" : set_radio("sexo", "M"),
								);	?>
								<?php echo form_radio($datos); ?>
								Masculino
							</label>
							<label>
								<?php $datos = array(
							        'name'          => "sexo",
							        'value'         => "F",
							        'required'		=> "required",
							        'checked'		=> (isset($paciente->sexo_paciente) && $paciente->sexo_paciente == "F") ? "checked" : set_radio("sexo", "F")
								);	?>
								<?php echo form_radio($datos); ?>
								Femenino
							</label>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-3 control-label',
						); ?>
						<?php echo form_label('Diagnóstico:','diagnostico', $atributos) ?>
						<div class="col-lg-9">
							<?php $datos = array(
						        'name'          => 'diagnostico',
						        'id'            => 'diagnostico',
						        'max'     		=> '1000',
						        'min'			=> '5',
						        'required'		=> 'required',
						        'class' 		=> 'form-control',
						        'rows'			=> '4',
						        'value' 		=> (isset($paciente->diagnostico_paciente))? $paciente->diagnostico_paciente: set_value('diagnostico'),
							);	?>
							<?php echo form_textarea($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-6 col-lg-3">
							<?php $datos = array(
								'name' 			=> 'submit',
								'value' 		=> 'Actualizar paciente',
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