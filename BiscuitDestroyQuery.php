<?php

/*
 * EXAMPLES:
 * DESTROY users;
 */

class BiscuitDestroyQuery extends BiscuitQuery
{

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(rtrim(explode(' ', $query)[1], ';'));
    }
}