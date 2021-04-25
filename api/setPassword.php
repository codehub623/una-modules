<?php

require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;




/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
// $jwt = JWT::encode($token, $key);
// $decoded = JWT::decode($jwt, $key, array('HS256'));

include __DIR__ . '/../modules/templates/QueryBuilder.php';

$key = "oowndlc1230j09j@394@!@4rf4%&(e";

$mysql = new QueryBuilder('localhost', 'root', '', 'una');

if (isset($_POST['data'])) {

  $data = $_POST['data'];
  $data  = json_decode($data);
  try {
    JWT::$leeway = 10;
    $decoded = JWT::decode($data->token, $key, array('HS256'));
    $id = $decoded->claims->id;
    $sPassword = $data->password;
    $sSalt  = genRndPwd(8, true);
    $sPasswordHash = encryptUserPwd($sPassword, $sSalt);
    $result = $mysql->setpassword($id, $sPasswordHash, $sSalt);
    if ($result === TRUE) {
      echo json_encode(array('success' => true));
    } else {
      echo json_encode(array('success' => false, 'error' => $result));
    }
  } catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => 'invalid data or expired data'));
  }
} else {
  echo json_encode(array('success' => false, 'message' => 'empty data'));
}


function genRndPwd($iLength = 8, $bSpecialCharacters = true)
{
  $sPassword = '';
  $sChars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";

  if ($bSpecialCharacters === true)
    $sChars .= "!?=/&+,.";

  for ($i = 0; $i < $iLength; $i++) {
    $x = mt_rand(0, strlen($sChars) - 1);
    $sPassword .= $sChars[$x];
  }

  return $sPassword;
}

function encryptUserPwd($sPwd, $sSalt)
{
  return sha1(md5($sPwd) . $sSalt);
}
