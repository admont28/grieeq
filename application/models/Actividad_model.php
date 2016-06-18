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
	 * Constante que almacenará el nombre de la tabla Actividad.
	 */
	const TABLE_NAME = "Actividad";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Actividad.
	 */
	const TABLE_PK_NAME = "idActividad";

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

	/**
	 * Función contar_registros del modelo Actividad_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla Actividad.
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
     * Función obtener_resultados del modelo Actividad_model.
	 *
	 * Esta función se encarga de obtener los factores de riesgo dado cierto limite e inicio.
	 *
	 * @access public
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @return Array          Retorna un arreglo de objetos con las actividades encontradas.
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
     * Función obtener_por_id del modelo Actividad_model.
	 *
	 * Esta función se encarga de obtener una actividad dado su id.
	 *
	 * @access public
     * @param  integer $idActividad Id único de la actividad.
     * @return mixed              Retorna la actvidad si la encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idActividad){
    	$factorRiesgo = $this->db->get_where(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idActividad));
    	if($factorRiesgo->num_rows() == 1){
			return $factorRiesgo->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_id del modelo Actividad_model.
	 *
	 * Esta función se encarga de eliminar una actividad dado su id.
	 * La función recibe un parámetro para indicar si sebe eliminar la imagen asociada a la actividad del servidor o si solo debe ser eliminada de la base de datos.
	 *
	 * @access public
     * @param  integer $idActividad Identificación única de la actividad.
     * @param boolean $eliminar_imagen Valor booleano para indicar si se debe intentar eliminar la imagen asociada a la actividad o solo eliminar la actividad de la base de datos.
     * @return boolean                 Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_id($idActividad, $eliminar_imagen = true){
    	$this->db->trans_start(); // Inicio de la transacción.
    	$this->load->model('TipoHeridaActividad_model');
    	$this->load->model('FactorRiesgoActividad_model');
		$this->db->delete(TipoHeridaActividad_model::TABLE_NAME, array(self::TABLE_NAME."_".self::TABLE_PK_NAME => $idActividad));
    	$this->db->delete(FactorRiesgoActividad_model::TABLE_NAME, array(self::TABLE_NAME."_".self::TABLE_PK_NAME => $idActividad));
		$resultado = $this->db->delete(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idActividad));
		$this->db->trans_complete(); // Fin de la transacción.

		if ($this->db->trans_status() === FALSE){
		    return false;
		}
		if($eliminar_imagen){
			$this->eliminar_directorio("./assets/img/actividad/".$idActividad);
		}
		return true;
	}

	/**
     * Función eliminar_directorio del modelo Actividad_model.
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

	/**
	 * Función crear_actividad del modelo Actividad_model.
	 *
	 * Esta función se encarga de insertar en la base de datos una nueva actividad.
	 *
	 * @access public
	 * @param  string $nombre      Nombre de la nueva actividad.
	 * @param  string $descripcion Descripción de la nueva actividad.
	 * @param  string $precaucion  precaucion de la nueva actividad.
	 * @param  string $imagen      Nombre de la imagen asociada a la actividad.
	 * @return integer             Retorna el id del actividad insertado.
	 */
	public function crear_actividad($nombre, $descripcion, $precaucion, $imagen){
		$datos = array(
			'nombre_actividad'      => $nombre,
			'descripcion_actividad' => $descripcion,
			'precaucion_actividad'  => $precaucion,
			'imagen_actividad'      => $imagen
		);
		$this->db->insert(self::TABLE_NAME, $datos);
		$idActividad = $this->db->insert_id();
		$datos = array(
			'imagen_actividad'      => "actividad/".$idActividad."/".$imagen
		);
		$this->db->where(self::TABLE_PK_NAME, $idActividad);
		$this->db->update(self::TABLE_NAME, $datos);
		return $idActividad;
	}
}// Fin de la clase Actividad_model
/* End of file Actividad_model.php */
/* Location: ./application/models/Actividad_model.php */