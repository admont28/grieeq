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
	<?php if(isset($rol,$url_adicionarpaciente) && $rol == "admin"): ?>
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
					<a class="btn btn-primary btn-block" href="" title="Editar paciente seleccionado">Editar paciente seleccionado</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" title="Adicionar situación de enfermería a paciente seleccionado">Adicionar situación de enfermería a paciente seleccionado</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" title="Dar de alta a paciente seleccionado (Eliminar)">Dar de alta a paciente seleccionado (Eliminar)</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-bottom-1em">
					<a class="btn btn-primary btn-block" href="" title="Ver historial del paciente seleccionado">Ver historial del paciente seleccionado</a>
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