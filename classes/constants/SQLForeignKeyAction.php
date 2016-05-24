<?php
interface SQLForeignKeyAction {
	const CASCADE = "CASCADE";
	const RESTRICT = "RESTRICT";
	const NO_ACTION = "NO ACTION";
	const SET_NULL = "SET NULL";
	const SET_DEFAULT = "SET DEFAULT";
}