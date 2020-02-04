<?php
namespace model;
use \dawfony\Klasto;
class OrmUsuario
{
    public function registrarUsuario($usuario) {
        $bd = Klasto::getInstance();
        $login = $usuario->login;
        $contrasenha = $usuario->contrasenha;
        $rol = 0;
        $nombre = $usuario->nombre;
        $email = $usuario->email;
        $params = [$login, $contrasenha, $rol, $nombre, $email];
        $sql = "INSERT INTO usuario VALUES (?, ?, ?, ?, ?)";
        return $bd->execute($sql, $params);
    }

    public function recibirContrasenha($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT password FROM usuario WHERE login = ?";
        return $bd->queryOne($sql, $params);
    }

    public function obtenerUsuario($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT nombre, email FROM usuario WHERE login = ?";
    }

}