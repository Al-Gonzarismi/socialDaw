<?php
namespace controller;
use \model\OrmSocialDaw;
use \model\Post;
class PostController extends Controller {
    public function listado() {
        $title="Listado";
        $orm = new OrmSocialDaw;
        $posts = $orm->obtenerTodosLosPosts();
        echo \dawfony\Ti::render("view/ListadoView.phtml", compact('title', 'posts'));
    }

    public function addPost() {
        $title="Añadir Post";
        $genero = "";
        $resumen = "";
        $comentario = "";
        $errorImagen = "";
        echo \dawfony\Ti::render("view/AddPostView.phtml", compact('title', 'genero', 'resumen', 'comentario', 'errorImagen'));
    }

    public function comprobarPost() {
        extract($_POST);
        $resumen = sanitizar($resumen);
        $comentario = sanitizar($comentario);
        $img = $_FILES["imagen"];
        if (!validarImagen($img)) {
            $title="Añadir Post";
            $errorImagen = "No es una imagen";
            echo \dawfony\Ti::render("view/AddPostView.phtml", compact('title', 'genero', 'resumen', 'comentario', 'errorImagen'));
        } else {
            var_dump($img);
        }
    }
}    