# SQL Statements API

Small PHP 8.1+ library for building SQL statements through composable objects.

The package focuses on query construction only. It does not open database connections,
quote values, bind parameters, or execute SQL. Every statement and clause object is
`\Stringable`, so it can be cast to a raw SQL string when ready.

## Installation

```console
composer require lucinda/queries
```

## Supported Statements

Standard SQL classes live under `Lucinda\Query`:

| Statement | Class | Purpose |
|-----------|-------|---------|
| SELECT | `Lucinda\Query\Select` | Single SELECT statement |
| SELECT groups | `Lucinda\Query\SelectGroup` | UNION, INTERSECT and EXCEPT groups |
| INSERT VALUES | `Lucinda\Query\Insert` | `INSERT INTO ... VALUES` |
| INSERT SELECT | `Lucinda\Query\InsertSelect` | `INSERT INTO ... SELECT` |
| UPDATE | `Lucinda\Query\Update` | `UPDATE ... SET ... WHERE` |
| DELETE | `Lucinda\Query\Delete` | `DELETE FROM ... WHERE` |
| TRUNCATE | `Lucinda\Query\Truncate` | `TRUNCATE TABLE ...` |

Vendor-specific classes live under `Lucinda\Query\Vendor`:

| Vendor | Namespace | Additions |
|--------|-----------|-----------|
| MySQL | `Lucinda\Query\Vendor\MySQL` | `IGNORE`, `REPLACE`, `INSERT ... SET`, `SQL_CALC_FOUND_ROWS`, `STRAIGHT_JOIN`, MySQL regexp/full-text conditions, `XOR` |
| PostgreSQL | `Lucinda\Query\Vendor\PostgreSQL` | `RETURNING`, `ON CONFLICT`, `ILIKE`, PostgreSQL regexp operators, full-text `@@` |
| SQLite | `Lucinda\Query\Vendor\SQLite` | `RETURNING`, `ON CONFLICT`, `INSERT OR ...`, `GLOB` |

Use the standard classes when the SQL is portable. Use a vendor namespace when the
statement needs dialect-specific syntax.

## Examples

### Standard SELECT

```php
use Lucinda\Query\Select;

$query = new Select("users", "u");
$query->fields(["d.name"]);
$query->joinInner("user_departments", "ud")->on(["u.id" => "ud.user_id"]);
$query->joinInner("departments", "d")->on(["ud.department_id" => "d.id"]);
$query->where(["u.id" => ":id"]);
$query->orderBy(["d.name"]);

echo $query;
```

Output:

```sql
SELECT
d.name
FROM users AS u
INNER JOIN user_departments AS ud ON u.id = ud.user_id
INNER JOIN departments AS d ON ud.department_id = d.id
WHERE u.id = :id
ORDER BY d.name ASC
```

### MySQL INSERT With Duplicate-Key Update

```php
use Lucinda\Query\Vendor\MySQL\Insert;

$query = new Insert("users");
$query->ignore();
$query->columns(["id", "name"]);
$query->values([":id", ":name"]);
$query->onDuplicateKeyUpdate(["name" => "VALUES(name)"]);

echo $query;
```

```sql
INSERT IGNORE INTO users (id, name) VALUES
(:id, :name)
ON DUPLICATE KEY UPDATE name = VALUES(name)
```

### PostgreSQL Upsert With RETURNING

```php
use Lucinda\Query\Vendor\PostgreSQL\Insert;

$query = new Insert("users");
$query->columns(["id", "name"]);
$query->values([":id", ":name"]);
$query->onConflictDoUpdate(["id"], ["name" => "EXCLUDED.name"]);
$query->returning(["id", "name"]);

echo $query;
```

```sql
INSERT INTO users (id, name) VALUES
(:id, :name)
ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name
RETURNING id, name
```

### SQLite INSERT OR REPLACE

```php
use Lucinda\Query\Vendor\SQLite\Insert;

$query = new Insert("users");
$query->orReplace();
$query->columns(["id", "name"]);
$query->values([":id", ":name"]);
$query->returning(["id"]);

echo $query;
```

```sql
INSERT OR REPLACE INTO users (id, name) VALUES
(:id, :name)
RETURNING id
```

## Core API

### Select

`Lucinda\Query\Select` builds a single `SELECT` statement.

| Method | Purpose |
|--------|---------|
| `__construct(string|Select|SelectGroup $tableDefinition, string $tableAlias = "")` | Sets the source table or derived table |
| `distinct(): void` | Adds `DISTINCT` |
| `fields(array $columns = []): Clause\Fields` | Sets selected expressions |
| `joinLeft(...)`, `joinRight(...)`, `joinInner(...)`, `joinCross(...)` | Adds JOIN clauses |
| `where(array $condition = [], Operator\Logical $logicalOperator = Operator\Logical::_AND_): Clause\Condition` | Adds `WHERE` |
| `groupBy(array $columns = []): Clause\Columns` | Adds `GROUP BY` |
| `having(array $condition = [], Operator\Logical $logicalOperator = Operator\Logical::_AND_): Clause\Condition` | Adds `HAVING` |
| `orderBy(array $fields = []): Clause\OrderBy` | Adds `ORDER BY` |
| `limit(int $limit, int $offset = 0): void` | Adds `LIMIT` / `OFFSET` |
| `with(bool $isRecursive = false): Clause\With` | Adds common table expressions |
| `window(): Clause\Window` | Adds window definitions |

