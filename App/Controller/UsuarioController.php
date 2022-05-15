<?php
namespace App\Controller;
use App\Model\Usuario;

class UsuarioController {
  public function get($id = null) {
    if ($id) {
      return Usuario::getUsuario($id);
    }
    else {
      return Usuario::getUsuarios();
    }
  }

  public function send() {
    return Usuario::sendUsuario($_POST);
  }

  public static function login() {
    return Usuario::login((array) json_decode(file_get_contents('php://input')));
  }

  public function update() {

  }

  public function delete() {

  }
}