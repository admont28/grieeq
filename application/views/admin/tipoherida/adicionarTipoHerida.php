<?php 
/**
 * Vista adicionarTipoHerida, es la encargada de mostrar los campos necesarios para la adición de un nuevo tipo de herida al sistema.
 *
 * @package aplication/views/admin/tipoherida
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Administración - Adicionar tipo de herida"; ?></h1>
		</div>
	</div>
	<?php if(isset($url_adicionartipoherida)): ?>
		<div class="row">
			<?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
		</div>
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2  col-sm-8 col-sm-offset-2 col-xs-12 ">
				<?php $atributos = array('class' => 'form-horizontal', 'role' => 'form');?>
				<?php echo form_open_multipart($url_adicionartipoherida,$atributos); ?>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Nombre:', 'nombre', $atributos); ?>
						<div class="col-lg-8">
							<?php $datos = array(
						        'name'          => 'nombre',
						        'id'            => 'nombre',
						        'maxlength'     => '100',
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
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Descripción:','descripcion', $atributos) ?>
						<div class="col-lg-8">
							<?php $datos = array(
						        'name'          => 'descripcion',
						        'id'            => 'descripcion',
						        'maxlength'     => '500',
						        'minlength'		=> '5',
						        'required'		=> 'required',
						        'class' 		=> 'form-control',
						        'value' 		=> set_value('descripcion'),
							);	?>
							<?php echo form_textarea($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Imagen asociada','imagen',$atributos) ?>
						<div class="col-lg-8">
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
						<div class="col-lg-offset-6 col-lg-4">
							<?php $datos = array(
								'name' 			=> 'submit',
								'value' 		=> 'Adicionar tipo de herida',
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