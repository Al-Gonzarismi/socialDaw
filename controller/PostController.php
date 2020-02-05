<?php
namespace controller;
use \model\OrmPost;
//use \model\OrmComentarios;
use \model\Post;
class PostController extends Controller {
    public function listado() {
        $title="Listado";
        $orm = new OrmPost;
        $posts = $orm->obtenerTodosLosPosts();
        foreach ($posts as $post) {
            $post->categoria = $orm->obtenerCategoria($post->categoria_post_id)["descripcion"];
            if (isset($_SESSION["login"])) {
                $post->like = $orm->tieneLike($post->id, $_SESSION["login"]);
            }
            $post->numLikes = $orm->contarLikes($post->id);
        }
        $orm = new OrmComentarios;
        foreach ($posts as $post) {
            $comentario = $orm->obtenerComentariosDePost($post->id);
            $post->numComentarios = $orm->cuentaComentarios($post->id);
        }
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
        global $URL_PATH;
        extract($_POST);
        $resumen = sanitizar($resumen);
        $comentario = sanitizar($comentario);
        $img = $_FILES["imagen"];
        if (!validarImagen($img)) {
            $title="Añadir Post";
            $errorImagen = "No es una imagen";
            echo \dawfony\Ti::render("view/AddPostView.phtml", compact('title', 'genero', 'resumen', 'comentario', 'errorImagen'));
        } else {
            $post = new Post;
            $post->fecha = date('Y-m-d H:i:s');
            $post->resumen = $resumen;
            $post->texto = $comentario;
            $post->foto = $img["name"];
            $post->categoria_post_id = $genero;
            $post->usuario_login = $_SESSION["login"];
            $orm = new OrmPost;
            $orm->annadirPost($post);
            header("Location: $URL_PATH/");
        }
    }

    public function visualizarPost($id) {
        $orm = new OrmPost;
        $post = $orm->obtenerPostsPorId($id);
        $post->categoria = $orm->obtenerCategoria($post->categoria_post_id)["descripcion"];
        if (isset($_SESSION["login"])) {
            $post->like = $orm->tieneLike($post->id, $_SESSION["login"]);
        }
        $post->numLikes = $orm->contarLikes($post->id);
        $orm = new OrmComentarios;
        $comentario = $orm->obtenerComentariosDePost($post->id);
        $post->numComentarios = $orm->cuentaComentarios($post->id);
        echo \dawfony\Ti::render("view/PostView.phtml", compact('post'));
    }
}    