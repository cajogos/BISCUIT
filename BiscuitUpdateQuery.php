<?php

/*
 * EXAMPLES
 * UPDATE users { SET(user_id:123) WHERE(status:eq:A) };
 * TODO: Update query does not change multiple values - only one at a time.
 */

class BiscuitUpdateQuery extends BiscuitQuery
{
    private $set_clause;
    private $where_clause;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        $this->set_clause = new SetClause($query);
        $this->where_clause = new WhereClause($query);
    }

    public function getSetClause()
    {
        return $this->set_clause;
    }

    public function getWhereClause()
    {
        return $this->where_clause;
    }
}