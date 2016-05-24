<?php
require_once("statements/schema/SQLSchemaCreateStatement.php");
require_once("statements/schema/SQLSchemaDropStatement.php");
require_once("statements/table/SQLTableAlterStatement.php");
require_once("statements/table/SQLTableCreateStatement.php");
require_once("statements/table/SQLTableDeleteStatement.php");
require_once("statements/table/SQLTableDropStatement.php");
require_once("statements/table/SQLTableInsertStatement.php");
require_once("statements/table/SQLTableInsertSelectStatement.php");
require_once("statements/table/SQLTableSelectStatement.php");
require_once("statements/table/SQLTableSelectGroupStatement.php");
require_once("statements/table/SQLTableTruncateStatement.php");
require_once("statements/table/SQLTableUpdateStatement.php");
require_once("statements/view/SQLViewAlterStatement.php");
require_once("statements/view/SQLViewCreateStatement.php");
require_once("statements/view/SQLViewDropStatement.php");

require_once("statements/server/AbstractSQLServerStatement.php");