<?php
/**
 * Vista footer, es la encargada de mostrar el footer, cargar el js de bootstrap, cerrar el body y cerrar el documento html.
 *
 * @package aplication/views/plantilla
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<!-- Etiqueta usada para evitar que el footer se descontrole -->
	<div id="push"></div>
	<!-- Fin del div Wrap (id=wrap) -->
	</div> 
	<div id="footer">
		<div class="container">
	    	<p class="text-muted credit">Diseñado por <a href="https://github.com/admont28">Andrés David Montoya Aguirre</a>.</p>
		</div>
	</div>
	<!-- Archivo JS de Bootstrap -->
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>
</body>
</html>