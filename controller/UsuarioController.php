<?php
namespace controller;
use \model\Usuario;
use \model\OrmSocialDaw;
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
        $orm = new OrmSocialDaw;
        $filasInsertadas = $orm->registrarUsuario($usuario);
        $title = "Listado";
        echo \dawfony\Ti::render("view/ListadoView.phtml", compact('title', 'filasInsertadas'));
    }

    public function login() {
        $title = "Login";
        echo \dawfony\Ti::render("view/LoginView.phtml", compact('title'));
    }

    public function comprobarLogin() {
        global $URL_PATH;
        $login = $_POST["login"];
        $orm = new OrmSocialDaw;
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
        $orm = new OrmSocialDaw;
        $posts = $orm->obtenerPostsPorUsuario($login);
        echo \dawfony\Ti::render("view/PerfilView.phtml", compact('title', 'login', 'posts'));
    }
}