<?php

/*
 * Class to manage the where clauses.
 * Examples: WHERE(status:eq:a)
 * TODO: Where clauses do not support multiple arguments for AND/OR
 */

class WhereClause
{
    private $clause;
    private $arguments; // Array with column (0), operator (1), value (2)

    public function __construct($query)
    {
        $query = strtolower($query);
        $this->clause = WhereClause::getWhereClauseFromQuery($query);
        $this->arguments = $this->genArguments($this->clause);
    }

    public function getClause()
    {
        return $this->clause;
    }

    public function getWhereArguments()
    {
        return $this->arguments;
    }

    public static function getWhereClauseFromQuery($query)
    {
        $where_clause = null;
        // Use Regex to find the WHERE clause
        preg_match("/(where\(([^)]+)\))/", $query, $results);
        if (preg_match("/(where\(([^)]+)\))/", $query))
        {
            // If found return the where clause
            $where_clause = $results[1];
        }
        return $where_clause;
    }

    private function genArguments($clause)
    {
        preg_match("/where\(([^)]+)\\)/", $clause, $results);
        $arguments = explode(':', $results[1]);
        $arguments_array = array(
            'column' => $arguments[0],
            'operator' => $arguments[1],
            'value' => $arguments[2]
        );
        if (!WhereClause::isValidOperator($arguments_array['operator']))
        {
            // Check if operator is valid, if not set it to NDF
            $arguments_array['operator'] = 'ndf';
        }
        return $arguments_array;
    }

    public static function isValidOperator($operator)
    {
        $operator = strtolower($operator);
        $valid_operators = array('eq', 'gt', 'lt', 'gte', 'lte');
        foreach ($valid_operators as $valid)
        {
            if ($operator == $valid)
            {
                return true;
            }
        }
        return false;
    }
}

?>