<?php
namespace model;
use \dawfony\Klasto;
class OrmPost
{
    public function obtenerTodosLosPosts() {
        $bd = Klasto::getInstance();
        $params = [];
        $sql = "SELECT fecha, resumen, texto, foto, categoria_post_id, usuario_login FROM post";
        return $bd->query($sql, $params, "model\Post");
    }

    public function obtenerPostsPorUsuario($login) {
        $bd = Klasto::getInstance();
        $params = [$login];
        $sql = "SELECT fecha, resumen, texto, foto, categoria_post_id, usuario_login FROM post WHERE usuario_login = ?";
        return $bd->query($sql, $params, "model\Post");
    }
}