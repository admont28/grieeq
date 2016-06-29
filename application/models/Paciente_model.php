<?php 
/**
 * Archivo Paciente_model, contiene la clase para manejar la tabla Paciente de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Paciente el cual contendrá las funciones para gestionar la tabla Paciente
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Paciente_model extends CI_Model {
	/**
	 * Constante que almacenará el nombre de la tabla Paciente.
	 */
	const TABLE_NAME = "Paciente";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Paciente.
	 */
	const TABLE_PK_NAME = "idPaciente";

	/**
	 * Función __construct del modelo Paciente_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (Paciente_model).
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
	 * Función contar_registros del modelo Paciente_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla Paciente.
	 *
	 * @access public
	 * @return integer Retorna la cantidad de resultados obtenidos.
	 */
	public function contar_registros(){
        $this->db->select('*');    
        $this->db->from(self::TABLE_NAME);
        return $this->db->count_all_results();
    }
    
    /**
     * Función obtener_resultados del modelo Paciente_model.
	 *
	 * Esta función se encarga de obtener los Pacientes dado cierto limite e inicio.
	 *
	 * @access public
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @param  integer $idUsuario Identificador único del usuario.
     * @return Array          Retorna un arreglo de objetos con los Pacientes encontrados.
     */
    public function obtener_resultados($limit = 100, $start = 0, $idUsuario = 0){
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $this->db->where("Usuario_idUsuario", $idUsuario);
        $this->db->order_by(self::TABLE_PK_NAME, 'ASC');
        $this->db->limit($limit, $start);    
        $query = $this->db->get();    
        return $query->result();
    }

    /**
     * Función crear_paciente del modelo Paciente_model.
	 *
	 * Esta función se encarga de crear un nuevo paciente en la base de datos.
	 *
	 * @access public
     * @param  string $nombre         	Nombre del nuevo paciente a adicionar.
     * @param  string $identificacion 	Identificación del paciente a adicionar.
     * @param  integer $edad           	Edad del paciente a adicionar.
     * @param  string $sexo           	Sexo del paciente, M o F.
     * @param  string $diagnostico    	Diagnóstico inicial del paciente.
     * @param  integer $idUsuario      	Identificador del usuario que adiciona el paciente.
     * @return boolean                 	Retorna true si se pudo insertar, de lo contrario retorna false.
     */
	public function crear_paciente($nombre, $identificacion, $edad, $sexo, $diagnostico, $idUsuario){
		$data = array(
				'nombre_paciente' => $nombre,
				'identificacion_paciente' => $identificacion,
				'edad_paciente' => $edad,
				'sexo_paciente' => $sexo,
				'diagnostico_paciente' => $diagnostico,
				'Usuario_idUsuario' => $idUsuario,
				);
		return $this->db->insert(self::TABLE_NAME,$data);
	}

	/**
     * Función obtener_por_id del modelo Paciente_model.
	 *
	 * Esta función se encarga de obtener un paciente dado su id.
	 *
	 * @access public
     * @param  integer $idPaciente 	Id único del paciente.
     * @return mixed              	Retorna el paciente si lo encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idPaciente){
    	$paciente = $this->db->get_where(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idPaciente));
    	if($paciente->num_rows() == 1){
			return $paciente->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_id del modelo Paciente_model.
	 *
	 * Esta función se encarga de eliminar un paciente dado su id.
	 *
	 * @access public
     * @param  integer $idPaciente 		Identificación única del paciente.
     * @return boolean                 	Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_id($idPaciente){
    	// TODO: Eliminar todas las relaciones del paciente.
    	return $this->db->delete(self::TABLE_NAME, array('idPaciente' => $idPaciente));
    }
}// Fin de la clase Paciente_model
/* End of file Paciente_model.php */
/* Location: ./application/models/Paciente_model.php */