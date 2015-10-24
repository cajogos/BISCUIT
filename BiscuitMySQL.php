<?php

/*
 * Class responsible for parsing BISCUIT into MySQL.
 */

class BiscuitMySQL implements Parser
{
    private $MySQL_Result;

    public function __construct($query_input)
    {
        try {
            $query = BiscuitQuery::convertToChildQueryType($query_input);

            if ($query instanceof BiscuitBuildQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseBuild($query);
            } else if ($query instanceof BiscuitChangeQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseChange($query);
            } else if ($query instanceof BiscuitFetchQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseFetch($query);
            } else if ($query instanceof BiscuitUpdateQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseUpdate($query);
            } else if ($query instanceof BiscuitInsertQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseInsert($query);
            } else if ($query instanceof BiscuitDestroyQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseDestroy($query);
            } else if ($query instanceof BiscuitRemoveQuery) {
                $this->MySQL_Result = BiscuitMySQL::parseRemove($query);
            }
        } catch (BiscuitException $e) {
            $this->MySQL_Result = $e->getExceptionMessage();
        }
    }

    public function getMySQLResult()
    {
        return $this->MySQL_Result;
    }

    /* *** BUILD QUERY *** */
    public static function parseBuild(BiscuitBuildQuery $query)
    {
        // BISCUIT --> BUILD users { user_id:int:autoinc:notnull, username:string:notnull };
        // MySQL --> CREATE TABLE users ( user_id BIGINT NOT NULL AUTO_INCREMENT, username TEXT NOT NULL );
        try {
            $MySQL_Result = "CREATE TABLE " . $query->getTableName() . " ( ";
            $attributes = $query->getAttributes();
            foreach ($attributes as $attr) {
                if ($attr instanceof Attribute) {
                    $key = $attr->getKey();
                    $value = BiscuitMySQL::getMySQLTypes($attr->getValue());
                    $MySQL_Result .= "\n" . $key . ' ' . $value;
                    // Check for extras
                    $extras = $attr->getExtras();
                    if (count($extras) > 0) {
                        foreach ($extras as $ext) {
                            $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLAttributes($ext);
                        }
                    }
                    $MySQL_Result .= ", ";
                } else {
                    break;
                }
            }
            // Remove comma
            $MySQL_Result = substr($MySQL_Result, 0, -2);
            $MySQL_Result .= "\n);";
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Build Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
            return $MySQL_Result;
        }
        return $MySQL_Result;
    }

    /* *** FETCH QUERY *** */
    public static function parseFetch(BiscuitFetchQuery $query)
    {
        // BISCUIT --> FETCH users { [user_id, status] WHERE(status:eq:A) SORT(asc:user_id) };
        // MySQL --> SELECT user_id, status FROM users WHERE status = 'A' ORDER BY user_id ASC;
        try {
            $MySQL_Result = "SELECT ";
            $columns = $query->getColumnNames();
            if ($columns != 'all') {
                $columns = implode(', ', $columns);
            } else {
                $columns = '*';
            }
            $MySQL_Result .= $columns;
            $MySQL_Result .= ' FROM ' . $query->getTableName();
            if ($query->getWhereClause()) {
                $where_args = $query->getWhereClause()->getWhereArguments();
                $MySQL_Result .= ' WHERE ' . $where_args['column'];
                $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLComparison($where_args['operator']) . ' ';
                $MySQL_Result .= '\'' . $where_args['value'] . '\'';
            }
            if ($query->getSortClause()) {
                $sort_args = $query->getSortClause()->getSortArguments();
                $MySQL_Result .= ' ORDER BY ' . $sort_args['column'];
                $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLFunction($sort_args['type']);
            }
            $MySQL_Result .= ';';
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Fetch Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
        }
        return $MySQL_Result;
    }

    /* *** CHANGE QUERY *** */
    public static function parseChange(BiscuitChangeQuery $query)
    {
        // BISCUIT --> CHANGE users { add(join_date:date) };
        // MYSQL --> ALTER TABLE users ADD join_date DATETIME;
        try {
            $MySQL_Result = 'ALTER TABLE ' . $query->getTableName();
            $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLAlter($query->getClauseType());
            $alter_args = $query->getArguments();
            $MySQL_Result .= ' ' . $alter_args['column'];
            if (count($alter_args) > 1)
                $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLTypes($alter_args['type']);
            $MySQL_Result .= ';';
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Change Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
            return $MySQL_Result;
        }
        return $MySQL_Result;
    }

    /* *** UPDATE QUERY *** */
    public static function parseUpdate(BiscuitUpdateQuery $query)
    {
        // BISCUIT --> UPDATE users { SET(user_id:123) WHERE(status:eq:A) };
        // MYSQL --> UPDATE users SET user_id = '123' WHERE status = 'A';
        try {
            $MySQL_Result = 'UPDATE ' . $query->getTableName();
            $set_args = $query->getSetClause()->getSetArguments();
            $MySQL_Result .= ' SET ' . $set_args['column'] . ' = \'' . $set_args['value'] . '\'';
            $where_args = $query->getWhereClause()->getWhereArguments();
            $MySQL_Result .= ' WHERE ' . $where_args['column'];
            $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLComparison($where_args['operator']) . ' ';
            $MySQL_Result .= '\'' . $where_args['value'] . '\'';
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Update Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
        }
        return $MySQL_Result;
    }

