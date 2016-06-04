<?php 
/**
 * Archivo Localizacion_model, contiene la clase para manejar la tabla Localizacion de la base de datos.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Localizacion el cual contendrá las funciones para gestionar la tabla Localizacion
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Localizacion_model extends CI_Model {

	/**
	 * Función __construct del modelo Localizacion_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (Localizacion_model).
	 * La función ejecuta el constructor de la clase padre (CI_Model).
	 * La función carga los archivos necesarios para gestionar la base de datos.
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Función obtenerLocalizaciones del modelo Localizacion_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener todas las localizaciones almacenadas.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerLocalizaciones(){
		$query = $this->db->get("Localizacion");
		if($query->num_rows() > 0) return $query;
		else return false;
	}
}
?>