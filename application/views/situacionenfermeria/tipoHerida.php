<?php 
/**
 * Vista type_wound, es la encargada de mostrar todos los tipos de herida organizados, además,
 * crea el select para que el usuario seleccione uno y continue.
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
			<h1>2. Tipo de herida</h1>
		</div>
	</div>
	<div class="row">
		<?php if(isset($typeswoundsselect, $typeswounds)): ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>A continuación se presenta una serie de tipos de herida con sus respectivas imágenes, al final de la página deberá seleccionar el tipo de herida que presente el paciente.</p>
			</div>
		<?php else: ?>
			<p class="text-danger">Por el momento no existen tipos de herida registrados, por favor inténtalo más tarde.</p>
		<?php endif; ?>
	</div>
	<?php 
		if(isset($typeswoundsselect, $typeswounds, $url_tipoherida, $seleccionado)):  
			$cont=2; 
			foreach($typeswounds as $row):
				if($cont%2==0): ?>
					<div class="row margin-bottom-2em">
				<?php endif; // Endif?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center margin-bottom-2em">
						<legend><?php echo $row->nombre_tipoherida ?></legend>
						<p class="text-justify">
							<?php echo $row->descripcion_tipoherida ?>
						</p>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
							<img style="max-width: 100%; height: auto;" src="<?php echo asset_url('img/'.$row->imagen_tipoherida); ?>" alt="Imagen <?php $row->nombre_tipoherida?>">
						</div>
					</div>
				<?php $cont++; 
				if($cont%2==0): ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
					<h3>Seleccione una opción</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php echo form_open($url_tipoherida) ?>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<?php echo form_label('Tipo de herida: '); ?>	
							</div>
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
								<?php
									$estilos = array(
									'class' => 'form-control'
		 							);
									echo form_dropdown('selTipoHerida', $typeswoundsselect, $seleccionado, $estilos); 
								?>
							</div>
					</div>
					<?php
						$datos = array(
							'name' => 'submit',
							'value' => 'Ir a los factores de riesgo',
							'class' => 'btn btn-primary col-lg-4 col-md-4 col-sm-4 col-xs-12'
							);
						echo form_submit($datos);
						echo form_close();
					?>
				</div>
			</div>
			<?php
		endif; // Endif isset
	?>
</div>