<?php

include __DIR__ . '/../modules/templates/QueryBuilder.php';

$mysql = new QueryBuilder('localhost', 'root', '', 'una');


if (isset($_POST['data']) && isset($_POST['accountId'])) {
  $accountId = $_POST['accountId'];
  $data = (array) json_decode($_POST['data']);
  $result = $mysql->update($data, $accountId);
  echo json_encode($result);
} else {
  echo json_encode(array('success' => false));
}