    /* *** INSERT QUERY *** */
    public static function parseInsert(BiscuitInsertQuery $query)
    {
        // BISCUIT --> INSERT users { user_id:bcd001, age:45, status:A };
        // MYSQL -->  INSERT INTO users (user_id, age, status) VALUES ('bcd001', '45', 'A');
        try {
            $MySQL_Result = 'INSERT INTO ' . $query->getTableName();
            $cols = array();
            $values = array();
            foreach ($query->getInsertPairs() as $pair) {
                $cols[] = $pair['column'];
                $values[] = $pair['value'];
            }
            $MySQL_Result .= ' (' . implode(', ', $cols) . ')';
            $MySQL_Result .= ' VALUES (\'' . implode('\', \'', $values) . '\')';
            $MySQL_Result .= ';';
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Insert Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
            return $MySQL_Result;
        }
        return $MySQL_Result;
    }

    /* *** DESTROY QUERY *** */
    public static function parseDestroy(BiscuitDestroyQuery $query)
    {
        // BISCUIT --> DESTROY users;
        // MySQL --> DROP TABLE IF EXISTS users;
        try {
            $MySQL_Result = "DROP TABLE IF EXISTS " . $query->getTableName() . ";";
            return $MySQL_Result;
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Delete Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
            return $MySQL_Result;
        }
    }

    /* *** REMOVE QUERY *** */
    public static function parseRemove(BiscuitRemoveQuery $query)
    {
        // BISCUIT --> REMOVE users { WHERE(username:eq:user001) };
        // MySQL --> DELETE FROM users WHERE username = 'user001';
        try {
            $MySQL_Result = "DELETE FROM " . $query->getTableName();
            $where_args = $query->getWhereClause()->getWhereArguments();
            $MySQL_Result .= ' WHERE ' . $where_args['column'];
            $MySQL_Result .= ' ' . BiscuitMySQL::getMySQLComparison($where_args['operator']) . ' ';
            $MySQL_Result .= '\'' . $where_args['value'] . '\'';
            $MySQL_Result .= ';';
        } catch (BiscuitException $e) {
            $MySQL_Result = 'Failed to process Remove Query into MySQL: ' . $query->getQuery();
            $MySQL_Result .= $e->getExceptionMessage();
        }
        return $MySQL_Result;
    }

    /*
     * MYSQL
     * Function to convert alter
     */
    public static function getMySQLAlter($alter)
    {
        switch ($alter) {
            case 'add':
                return 'ADD';
            case 'remove':
                return 'DROP';
            case 'rename':
                return 'RENAME TO';
            default:
                throw new BiscuitException('The alter method does not match a valid type: ' . $alter);
        }
    }

    /*
     * MYSQL
     * Function to convert comparison operators from literal strings to their MySQL values.
     */
    public static function getMySQLComparison($operator)
    {
        switch ($operator) {
            case 'eq':
                return '=';
            case 'gt':
                return '>';
            case 'lt':
                return '<';
            case 'gte':
                return '>=';
            case 'lte':
                return '<=';
            case 'neq':
                return '!=';
            default:
                throw new BiscuitException('Invalid BISCUIT to MySQL operator given: ' . $operator);
        }
    }

    /* MYSQL
     * Function to be able to parse the BISCUIT types into MySQL types
     */
    public static function getMySQLTypes($type)
    {
        switch ($type) {
            case 'string':
                return 'TEXT';
            case 'int':
                return 'BIGINT';
            case 'dec':
                return 'DOUBLE';
            case 'date':
                return 'DATE';
            case 'time':
                return 'TIME';
            case 'timestamp':
                return 'TIMESTAMP';
            default:
                throw new BiscuitException('Invalid BISCUIT to MySQL Type given: ' . $type);
        }
    }

    /* MYSQL
     * Function to be able to parse the BISCUIT attribute extras into MySQL
     */
    public static function getMySQLAttributes($attribute)
    {
        switch ($attribute) {
            case 'autoinc':
                return 'AUTO_INCREMENT';
            case 'notnull':
                return 'NOT NULL';
            case 'unique':
                return 'UNIQUE';
            default:
                throw new BiscuitException('Invalid MySQL Attribute given: ' . $attribute);
        }
    }

    /*
     * MYSQL
     * Function that returns the correct function names.
     */
    public static function getMySQLFunction($function)
    {
        switch ($function) {
            case 'asc':
                return 'ASC';
            case'dsc':
                return 'DESC';
            default:
                throw new BiscuitException('Failed to convert to the MySQL function: ' . $function);
        }
    }
}