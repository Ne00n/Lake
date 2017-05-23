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

    public function testComponents() {
      //INSERT
      $insertID = $this->Lake->INSERT('Users')->INTO(array('Name' => 'Test'))->VAR('s')->DONE();
      $this->assertEquals($insertID,1);
      $this->assertEquals($this->Lake->getErrors(),array());
      //SELECT
      $results = $this->Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertID))->OR()->WHERE(array('Name' => 'Test'))->VAR('is')->DONE();
      $this->assertEquals($results,array(0 => array('ID' => 1,'Name' => 'Test')));
      $this->assertEquals($this->Lake->getErrors(),array());
      //DELETE
      $this->Lake->DELETE()->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
      $this->assertEquals($this->Lake->getErrors(),array());
      //Check if nothing is left
      $results = $this->Lake->SELECT(array('ID'))->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
      $this->assertEquals($results,array());
    }

  }

?>
