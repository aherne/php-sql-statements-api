# SQL Statements API

*Documentation below refers to latest API version, available in branch [v3.0.0](https://github.com/aherne/php-sql-statements-api/tree/v1.0)!*

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
    - [Lucinda\Query\MySQLSelect](#class-mysql-select): extends [Lucinda\Query\Select](#class-select) in order to support vendor-specific operations (eg: SQL_NO_CACHE)
- **INSERT**:
    - [Lucinda\Query\MySQLInsert](#class-mysql-insert): extends [Lucinda\Query\Insert](#class-insert) in order to support vendor-specific operations (eg: IGNORE)
    - [Lucinda\Query\MySQLInsertSelect](#class-mysql-insertselect): extends [Lucinda\Query\InsertSelect](#class-insertselect) in order to support vendor-specific operations (eg: IGNORE)
    - [Lucinda\Query\MySQLInsertSet](#class-mysql-insertset): encapsulates vendor-specific statement INSERT INTO ... SET statement (eg: INSERT INTO table (id, name) SET id=1, name='Lucian')
- **REPLACE**:
    - [Lucinda\Query\MySQLReplace](#class-mysql-replace): extends [Lucinda\Query\Insert](#class-insert) in order to support vendor-specific REPLACE INTO ... VALUES statement
    - [Lucinda\Query\MySQLReplaceSelect](#class-mysql-replaceselect): extends [Lucinda\Query\InsertSelect](#class-insertselect) in order to support vendor-specific REPLACE INTO ... SELECT statement
    - [Lucinda\Query\MySQLReplaceSet](#class-mysql-replaceset): encapsulates vendor-specific statement REPLACE INTO ... SET statement (eg: REPLACE INTO table (id, name) SET id=1, name='Lucian')
- **UPDATE**
    - [Lucinda\Query\MySQLUpdate](#class-mysql-update): extends [Lucinda\Query\Update](#class-update) in order to support vendor-specific operations (eg: IGNORE)
- **DELETE**
    - [Lucinda\Query\MySQLDelete](#class-mysql-delete): extends [Lucinda\Query\Delete](#class-delete) in order to support vendor-specific operations (eg: IGNORE)

Each of above or clauses they individually call to implement **Lucinda\Query\Stringable**, which manages conversion of statement/clause into SQL via *toString()* method.

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/php-sql-statements-api/blob/v1.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/php-sql-statements-api/blob/v1.0/unit-tests.xml): sets up unit tests
- [tests](https://github.com/aherne/php-sql-statements-api/tree/v1.0/tests): unit tests for classes from [src](https://github.com/aherne/php-sql-statements-api/tree/v1.0/src) folder
- [tests_drivers](https://github.com/aherne/php-sql-statements-api/tree/v1.0/tests_drivers): unit tests for classes from [drivers](https://github.com/aherne/php-sql-statements-api/tree/v1.0/drivers) folder

## Examples

To see examples how each classes are used, check unit tests in **[tests](https://github.com/aherne/php-sql-statements-api/tree/v1.0/test)**! Simple example:

```php
$statement = new \Lucinda\Query\Select("users", "t1");
$statement->fields(["t3.name"]);
$statement->joinInner("user_departments", "t2")->on(["t1.id"=>"t2.user_id"]);
$statement->joinInner("departments", "t3")->on(["t2.department_id"=>"t3.id"]);
$statement->where(["t1.id"=>":id"]);
$statement->orderBy(["t3.name"]);
```

Encapsulating:

```sql
SELECT t3.name
FROM users AS t1
INNER JOIN user_departments AS t2 ON t1.id = t2.user_id
INNER JOIN departments AS t3 ON t2.department_id = t3.id
WHERE t1.id = :id
ORDER BY t3.name
```

## Reference Guide

### Class Select

[Lucinda\Query\Select](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Select.php) encapsulates a standard SELECT statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table, string $alias="" | void | Constructs a SELECT statement based on table name and optional alias |
| distinct | void | void | Sets statement as DISTINCT, filtering out repeating rows |
| fields | array $columns = [] | [Lucinda\Query\Fields](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Fields.php) | Sets fields or columns to select |
| joinLeft | string $tableName, string $tableAlias = "" | [Lucinda\Query\Join](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Join.php) | Adds a LEFT JOIN statement |
| joinRight | string $tableName, string $tableAlias = "" | [Lucinda\Query\Join](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Join.php) | Adds a RIGHT JOIN statement |
| joinInner | string $tableName, string $tableAlias = "" | [Lucinda\Query\Join](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Join.php) | Adds a INNER JOIN statement |
| joinCross | string $tableName, string $tableAlias = "" | [Lucinda\Query\Join](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Join.php) | Adds a CROSS JOIN statement |
| where | array $condition=[], string $logicalOperator = [Lucinda\Query\LogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/LogicalOperator.php)::_AND_ | [Lucinda\Query\Condition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Condition.php) | Sets up WHERE clause. |
| groupBy | array $columns = [] | [Lucinda\Query\Columns](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Columns.php) | Sets up GROUP BY statement |
| having | array $condition=[], string $logicalOperator = [Lucinda\Query\LogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/LogicalOperator.php)::_AND_ | [Lucinda\Query\Condition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Condition.php) | Sets up HAVING clause. |
| orderBy | array $fields = [] | [Lucinda\Query\OrderBy](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/OrderBy.php) | Sets up ORDER BY clause |
| limit | int $limit, int $offset=0 | void | Sets a LIMIT clause |
| __toString | void | string | Converts object to SQL statement. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class SelectGroup

[Lucinda\Query\SelectGroup](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/SelectGroup.php) encapsulates a list of SELECT statements joined by a SET operator (eg: UNION) via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $operator = [Lucinda\Query\SetOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/SetOperator.php)::UNION | void | Constructs a SELECT ... OPERATOR ... SELECT statement based on Set OPERATOR |
| addSelect | [Lucinda\Query\Select](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Select.php) $select | void | Adds SELECT statement to group |
| addSelect | [Lucinda\Query\SelectGroup](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/SelectGroup.php) $select | void | Adds SELECT ... OPERATOR ... SELECT statement to group |
| orderBy | array $fields = [] | [Lucinda\Query\OrderBy](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/OrderBy.php) | Sets up ORDER BY clause |
| limit | int $limit, int $offset=0 | void | Sets a LIMIT clause |
| __toString | void | string | Converts object to SQL statement. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class Insert

[Lucinda\Query\Insert](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Insert.php) encapsulates a standard INSERT INTO VALUES statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a INSERT INTO ... VALUES statement based on table name |
| columns | array $columns = [] | [Lucinda\Query\Columns](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Columns.php) | Sets columns that will be inserted into. |
| values | array $updates = [] | [Lucinda\Query\Row](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Row.php) | Adds row to table via list of values to insert in columns |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class InsertSelect

[Lucinda\Query\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/InsertSelect.php) encapsulates a standard INSERT INTO SELECT statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a INSERT INTO ... SELECT statement based on table name |
| columns | array $columns = [] | [Lucinda\Query\Columns](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Columns.php) | Sets columns that will be inserted into. |
| select | [Lucinda\Query\Select](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Select.php) $select | void | Sets rows to insert based on a SELECT statement |
| select | [Lucinda\Query\SelectGroup](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/SelectGroup.php) $select | void | Sets rows to insert based on a SELECT ... OPERATOR ... SELECT group statement |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class Update

[Lucinda\Query\Update](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Update.php) encapsulates a standard UPDATE statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a UPDATE statement based on table name |
| set | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up SET clause. |
| where | array $condition = [], string $logicalOperator = [Lucinda\Query\LogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/LogicalOperator.php)::_AND_ | [Lucinda\Query\Condition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Condition.php) | Sets up WHERE clause. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class Delete

[Lucinda\Query\Delete](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Delete.php) encapsulates a standard DELETE statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a DELETE statement based on table name |
| where | array $condition=[], string $logicalOperator = [Lucinda\Query\LogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/LogicalOperator.php)::_AND_ | [Lucinda\Query\Condition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Condition.php) | Sets up WHERE clause. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class Truncate

[Lucinda\Query\Truncate](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Truncate.php) encapsulates a standard TRUNCATE statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a TRUNCATE statement based on table name |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class MySQL Select

[Lucinda\Query\MySQLSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLSelect.php) encapsulates a MySQL SELECT statement on top of [Lucinda\Query\Select](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Select.php) via following extra methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| setCalcFoundRows | void | void | Appends a SQL_CALC_FOUND_ROWS option to SELECT |
| setStraightJoin | void | void | Appends a STRAIGHT_JOIN option to SELECT |
| getCalcFoundRows | void | string | Gets query to retrieve found rows after a SELECT with SQL_CALC_FOUND_ROWS has ran |

In addition of above operations, *where* method can use:

- [Lucinda\Query\MySQLCondition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLCondition.php) to support regexp conditions and fulltext searches
- [Lucinda\Query\MySQLLogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLLogicalOperator.php) to support XOR operator

### Class MySQL Insert

[Lucinda\Query\MySQLInsert](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLInsert.php) encapsulates a MySQL INSERT INTO VALUES statement on top of [Lucinda\Query\Insert](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Insert.php) via following extra methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| ignore | void | void | Sets statement as IGNORE, ignoring foreign key errors and duplicates |
| onDuplicateKeyUpdate | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up ON DUPLICATE KEY UPDATE clause. |

### Class MySQL InsertSelect

[Lucinda\Query\MySQLInsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLInsertSelect.php) encapsulates a MySQL INSERT INTO SELECT statement on top of [Lucinda\Query\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/InsertSelect.php) via following extra methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| ignore | void | void | Sets statement as IGNORE, ignoring foreign key errors and duplicates |
| onDuplicateKeyUpdate | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up ON DUPLICATE KEY UPDATE clause. |

### Class MySQL InsertSet

[Lucinda\Query\MySQLInsertSet](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLInsertSet.php) encapsulates a MySQL INSERT INTO SET statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a INSERT INTO ... SET statement based on table name |
| ignore | void | void | Sets statement as IGNORE, ignoring foreign key errors and duplicates |
| set | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up SET clause. |
| onDuplicateKeyUpdate | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up ON DUPLICATE KEY UPDATE clause. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class MySQL Replace

[Lucinda\Query\MySQLReplace](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLReplace.php) encapsulates a MySQL REPLACE INTO VALUES statement on top of [Lucinda\Query\Insert](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Insert.php) with no extra methods, except that INSERT will have REPLACE instead.

### Class MySQL ReplaceSelect

[Lucinda\Query\MySQLReplaceSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLReplaceSelect.php) encapsulates a MySQL REPLACE INTO SELECT statement on top of [Lucinda\Query\InsertSelect](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/InsertSelect.php) with no extra methods, except that INSERT will have REPLACE instead.

### Class MySQL ReplaceSet

[Lucinda\Query\MySQLReplaceSet](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLReplaceSet.php) encapsulates a MySQL REPLACE INTO SET statement via following public methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| __construct | string $table | void | Constructs a REPLACE INTO ... SET statement based on table name |
| set | array $contents = [] | [Lucinda\Query\Set](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/clauses/Set.php) | Sets up SET clause. |
| toString | void | string | Compiles SQL statement based on data collected in class fields. |

### Class MySQL Update

[Lucinda\Query\MySQLUpdate](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLUpdate.php) encapsulates a MySQL UPDATE statement on top of [Lucinda\Query\Update](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Update.php) via following extra methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| ignore | void | void | Sets statement as IGNORE, ignoring foreign key errors and duplicates |

In addition of above operations, *where* method can use:

- [Lucinda\Query\MySQLCondition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLCondition.php) to support regexp conditions and fulltext searches
- [Lucinda\Query\MySQLLogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLLogicalOperator.php) to support XOR operator

### Class MySQL Delete

[Lucinda\Query\MySQLDelete](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/MySQLDelete.php) encapsulates a MySQL DELETE statement on top of [Lucinda\Query\Delete](https://github.com/aherne/php-sql-statements-api/blob/v1.0/src/Delete.php) via following extra methods:

| Method | Arguments | Returns | Description |
| --- | --- | --- | --- |
| ignore | void | void | Sets statement as IGNORE, ignoring foreign key errors and duplicates |

In addition of above operations, *where* method can use:

- [Lucinda\Query\MySQLCondition](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLCondition.php) to support regexp conditions and fulltext searches
- [Lucinda\Query\MySQLLogicalOperator](https://github.com/aherne/php-sql-statements-api/blob/v1.0/plugins/MySQL/clauses/MySQLLogicalOperator.php) to support XOR operator
