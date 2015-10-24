<?php

/*
 * Class to manage the sort clauses.
 * The sort are used to sort (order by) the results.
 * Example: SORT(asc, user_id)
 */

class SortClause
{
    private $clause;
    private $arguments; // Array with type (0) and column (1)

    public function __construct($query)
    {
        $query = strtolower($query);
        $this->clause = SortClause::getSortClauseFromQuery($query);
        $this->arguments = $this->genArguments($this->clause);
    }

    public function getClause()
    {
        return $this->clause;
    }

    public function getSortArguments()
    {
        return $this->arguments;
    }

    public static function getSortClauseFromQuery($query)
    {
        $sort_clause = null;
        // Use Regex to find the SORT clause
        preg_match("/(sort\(([^)]+)\))/", $query, $results);
        if (preg_match("/(sort\(([^)]+)\))/", $query)) {
            // If found return the sort clause
            $sort_clause = $results[1];
        }
        return $sort_clause;
    }

    private function genArguments($clause)
    {
        preg_match("/sort\(([^)]+)\\)/", $clause, $results);
        $arguments = explode(':', $results[1]);
        $arguments_array = array(
            'type' => $arguments[0],
            'column' => $arguments[1]
        );
        // Check if valid type argument
        if (!SortClause::isValidSortType($arguments_array['type'])) {
            // If not valid - set to default to asc
            $arguments_array['type'] = 'asc';
        }
        return $arguments_array;
    }

    public static function isValidSortType($type)
    {
        $valid_types = array('asc', 'dsc');
        foreach ($valid_types as $valid) {
            if ($type == $valid) {
                return true;
            }
        }
        return false;
    }
}

?>