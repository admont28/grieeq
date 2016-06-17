<?php 
/**
 * Archivo TipoHeridaActividad_model, contiene la clase para manejar la tabla TipoHeridaActividad de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo TipoHeridaActividad el cual contendrá las funciones para gestionar la tabla TipoHeridaActividad
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class TipoHeridaActividad_model extends CI_Model {
	/**
	 * Constante que almacenará el nombre de la tabla TipoHeridaActividad.
	 */
	const TABLE_NAME = "TipoHeridaActividad";

	/**
	 * Función __construct del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (TipoHeridaActividad_model).
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
	 * Función obtener_tipos_de_herida_por_actividad del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se encarga de consultar todos los tipos de herida relacionados con la actividad pasada por parámetro.
	 *
	 * @access public
	 * @param  integer $idActividad Identificador único de la actividad.
	 * @return array              	Retorna un arreglo de objetos de tipo TipoHeridaActividad si encuentra alguna, de lo contrario el arreglo irá vacío.
	 */
	public function obtener_tipos_de_herida_por_actividad($idActividad){
		$this->db->where(array('Actividad_idActividad' => $idActividad));
		$this->db->order_by("orden_tipoheridaactividad ASC");
		$query = $this->db->get(self::TABLE_NAME);
		return $query->result();

	}
}// Fin de la clase TipoHeridaActividad_model
/* End of file TipoHeridaActividad_model.php */
/* Location: ./application/models/TipoHeridaActividad_model.php */