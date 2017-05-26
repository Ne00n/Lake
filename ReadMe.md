# Lake

Simple Wrapper for MySQLi.

[![Build Status](https://travis-ci.org/Ne00n/Lake.svg?branch=master)](https://travis-ci.org/Ne00n/Lake)

Examples:

```
include 'Lake.php';

$Lake = new Lake('127.0.0.1','user','password','database');

if ($Lake->getSuccess() == false) {
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

Currently Support for:

- INSERT
- SELECT
- DELETE
- WHERE/OR/AND
