<?php

require_once('ParserFunctions.php');


/* *** BUILD QUERY *** */
$query = "BUILD users { user_id:int:autoinc:notnull, username:string:notnull };";
p_info('Query', $query);
$buildQuery = new BiscuitBuildQuery($query);
p_var($buildQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** FETCH QUERY *** */
$query = "FETCH users { [user_id, status] WHERE(status:eq:A) SORT(dsc:user_id) };";
p_info('Query', $query);
$fetchQuery = new BiscuitFetchQuery($query);
p_var($fetchQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** INSERT QUERY *** */
$query = "INSERT users { user_id:bcd001, age:45, status:A };";
p_info('Query', $query);
$insertQuery = new BiscuitInsertQuery($query);
p_var($insertQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** DESTROY QUERY *** */
$query = "DESTROY users;";
p_info('Query', $query);
$destroyQuery = new BiscuitDestroyQuery($query);
p_var($destroyQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** REMOVE QUERY *** */
$query = "REMOVE users { WHERE(username:eq:user001) };";
p_info('Query', $query);
$removeQuery = new BiscuitRemoveQuery($query);
p_var($removeQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** CHANGE QUERY *** */
$query = "CHANGE users { ADD(join_date:date) };";
p_info('Query', $query);
$changeQuery = new BiscuitChangeQuery($query);
p_var($changeQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

echo '<hr />';

/* *** UPDATE QUERY *** */
$query = "UPDATE users { SET(user_id:123) WHERE(status:eq:A) };";
p_info('Query', $query);
$updateQuery = new BiscuitUpdateQuery($query);
p_var($updateQuery);
// MySQL output test
$mysql_biscuit = new BiscuitMySQL(new BiscuitQuery($query));
p_info('MySQL Output', $mysql_biscuit->getMySQLResult(), 'blue');

?>

<style>
    body, pre {
        font-family: "Monaco", monospace;
        font-size: 14px;
    }
</style>