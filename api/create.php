<?php

include __DIR__ . '/../modules/templates/QueryBuilder.php';

require_once(__DIR__ . '/sendgrid/sendgrid-php.php');

$mysql = new QueryBuilder('localhost', 'root', '', 'una');

$sendgrid_key = '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';


if (isset($_POST['data']) || isset($_POST['accountId'])) {
  $accountId = $_POST['accountId'];
  $data = (array) json_decode($_POST['data']);
  $result = $mysql->createAccountFromCustomData($data,$accountId);
  echo json_encode($result);
} else {
  echo json_encode(array('success' => false, 'message'=>'empty data'));
}
