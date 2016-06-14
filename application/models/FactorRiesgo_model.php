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
	 * Constante que almacenará el nombre de la tabla Usuario.
	 */
	const TABLE_NAME = "FactorRiesgo";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Usuario.
	 */
	const TABLE_PK_NAME = "idFactorRiesgo";

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
		$query = $this->db->get(self::TABLE_NAME);
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	/**
	 * Función contar_registros del modelo FactorRiesgo_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla FactorRiesgo.
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
     * Función obtener_resultados del modelo FactorRiesgo_model.
	 *
	 * Esta función se encarga de obtener los factores de riesgo dado cierto limite e inicio.
	 *
	 * @access public
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @return Array          Retorna un arreglo de objetos con los factores de riesgo encontrados.
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
     * Función obtener_por_id del modelo FactorRiesgo_model.
	 *
	 * Esta función se encarga de obtener un factor de riesgo dado su id.
	 *
	 * @access public
     * @param  integer $idFactorRiesgo Id único del factor de riesgo.
     * @return mixed              Retorna el factor de riesgo si lo encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idFactorRiesgo){
    	$factorRiesgo = $this->db->get_where(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idFactorRiesgo));
    	if($factorRiesgo->num_rows() == 1){
			return $factorRiesgo->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_id del modelo FactorRiesgo_model.
	 *
	 * Esta función se encarga de eliminar un factor de riesgo dado su id.
	 * La función recibe un parámetro para indicar si sebe eliminar la imagen asociada al factor de riesgo del servidor o si solo debe ser eliminada de la base de datos.
	 *
	 * @access public
     * @param  integer $idFactorRiesgo Identificación única del factor de riesgo.
     * @param boolean $eliminar_imagen Valor booleano para indicar si se debe intentar eliminar la imagen asociada al factor de riesgo o solo eliminar el factor de riesgo de la base de datos.
     * @return boolean                 Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_id($idFactorRiesgo, $eliminar_imagen = true){
		$resultado = $this->db->delete(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idFactorRiesgo));
		if($eliminar_imagen){
			$this->eliminar_directorio("./assets/img/factorriesgo/".$idFactorRiesgo);
		}
		return true;
	}

	/**
     * Función eliminar_directorio del modelo FactorRiesgo_model.
     *
     * Esta función se encarga de eliminar un directorio y todo su contenido del servidor.
     *
     * @access private
     * @param  string $dir Path del directorio que se desea eliminar, pj: ./assets/img
     * @return void      No retorna nada, solo elimina el directorio y sus archivos.
     */
    private function eliminar_directorio($dir) {
	    if(!$dh = @opendir($dir)) return;
	    while (false !== ($current = readdir($dh))) {
	        if($current != '.' && $current != '..') {
	            //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
	            if (!@unlink($dir.'/'.$current)) 
	                $this->eliminar_directorio($dir.'/'.$current);
	        }       
	    }
	    closedir($dh);
	    //echo 'Se ha borrado el directorio '.$dir.'<br/>';
	    @rmdir($dir);
	}
}// Fin de la clase FactorRiesgo_model
/* End of file FactorRiesgo_model.php */
/* Location: ./application/models/FactorRiesgo_model.php */