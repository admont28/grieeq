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
        $config["per_page"]         = 4;
        $config['use_page_numbers'] = TRUE;
        // Enlace para usar la paginación         
        $config['base_url']         = base_url()."Administrador/administracion-de-usuarios/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config                     = $this->bs_paginacion($config);
        $page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $identificacion_usuario     = $this->session->usuario['identificacion_usuario'];
        $config['total_rows']       = $this->Usuario_model->contar_registros();        
        $usuarios                   = $this->Usuario_model->obtener_resultados($config["per_page"], $page_number);
        $this->pagination->initialize($config);                
        $data['pagination']         = $this->pagination->create_links();
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
            $idUsuario          = $this->input->post('seleccion');
            $id_usuario_session = $this->session->usuario['identificacion_usuario'];
            $this->load->model('Usuario_model');
            $usuario            = $this->Usuario_model->obtener_por_id($idUsuario);
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
			'Administración de usuarios' => 'Administrador/administracion-de-usuarios',
			'Editar usuario'
		));
		$data                      = array();
		$this->load->model('Usuario_model');
		$usuario = $this->Usuario_model->obtener_por_id($idUsuario);
		if($usuario == null){
            $mensaje['tipo']    = "error";
            $mensaje['mensaje'] = "Identificador de usuario no válido.";
            $this->session->set_flashdata('mensaje', $mensaje);
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
            $this->form_validation->set_rules('password','Contraseña','trim|max_length[50]|min_length[8]|matches[repetirpassword]');
			$this->form_validation->set_rules('repetirpassword','Repetir contraseña','trim|max_length[50]|min_length[8]|matches[password]');
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
            $idUsuario          = $this->input->post('seleccion');
            $operacion          = $this->input->post('operacion');
            $id_usuario_session = $this->session->usuario['identificacion_usuario'];
            $this->load->model('Usuario_model');
            $usuario            = $this->Usuario_model->obtener_por_id($idUsuario);
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
                        $titulo  = "¡Usuario habilitado con éxito!";
                        $mensaje = "El usuario ha sido habilitado con éxito.<br>Al pulsar en el botón OK se recargará la página actual.";
    				}else if($operacion == "inhabilitar"){
                        $titulo  = "¡Usuario inhabilitado con éxito!";
                        $mensaje = "El usuario ha sido inhabilitado con éxito.<br>Al pulsar en el botón OK se recargará la página actual.";
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

    /**
     * Función administracion_de_tipos_de_heridas del controlador Administrador.
     *
     * Esta función se encarga de obtener los tipos de herida y mostrarlos en forma de tabla y paginarlos.
     *
     * @access public
     * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
     * @param  integer $page_number Número de la página a mostrar.
     * @return void                 Muestra la página con los tipos de herida paginados.
     */
    public function administracion_de_tipos_de_heridas($pagina='',$page_number = 1){
        $this->breadcrumb->populate(array(
            'Inicio' => '',
            'Perfil' => 'Usuario',
            'Administración de tipos de heridas'
        ));
        $this->load->model('TipoHerida_model');        
        // Cargo la librería pagination de codeigniter.
        $this->load->library("pagination");
        // configuro la cantidad de registros por página.
        $config["per_page"]         = 4;
        $config['use_page_numbers'] = TRUE;
        // Enlace para usar la paginación         
        $config['base_url']         = base_url()."Administrador/administracion-de-tipos-de-heridas/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config                     = $this->bs_paginacion($config);
        $page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $config['total_rows']       = $this->TipoHerida_model->contar_registros();        
        $tiposHeridas               = $this->TipoHerida_model->obtener_resultados($config["per_page"], $page_number);
        $this->pagination->initialize($config);                
        $data['pagination']         = $this->pagination->create_links();
        $this->load->library('table');
        $this->table->set_empty("---");
        $this->table->set_heading(
            'Seleccionar',
            'Nombre',
            'Descripción',                
            'Imagen asociada'
        );
        if(count($tiposHeridas)>0){
            foreach ($tiposHeridas as $tipoHerida){
                $datos = array(
                    'name'  => 'seleccionar',
                    'id'    => 'seleccionar',
                    'class' => 'seleccion',
                    'value' => $tipoHerida->idTipoHerida,
                    'type'  => 'radio',
                );
                $input = form_input($datos);
                $this->table->add_row(
                    array('data' => $input),
                    array('data' => $tipoHerida->nombre_tipoherida),
                    array('data' => $tipoHerida->descripcion_tipoherida),          
                    array('data' => "<div class='text-center'><img width='70px' src='".asset_url('img/'.$tipoHerida->imagen_tipoherida)."' alt='".$tipoHerida->nombre_tipoherida."' /></div>")
                );
            }
            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();
        }
        $data['titulo'] ="Administración - Tipos de heridas";
        $this->mostrar_pagina('admin/tipoherida/administracionTipoHerida', $data);
    }

    /**
     * Función eliminar_tipo_herida del controlador Administrador.
     *
     * Esta función se encarga de eliminar un tipo de herida de la base de datos.
     *
     * @access public
     * @return void Imprime un objeto JSON dependiendo de lo que se pudo hacer, si no existe nada por post, se redirige a: administracion-de-tipos-de-heridas. 
     */
    public function eliminar_tipo_herida(){
        if ($this->input->post('seleccion')) {
            $idTipoHerida = $this->input->post('seleccion');
            $this->load->model('TipoHerida_model');
            $tipoHerida   = $this->TipoHerida_model->obtener_por_id($idTipoHerida);
            if ($tipoHerida != null) {
                $respuesta = $this->TipoHerida_model->eliminar_por_id($tipoHerida->idTipoHerida);
                if($respuesta){
                    echo json_encode(array("state" => "success", "title" => "¡Tipo de herida eliminado con éxito!", "message" => "El tipo de herida ha sido eliminado con éxito."));
                    die();
                }else{
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                    die();
                }
            } else {
                echo json_encode(array("state" => "error", "message" => "Identificador del tipo de herida no válido."));
                die();
            }
        } else {
            redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
        }
    }

    /**
     * Función formulario_edicion_de_tipo_de_herida del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario de edición de un tipo de herida.
     *
     * @access public
     * @param  integer $idTipoHerida identificador único del tipo de herida a editar.
     * @return void               Muestra el formulario de edición si existe el tipo de herida, sino, redirecciona a: Administrador/administracion-de-tipos-de-heridas
     */
    public function formulario_edicion_de_tipo_de_herida($idTipoHerida){
        $this->breadcrumb->populate(array(
            'Inicio'                     => '',
            'Perfil'                     => 'Usuario',
            'Administración de tipos de herida' => 'Administrador/administracion-de-tipos-de-heridas',
            'Editar tipo de herida'
        ));
        $data       = array();
        $this->load->model('TipoHerida_model');
        $tipoherida = $this->TipoHerida_model->obtener_por_id($idTipoHerida);
        if($tipoherida == null){
            $mensaje['tipo']    = "error";
            $mensaje['mensaje'] = "Identificador de tipo de herida no válido.";
            $this->session->set_flashdata('mensaje', $mensaje);
            redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
        }
        $data['tipoherida']           = $tipoherida;
        $data['titulo']               = "Administración - Editar tipo de herida";
        $data['url_editartipoherida'] = "Administrador/editar-tipo-herida";
        $this->mostrar_pagina('admin/tipoherida/editarTipoHerida', $data);
    }

    /**
     * Función editar_tipo_herida del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de editar un tipo de herida en la base de datos
     *
     * @access public
     * @return void  Redirecciona a administracion-de-tipos-de-heridas si encuentra algún error o si ha sido exitosa la actualización.
     */
    public function editar_tipo_herida(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario
            $this->form_validation->set_rules('idTipoHerida', 'Id Tipo de Herida', 'trim|required');
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|required|max_length[500]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            $idTipoHerida = $this->security->xss_clean($this->input->post('idTipoHerida'));
            // Validamos el formulario, si retorna falso cargamos el método formulario_edicion_de_tipo_de_herida para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_edicion_de_tipo_de_herida($idTipoHerida);
            }else{
                $exito = true;
                if(isset($_FILES) && !empty($_FILES) && $_FILES['imagen']['error'] != 4 ){
                    $config['upload_path']          = './assets/img/tipoherida/'.$idTipoHerida;
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['max_size']             = 2048;
                    $config['max_width']            = 1024;
                    $config['max_height']           = 768;
                    $this->upload->initialize($config);
                    if ( ! $this->upload->do_upload('imagen')){
                        $exito = false;
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = $this->upload->display_errors();
                        $this->session->set_flashdata('mensaje', $mensaje);
                        $this->formulario_edicion_de_tipo_de_herida($idTipoHerida);
                    }

                }
                if($exito){
                    $nombre_imagen = $this->upload->data();
                    $nombre  = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                    $this->load->model('TipoHerida_model');
                    $resultado = $this->TipoHerida_model->editar_tipo_herida($idTipoHerida, $nombre, $descripcion, $nombre_imagen['file_name']);
                    $mensaje         = array();
                    if($resultado){
                        $mensaje['tipo']    = "success";
                        $mensaje['mensaje'] = "Tipo de herida actualizado exitosamente. Nombre: ".$nombre;
                    }
                    else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                    }
                    $this->session->set_flashdata('mensaje', $mensaje);
                    redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
                }
            }
        }else{
            redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
        }
    }

    /**
     * Función formulario_adicionar_tipo_de_herida del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario para adicionar un nuevo tipo de herida al sistema.
     *
     * @access public
     * @return void No retorna nada, muestra la página para adicionar un tipo de herida.
     */
    public function formulario_adicionar_tipo_de_herida(){
        $this->breadcrumb->populate(array(
            'Inicio'                     => '',
            'Perfil'                     => 'Usuario',
            'Administración de tipos de herida' => 'Administrador/administracion-de-tipos-de-heridas',
            'Adicionar tipo de herida'
        ));
        $data                        = array();
        $data['url_adicionartipoherida'] = "Administrador/adicionar-tipo-de-herida";
        $data['titulo']              = "Administración - Adicionar tipo de herida";
        $this->mostrar_pagina('admin/tipoherida/adicionarTipoHerida', $data);
    }

    /**
     * Función adicionar_tipo_de_herida del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de adicionar un tipo de herida en la base de datos para luego adicionarla y subir la imagen asociada a su respectiva localización.   
     *
     * @access public
     * @return void no retorna nada, valida los campos e inserta en la base de datos.
     */
    public function adicionar_tipo_de_herida(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario;
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|max_length[500]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            // Validamos el formulario, si retorna falso cargamos el método formulario_adicionar_tipo_de_herida para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_adicionar_tipo_de_herida();
            }else{
                $config['upload_path']          = './assets/tmp/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2048;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('imagen')){
                    $exito = false;
                    $mensaje['tipo']    = "error";
                    $mensaje['mensaje'] = $this->upload->display_errors();
                    $this->session->set_flashdata('mensaje', $mensaje);
                    $this->formulario_adicionar_tipo_de_herida();
                }else{
                    $nombre_imagen = $this->upload->data();
                    $nombre  = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                    $this->load->model('TipoHerida_model');
                    $idTipoHerida = $this->TipoHerida_model->crear_tipo_herida($nombre, $descripcion, $nombre_imagen['file_name']);
                    $mensaje         = array();
                    if($idTipoHerida){
                        $errores = false;
                        $creacion_directorio = mkdir("./assets/img/tipoherida/".$idTipoHerida, 0755);
                        if($creacion_directorio){
                            $mover = rename("./assets/tmp/".$nombre_imagen['file_name'], "./assets/img/tipoherida/".$idTipoHerida."/".$nombre_imagen['file_name']);
                            if($mover){
                                $mensaje['tipo']    = "success";
                                $mensaje['mensaje'] = "Tipo de herida adicionado exitosamente. Nombre: ".$nombre;
                            }else{
                                $errores = true;
                            }
                        }else{
                            $errores = true;
                        }
                        if($errores){
                            $mensaje['tipo']    = "error";
                            $mensaje['mensaje'] = "No se ha podido cargar la imagen debido a un error inesperado, por favor inténtelo de nuevo.";
                            // Elimino el tipo de herida de la base de datos y con el parámetro false le indico que no intente eliminar la imagen ya que no se ha subido correctamente y no existe. Esto se hace para evitar errores.
                            $this->TipoHerida_model->eliminar_por_id($idTipoHerida, false);
                            $this->session->set_flashdata('mensaje', $mensaje);
                            $this->formulario_adicionar_tipo_de_herida();
                        }
                    }
                    else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                    }
                    $this->session->set_flashdata('mensaje', $mensaje);
                    redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
                }
            }
        }else{
            redirect('Administrador/administracion-de-tipos-de-heridas','refresh');
        }
    }

    /**
     * Función ordenar_actividades del controlador Administrador.
     *
     * Esta función se encarga de mostrar las actividades relacionadas con el tipo de herida pasado por parametro, para que el usuario pueda ordenarlas.   
     *
     * @access public
     * @param  integer $idTipoHerida Identificador único del tipo de herida.
     * @return void                  No retorna, muestra la página con las actividades del tipo de herida pasado por parametro para que sean ordenadas, las muestra en el orden en que se encuentra en la base de datos.
     */
    public function ordenar_actividades($idTipoHerida){
        $this->breadcrumb->populate(array(
            'Inicio'                     => '',
            'Perfil'                     => 'Usuario',
            'Administración de tipos de herida' => 'Administrador/administracion-de-tipos-de-heridas',
            'Ordenar actividades del tipo de herida'
        ));
        $this->load->model('TipoHerida_model');
        $tipoHerida = $this->TipoHerida_model->obtener_por_id($idTipoHerida);
        if(!is_null($tipoHerida)){
            $this->load->model('TipoHeridaActividad_model');
            $this->load->model('Actividad_model');
            $actividades = array();
            $actividades_tipo_heridas = $this->TipoHeridaActividad_model->obtener_actividades_por_tipo_de_herida($idTipoHerida);
            foreach ($actividades_tipo_heridas as $a) {
                $actividad = $this->Actividad_model->obtener_por_id($a->Actividad_idActividad);
                $a->nombre_actividad = $actividad->nombre_actividad;
                $actividades[] = $a;
            }
            $data = array();
            $data['actividades'] = $actividades;
            $data['tipoHerida'] = $tipoHerida;
            $data['titulo'] = "Administración - Ordenar actividades del tipo de herida: ".$tipoHerida->nombre_tipoherida;
            $data['url_ordenaractividades'] = "Administrador/ordenar-actividades";
            $this->mostrar_pagina("admin/tipoherida/ordenarActividades", $data);
        }
    }

    /**
     * Función ordenar_actividades_post del controlador Administrador.
     *
     * Esta función se encarga de guardar el orden de las actividades que llegan en post, desde 1 hasta n.
     *
     * @access public
     * @return JSON Retorna un objeto JSON con la información de la respuesta, un mensaje de éxito o error.
     */
    public function ordenar_actividades_post(){
        if (isset($_POST, $_POST['idTipoHerida'], $_POST['actividades'])) {
            $this->load->model('TipoHerida_model');
            $tipoHerida = $this->TipoHerida_model->obtener_por_id($this->input->post('idTipoHerida'));
            $errores = false;
            // INICIO DE LA TRANSACCIÓN
            $this->db->trans_begin();
            if(!is_null($tipoHerida)){
                $this->load->model('TipoHeridaActividad_model');
                $this->load->model('Actividad_model');
                $actividades = json_decode($this->input->post('actividades'));
                foreach ($actividades as $a) {
                    $actividad = $this->Actividad_model->obtener_por_id($a->id_actividad);
                    if(!is_null($actividad)){
                        $this->TipoHeridaActividad_model->actualizar_orden_actividad($a->id_actividad, $tipoHerida->idTipoHerida, $a->orden);
                    }else{
                        $errores = true;
                        break;
                    }
                }
            }
            if($errores){
                // SI HUBO ALGÚN ERROR, HAGO UN ROLLBACK
                $this->db->trans_rollback();
                echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo más tarde."));
            }else{
                if($this->db->trans_status() === FALSE){
                    // SI TODO SALE BIEN LOGICAMENTE PERO HUBO ALGÙN ERROR EN BD, HAGO EL ROLLBACK
                    $this->db->trans_rollback();
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo más tarde."));
                }
                else{
                    // SI TODO SALE BIEN, HAGO EL COMMIT A LA BD.
                    $this->db->trans_commit();
                    echo json_encode(array("state" => "success", "title"=> "¡Guardado con éxito!", "message" => "Se ha guardado el orden de las actividades con éxito."));
                }
            }
        }
    }

    /**
     * Función administracion_de_factores_de_riesgo del controlador Administrador.
     *
     * Esta función se encarga de obtener los factores de riesgo y mostrarlos en forma de tabla y paginarlos.
     *
     * @access public
     * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
     * @param  integer $page_number Número de la página a mostrar.
     * @return void                 Muestra la página con los factores de riesgo paginados.
     */
    public function administracion_de_factores_de_riesgo($pagina='',$page_number = 1){
        $this->breadcrumb->populate(array(
            'Inicio' => '',
            'Perfil' => 'Usuario',
            'Administración de factores de riesgo'
        ));
        $this->load->model('FactorRiesgo_model');        
        // Cargo la librería pagination de codeigniter.
        $this->load->library("pagination");
        // configuro la cantidad de registros por página.
        $config["per_page"]         = 4;
        $config['use_page_numbers'] = TRUE;
        // Enlace para usar la paginación         
        $config['base_url']         = base_url()."Administrador/administracion-de-factores-de-riesgo/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config                     = $this->bs_paginacion($config);
        $page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $config['total_rows']       = $this->FactorRiesgo_model->contar_registros();        
        $factoresRiesgo               = $this->FactorRiesgo_model->obtener_resultados($config["per_page"], $page_number);
        $this->pagination->initialize($config);                
        $data['pagination']         = $this->pagination->create_links();
        $this->load->library('table');
        $this->table->set_empty("---");
        $this->table->set_heading(
            'Seleccionar',
            'Nombre',
            'Descripción',  
            'Ejemplo',              
            'Imagen asociada'
        );
        if(count($factoresRiesgo)>0){
            foreach ($factoresRiesgo as $factorRiesgo){
                $datos = array(
                    'name'  => 'seleccionar',
                    'id'    => 'seleccionar',
                    'class' => 'seleccion',
                    'value' => $factorRiesgo->idFactorRiesgo,
                    'type'  => 'radio',
                );
                $input = form_input($datos);
                $this->table->add_row(
                    array('data' => $input),
                    array('data' => $factorRiesgo->nombre_factorriesgo),
                    array('data' => $factorRiesgo->descripcion_factorriesgo), 
                    array('data' => $factorRiesgo->ejemplo_factorriesgo),          
                    array('data' => "<div class='text-center'><img width='70px' src='".asset_url('img/'.$factorRiesgo->imagen_factorriesgo)."' alt='".$factorRiesgo->nombre_factorriesgo."' /></div>")
                );
            }
            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();
        }
        $data['titulo'] ="Administración - Factores de riesgo";
        $this->mostrar_pagina('admin/factorriesgo/administracionFactorRiesgo', $data);
    }

    /**
     * Función eliminar_factor_riesgo del controlador Administrador.
     *
     * Esta función se encarga de eliminar un Factor de Riesgo de la base de datos.
     *
     * @access public
     * @return void Imprime un objeto JSON dependiendo de lo que se pudo hacer, si no existe nada por post, se redirige a: administracion-de-factores-de-riesgo. 
     */
    public function eliminar_factor_riesgo(){
        if ($this->input->post('seleccion')) {
            $idFactorRiesgo = $this->input->post('seleccion');
            $this->load->model('FactorRiesgo_model');
            $factorRiesgo   = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
            if ($factorRiesgo != null) {
                $respuesta = $this->FactorRiesgo_model->eliminar_por_id($factorRiesgo->idFactorRiesgo);
                if($respuesta){
                    echo json_encode(array("state" => "success", "title" => "¡Factor de riesgo eliminado con éxito!", "message" => "El factor de riesgo ha sido eliminado con éxito."));
                    die();
                }else{
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                    die();
                }
            } else {
                echo json_encode(array("state" => "error", "message" => "Identificador del factor de riesgo no válido."));
                die();
            }
        } else {
            redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
        }
    }

    /**
     * Función formulario_edicion_de_factor_de_riesgo del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario de edición de un factor de riesgo.
     *
     * @access public
     * @param  integer $idFactorRiesgo identificador único del factor de riesgo a editar.
     * @return void               Muestra el formulario de edición si existe el factor de riesgo, sino, redirecciona a: Administrador/administracion-de-factores-de-riesgo
     */
    public function formulario_edicion_de_factor_de_riesgo($idFactorRiesgo){
        $this->breadcrumb->populate(array(
            'Inicio'                     => '',
            'Perfil'                     => 'Usuario',
            'Administración de factores de riesgo' => 'Administrador/administracion-de-factores-de-riesgo',
            'Editar factor de riesgo'
        ));
        $data       = array();
        $this->load->model('FactorRiesgo_model');
        $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
        if($factorRiesgo == null){
            $mensaje['tipo']    = "error";
            $mensaje['mensaje'] = "Identificador de factor de riesgo no válido.";
            $this->session->set_flashdata('mensaje', $mensaje);
            redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
        }
        $data['factorriesgo']           = $factorRiesgo;
        $data['titulo']               = "Administración - Editar factor de riesgo";
        $data['url_editarfactorriesgo'] = "Administrador/editar-factor-riesgo";
        $this->mostrar_pagina('admin/factorriesgo/editarFactorRiesgo', $data);
    }

    /**
     * Función editar_factor_riesgo del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de editar un factor de riesgo en la base de datos
     *
     * @access public
     * @return void  Redirecciona a administracion-de-factores-de-riesgo si encuentra algún error o si ha sido exitosa la actualización.
     */
    public function editar_factor_riesgo(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario
            $this->form_validation->set_rules('idFactorRiesgo', 'Id factor de riesgo', 'trim|required');
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|required|max_length[500]|min_length[5]');
            $this->form_validation->set_rules('ejemplo','Ejemplo','trim|required|max_length[155]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            $idFactorRiesgo = $this->security->xss_clean($this->input->post('idFactorRiesgo'));
            // Validamos el formulario, si retorna falso cargamos el método formulario_edicion_de_factor_de_riesgo para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_edicion_de_factor_de_riesgo($idFactorRiesgo);
            }else{
                $exito = true;
                if(isset($_FILES) && !empty($_FILES) && $_FILES['imagen']['error'] != 4 ){
                    $config['upload_path']          = './assets/img/factorriesgo/'.$idFactorRiesgo;
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['max_size']             = 2048;
                    $config['max_width']            = 1024;
                    $config['max_height']           = 768;
                    $this->upload->initialize($config);
                    if ( ! $this->upload->do_upload('imagen')){
                        $exito = false;
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = $this->upload->display_errors();
                        $this->session->set_flashdata('mensaje', $mensaje);
                        $this->formulario_edicion_de_factor_de_riesgo($idFactorRiesgo);
                    }
                }
                if($exito){
                    $imagen = $this->upload->data();
                    $nombre  = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                    $ejemplo = $this->security->xss_clean($this->input->post('ejemplo'));
                    $this->load->model('FactorRiesgo_model');
                    $resultado = $this->FactorRiesgo_model->editar_factor_riesgo($idFactorRiesgo, $nombre, $descripcion, $ejemplo, $imagen['file_name']);
                    $mensaje         = array();
                    if($resultado){
                        $mensaje['tipo']    = "success";
                        $mensaje['mensaje'] = "factor de riesgo actualizado exitosamente. Nombre: ".$nombre;
                    }
                    else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                    }
                    $this->session->set_flashdata('mensaje', $mensaje);
                    redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
                }
            }
        }else{
            redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
        }
    }

    /**
     * Función formulario_adicionar_factor_de_riesgo del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario para adicionar un nuevo factor de riesgo al sistema.
     *
     * @access public
     * @return void No retorna nada, muestra la página para adicionar un factor de riesgo.
     */
    public function formulario_adicionar_factor_de_riesgo(){
        $this->breadcrumb->populate(array(
            'Inicio'                               => '',
            'Perfil'                               => 'Usuario',
            'Administración de factores de riesgo' => 'Administrador/administracion-de-factores-de-riesgo',
            'Adicionar factor de riesgo'
        ));
        $data                              = array();
        $data['url_adicionarfactorriesgo'] = "Administrador/adicionar-factor-de-riesgo";
        $data['titulo']                    = "Administración - Adicionar factor de riesgo";
        $this->mostrar_pagina('admin/factorriesgo/adicionarFactorRiesgo', $data);
    }

    /**
     * Función adicionar_factor_de_riesgo del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de adicionar un factor de riesgo en la base de datos para luego adicionarlo y subir la imagen asociada.   
     *
     * @access public
     * @return void no retorna nada, valida los campos e inserta en la base de datos.
     */
    public function adicionar_factor_de_riesgo(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario;
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[255]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|max_length[500]|min_length[5]');
            $this->form_validation->set_rules('ejemplo','Ejemplo','trim|max_length[255]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            // Validamos el formulario, si retorna falso cargamos el método formulario_adicionar_factor_de_riesgo para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_adicionar_factor_de_riesgo();
            }else{
                $config['upload_path']          = './assets/tmp/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2048;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('imagen')){
                    $exito              = false;
                    $mensaje['tipo']    = "error";
                    $mensaje['mensaje'] = $this->upload->display_errors();
                    $this->session->set_flashdata('mensaje', $mensaje);
                    $this->formulario_adicionar_factor_de_riesgo();
                }else{
                    $nombre_imagen  = $this->upload->data();
                    $nombre         = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion    = $this->security->xss_clean($this->input->post('descripcion'));
                    $ejemplo        = $this->security->xss_clean($this->input->post('ejemplo'));
                    $this->load->model('FactorRiesgo_model');
                    $idFactorRiesgo = $this->FactorRiesgo_model->crear_factor_riesgo($nombre, $descripcion, $ejemplo, $nombre_imagen['file_name']);
                    $mensaje        = array();
                    if($idFactorRiesgo){
                        $errores = false;
                        $creacion_directorio = mkdir("./assets/img/factorriesgo/".$idFactorRiesgo, 0755);
                        if($creacion_directorio){
                            $mover = rename("./assets/tmp/".$nombre_imagen['file_name'], "./assets/img/factorriesgo/".$idFactorRiesgo."/".$nombre_imagen['file_name']);
                            if($mover){
                                $mensaje['tipo']    = "success";
                                $mensaje['mensaje'] = "Factor de riesgo adicionado exitosamente. Nombre: ".$nombre;
                            }else{
                                $errores = true;
                            }
                        }else{
                            $errores = true;
                        }
                        if($errores){
                            $mensaje['tipo']    = "error";
                            $mensaje['mensaje'] = "No se ha podido cargar la imagen debido a un error inesperado, por favor inténtelo de nuevo.";
                            // Elimino el factor de riesgo de la base de datos y con el parámetro false le indico que no intente eliminar la imagen ya que no se ha subido correctamente y no existe. Esto se hace para evitar errores.
                            $this->FactorRiesgo_model->eliminar_por_id($idFactorRiesgo, false);
                            $this->session->set_flashdata('mensaje', $mensaje);
                            $this->formulario_adicionar_factor_de_riesgo();
                        }
                    }
                    else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                    }
                    $this->session->set_flashdata('mensaje', $mensaje);
                    redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
                }
            }
        }else{
            redirect('Administrador/administracion-de-factores-de-riesgo','refresh');
        }
    }

    /**
     * Función administracion_de_actividades del controlador Administrador.
     *
     * Esta función se encarga de obtener las actividades y mostrarlas en forma de tabla y paginarlas.
     *
     * @access public
     * @param  string  $pagina      Es usada para mostrar en la url el string: pagina 
     * @param  integer $page_number Número de la página a mostrar.
     * @return void                 Muestra la página con las actividades paginadas.
     */
    public function administracion_de_actividades($pagina='',$page_number = 1){
        $this->breadcrumb->populate(array(
            'Inicio' => '',
            'Perfil' => 'Usuario',
            'Administración de actividades'
        ));
        $this->load->model('Actividad_model');        
        // Cargo la librería pagination de codeigniter.
        $this->load->library("pagination");
        // configuro la cantidad de registros por página.
        $config["per_page"]         = 4;
        $config['use_page_numbers'] = TRUE;
        // Enlace para usar la paginación         
        $config['base_url']         = base_url()."Administrador/administracion-de-actividades/pagina/";
        // Adición del html de bootstrap a la variable de configuración
        $config                     = $this->bs_paginacion($config);
        $page_number                = intval(($page_number  == 1 || $page_number  == 0) ? 0 : ($page_number * $config['per_page']) - $config['per_page']);
        $config['total_rows']       = $this->Actividad_model->contar_registros();        
        $Actividades                = $this->Actividad_model->obtener_resultados($config["per_page"], $page_number);
        $this->pagination->initialize($config);                
        $data['pagination']         = $this->pagination->create_links();;
        $this->load->library('table');
        $this->table->set_empty("---");
        $this->table->set_heading(
            'Seleccionar',
            'Nombre',
            'Descripción',  
            'Precaución',              
            'Imagen asociada'
        );
        if(count($Actividades)>0){
            foreach ($Actividades as $actividad){
                $datos = array(
                    'name'  => 'seleccionar',
                    'id'    => $actividad->idActividad,
                    'class' => 'seleccion',
                    'value' => $actividad->idActividad,
                    'type'  => 'radio',
                );
                $input = form_input($datos);
                $this->table->add_row(
                    array('data' => $input),
                    array('data' => $actividad->nombre_actividad),
                    array('data' => $actividad->descripcion_actividad), 
                    array('data' => $actividad->precaucion_actividad),          
                    array('data' => "<div class='text-center'><img width='70px' src='".asset_url('img/'.$actividad->imagen_actividad)."' alt='".$actividad->nombre_actividad."' /></div>")
                );
            }
            $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();
        }
        $data['titulo'] ="Administración - Actividades";
        $this->mostrar_pagina('admin/actividad/administracionActividad', $data);
    }

    /**
     * Función obtener_tipos_de_herida_dada_actividad del controlador Administrador.
     *
     * Esta función se encarga de obtener los tipos de herida asociados a una actividad.
     *
     * @access public
     * @return void No retorna nada, imprime (echo) una tabla html con los tipos de heridas asociados a la actividad obtenida desde $_GET, si no se encuentran tipos de heridas se imprime un parrafó indicando que no se encontraron resultados.
     */
    public function obtener_tipos_de_herida_dada_actividad(){
        $id = $this->input->get('id');
        if(isset($id) && !empty($id)){
            $this->load->model('TipoHeridaActividad_model');
            $this->load->model('TipoHerida_model');
            $actividades_tipoheridas = $this->TipoHeridaActividad_model->obtener_tipos_de_herida_por_actividad($id);
            $tipos_de_heridas = array();
            foreach ($actividades_tipoheridas as $at) {
                $tipos_de_heridas[] = $this->TipoHerida_model->obtener_por_id($at->TipoHerida_idTipoHerida);
            }
            $data['tipos_de_heridas'] = $tipos_de_heridas;
            $vista = $this->load->view('admin/tipoherida/tablaTipoHerida', $data, true);
            echo $vista;
        }
    }

    /**
     * Función obtener_factores_de_riesgo_dada_actividad del controlador Administrador.
     *
     * Esta función se encarga de obtener los factores de riesgo asociados a una actividad.
     *
     * @access public
     * @return void No retorna nada, imprime (echo) una tabla html con los factores de riesgo asociados a la actividad obtenida desde $_GET, si no se encuentran factores de riesgo se imprime un parrafó indicando que no se encontraron resultados.
     */
    public function obtener_factores_de_riesgo_dada_actividad(){
        $id = $this->input->get('id');
        if(isset($id) && !empty($id)){
            $this->load->model('FactorRiesgoActividad_model');
            $this->load->model('FactorRiesgo_model');
            $actividades_factoresriesgo = $this->FactorRiesgoActividad_model->obtener_factores_de_riesgo_por_actividad($id);
            $factores_de_riesgo = array();
            foreach ($actividades_factoresriesgo as $af) {
                $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($af->FactorRiesgo_idFactorRiesgo);
                $factorRiesgo->incluir = $af->incluir_factorriesgoactividad;
                $factores_de_riesgo[] = $factorRiesgo;
            }
            $data['factores_de_riesgo'] = $factores_de_riesgo;
            $vista = $this->load->view('admin/factorriesgo/tablaFactorRiesgo', $data, true);
            echo $vista;
        }
    }

    /**
     * Función eliminar_actividad del controlador Administrador.
     *
     * Esta función se encarga de eliminar un Factor de Riesgo de la base de datos.
     *
     * @access public
     * @return void Imprime un objeto JSON dependiendo de lo que se pudo hacer, si no existe nada por post, se redirige a: administracion-de-actividades.
     */
    public function eliminar_actividad(){
        if ($this->input->post('seleccion')) {
            $idActividad = $this->input->post('seleccion');
            $this->load->model('Actividad_model');
            $actividad   = $this->Actividad_model->obtener_por_id($idActividad);
            if ($actividad != null) {
                $respuesta = $this->Actividad_model->eliminar_por_id($actividad->idActividad);
                if($respuesta){
                    echo json_encode(array("state" => "success", "title" => "¡Actividad eliminada con éxito!", "message" => "La actividad ha sido eliminada con éxito."));
                    die();
                }else{
                    echo json_encode(array("state" => "error", "message" => "Ha ocurrido un error inesperado, por favor inténtelo de nuevo."));
                    die();
                }
            } else {
                echo json_encode(array("state" => "error", "message" => "Identificador de la actividad no válido."));
                die();
            }
        } else {
            redirect('Administrador/administracion-de-actividades','refresh');
        }
    }

    /**
     * Función formulario_adicionar_actividad del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario para adicionar una nueva actividad al sistema.
     *
     * @access public
     * @return void No retorna nada, muestra la página para adicionar una actividad.
     */
    public function formulario_adicionar_actividad(){
        $this->breadcrumb->populate(array(
            'Inicio'                               => '',
            'Perfil'                               => 'Usuario',
            'Administración de actividades' => 'Administrador/administracion-de-actividades',
            'Adicionar factor de riesgo'
        ));
        $this->load->model('TipoHerida_model');
        $this->load->model('FactorRiesgo_model');
        $data                              = array();
        $data['tipos_de_heridas']          = $this->TipoHerida_model->obtenerTiposHerida();
        $data['factores_de_riesgo']        = $this->FactorRiesgo_model->obtenerFactoresRiesgo();
        $data['url_adicionaractividad']    = "Administrador/adicionar-actividad";
        $data['titulo']                    = "Administración - Adicionar actividad";
        $this->mostrar_pagina('admin/actividad/adicionarActividad', $data);
    }

    /**
     * Función adicionar_actividad del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de adicionar una actividad en la base de datos para luego adicionarla y subir la imagen asociada.   
     *
     * @access public
     * @return void no retorna nada, valida los campos e inserta en la base de datos.
     */
    public function adicionar_actividad(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario;
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[255]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|required|max_length[500]|min_length[5]');
            $this->form_validation->set_rules('precaucion','Precaución','trim|max_length[500]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            // Validamos el formulario, si retorna falso cargamos el método formulario_adicionar_actividad para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_adicionar_actividad();
            }else{
                $config['upload_path']          = './assets/tmp/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 2048;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('imagen')){
                    $exito              = false;
                    $mensaje['tipo']    = "error";
                    $mensaje['mensaje'] = $this->upload->display_errors();
                    $this->session->set_flashdata('mensaje', $mensaje);
                    $this->formulario_adicionar_actividad();
                }else{
                    $nombre_imagen      = $this->upload->data();
                    $nombre             = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion        = $this->security->xss_clean($this->input->post('descripcion'));
                    $precaucion         = $this->security->xss_clean($this->input->post('precaucion'));
                    $tipos_de_heridas   = (!is_null($this->input->post('heridas')) ) ? $this->input->post('heridas'): array();
                    $factores_de_riesgo = (!is_null($this->input->post('factores_de_riesgo')) ) ? $this->input->post('factores_de_riesgo'): array();
                    
                    $this->db->trans_begin(); // Inicio de la trasacción.

                    $this->load->model('Actividad_model');
                    $idActividad = $this->Actividad_model->crear_actividad($nombre, $descripcion, $precaucion, $nombre_imagen['file_name']);
                    $mensaje     = array();
                    if($idActividad){
                        $errores = false;
                        $creacion_directorio = mkdir("./assets/img/actividad/".$idActividad, 0755);
                        if($creacion_directorio){
                            $mover = rename("./assets/tmp/".$nombre_imagen['file_name'], "./assets/img/actividad/".$idActividad."/".$nombre_imagen['file_name']);
                            if($mover){
                                $this->load->model('TipoHeridaActividad_model');
                                $this->load->model('TipoHerida_model');
                                foreach ($tipos_de_heridas as $indice => $idTipoHerida) {
                                    $tipoHerida = $this->TipoHerida_model->obtener_por_id($idTipoHerida);
                                    if(!is_null($tipoHerida)){
                                        $resultado = $this->TipoHeridaActividad_model->insertar($idActividad, $idTipoHerida);
                                        if(!$resultado){
                                            $errores = true;
                                            break;
                                        }
                                    }else{
                                        $errores = true;
                                        break;
                                    }
                                }
                                $this->load->model('FactorRiesgo_model');
                                $this->load->model('FactorRiesgoActividad_model');
                                foreach ($factores_de_riesgo as $indice => $valor) {
                                    // Valor viene con la letra i (inclusión) o la letra e (exclusión) seguido del id del factor de riesgo.
                                    $accion = substr($valor, 0, 1); // Exraigo la primer letra, i o e.
                                    $resultado = false;
                                    if($accion == "i"){
                                        $idFactorRiesgo = (int)substr($valor, 1); // Extraigo el id del factor de riesgo.
                                        $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
                                        if(!is_null($factorRiesgo)){
                                            $resultado = $this->FactorRiesgoActividad_model->insertar($idActividad, $idFactorRiesgo, true); 
                                        }
                                    }  
                                    else if($accion == "e"){ // Actividad de exclusión.
                                        $idFactorRiesgo = (int)substr($valor, 1); // Extraigo el id del factor de riesgo.
                                        $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
                                        if(!is_null($factorRiesgo)){
                                            $resultado = $this->FactorRiesgoActividad_model->insertar($idActividad, $idFactorRiesgo, false);
                                        }
                                        
                                    }else if($accion = "s"){
                                        continue;
                                    }else{
                                        $errores = true;
                                        break;
                                    }
                                    if(!$resultado){
                                        $errores = true;
                                        break;
                                    }
                                }
                                if(!$errores){
                                    $mensaje['tipo']    = "success";
                                    $mensaje['mensaje'] = "Actividad adicionada exitosamente. Nombre: ".$nombre;
                                    $this->db->trans_commit();
                                    $this->session->set_flashdata('mensaje', $mensaje);
                                    redirect('Administrador/administracion-de-actividades','refresh');
                                }
                            }else{
                                $errores = true;
                            }
                        }else{
                            $errores = true;
                        }
                        if($errores){
                            $mensaje['tipo']    = "error";
                            $mensaje['mensaje'] = "No se ha podido adicionar la actividad debido a un error inesperado, por favor recarga la página e inténtalo de nuevo.";
                            $this->db->trans_rollback();
                            $this->session->set_flashdata('mensaje', $mensaje);
                            $this->formulario_adicionar_actividad();
                        }
                    }
                    else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                    }
                }
            }
        }else{
            redirect('Administrador/administracion-de-actividades','refresh');
        }
    }
    /**
     * Función formulario_edicion_de_actividad del controlador Administrador.
     *
     * Esta función se encarga de mostrar el formulario de edición de una actividad.
     *
     * @access public
     * @param  integer $idActividad Identificador único de la actividad.
     * @return void               Muestra el formulario de edición si existe la actividad, sino, redirecciona a: Administrador/administracion-de-actividades
     */
    public function formulario_edicion_de_actividad($idActividad){
        $this->breadcrumb->populate(array(
            'Inicio'                     => '',
            'Perfil'                     => 'Usuario',
            'Administración de actividades' => 'Administrador/administracion-de-actividades',
            'Editar actividad'
        ));
        $data       = array();
        $this->load->model('Actividad_model');
        $actividad = $this->Actividad_model->obtener_por_id($idActividad);
        if($actividad == null){
            $mensaje['tipo']    = "error";
            $mensaje['mensaje'] = "Identificador de actividad no válido.";
            $this->session->set_flashdata('mensaje', $mensaje);
            redirect('Administrador/administracion-de-actividades','refresh');
        }
        $this->load->model('TipoHerida_model');
        $this->load->model('FactorRiesgo_model');
        $this->load->model('TipoHeridaActividad_model');
        $this->load->model('FactorRiesgoActividad_model');
        $tipos_de_heridas = $this->TipoHerida_model->obtenerTiposHerida();
        $tipos_de_heridas_por_actividad = $this->TipoHeridaActividad_model->obtener_tipos_de_herida_por_actividad($idActividad);
        $tipos_de_heridas_aux = array();
        foreach ($tipos_de_heridas as $th) {
            $th->checked = false; 
            foreach ($tipos_de_heridas_por_actividad as $thpa) {
                if($th->idTipoHerida == $thpa->TipoHerida_idTipoHerida){
                    $th->checked = true;
                    break;
                }
            }
            $tipos_de_heridas_aux[] = $th;
        }
        $factores_de_riesgo_aux = array();
        $factores_de_riesgo = $this->FactorRiesgo_model->obtenerFactoresRiesgo();
        $factores_de_riesgo_por_actividad = $this->FactorRiesgoActividad_model->obtener_factores_de_riesgo_por_actividad($idActividad);
        foreach ($factores_de_riesgo as $fr) {
            foreach ($factores_de_riesgo_por_actividad as $frpa) {
                if($fr->idFactorRiesgo == $frpa->FactorRiesgo_idFactorRiesgo){
                    if($frpa->incluir_factorriesgoactividad){
                        $fr->incluir = true;
                    }
                    else{
                        $fr->incluir = false;
                    }
                }
            }
            $factores_de_riesgo_aux[] = $fr;
        }
        $data['tipos_de_heridas']          = $tipos_de_heridas_aux;
        $data['factores_de_riesgo']        = $factores_de_riesgo_aux;
        $data['actividad']           = $actividad;
        $data['titulo']              = "Administración - Editar actividad";
        $data['url_editaractividad'] = "Administrador/editar-actividad";
        $this->mostrar_pagina('admin/actividad/editarActividad', $data);
    }

    /**
     * Función editar_actividad del controlador Administrador.
     *
     * Esta función se encarga de realizar las validaciones antes de editar una actividad en la base de datos.
     *
     * @access public
     * @return void  Redirecciona a administracion-de-actividades si encuentra algún error o si ha sido exitosa la actualización.
     */
    public function editar_actividad(){
        if($this->input->post('submit')){
            $this->load->library('upload');
            //hacemos las comprobaciones que de nuestro formulario
            $this->form_validation->set_rules('idActividad', 'Id actividad', 'trim|required');
            $this->form_validation->set_rules('nombre','Nombre','trim|required|max_length[100]|min_length[5]');
            $this->form_validation->set_rules('descripcion','Descripción','trim|required|max_length[500]|min_length[5]');
            $this->form_validation->set_rules('precaucion','Precaución','trim|max_length[155]|min_length[5]');
            $this->form_validation->set_message('required', 'El campo %s es obligatorio');
            $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s carácteres');
            $this->form_validation->set_message('max_length', 'El campo %s debe tener menos %s car&aacute;cteres');
            $idActividad = $this->security->xss_clean($this->input->post('idActividad'));
            // Validamos el formulario, si retorna falso cargamos el método formulario_edicion_de_actividad para mostrar los errores ocurridos.
            if (!$this->form_validation->run()){
                $this->formulario_edicion_de_actividad($idActividad);
            }else{
                $exito = true;
                if(isset($_FILES) && !empty($_FILES) && $_FILES['imagen']['error'] != 4 ){
                    $config['upload_path']          = './assets/img/actividad/'.$idActividad;
                    $config['allowed_types']        = 'gif|jpg|png';
                    $config['max_size']             = 2048;
                    $config['max_width']            = 1024;
                    $config['max_height']           = 768;
                    $this->upload->initialize($config);
                    if ( ! $this->upload->do_upload('imagen')){
                        $exito              = false;
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = $this->upload->display_errors();
                        $this->session->set_flashdata('mensaje', $mensaje);
                        $this->formulario_edicion_de_actividad($idActividad);
                    }
                }
                if($exito){
                    $imagen      = $this->upload->data();
                    $nombre      = $this->security->xss_clean($this->input->post('nombre'));
                    $descripcion = $this->security->xss_clean($this->input->post('descripcion'));
                    $precaucion  = $this->security->xss_clean($this->input->post('precaucion'));
                    $tipos_de_heridas   = (!is_null($this->input->post('heridas')) ) ? $this->input->post('heridas'): array();
                    $factores_de_riesgo = (!is_null($this->input->post('factores_de_riesgo')) ) ? $this->input->post('factores_de_riesgo'): array();
                    $this->load->model('Actividad_model');
                    // INICIO DE LA TRANSACCIÓN
                    $this->db->trans_begin();
                    $resultado = $this->Actividad_model->editar_actividad($idActividad, $nombre, $descripcion, $precaucion, $imagen['file_name']);
                    $mensaje   = array();
                    $errores = false;
                    if($resultado){
                        $this->load->model('TipoHeridaActividad_model');
                        $this->load->model('TipoHerida_model');
                        $this->load->model('FactorRiesgo_model');
                        $this->load->model('FactorRiesgoActividad_model');
                        $eliminacion_tipos_de_herida = $this->TipoHeridaActividad_model->eliminar_relacion_tipos_de_herida_por_actividad($idActividad);
                        $eliminacion_factores_de_riesgo = $this->FactorRiesgoActividad_model->eliminar_relacion_factores_de_riesgo_por_actividad($idActividad);
                        if($eliminacion_tipos_de_herida && $eliminacion_factores_de_riesgo){
                            foreach ($tipos_de_heridas as $indice => $idTipoHerida) {
                                $tipoHerida = $this->TipoHerida_model->obtener_por_id($idTipoHerida);
                                if(!is_null($tipoHerida)){
                                    $resultado = $this->TipoHeridaActividad_model->insertar($idActividad, $idTipoHerida);
                                    if(!$resultado){
                                        // Falló la inserción de la relación entre tipo de herida y actividad
                                        $errores = true;
                                        break;
                                    }
                                }else{
                                    // No existe el tipo de herida que seleccionó el usuario.
                                    $errores = true;
                                    break;
                                }
                            }
                            if(!$errores){
                                foreach ($factores_de_riesgo as $indice => $valor) {
                                    // Valor viene con la letra i (inclusión) o la letra e (exclusión) seguido del id del factor de riesgo.
                                    $accion = substr($valor, 0, 1); // Exraigo la primer letra, i o e.
                                    $resultado_consulta = false;
                                    if($accion == "i"){
                                        $idFactorRiesgo = (int)substr($valor, 1); // Extraigo el id del factor de riesgo.
                                        $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
                                        if(!is_null($factorRiesgo)){
                                            $resultado_consulta = $this->FactorRiesgoActividad_model->insertar($idActividad, $idFactorRiesgo, true); 
                                        }else{
                                            $errores = true;
                                            break;
                                        }
                                    }  
                                    else if($accion == "e"){ // Actividad de exclusión.
                                        $idFactorRiesgo = (int)substr($valor, 1); // Extraigo el id del factor de riesgo.
                                        $factorRiesgo = $this->FactorRiesgo_model->obtener_por_id($idFactorRiesgo);
                                        if(!is_null($factorRiesgo)){
                                            $resultado_consulta = $this->FactorRiesgoActividad_model->insertar($idActividad, $idFactorRiesgo, false);
                                        }
                                        else{
                                            $errores = true;
                                            break;
                                        }  
                                    }else if($accion = "s"){
                                        continue;
                                    }else{
                                        // Viene una acción incorrecta, solo debe ser i,e o s.
                                        $errores = true;
                                        break;
                                    }
                                    if(!$resultado_consulta){
                                        $errores = true;
                                        break;
                                    }
                                }
                            }
                        }else{
                            // Falló la eliminación de los tipos de herida o factores de riesgo.
                            $errores = true;
                        }
                    }
                    else{
                        // Falló la edición de la actividad.
                        $errores = true;
                    }
                    if(!$errores){
                        // No han habido errores lógica de programación, pero válido que no hayan errores en la transacción.
                        if ($this->db->trans_status() === FALSE){
                            $mensaje['tipo']    = "error";
                            $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                            $this->db->trans_rollback();
                        }
                        else{
                            $mensaje['tipo']    = "success";
                            $mensaje['mensaje'] = "Actividad actualizada exitosamente. Nombre: ".$nombre;
                            $this->db->trans_commit();                        }
                    }else{
                        $mensaje['tipo']    = "error";
                        $mensaje['mensaje'] = "Ha ocurrido un error inesperado, porfavor inténtelo de nuevo.";
                        $this->db->trans_rollback();
                    }
                    $this->session->set_flashdata('mensaje', $mensaje);
                    redirect('Administrador/administracion-de-actividades','refresh');
                }
            }
        }else{
            redirect('Administrador/administracion-de-actividades','refresh');
        }
    }
} // Fin de la clase Administrador.
/* End of file Administrador.php */
/* Location: ./application/controllers/Administrador.php */