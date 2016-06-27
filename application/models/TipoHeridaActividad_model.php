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

	/**
	 * Función insertar del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se encarga de insertar un registro en la base de datos.
	 *
	 * @access public
	 * @param  integer  $idActividad  Identificador único de la actividad.
	 * @param  integer  $idTipoHerida Identificador único del tipo de herida.
	 * @param  integer $orden         Orden de la actividad para el tipo de herida.
	 * @return boolean                Retorna true si se pudo insertar, de lo contrario retorna false.
	 */
	public function insertar($idActividad, $idTipoHerida, $orden = 0){
		$datos = array(
			'Actividad_idActividad'     => $idActividad,
			'TipoHerida_idTipoHerida'   => $idTipoHerida,
			'orden_tipoheridaactividad' => $orden
		);
		return $this->db->insert(self::TABLE_NAME, $datos);
	}

	/**
	 * Función eliminar_relacion_tipos_de_herida_por_actividad del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se encarga de eliminar de la base de datos las relaciones entre tipos de herida y actividad.
	 * 
	 * @access public
	 * @param  integer $idActividad  Identificador único de la actividad.
	 * @return boolean               Retorna true si se pudo eliminar, de lo contrario retorna false.
	 */
	public function eliminar_relacion_tipos_de_herida_por_actividad($idActividad = 0){
		return $resultado = $this->db->delete(self::TABLE_NAME, array("Actividad_idActividad" => $idActividad));
	}

	/**
	 * Función obtener_actividades_por_tipo_de_herida del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se encarga de obtener las actividades dado un tipo de herida en específico.
	 * 
	 * @access public
	 * @param  integer $idTipoHerida Identificación única del tipo de herida.
	 * @return array                 Retorna un arreglo con el resultado de la consulta, el arreglo retorna vacío si no encuentra ninguna coincidencia.
	 */
	public function obtener_actividades_por_tipo_de_herida($idTipoHerida = 0){
		$this->db->where(array('TipoHerida_idTipoHerida' => $idTipoHerida));
		$this->db->order_by("orden_tipoheridaactividad ASC");
		$query = $this->db->get(self::TABLE_NAME);
		return $query->result();
	}

	/**
	 * Función actualizar_orden_actividad del modelo TipoHeridaActividad_model.
	 *
	 * Esta función se encarga de actualizar el orden de una actividad dado un tipo de herida.
	 * 
	 * @access public
	 * @param  integer $idActividad  Identificador único de la actividad a actualizar.
	 * @param  integer $idTipoHerida Identificador único del tipo de herida con la que la actividad está relacionada.
	 * @param  integer $orden        Orden de la actividad, ej: 1,2,3,4,5...
	 * @return boolean               Retorna 
	 */
	public function actualizar_orden_actividad($idActividad = 0, $idTipoHerida = 0, $orden = 0){
		$data = array(
			'orden_tipoheridaactividad' => $orden,
		);
		$this->db->where('Actividad_idActividad', $idActividad);
		$this->db->where('TipoHerida_idTipoHerida', $idTipoHerida);
		return $this->db->update(self::TABLE_NAME, $data);
	}

}// Fin de la clase TipoHeridaActividad_model
/* End of file TipoHeridaActividad_model.php */
/* Location: ./application/models/TipoHeridaActividad_model.php */