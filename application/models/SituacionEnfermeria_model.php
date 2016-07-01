<?php 
/**
 * Archivo SituacionEnfermeria_model, contiene la clase para manejar la tabla SituacionEnfermeria de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo SituacionEnfermeria el cual contendrá las funciones para gestionar la tabla SituacionEnfermeria
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class SituacionEnfermeria_model extends CI_Model {
	/**
	 * Constante que almacenará el nombre de la tabla SituacionEnfermeria.
	 */
	const TABLE_NAME = "SituacionEnfermeria";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla SituacionEnfermeria.
	 */
	const TABLE_PK_NAME = "idSituacionEnfermeria";

	/**
	 * Función __construct del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (SituacionEnfermeria_model).
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
	 * Función crear_situacion_de_enfermeria del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de insertar en la base de datos una nueva situación de enfermería.
	 * La función hace uso de transacciones para garantizar la integridad de los datos.
	 *
	 * @access public
	 * @param  integer $idPaciente     Identificador único del paciente.
	 * @param  integer $idLocalizacion Identificador único de la localización anatómica.
	 * @param  integer $idTipoHerida   Identificador único del tipo de herida.
	 * @param  string  $observaciones  Observaciones del usuario.
	 * @param  array   $factoresRiesgo Factores de riesgo que seleccionó el usuario.
	 * @param  array   $actividades    Actividades finales sugeridas por la aplicación.
	 * @return boolean                 Retorna true si se pudo insertar todo correctamente, de lo contrario retorna false.
	 */
	public function crear_situacion_de_enfermeria($idPaciente, $idLocalizacion, $idTipoHerida, $observaciones, $factoresRiesgo, $actividades){
		// INICIO DE LA TRANSACCIÓN
        $this->db->trans_begin();
		$datos = array(
			'Paciente_idPaciente'               => $idPaciente,
			'Localizacion_idLocalizacion'       => $idLocalizacion,
			'TipoHerida_idTipoHerida'           => $idTipoHerida,
			'observaciones_situacionenfermeria' => $observaciones
		);
		$this->db->insert(self::TABLE_NAME, $datos);
		$idSituacionEnfermeria = $this->db->insert_id();
		foreach ($factoresRiesgo as $llave => $valor) {
			$datos = array(
				'FactorRiesgo_idFactorRiesgo' => $llave,
				'SituacionEnfermeria_idSituacionEnfermeria' => $idSituacionEnfermeria
			);	
			$this->db->insert("SituacionEnfermeriaFactorRiesgo", $datos);
		}
		foreach ($actividades as $a) {
			$datos = array(
				'Actividad_idActividad' => $a->idActividad,
				'SituacionEnfermeria_idSituacionEnfermeria' => $idSituacionEnfermeria
			);	
			$this->db->insert("SituacionEnfermeriaActividad", $datos);
		}
		if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }
        else{
            $this->db->trans_commit();
            return true;
        }
	}
}// Fin de la clase SituacionEnfermeria_model
/* End of file SituacionEnfermeria_model.php */
/* Location: ./application/models/SituacionEnfermeria_model.php */