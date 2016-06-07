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
	 * Constante que almacenará el nombre de la tabla Usuario.
	 */
	const TABLE_NAME = "TipoHerida";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Usuario.
	 */
	const TABLE_PK_NAME = "idTipoHerida";

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

	/**
	 * Función contar_registros del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla TipoHerida.
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
     * Función obtener_resultados del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de obtener los tipos de heridas dado cierto limite e inicio.
	 *
	 * @access public
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @return Array          Retorna un arreglo de objetos con los tipos de heridas encontrados.
     */
    public function obtener_resultados($limit=100,$start=0){
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $this->db->order_by(self::TABLE_PK_NAME, 'ASC');
        $this->db->limit($limit, $start);    
        $query = $this->db->get();    
        return $query->result();
    }

    /**
     * Función obtener_por_id del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de obtener un tipo de herida dado su id.
	 *
	 * @access public
     * @param  integer $idTipoHerida Id único del tipo de herida.
     * @return mixed              Retorna el tipo de herida si lo encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idTipoHerida){
    	$tipoHerida = $this->db->get_where(self::TABLE_NAME, array('idTipoHerida' => $idTipoHerida));
    	if($tipoHerida->num_rows() == 1){
			return $tipoHerida->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_id del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de eliminar un tipo de herida dado su id.
	 *
	 * @access public
     * @param  integer $idTipoHerida Identificación única del tipo de herida.
     * @return boolean                 Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_id($idTipoHerida){
    	return $this->db->delete(self::TABLE_NAME, array('idTipoHerida' => $idTipoHerida));
    }
}// Fin de la clase TipoHerida_model
/* End of file TipoHerida_model.php */
/* Location: ./application/models/TipoHerida_model.php */