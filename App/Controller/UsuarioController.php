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

  public function update($id) {
    return Usuario::updateUsuario($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Usuario::deleteUsuario($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}