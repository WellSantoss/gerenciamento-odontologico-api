<?php
namespace App\Controller;
use App\Model\Retirada;

class RetiradaController {
  public function get($param = null) {
    if ($param) {
      return Retirada::getRetiradas($param);
    }
    else {
      return Retirada::getRetiradas();
    }
  }

  public function send() {
    return Retirada::sendRetirada((array) json_decode(file_get_contents('php://input')));
  }

  public function delete($id) {
    if ($id) {
      return Retirada::deleteRetirada((array) json_decode(file_get_contents('php://input')), $id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}