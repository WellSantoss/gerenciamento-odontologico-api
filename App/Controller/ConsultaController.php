<?php
namespace App\Controller;
use App\Model\Consulta;

class ConsultaController {
  public function getbydate($param) {
    return Consulta::getConsultasByData($param);
  }

  public function getall($param = null) {
    if ($param) {
      return Consulta::getConsultas($param);
    }
    else {
      return Consulta::getConsultas();
    }
  }

  public function getdentista($id_dentista) {
    return Consulta::getConsultasByDentista($id_dentista);
  }

  public function send() {
    return Consulta::sendConsulta((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Consulta::updateConsulta((array) json_decode(file_get_contents('php://input')), $id);
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