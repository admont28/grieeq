<?php 
/**
 * Vista content, es la encargada de mostrar el inicio o home de la aplicaicón.
 *
 * @package aplication/views
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row margin-bottom-2em">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-center">
			<h1>Bienvenido a la aplicación web de apoyo para el profesional de la salud en el cuidado de las personas con heridas</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-center margin-bottom-2em">
			<img src="<?php echo asset_url('img/logo_uq.png')?>" alt="Logo Universidad del Quindío.">
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<p class="text-justify">El programa de Enfermería e Ingeniería de Sistemas y Computación de la Universidad del Quindío le da la bienvenida a la aplicación para el tratamiento de heridas en los pacientes, esta aplicación fue construida por un equipo multidisciplinar e íntegro con el fin de brindar una guía a las enfermeras y médicos que se enfrentan en el día a día al tratamiento de las heridas.</p>
			<p class="text-justify">La aplicación estará en constante actualización, permitiendo esto que la información presentada no se encuentre obsoleta y siempre se encuentre actualizada.</p>
			<p class="text-justify">Está aplicación para el tratamiento de heridas cuenta con imágenes en forma de ayuda para que el usuario pueda usar la aplicación sin mayores inconvenientes, además de las imágenes, cuenta con títulos alusivos a lo que se desea mostrar y un texto descriptivo.</p>
		</div>
	</div>
</div>