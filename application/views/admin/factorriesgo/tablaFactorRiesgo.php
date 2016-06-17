<?php 
/**
 * Vista tablaFactorRiesgo, es la encargada de mostrar los factores de riesgo en forma de tabla bootstrap.
 *
 * @package aplication/views/admin/factorriesgo
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
?>
<?php if (isset($factores_de_riesgo) && sizeof($factores_de_riesgo) > 0 ): ?>
	<div class='table table-responsive'>
		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Ejemplo</th>
					<th>Tipo</th>
					<th>Imagen asociada</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($factores_de_riesgo as $fr): ?>
					<tr>
						<td><?php echo $fr->nombre_factorriesgo ?></td>
						<td><?php echo $fr->descripcion_factorriesgo ?></td>
						<td><?php echo $fr->ejemplo_factorriesgo ?></td>
						<td><?php echo ($fr->incluir) ? "Inclusión" : "Exclusión" ?></td>
						<td><img src="<?php echo asset_url('img/'.$fr->imagen_factorriesgo); ?>" alt="<?php echo $fr->nombre_factorriesgo ?>" width="70px"></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p>No se encontraron resultados.</p>
<?php endif; ?>