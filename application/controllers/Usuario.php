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
	 * La función ejecuta el constructor de la clase padre (CI_Controller).
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
	 * La función muestra la página inicial de la aplicación.
	 *
	 * @access public
	 * @return void No se retorna, se muestra la página.
	 */
	public function index(){
		switch ($this->session->usuario['rol_usuario']) {
			case '':
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
				break;
			case 'admin':
				$this->formulario_de_registro_de_usuario();
				break;
			case 'normal':
				redirect('Usuario/perfil','refresh');
				break;
			default:
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
				break;
		}
	}

	public function formulario_de_registro_de_usuario(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Registro de usuario'
		));
		$data                        = array();
		$data['url_registrousuario'] = "Usuario/registro-de-usuario";
		$data['titulo'] = "Registro de usuario";
		$this->mostrar_pagina('usuario/registroUsuario', $data);
	}

	public function registro_de_usuario(){
		
		if($this->input->post('submit')){
			//hacemos las comprobaciones que deseemos en nuestro formulario
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required|max_length[50]|min_length[8]|is_unique[Usuario.identificacion_usuario]');
			$this->form_validation->set_rules('password','Contraseña','trim|required|max_length[50]|min_length[8]');
			$this->form_validation->set_rules('repetirpassword','Repetir contraseña','trim|required|max_length[50]|min_length[8]|callback_passwords_iguales['.$this->input->post('password').']');
			$this->form_validation->set_rules('correo','Correo electrónico','trim|valid_email|required|is_unique[Usuario.correo_usuario]');
			
			//validamos que se introduzcan los campos requeridos con la función de ci required
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			//validamos el email con la función de ci valid_email
			$this->form_validation->set_message('valid_email', 'El campo %s no es v&aacute;lido');
			//comprobamos que se cumpla el mínimo de caracteres introducidos
			$this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
			//comprobamos que se cumpla el máximo de caracteres introducidos
			$this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
			$this->form_validation->set_message('passwords_iguales', 'Las contraseñas deben ser iguales.');
			$this->form_validation->set_message('is_unique', 'El valor del campo %s ya existe en el sistema.');
			if (!$this->form_validation->run()){
				$this->formulario_de_registro_de_usuario();
			}else{
				$identificacion  = $this->security->xss_clean($this->input->post('identificacion'));
				$password        = $this->security->xss_clean($this->input->post('password'));
				$correo          = $this->security->xss_clean($this->input->post('correo'));
				$this->load->model('Usuario_model');
				$resultado = $this->Usuario_model->crear_usuario($identificacion, $password, $correo);
				$mensaje         = array();
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

	public function formulario_inicio_de_sesion(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Inicio de sesión'
		));
		$data                        = array();
		$data['titulo'] = "Inicio de sesión";
		$data['url_iniciosesion'] = "Usuario/inicio-de-sesion";
		$this->mostrar_pagina('usuario/inicioSesion', $data);
	}

	public function inicio_de_sesion(){
		if($this->input->post('submit')){
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required');
			$this->form_validation->set_rules('password','Contraseña','trim|required');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			if (!$this->form_validation->run()){
				$this->formulario_inicio_de_sesion();
			}else{
				$identificacion  = $this->input->post('identificacion');
				$password        = $this->input->post('password');
				$this->load->model('Usuario_model');
				$resultado = $this->Usuario_model->login($identificacion, $password);
				$mensaje         = array();
				if(!is_null($resultado)){
					$mensaje['tipo']    = "success";
					$mensaje['mensaje'] = "Ha iniciado sesión exitosamente.".$resultado->identificacion_usuario;
					$data = array(
		                'is_logued' 				=> 		TRUE,
		                'identificacion_usuario' 	=> 		$resultado->identificacion_usuario,
		                'rol_usuario'					=>		($resultado->administrador_usuario == true)? "admin" : "normal",
	            		);		
					$this->session->set_userdata('usuario',$data);
					$this->index();
				}
				else{
					$mensaje['tipo']    = "error";
					$mensaje['mensaje'] = "La Identificación o la contraseña no corresponden a un usuario registrado en el sistema.";
				}
				$this->session->set_flashdata('mensaje', $mensaje);
				redirect('Usuario/formulario-inicio-de-sesion','refresh');
			}
		}else{
			redirect('Usuario/formulario-inicio-de-sesion', 'refresh');
		}
	}

	public function cerrar_sesion(){
		$this->session->sess_destroy();
		redirect('Usuario','refresh');
	}

	public function passwords_iguales($repetirpassword, $password){
		if($repetirpassword === $password)
			return true;
		return false;
	}

	public function perfil(){
		echo "Perfil";
	}

} // Fin Clase Inicio
?>