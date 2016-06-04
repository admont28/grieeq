<?php
/**
 * Archivo Error_404, contiene la clase para manejar el error 404.
 */


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador Error_404 el cual contendrá las funciones para mostrar los distintos elementos
 * relacionados con los errores 404, página web no econtrada.
 * 
 * @package aplication/controllers
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Error_404 extends CI_Controller {

	/**
	 * Función __construct del controlador Error_404.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (Error_404).
	 * La función ejecuta el constructor de la clase padre (CI_Controller).
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función index para este controlador.
	 * 
	 * Está función será ejecutada si no se especifica nada en la URL.
	 * La función muestra la página inicial de la aplicación.
	 *
	 * @access public
	 * @return void No se retorna, se muestra la página.
	 */
	public function index(){
		show_404();
	}
}
?>