# Lake

Simple Wrapper for MySQLi.

[![Build Status](https://travis-ci.org/Ne00n/Lake.svg?branch=master)](https://travis-ci.org/Ne00n/Lake)

Examples:

```
include 'Lake.php';

$Lake = new Lake('127.0.0.1','user','password','database');

if ($Lake->getSuccess() === false) {
  die('MySQL Connection failed');
  //Use $Lake->getErrors() for more details
}

```

INSERT INTO Users (Name) VALUES (Test);
```
$insertID = $Lake->INSERT('Users')->INTO(array('Name' => 'Test'))->VAR('s')->DONE();
```
SELECT ID,Name FROM Users WHERE ID = 1 OR Name = "Test";
```
$select = array('ID','Name');
$where1 = array('ID' => 1);
$where2 = array('Name' => 'Test');

$results = $Lake->SELECT($select)->FROM('Users')->WHERE($where1)->OR()->WHERE($where2)->VAR('is')->DONE();
```
SELECT ID,Name FROM Users WHERE ID = 1 AND Name = "Test";
```
$select = array('ID','Name');
$where = array('ID' => 1,'Name' => 'Test');

$results = $Lake->SELECT($select)->FROM('Users')->WHERE($where)->VAR('is')->DONE();
```
SELECT ID,Name FROM Users WHERE ID = 1;
```
$select = array('ID','Name');
$where = array('ID' => 1);

$results = $Lake->SELECT($select)->FROM('Users')->WHERE($where)->VAR('i')->DONE();
```
SELECT ID,Name FROM Users;
```
$results = $Lake->SELECT(array('ID','Name'))->FROM('Users')->DONE();
```
UPDATE Users SET Name = "Fabian" WHERE ID = 2;
```
$this->Lake->UPDATE('Users')->SET(array('Name' => 'Fabian'))->WHERE(array('ID' => 2))->VAR('si')->DONE();
```
DELETE FROM Users WHERE ID = 1;
```
$Lake->DELETE()->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
```
DELETE FROM Users WHERE ID = 2 OR ID = 3;
```
$where1 = array('ID' => 2);
$where2 = array('ID' => 3);

$Lake->DELETE()->FROM('Users')->WHERE($where1)->OR()->WHERE($where2)->VAR('ii')->DONE();
```
DELETE FROM Users;
```
$Lake->DELETE()->FROM('Users')->DONE();
```

If you need more examples, take a look into: /tests/Main.php

Note: Only the Variables get parametrized (e.g. 2,3,Test), not the Table/Columns, do not use any User input for those!<br />
MySQLi does not support those to be parametrized because of certain reasons: https://stackoverflow.com/questions/11312737/can-i-parameterize-the-table-name-in-a-prepared-statement

Currently Support for:

- INSERT
- SELECT
- UPDATE
- DELETE
- WHERE/OR/AND
