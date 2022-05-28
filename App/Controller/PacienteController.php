<?php
namespace App\Controller;
use App\Model\Paciente;

class PacienteController {
  public function get($param = null) {
    if ($param) {
      return Paciente::getPacientes($param);
    }
    else {
      return Paciente::getPacientes();
    }
  }

  public function getobs($id) {
    if ($id) {
      return Paciente::getObsPaciente($id);
    }
    else {
      throw new \Exception("Selecione um paciente para obter as informações.");
    }
  }

  public function send() {
    return Paciente::sendPaciente((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Paciente::updatePaciente($_POST, $id);
  }

  public function updateobs($id) {
    return Paciente::updateObsPaciente($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Paciente::deletePaciente($id);
    }
    else {
      throw new \Exception("Selecione um paciente para excluir.");
    }
  }
}