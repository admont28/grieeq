<?php 
/**
 * Vista nav, es la encargada de mostrar el menú de navegación así como el breadcrumb y mostrar los mensajes que provengan desde los controladores por medio de la sesión.
 *
 * @package aplication/views/plantilla
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
	      	<a class="navbar-brand" href="<?php echo base_url(); ?>">IT-Care@HealUQ</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse navbar-ex6-collapse">
	      	<ul class="nav navbar-nav">
	        	<li class="<?php if(isset($controller) && $controller == 'Inicio') echo 'active'; ?>"><a href="<?php echo base_url(); ?>">Inicio</a></li>
	        	<li class="<?php if(isset($controller) && $controller == 'SituacionEnfermeria') echo 'active'; ?>"><a href="	<?php echo base_url('SituacionEnfermeria') ?>">Situacion de Enfermeria</a></li>
	      	</ul>
	    	<ul class="nav navbar-nav navbar-right" style="margin-right: 0 !important;">
		    	<?php $session = $this->session->has_userdata('usuario'); ?>
		    	<?php if($session): ?>
		    		<?php $identificacion_usuario = $this->session->usuario['identificacion_usuario']; ?>
		    		<?php $rol_usuario = $this->session->usuario['rol_usuario']; ?>
		    		<?php if ($rol_usuario == "admin"): ?>
		    			<li class="dropdown">
				          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administración <span class="caret"></span></a>
				          	<ul class="dropdown-menu">
				          		<li class="dropdown-header">Administración de tipos de herida</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/administracion-de-tipos-de-heridas'); ?>">Visualizar los tipos de herida</a>
				            	</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/formulario-adicionar-tipo-de-herida'); ?>">Adicionar tipo de herida</a>
				            	</li>
				            	<li role="separator" class="divider"></li>
				            	<li class="dropdown-header">Administración de factores de riesgo</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/administracion-de-factores-de-riesgo'); ?>">Visualizar los factores de riesgo</a>
				            	</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/formulario-adicionar-factor-de-riesgo'); ?>">Adicionar factor de riesgo</a>
				            	</li>
				            	<li role="separator" class="divider"></li>
				            	<li class="dropdown-header">Administración de actividades</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/administracion-de-actividades'); ?>">Visualizar las actividades</a>
				            	</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/formulario-adicionar-actividad'); ?>">Adicionar actividad</a>
				            	</li>
				            	<li role="separator" class="divider"></li>
				            	<li class="dropdown-header">Administración de usuarios</li>
				            	<li>
				            		<a href="<?php echo base_url('Administrador/administracion-de-usuarios'); ?>">Visualizar los usuarios</a>
				            	</li>
				          </ul>
				        </li>
		    		<?php endif; ?>
		    		<li class="dropdown">
				          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pacientes <span class="caret"></span></a>
				          	<ul class="dropdown-menu">
				            	<li>
				            		<a href="<?php echo base_url('Usuario/formulario-adicionar-paciente'); ?>">Adicionar paciente</a>
				            	</li>
				          </ul>
				        </li>
		    		<li>
		      			<a href="<?php echo base_url('Usuario/perfil'); ?>">
		      				<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuario: <?php echo $identificacion_usuario ?>
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