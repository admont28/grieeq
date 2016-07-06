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

	/**
	 * Función obtener_por_paciente del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de obtener las situaciones de enfermería dado un paciente.
	 *
	 * @access public
	 * @param  integer $idPaciente Identificador único del paciente.
	 * @return array               Retorna las situaciones de enfermería del paciente si existen, de lo contrario retorna un arreglo vacío.
	 */
	public function obtener_por_paciente($idPaciente = 0){
		if($idPaciente != 0){
			$this->db->where(array('Paciente_idPaciente' => $idPaciente));
			$query = $this->db->get(self::TABLE_NAME);
			return $query->result();
		}
		return array();
	}
	/**
	 * Función contar_registros del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla SituacionEnfermeria.
	 *
	 * @access public
	 * @return integer Retorna la cantidad de resultados obtenidos.
	 */
	public function contar_registros($idPaciente, $idUsuario){
        $this->db->select('*');    
        $this->db->from(self::TABLE_NAME);
        $this->db->where(array('Paciente_idPaciente' => $idPaciente));
        return $this->db->count_all_results();
    }
    
    /**
     * Función obtener_resultados del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de obtener las situaciones de enfermería dado cierto limite e inicio.
	 *
	 * @access public
	 * @param  integer $idPaciente Identificador del paciente al cual pertene las situaciones de enfermería
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @return Array          Retorna un arreglo de objetos con las situaciones de enfermería encontradas.
     */
    public function obtener_resultados($idPaciente, $limit=100, $start=0){
		$this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $this->db->where(array('Paciente_idPaciente' => $idPaciente));
        $this->db->order_by(self::TABLE_PK_NAME, 'ASC');
        $this->db->limit($limit, $start);    
        $query = $this->db->get();    
        return $query->result();
    }

    /**
     * Función obtener_por_id del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de obtener una situación de enfermería dado su id.
	 *
	 * @access public
     * @param  integer $idSituacionEnfermeria Identificador único de la situación de enfermería.
     * @return Object                         Retorna un objeto con la situación de enfermería si la encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idSituacionEnfermeria = 0){
		if($idSituacionEnfermeria != 0){
			$this->db->where(array(self::TABLE_PK_NAME => $idSituacionEnfermeria));
			$query = $this->db->get(self::TABLE_NAME);
			return $query->row();
		}
		return null;
	}

	/**
	 * Función obtener_por_id del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de obtener los identificadores de las actividades relacionadas con una situación de enfermería.
	 *
	 * @access public
	 * @param  integer $idSituacionEnfermeria Identificador único de la situación de enfermería.
	 * @return array                          Retorna un arreglo con los identificadores de las  actividades asociadas a la situación de enfermería pasada por parámetro.
	 */
	public function obtener_actividades($idSituacionEnfermeria = 0){
		if($idSituacionEnfermeria != 0){
			$this->db->where(array("SituacionEnfermeria_idSituacionEnfermeria" => $idSituacionEnfermeria));
			$query = $this->db->get("SituacionEnfermeriaActividad");
			return $query->result();
		}
		return array();
	}

	/**
	 * Función obtener_por_id del modelo SituacionEnfermeria_model.
	 *
	 * Esta función se encarga de obtener los identificadores de los factores de riesgo relacionados con una situación de enfermería.
	 *
	 * @access public
	 * @param  integer $idSituacionEnfermeria Identificador único de la situación de enfermería.
	 * @return array                          Retorna un arreglo con los identificadores de los factores de riesgo asociados a la situación de enfermería pasada por parámetro.
	 */
	public function obtener_factores_de_riesgo($idSituacionEnfermeria = 0){
		if($idSituacionEnfermeria != 0){
			$this->db->where(array("SituacionEnfermeria_idSituacionEnfermeria" => $idSituacionEnfermeria));
			$query = $this->db->get("SituacionEnfermeriaFactorRiesgo");
			return $query->result();
		}
		return array();
	}
}// Fin de la clase SituacionEnfermeria_model
/* End of file SituacionEnfermeria_model.php */
/* Location: ./application/models/SituacionEnfermeria_model.php */