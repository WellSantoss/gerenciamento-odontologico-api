<?php
namespace App\Controller;
use App\Model\Paciente;

class PacienteController {
  public function get($id) {
    if ($id) {
      return Paciente::getPaciente($id);
    }
    else {
      throw new \Exception("Selecione um paciente para obter as informações.");
    }
  }

  public function getall($param = null) {
    if ($param) {
      return Paciente::getPacientes($param);
    }
    else {
      return Paciente::getPacientes();
    }
  }

  public function send() {
    return Paciente::sendPaciente((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Paciente::updatePaciente((array) json_decode(file_get_contents('php://input')), $id);
  }

  public function updateobs($id) {
    return Paciente::updateObsPaciente((array) json_decode(file_get_contents('php://input')), $id);
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