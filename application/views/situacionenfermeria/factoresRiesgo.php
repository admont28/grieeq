<?php 
/**
 * Vista risk_factor, es la encargada de mostrar todos los factores de riesgo organizados y crear el select multiple
 * para que el usuario pueda seleccionar varios.
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
			<h1>3. Factores de riesgo</h1>
		</div>
	</div>
	<div class="row">
		<?php if(isset($risksfactosselect, $risksfactors)): ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>A continuación se presenta una serie de factores de riesgo que influyen en el tratamiento de las heridas con su descripción y ejemplos para su fácil comprensión, al final de la página deberá seleccionar los factores de riesgo que presenta el paciente.</p>
			</div>
		<?php else: ?>
			<p class="text-danger">Por el momento no existen factores de riesgo registrados, por favor inténtalo más tarde.</p>
		<?php endif; ?>
	</div>
	<?php if(isset($risksfactosselect, $risksfactors, $url_factorriesgo)): 
			$cont=2; 
			$size= sizeof($risksfactors);
			foreach($risksfactors as $row):
				if($cont%2==0): ?>
					<div class="row margin-bottom-2em">
				<?php endif; // Endif cont ?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center margin-bottom-2em">
						<legend><?php echo $row->nombre_factorriesgo ?></legend>
						<p class="text-justify">
							<?php echo $row->descripcion_factorriesgo ?>
						</p>
						<p>
							<strong>Ejemplo: </strong>
							<?php echo $row->ejemplo_factorriesgo ?>
						</p>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
							<img style="max-width: 100%; height: auto;" src="<?php echo asset_url('img/'.$row->imagen_factorriesgo); ?>" alt="Imagen <?php echo $row->nombre_factorriesgo?>">
						</div>
					</div>
			<?php 
				$cont++; 
				if($cont%2==0 || (($cont - 2) == $size)): ?>
					</div>
				<?php endif; // Endif cont ?> 
			<?php endforeach; // Endforeach ?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
					<h3>Seleccione las opciones que considere</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?php echo form_open($url_factorriesgo) ?>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<?php echo form_label('Factores de riesgo: '); ?>	
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<?php
								$estilos = array(
								'class' => 'form-control'
	 							);
	 							foreach ($risksfactosselect as $fr) {
	 								$datos = array(
	 									'name' => $fr['id'],
	 									'id' => $fr['id'],
	 									'fr' => $fr['nombre'],
	 									'checked' => $fr['seleccionado']
	 									);
	 								?>
	 								<div class="checkbox">
										<label>
			 								<?php echo form_checkbox($datos);
			 									  echo $fr['nombre'];
			 								 ?>
		 								</label>
	 								</div>
	 								<?php
	 							}
							?>
						</div>
					</div>
					<?php
						$datos = array(
							'name' => 'submit',
							'value' => 'Ir a las actividades a realizar',
							'class' => 'btn btn-primary col-lg-4 col-md-4 col-sm-4 col-xs-12'
							);
						echo form_submit($datos);
						echo form_close();
					?>
				</div>
			</div>
			<?php endif; ?>
<!-- Fin Container -->	
</div>