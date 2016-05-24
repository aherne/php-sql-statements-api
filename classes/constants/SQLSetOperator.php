<?php
interface SQLSetOperator {
	const UNION = "UNION";
	const UNION_ALL = "UNION ALL";
	const INTERSECT = "INTERSECT";
	const EXCEPT = "EXCEPT";
}