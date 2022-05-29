<?php
namespace App\Controller;
use App\Model\Consulta;

class ConsultaController {
  public function getbydate($param) {
    return Consulta::getConsultasByData($param);
  }

  public function send() {
    return Consulta::sendConsulta((array) json_decode(file_get_contents('php://input')));
  }

  public function delete($id) {
    if ($id) {
      return Consulta::deleteConsulta($id);
    }
    else {
      throw new \Exception("Selecione uma consulta para excluir.");
    }
  }
}