### SelectGroup

`Lucinda\Query\SelectGroup` combines `Select` or nested `SelectGroup` objects using
a set operator.

| Method | Purpose |
|--------|---------|
| `__construct(Operator\Set $operator = Operator\Set::UNION)` | Chooses the set operator |
| `addSelect(Stringable $select): void` | Adds a SELECT or SELECT group |
| `orderBy(array $fields = []): Clause\OrderBy` | Adds final ordering |
| `limit(int $limit, int $offset = 0): void` | Adds final limit |

### Insert

| Method | Purpose |
|--------|---------|
| `__construct(string $table)` | Sets target table |
| `columns(array $columns = []): Clause\Columns` | Sets target columns |
| `values(array $updates = []): Clause\Row` | Adds one VALUES row |

### InsertSelect

| Method | Purpose |
|--------|---------|
| `__construct(string $table)` | Sets target table |
| `with(bool $isRecursive = false): Clause\With` | Adds common table expressions |
| `columns(array $columns = []): Clause\Columns` | Sets target columns |
| `select(Stringable $select): void` | Sets SELECT source |

### Update

| Method | Purpose |
|--------|---------|
| `__construct(string $table)` | Sets target table |
| `with(bool $isRecursive = false): Clause\With` | Adds common table expressions |
| `set(array $contents = []): Clause\Set` | Adds `SET` assignments |
| `where(array $condition = [], Operator\Logical $logicalOperator = Operator\Logical::_AND_): Clause\Condition` | Adds `WHERE` |

### Delete

| Method | Purpose |
|--------|---------|
| `__construct(string $table)` | Sets target table |
| `with(bool $isRecursive = false): Clause\With` | Adds common table expressions |
| `where(array $condition = [], Operator\Logical $logicalOperator = Operator\Logical::_AND_): Clause\Condition` | Adds `WHERE` |

### Truncate

| Method | Purpose |
|--------|---------|
| `__construct(string $table)` | Sets target table |

## Vendor API

### MySQL

| Class | Extra methods |
|-------|---------------|
| `Vendor\MySQL\Select` | `setCalcFoundRows()`, `setStraightJoin()`, `getCalcFoundRows()` |
| `Vendor\MySQL\Insert` | `ignore()`, `onDuplicateKeyUpdate(array $contents = []): Clause\Set` |
| `Vendor\MySQL\InsertSelect` | `ignore()`, `onDuplicateKeyUpdate(array $contents = []): Clause\Set` |
| `Vendor\MySQL\InsertSet` | `ignore()`, `set(array $contents = []): Clause\Set`, `onDuplicateKeyUpdate(array $contents = []): Clause\Set` |
| `Vendor\MySQL\Replace` | Emits `REPLACE INTO ... VALUES` |
| `Vendor\MySQL\ReplaceSelect` | Emits `REPLACE INTO ... SELECT` |
| `Vendor\MySQL\ReplaceSet` | Emits `REPLACE INTO ... SET` |
| `Vendor\MySQL\Update` | `ignore()`, JOIN helpers, MySQL conditions |
| `Vendor\MySQL\Delete` | `ignore()`, JOIN helpers, MySQL conditions |
| `Vendor\MySQL\Clause\Condition` | `setRegexp(...)`, `setMatchAgainst(...)` |

### PostgreSQL

| Class | Extra methods |
|-------|---------------|
| `Vendor\PostgreSQL\Select` | PostgreSQL `where()` and `having()` condition objects |
| `Vendor\PostgreSQL\Insert` | `onConflictDoNothing(...)`, `onConflictDoUpdate(...)`, `onConstraintDoNothing(...)`, `onConstraintDoUpdate(...)`, `returning(...)` |
| `Vendor\PostgreSQL\InsertSelect` | Same conflict and `returning(...)` helpers |
| `Vendor\PostgreSQL\Update` | PostgreSQL conditions and `returning(...)` |
| `Vendor\PostgreSQL\Delete` | PostgreSQL conditions and `returning(...)` |
| `Vendor\PostgreSQL\Clause\Condition` | `setILike(...)`, `setRegexp(...)`, `setFullTextSearch(...)` |

### SQLite

| Class | Extra methods |
|-------|---------------|
| `Vendor\SQLite\Select` | SQLite `where()` and `having()` condition objects |
| `Vendor\SQLite\Insert` | `or(...)`, `orIgnore()`, `orReplace()`, `onConflictDoNothing(...)`, `onConflictDoUpdate(...)`, `returning(...)` |
| `Vendor\SQLite\InsertSelect` | Same conflict and `returning(...)` helpers |
| `Vendor\SQLite\Update` | SQLite conditions and `returning(...)` |
| `Vendor\SQLite\Delete` | SQLite conditions and `returning(...)` |
| `Vendor\SQLite\Clause\Condition` | `setGlob(...)` |

## Design Notes

- Public builder classes are concrete and intentionally small.
- Classes are `final` unless they are used as base classes by vendor drivers.
- The standard classes remain open where drivers extend them: `Select`, `Insert`,
  `InsertSelect`, `Update`, `Delete`, and `Clause\Condition`.
- SQL values are passed as strings or placeholders exactly as supplied. This library
  does not perform quoting or escaping.

## Tests

Install development dependencies, then run:

```console
composer install
php test.php
```

The custom unit runner is configured by `unit-tests.xml`. Standard tests live in
`tests/`; vendor tests live in `tests_drivers/`.
