<?php

/**
 * Hook que verifica el acceso a los controladores.
 */
class UsuarioAutenticado{

	private $ci;

	private $controladores_no_permitidos;

	private $metodos_permitidos;

	private $metodos_no_permitidos;

	public function __construct(){
		$this->ci                                    =& get_instance();
		$this->controladores_no_permitidos['normal'] = ['Administrador'];
		$this->controladores_no_permitidos['admin']  = ['Usuario'];
		$this->metodos_permitidos['normal']          = [''];
		$this->metodos_permitidos['admin']           = [''];
		$this->metodos_no_permitidos['normal']       = ['formulario_inicio_de_sesion','inicio_de_sesion', 'formulario_de_registro_de_usuario','
registro_de_usuario'];
		$this->metodos_no_permitidos['admin']        = ['formulario_inicio_de_sesion','inicio_de_sesion'];
	}

	public function verificar_acceso(){
		$clase   = $this->ci->router->class;
		$metodo  = $this->ci->router->method;
		$session = $this->ci->session->userdata('usuario');
		
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
					$this->redirigir_segun_rol($rol);
				}
			}
			// Se verifica que el controlador al que intenta acceder el usuario no se encuentre en
			// los controladores no permitidos
			if (!in_array($clase, $this->controladores_no_permitidos[$rol])) {
				// Verifico que el método al que el usuario intenta acceder se encuentre en
				// los metodos no permitidos del rol, para poderlo redireccionar.
				if (in_array($metodo, $this->metodos_no_permitidos[$rol])) {
					$this->redirigir_segun_rol($rol);
				}
			}
		}
	}

	private function redirigir_segun_rol($rol){
		if($rol == "admin")
			redirect('Administrador','refresh');
		else if($rol == "normal")
			redirect('Usuario','refresh');
	}
} // Cierre clase UsuarioAutenticado