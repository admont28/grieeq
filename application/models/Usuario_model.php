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
	/**
	 * Constante que almacenará el nombre de la tabla Usuario.
	 */
	const TABLE_NAME = "Usuario";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Usuario.
	 */
	const TABLE_PK_NAME = "idUsuario";
	/**
	 * Constante que almacenará el SALT para cifrar las contraseñas.
	 * @var [type]
	 */
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

	/**
	 * Función crear_usuario del modelo Usuario_model.
	 *
	 * Esta función se encarga de crear un nuevo usuario en la base de datos.
	 *
	 * @access public
	 * @param  string $identificacion Identificación del usuario a crear.
	 * @param  string $password       Contraseña del nuevo usuario.
	 * @param  string $correo         Correo electrónico del nuevo usuario.
	 * @return boolean                Retorna true si logró insertar el usuario en la base de datos.
	 */
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
		return $this->db->insert(self::TABLE_NAME,$data);
	}

	/**
	 * Función editar_usuario del modelo Usuario_model.
	 *
	 * Esta función se encarga de Editar un usuario en la base de datos.
	 * Si la contraseña está en blanco no se actualizará este campo.
	 *
	 * @access public
	 * @param  integer $idUsuario     Identificador único del usuario editado.
	 * @param  string $identificacion Identificación del usuario editado.
	 * @param  string $password       Contraseña del usuario editado.
	 * @param  string $correo         Correo del usuario editado.
	 * @return boolean                Retorna true si se pudo actualizar el usuario, de lo contrario retorna false.
	 */
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
		$this->db->where(self::TABLE_PK_NAME, $idUsuario);
		return $this->db->update(self::TABLE_NAME, $data);
	}

	/**
	 * Función login del modelo Usuario_model.
	 *
	 * Esta función se encarga de validar el login de la aplicación.
	 *
	 * @access public
	 * @param  string $identificacion Identificación del usuario que inicia sesión.
	 * @param  string $password       Contraseña del usuario que inicia sesión.
	 * @return mixed                  Retorna el usuario si pasa la validación del login, si no retorna null.
	 */
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

	/**
	 * Función contar_registros del modelo Usuario_model.
	 *
	 * Esta función se encarga de contar la cantidad de registros en la tabla Usuario.
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
     * Función obtener_resultados del modelo Usuario_model.
	 *
	 * Esta función se encarga de obtener los usuarios dado cierto limite e inicio.
	 *
	 * @access public
     * @param  integer $limit limite de la consulta.
     * @param  integer $start inicio de la consulta.
     * @return Array          Retorna un arreglo de objetos con los usuarios encontrados.
     */
    public function obtener_resultados($limit=100,$start=0){
        $this->db->select('*');        
        $this->db->from(self::TABLE_NAME);
        $this->db->order_by(self::TABLE_PK_NAME, 'DESC');
        $this->db->limit($limit, $start);    
        $query = $this->db->get();    
        return $query->result();
    }

    /**
     * Función obtener_por_identificacion del modelo Usuario_model.
	 *
	 * Esta función se encarga de obtener un usuario dada su identificación.
	 *
	 * @access public
     * @param  integer $identificacion Identificación única del usuario.
     * @return mixed                   Retorna el usuario si lo encuentra, sino retorna null.
     */
    public function obtener_por_identificacion($identificacion){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    /**
     * Función eliminar_por_identificacion del modelo Usuario_model.
	 *
	 * Esta función se encarga de eliminar un usuario dada su identificación.
	 *
	 * @access public
     * @param  integer $identificacion Identificación única del usuario.
     * @return boolean                 Retorna true si se pudo eliminar, sino retorna false.
     */
    public function eliminar_por_identificacion($identificacion){
    	return $this->db->delete(self::TABLE_NAME, array('identificacion_usuario' => $identificacion));
    }

    /**
     * Función obtener_por_id del modelo Usuario_model.
	 *
	 * Esta función se encarga de obtener un usuario dado su id.
	 *
	 * @access public
     * @param  integer $idUsuario Id único del usuario.
     * @return mixed              Retorna el usuario si lo encuentra, de lo contrario retorna null.
     */
    public function obtener_por_id($idUsuario){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('idUsuario' => $idUsuario));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    /**
     * Función obtener_por_correo del modelo Usuario_model.
	 *
	 * Esta función se encarga de obtener un usuario dado su correo electrónico.
	 *
	 * @access public
     * @param  string $correo_usuario Correo del usuario a obtener.
     * @return mixed                  Retorna el usuario si lo encuentra, de lo contrario retorna null.
     */
    public function obtener_por_correo($correo_usuario){
    	$usuario = $this->db->get_where(self::TABLE_NAME, array('correo_usuario' => $correo_usuario));
    	if($usuario->num_rows() == 1){
			return $usuario->row();
		}
		return null;
    }

    /**
     * Función cambiar_estado_usuario del modelo Usuario_model.
	 *
	 * Esta función se encarga de cambiar el estado de un usuario dependendiendo de la operación, si la operación es habilitar, cambia el estado a habilitado, si la operación es inhabilitar, cambia el estado a inhabilitado, en ambos casos adiciona la fecha de la acción.
	 *
	 * @access public
     * @param  string $operacion Operación a realizar (habilitar o inhabilitar).
     * @param  integer $idUsuario Id único del usuario a cambiar el estado.
     * @return boolean            Retorna true si se pudo cambiar el estado.
     */
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
		$this->db->where(self::TABLE_PK_NAME, $idUsuario);
		$this->db->update(self::TABLE_NAME, $data);
		return true;
    }

}// Fin de la clase TipoHerida_model
/* End of file TipoHerida_model.php */
/* Location: ./application/models/TipoHerida_model.php */