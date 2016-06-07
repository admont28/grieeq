<?php 
/**
 * Vista inicioSituacionEnfermeria, es la encargada de mostrar la información principal sobre el uso de la aplicación.
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
			<h1>Aplicación Web de apoyo para el profesional de la salud en el cuidado de las personas con heridas</h1>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
			<p>La aplicación para el tratamiento de heridas consta de 4 pasos los cuales son mencionados a continuación:</p>
			<ol>
				<li><strong>Localización Anatómica de la herida: </strong>Se presenta la imagen de los miembros superiores e inferiores del cuerpo humano como forma de ayuda para que el usuario seleccione la localización anatómica de la herida en el paciente.</li>
				<li><strong>Tipo de Herida: </strong>Se presentan los tipos de heridas existentes con una breve descripción y una imagen relacionada para que el usuario pueda seleccionar fácilmente el tipo de herida del paciente.</li>
				<li><strong>Factores de Riesgo: </strong>Se presentan los distintos factores de riesgo que entran en juego en el tratamiento de una herida con una breve descripción y ejemplos para facilitar su comprensión, el usuario deberá seleccionar los factores de riesgo con los que cuenta el paciente, estos factores de riesgo influirán en las actividades finales a realizar.</li>
				<li><strong>Actividades a Realizar: </strong>Se presenta una lista de actividades ordenadas lógicamente para que el usuario las siga de forma secuencial.</li>
			</ol>
		</div>
		<a class="btn btn-primary margin-bottom-2em col-lg-2 col-md-2 col-sm-4 col-xs-10 col-lg-offset-5 col-md-offset-5 col-sm-offset-4 col-xs-offset-1" href="<?php if(isset($url_localizacion)) echo $url_localizacion?>" title="Iniciar la aplicación">Iniciar la Aplicación</a>
	</div>
</div>