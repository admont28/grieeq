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
	 * La función carga la librería table para poder ser usada en todo el controlador.
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
		// Se carga el helper captcha
		$this->load->helper('captcha');
		// Se carga el helper string para poder hacer uso de los métodos random_string entre otros.
		$this->load->helper('string');
		// Se carga el modelo Captcha model
		$this->load->model('Captcha_model');
		//creamos un random alfanumerico de longitud 6 
		//para nuestro captcha y sesión captcha
		$this->rand = random_string('alnum', 6);
		// Se carga la librería table para mostrar la info básica del paciente en forma de tabla.
		$this->load->library('table');
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
		// Pasamos a la vista el captcha que se ha creado.
		$data['captcha'] = $this->captcha();
		//creamos una sesión con el string del captcha que hemos creado
		//para utilizarlo en la función callback
		$this->session->set_userdata('captcha', $this->rand);
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
			$this->form_validation->set_rules('captcha', 'Captcha', 'callback_validar_captcha');
			$this->form_validation->set_rules('identificacion','Identificación','trim|required|max_length[50]|min_length[8]|is_unique[Usuario.identificacion_usuario]');
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
				$expiracion = time()-600; // Límite de 10 minutos 
				$ip = $this->input->ip_address();//ip del usuario
				$captcha = $this->input->post('captcha');//captcha introducido por el usuario
				//eliminamos los captcha con más de 2 minutos de vida
				$this->Captcha_model->eliminar_captcha_antiguo($expiracion);
				//comprobamos si es correcta la imagen introducida
				$validacion = $this->Captcha_model->comprobar($ip,$expiracion,$captcha);
				/*
				|si el número de filas devuelto por la consulta es igual a 1
				|es decir, si el captcha ingresado en el campo de texto es igual
				|al que hay en la base de datos, junto con la ip del usuario 
				|entonces dejamos continuar porque todo es correcto
				*/
				if($validacion == 1)
				{
					$identificacion = $this->security->xss_clean($this->input->post('identificacion'));
					$password       = $this->security->xss_clean($this->input->post('password'));
					$correo         = $this->security->xss_clean($this->input->post('correo'));
					$this->load->model('Usuario_model');
					$resultado      = $this->Usuario_model->crear_usuario($identificacion, $password, $correo);
					$mensaje        = array();
					if($resultado){
						$mensaje['tipo']    = "success";
						$mensaje['mensaje'] = "Usuario registrado exitosamente. El administrador del sistema habilitará o inhabilitará su inicio de sesión.";
					}
					else{
						$mensaje['tipo']    = "error";
						$mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
					}
					$this->session->set_userdata('mensaje', $mensaje);
					$this->session->mark_as_flash('mensaje');
                	redirect('Usuario/formulario-de-registro-de-usuario');
                }else{
					$mensaje['tipo']    = "error";
					$mensaje['mensaje'] = "La imagen ha expirado, debe escribir el texto que aparece en la nueva imagen y presionar el botón Registrarme.";
					$this->session->set_flashdata('mensaje', $mensaje);
					$this->formulario_de_registro_de_usuario();
				}
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
			$this->form_validation->set_rules('identificacion','Identificación','trim|required');
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
						'idUsuario'			 	 => $resultado->idUsuario,
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
	 * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
 	 * @param  integer $page_number Número de la página a mostrar.
	 * @return void    Muestra la página de perfil para el usuario con los pacientes que tenga registrados.
	 */
	public function perfil($pagina='',$page_number = 1){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Perfil'
		));
        $this->load->model('Paciente_model');        
        // Cargo la librería pagination de codeigniter.
        $this->load->library("pagination");
		$data                       = array();
		// configuro la cantidad de registros por página.
		$config["per_page"]         = 4;
		$config['use_page_numbers'] = TRUE;
		$config["uri_segment"]      = 4;
        // Enlace para usar la paginación         
        $config['base_url']         = base_url()."Usuario/perfil/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config                     = $this->bs_paginacion($config);
        $session 					= $this->session->usuario; 
        $page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $identificacion_usuario     = $this->session->usuario['identificacion_usuario'];
        $config['total_rows']       = $this->Paciente_model->contar_registros($session['idUsuario']);   
            
        $pacientes                  = $this->Paciente_model->obtener_resultados($config["per_page"], $page_number, $session['idUsuario']);
        $this->pagination->initialize($config);      
        $data['pagination']         = $this->pagination->create_links();
        $this->load->library('table');
        $this->table->set_empty("---");
        $this->table->set_heading(
        	'Seleccionar',
            'Nombre',
            'Identificación',                
            'Edad',
            'Sexo',
            'Diagnóstico'
        );
        if(count($pacientes)>0){
            foreach ($pacientes as $paciente){
            	$datos = array(
					'name'  => 'seleccionar',
					'id'    => 'seleccionar',
					'class' => 'seleccion',
					'value' => $paciente->idPaciente,
					'type'  => 'radio',
	            );
				$input = form_input($datos);
                $this->table->add_row(
                	array('data' => $input),
                    array('data' => $paciente->nombre_paciente),
                   	array('data' => $paciente->identificacion_paciente),          
                    array('data' => $paciente->edad_paciente),
                    array('data' => $paciente->sexo_paciente),
                    array('data' => $paciente->diagnostico_paciente)
                );
            }
            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();
        }	
		$data['titulo']                           = "Perfil - Lista de pacientes";
		$data['rol']                              = $this->obtener_rol_sesion();
		$data['url_adicionarpaciente']            = "Usuario/formulario-adicionar-paciente";
		$data['url_editarpaciente']               = "Usuario/formulario-edicion-de-paciente";
		$data['url_adicionarsituacionenfermeria'] = "SituacionEnfermeria/index";
		$data['url_historialsituacionenfermeria'] = "Usuario/historial-paciente";
		$data['url_exportarhistorial']			  = "Usuario/exportar-historial-de-paciente";
		$data['url_gestiontiposherida']           = "Administrador/administracion-de-tipos-de-heridas";
		$data['url_gestionfactoresriesgo']        = "Administrador/administracion-de-factores-de-riesgo";
		$data['url_gestionactividades']           = "Administrador/administracion-de-actividades";
		$data['url_gestionusuarios']              = "Administrador/administracion-de-usuarios";
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

	/**
	 * Función captcha para el controlador Usuario.
	 *
	 * Esta función se encarga de crear el captcha con el helper Captcha de CodeIgniter, además
	 * llama al modelo Captcha_model para insertar el captcha creado en la base de datos.
	 *
	 * @access private
	 * @return array Retorna un array con el captcha creado.
	 */
	private function captcha(){
		//configuramos el captcha
		$conf_captcha = array(
			'word'   => $this->rand,
			'img_path' => './assets/img/captcha/',
			'img_url' =>  asset_url('img/captcha/'),
			'font_path' =>'./assets/fonts/AlfaSlabOne-Regular.ttf',
			'img_width' => '250',
			'img_height' => '50', 
			//decimos que pasados 10 minutos elimine todas las imágenes
			//que sobrepasen ese tiempo
			'expiration' => 600 
		);
		//guardamos la info del captcha en $cap
		$cap = create_captcha($conf_captcha);
		//pasamos la info del captcha al modelo para 
		//insertarlo en la base de datos
		$this->Captcha_model->crear_captcha($cap);
		//devolvemos el captcha para utilizarlo en la vista
		return $cap;
	}

	/**
	 * Función validar_captcha para el controlador Usuario.
	 *
	 * Esta función se encarga de validar la palabra del captcha introducida por el usuario contra el captcha almacenado en la sesión.
	 * Esta función es llamada por el callback al momento de registrar un nuevo usuario en el sistema.
	 *
	 * @access public
	 * @return boolean Retorna true si el captcha es igual al captcha guardado en la sesión.
	 */
	public function validar_captcha(){
	    if($this->input->post('captcha') != $this->session->userdata('captcha')){
	        $this->form_validation->set_message('validar_captcha', 'El texto de la imagen no coincide con el ingresado.');
	        return false;
	    }else{
	        return true;
	    }
	}

	/**
	 * Función formulario_adicionar_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de mostrar el formulario para adicionar un paciente en la base de datos.
	 *
	 * @access public
	 * @return void No retorna, muestra la página con el formulario para adicionar un paciente.
	 */
    public function formulario_adicionar_paciente(){
    	$this->breadcrumb->populate(array(
			'Inicio' => '',
			'Perfil' => 'Usuario',
			'Adicionar paciente'
        ));
		$data                            = array();
		$data['url_adicionarpaciente'] 	 = "Usuario/adicionar-paciente";
		$data['titulo']                  = "Adicionar paciente";
        $this->mostrar_pagina('paciente/adicionarPaciente', $data);
    }

    /**
     * Función adicionar_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de adicionar un paciente en la base de datos, haciendo las validaciones antes de agregarlo.
	 *
	 * @access public
     * @return void No retorna, Redirige a otra página mostrando un mensaje de éxito, o muestra los mensajes de error si existieron.
     */
    public function adicionar_paciente(){
    	if($this->input->post('submit')){
            //hacemos las comprobaciones que de nuestro formulario;
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('identificacion','Identificación','trim|required|max_length[45]|min_length[5]');
            $this->form_validation->set_rules('edad','Edad','trim|required|is_natural_no_zero|max_length[200]|min_length[0]');
            $this->form_validation->set_rules('sexo','Sexo','trim|required|in_list[M,F]');
            $this->form_validation->set_rules('diagnostico','Diagnóstico','trim|required|max_length[1000]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos de %s car&aacute;cteres');
            $this->form_validation->set_message('is_natural_no_zero', 'El campo %s debe ser mayor a cero.');
            $this->form_validation->set_message('in_list', 'El campo %s debe ser Masculino o Femenino');
            // Validamos el formulario, si retorna falso cargamos el método formulario_adicionar_paciente para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_adicionar_paciente();
            }else{
				$nombre         = $this->security->xss_clean($this->input->post('nombre'));
				$identificacion = $this->security->xss_clean($this->input->post('identificacion'));
				$edad           = $this->security->xss_clean($this->input->post('edad'));
				$sexo           = $this->security->xss_clean($this->input->post('sexo'));
				$diagnostico    = $this->security->xss_clean($this->input->post('diagnostico'));
				$session        = $this->session->usuario;
				$this->load->model('Paciente_model');
				$resultado      = $this->Paciente_model->crear_paciente($nombre, $identificacion, $edad, $sexo, $diagnostico, $session['idUsuario']);
				$mensaje        = array();
                if($resultado){
                    $mensaje['tipo']    = "success";
                    $mensaje['mensaje'] = "El paciente ha sido adicionado con éxito. Nombre: ".$nombre;
                }
                else{
                    $mensaje['tipo']    = "error";
                    $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                }
                $this->session->set_flashdata('mensaje', $mensaje);
                redirect('Usuario/perfil','refresh');
            }
        }else{
            redirect('Usuario/formulario-adicionar-paciente','refresh');
        }
    }

    /**
     * Función eliminar_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de eliminar (dar de alta) a un paciente dado de la base de datos.
	 *
	 * @access public
     * @return void Imprime un objeto JSON dependiendo de lo que se pudo hacer, si no existe nada por post, se redirige a: perfil.
     */
    public function eliminar_paciente(){
    	if ($this->input->post('seleccion')) {
            $idPaciente          = $this->input->post('seleccion');
            $this->load->model('Paciente_model');
            $paciente            = $this->Paciente_model->obtener_por_id($idPaciente);
    		if ($paciente != null) {
    			$respuesta = $this->Paciente_model->eliminar_por_id($paciente->idPaciente);
    			if($respuesta){
    				echo json_encode(array("state" => "success", "message" => "El paciente ha sido eliminado con éxito"));
    				die();
    			}else{
    				echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
    				die();
    			}
    		} else {
    			echo json_encode(array("state" => "error", "message" => "Identificador del paciente no válido."));
    			die();
    		}
    	} else {
    		redirect('Usuario/perfil','refresh');
    	}
    }

    /**
	 * Función formulario_edicion_de_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de mostrar el formulario para editar un paciente en la base de datos.
	 *
	 * @access public
	 * @param integer $idPaciente Identificador único del paciente.
	 * @return void No retorna, muestra la página con el formulario para editar un paciente.
	 */
    public function formulario_edicion_de_paciente($idPaciente){
    	$this->breadcrumb->populate(array(
			'Inicio' => '',
			'Perfil' => 'Usuario',
			'Editar paciente'
        ));
		$data                            = array();
		$this->load->model('Paciente_model');
		$paciente = $this->Paciente_model->obtener_por_id($idPaciente);
		if($paciente == null){
            $mensaje['tipo']    = "error";
            $mensaje['mensaje'] = "Identificador de paciente no válido.";
            $this->session->set_flashdata('mensaje', $mensaje);
			redirect('Usuario/perfil','refresh');
		}
		$data['paciente']           	 = $paciente;
		$data['url_editarpaciente'] 	 = "Usuario/editar-paciente";
		$data['titulo']                  = "Editar paciente";
        $this->mostrar_pagina('paciente/editarPaciente', $data);
    }

    /**
     * Función editar_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de editar un paciente en la base de datos, haciendo las validaciones antes de actualizarlo.
	 *
	 * @access public
     * @return void No retorna, Redirige a otra página mostrando un mensaje de éxito, o muestra los mensajes de error si existieron.
     */
    public function editar_paciente(){
    	if($this->input->post('submit')){
            //hacemos las comprobaciones que de nuestro formulario;
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('identificacion','Identificación','trim|required|max_length[45]|min_length[5]');
            $this->form_validation->set_rules('edad','Edad','trim|required|is_natural_no_zero|max_length[200]|min_length[0]');
            $this->form_validation->set_rules('sexo','Sexo','trim|required|in_list[M,F]');
            $this->form_validation->set_rules('diagnostico','Diagnóstico','trim|required|max_length[1000]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos de %s car&aacute;cteres');
            $this->form_validation->set_message('is_natural_no_zero', 'El campo %s debe ser mayor a cero.');
            $this->form_validation->set_message('in_list', 'El campo %s debe ser Masculino o Femenino');
            $idPaciente = $this->security->xss_clean($this->input->post('idPaciente'));
            // Validamos el formulario, si retorna falso cargamos el método formulario_adicionar_paciente para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_adicionar_paciente();
            }else{
				$nombre         = $this->security->xss_clean($this->input->post('nombre'));
				$identificacion = $this->security->xss_clean($this->input->post('identificacion'));
				$edad           = $this->security->xss_clean($this->input->post('edad'));
				$sexo           = $this->security->xss_clean($this->input->post('sexo'));
				$diagnostico    = $this->security->xss_clean($this->input->post('diagnostico'));
				$session        = $this->session->usuario;
				$this->load->model('Paciente_model');
				$resultado = $this->Paciente_model->editar_paciente($idPaciente, $nombre, $identificacion, $edad, $sexo, $diagnostico, $session['idUsuario']);
				$mensaje        = array();
                if($resultado){
                    $mensaje['tipo']    = "success";
                    $mensaje['mensaje'] = "El paciente ha sido editado con éxito. Nombre: ".$nombre;
                }
                else{
                    $mensaje['tipo']    = "error";
                    $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                }
                $this->session->set_flashdata('mensaje', $mensaje);
                redirect('Usuario/perfil','refresh');
            }
        }else{
            redirect('Usuario/formulario-edicion-de-paciente','refresh');
        }
    }

    /**
     * Función historial_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de obtener el historial de un paciente dado y mostrarlo al usuario.
	 *
	 * @access public
     * @param  integer $idPaciente  Identificación única del paciente.
     * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
 	 * @param  integer $page_number Número de la página a mostrar.
	 * @return void    				No retorna nada, muestra la página con las situaciones de enfermería del paciente pasado por parámetro.
     */
    public function historial_paciente($idPaciente = 0, $pagina='', $page_number = 1){
    	if($idPaciente != 0){
    		$this->breadcrumb->populate(array(
				'Inicio' => '',
				'Perfil' => 'Usuario',
				'Historial del paciente'
	        ));
			$this->load->model('Paciente_model');
			$data     = array();
			$paciente = $this->Paciente_model->obtener_por_id($idPaciente);
			if($paciente == null){
	            $mensaje['tipo']    = "error";
	            $mensaje['mensaje'] = "Identificador de paciente no válido.";
	            $this->session->set_flashdata('mensaje', $mensaje);
				redirect('Usuario/perfil','refresh');
			}
			$session 		  = $this->session->usuario; 
			$validar_paciente = $this->Paciente_model->validar_paciente($idPaciente, $session['idUsuario']);
			if($validar_paciente){
				$this->load->model('SituacionEnfermeria_model');
		        // Cargo la librería pagination de codeigniter.
		        $this->load->library("pagination");
				$data                       = array();
				// configuro la cantidad de registros por página.
				$config["per_page"]         = 4;
				$config['use_page_numbers'] = TRUE;
				$config["uri_segment"]      = 5;
				// Enlace para usar la paginación         
				$config['base_url']         = base_url()."Usuario/historial-paciente/".$idPaciente."/pagina/";
				// Adición del html de bootstrap a la variable de configuración
				$config                     = $this->bs_paginacion($config);
				$page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
				$config['total_rows']       = $this->SituacionEnfermeria_model->contar_registros($idPaciente, $session['idUsuario']);
				$situacionesenfermeria      = $this->SituacionEnfermeria_model->obtener_resultados($idPaciente, $config["per_page"], $page_number);
		        $this->pagination->initialize($config);      
		        $data['pagination']         = $this->pagination->create_links();
		        $this->load->library('table');
		        $this->table->set_empty("---");
		        $this->table->set_heading(
		        	'Seleccionar',
		            'Identificador',
		            'Observaciones'
		        );
		        if(count($situacionesenfermeria)>0){
		        	$i = 1;
		            foreach ($situacionesenfermeria as $se){
		            	$datos = array(
							'name'  => 'seleccionar',
							'id'    => $se->idSituacionEnfermeria,
							'class' => 'seleccion',
							'value' => $se->idSituacionEnfermeria,
							'type'  => 'radio',
			            );
						$input = form_input($datos);
		                $this->table->add_row(
		                	array('data' => $input),
		                    array('data' => $se->idSituacionEnfermeria),
		                   	array('data' => $se->observaciones_situacionenfermeria)
		                );
		                $i++;
		            }
		            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
		            $this->table->set_template($tmpl);
		            $data['table'] = $this->table->generate();
		        }	
				$data['paciente'] = $paciente;
				$data['titulo']   = "Historial del paciente: ".$paciente->nombre_paciente;
				$data['url_exportarhistorial'] = "Usuario/exportar-historial-de-paciente";
		        $this->mostrar_pagina('paciente/historialPaciente', $data);
			}else{
				$mensaje['tipo']    = "error";
	            $mensaje['mensaje'] = "Identificador de paciente no válido.";
	            $this->session->set_flashdata('mensaje', $mensaje);
				redirect('Usuario/perfil','refresh');
    		}
		}else{
			redirect('Usuario/perfil','refresh');
		}
    }

    /**
     * Función historial_paciente del controlador Usuario.
	 *
	 * Esta función se encarga de obtener el historial de un paciente dado y exportarlo a un documento en formato .docx
	 *
	 * @access public
     * @param  integer $idPaciente Identificador único del paciente.
     * @return void                No retorna nada, presenta la descarga del documento .docx
     */
    public function exportar_historial_de_paciente($idPaciente = 0){
    	if($idPaciente != 0){
    		$this->load->model('Paciente_model');
    		$paciente = $this->Paciente_model->obtener_por_id($idPaciente);
    		$session 		  = $this->session->usuario; 
			$validar_paciente = $this->Paciente_model->validar_paciente($idPaciente, $session['idUsuario']);
    		if(!is_null($paciente) && $validar_paciente){
    			// Cargamos la librería creada para manejar PHPWord.
	    		$this->load->library('word');
	    		$sectionStyle = array(
					'marginTop'    => 1000,
					'marginRight'  => 1000,
					'marginBottom' => 1000,
					'marginLeft'   => 1000
				);
				// Adicionamos una sección al documento.
				$section = $this->word->addSection($sectionStyle);
				// Creamos los estilos para el título 1.
				$fontStyle1 = array('name' => 'Cambria', 'size' => 16, 'bold' => true);
				// Configuramos el estilo del título 1.
				$this->word->addTitleStyle(1, $fontStyle1);
				// Creamos los estilos para el título 2.
				$fontStyle2 = array('name' => 'Cambria', 'size' => 14, 'bold' => true);
				$paragraphStyle2 = array('align' => 'both', 'indent' => 1);
				// Configuramos el estilo del título 2.
				$this->word->addTitleStyle(2, $fontStyle2, $paragraphStyle2);
				// Creamos los estilos para el título 3.
				$fontStyle3 = array('name' => 'Cambria', 'size' => 12, 'bold' => false);
				$paragraphStyle3 = array('align' => 'both', 'indent' => 2);
				// Configuramos el estilo del título 3.
				$this->word->addTitleStyle(3, $fontStyle3,$paragraphStyle3);
				//$section->addTOC();
				// Adicionamos un título 1.
				$section->addTitle('Historial del paciente: '.$paciente->nombre_paciente, 1);
				// Adicionamos un salto de línea.
				$section->addTextBreak(1);
				// Adicionamos un título 2.
				$section->addTitle('Información básica del paciente:', 2);
				$section->addText("Nombre: ".$paciente->nombre_paciente, array('name' => 'Cambria'), array('align' => 'both', 'indent' => 2));
				$section->addText("Identificación: ".$paciente->identificacion_paciente, array('name' => 'Cambria'), array('align' => 'both', 'indent' => 2));
				$section->addText("Edad: ".$paciente->edad_paciente, array('name' => 'Cambria'), array('align' => 'both', 'indent' => 2));
				$sexo_paciente = $paciente->sexo_paciente == "M" ? "Masculino" : "Femenino";
				$section->addText("Sexo: ".$sexo_paciente, array('name' => 'Cambria'), array('align' => 'both', 'indent' => 2));
				$section->addText("Diagnóstico: ".$paciente->diagnostico_paciente, array('name' => 'Cambria'), array('align' => 'both', 'indent' => 2));
				// Adicionamos un salto de línea.
				$section->addTextBreak(1);

				$this->load->model('SituacionEnfermeria_model');
				$this->load->model('Localizacion_model');
				$this->load->model('TipoHerida_model');
				$this->load->model('FactorRiesgo_model');
				$this->load->model('Actividad_model');
				$situacionesEnfermeria = $this->SituacionEnfermeria_model->obtener_por_paciente($idPaciente);
				$paragraphStyle = array('align' => 'both', 'indent' => 3);
				$i = 1;
				foreach ($situacionesEnfermeria as $se) {
					$section->addTitle('Situación de enfermería: '.$i, 2);
					$section->addText("Observaciones: ".$se->observaciones_situacionenfermeria, array('name' => 'Cambria'), array('indent' => 2));
					$section->addTextBreak(1);
					/*
					 * ADICIONO LA INFORMACIÓN DE LA LOCALIZACIÓN ANATÓMICA DE LA HERIDA.
					 */
					$localizacion = $this->Localizacion_model->obtener_por_id($se->Localizacion_idLocalizacion);
					$section->addTitle('Localización anatómica de la herida:', 3);
					$nombre_localizacion = "Sin localización anatómica.";
					if(isset($localizacion) && sizeof($localizacion) == 1)
						$nombre_localizacion = $localizacion[0]->nombre_localizacion;
					$section->addText("Nombre: ".$nombre_localizacion, array('name' => 'Cambria'), $paragraphStyle);

					/*
					 * ADICIONO LA INFORMACIÓN DEL TIPO DE HERIDA.
					 */
					$tipoHerida = $this->TipoHerida_model->obtener_por_id($se->TipoHerida_idTipoHerida);
					$section->addTitle('Tipo de herida:', 3);
					$nombre_tipoherida = "Sin nombre de tipo de herida.";
					$descripcion_tipoherida = "Sin descripción en el tipo de herida.";
					if (isset($tipoHerida, $tipoHerida->nombre_tipoherida, $tipoHerida->descripcion_tipoherida)) {
						$nombre_tipoherida      = $tipoHerida->nombre_tipoherida;
						$descripcion_tipoherida = $tipoHerida->descripcion_tipoherida;
					}
					$section->addText("Nombre: ".$nombre_tipoherida, array('name' => 'Cambria'), $paragraphStyle);
					$section->addText("Descripción: ".$descripcion_tipoherida, array('name' => 'Cambria'), $paragraphStyle);

					/*
					 * ADICIONO LA INFORMACIÓN DE LOS FACTORES DE RIESGO
					 */
					$SituacionEnfermeriaFactoresRiesgo = $this->SituacionEnfermeria_model->obtener_factores_de_riesgo($se->idSituacionEnfermeria);
					$i_factorriesgo = 1;
		            foreach ($SituacionEnfermeriaFactoresRiesgo as $sefr) {
		                $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($sefr->FactorRiesgo_idFactorRiesgo);
		                $section->addTitle('Factor de riesgo: '.$i_factorriesgo, 3);
		                $section->addText("Nombre: ".$factorRiesgo->nombre_factorriesgo, array('name' => 'Cambria'), $paragraphStyle);
		                $section->addText("Descripción: ".$factorRiesgo->descripcion_factorriesgo, array('name' => 'Cambria'), $paragraphStyle);
		                $section->addText("Ejemplo: ".$factorRiesgo->ejemplo_factorriesgo, array('name' => 'Cambria'), $paragraphStyle);
		                $i_factorriesgo++;
		            }

		            /*
					 * ADICIONO LA INFORMACIÓN DE LAS ACTIVIDADES SUGERIDAS.
					 */
		            $SituacionEnfermeriaActividades = $this->SituacionEnfermeria_model->obtener_actividades($se->idSituacionEnfermeria);
		            $i_actividad = 1;
		            foreach ($SituacionEnfermeriaActividades as $sea) {
		                $actividad = $this->Actividad_model->obtener_por_id($sea->Actividad_idActividad);
		                $section->addTitle('Actividad: '.$i_actividad, 3);
		                $section->addText("Nombre: ".$actividad->nombre_actividad, array('name' => 'Cambria'), $paragraphStyle);
		                $section->addText("Descripción: ".$actividad->descripcion_actividad, array('name' => 'Cambria'), $paragraphStyle);
		                $texto_precaucion = ($actividad->precaucion_actividad == "") ? "Ninguna." : $actividad->precaucion_actividad;
		                $section->addText("Precaución: ".$texto_precaucion, array('name' => 'Cambria'), $paragraphStyle);
		                $i_actividad++;
		            }
		            $section->addTextBreak(1);
					$i++;
				}// Cierre foreach de situaciones de enfermería.
				// Configuramos el nombre con el que se guardará el documento.
				$filename = "historialpaciente_".$paciente->identificacion_paciente.".docx";
				// Configuramos las cabeceras de la petición para indicar que se descargará un archivo word.
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
				header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
				header('Cache-Control: max-age=0'); //no cache
				// Creamos el documento word con la fabrica IOFactory de la librería PHPWord
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->word, 'Word2007');
				// Guardamos el documento.
				$objWriter->save('php://output');
    		}
    	}else{
    		redirect('Usuario/perfil','refresh');
    	}
    }
} // Fin de la clase Usuario
/* End of file Usuario.php */
/* Location: ./application/controllers/Usuario.php */