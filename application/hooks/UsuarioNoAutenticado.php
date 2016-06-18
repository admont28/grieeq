<?php
/**
 * Archivo UsuarioNoAutenticado, contiene la clase para validar el acceso a los controladores y métodos cuando el usuario no está autenticado.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase UsuarioNoAutenticado.
 *
 * Esta clase es un hook que verifica el acceso a los controladores y los métodos de toda la aplicación si el usuario es un usuario no autenticado o anónimo.
 *
 * @package aplication/hooks
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class UsuarioNoAutenticado{
	/**
	 * Variable CI que contendrá una instancia del núcleo de codeigniter
	 * @var Object
	 */
	private $ci;
	/**
	 * Variable controladores permitidos que contendrá la lista de controladores a los que un usuario no autenticado podrá acceder.
	 * @var Array
	 */
	private $controladores_permitidos;
	/**
	 * Variable metodos permitidos que contendrá la lista de métodos a los que un usuario no autenticado puede acceder.
	 * @var Array
	 */
	private $metodos_permitidos;
	/**
	 * Variable metodos no permitidos que contendrá la lista de métodos a los que un usuario no autenticado no podrá acceder.
	 * @var [type]
	 */
	private $metodos_no_permitidos;

	/**
	 * Función __construct del hook UsuarioNoAutenticado.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este hook (UsuarioNoAutenticado).
	 *
	 * La función inicializa las variables de la clase UsuarioNoAutenticado para el manejo de ellas.
	 * 
	 * @access public
	 * @return void  
	 */
	public function __construct(){
		$this->ci                       =& get_instance();
		$this->controladores_permitidos = ['Usuario','SituacionEnfermeria','Inicio'];
		$this->metodos_permitidos       = [''];
		$this->metodos_no_permitidos    = [
			'cerrar_sesion',
			'perfil'
		];
	}

	/**
	 * Función verificar_acceso para el hook UsuarioNoAutenticado.
	 * 
	 * Esta función se encarga de verificar que el usuario no autenticado tenga permisos de acceso sobre los controladores y métodos a los que está accediendo.
	 *
	 * @access public
	 * @return void Redirige al inicio de sesión del usuario si no tiene permisos para acceder.
	 */
	public function verificar_acceso(){
		$clase   = $this->ci->router->class;
		$metodo  = $this->ci->router->method;
		$session = $this->ci->session->usuario;
		// Si el usuario no ha iniciado sesión y el controlador al que está accediendo
		// no está dentro de los controladores permitidos.
		if(empty($session) && !in_array($clase,$this->controladores_permitidos)){
			// Verificamos que la función a la que intenta acceder el usuario sin 
			// iniciar sesión no se encuentre entre las funciones permitidas
			if(!in_array($metodo, $this->metodos_permitidos)){	
				// Si es así, se redirige al login.
				$url = $clase."/".$metodo;
				$this->ci->session->set_flashdata('url', $url);
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
			}
		}
		// Si el usuario no ha iniciado sesión y el controlador al que está accediendo
		// se encuentra dentro de los controladores permitidos para el usuario.
		if(empty($session) && in_array($clase, $this->controladores_permitidos)){
			// Se verifica si la función que solicita el usuario se encuentra en las funciones
			// no permitidas para el.
			if (in_array($metodo, $this->metodos_no_permitidos)) {
				// Si es así, se redirige al login.
				$url = $clase."/".$metodo;
				$this->ci->session->set_flashdata('url', $url);
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
			}
		}
	}
} // Fin de la clase UsuarioNoAutenticado
/* End of file UsuarioNoAutenticado.php */
/* Location: ./application/hooks/UsuarioNoAutenticado.php */