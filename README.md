# SQL Statements API

The purpose of this API is to automate generation of CRUD statements based on SQL standards or their vendor-specific derivation.

## Classes

Following standard SQL statements are supported for generation (found in **src** folder):

- SELECT: using classes 
    - **Lucinda\Query\Select**: encapsulates a single SELECT statement (eg: SELECT id FROM table)
    - **Lucinda\Query\SelectGroup**: encapsulates a group of SELECT statements (eg: (SELECT id from table1) UNION (SELECT id FROM table2))
- INSERT: using classes 
    - **Lucinda\Query\Insert**: encapsulates an INSERT INTO ... VALUES statement (eg: INSERT INTO table (id, name) VALUES (1, 'asd'))
    - **Lucinda\Query\InsertSelect**: encapsulates an INSERT INTO ... SELECT statement (eg: INSERT INTO table (id, name) SELECT id, name FROM table2)
- UPDATE: using class
    - **Lucinda\Query\Update**: encapsulates an UPDATE statement (eg: UPDATE users SET name='Lucian' WHERE id=18)
- DELETE: using class
    - **Lucinda\Query\Delete**: encapsulates a DELETE statement (eg: DELETE FROM users WHERE id=18)
- TRUNCATE: using class 
    - **Lucinda\Query\Truncate**: encapsulates a TRUNCATE statement (eg: TRUNCATE TABLE users)
    
MySQL vendor statements extending SQL statements is also supported (found in **plugins/MySQL** folder):

- SELECT: using classes 
    - **Lucinda\Query\MySQLSelect**: extends **Lucinda\Query\Select**, supporting MySQL-specific operators as well
    - **Lucinda\Query\MySQLSelectGroup**: extends **Lucinda\Query\SelectGroup**, supporting MySQL-specific operators as well
- INSERT: using classes 
    - **Lucinda\Query\MySQLInsert**: extends **Lucinda\Query\Insert**, supporting MySQL-specific IGNORE and ON DUPLICATE KEY UPDATE clauses as well
    - **Lucinda\Query\MySQLInsertSelect**: extends **Lucinda\Query\InsertSelect**, supporting MySQL-specific IGNORE and ON DUPLICATE KEY UPDATE clauses as well
    - **Lucinda\Query\MySQLInsertSet**: encapsulates an INSERT INTO ... SET statement (eg: INSERT INTO table (id, name) SET id=1, name='Lucian'), supporting MySQL-specific IGNORE and ON DUPLICATE KEY UPDATE clauses as well
- UPDATE: using class
    - **Lucinda\Query\MySQLUpdate**: extends **Lucinda\Query\Update**, supporting MySQL-specific IGNORE clause as well
- DELETE: using class
    - **Lucinda\Query\MySQLDelete**: extends **Lucinda\Query\Delete**, supporting MySQL-specific IGNORE clause as well
 
Each of above or clauses they individually call to implement **Lucinda\Query\Stringable**, which manages conversion of statement/clause into SQL via *toString()* method. 

## Examples

To see examples how each classes are used, check unit tests in **test** folder!

Example:

_$statement = new \Lucinda\Query\SelectGroup();
$statement->addSelect(new Lucinda\Query\Select("asd", "k"));
$statement->addSelect(new Lucinda\Query\Select("fgh", "h"));
$statement->orderBy(["k","z"]);
$statement->limit(10,4);_

## Adding support for other vendors

Developers are free to add support for other vendors as well! Procedure is:

- fork repo
- work on that branch
- ask for a merge request