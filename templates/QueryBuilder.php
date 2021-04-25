<?php

// use OAuth\OAuth2\Service\Instagram;

class QueryBuilder
{
  public $conn;
  function __construct($servername, $username, $password, $dbname)
  {
    $this->conn = new mysqli($servername, $username, $password, $dbname);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }
  public function accounts($page = 1, $page_size = 25)
  {
    $offset = ($page - 1) * $page_size;
    $sql = "SELECT  `id`,`name`, `email`, `brand`,`store`,`market`,`flickr`,`instagram` FROM `vnd_test_data`LIMIT $page_size OFFSET $offset";
    $result = $this->conn->query($sql);
    $data = [];
    if ($result) {
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          array_push($data, $row);
        }
      }
    }
    return $data;
  }

  public function createCustomProfileTable()
  {
    $res = array();

    $sql_0 = "CREATE TABLE IF NOT EXISTS vnd_test_data (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      accountId INT(6),
      brand VARCHAR(255) DEFAULT '',
      name VARCHAR(255) DEFAULT '',
      email VARCHAR(255) NOT NULL UNIQUE,
      market VARCHAR(255) DEFAULT '',
      store VARCHAR(255) DEFAULT '',
      flickr VARCHAR(255) DEFAULT '',
      instagram VARCHAR(255) DEFAULT '',
      facebook VARCHAR(255) DEFAULT '',
      added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )";
    if ($this->conn->query($sql_0)) {
      $res[0] = true;
    } else {
      $res[0] = $this->conn->error;
    };


    $sql_1 = "CREATE TABLE IF NOT EXISTS vnd_test_record_table (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      staff_id INT(6),
      data_id INT(6),
      action VARCHAR(255),
      name_from VARCHAR(255) DEFAULT '',
      email_from VARCHAR(255) DEFAULT '',
      brand_from VARCHAR(255) DEFAULT '',
      market_from VARCHAR(255) DEFAULT '',
      store_from VARCHAR(255) DEFAULT '',
      flickr_from VARCHAR(255) DEFAULT '',
      instagram_from VARCHAR(255) DEFAULT '',
      facebook_from VARCHAR(255) DEFAULT '',
      name_to VARCHAR(255) DEFAULT '',
      email_to VARCHAR(255) DEFAULT '',
      brand_to VARCHAR(255) DEFAULT '',
      market_to VARCHAR(255) DEFAULT '',
      store_to VARCHAR(255) DEFAULT '',
      flickr_to VARCHAR(255) DEFAULT '',
      instagram_to VARCHAR(255) DEFAULT '',
      facebook_to VARCHAR(255) DEFAULT '',
      bulk TINYINT(1) DEFAULT 0,
      added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )";
    if ($this->conn->query($sql_1)) {
      $res[1] = true;
    } else {
      $res[1] = $this->conn->error;
    };

    return $res;
  }

  public function insertData($data, $accountId = 0)
  {

    $r = 0;
    $rr = array();
    foreach ($data as $d) {
      $r++;
      $rr[count($rr)] = $this->createAccountFromCustomData($d,$accountId,1);
      if ($r > 3) {
        break;
      }
    }
    return $rr;
  }

  public function get_latest_row($table)
  {
    $query = "SELECT * FROM $table ORDER BY `id` DESC LIMIT 1";
    $result = $this->conn->query($query)->fetch_assoc();
    return $result;
  }


  public function createAccountFromCustomData($data, $accountId, $bulk = 0, $action = 'create', $ignore = false)
  {
    $time = time();
    $select_query_sys_accounts = "SELECT * FROM sys_accounts where `email`='$data[email]'";
    $result_select_query_sys_accounts = $this->conn->query($select_query_sys_accounts)->fetch_assoc();
    $select_query_vnd_table = "SELECT * FROM vnd_test_data where `email`='$data[email]'";
    $result_select_query_vnd_table = $this->conn->query($select_query_vnd_table)->fetch_assoc();

    if (!$result_select_query_sys_accounts && !$result_select_query_vnd_table) {


      $insert_query_bx_persons = "INSERT INTO bx_persons_data (`added`, changed,`fullname`) VALUES ('$time','$time','$data[name]')";
      $this->conn->query($insert_query_bx_persons);
      $id_bx_persons = $this->get_latest_row('bx_persons_data')['id'];

      $insert_query_sys_acounts = "INSERT INTO sys_accounts (`name`, `email`,`role`) VALUES ('$data[name]', '$data[email]', 1)";
      $this->conn->query($insert_query_sys_acounts);
      $id_sys_accounts = $this->get_latest_row('sys_accounts')['id'];

      $insert_query_sys_profiles = "INSERT INTO sys_profiles (`account_id`,`type`, `content_id`) VALUES ($id_sys_accounts,'bx_persons', $id_bx_persons)";
      $this->conn->query($insert_query_sys_profiles);
      $id_sys_profiles = $this->get_latest_row('sys_profiles')['id'];

      $update_query_sys_accounts = "UPDATE sys_accounts SET `profile_id`=$id_sys_profiles WHERE `id`=$id_sys_accounts";
      $this->conn->query($update_query_sys_accounts);

      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        array_push($keys, "`$key`");
        array_push($values, "'$value'");
      }

      $keys = join(',', $keys) . ",`accountId`";
      $values = join(',', $values) . ",'$id_sys_accounts'";
      $insert_query_vnd_table  = "INSERT INTO vnd_test_data ($keys) VALUES ($values)";
      $this->conn->query($insert_query_vnd_table);
      $id_vnd_table = $this->get_latest_row('vnd_test_data')['id'];

      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        array_push($keys, "`" . $key . "_to`");
        array_push($values, "'$value'");
      }
      $keys = join(',', $keys) . ",`staff_id`,`data_id`,`action`,`bulk`";
      $values = join(',', $values) . ",'$accountId','$id_vnd_table','$action','$bulk'";


      $insert_query_vnd_test_recod  = "INSERT INTO vnd_test_record_table ($keys) VALUES ($values)";
      $this->conn->query($insert_query_vnd_test_recod);

      return array('success' => true, 'status_sys' => false, 'status_vnd' => false, 'action' => $action, 'id' => $id_vnd_table, 'recode' => true);
    } else if ($result_select_query_sys_accounts && !$result_select_query_vnd_table) {


      $time = time();
      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        array_push($keys, "`$key`");
        array_push($values, "'$value'");
      }

      $keys = join(',', $keys) . ",`accountId`";
      $values = join(',', $values) . ",'$result_select_query_sys_accounts[id]'";
      $insert_query_vnd_table  = "INSERT INTO vnd_test_data ($keys) VALUES ($values)";
      $this->conn->query($insert_query_vnd_table);
      $id_vnd_table = $this->get_latest_row('vnd_test_data')['id'];

      $keys = array();
      $values = array();
      foreach ($data as $key => $value) {
        array_push($keys, "`" . $key . "_to`");
        array_push($values, "'$value'");
      }
      $keys = join(',', $keys) . ",`staff_id`,`data_id`,`action`,`bulk`";
      $values = join(',', $values) . ",'$accountId','$id_vnd_table','$action','$bulk";

      $insert_query_vnd_test_recod  = "INSERT INTO vnd_test_record_table ($keys) VALUES ($values)";
      $this->conn->query($insert_query_vnd_test_recod);

      return array('success' => true, 'status_sys' => true, 'status_vnd' => false, 'action' => $action, 'id' => $id_vnd_table, 'recode' => true);
    } else if ($result_select_query_sys_accounts && $result_select_query_vnd_table) {
      if ($ignore == true) {
        return $this->update($data, $accountId, $bulk);
      } else {
        return array('success' => false, 'status_sys' => true, 'status_vnd' => true, 'ignore' => false);
      }
    } else {
      return array('success' => false, 'error' => "Logic Error");
    }
  }



  public function update($data, $accountId, $bulk = 0, $action = 'edit')
  {

    $select_query_vnd_table = "SELECT * FROM vnd_test_data where `email`='$data[email]'";
    $result_select_query_vnd_table = $this->conn->query($select_query_vnd_table)->fetch_assoc();


    $update_data = array();
    foreach ($data as $key => $value) {
      array_push($update_data, "`$key`='$value'");
    }
    $update_data = join(',', $update_data);

    $update_query_vnd_test_data =  "UPDATE vnd_test_data SET $update_data WHERE `email`=$data[email]";
    $this->conn->query($update_query_vnd_test_data);

    $keys_from = array();
    $values_from = array();

    foreach ($result_select_query_vnd_table as $key => $value) {
      if ($key == 'id' || $key == 'accountId' || $key == 'added_date' || $key == 'updated_date') {
        continue;
      } else {
        array_push($keys_from, "`" . $key . "_from`");
        array_push($values_from, "'$value'");
      }
    }
    $keys_from = join(',', $keys_from);
    $values_from = join(',', $values_from);

    $keys = array();
    $values = array();
    foreach ($data as $key => $value) {
      array_push($keys, "`" . $key . "_to`");
      array_push($values, "'$value'");
    }
    $keys = join(',', $keys);
    $values = join(',', $values);

    $keys = $keys . ",`staff_id`,`data_id`,`action`,`bulk`";
    $values = $values . ",'$accountId','$result_select_query_vnd_table[id]','$action','$bulk'";

    $insert_query_vnd_test_recod  = "INSERT INTO vnd_test_record_table ($keys_from,$keys) VALUES ($values_from,$values)";
    $this->conn->query($insert_query_vnd_test_recod);
    return array('success' => true, 'status_sys' => true, 'status_vnd' => true, 'action' => $action, 'id' => $result_select_query_vnd_table['id'], 'recode' => true);
  }

  public function delete($data, $accountId, $action = 'delete', $force = false)
  {

    $p = '';
    $select_query_vnd_table = "SELECT * FROM vnd_test_data where `email`='$data[email]'";
    $result_select_query_vnd_table = $this->conn->query($select_query_vnd_table)->fetch_assoc();

    $delete_query_vnd_test_data = "DELETE FROM vnd_test_data WHERE `email`='$data[email]'";
    if ($this->conn->query($delete_query_vnd_test_data)) {
      $p = 'true';
    } else {
      $p = $this->conn->error;;
    };

    if ($force == true) {
      $delete_query_sys_accounts = "DELETE FROM sys_accounts WHERE `email`='$data[email]'";
      $this->conn->query($delete_query_sys_accounts);
    }

    $keys_from = array();
    $values_from = array();

    foreach ($result_select_query_vnd_table as $key => $value) {
      if ($key == 'id' || $key == 'accountId' || $key == 'added_date' || $key == 'updated_date') {
        continue;
      } else {
        array_push($keys_from, "`" . $key . "_from`");
        array_push($values_from, "'$value'");
      }
    }

    $keys_from = join(',', $keys_from);
    $values_from = join(',', $values_from);

    $keys_from = $keys_from . ",`staff_id`,`data_id`,`action`";
    $values_from = $values_from . ",'$accountId','$result_select_query_vnd_table[id]','$action'";

    $insert_query_vnd_test_recod  = "INSERT INTO vnd_test_record_table ($keys_from) VALUES ($values_from)";
    $this->conn->query($insert_query_vnd_test_recod);

    return array('success' => true, 'status_sys' => true, 'status_vnd' => true, 'action' => $action, 'id' => $result_select_query_vnd_table['id'], 'recode' => true);
  }
  public function setpassword($id, $password, $salt)
  {
    $select_query_vnd_table = "SELECT * FROM vnd_test_data where `id`='$id'";
    $result_select_query_vnd_table = $this->conn->query($select_query_vnd_table)->fetch_assoc();

    $sys_accounts_update_query  = "UPDATE sys_accounts SET `password`='$password',`salt`='$salt',`email_confirmed`='1' WHERE `id`=$result_select_query_vnd_table[accountId]";
    if ($this->conn->query($sys_accounts_update_query) === TRUE) {
      return true;
    } else {
      return $this->conn->error;
    }
  }
}
