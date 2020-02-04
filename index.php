<?php
require 'vendor/autoload.php';
require 'cargarconfig.php'; 

use NoahBuscher\Macaw\Macaw;
use controller\PruebaController;

session_start();

Macaw::get($URL_PATH . '/', "controller\PostController@listado");

Macaw::get($URL_PATH . '/Registrate', "controller\UsuarioController@registro");

Macaw::post($URL_PATH . '/Registrate', "controller\UsuarioController@comprobarRegistro");

Macaw::get($URL_PATH . '/Login', "controller\UsuarioController@login");

Macaw::post($URL_PATH . '/Login', "controller\UsuarioController@comprobarLogin");

Macaw::get($URL_PATH . '/CerrarSesion', "controller\UsuarioController@cerrarSesion");

Macaw::get($URL_PATH . '/Perfil/(:any)', "controller\UsuarioController@perfil");

Macaw::get($URL_PATH . '/AddPost', "controller\PostController@addPost");

Macaw::post($URL_PATH . '/AddPost', "controller\PostController@comprobarPost");

Macaw::error(function() {
  echo '404 :: Not Found';
});

Macaw::dispatch();