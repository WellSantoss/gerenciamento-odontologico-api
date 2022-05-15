<?php
header('Content-Type: application/json');
if (isset($_SERVER['HTTP_ORIGIN'])) {
  // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
  // you want to allow, and if so:
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // may also be using PUT, PATCH, HEAD etc
  }    

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  }

  exit(0);
}

require_once 'vendor/autoload.php';
use App\Controller\Auth;

$url = explode('/', $_GET['url']);
$controller = 'App\Controller\\'.ucfirst($url[1]).'Controller';
$method = strtolower($url[2]);
$param = array_slice($url, 3);

if (Auth::checkAuth($method)) {
  try {
    $response = call_user_func_array(array(new $controller, $method), $param);

    http_response_code(200);
    echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
  } catch (\Exception $e) {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
  }
}
else {
  http_response_code(401);
  echo json_encode(array('status' => 'error', 'data' => 'Não autenticado.'), JSON_UNESCAPED_UNICODE);
}
