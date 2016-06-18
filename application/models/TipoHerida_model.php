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
	 * Función obtenerTiposHerida del modelo TipoHerida_model.
	 *
	 * La función realiza una consulta a la base de datos para obtener todos los tipos de herida almacenados.
	 *
	 * @access public
	 * @return array|boolean     Retorna un arreglo con el resultado de la consulta si existe al menos 1 registro, de lo contrario retorna false.
	 */
	public function obtenerTiposHerida(){
		$query = $this->db->get(self::TABLE_NAME);
		if($query->num_rows() > 0) return $query->result();
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
    	$tipoHerida = $this->db->get_where(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idTipoHerida));
    	if($tipoHerida->num_rows() == 1){
			return $tipoHerida->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_id del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de eliminar un tipo de herida dado su id.
	 * La función recibe un parámetro para indicar si sebe eliminar la imagen asociada al tipo de herida del servidor o si solo debe ser eliminada de la base de datos.
	 *
	 * @access public
     * @param  integer $idTipoHerida Identificación única del tipo de herida.
     * @param boolean $eliminar_imagen Valor booleano para indicar si se debe intentar eliminar la imagen asociada al tipo de herida o solo eliminar el tipo de herida de la base de datos.
     * @return boolean                 Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_id($idTipoHerida, $eliminar_imagen = true){
		$resultado = $this->db->delete(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idTipoHerida));
		if($eliminar_imagen){
			$this->eliminar_directorio("./assets/img/tipoherida/".$idTipoHerida);
		}
		return true;
	}

    /**
     * Función editar_tipo_herida del modelo TipoHerida_model.
     *
     * Esta función se encarga de editar un tipo de herida en la base datos, así como la eliminación de la imagen anterior, si es que ha editado la imagen.
     *
     * @access public
     * @param  integer $idTipoHerida  Identificación única del tipo de herida.
     * @param  string $nombre        Nombre del tipo de herida editado.
     * @param  string $descripcion   Descripción del tipo de herida editado.
     * @param  string $nombre_imagen Nombre de la nueva imagen del tipo de herida, si viene vació no se editará el nombre de la imagen.
     * @return boolean                Retorna true si pudo editar el tipo de herida.
     */
    public function editar_tipo_herida($idTipoHerida, $nombre, $descripcion, $nombre_imagen){
    	if(trim($nombre_imagen) == ""){
    		$data = array(
				'nombre_tipoherida'      => $nombre,
				'descripcion_tipoherida' => $descripcion
    		);
    	}else{
    		$tipoHerida = $this->db->get_where(self::TABLE_NAME, array(self::TABLE_PK_NAME => $idTipoHerida));
	    	if($tipoHerida->num_rows() == 1){
				$tipoHerida = $tipoHerida->row();
    			unlink("./assets/img/".$tipoHerida->imagen_tipoherida);
    			$data = array(
					'nombre_tipoherida'      => $nombre,
					'descripcion_tipoherida' => $descripcion,
					'imagen_tipoherida'      => "tipoherida/".$idTipoHerida."/".$nombre_imagen
	    		);
			}
    	}
    	$this->db->where(self::TABLE_PK_NAME, $idTipoHerida);
		$this->db->update(self::TABLE_NAME, $data);
		return true;
    }

    /**
     * Función eliminar_directorio del modelo TipoHerida_model.
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
	 * Función crear_tipo_herida del modelo TipoHerida_model.
	 *
	 * Esta función se encarga de insertar en la base de datos un nuevo tipo de herida.
	 *
	 * @access public
	 * @param  string $nombre      Nombre del nuevo tipo de herida.
	 * @param  string $descripcion Descripción del nuevo tipo de herida.
	 * @param  string $imagen      Nombre de la imagen asociada al tipo de herida.
	 * @return integer             Retorna el id del tipo de herida insertado.
	 */
	public function crear_tipo_herida($nombre, $descripcion, $imagen){
		$datos = array(
			'nombre_tipoherida'      => $nombre,
			'descripcion_tipoherida' => $descripcion,
			'imagen_tipoherida'      => $imagen
		);
		$this->db->insert(self::TABLE_NAME, $datos);
		$idTipoHerida = $this->db->insert_id();
		$datos = array(
			'imagen_tipoherida'      => "tipoherida/".$idTipoHerida."/".$imagen
		);
		$this->db->where(self::TABLE_PK_NAME, $idTipoHerida);
		$this->db->update(self::TABLE_NAME, $datos);
		return $idTipoHerida;
	}
}// Fin de la clase TipoHerida_model
/* End of file TipoHerida_model.php */
/* Location: ./application/models/TipoHerida_model.php */