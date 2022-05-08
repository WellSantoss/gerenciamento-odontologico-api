<?php
namespace App\Service;
use App\Model\Usuario;

class UsuarioService {
  public function get($id = null) {
    if ($id) {
      return Usuario::getUsuario($id);
    }
    else {
      return Usuario::getUsuarios();
    }
  }

  public function post() {

  }

  public function update() {

  }

  public function delete() {

  }
}