<?php 
/**
 * Vista activities, es la encargada de mostrar las actividades a realizar de forma ordenada, con
 * responsive y si se incluyen actividades relacionadas con los factores de riesgo se separa de las
 * demás actividades.
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
			<h1>4. Actividades a realizar</h1>
		</div>
		<?php if(isset($actividades_finales)) { ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-justify margin-bottom-2em">
				<p>A continuación se presentan las actividades sugeridas para el tratamiento de la herida, estas actividades son acorde al tipo de herida y los factores de riesgo seleccionados. Si no seleccionó algún factor de riesgo, solo se mostrarán las actividades relacionadas con el tipo de herida escogido.</p>
			</div>
		<?php } else{  // Endif isset ?>
			<p class="text-danger">Por el momento no existen actividades asociadas, por favor inténtalo más tarde.</p>
		<?php
			} 
		?>
	</div>
	<?php 
		if (isset($actividades_finales, $url_reinicio) && is_array($actividades_finales) && sizeof($actividades_finales) > 0) {
			$cont = 2; 
			$size = sizeof($actividades_finales);
			$impreso = false;
			foreach($actividades_finales as $row){
				if($cont%2==0){ ?>
					<div class="row margin-bottom-2em">
				<?php } // Endif cont ?>
					<?php if(isset($row->FactorRiesgo_idFactorRiesgo) && !$impreso) {
							$impreso = true;
							$cont = 2;
						?>
							</div>
							<div class="row margin-bottom-2em">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
									<legend><strong>Actividades relacionadas con los factores de riesgo.</strong></legend>
								</div>
							</div>
							<div class="row margin-bottom-2em">
					<?php } ?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-center margin-bottom-2em">
						<legend><?php echo ($impreso) ? "" : ($cont-1)."." ?> Actividad <?php echo $row->nombre ?></legend>
						<p class="text-justify">
							<?php echo $row->descripcion ?>
						</p>
						<p>
							<strong>Precaución: </strong>
							<?php echo ($row->precaucion == '') ? 'Ninguna': $row->precaucion; ?>
						</p>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
							<img style="max-width: 100%; height: auto;" src="<?php echo asset_url('img/'.$row->imagen); ?>" alt="Imagen actividad <?php echo $row->nombre?>">
						</div>
					</div>
			<?php $cont++; 
				if($cont%2==0 || (($cont - 2) == $size)){ ?>
					</div>
				<?php 
				} // Endif cont
			} // Endforeach
			
		} // endif isset
	?>
	<div class="row margin-bottom-2em">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<legend>¿Desea usar de nuevo la aplicación?</legend>
					<p class="text-center"><a class="btn btn-primary" href="<?php if(isset($url_reinicio)) echo $url_reinicio?>" title="Usar de nuevo la aplicación">Usar de nuevo la aplicación</a></p>
				</div>
	</div>
</div>