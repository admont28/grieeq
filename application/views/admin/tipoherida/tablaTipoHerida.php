<?php 
/**
 * Vista tablaTipoHerida, es la encargada de mostrar los tipos de herida en forma de tabla bootstrap.
 *
 * @package aplication/views/admin/tipoherida
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
?>
<?php if (isset($tipos_de_heridas) && sizeof($tipos_de_heridas) > 0 ): ?>
	<div class='table table-responsive'>
		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Imagen asociada</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tipos_de_heridas as $th): ?>
					<tr>
						<td><?php echo $th->nombre_tipoherida ?></td>
						<td><?php echo $th->descripcion_tipoherida ?></td>
						<td><img src="<?php echo asset_url('img/'.$th->imagen_tipoherida); ?>" alt="<?php echo $th->nombre_tipoherida ?>" width="70px"></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p>No se encontraron resultados.</p>
<?php endif; ?>