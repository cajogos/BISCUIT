<?php

/*
 * Class to manage the set clauses.
 * Examples: SET(user_id:123)
 */

class SetClause
{
    private $clause;
    private $arguments; // Array with column (0), value (1)

    public function __construct($query)
    {
        $query = strtolower($query);
        $this->clause = SetClause::getSetClauseFromQuery($query);
        $this->arguments = $this->genArguments($this->clause);
    }

    public function getClause() {
        return $this->clause;
    }

    public function getSetArguments() {
        return $this->arguments;
    }


    public static function getSetClauseFromQuery($query)
    {
        $set_clause = null;
        // Use Regex to find the WHERE clause
        preg_match("/(set\(([^)]+)\))/", $query, $results);
        if (preg_match("/(set\(([^)]+)\))/", $query))
        {
            // If found return the where clause
            $set_clause = $results[1];
        }
        return $set_clause;
    }

    private function genArguments($clause)
    {
        preg_match("/set\(([^)]+)\\)/", $clause, $results);
        $arguments = explode(':', $results[1]);
        $arguments_array = array(
            'column' => $arguments[0],
            'value' => $arguments[1]
        );
        return $arguments_array;
    }
}


?>