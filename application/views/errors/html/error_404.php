<?php 
/**
 * Vista page_missing, es la encargada de mostrar al usuario que la página a la que intentó acceder no existe en la aplicación.
 *
 * @package aplication/views
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
    <style>

        * {
            line-height: 1.2;
            margin: 0;
            font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
        }

        html {
            display: table;
            height: 100%;
            text-align: center;
            width: 100%;
        }

        body {
            display: table-cell;
            vertical-align: middle;
            margin: 2em auto;
        }

        h1, h3, h3, h4, p{
            font-style: normal; 
            font-variant: normal; 
            font-weight: 500; 
        }

        h1 {
            color: black;
            font-size: 48px;
        }

        p {
            margin: 0 auto;
            width: 90%;
            font-size: 17px;
        }

        @media only screen and (max-width: 280px) {

            body, p {
                width: 95%;
            }

            h1 {
                font-size: 1.5em;
                margin: 0 0 0.3em;
            }

        }
    
    </style>
</head>
<body>
    <h1>Aplicación web de apoyo para el profesional de la salud en el cuidado de las personas con heridas</h1>
    <br>
    <h2>Oops! Página web no encontrada</h2>
    <div style="text-align: center;">
        <img src="<?php echo asset_url('img/page_not_found.png') ?>" alt="Página web no encontrada">
    </div>
    <br>
    <p>Lo sentimos, pero la página que estabas tratando de ver no existe.</p>
    <br>
    <p>Sorry, but the page you were trying to view does not exist.</p>
    <br>
    <a href="<?php echo base_url() ?>" title="Regresar a la aplicación de tratamiento de heridas">Regresar a la aplicación</a>
    <br>
    <br>
</body>
</html>
<!-- IE needs 512+ bytes: http://blogs.msdn.com/b/ieinternals/archive/2010/08/19/http-error-pages-in-internet-explorer.aspx -->
