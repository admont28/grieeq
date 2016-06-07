<?php
/**
 * Vista head, es la encargada de crear el documento html 5 con su head, cargar los js y css a usar en la aplicación y abrir el body.
 *
 * @package aplication/views/plantilla
 * @author Andrés David Montoya Aguirre <admont28@gmail.com>
 * @link https://github.com/admont28 Perfil del autor.
 * @version 1.0 Versión inicial del fichero.
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<!-- Codificación de carácteres -->
  	<meta charset="utf-8">
	<!-- Etiquete que permite la compatibilidad con el responsive en dispositivos -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Titulo de nuestra aplicación web -->
	<title>GRIEEQ</title>
	<!-- Archivo JS de JQuery -->
	<script type="text/javascript" src="<?php echo asset_url('js/jquery.js'); ?>"></script>
	<!-- Archivo CSS de Bootstrap -->
	<link rel="stylesheet" href="<?php echo asset_url('css/bootstrap.css'); ?>" />
	<!-- Archivo CSS de estilos personales -->
	<link rel="stylesheet" href="<?php echo asset_url('css/main.css'); ?>" />
	<!-- Archivo JS de SwwetAlert2 -->
	<script type="text/javascript" src="<?php echo asset_url('js/sweetalert2.min.js'); ?>"></script>
	<!-- Archivo CSS de SwwetAlert2 -->
	<link rel="stylesheet" href="<?php echo asset_url('css/sweetalert2.min.css'); ?>" />
	<!-- Etiquetas para FAVICON en distintos dispositivos, generado en http://www.favicon-generator.org/ -->
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo asset_url('img/web/apple-icon-57x57.png'); ?>">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo asset_url('img/web/apple-icon-60x60.png'); ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo asset_url('img/web/apple-icon-72x72.png'); ?>">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo asset_url('img/web/apple-icon-76x76.png'); ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo asset_url('img/web/apple-icon-114x114.png'); ?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo asset_url('img/web/apple-icon-120x120.png'); ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo asset_url('img/web/apple-icon-144x144.png'); ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo asset_url('img/web/apple-icon-152x152.png'); ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset_url('img/web/apple-icon-180x180.png'); ?>">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo asset_url('img/web/android-icon-192x192.png'); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo asset_url('img/web/favicon-32x32.png'); ?>">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo asset_url('img/web/favicon-96x96.png'); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo asset_url('img/web/favicon-16x16.png'); ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo asset_url('img/web/favicon.ico'); ?>" />
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo asset_url('img/web/ms-icon-144x144.png'); ?>">
	<meta name="theme-color" content="#ffffff">
	<!-- CIERRE - Etiquetas para FAVICON en distintos dispositivos, generado en http://www.favicon-generator.org/ -->
</head>
<body>
<div id="wrap">