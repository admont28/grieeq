<?php 
/**
 * Vista administracionTipoHerida, es la encargada de mostrar las acciones administrativas para los tipos de herida
 *
 * @package aplication/views/admin/tipoherida
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1><?php echo (isset($titulo))? $titulo: "Administración - Tipos de heridas"; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <?php echo $pagination; ?>    
		    <?php if(isset($table)): ?>
	        	<div class="data table-responsive">
	            	<?php echo $table; ?>
	        	</div>
		    <?php endif; ?>
    		<?php echo $pagination;?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-left">
			<h1>Acciones sobre los tipos de herida</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-bottom-1em">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="" id="adicionar" title="Adicionar un nuevo tipo de herida">Adicionar un nuevo tipo de herida</a>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="" id="editar" title="Editar tipo de herida seleccionado">Editar tipo de herida seleccionado</a>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-primary btn-block" href="" id="eliminar" title="Eliminar el tipo de herida seleccionado">Eliminar el tipo de herida seleccionado</a>
			</div>
		</div>
	</div>
</div>