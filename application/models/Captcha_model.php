<?php 
/**
 * Archivo Captcha_model, contiene la clase para manejar la tabla Captcha de la base de datos.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Captcha el cual contendrá las funciones para gestionar la tabla Captcha
 * de la base de datos.
 *
 * @package aplication/models
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Captcha_model extends CI_Model {
	/**
	 * Constante que almacenará el nombre de la tabla Captcha.
	 */
	const TABLE_NAME = "Captcha";
	/**
	 * Constante que almacenará el nombre de la llave primaria de la tabla Captcha.
	 */
	const TABLE_PK_NAME = "idCaptcha";
	
	/**
	 * Función __construct del modelo Captcha_model.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este modelo (Captcha_model).
	 * La función ejecuta el constructor de la clase padre (CI_Model).
	 * La función carga los archivos necesarios para gestionar la base de datos.
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función crear_captcha del modelo Captcha_model.
	 *
	 * Esta función se encarga de insertar en la base de datos la información de un captcha generado.
	 * 
	 * @access public
	 * @param  Array $cap Arreglo que contiene la información del captcha generado por el helper Captcha de CodeIgniter.
	 * @return void      No retorna, solo inserta en base de datos.
	 */
	public function crear_captcha($cap){
		//insertamos el captcha en la bd
		$data = array(
			'time_captcha' => $cap['time'],
			'ip_address_captcha' => $this->input->ip_address(),
			'word_captcha' => $cap['word']
			);
		$query = $this->db->insert_string(self::TABLE_NAME, $data);
		$this->db->query($query);
	}

	/**
	 * Función eliminar_captcha_antiguo del modelo Captcha_model.
	 *
	 * Esta función se encarga de eliminar de la base de datos los captchas que ya hayan expirado.
	 *
	 * @access public
	 * @param  int $expiracion Tiempo de expiración.
	 * @return void            No retorna, solo elimina de la base de datos.
	 */
	public function eliminar_captcha_antiguo($expiracion){
		//eliminamos los registros de la base de datos cuyo 
		//time_captcha sea menor a expiracion
		$this->db->where('time_captcha <',$expiracion);
		$this->db->delete(self::TABLE_NAME);
	}

	/**
	 * Función comprobar del modelo Captcha_model.
	 *
	 * Esta función se encarga de comprobar la validez de un captcha dada la ip del usuario que creo el captcha, el tiempo de expiración del mismo y la palabra que se ingresó.
	 *
	 * @access public
	 * @param  string $ip         Ip del usuario que creo el captcha.
	 * @param  int $expiracion 	  Expiración del captcha.
	 * @param  string $captcha    Palabra que el usuario escribe.
	 * @return int             	  Retorna el numero de filas que coinciden.
	 */
	public function comprobar($ip,$expiracion,$captcha){
		//comprobamos si existe un registro con los datos
		//envíados desde el formulario
		$this->db->where('word_captcha',$captcha);
		$this->db->where('ip_address_captcha',$ip);
		$this->db->where('time_captcha >',$expiracion);
		$query = $this->db->get(self::TABLE_NAME);
		//devolvemos el número de filas que coinciden
		return $query->num_rows();
	}

}// Fin de la clase Captcha_model
/* End of file Captcha_model.php */
/* Location: ./application/models/Captcha_model.php */