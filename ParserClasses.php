<?php

/*
 * The Parser Classes file has the requires of all the classes needed for BISCUIT parser to work
 */
require_once('BiscuitQuery.php');
require_once('BiscuitException.php');
// Extra classes for query
require_once('Attribute.php');
require_once('WhereClause.php');
require_once('SortClause.php');
require_once('SetClause.php');
// Child classes of BiscuitQuery
require_once('BiscuitBuildQuery.php');
require_once('BiscuitFetchQuery.php');
require_once('BiscuitInsertQuery.php');
require_once('BiscuitDestroyQuery.php');
require_once('BiscuitChangeQuery.php');
require_once('BiscuitUpdateQuery.php');
require_once('BiscuitRemoveQuery.php');
// Parser Classes
require_once('Parser.php');
require_once('BiscuitMySQL.php');