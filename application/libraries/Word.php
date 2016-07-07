<?php 
/**
 * Archivo Word, contiene la clase para manejar la exportación a archivos en formato .docx
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH."third_party/PhpOffice/PhpWord/Autoloader.php";
\PhpOffice\PhpWord\Autoloader::register();

/**
 * Librería Word el cual contendrá la instancia de la clase de PHPWord para crear documentos en formato .docx
 *
 * @package aplication/libraries
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial de la clase.
 */
class Word extends \PhpOffice\PhpWord\PhpWord { 

	/**
	 * Función __construct de la librería Word.
	 *
	 * Esta función se ejecuta cuando se crea una instancia de esta librería (Word).
	 * La función ejecuta el constructor de la clase padre (\PhpOffice\PhpWord\PhpWord).
	 * 
	 * @access public
	 * @return void  
	 */
    public function __construct() { 
        parent::__construct(); 
    } 
}