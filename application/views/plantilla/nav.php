<?php 
/**
 * Vista nav, es la encargada de mostrar el menú de navegación así como el breadcrumb
 *
 * @package aplication/views
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="menu">
	  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex6-collapse">
	        <span class="sr-only">Desplegar navegación</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="<?php echo base_url(); ?>">Tratamiento de Heridas</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse navbar-ex6-collapse">
	      <ul class="nav navbar-nav">
	        <li class="<?php if(isset($controller) && $controller == 'Inicio') echo 'active'; ?>"><a href="<?php echo base_url(); ?>">Inicio</a></li>
	        <li class="<?php if(isset($controller) && $controller == 'SituacionEnfermeria') echo 'active'; ?>"><a href="<?php echo base_url('SituacionEnfermeria') ?>">Situacion de Enfermeria</a></li>
	      </ul>
	    <ul class="nav navbar-nav navbar-right" style="margin-right: 0 !important;">
			<?php if($this->session->has_userdata('usuario')): ?>
		      		<li>
		      			<a href="<?php echo base_url('Usuario'); ?>">
		      				<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuario: <?php echo $this->session->usuario['identificacion_usuario'] ?>
		      			</a>
		      		</li>
		      		<li>
		      			<a href="<?php echo base_url('Usuario/cerrar-sesion'); ?>">Cerar sesión
		      			</a>
		      		</li>
			<?php else: ?>
					<li><a href="<?php echo base_url('Usuario/formulario-de-registro-de-usuario'); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Registrarme</a></li>
		      		<li><a href="<?php echo base_url('Usuario/formulario-inicio-de-sesion'); ?>">Iniciar Sesión</a></li>
			<?php endif; ?>
		    </ul>
	    </div><!-- /.navbar-collapse -->
	  </nav>
</div>
<div style="margin-top: 50px;">
	<?php echo $this->breadcrumb->output(); ?>
</div>
<?php $mensajes = $this->session->flashdata();?>
	<?php if($mensajes): ?>
		<?php foreach ($mensajes as $key => $value): ?>
			<?php if(is_array($value)): ?>
				<?php if(isset($value['tipo']) && $value['tipo']== "error"): ?>
					<div class="alert alert-danger alert-dismissable">
					  	<button type="button" class="close" data-dismiss="alert">&times;</button>
					  	<?php echo (isset($value['mensaje']) ? $value['mensaje']: ""); ?>
					</div>
				<?php elseif(isset($value['tipo']) && $value['tipo'] == "success"): ?>
					<div class="alert alert-success alert-dismissable">
					  	<button type="button" class="close" data-dismiss="alert">&times;</button>
					  	<?php echo (isset($value['mensaje']) ? $value['mensaje']: ""); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			
		<?php endforeach; ?>
	<?php endif; ?>