<?php
/**
 * Archivo UsuarioAutenticado, contiene la clase para validar el acceso a los controladores y métodos cuando el usuario está autenticado.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase UsuarioAutenticado.
 *
 * Esta clase es un hook que verifica el acceso a los controladores y los métodos de toda la aplicación para validar si el usuario es un usuario autenticado y tiene permisos para acceder a ciertas funciones de la aplicación.
 *
 * @package aplication/hooks
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class UsuarioAutenticado{
	/**
	 * Variable CI que contendrá una instancia del núcleo de codeigniter
	 * @var Object
	 */
	private $ci;
	/**
	 * Variable controladores no permitidos que contendrá la lista de controladores a los que un usuario autenticado no podrá acceder.
	 * @var Array
	 */
	private $controladores_no_permitidos;
	/**
	 * Variable metodos permitidos que contendrá la lista de métodos a los que un usuario autenticado puede acceder aún así no pueda acceder al controlador.
	 * @var Array
	 */
	private $metodos_permitidos;
	/**
	 * Variable metodos no permitidos que contendrá la lista de métodos a los que un usuario autenticado no podrá acceder.
	 * @var [type]
	 */
	private $metodos_no_permitidos;

	/**
	 * Función __construct del hook UsuarioAutenticado.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este hook (UsuarioAutenticado).
	 *
	 * La función inicializa las variables de la clase UsuarioAutenticado para el manejo de ellas.
	 * 
	 * @access public
	 * @return void  
	 */
	public function __construct(){
		$this->ci                                    =& get_instance();
		$this->controladores_no_permitidos['normal'] = [
			'Administrador'
		];

		$this->controladores_no_permitidos['admin']  = [
			'Usuario'
		];

		$this->metodos_permitidos['normal']          = [
		];

		$this->metodos_permitidos['admin']           = [
			'index', // Controlador Usuario
			'cerrar_sesion', // Controlador Usuario
			'perfil', // Controlador Usuario
			'formulario_adicionar_paciente', // Controlador Usuario
			'adicionar_paciente' // Controlador Usuario
		];

		$this->metodos_no_permitidos['normal']       = [
			'formulario_inicio_de_sesion', // Controlador Usuario
			'inicio_de_sesion', // Controlador Usuario
			'formulario_de_registro_de_usuario', // Controlador Usuario
			'registro_de_usuario', // Controlador Usuario
		];

		$this->metodos_no_permitidos['admin']        = [];
	}

	/**
	 * Función verificar_acceso para el hook UsuarioAutenticado.
	 * 
	 * Esta función se encarga de verificar que el usuario autenticado tenga permisos de acceso sobre los controladores y métodos a los que está accediendo.
	 *
	 * @access public
	 * @return void Redirige al perfil del usuario si no tiene permisos para acceder.
	 */
	public function verificar_acceso(){
		$clase   = $this->ci->router->class;
		$metodo  = $this->ci->router->method;
		$session = $this->ci->session->usuario;
		
		// Se verifica que el usuario haya iniciado sesión y exista en la sesión
		// la variable llamada rol_usuario.
		if($session && $session['rol_usuario']){
			// Se obtiene el rol del usuario.
			$rol = $session['rol_usuario'];
			// Se verifica que el controlador al que el usuario intenta acceder se encuentre en
			// los controladores no permitidos.
			if(in_array($clase, $this->controladores_no_permitidos[$rol])){
				// Se verifica que el método al que el usuario intenta acceder
				// no se encuentre en los metodos permitidos del rol para poderlo redireccionar.
				if (!in_array($metodo, $this->metodos_permitidos[$rol])) {
					redirect('Usuario/perfil','refresh');
				}
			}
			// Se verifica que el controlador al que intenta acceder el usuario no se encuentre en
			// los controladores no permitidos
			if (!in_array($clase, $this->controladores_no_permitidos[$rol])) {
				// Verifico que el método al que el usuario intenta acceder se encuentre en
				// los metodos no permitidos del rol, para poderlo redireccionar.
				if (in_array($metodo, $this->metodos_no_permitidos[$rol])) {
					redirect('Usuario/perfil','refresh');
				}
			}
		}
	}
} // Fin de la clase UsuarioAutenticado
/* End of file UsuarioAutenticado.php */
/* Location: ./application/hooks/UsuarioAutenticado.php */