<?php
/**
 * Archivo Inicio, contiene la clase para manejar el home o inicio de la aplicación.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador Inicio el cual contendrá las funciones para mostrar los distintos elementos
 * de la página principal de la aplicación.
 * 
 * @package aplication/controllers
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Inicio extends MY_ControladorGeneral {

	/**
	 * Función __construct del controlador Home.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (Home).
	 * La función ejecuta el constructor de la clase padre (CI_Controller).
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función index para el controlador Home.
	 * 
	 * Esta función será ejecutada si no se especifica nada en la URL (ej: URL_APP/home).
	 * La función muestra la página inicial de la aplicación.
	 *
	 * @access public
	 * @return void No se retorna, se muestra la página.
	 */
	public function index(){
		$this->breadcrumb->append("Inicio");
		$this->mostrar_pagina('plantilla/index');
	}

} // Fin de la clase Inicio
/* End of file Inicio.php */
/* Location: ./application/controllers/Inicio.php */