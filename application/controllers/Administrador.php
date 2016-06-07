<?php
/**
 * Archivo Administrador, contiene la clase para manejar la administración de la aplicación
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controlador Administrador el cual contendrá las funciones para administrar la aplicación web.
 * 
 * @package aplication/controllers
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Administrador extends MY_ControladorGeneral {

	/**
	 * Función __construct del controlador Administrador.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (Administrador).
	 * La función ejecuta el constructor de la clase padre (MY_ControladorGeneral).
	 *
	 * @access public
	 * @return void 
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función index del controlador Administrador.
	 * 
	 * Esta función se encarga de redireccionar a: administración de usuarios.
	 * 
	 * @access public
	 * @return void Redirecciona al método administracion_de_usuarios.
	 */
	public function index(){
		redirect('Administrador/administracion-de-usuarios','refresh');
	}

	/**
	 * Función bs_paginación del controlador Administrador.
	 *
	 * Esta función se encarga de adicionar al parámetro config las etiquetas para la paginación en bootstrap.
	 *
	 * @access private
	 * @param  Array $config Arreglo a adicionar las etiquetas para la paginación de bootstrap.
	 * @return Array         Retorna un arreglo con las etiquetas usadas en la paginación de bootstrap.
	 */
	private function bs_paginacion($config){
        /* This Application Must Be Used With BootStrap 3 *  */
		$config['full_tag_open']    = "<ul class='pagination'>";
		$config['full_tag_close']   ="</ul>";
		$config['num_tag_open']     = '<li>';
		$config['num_tag_close']    = '</li>';
		$config['cur_tag_open']     = "<li class='disabled'><li class='active'><a>";
		$config['cur_tag_close']    = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open']    = "<li>";
		$config['next_tagl_close']  = "</li>";
		$config['prev_tag_open']    = "<li>";
		$config['prev_tagl_close']  = "</li>";
		$config['first_tag_open']   = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open']    = "<li>";
		$config['last_tagl_close']  = "</li>";
        return $config;
    }

 	/**
 	 * Función administracion_de_usuarios del controlador Administrador.
	 *
	 * Esta función se encarga de obtener los usuarios y mostrarlos en forma de tabla y paginarlos.
	 *
	 * @access public
 	 * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
 	 * @param  integer $page_number Número de la página a mostrar.
 	 * @return void               	Muestra la página con los usuarios paginados.
 	 */
    public function administracion_de_usuarios($pagina='',$page_number = 1){
    	$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Perfil' => 'Usuario',
		   	'Administración de usuarios'
		));
        $this->load->model('Usuario_model');        
        // Cargo la librería pagination de codeigniter.
        $this->load->library("pagination");
        // configuro la cantidad de registros por página.
        $config["per_page"] = 4;
        $config['use_page_numbers'] = TRUE;
        // Enlace para usar la paginación         
        $config['base_url'] = base_url()."Administrador/administracion-de-usuarios/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config = $this->bs_paginacion($config);
        $page_number = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $identificacion_usuario = $this->session->usuario['identificacion_usuario'];
        $config['total_rows'] = $this->Usuario_model->contar_registros();        
        $usuarios = $this->Usuario_model->obtener_resultados($config["per_page"], $page_number);
        $this->pagination->initialize($config);                
        $data['pagination'] = $this->pagination->create_links();
        $this->load->library('table');
        $this->table->set_empty("---");
        $this->table->set_heading(
        	'Seleccionar',
            'Identificación',
            'Correo',                
            'Fecha de solicitud',
            'Fecha de aceptación',
            'Fecha de denegación',
            'Estado'
        );
        if(count($usuarios)>0){
            foreach ($usuarios as $usuario){
            	$datos = array(
					'name'  => 'seleccionar',
					'id'    => 'seleccionar',
					'class' => 'seleccion',
					'value' => $usuario->idUsuario,
					'type'  => 'radio',
	            );
				$input = form_input($datos);
                $this->table->add_row(
                	array('data' => $input),
                    array('data' => $usuario->identificacion_usuario),
                   	array('data' => $usuario->correo_usuario),          
                    array('data' => $usuario->fecha_solicitud_usuario),
                    array('data' => $usuario->fecha_aceptacion_usuario),
                    array('data' => $usuario->fecha_denegacion_usuario),
                    array('data' =>  ($usuario->estado_usuario == true) ? "Habilitado" : "Inhabilitado" )
                );
            }
            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();
        }else{
        	redirect('Administrador/administracion-de-usuarios','refresh');
        }
        $this->mostrar_pagina('admin/usuario/administracionUsuario', $data);
    }

    /**
     * Función eliminar_usuario del controlador Administrador.
	 *
	 * Esta función se encarga de eliminar un usuario de la base de datos.
	 *
	 * @access public
     * @return void Imprime un objeto JSON dependiendo de lo que se pudo hacer, si no existe nada por post, se redirige a: administracion-de-usuarios. 
     */
    public function eliminar_usuario(){
    	if ($this->input->post('seleccion')) {
    		$idUsuario = $this->input->post('seleccion');
    		$id_usuario_session = $this->session->usuario['identificacion_usuario'];
    		$this->load->model('Usuario_model');
    		$usuario = $this->Usuario_model->obtener_por_id($idUsuario);
    		if ($usuario != null && $usuario->identificacion_usuario != $id_usuario_session) {
    			$respuesta = $this->Usuario_model->eliminar_por_identificacion($usuario->identificacion_usuario);
    			if($respuesta){
    				echo json_encode(array("state" => "success", "message" => "El usuario ha sido eliminado con éxito"));
    				die();
    			}else{
    				echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
    				die();
    			}
    		} else {
    			echo json_encode(array("state" => "error", "message" => "Identificador del usuario no válido."));
    			die();
    		}
    	} else {
    		redirect('Administrador/administracion-de-usuarios','refresh');
    	}
    }

    /**
     * Función formulario_edicion_de_usuario del controlador Administrador.
	 *
	 * Esta función se encarga de mostrar el formulario de edición de un usuario.
	 *
	 * @access public
     * @param  integer $idUsuario identificador único del usuario a editar.
     * @return void            	  Muestra el formulario de edición si existe el usuario, sino, redirecciona a: administracion-de-usuarios.
     */
    public function formulario_edicion_de_usuario($idUsuario){
    	$this->breadcrumb->populate(array(
			'Inicio'                     => '',
			'Perfil'                     => 'Usuario',
			'Administración de usuarios' => 'Administrador/administracion_de_usuarios',
			'Editar usuario'
		));
		$data                      = array();
		$this->load->model('Usuario_model');
		$usuario = $this->Usuario_model->obtener_por_id($idUsuario);
		if($usuario == null){
			redirect('Administrador/administracion-de-usuarios','refresh');
		}
		$data['usuario']           = $usuario;
		$data['titulo']            = "Administración - Editar usuario";
		$data['url_editarusuario'] = "Administrador/editar-usuario";
		$this->mostrar_pagina('admin/usuario/editarUsuario', $data);
    }

    /**
     * Función editar_usuario del controlador Administrador.
	 *
	 * Esta función se encarga de realizar las validaciones antes de editar un usuario en la base de datos
	 *
	 * @access public
     * @return void  Redirecciona a administracion-de-usuarios si encuentra algún error o si ha sido exitosa la actualización.
     */
    public function editar_usuario(){
    	if($this->input->post('submit')){
    		//hacemos las comprobaciones que de nuestro formulario
    		$this->form_validation->set_rules('idUsuario', 'Id usuario', 'trim|required');
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required|max_length[50]|min_length[8]|callback_es_unico[identificacion]');
			$this->form_validation->set_rules('password','Contraseña','trim|max_length[50]|min_length[8]');
			$this->form_validation->set_rules('repetirpassword','Repetir contraseña','matches[password]');
			$this->form_validation->set_rules('correo','Correo electrónico','trim|valid_email|required|callback_es_unico[correo]');
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			$this->form_validation->set_message('valid_email', 'El campo %s no es v&aacute;lido');
			$this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
			$this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
			$this->form_validation->set_message('matches', 'Las contraseñas deben ser iguales.');
			$this->form_validation->set_message('es_unico', 'El valor del campo %s ya existe en el sistema.');
			$idUsuario = $this->security->xss_clean($this->input->post('idUsuario'));
			// Validamos el formulario, si retorna falso cargamos el método formulario_edicion_de_usuario para mostrar los errores ocurridos.
			if (!$this->form_validation->run()){
				$this->formulario_edicion_de_usuario($idUsuario);
			}else{
				$identificacion  = $this->security->xss_clean($this->input->post('identificacion'));
				$password        = $this->security->xss_clean($this->input->post('password'));
				$correo          = $this->security->xss_clean($this->input->post('correo'));
				$this->load->model('Usuario_model');
				$resultado = $this->Usuario_model->editar_usuario($idUsuario, $identificacion, $password, $correo);
				$mensaje         = array();
				if($resultado){
					$mensaje['tipo']    = "success";
					$mensaje['mensaje'] = "Usuario actualizado exitosamente. Identificación: ".$identificacion.", correo: ".$correo;
				}
				else{
					$mensaje['tipo']    = "error";
					$mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
				}
				$this->session->set_flashdata('mensaje', $mensaje);
				redirect('Administrador/administracion-de-usuarios','refresh');
			}
    	}else{
    		redirect('Administrador/administracion-de-usuarios','refresh');
    	}
    }

    /**
     * Función es_unico del controlador Administrador.
	 *
	 * Esta función se encarga de realizar la validación en base de datos sobre un campo, si la operación es 'identificación' validará que la identificación introducida no se encuentre ya registrada en la base de datos, si la operación es 'correo' validará que el correo electrónico introducido no se encuentra ya registrado en la base de datos.
	 *
	 * Función usada por el callback de form validation, en la edición de un usuario.
	 *
	 * @access public
     * @param  mixed $campo     Campo que se desea validar (identificación o correo)
     * @param  string $operacion Operación que se desea hacer (validar identificación o validar correo electrónico)
     * @return boolean            Retorna true si es unico el campo a validar, de lo contrario retorna false.
     */
    public function es_unico($campo, $operacion){
    	$idUsuario = $this->input->post('idUsuario');
    	$this->load->model('Usuario_model');
    	if(trim($campo) != "" && trim($operacion) != "" && trim($idUsuario) != ""){
    		$usuario = null;
    		if($operacion == "identificacion"){
    			$usuario = $this->Usuario_model->obtener_por_identificacion($campo);
    		}else if($operacion == "correo"){
    			$usuario = $this->Usuario_model->obtener_por_correo($campo);
    		}
	    	if(!is_null($usuario)){
	    		if($usuario->idUsuario == $idUsuario){
	    			return true;
	    		}else{
	    			return false;
	    		}
	    	}
	    	return true;
    	}else{
    		return false;
    	}
    }

    /**
     * Función cambiar_estado_usuario del controlador Administrador.
	 *
	 * Esta función se encarga de cambiar el estado de un usuario en la base de datos, el cambio de estado es de: inhabilitado o habilitado.
	 *
	 * @access public
     * @return void 	Imprime un JSON con la respuesta o redirige si no existe nada en post
     */
    public function cambiar_estado_usuario(){
    	if ($this->input->post('seleccion')) {
    		$idUsuario = $this->input->post('seleccion');
    		$operacion = $this->input->post('operacion');
    		$id_usuario_session = $this->session->usuario['identificacion_usuario'];
    		$this->load->model('Usuario_model');
    		$usuario = $this->Usuario_model->obtener_por_id($idUsuario);
    		if ($usuario != null && $usuario->identificacion_usuario != $id_usuario_session && trim($operacion) != "" && ($operacion == "habilitar" || $operacion == "inhabilitar")) {
    			if($operacion == "habilitar" && $usuario->estado_usuario == true){
    				echo json_encode(array("state" => "error", "message" => "El usuario ya se encuentra habilitado.<br>Al pulsar en el botón OK se recargará la página actual."));
    				die();
    			}else if($operacion == "inhabilitar" && $usuario->estado_usuario == false){
    				echo json_encode(array("state" => "error", "message" => "El usuario ya se encuentra inhabilitado.<br>Al pulsar en el botón OK se recargará la página actual."));
    				die();
    			}
    			$respuesta = $this->Usuario_model->cambiar_estado_usuario($operacion, $usuario->idUsuario);
    			if($respuesta){
    				$titulo = "";
    				$mensaje = "";
    				if($operacion == "habilitar"){
    					$titulo = "¡Usuario habilitado con éxito!";
    					$mensaje  = "El usuario ha sido habilitado con éxito.<br>Al pulsar en el botón OK se recargará la página actual.";
    				}else if($operacion == "inhabilitar"){
    					$titulo = "¡Usuario inhabilitado con éxito!";
    					$mensaje  = "El usuario ha sido inhabilitado con éxito.<br>Al pulsar en el botón OK se recargará la página actual.";
    				}
    				echo json_encode(array("state" => "success", "title"=> $titulo, "message" => $mensaje));	
    				die();
    			}else{
    				echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo.<br>Al pulsar en el botón OK se recargará la página actual."));
    				die();
    			}
    		} else {
    			echo json_encode(array("state" => "error", "message" => "Identificador del usuario no válido.<br>Al pulsar en el botón OK se recargará la página actual."));
    			die();
    		}
    	} else {
    		redirect('Administrador/administracion-de-usuarios','refresh');
    	}
    }
} // Fin de la clase Administrador.
/* End of file Administrador.php */
/* Location: ./application/controllers/Administrador.php */