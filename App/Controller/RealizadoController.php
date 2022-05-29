<?php
namespace App\Controller;
use App\Model\Realizado;

class RealizadoController {
  public function get($id_consulta) {
    return Realizado::getProcedimentosRealizados($id_consulta);
  }

  public function send() {
    return Realizado::sendProcedimento((array) json_decode(file_get_contents('php://input')));
  }

  // public function update($id) {
  //   return Realizado::updateRealizado($_POST, $id);
  // }

  public function delete($id) {
      return Realizado::deleteRealizado($id);
  }
}