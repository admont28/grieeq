<?php
/**
 * Archivo Usuario, contiene la clase para manejar los Usuarios de la aplicación.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador Usuario el cual contendrá las funciones para gestionar los usuarios del sistema
 * 
 * @package aplication/controllers
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Usuario extends MY_ControladorGeneral {

	/**
	 * Función __construct del controlador Usuario.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (Usuario).
	 * La función ejecuta el constructor de la clase padre (MY_ControladorGeneral).
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función index para el controlador Usuario.
	 * 
	 * Esta función será ejecutada si no se especifica nada en la URL (ej: URL_APP/Usuario).
	 * La función redirige al metodo perfil si el usuario ha iniciado sesión, sino, es redireccionado al metodo: formulario-inicio-de-sesion
	 *
	 * @access public
	 * @return void No se retorna, se muestra la página.
	 */
	public function index(){
		$session = $this->session->usuario;
		if (isset($session)) {
			redirect('Usuario/perfil','refresh');
		}
		else{
			redirect('Usuario/formulario-inicio-de-sesion','refresh');
		}
	}

	/**
	 * Función formulario_de_registro_de_usuario para el controlador Usuario.
	 * 
	 * Esta función se encarga de mostrar la página para el registro de un nuevo usuario.
	 *
	 * @access public
	 * @return void 	Se muestra la página para el registro de un nuevo usuario.
	 */
	public function formulario_de_registro_de_usuario(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Registro de usuario'
		));
		$data                        = array();
		$data['url_registrousuario'] = "Usuario/registro-de-usuario";
		$data['titulo']              = "Registro de usuario";
		$this->mostrar_pagina('usuario/registroUsuario', $data);
	}

	/**
	 * Función registro_de_usuario para el controlador Usuario.
	 * 
	 * Esta función se encarga de registrar un nuevo usuario en la base de datos, haciendo las validaciones de los campos y mostrando errores si los encuentra.
	 *
	 * @access public
	 * @return void Redirige a formulario-de-registro-de-usuario si todo sale bien o si existe algun error, si los campos no pasan la validación se carga el método: formulario_de_registro_de_usuario mostrando los mensajes asociados.
	 */
	public function registro_de_usuario(){
		if($this->input->post('submit')){
			//hacemos las comprobaciones que deseemos en nuestro formulario
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required|max_length[50]|min_length[8]|is_unique[Usuario.identificacion_usuario]');
			$this->form_validation->set_rules('password','Contraseña','trim|required|max_length[50]|min_length[8]');
			$this->form_validation->set_rules('repetirpassword','Repetir contraseña','trim|required|max_length[50]|min_length[8]|callback_passwords_iguales['.$this->input->post('password').']');
			$this->form_validation->set_rules('correo','Correo electrónico','trim|valid_email|required|is_unique[Usuario.correo_usuario]');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('valid_email', 'El campo %s no es v&aacute;lido');
			$this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
			$this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
			$this->form_validation->set_message('passwords_iguales', 'Las contraseñas deben ser iguales.');
			$this->form_validation->set_message('is_unique', 'El valor del campo %s ya existe en el sistema.');
			if (!$this->form_validation->run()){
				$this->formulario_de_registro_de_usuario();
			}else{
				$identificacion = $this->security->xss_clean($this->input->post('identificacion'));
				$password       = $this->security->xss_clean($this->input->post('password'));
				$correo         = $this->security->xss_clean($this->input->post('correo'));
				$this->load->model('Usuario_model');
				$resultado      = $this->Usuario_model->crear_usuario($identificacion, $password, $correo);
				$mensaje        = array();
				if($resultado){
					$mensaje['tipo']    = "success";
					$mensaje['mensaje'] = "Usuario registrado exitosamente.";
				}
				else{
					$mensaje['tipo']    = "error";
					$mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
				}
				$this->session->set_flashdata('mensaje', $mensaje);
				redirect('Usuario/formulario-de-registro-de-usuario','refresh');
			}
		}else{
			redirect('Usuario/formulario-de-registro-de-usuario', 'refresh');
		}
	}

	/**
	 * Función formulario_inicio_de_sesion para el controlador Usuario.
	 * 
	 * Esta función se encarga de mostrar el formulario para iniciar sesión en la aplicación.
	 *
	 * @access public
	 * @return void 	Muestra la página para iniciar sesión.
	 */
	public function formulario_inicio_de_sesion(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Inicio de sesión'
		));
		$data                     = array();
		$data['titulo']           = "Inicio de sesión";
		$data['url_iniciosesion'] = "Usuario/inicio-de-sesion";
		$this->mostrar_pagina('usuario/inicioSesion', $data);
	}

	/**
	 * Función inicio_de_sesion para el controlador Usuario.
	 * 
	 * Esta función se encarga de iniciar sesión en la aplicación.
	 *
	 * @access public
	 * @return void 	Redirige o muestra mensajes al usuario.
	 */
	public function inicio_de_sesion(){
		if($this->input->post('submit')){
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required');
			$this->form_validation->set_rules('password','Contraseña','trim|required');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			if (!$this->form_validation->run()){
				$this->formulario_inicio_de_sesion();
			}else{
				$identificacion = $this->input->post('identificacion');
				$password       = $this->input->post('password');
				$this->load->model('Usuario_model');
				$resultado      = $this->Usuario_model->login($identificacion, $password);
				$mensaje        = array();
				if(!is_null($resultado)){
					$mensaje['tipo']    = "success";
					$mensaje['mensaje'] = "Ha iniciado sesión exitosamente.".$resultado->identificacion_usuario;
					$data = array(
						'logueado'               => TRUE,
						'identificacion_usuario' => $resultado->identificacion_usuario,
						'rol_usuario'            =>	($resultado->administrador_usuario == true)? "admin" : "normal",
	            		);		
					$this->session->set_userdata('usuario',$data);
					$this->index();
				}
				else{
					$mensaje['tipo']    = "error";
					$mensaje['mensaje'] = "La Identificación o la contraseña no corresponden a un usuario registrado en el sistema o usted no tiene permisos para realizar esta acción.";
				}
				$this->session->set_flashdata('mensaje', $mensaje);
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
			}
		}else{
			redirect('Usuario/formulario-inicio-de-sesion', 'refresh');
		}
	}

	/**
	 * Función cerrar_sesion para el controlador Usuario.
	 * 
	 * Esta función se encarga de destruir la sesión del usuario para cerrar sesión.
	 *
	 * @access public
	 * @return void 	Redirige al index del controlador Usuario.
	 */
	public function cerrar_sesion(){
		$this->session->sess_destroy();
		redirect('Usuario','refresh');
	}

	/**
	 * Función perfil para el controlador Usuario.
	 * 
	 * Esta función se encarga de mostrar la página perfil para el usuario.
	 *
	 * @access public
	 * @return void 	Muestra la página de perfil para el usuario.
	 */
	public function perfil(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Perfil'
		));
		$data                              = array();
		$data['titulo']                    = "Perfil - Lista de pacientes";
		$data['rol']                       = $this->obtener_rol_sesion();
		$data['url_gestiontiposherida']    = "";
		$data['url_gestionfactoresriesgo'] = "";
		$data['url_gestionactividades']    = "";
		$data['url_gestionusuarios']       = "Administrador/administracion-de-usuarios";
		$this->mostrar_pagina('usuario/perfil', $data);	
	}

	/**
	 * Función obtener_rol_sesion para el controlador Usuario.
	 * 
	 * Esta función se encarga de obtener el rol del usuario que ha iniciado sesión en la aplicación.
	 *
	 * @access private
	 * @return mixed Retorna un string admin si el usuario es un administrador, retorna normal si el usuario es convencional, retorna null si no existe la sesión.
	 */
	private function obtener_rol_sesion(){
		$session = $this->session->usuario;
		if(isset($session, $session['logueado'], $session['identificacion_usuario'], $session['rol_usuario'])){
			if($session['rol_usuario'] == "admin")
				return "admin";
			if($session['rol_usuario'] == "normal")
				return "normal";
		}
		return null;
	}

	/**
	 * Función passwords_iguales para el controlador Usuario.
	 * 
	 * Esta función se encarga de verificar que dos contraseñas sean iguales.
	 *
	 * Esta función es llamada por el callback de form validation al momento de registrar un usuario nuevo.
	 *
	 * @access public
	 * @param  string $repetirpassword contraseña que se repite al registrar un usuario.
	 * @param  string $password        constraseña que ingresa al registrar un usuario.
	 * @return boolean                 Retorna true si las contraseñas son iguales, de lo contrario retorna false.
	 */
	public function passwords_iguales($repetirpassword, $password){
		if($repetirpassword === $password)
			return true;
		return false;
	}

} // Fin de la clase Usuario
/* End of file Usuario.php */
/* Location: ./application/controllers/Usuario.php */