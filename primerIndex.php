<?php
require 'vendor/autoload.php';
require 'cargarconfig.php';//autoload 

use NoahBuscher\Macaw\Macaw;
use controller\PruebaController;

echo "<pre>" . var_dump($_SERVER["REQUEST_URI"]) . var_dump($_SERVER["QUERY_STRING"]) . "</pre>";
Macaw::get($URL_PATH . '/', function () {//si recibes una url que coincida con /socialdaw/ entonces la procesamos con una retrollamada
  echo 'principal';//Porque la url seria http://localhost/socialdaw
});

Macaw::get($URL_PATH . '/demo', function () {//retrollamada
  echo 'demo';//La url seria http://localhost/socialdaw/demo
});

// "slugs" con expresiones regulares en la url
// Un slug es una parte variable de una url amigable para SEO.
// Equivalenet a un parámetro.
// Macaw admite tres abreviaturas:  :any, :int :all

Macaw::get($URL_PATH . '/demo/(:any)',function ($slug) {//(:any)->Indica expresion regular. Cualquier cosa. Si fuera /(:int) solo enteros
  echo "demo $slug";//La url seria http://localhost/socialdaw/demo/algo(Variable que ponemos como una expresion regular)
});

// Pasando el nombre de una clase de controlador y un método,
// Macaw lo puede invocar directamente.

Macaw::get($URL_PATH . '/demo2/(:any)', "controller\PruebaController@foo");//Instacia la clase PruebaController y llama al método foo


// Captura de URL no definidas.
Macaw::error(function() {
  echo '404 :: Not Found';
});

Macaw::dispatch();//Procesa la url
