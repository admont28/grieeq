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
	<?php if(isset($rol,$url_gestiontiposherida,$url_gestionfactoresriesgo,$url_gestionactividades, $url_gestionusuarios) && $rol == "admin"): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
				<h1>Acciones Administrativas</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-1em">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestiontiposherida; ?>" title="Gestionar información de los tipos de herida">Gestionar información de los tipos de herida</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionfactoresriesgo; ?>" title="Gestionar la información de los factores de riesgo">Gestionar información de los factores de riesgo</a>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionactividades; ?>" title="Gestionar la información de las actividades">Gestionar información de las actividades</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<a class="btn btn-primary btn-block" href="<?php echo base_url().$url_gestionusuarios; ?>" title="Gestionar los usuarios">Gestionar los usuarios</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>