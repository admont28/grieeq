<?php 
/**
 * Archivo FactorRiesgo_model, contiene la clase para manejar la tabla FactorRiesgo de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo FactorRiesgo el cual contendrá las funciones para gestionar la tabla FactorRiesgo
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class FactorRiesgo_model extends CI_Model {

	/**
	 * Función __construct del modelo FactorRiesgo_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (FactorRiesgo_model).
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
	 * Función obtenerFactoresRiesgo del modelo FactorRiesgo_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener todos las factores de riesgo almacenados.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerFactoresRiesgo(){
		$query = $this->db->get("FactorRiesgo");
		if($query->num_rows() > 0) return $query;
		else return false;
	}
}// Fin de la clase FactorRiesgo_model
/* End of file FactorRiesgo_model.php */
/* Location: ./application/models/FactorRiesgo_model.php */