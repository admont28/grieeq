<?php 
/**
 * Vista editarUsuario, es la encargada de mostrar los campos necesarios para la edición de usuarios.
 *
 * @package aplication/views/admin/usuario
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Administración - Editar usuario"; ?></h1>
		</div>
	</div>
	<?php if(isset($url_editarusuario, $usuario)): ?>
		<div class="row">
			<?php echo validation_errors('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>'); ?>
		</div>
		<div class="row">
			<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2  col-sm-8 col-sm-offset-2 col-xs-12 ">
				<?php $atributos = array('class' => 'form-horizontal', 'role' => 'form');?>
				<?php echo form_open($url_editarusuario,$atributos); ?>
					<?php echo form_hidden('idUsuario', $usuario->idUsuario); ?>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Número de identificación:', 'identificacion', $atributos); ?>
						<div class="col-lg-8">
							<?php $datos = array(
						        'name'          => 'identificacion',
						        'id'            => 'identificacion',
						        'maxlength'     => '45',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'autofocus'		=> 'autofocus',
						        'value' 		=> (isset($usuario->identificacion_usuario))? $usuario->identificacion_usuario: set_value('identificacion'),
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Contraseña:','password', $atributos) ?>
						<div class="col-lg-8">
							<?php $datos = array(
						        'name'          => 'password',
						        'id'            => 'password',
						        'maxlength'     => '50',
						        'minlength'		=> '8',
						        'class' 		=> 'form-control',
						        'value' 		=> set_value('password'),
							);	?>
							<?php echo form_password($datos); ?>
							<p style="color: green;">Si deja el campo en blanco se conservará la contraseña actual</p>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Repetir contraseña:','repetirpassword',$atributos) ?>
						<div class="col-lg-8">
							<?php $datos = array(
						        'name'          => 'repetirpassword',
						        'id'            => 'repetirpassword',
						        'maxlength'     => '50',
						        'minlength'		=> '8',
						        'class' 		=> 'form-control',
						        'value' 		=> set_value('repetirpassword'),
							);	?>
							<?php echo form_password($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<?php $atributos = array(
								'class'			=> 'col-lg-4 control-label',
						); ?>
						<?php echo form_label('Correo electrónico:','correo',$atributos) ?>
						<div class="col-lg-8">
							<?php $datos = array(
								'type'			=> 'email',
						        'name'          => 'correo',
						        'id'            => 'correo',
						        'maxlength'     => '40',
						        'minlength'		=> '8',
						        'class' 		=> 'form-control',
						        'required'		=> 'required',
						        'value' 		=> (isset($usuario->correo_usuario)) ? $usuario->correo_usuario: set_value('correo'),
							);	?>
							<?php echo form_input($datos); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-6 col-lg-3">
							<?php $datos = array(
								'name' 			=> 'submit',
								'value' 		=> 'Actualizar usuario',
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