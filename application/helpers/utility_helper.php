<?php 
	/**
	 * Archivo usado como helper para acceder al directorio assets que contiene las imagenes, css,
	 * js y demás archivos de poyo.
	 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
	 * @link https://github.com/admont28 Perfil del autor.
	 * @version 1.0 Versión inicial de la clase.
	 */

	/**
	 * Función que permite obtener la ruta del directorio assets (/assets).
	 * @param  string $url Url que se desea acceder dentro del directorio assets.
	 * @return string      Retorna una cadena con la ruta.
	 */
	function asset_url($url = ''){
	   return base_url().'assets/'.$url;
	}
?>