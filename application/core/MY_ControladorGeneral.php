<?php
/**
 * Archivo ControladorGeneral, contiene la clase para manejar los controladores.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador ControladorGeneral el cual contendrá las funciones y elementos comunes a todos los controladores.
 *
 * @package aplication/core
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class MY_ControladorGeneral extends CI_Controller {

	/**
	 * Función __construct del controlador ControladorGeneral.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de este controlador (ControladorGeneral).
	 * La función ejecuta el constructor de la clase padre (CI_Controller).
	 * 
	 * @access public
	 * @return void  
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Función mostrar_pagina para el controlador ControladorGeneral.
	 *
	 * La función carga los archivos necesarios para mostrar una página (carga la plantilla), y muestra la página.
	 * @param  String $vista Nombre de la vista a cargar, por defecto se cargará la vista: information
	 * @param  Array $datos Array asociativo opcional por si se desea enviar datos a la vista, por defecto es un arreglo vacío.
	 *
	 * @access protected
	 * @return void Se muestra la página.
	 */
	protected function mostrar_pagina($vista = '', $datos = array()){
		$path_vista   = APPPATH.'views/'.$vista.'.php';
		$path_head    = APPPATH.'views/plantilla/head.php';
		$path_nav  	  = APPPATH.'views/plantilla/nav.php';
		$path_footer  = APPPATH.'views/plantilla/footer.php';
		if($vista == '' ){
			redirect('/', 'refresh');
		}
		else if(!file_exists($path_vista) || !file_exists($path_head) || !file_exists($path_nav) || !file_exists($path_footer)){
			show_404();
		}
		else{
			$this->load->view('plantilla/head');
			$data = array(
				'controller' => get_class($this)
				);
			$this->load->view("plantilla/nav", $data);
			$this->load->view($vista, $datos);
			$this->load->view('plantilla/footer');
		}
	}

	/**
	 * Función echodump para el controlador ControladorGeneral.
	 *
	 * Esta función se encarga de imprimir un arreglo con la función echo de php y el llamado la función dump de este mismo contorlador para imprimir el arreglo recursivamente.
	 * @param  array  $data Arreglo que se desea imprimir por pantalla.
	 * @access protected
	 * @return void         No retorna nada, solo imprimie en pantalla.
	 */
	protected function echodump($data = array()){
		echo "Imprimiendo Arreglo:";
		echo "<pre>";
		echo $this->dump($data);
		echo "</pre>";
	}

	/**
	 * Funcion dump para el controlador ControladorGeneral.
	 *
	 * Esta función solo puede ser accedida desde este mismo controlador (ControladorGeneral).
	 * La función permite visualizar un array de forma más organizada, es una alternativa a la función de php print_r().
	 * La función debe ser usada de la siguiente forma:
	 * 1. Imprimir la etiqueta: "< pre >" antes del llamado de la función dump.
	 * 2. Imprimir el string que retorna la función dump.
	 * 3. Imprimir la etiqueta de cierre: "</ pre >".
	 * @param  array  $data   Arreglo a imprimir.
	 * @param  integer $indent identación, solo es usada en la recursividad de la función.
	 * 
	 * @access private
	 * @return string          Retorna un string con el contenido del arreglo a mostrar.
	 */
	private function dump($data, $indent=0) {
	  $retval = '';
	  $prefix=\str_repeat(' |  ', $indent);
	  if (\is_numeric($data)) $retval.= "Number: $data";
	  elseif (\is_string($data)) $retval.= "String: '$data'";
	  elseif (\is_null($data)) $retval.= "NULL";
	  elseif ($data===true) $retval.= "TRUE";
	  elseif ($data===false) $retval.= "FALSE";
	  elseif (is_array($data)) {
	    $retval.= "Array (".count($data).')';
	    $indent++;
	    foreach($data AS $key => $value) {
	      $retval.= "\n$prefix [$key] = ";
	      $retval.= $this->dump($value, $indent);
	    }
	  }
	  elseif (is_object($data)) {
	    $retval.= "Object (".get_class($data).")";
	    $indent++;
	    foreach($data AS $key => $value) {
	      $retval.= "\n$prefix $key -> ";
	      $retval.= $this->dump($value, $indent);
	    }
	  }
	  return $retval;
	} 

	/**
	 * Función bs_paginación del controlador MY_ControladorGeneral.
	 *
	 * Esta función se encarga de adicionar al parámetro config las etiquetas para la paginación en bootstrap.
	 *
	 * @access protected
	 * @param  Array $config Arreglo a adicionar las etiquetas para la paginación de bootstrap.
	 * @return Array         Retorna un arreglo con las etiquetas usadas en la paginación de bootstrap.
	 */
	protected function bs_paginacion($config){
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
     * Función eliminar_directorio del controlador MY_ControladorGeneral.
     *
     * Esta función se encarga de eliminar un directorio y todo su contenido del servidor.
     *
     * @access private
     * @param  string $dir Path del directorio que se desea eliminar, pj: ./assets/img
     * @return void      No retorna nada, solo elimina el directorio y sus archivos.
     */
    protected function eliminar_directorio($dir) {
	    if(!$dh = @opendir($dir)) return;
	    while (false !== ($current = readdir($dh))) {
	        if($current != '.' && $current != '..') {
	            //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
	            if (!@unlink($dir.'/'.$current)) 
	                $this->eliminar_directorio($dir.'/'.$current);
	        }       
	    }
	    closedir($dh);
	    //echo 'Se ha borrado el directorio '.$dir.'<br/>';
	    @rmdir($dir);
	}
}// Fin de la clase MY_ControladorGeneral
/* End of file MY_ControladorGeneral.php */
/* Location: ./application/core/MY_ControladorGeneral.php */