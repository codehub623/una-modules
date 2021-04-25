<?php

include __DIR__ . '/../modules/templates/QueryBuilder.php';

$mysql = new QueryBuilder('localhost', 'root', '', 'una');

if ($_FILES['csv']) {
  $csv = array();
  // $csv = array_map('str_getcsv', $_FILES['file']['tmp_name']);
  // echo 'ok';

  $tmpName = $_FILES['csv']['tmp_name'];

  if (($handle = fopen($tmpName, 'r')) !== FALSE) {
    // necessary if a large csv file
    set_time_limit(0);
    $row = 0;
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
      // $col_count = count($data);
      $csv[$row]['name'] = $data[0] ? $data[0] : "";
      $csv[$row]['brand'] = $data[1] ? $data[1] : "";
      $csv[$row]['email'] = $data[2] ? $data[2] : "";
      $csv[$row]['store'] = $data[3] ? $data[3] : "";
      $csv[$row]['market'] = $data[4] ? $data[4] : "";
      $csv[$row]['flickr'] = $data[5] ? $data[5] : "";
      $csv[$row]['instagram'] = $data[6] ? $data[6] : "";
      // $csv[$row]['facebook'] = $data[7] ? $data[7] : "";
      $row++;
    }

    fclose($handle);
  }
  echo json_encode(array('success' => true, 'status' => $mysql->insertData($csv)));
} else {

  echo json_encode(array('success' => false, 'message'=>'empty data'));
}
