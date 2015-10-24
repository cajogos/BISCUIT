<?php

/*
 * EXAMPLES:
 * BUILD users { user_id:int:autoinc:notnull, username:string:notnull };
 * BUILD users;
 */

class BiscuitBuildQuery extends BiscuitQuery
{
    private $attributes;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        // Check that query contains curly braces -> attributes
        if (stripos($query, '{') && stripos($query, '}')) {
            $this->attributes = $this->genAttributes($query);
        } else {
            throw new BiscuitException('Failed to generate the attributes of this query: ' . $query);
        }
    }

    public function getAttributes()
    {
        if (count($this->attributes) > 0) {
            return $this->attributes;
        }
        return null;
    }

    // Generate the attributes from a complete query
    private function genAttributes($query)
    {
        preg_match("/ \{ (.*?) \}/", $query, $results);
        if (count($results) > 0) {
            $args = explode(',', $results[1]);
            // Trim the values obtained
            for ($i = 0; $i < count($args); $i++) {
                $args[$i] = trim($args[$i]);
            }
            // Convert the arguments into attribute objects
            $attributes_array = array();
            foreach ($args as $arg) {
                $attribute = new Attribute($arg);
                $attributes_array[] = $attribute;
            }
            return $attributes_array;
        } else {
            throw new BiscuitException('Failed to generate the attributes of this Build Query: ' . $query);
        }
    }
}