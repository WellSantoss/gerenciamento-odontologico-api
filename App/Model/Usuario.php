<?php
namespace App\Model;
use App\Controller\Auth;

class Usuario {
  public static function getUsuario(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT ativo, administrador, nome, foto, usuario FROM usuarios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getUsuarios() {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT ativo, administrador, nome, foto, usuario FROM usuarios';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum usuário encontrado.");
    }
  }

  public static function sendUsuario(array $data) {
    $fileName = $_FILES['foto']['name'];
    $tempPath = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
        
    if (!empty($fileName)) {
      $fileName = date("d_m_y_h_i_s", time()) . $fileName;
      $upload_path = 'upload/';
      $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

      if (in_array($fileExt, $valid_extensions)) {				
        if ($fileSize < 5000000) { // até '5MB'
          if (!move_uploaded_file($tempPath, $upload_path . $fileName)) {
            throw new \Exception("Erro ao fazer upload da imagem, tente novamente.");
          }
        }
        else {		
          throw new \Exception("Selecione uma imagem de até 5MB.");
        }
      }
      else {		
        throw new \Exception("Tipo de arquivo não aceito. Selecione um arquivo com uma das extensões a seguir: jpeg, jpg, png, gif");
      }
    }
    else {		
      throw new \Exception("Selecione uma imagem para continuar.");	
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO usuarios (ativo, administrador, foto, nome, usuario, senha) VALUES (:ativo, :administrador, :foto, :nome, :usuario, :senha)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':administrador', $data['administrador']);
    $stmt->bindValue(':foto', $fileName);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':usuario', $data['usuario']);
    $stmt->bindValue(':senha', password_hash($data['senha'], PASSWORD_DEFAULT));
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Usuário cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar usuário.");
    }
  }

  public static function login($data) {
    $usuario = $data['usuario'];
    $senha = $data['senha'];

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, nome, usuario, foto, administrador, senha FROM usuarios WHERE ativo = true and usuario = :usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt -> fetch(\PDO::FETCH_ASSOC);
      $hash = $row["senha"];

      if (password_verify($senha, $hash)) {
        $cargo = $row["administrador"] == '1' ? $row["administrador"] : self::getCargo($row["id"]);
        $token = Auth::generateToken($row["nome"], $row["usuario"]);

        return array("nome" => $row["nome"], "cargo" => $cargo, "foto" => $row["foto"], "token" => $token);
      }
      else {
        throw new \Exception("Senha incorreta.");
      }

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getCargo(int $id_usuario) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id FROM dentistas WHERE id_usuario = :id_usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_usuario', $id_usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return '2';
    }
    else {
      return '3';
    }
  }
}