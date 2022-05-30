<?php
namespace App\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Model\Usuario;

class AuthController {
  public static function gerarToken($id, $usuario) {
    $payload = [
      'sub' => $id,
      'iss' => 'dentalweb',
      'iat' => time(),
      'id' => $id,
      'user' => $usuario
    ];

    $jwt = JWT::encode($payload, JWT_KEY, 'HS256');

    return $jwt;
  }

  public static function checkAuth() {
    $http_header = apache_request_headers();

    if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
      $jwt = explode (' ', $http_header['Authorization']); // $bearer[0] = 'bearer'; $bearer[1] = 'token jwt';
    
      $decoded = JWT::decode($jwt[1], new Key(JWT_KEY, 'HS256'));
      $decoded_array = (array) $decoded;
      $id = $decoded_array['id'];

      return Usuario::getUsuarioAutenticado($id);
    }

    return false;
  }
}