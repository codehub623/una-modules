<?php

// require_once(__DIR__.'/jwt/src/BeforeValidException.php');
// require_once(__DIR__.'/jwt/src/ExpiredException.php');
// require_once(__DIR__.'/jwt/src/SignatureInvalidException.php');
// require_once(__DIR__.'/jwt/src/JWT.php');

// use \Firebase\JWT\JWT;

include __DIR__ . '/JWT.php';

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

// $sendgrid_key = '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';
$sendgrid_key = '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';

$base_url = 'localhost/una/page/invite?token=';

if (isset($_POST['data'])) {

  $data = $_POST['data'];
  $data  = json_decode($data);
  $result = array();
  $key = "oowndlc1230j09j@394@!@4rf4%&(e";
  foreach ($data as $d) {
    $now_seconds = time();
    $payload = array(
      "iat" => $now_seconds,
      "exp" => $now_seconds + (60 * 60),
      "claims" => array(
        "id" => $d->id
      )
    );
    $jwt = JWT::encode($payload, $key);
    $decoded = JWT::decode($jwt, $key, array('HS256'));



    $to = $d->email;
    $subject = 'Invitation';
    $from = 'nasatrian1971@email.com';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
      'Reply-To: ' . $from . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

    // Compose a simple HTML email message
    $message = '<html><body>';
    $message .= '<h1 style="color:#f40;">Invitation</h1>';
    $message .= "Please click <a href='$base_url$jwt'>here</a> to rest the password.(available for only 10 minutes)";
    $message .= '</body></html>';

    // Sending email
    if (mail($to, $subject, $message, $headers)) {
      $result[count($result)] = true;
    } else {
      $result[count($result)] = false;
    }







    // $email = new \SendGrid\Mail\Mail();
    // $email->setFrom("kardzavaryan@gmail.com", 'Developer');
    // $email->setSubject("Invitation to C.o.Lab");
    // $email->addTo($d->email);
    // $email->addTo('edithjenkins1992@gmail.com');
    // $email->addContent(
    //   "text/html",
    //   "<h4>Hello, " . $d->name . "</h4>
    //   <p>This is the password reset link. Please click <a href='$base_url$jwt'>here</a> to rest the password.(available for only 10 minutes)</p>
    //   "
    // );
    // $sendgrid = new \SendGrid($sendgrid_key);
    // try {
    //   $response = $sendgrid->send($email);
    //   $result[count($result)] = $response->statusCode();
    // } catch (Exception $e) {
    //   $result[count($result)] = $e->getMessage();
    // }
  }
  echo json_encode(array('success' => true, 'result' => $result));
} else {
  echo json_encode(array('success' => false, 'message' => 'empty data'));
}
