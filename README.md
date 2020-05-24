# SQL Statements API

Table of contents:

- [About](#about)
- [Installation](#installation)
- [Unit Tests](#unit-tests)
- [Examples](#examples)
- [Reference Guide](#reference-guide)

## About 

The purpose of this API is to automate generation of SQL statements (queries) based on SQL standards or their vendor-specific derivation. API is fully PSR-4 compliant, only requiring PHP7.1+ interpreter. To quickly see how it works, check:

- **[installation](#installation)**: describes how to install API on your computer
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility
- **[examples](#examples)**: shows a example of API functionality

## Installation

To install this api, you only need to go to your project root then run this command from console:

```console
composer require lucinda/queries
```

Once you have it installed, you're able to generate queries. Each standard SQL statement corresponds to one or more classes:

- **SELECT**:
    - [Lucinda\Query\Select](#class-select): encapsulates a single SELECT statement (eg: SELECT id FROM table)
    - [Lucinda\Query\SelectGroup](#class-selectgroup): encapsulates a group of SELECT statements united by a SET operator (eg: (SELECT id from table1) UNION (SELECT id FROM table2))
- **INSERT**:
    - [Lucinda\Query\Insert](#class-insert): encapsulates an INSERT INTO ... VALUES statement (eg: INSERT INTO table (id, name) VALUES (1, 'asd'))
    - [Lucinda\Query\InsertSelect](#class-insertselect): encapsulates an INSERT INTO ... SELECT statement (eg: INSERT INTO table (id, name) SELECT id, name FROM table2)
- **UPDATE**:
    - [Lucinda\Query\Update](#class-update): encapsulates an UPDATE statement (eg: UPDATE users SET name='Lucian' WHERE id=18)
- **DELETE**:
    - [Lucinda\Query\Delete](#class-delete): encapsulates a DELETE statement (eg: DELETE FROM users WHERE id=18)
- **TRUNCATE**:
    - [Lucinda\Query\Truncate](#class-truncate): encapsulates a TRUNCATE statement (eg: TRUNCATE TABLE users)
    
For each vendor implementing SQL standards, you can either use above or their vendor-specific derivations. MySQL vendor is already supported:

- **SELECT**:
    - [Lucinda\Query\Vendor\MySQL\Select](#class-mysql-select): extends [Lucinda\Query\Select](#class-select) in order to support vendor-specific operations (eg: SQL_NO_CACHE)
- **INSERT**:
    - [Lucinda\Query\Vendor\MySQL\Insert](#class-mysql-insert): extends [Lucinda\Query\Insert](#class-insert) in order to support vendor-specific operations (eg: IGNORE)
    - [Lucinda\Query\Vendor\MySQL\InsertSelect](#class-mysql-insertselect): extends [Lucinda\Query\InsertSelect](#class-insertselect) in order to support vendor-specific operations (eg: IGNORE)
    - [Lucinda\Query\Vendor\MySQL\InsertSet](#class-mysql-insertset): encapsulates vendor-specific statement INSERT INTO ... SET statement (eg: INSERT INTO table (id, name) SET id=1, name='Lucian')
- **REPLACE**:
    - [Lucinda\Query\Vendor\MySQL\Replace](#class-mysql-replace): extends [Lucinda\Query\Insert](#class-insert) in order to support vendor-specific REPLACE INTO ... VALUES statement
    - [Lucinda\Query\Vendor\MySQL\ReplaceSelect](#class-mysql-replaceselect): extends [Lucinda\Query\InsertSelect](#class-insertselect) in order to support vendor-specific REPLACE INTO ... SELECT statement
    - [Lucinda\Query\Vendor\MySQL\ReplaceSet](#class-mysql-replaceset): encapsulates vendor-specific statement REPLACE INTO ... SET statement (eg: REPLACE INTO table (id, name) SET id=1, name='Lucian')
- **UPDATE**
    - [Lucinda\Query\Vendor\MySQL\Update](#class-mysql-update): extends [Lucinda\Query\Update](#class-update) in order to support vendor-specific operations (eg: IGNORE)
- **DELETE**
    - [Lucinda\Query\Vendor\MySQL\Delete](#class-mysql-delete): extends [Lucinda\Query\Delete](#class-delete) in order to support vendor-specific operations (eg: IGNORE)
 
Each of above or clauses they individually call to implement **Lucinda\Query\Stringable**, which manages conversion of statement/clause into SQL via *toString()* method. 

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/unit-tests.xml): sets up unit tests
- [tests](https://github.com/aherne/php-sql-statements-api/tree/v2.0.0/tests): unit tests for classes from [src](https://github.com/aherne/php-sql-statements-api/tree/v2.0.0/src) folder
- [tests_drivers](https://github.com/aherne/php-sql-statements-api/tree/v2.0.0/tests_drivers): unit tests for classes from [drivers](https://github.com/aherne/php-sql-statements-api/tree/v2.0.0/drivers) folder

## Examples

To see examples how each classes are used, check unit tests in **tests** or **tests_drivers** folder! Usage example:

```php
$statement = new \Lucinda\Query\SelectGroup();
$statement->addSelect(new Lucinda\Query\Select("asd", "k"));
$statement->addSelect(new Lucinda\Query\Select("fgh", "h"));
$statement->orderBy(["k","z"]);
$statement->limit(10,4);
```

## Reference Guide

### Class Select

[Lucinda\Queries\Select](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Select.php) encapsulates a standard SELECT statement. 

### Class SelectGroup

[Lucinda\Queries\SelectGroup](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/SelectGroup.php) encapsulates a list of SELECT statements joined by a SET operator (eg: UNION).

### Class Insert

[Lucinda\Queries\Insert](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Insert.php) encapsulates a standard INSERT INTO VALUES statement.

### Class InsertSelect

[Lucinda\Queries\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/InsertSelect.php) encapsulates a standard INSERT INTO SELECT statement.

### Class Update

[Lucinda\Queries\Update](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Update.php) encapsulates a standard UPDATE statement.

### Class Delete

[Lucinda\Queries\Delete](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Delete.php) encapsulates a standard DELETE statement.

### Class Truncate

[Lucinda\Queries\Truncate](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Truncate.php) encapsulates a standard TRUNCATE statement.

### Class MySQL Select

[Lucinda\Queries\Vendor\MySQL\Select](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL SELECT statement on top of [Lucinda\Queries\Select](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Select.php).

### Class MySQL Insert

[Lucinda\Queries\Vendor\MySQL\Insert](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL INSERT INTO VALUES statement on top of [Lucinda\Queries\Insert](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Insert.php).

### Class MySQL InsertSelect

[Lucinda\Queries\Vendor\MySQL\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL INSERT INTO SELECT statement on top of [Lucinda\Queries\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/InsertSelect.php).

### Class MySQL InsertSet

[Lucinda\Queries\Vendor\MySQL\InsertSet](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL INSERT INTO SET statement.

### Class MySQL Replace

[Lucinda\Queries\Vendor\MySQL\Replace](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL REPLACE INTO VALUES statement on top of [Lucinda\Queries\Insert](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Insert.php).

### Class MySQL ReplaceSelect

[Lucinda\Queries\Vendor\MySQL\ReplaceSelect](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL REPLACE INTO SELECT statement on top of [Lucinda\Queries\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/InsertSelect.php).

### Class MySQL ReplaceSet

[Lucinda\Queries\Vendor\MySQL\ReplaceSet](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL REPLACE INTO SET statement.

### Class MySQL Update

[Lucinda\Queries\Vendor\MySQL\Update](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL UPDATE statement on top of [Lucinda\Queries\Update](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Update.php).

### Class MySQL Delete

[Lucinda\Queries\Vendor\MySQL\Delete](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/) encapsulates a MySQL DELETE statement on top of [Lucinda\Queries\Delete](https://github.com/aherne/php-sql-statements-api/blob/v2.0.0/src/Delete.php). 
