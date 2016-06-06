<?php

/**
 * Hook que verifica el acceso a los controladores cuando el usuario no está autenticado.
 */
class UsuarioNoAutenticado{

	private $ci;

	private $controladores_permitidos;

	private $metodos_permitidos;

	private $metodos_no_permitidos;

	public function __construct(){
		$this->ci                       =& get_instance();
		$this->controladores_permitidos = ['Usuario','SituacionEnfermeria','Inicio'];
		$this->metodos_permitidos       = [''];
		$this->metodos_no_permitidos    = [
			'cerrar_sesion',
			'perfil'
		];
	}

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
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
			}
		}
	}
} // Cierre clase UsuarioNoAutenticado