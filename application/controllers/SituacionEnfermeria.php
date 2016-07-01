<?php
/**
 * Archivo SituacionEnfermeria, contiene la clase para manejar las situaciones de enfermería de la aplicación.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador SituacionEnfermeria el cual contendrá las funciones para mostrar los distintos elementos
 * de la página principal de la aplicación.
 *
 * @package aplication/controllers
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class SituacionEnfermeria extends MY_ControladorGeneral {

	/**
	 * Constante que almacena el nombre de la situación de enfermería.
	 */
	const SITUACIONENFERMERIA_NOMBRE = "Situación de Enfermería";
	/**
	 * Constante que almacena la url de la clase actual.
	 */
	const SITUACIONENFERMERIA_URL = "SituacionEnfermeria/";
	/**
	 * Constante que almacena el nombre de la localización anatómica.
	 */
	const LOCALIZACION_NOMBRE = "Localización anatómica";
	/**
	 * Constante que almacena la url de la localización anatómica.
	 */
	const LOCALIZACION_URL = "localizacion-anatomica/";
	/**
	 * Constante que almacena el nombre del tipo de herida.
	 */
 	const TIPOHERIDA_NOMBRE = "Tipo de herida";
 	/**
 	 * Constante que almacena la url del tipo de herida.
 	 */
 	const TIPOHERIDA_URL = "tipo-de-herida";
 	/**
 	 * Constante que almacena el nombre del factor de riesgo.
 	 */
	const FACTORRIESGO_NOMBRE = "Factores de riesgo";
	/**
	 * Constante que almacena la url del factor de riesgo.
	 */
	const FACTORRIESGO_URL = "factores-de-riesgo/";
	/**
	 * Constante que almacena el nombre de las actividades sugeridas.
	 */
	const ACTIVIDAD_NOMBRE = "Actividades sugeridas";
	/**
	 * Constante que alamacena la url de las actividades sugeridas.
	 */
	const ACTIVIDAD_URL = "actividades-sugeridas/";
	/**
	 * Constante que almacena la url para el reinicio de la aplicación
	 */
	const REINICIO_URL = "reiniciar-situacion-de-enfermeria";

	/**
	 * Función __construct del controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (SituacionEnfermeria).
	 * La función ejecuta el constructor de la clase padre (CI_Controller).
	 * La función carga la librería table para poder ser usada en todo el controlador.
	 * 
	 * @access public
	 * @return void  
	 */
	public function __construct(){
		parent::__construct();
		$this->load->library('table');
	}

	/**
	 * Función index para el controlador SituacionEnfermeria.
	 * 
	 * Esta función será ejecutada si no se especifica nada en la URL.
	 * La función muestra la página inicial de la aplicación.
	 *
	 * @access public
	 * @param integer $idPaciente Id del paciente al que se le adiciona la situación de enfermería, si es 0 no se adiciona a ningún paciente, solo se verifica la situación de enfermería.
	 * @return void No se retorna, se muestra la página. 
	 */
	public function index($idPaciente = 0){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		    self::SITUACIONENFERMERIA_NOMBRE => self::SITUACIONENFERMERIA_URL,
		));
		// Se muestra la página por defecto.
		$data = array();
		echo $idPaciente;
		if($idPaciente != 0){
			$this->session->set_userdata('idPaciente', $idPaciente);
		}
		$url_localizacion = self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL;
		$data['url_localizacion'] = base_url($url_localizacion);
		$this->mostrar_pagina("situacionenfermeria/inicioSituacionEnfermeria", $data);
	}

	/**
	 * Función localizacion_herida para el controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta al acceder a: URL_APP/SituacionEnfermeria/localizacion-herida.
	 * La función carga el modelo Localizacion_model para acceder a la base de datos y obtener
	 * todas las localizaciones existentes, si existen localizaciones en la base de datos,
	 * estas son mostradas con una imagen de ayuda, si no existe se mostrará un mensaje.
	 * 
	 * @access public
	 * @return void No se retorna, se muestra la página. 
	 */
	public function localizacion_anatomica(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		    self::SITUACIONENFERMERIA_NOMBRE => self::SITUACIONENFERMERIA_URL,
		    self::LOCALIZACION_NOMBRE
		));
		$localizacion_seleccionada = $this->input->post('selLocalizacion');
		if($localizacion_seleccionada){
			$this->session->set_userdata('localizacion', $localizacion_seleccionada);
			$url_tipoherida = self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL;
			redirect($url_tipoherida);
		}else{
			// Cargamos el modelo Localization para usar sus metodos.
			$this->load->model('Localizacion_model');
			// Ejecutamos el método getLocationz del modelo Localization que nos devuelve
			// todas las localizaciones o false si no hay almenos 1.
			$consulta = $this->Localizacion_model->obtenerLocalizaciones();
			$data = array();
			// Verificamos que exista almenos 1 localización.
			if($consulta){
				// Almacenamos en una matriz bidimensional así $datos['idLocalizacion'] = 'nombre'
	        	foreach($consulta->result() as $row)
	           		$datos[htmlspecialchars($row->idLocalizacion, ENT_QUOTES)] = htmlspecialchars($row->nombre_localizacion, ENT_QUOTES);
	           	// Cargamos la vista y le enviamos los datos a mostrar
	           	$data['localizations'] = $datos;
			}
			// Debo mostrar la localización guardada
			$url_localizacion = self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL;
			$data['url_localizacion'] = base_url($url_localizacion);
			$data['seleccionado'] = ($this->session->has_userdata('localizacion')) ? $this->session->localizacion: "";
			$idPaciente = $this->session->userdata('idPaciente');
		    if(isset($idPaciente) && $idPaciente > 0){
		        $this->load->model('Paciente_model');
		        $paciente = $this->Paciente_model->obtener_por_id($idPaciente);
		        if(!is_null($paciente))
		        	$data['paciente'] = $paciente;
		    }
			$this->mostrar_pagina('situacionenfermeria/localizacionHerida',$data);
		}
	}

	/**
	 * Función tipo_herida para el controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta al acceder a: URL_APP/SituacionEnfermeria/tipo-herida.
	 * La función verifica que exista una selección de localización anatómica, si es así,
	 * carga todos los tipos de herida existentes en la base de datos y los muestra, 
	 * si no se ha seleccionado una localización anatómica se redirige a: URL_APP/SituacionEnfermeria/localizacion-herida.
	 * 
	 * @access public
	 * @return void Se muestra la página si no existe algún error, de lo contrario es redireccionado
	 */
	public function tipo_de_herida(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		    self::SITUACIONENFERMERIA_NOMBRE => self::SITUACIONENFERMERIA_URL,
		    self::LOCALIZACION_NOMBRE => self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL,
		    self::TIPOHERIDA_NOMBRE
		));
		$tipo_herida_seleccionada = $this->input->post('selTipoHerida');
		if($tipo_herida_seleccionada){
			$this->session->set_userdata('tipo_herida', $tipo_herida_seleccionada);
			$url_factorriesgo = self::SITUACIONENFERMERIA_URL.self::FACTORRIESGO_URL;
			redirect($url_factorriesgo);
		}else{
			if($this->session->has_userdata('localizacion')){
				$this->load->model('TipoHerida_model');
				$tipos_herida = $this->TipoHerida_model->obtenerTiposHerida();
				$data = array();
				// Verificamos que exista almenos 1 tipo de herida.
				if($tipos_herida){
					// Almacenamos en una matriz bidimensional así $datos['idTipoHerida'] = 'nombre'
		        	foreach($tipos_herida as $row)
		           		$datos[htmlspecialchars($row->idTipoHerida, ENT_QUOTES)] = htmlspecialchars($row->nombre_tipoherida, ENT_QUOTES);
		           	// Cargamos la vista y le enviamos los datos a mostrar
		           	$data['typeswoundsselect'] = $datos;
		           	$data['typeswounds'] = $tipos_herida;
				}
				// Debo mostrar el tipo de herida guardada
				//$data['seleccionado'] = $this->session->userdata('tipo_herida');
				$url_tipoherida = self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL;
				$data['url_tipoherida'] = base_url($url_tipoherida); 	
				$data['seleccionado'] = ($this->session->has_userdata('tipo_herida')) ? $this->session->tipo_herida: "";
				$idPaciente = $this->session->userdata('idPaciente');
			    if(isset($idPaciente) && $idPaciente > 0){
			        $this->load->model('Paciente_model');
			        $paciente = $this->Paciente_model->obtener_por_id($idPaciente);
			        if(!is_null($paciente))
			        	$data['paciente'] = $paciente;
			    }
				$this->mostrar_pagina('situacionenfermeria/tipoHerida', $data);
			} else{
				$url_localizacion = self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL;
				redirect($url_localizacion);
			}
		}
	}

	/**
	 * Función factor_riesgo para el controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta al acceder a: URL_APP/SituacionEnfermeria/factor-riesgo.
	 * La función verifica que se haya seleccionado una localización, si no es así, 
	 * se redirige a URL_APP/SituacionEnfermeria/localizacion-herida.
	 * La función carga en la vista todos los factores de riesgo existentes en la base de datos.
	 *
	 * @access public
	 * @return void Se muestra la página si no existe algún error, de lo contrario es redireccionado
	 */
	public function factores_de_riesgo(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		    self::SITUACIONENFERMERIA_NOMBRE => self::SITUACIONENFERMERIA_URL,
		    self::LOCALIZACION_NOMBRE => self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL,
		    self::TIPOHERIDA_NOMBRE => self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL,
		    self::FACTORRIESGO_NOMBRE
		));
		if($this->input->post()){
			$datos = array();
			foreach ($this->input->post() as $key => $value) {
				if($key != "submit")
					$datos[$key] = $value;
			}
			$this->session->set_userdata('factores_riesgo', $datos);
			$url_actividad = self::SITUACIONENFERMERIA_URL.self::ACTIVIDAD_URL;
			redirect($url_actividad);
		}else{
			if($this->session->has_userdata('localizacion')){
				if($this->session->has_userdata('tipo_herida')){
					$this->load->model('FactorRiesgo_model');
					$consulta = $this->FactorRiesgo_model->obtenerFactoresRiesgo();
					$data = array();
					$factores_riesgo_seleccionados = ($this->session->has_userdata('factores_riesgo')) ? $this->session->factores_riesgo: array();
					// Verificamos que exista almenos 1 Factor de riesgo.
					if($consulta){
						// Almacenamos en una matriz bidimensional así $datos['idFactorRiesgo'] = 'nombre'
						$datos = array();
			        	foreach($consulta as $row){
			        		$id = htmlspecialchars($row->idFactorRiesgo, ENT_QUOTES);
			        		$nombre = htmlspecialchars($row->nombre_factorriesgo, ENT_QUOTES);;
			        		$dato['id'] = $id;
			        		$dato['nombre'] = $nombre;
			           		$dato['seleccionado'] = (array_key_exists($id, $factores_riesgo_seleccionados)) ? true: false;
			           		$datos[] = $dato;
			        	}
			           	// Cargamos la vista y le enviamos los datos a mostrar
			           	$data['risksfactosselect'] = $datos;
			           	$data['risksfactors'] = $consulta;
					}
					// Se guarda la selección del tipo de herida: selTipoHerida 
					$url_factorriesgo = self::SITUACIONENFERMERIA_URL.self::FACTORRIESGO_URL;
					$data['url_factorriesgo'] = base_url($url_factorriesgo);
					$idPaciente = $this->session->userdata('idPaciente');
				    if(isset($idPaciente) && $idPaciente > 0){
				        $this->load->model('Paciente_model');
				        $paciente = $this->Paciente_model->obtener_por_id($idPaciente);
				        if(!is_null($paciente))
				        	$data['paciente'] = $paciente;
				    } 	
					$this->mostrar_pagina('situacionenfermeria/factoresRiesgo', $data);
				}else{
					$url_tipoherida = self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL;
					redirect($url_tipoherida);
				}	
			} else{
				$url_localizacion = self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL;
				redirect($url_localizacion);
			}
		}
	}

	/**
	 * Función actividades_herida para el controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta al acceder a: URL_APP/SituacionEnfermeria/actividades-herida.
	 * La función verifica que se haya seleccionado un tipo de herida y una localización,
	 * si no es así, será redirigido a URL_APP/SituacionEnfermeria/tipo-herida.
	 * La función sigue el siguiente flujo:
	 * 1. Extrae de la base de datos las actividades relacionadas con el tipo de herida seleccionado.
	 * 2. Extrae de la base de datos las actividades relacionadas con cada factor de riesgo seleccionado, si no se ha seleccionado algún factor de riesgo, quedará un arreglo vacío.
	 * 3. Se unen las actividades relacionadas con el tipo de herida y las actividades relacionadas con los factores de riesgo, donde su atributo sea incluir, en esta lista no se repiten actividades.
	 * 4. Se crea un arreglo con las actividades que se deben excluir.
	 * 5. Se elimina del arreglo general las actividades que se deben excluir.
	 * 6. Se muestran las actividades restantes.
	 * 
	 * @access public
	 * @return void Se muestra la página si no existe algún error, de lo contrario es redireccionado
	 */
	public function actividades_sugeridas(){
		$this->breadcrumb->populate(array(
		    'Inicio' => '',
		    self::SITUACIONENFERMERIA_NOMBRE => self::SITUACIONENFERMERIA_URL,
		    self::LOCALIZACION_NOMBRE => self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL,
		    self::TIPOHERIDA_NOMBRE => self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL,
		    self::FACTORRIESGO_NOMBRE => self::SITUACIONENFERMERIA_URL.self::FACTORRIESGO_URL,
		    self::ACTIVIDAD_NOMBRE
		));
		if($this->session->has_userdata('actividades')){
			$this->session->unset_userdata('actividades');
		}
		if($this->session->has_userdata('localizacion') && $this->session->has_userdata('tipo_herida')){
			$datos_post = $this->input->post();
			$this->load->model('Actividad_model');
			$actividades_tipo_herida = $this->Actividad_model->obtenerActividadesTipoHerida($this->session->tipo_herida);
			$actividades_factor_riesgo = array();
			// Creo el arreglo con las actividades de cada factor de riesgo.
			foreach ($datos_post as $key => $value) {
				if($key != 'submit'){
					$actividad = $this->Actividad_model->obtenerActividadesFactorRiesgo($key);
					if($actividad)
						$actividades_factor_riesgo[] = $actividad;
				}
			}
			// inicalmente las actividades generales contendrán las actividades dadas por el tipo de herida
			$actividades_generales = (is_array($actividades_tipo_herida)) ? $actividades_tipo_herida: array();
			// Si seleccionó algún factor de riesgo procedo a incluir y excluir las actividades
			// que así se indiquen
			if(sizeof($actividades_factor_riesgo) != 0){
				// Arreglo para almacenar las actividades que se van a excluir $actividad->incluir==0
				$actividades_excluir = array();
				// Se adiciona al arreglo general las actividades que se deben incluir por cada 
				// factor de riesgo, además las actividades que se deben excluir son almacenadas
				// en un arreglo aparte.
				foreach ($actividades_factor_riesgo as $row) {
					foreach ($row as $key) {
						if($key->incluir_factorriesgoactividad == 1){
							if (!$this->existe_actividad($actividades_generales,$key))
								$actividades_generales[] = $key;
						}
						else{
							if (!$this->existe_actividad($actividades_excluir,$key))
								$actividades_excluir[] = $key;
						}
					}	
				}
				// Elimino las actividades que se deban excluir del arreglo general
				foreach ($actividades_excluir as $excluir) {
					$i = 0;
					foreach ($actividades_generales as $actividad) {
						if($excluir->idActividad == $actividad->idActividad){
							array_splice($actividades_generales, $i, 1);
							break;
						}
						$i++;
					}
				}
			}
			
			$data['actividades_finales']            = (sizeof($actividades_generales) > 0)? $actividades_generales : null;
			$url_reinicio                           = self::SITUACIONENFERMERIA_URL.self::REINICIO_URL;
			$data['url_reinicio']                   = base_url($url_reinicio); 
			$data['url_guardarsituacionenfermeria'] = base_url(self::SITUACIONENFERMERIA_URL."guardar-situacion-de-enfermeria"); 
			$idPaciente                             = $this->session->userdata('idPaciente');
		    if(isset($idPaciente) && $idPaciente > 0){
		        $this->load->model('Paciente_model');
		        $paciente = $this->Paciente_model->obtener_por_id($idPaciente);
		        if(!is_null($paciente)){
		        	$data['paciente'] = $paciente;
		        	$this->session->set_userdata('actividades', $actividades_generales);
		        }
		    }	
			$this->mostrar_pagina('situacionenfermeria/actividadesSugeridas',$data);
		} else{
			$url_tipoherida = self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL;
			header("Location: ".base_url($url_tipoherida));
		}
	}

	/**
	 * Función reiniciar_situacion_de_enfermeria para el controlador SituacionEnfermeria.
	 *
	 * Esta función se ejecuta al acceder a: URL_APP/SituacionEnfermeria/reiniciar-situacion-de-enfermeria.
	 * La función elimina todas las variables de sesión y destuye la sesión, luego redirecciona a: URL_APP/SituacionEnfermeria
	 *
	 * @access public
	 * @return void Se muestra la página si no existe algún error, de lo contrario es redireccionado
	 */
	public function reiniciar_situacion_de_enfermeria(){
		// Eliminar la localización y el tipo de herida de la session.
		$this->session->unset_userdata("localizacion");
		$this->session->unset_userdata("tipo_herida");
		// Redireccionar al inicio de la situación de enfermería.
		redirect('/SituacionEnfermeria');
	}

	/**
	 * Función existe_actividad para el controlador SituacionEnfermeria.
	 *
	 * Esta función solo puede se accedida desde este mismo controlador (SituacionEnfermeria).
	 * La función verifica si una actividad se encuentra ya contenida en un arreglo,
	 * para comprobar la existencia se usa $actividad->idActividad
	 * @param  array $lista     Arreglo en donde se verificará la existencia de la actividad.
	 * @param  object $actividad Objeto de tipo Actividad que se verificará si existe en la lista.
	 * 
	 * @access private
	 * @return boolean           Retorna true si la actividad se encuentra en la lista, de lo contrario retorna false.
	 */
	private function existe_actividad($lista, $actividad){
		foreach ($lista as $key ) {
			if($key->idActividad == $actividad->idActividad)
				return true;
		}
		return false;
	}

	/**
	 * Función guardar_situacion_de_enfermeria para el controlador SituacionEnfermeria.
	 *
	 * Esta función se encarga de almacenar la situación de enfermería contenida en las variables de sesión a un paciente también contenido allí.
	 *
	 * @access public
	 * @return void No retorna nada, solo redigire a Usuario/perfil mostrando mensajes de éxito o error.
	 */
	public function guardar_situacion_de_enfermeria(){
		if($this->input->post('submit')){
			$observaciones = $this->security->xss_clean($this->input->post('observaciones'));
			if(trim($observaciones) == ""){
				$mensaje['tipo']    = "error";
                $mensaje['mensaje'] = "Debe proporcionar una observación.";
                $url_actividad = self::SITUACIONENFERMERIA_URL.self::ACTIVIDAD_URL;
				$this->session->set_flashdata('mensaje', $mensaje);
                redirect($url_actividad,'refresh');
			}else{
				$idPaciente     = $this->session->has_userdata('idPaciente');
				$localizacion   = $this->session->has_userdata('localizacion');
				$tipoHerida     = $this->session->has_userdata('tipo_herida');
				$factoresRiesgo = $this->session->has_userdata('factores_riesgo');
				$actividades    = $this->session->has_userdata('actividades');
				if(!$idPaciente){
					$mensaje['tipo']    = "error";
	                $mensaje['mensaje'] = "No existe ningún paciente para adicionarle la situación de enfermería.";
	                $url_actividad = self::SITUACIONENFERMERIA_URL;
	                redirect($url_actividad,'refresh');
				}
				if(!$localizacion){
					$url_localizacion = self::SITUACIONENFERMERIA_URL.self::LOCALIZACION_URL;
					redirect($url_localizacion);
				}else if(!$tipoHerida){
					$url_tipoherida = self::SITUACIONENFERMERIA_URL.self::TIPOHERIDA_URL;
					redirect($url_tipoherida);
				}else if(!$factoresRiesgo){
					$url_factorriesgo = self::SITUACIONENFERMERIA_URL.self::FACTORRIESGO_URL;
					redirect($url_factorriesgo);
				}else if(!$actividades){
					$url_actividad = self::SITUACIONENFERMERIA_URL.self::ACTIVIDAD_URL;
					redirect($url_actividad);
				}
				$idPaciente     = $this->session->idPaciente;
				$localizacion   = $this->session->localizacion;
				$tipoHerida     = $this->session->tipo_herida;
				$factoresRiesgo = $this->session->factores_riesgo;
				$actividades    = $this->session->actividades;
				$this->load->model('SituacionEnfermeria_model');
				$resultado = $this->SituacionEnfermeria_model->crear_situacion_de_enfermeria($idPaciente, $localizacion, $tipoHerida, $observaciones, $factoresRiesgo, $actividades);
				if($resultado) {
					$mensaje['tipo']    = "success";
	                $mensaje['mensaje'] = "Se ha adicionado la situación de enfermería con éxito.";
					$this->session->set_flashdata('mensaje', $mensaje);
	                redirect("Usuario/perfil",'refresh');
				}else{
					$mensaje['tipo']    = "error";
	                $mensaje['mensaje'] = "Ha ocurrido un error al adicionar la situación de enfemería, por favor inténtelo de nuevo.";
					$this->session->set_flashdata('mensaje', $mensaje);
	                redirect("Usuario/perfil",'refresh');
				}
			}
		}
	}
}// Fin de la clase SituacionEnfermeria
/* End of file SituacionEnfermeria.php */
/* Location: ./application/controllers/SituacionEnfermeria.php */