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

INSERT:
```
$insertID = $Lake->INSERT('Users')->INTO(array('Name' => 'Test'))->VAR('s')->DONE();
```
SELECT:
```
$results = $Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertID))->OR()->WHERE(array('Name' => 'Test'))->VAR('is')->DONE();
$results = $Lake->SELECT(array('ID','Name'))->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
$results = $Lake->SELECT(array('ID','Name'))->FROM('Users')->DONE();
```
DELETE:
```
$Lake->DELETE()->FROM('Users')->WHERE(array('ID' => $insertID))->VAR('i')->DONE();
$Lake->DELETE()->FROM('Users')->DONE();
```

Currently Support for:

- INSERT
- SELECT
- DELETE
- WHERE
- OR
