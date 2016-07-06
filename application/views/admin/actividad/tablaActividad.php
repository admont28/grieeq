<?php 
/**
 * Vista tablaActividad, es la encargada de mostrar las actividades en forma de tabla bootstrap.
 *
 * @package aplication/views/admin/actividad
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
?>
<?php if (isset($actividades) && sizeof($actividades) > 0 ): ?>
	<div class='table table-responsive'>
		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Precaución</th>
					<th>Imagen asociada</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($actividades as $a): ?>
					<tr>
						<td><?php echo $a->nombre_actividad ?></td>
						<td><?php echo $a->descripcion_actividad ?></td>
						<td><?php echo $a->precaucion_actividad ?></td>
						<td><img src="<?php echo asset_url('img/'.$a->imagen_actividad); ?>" alt="<?php echo $a->nombre_actividad ?>" width="70px"></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p>No se encontraron resultados.</p>
<?php endif; ?>