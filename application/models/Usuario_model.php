<?php 
/**
 * Archivo Usuario_model, contiene la clase para manejar la tabla Usuario de la base de datos.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Usuario el cual contendrá las funciones para gestionar la tabla Usuario
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Usuario_model extends CI_Model {

	const TABLE_NAME = "Usuario";

	const TABLE_PK_NAME = "idUsuario";

	const SALT = '$6$rounds=5000$H3r1D4sD4v1d¿.!GgrR113Q?%$)(&!uQmnh*:¿,,.1AsasHtRQsqwyDNF$';
	/**
	 * Función __construct del modelo Usuario_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (Usuario_model).
	 * La función ejecuta el constructor de la clase padre (CI_Model).
	 * La función carga los archivos necesarios para gestionar la base de datos.
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
		$this->load->helper('date');
	}

	//creamos la funcion nuevo comentario que será la que haga la inserción a la base
	//de datos pasándole los datos a introducir en forma de array, siempre al estilo ci
	public function crear_usuario($identificacion,$password,$correo){
		$password = crypt($password, self::SALT);
		date_default_timezone_set("America/bogota");
		$time = time();
		$datestring = "%Y-%m-%d";
		$fecha = mdate($datestring,$time);
		$data = array(
				'identificacion_usuario' => $identificacion,
				'correo_usuario' => $correo,
				'password_usuario' => $password,
				'fecha_solicitud_usuario' => $fecha,
				'estado_usuario' => false,
				'administrador_usuario' => false,
				);
		$this->db->insert(self::TABLE_NAME,$data);
		return true;
	}

	public function editar_usuario($idUsuario, $identificacion,$password,$correo){
		if(trim($password) == ""){
			$data = array(
			    'identificacion_usuario' => $identificacion,
			    'correo_usuario' => $correo,
			);
		}else{
			$password = crypt($password, self::SALT);
			$data = array(
			    'identificacion_usuario' => $identificacion,
			    'correo_usuario' => $correo,
			    'password_usuario' => $password
			);
		}
		$this->db->where('idUsuario', $idUsuario);
		$this->db->update(self::TABLE_NAME, $data);
		return true;
	}

	public function login($identificacion, $password){
		$usuario = $this->db->get_where(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
		if($usuario->num_rows() == 1){
			$usuario = $usuario->row();
			if($usuario->estado_usuario == true && hash_equals($usuario->password_usuario, crypt($password, self::SALT))){
				return $usuario;
			}else{
				return null;
			}
		}
		return null;
	}

	public function contar_registros(){
        $this->db->select('*');    
        $this->db->from(self::TABLE_NAME);
        return $this->db->count_all_results();
    }
    
    public function obtener_resultados($limit=100,$start=0){
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $this->db->order_by(self::TABLE_PK_NAME, 'ASC');
        $this->db->limit($limit, $start);    
        $query = $this->db->get();    
       
        return $query->result();
    }

    public function obtener_por_identificacion($identificacion){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    public function eliminar_por_identificacion($identificacion){
    	return $this->db->delete(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
    }

    public function obtener_por_id($idUsuario){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('idUsuario' => $idUsuario));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    public function obtener_por_correo($correo_usuario){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('correo_usuario' => $correo_usuario));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    public function cambiar_estado_usuario($operacion, $idUsuario){
    	date_default_timezone_set("America/bogota");
		$time = time();
		$datestring = "%Y-%m-%d";
		$fecha = mdate($datestring,$time);
		if($operacion == "habilitar"){
			$data = array(
			    'estado_usuario' => true,
			    'fecha_aceptacion_usuario' => $fecha,
			);
		}else if($operacion == "inhabilitar"){
			$data = array(
			    'estado_usuario' => false,
			    'fecha_denegacion_usuario' => $fecha,
			);
		}
		$this->db->where('idUsuario', $idUsuario);
		$this->db->update(self::TABLE_NAME, $data);
		return true;
    }

} // Fin clase Usuario_model
?>