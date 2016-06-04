<?php 
/**
 * Vista localization_wound, es la encargada de mostrar las localizaciones anatómicas y crear el select con ellas
 * para que el usuario pueda seleccionar una localización.
 *
 * @package aplication/views
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-center">
			<h1>1. Localización anatómica de la herida</h1>
		</div>
	</div>
	<?php if(isset($localizations, $url_localizacion)): ?>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>A continuación se presenta una serie de localizaciones anatómicas del cuerpo humano, en la cual deberá seleccionar donde se encuentre la herida.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">
				<img class="img-responsive" src="<?php echo asset_url('img/miembro_superior.jpg'); ?>" alt="Miembro superior del cuerpo humano.">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center">
				<img class="img-responsive" src="<?php echo asset_url('img/miembro_inferior.jpg'); ?>" alt="Miembro inferior del cuerpo humano.">
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
				<h3>Seleccione una opción</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?php echo form_open($url_localizacion) ?>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<?php echo form_label('Localización de la herida: '); ?>	
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<?php
								$estilos = array(
								'class' => 'form-control'
	 							);
								echo form_dropdown('selLocalizacion', $localizations,$seleccionado, $estilos); 
							?>
						</div>
					</div>
					<?php 
						$datos = array(
							'name' => 'submit',
							'value' => 'Ir al tipo de herida',
							'class' => 'btn btn-primary col-lg-4 col-md-4 col-sm-4 col-xs-12'
							);
						echo form_submit($datos);
						echo form_close();?>
			</div>
		</div>
	<?php else:  // Endif isset ?>
		<p class="text-danger">Por el momento no existen localizaciones anatómicas registradas, por favor inténtalo más tarde.</p>
	<?php endif;?>	
</div>