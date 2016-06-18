<?php 
/**
 * Archivo FactorRiesgoActividad_model, contiene la clase para manejar la tabla FactorRiesgoActividad de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo FactorRiesgoActividad el cual contendrá las funciones para gestionar la tabla FactorRiesgoActividad
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class FactorRiesgoActividad_model extends CI_Model {
	/**
	 * Constante que almacenará el nombre de la tabla FactorRiesgoActividad.
	 */
	const TABLE_NAME = "FactorRiesgoActividad";

	/**
	 * Función __construct del modelo FactorRiesgoActividad_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (FactorRiesgoActividad_model).
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
	 * Función obtener_factores_de_riesgo_por_actividad del modelo FactorRiesgoActividad_model.
	 *
	 * Esta función se encarga de consultar todos los tipos de herida relacionados con la actividad pasada por parámetro.
	 *
	 * @access public
	 * @param  integer $idActividad Identificador único de la actividad.
	 * @return array              	Retorna un arreglo de objetos de tipo FactorRiesgoActividad si encuentra alguno, de lo contrario el arreglo irá vacío.
	 */
	public function obtener_factores_de_riesgo_por_actividad($idActividad){
		$this->db->where(array('Actividad_idActividad' => $idActividad));
		$query = $this->db->get(self::TABLE_NAME);
		return $query->result();

	}

	/**
	 * Función insertar del modelo FactorRiesgoActividad_model.
	 *
	 * Esta función se encarga de insertar un registro en la base de datos.
	 *
	 * @access public
	 * @param  integer  $idActividad    Identificador único de la actividad.
	 * @param  integer  $idFactorRiesgo Identificador único del factor de riesgo.
	 * @param  boolean $incluir         Booleano que indica si la actividad es de inclusión o exclusión.
	 * @return boolean                  Retorna true si se pudo insertar, de lo contrario retorna false.
	 */
	public function insertar($idActividad, $idFactorRiesgo, $incluir = false){
		$datos = array(
			'Actividad_idActividad'         => $idActividad,
			'FactorRiesgo_idFactorriesgo'   => $idFactorRiesgo,
			'incluir_factorriesgoactividad' => $incluir
		);
		return $this->db->insert(self::TABLE_NAME, $datos);
	}
}// Fin de la clase FactorRiesgoActividad_model
/* End of file FactorRiesgoActividad_model.php */
/* Location: ./application/models/FactorRiesgoActividad_model.php */