<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

	public function index()
	{
		
	}

	/*public function administracion_de_usuarios(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Perfil' => 'Usuario',
		   	'Administración de usuarios'
		));
		$data = array();
		$this->mostrar_pagina('admin/usuario/administracionUsuario', $data);	
	}*/

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
 
    public function administracion_de_usuarios($pagina='',$page_number = 1){
    	$this->breadcrumb->populate(array(
		    'Inicio' => '',
		   	'Perfil' => 'Usuario',
		   	'Administración de usuarios'
		));
        $this->load->model('Usuario_model');        
            
        //Pagination
        $this->load->library("pagination");
        //Set config options
        $config["per_page"] = 4;
        $config['use_page_numbers'] = TRUE;            
        $config['base_url'] = base_url()."Administrador/administracion-de-usuarios/pagina/";//Link to use for pagination
        //Add bootstrap html to config
        $config = $this->bs_paginacion($config);
        //fix request for usuarios for page number use
        $page_number = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $identificacion_usuario = $this->session->usuario['identificacion_usuario'];
        $config['total_rows'] = $this->Usuario_model->contar_registros();        
        $usuarios = $this->Usuario_model->obtener_resultados($config["per_page"], $page_number, $identificacion_usuario);

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

    public function editar_usuario(){
    	if($this->input->post('submit')){
    		//hacemos las comprobaciones que deseemos en nuestro formulario
    		$this->form_validation->set_rules('idUsuario', 'Id usuario', 'trim|required');
			$this->form_validation->set_rules('identificacion','Identificacion','trim|required|max_length[50]|min_length[8]|callback_es_unico[identificacion]');
			$this->form_validation->set_rules('password','Contraseña','trim|max_length[50]|min_length[8]');
			$this->form_validation->set_rules('repetirpassword','Repetir contraseña','matches[password]');
			$this->form_validation->set_rules('correo','Correo electrónico','trim|valid_email|required|callback_es_unico[correo]');
			
			//validamos que se introduzcan los campos requeridos con la función de ci required
			$this->form_validation->set_message('required', 'El campo %s es obligatorio');
			//validamos el email con la función de ci valid_email
			$this->form_validation->set_message('valid_email', 'El campo %s no es v&aacute;lido');
			//comprobamos que se cumpla el mínimo de caracteres introducidos
			$this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
			//comprobamos que se cumpla el máximo de caracteres introducidos
			$this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
			$this->form_validation->set_message('matches', 'Las contraseñas deben ser iguales.');
			$this->form_validation->set_message('es_unico', 'El valor del campo %s ya existe en el sistema.');
			$idUsuario = $this->security->xss_clean($this->input->post('idUsuario'));
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

    public function habilitar_usuario(){
    	if ($this->input->post('seleccion')) {
    		$idUsuario = $this->input->post('seleccion');
    		$id_usuario_session = $this->session->usuario['identificacion_usuario'];
    		$this->load->model('Usuario_model');
    		$usuario = $this->Usuario_model->obtener_por_id($idUsuario);
    		if ($usuario != null && $usuario->identificacion_usuario != $id_usuario_session) {
    			if($usuario->estado_usuario == true){
    				echo json_encode(array("state" => "error", "message" => "El usuario ya se encuentra habilitado."));
    				die();
    			}
    			$respuesta = $this->Usuario_model->habilitar_usuario($usuario->idUsuario);
    			if($respuesta){
    				echo json_encode(array("state" => "success", "title"=> "¡Usuario habilitado con éxito!", "message" => "El usuario ha sido habilitado con éxito"));
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

}

/* End of file Administrador.php */
/* Location: ./application/controllers/Administrador.php */