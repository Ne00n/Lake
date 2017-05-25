<?php

  include 'Lake.php';
  use PHPUnit\Framework\TestCase;

  class TestsMain extends TestCase {

    private $Lake;

    public function setUp() {
      $this->Lake = new Lake('127.0.0.1','lake','lake','lake');
      if ($this->Lake->getSuccess() === false) {
        die('MySQL Connection failed');
      }
    }

    private function validateStatus() {
      if ($this->Lake->getSuccess() === false) { return false; }
      if (!empty($this->Lake->getErrors())) { return false; }
      return true;
    }

    public function testComponents() {
      //INSERT
      $insertID = $this->Lake->INSERT('Users')->INTO(array('Name' => 'Test'))->VAR('s')->DONE();
      $this->assertEquals($insertID,1);
      $this->assertEquals($this->validateStatus(),true);
      $insertIDSecond = $this->Lake->INSERT('Users')->INTO(array('Name' => 'Bob'))->VAR('s')->DONE();
      $this->assertEquals($insertIDSecond,2);
      $this->assertEquals($this->validateStatus(),true);
      $insertIDThird = $this->Lake->INSERT('Users')->INTO(array('Name' => 'Lisa'))->VAR('s')->DONE();
      $this->assertEquals($insertIDThird,3);
      $this->assertEquals($this->validateStatus(),true);
      //SELECT, by ID OR Name
      $results = $this->Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertID))->OR()->WHERE(array('Name' => 'Test'))->VAR('is')->DONE();
      $this->assertEquals($results,array(0 => array('ID' => 1,'Name' => 'Test')));
      $this->assertEquals($this->validateStatus(),true);
      //SELECT, by ID AND Name
      $results = $this->Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertID,'Name' => 'Test'))->VAR('is')->DONE();
      $this->assertEquals($results,array(0 => array('ID' => 1,'Name' => 'Test')));
      $this->assertEquals($this->validateStatus(),true);
      //by ID only
      $results = $this->Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertIDSecond))->VAR('i')->DONE();
      $this->assertEquals($results,array(0 => array('ID' => 2,'Name' => 'Bob')));
      $this->assertEquals($this->validateStatus(),true);
      //all entries
      $results = $this->Lake->SELECT(array('ID','Name'))->FROM('Users')->DONE();
      $this->assertEquals($results,array(0 => array('ID' => 1,'Name' => 'Test'),1 => array('ID' => 2,'Name' => 'Bob'),2 => array('ID' => 3,'Name' => 'Lisa')));
      $this->assertEquals($this->validateStatus(),true);
      //DELETE by ID
      $this->Lake->DELETE()->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
      $this->assertEquals($this->validateStatus(),true);
      //Check if nothing is left by userID 1
      $results = $this->Lake->SELECT(array('ID'))->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
      $this->assertEquals($results,array());
      $this->assertEquals($this->validateStatus(),true);
      //DELETE everything from Users
      $this->Lake->DELETE()->FROM('Users')->DONE();
      //Check if Users is empty
      $results = $this->Lake->SELECT(array('ID'))->FROM('Users')->DONE();
      $this->assertEquals($results,array());
      $this->assertEquals($this->validateStatus(),true);
    }

  }

?>
