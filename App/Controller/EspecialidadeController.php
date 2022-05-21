<?php
namespace App\Controller;
use App\Model\Especialidade;

class EspecialidadeController {
  public function get($param = null) {
    if ($param) {
      if (gettype($param) == 'integer') {
        return Especialidade::getEspecialidade($param);
      }
      else {
        return Especialidade::getEspecialidades($param);
      }
    }
    else {
      return Especialidade::getEspecialidades();
    }
  }

  public function send() {
    return Especialidade::sendEspecialidade((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Especialidade::updateEspecialidade($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Especialidade::deleteEspecialidade($id);
    }
    else {
      throw new \Exception("Selecione uma especialidade para excluir.");
    }
  }
}