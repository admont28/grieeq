<?php 
/**
 * Archivo Actividad_model, contiene la clase para manejar la tabla Actividad de la base de datos.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Actividad el cual contendrá las funciones para gestionar la tabla Actividad
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Actividad_model extends CI_Model {

	/**
	 * Función __construct del modelo Actividad_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (Actividad_model).
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
	 * Función obtenerActividadesTipoHerida del modelo Actividad_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener las actividades relacionadas con un tipo de herida en específico.
	 * @param  integer $idTipoHerida Entero que identifica unívocamente al tipo de herida.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerActividadesTipoHerida($idTipoHerida){
		//$query = "SELECT * FROM Actividad A, TipoHeridaActividad T WHERE T.TipoHerida_idTipoHerida = $tipo_herida AND T.Actividad_idAcitivdad = A.idActividad";
		// SELECT * FROM `Actividad`, `TipoHeridaActividad`, `FactorRiesgoActividad` WHERE `TipoHeridaActividad`.`TipoHerida_idTipoHerida` = '1' AND `FactorRiesgoActividad`.`FactorRiesgo_idFactorRiesgo` = 1 AND ( `FactorRiesgoActividad`.`Actividad_idActividad` = `Actividad`.`idActividad` OR `TipoHeridaActividad`.`Actividad_idActividad` = `Actividad`.`idActividad`)
		$this->db->from("Actividad , TipoHeridaActividad");
		$this->db->where("TipoHeridaActividad.TipoHerida_idTipoHerida", $idTipoHerida);
		$this->db->where("TipoHeridaActividad.Actividad_idActividad = Actividad.idActividad");
		$this->db->order_by("orden_tipoheridaactividad", "asc"); 
		$resultado = $this->db->get();
		if($resultado->num_rows() > 0 ) return $resultado->result();
        else return false;
	}

	/**
	 * Función obtenerActividadesFactorRiesgo del modelo Actividad_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener las actividades relacionadas con un factor de riesgo en específico.
	 * @param  Integer $idFactorRiesgo Entero que identifica unívocamente al factor de riesgo.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerActividadesFactorRiesgo($idFactorRiesgo){
		$this->db->from("Actividad , FactorRiesgoActividad");
		$this->db->where("FactorRiesgoActividad.FactorRiesgo_idFactorRiesgo", $idFactorRiesgo);
		$this->db->where("FactorRiesgoActividad.Actividad_idActividad = Actividad.idActividad");
		$resultado = $this->db->get();
		if($resultado->num_rows() > 0 )return $resultado->result();
        else return false;
	}
}
?>