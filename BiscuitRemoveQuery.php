<?php

/**
 * EXAMPLES
 * REMOVE users { WHERE(username:eq:user001) };
 */
class BiscuitRemoveQuery extends BiscuitQuery
{
    private $where_clause;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        $this->where_clause = new WhereClause($query);
    }

    public function getWhereClause()
    {
        return $this->where_clause;
    }
}