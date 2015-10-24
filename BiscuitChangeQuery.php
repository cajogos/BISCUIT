<?php

/*
 * EXAMPLES
 * CHANGE users { add(join_date:date) };
 * CHANGE users { remove(join_date) };
 * CHANGE users { rename(join_date:join) };
 */

class BiscuitChangeQuery extends BiscuitQuery
{
    private $clause_type;
    private $arguments;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        // Get clause type
        preg_match("/(\w+)\(.*?\)/", $query, $results);
        $clause = $results[0];
        if (!BiscuitChangeQuery::validateClauseType($results[1])) {
            $this->clause_type = 'ndf'; // Set to NDF if is not valid
        } else {
            $this->clause_type = $results[1];
        }
        // Generate the arguments
        $this->arguments = $this->genArguments($clause);
    }

    public function getClauseType()
    {
        return $this->clause_type;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    private function genArguments($clause)
    {
        preg_match("/\w+\((.*?)\)/", $clause, $results);
        $results = explode(':', $results[1]);
        $arguments_array = array(
            'column' => $results[0]
        );
        if (count($results) > 1) {
            $arguments_array['type'] = $results[1];
        }
        return $arguments_array;
    }

    public static function validateClauseType($type)
    {
        $valid_types = array('add', 'remove', 'rename');
        foreach ($valid_types as $valid) {
            if ($type == $valid) {
                return true;
            }
        }
        return false;
    }
}

?>