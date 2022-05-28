<?php
namespace App\Controller;
use App\Model\Dentista;

class DentistaController {
  public function getall($param = null) {
    if ($param) {
      return Dentista::getDentistas($param);
    }
    else {
      return Dentista::getDentistas();
    }
  }

  public function send() {
    return Dentista::sendDentista((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Dentista::updateDentista((array) json_decode(file_get_contents('php://input')), $id);
  }

  public function delete($id) {
    if ($id) {
      return Dentista::deleteDentista($id);
    }
    else {
      throw new \Exception("Selecione um dentista para excluir.");
    }
  }
}