<?php /**
 * Vista tablaLocalizacion, es la encargada de mostrar una localización anatómica en forma de tabla bootstrap.
 *
 * @package aplication/views/admin/localizacion
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
if (isset($localizaciones) && sizeof($localizaciones) > 0 ): ?>
	<div class='table table-responsive'>
		<table class='table table-striped table-bordered table-hover'>
			<thead>
				<tr>
					<th>Nombre</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($localizaciones as $l): ?>
					<tr>
						<td><?php echo $l->nombre_localizacion; ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p>No se encontraron resultados.</p>
<?php endif; ?>