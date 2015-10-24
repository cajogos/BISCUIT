<?php

/*
 * EXAMPLES
 * INSERT users { user_id:bcd001, age:45, status:A };
 */

class BiscuitInsertQuery extends BiscuitQuery
{
    private $insert_pairs;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        $this->insert_pairs = $this->genInsertPairs($query);
    }


    public function getInsertPairs()
    {
        return $this->insert_pairs;
    }

    private function genInsertPairs($query)
    {
        // Obtain the arguments
        preg_match("/ \{ (.*?) \}/", $query, $results);
        $arguments = explode(',', $results[1]);
        // Trim variables
        for ($i = 0; $i < count($arguments); $i++) {
            $arguments[$i] = trim($arguments[$i]);
        }
        $update_pairs = array();
        foreach ($arguments as $arg) {
            $args_array = explode(':', $arg);
            $pair = array(
                'column' => $args_array[0],
                'value' => $args_array[1]
            );
            array_push($update_pairs, $pair);
        }
        return $update_pairs;
    }
}