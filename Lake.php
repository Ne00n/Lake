<?php
class Lake {

  private $dbHost;
  private $dbUser;
  private $dbPassword;
  private $dbDatabase;
  private $success = true;
  private $errors = array();
  private $Database;

  //input
  private $type;
  private $insert;
  private $select;
  private $into;
  private $intoRaw;
  private $from;
  private $where;
  private $whereRaw;
  private $var;

  public function __construct($dbHost,$dbUser,$dbPassword,$dbDatabase) {
    $this->dbHost = $dbHost;
    $this->dbUser = $dbUser;
    $this->dbPassword = $dbPassword;
    $this->dbDatabase = $dbDatabase;
    $this->initDB();
  }

  private function initDB() {
      $this->Database = new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbDatabase);
      if ($this->Database->connect_error) {
        $this->success = false;
        $this->errors[] ="Not connected, error: " .   $this->Database->connect_error;
      }
  }

  public function INSERT($input) {
    $this->type = 'insert';
    $this->insert = $input;
    return $this;
  }

  public function INTO($input) {
    $i = 1;
    foreach ($input as $key => $value) {
      if ($i == count($input)) {
        $this->into .= $key;
      } else {
        $this->into .= $key.',';
      }
      $i++;
    }
    $this->intoRaw = $input;
    return $this;
  }

  public function DELETE() {
    $this->type = 'delete';
    return $this;
  }

  public function SELECT($input) {
    $this->type = 'select';
    $this->select = implode(',',$input);
    return $this;
  }

  public function FROM($input) {
    $this->from = $input;
    return $this;
  }

  public function WHERE($input) {
    $i = 1;
    foreach ($input as $key => $value) {
      if ($i == count($input)) {
        $this->where .= $key.' = ?';
      } else {
        $this->where .= $key.' = ? AND ';
      }
      $i++;
      $this->whereRaw[$key] = $value;
    }
    return $this;
  }

  public function OR() {
    $this->where .= ' OR ';
    return $this;
  }

  public function VAR($input) {
    $this->var = array($input);
    return $this;
  }

  public function DONE() {
    $response = array();
    switch ($this->type) {
    case 'select':
      //SELECT REQUEST
      $values = array();
      $sql = "SELECT ".$this->select." FROM ".$this->from;
      if (!empty($this->where)) { $sql .= " WHERE ".$this->where; }
      $stmt = $this->Database->prepare($sql);
      if (false==$stmt) { $this->success = false; $this->errors[] = 'prepare() failed: ' . $this->Database->error; }

      foreach($this->whereRaw as $key => $value) {
          $values[$key] = &$this->whereRaw[$key];
      }

      $result_params = array_merge($this->var,$values);
      $result = call_user_func_array(array($stmt, 'bind_param'), $result_params);
      if (false==$result) { $this->success = false; $this->errors[] = 'bind_param() failed: ' . $this->Database->error; }

      $result = $stmt->execute();
      if (false==$result) { $this->success = false; $this->errors[] = 'execute() failed: ' . $this->Database->error; }
      $result = $stmt->get_result();
      //Build the Array
      while ($row = $result->fetch_assoc()) {
        $response[] = $row;
      }
      $stmt->close();

      $this->cleanUP();
      return $response;
    case 'insert':
      //INSERT REQUEST
      $values = array();
      $sql = "INSERT INTO ".$this->insert."(".$this->into.") VALUES (".$this->buildPlaceHolders($this->into).")";
      $stmt = $this->Database->prepare($sql);
      if (false==$stmt) { $this->success = false; $this->errors[] = 'prepare() failed: ' . $this->Database->error; }

      foreach($this->intoRaw as $key => $value) {
          $values[$key] = &$this->intoRaw[$key];
      }

      $result_params = array_merge($this->var,$values);
      $result = call_user_func_array(array($stmt, 'bind_param'), $result_params);
      if (false==$result) { $this->success = false; $this->errors[] = 'bind_param() failed: ' . $this->Database->error; }

      $result = $stmt->execute();
      if (false==$result) { $this->success = false; $this->errors[] = 'execute() failed: ' . $this->Database->error; }
      $insertID = $this->Database->insert_id;
      $stmt->close();

      $this->cleanUP();
      return $insertID;
    case 'delete':
      //DELETE REQUEST
      $values = array();
      $sql = "DELETE FROM ".$this->from." WHERE ".$this->where;
      $stmt = $this->Database->prepare($sql);
      if (false==$stmt) { $this->success = false; $this->errors[] = 'prepare() failed: ' . $this->Database->error; }

      foreach($this->whereRaw as $key => $value) {
          $values[$key] = &$this->whereRaw[$key];
      }

      $result_params = array_merge($this->var,$values);
      $result = call_user_func_array(array($stmt, 'bind_param'), $result_params);
      if (false==$result) { $this->success = false; $this->errors[] = 'bind_param() failed: ' . $this->Database->error; }

      $result = $stmt->execute();
      if (false==$result) { $this->success = false; $this->errors[] = 'execute() failed: ' . $this->Database->error; }
      $stmt->close();

      $this->cleanUP();
      break;
    }
  }

  private function cleanUP() {
    $this->type = NULL;
    $this->insert = NULL;
    $this->select = NULL;
    $this->into = NULL;
    $this->intoRaw = NULL;
    $this->from = NULL;
    $this->where = NULL;
    $this->whereRaw = NULL;
    $this->var = NULL;
  }

  public function buildPlaceHolders($data) {
    $response = '';
    for ($i = 1; $i <= count($data); $i++) {
      if ($i == count($data)) {
        $response .= '?';
      } else {
        $response .= '?,';
      }
    }
    return $response;
  }

  public function getSuccess() {
    return $this->success;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function get() {
    return $this->Database;
  }

}
?>
