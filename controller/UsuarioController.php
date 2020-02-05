<?php
namespace controller;
use \model\Usuario;
use \model\OrmUsuario;
use \model\OrmPost;
class UsuarioController extends Controller {

    public function registro() {
        $title = "Registro";
        echo \dawfony\Ti::render("view/RegistroView.phtml", compact('title'));
    }

    public function comprobarRegistro() {
        $usuario = new Usuario;
        $usuario->login = $_POST["login"];
        $usuario->contrasenha = password_hash($_POST["contrasenha"], PASSWORD_DEFAULT);
        $usuario->nombre = $_POST["nombre"];
        $usuario->email = $_POST["email"];
        $orm = new OrmUsuario;
        $filasInsertadas = $orm->registrarUsuario($usuario);
        $title = "Registro";
        echo \dawfony\Ti::render("view/RegistroView.phtml", compact('title', 'filasInsertadas'));
    }

    public function login() {
        $title = "Login";
        echo \dawfony\Ti::render("view/LoginView.phtml", compact('title'));
    }

    public function comprobarLogin() {
        global $URL_PATH;
        $login = $_POST["login"];
        $orm = new OrmUsuario;
        $contrasenhaValida = $orm->recibirContrasenha($login);
        if (password_verify($_POST["contrasenha"], $contrasenhaValida["password"])) {
            $title = "Listado";
            $_SESSION["login"] = $login;
            $_SESSION["rol"] = $login == "admin" ? 1 : 0;
            header("Location: $URL_PATH/");
        } else {
            $error = "Lo sentimos, el usuario o la contraseña no son correctos";
            echo \dawfony\Ti::render("view/LoginView.phtml", compact('title', 'error'));
        }
    }

    public function cerrarSesion() {
        global $URL_PATH;
        unset($_SESSION["rol"]);
        unset($_SESSION["login"]);
        header("Location: $URL_PATH/");
    }

    public function perfil($login) {
        $title = "Perfil de $login";
        $ormPost = new OrmPost;
        $ormUsuario = new OrmUsuario;
        $posts = $ormPost->obtenerPostsPorUsuario($login);
        foreach ($posts as $post) {
            $post->categoria = $ormPost->obtenerCategoria($post->categoria_post_id)["descripcion"];
        }
        $usuario = $ormUsuario->obtenerUsuario($login);
        $seguidores = $ormUsuario->obtenerSeguidores($login);
        $siguiendo = $ormUsuario->obtenerSeguidos($login);
        $loSigues = isset($_SESSION["login"]) ? $ormUsuario->loSigues($_SESSION["login"], $login) > 0 : false;
        echo \dawfony\Ti::render("view/PerfilView.phtml", compact('title', 'login', 'posts', 
        'usuario', 'seguidores', 'siguiendo', 'loSigues'));
    }

    public function seguirUsuario($usuario) {
        global $URL_PATH;
        if (!isset($_SESSION["login"])) {
            throw new \Exception("usuario sin login no tiene boton....paiaso");
        }
        $orm = new OrmUsuario;
        $orm->seguir($_SESSION["login"], $usuario);
        header("Location: $URL_PATH/Perfil/$usuario");
    }

    public function dejarSeguirUsuario($usuario) {
        global $URL_PATH;
        if (!isset($_SESSION["login"])) {
            throw new \Exception("usuario sin login no tiene boton....paiaso");
        }
        $orm = new OrmUsuario;
        $orm->dejarDeSeguir($_SESSION["login"], $usuario);
        header("Location: $URL_PATH/Perfil/$usuario");
    }
}