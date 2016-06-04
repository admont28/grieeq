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

	public function login($identificacion, $password){
		$usuario = $this->db->get_where(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
		if($usuario->num_rows() == 1){
			$usuario = $usuario->row();
			if($usuario->estado_usuario == true &&hash_equals($usuario->password_usuario, crypt($password, self::SALT))){
				return $usuario;
			}else{
				return null;
			}
		}
		return null;
	}


} // Fin clase Usuario_model
?>