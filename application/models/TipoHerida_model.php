<?php 
/**
 * Archivo TipoHerida_model, contiene la clase para manejar la tabla TipoHerida de la base de datos.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo TipoHerida el cual contendrá las funciones para gestionar la tabla TipoHerida
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class TipoHerida_model extends CI_Model {

	/**
	 * Función __construct del modelo TipoHerida_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (TipoHerida_model).
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
	 * Método para obtener todas los tipos de herida almacenadas en la base de datos.
	 * @return [Array|boolean] Retorna un arrelgo con el resultado de la consulta
	 * si existe almenos 1 fila, si no existe alguna fila retorna false.
	 */
	/**
	 * Función obtenerTiposHerida del modelo TipoHerida_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener todos los tipos de herida almacenados.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerTiposHerida(){
		$query = $this->db->get("TipoHerida");
		if($query->num_rows() > 0) return $query;
		else return false;
	}
}// Fin de la clase TipoHerida_model
/* End of file TipoHerida_model.php */
/* Location: ./application/models/TipoHerida_model.php */