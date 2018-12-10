<?php
namespace Lucinda\Query;

/**
 * Enum encapsulating possible SQL set operators (grouping SELECT statements)
 */
interface SetOperator {
	const UNION = "UNION";
	const UNION_ALL = "UNION ALL";
	const INTERSECT = "INTERSECT";
	const EXCEPT = "EXCEPT";
}