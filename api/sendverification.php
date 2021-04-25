<?php

require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;


include __DIR__ . '/../modules/templates/QueryBuilder.php';

require_once(__DIR__ . '/sendgrid/sendgrid-php.php');



/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
// $jwt = JWT::encode($token, $key);
// $decoded = JWT::decode($jwt, $key, array('HS256'));



$mysql = new QueryBuilder('localhost', 'root', '', 'una');

$sendgrid_key = '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';

$base_url = 'localhost/una/page/invite?token=';

if (isset($_POST['data'])) {

  $data = $_POST['data'];
  $data  = json_decode($data);
  $result = array();
  foreach ($data as $d) {
    $now_seconds = time();
    $key = "oowndlc1230j09j@394@!@4rf4%&(e";
    $payload = array(
      "iat" => $now_seconds,
      "exp" => $now_seconds + (60 * 60),
      "claims" => array(
        "id" => $d->id
      )
    );
    $jwt = JWT::encode($payload, $key);
    $decoded = JWT::decode($jwt, $key, array('HS256'));
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("bootcoman@gmail.com", 'Developer');
    $email->setSubject("Invitation to C.o.Lab");
    // $email->addTo($d->email);
    $email->addTo('edithjenkins1992@gmail.com');
    $email->addContent(
      "text/html",
      "<h4>Hello, " . $d->name . "</h4>
      <p>This is the password reset link. Please click <a href='$base_url$jwt'>here</a> to rest the password.(available for only 10 minutes)</p>
      "
    );
    $sendgrid = new \SendGrid($sendgrid_key);
    try {
      $response = $sendgrid->send($email);
      $result[count($result)] = array(
        'id' => $d->id,
        'name' => $d->name,
        'email' => $d->email,
      );
    } catch (Exception $e) {
      $result[count($result)] = $e->getMessage();
    }
  }
  echo json_encode(array('success' => true, 'result' => $result));
} else {
  echo json_encode(array('success' => false, 'message'=>'empty data'));
}
