<?php
namespace Lucinda\Query;

interface MySQLSetOperator {
	const UNION = "UNION";
	const UNION_ALL = "UNION ALL";
	const UNION_DISTINCT = "UNION DISTINCT";
}