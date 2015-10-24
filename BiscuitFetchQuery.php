<?php

/*
 * EXAMPLES:
 * FETCH users { all }; --> select * from users;
 * FETCH users { [id, user_id, status] }; --> select id, user_id, status from users;
 * FETCH users { all WHERE(status:eq:A) }; --> select * from users where status='A';
 * FETCH users { [user_id, status] WHERE(status:eq:A) }; --> select user_id, status from users WHERE status='A';
 * FETCH users { [user_id, status] WHERE(status:eq:A) SORT(asc:user_id) };
 */

class BiscuitFetchQuery extends BiscuitQuery
{
    private $columns_array = array();
    private $where_clause;
    private $sort_clause;

    public function __construct($query)
    {
        parent::__construct($query);
        $this->setTableName(explode(' ', $query)[1]);
        // Check if there are square brackets
        if (stripos($query, '[') && stripos($query, ']')) {
            $this->columns_array = $this->genColumnNames($query);
        } else if (stripos($query, 'all')) {
            $this->columns_array = 'all';
        }
        // Check if there are WHERE arguments
        if (stripos($query, 'where')) {
            $this->where_clause = new WhereClause($query);
        }
        // Check if there are SORT arguments
        if (stripos($query, 'sort')) {
            $this->sort_clause = new SortClause($query);
        }
    }

    private function genColumnNames($query)
    {
        preg_match("/\[(.*?)\]/", $query, $results);
        $columns = explode(',', $results[1]);
        for ($i = 0; $i < count($columns); $i++) {
            $columns[$i] = trim($columns[$i]);
        }
        return $columns;
    }

    public function getColumnNames()
    {
        return $this->columns_array;
    }

    public function getWhereClause()
    {
        if ($this->where_clause) return $this->where_clause;
        return false;
    }

    public function getSortClause()
    {
        if ($this->sort_clause) return $this->sort_clause;
        return false;
    }
}

?>