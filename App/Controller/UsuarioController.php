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

  public function login() {
    return Usuario::login($_POST);
  }

  public function update() {

  }

  public function delete() {

  }
}