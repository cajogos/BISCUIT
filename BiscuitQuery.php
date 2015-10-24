<?php

class BiscuitQuery
{
    private $query;
    private $type;
    private $table_name;

    public function __construct($query)
    {
        $this->query = $query;
        $this->type = BiscuitQuery::determineQueryType($query);
    }

    public function getQuery()
    {
        return $this->query;
    }

    protected function setTableName($name)
    {
        $this->table_name = $name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    /*
    * Function to determine what query type to use
    */
    public static function determineQueryType($query)
    {
        // Get the first value of the query
        $type = explode(' ', $query)[0];
        switch (strtolower($type)) {
            case 'build':
            case 'insert':
                $type = 'CREATE';
                break;
            case 'fetch':
                $type = 'READ';
                break;
            case 'change':
            case 'update':
                $type = 'UPDATE';
                break;
            case 'destroy':
            case 'remove':
                $type = 'DELETE';
                break;
            default:
                throw new BiscuitException('Invalid query type provided: ' . $type);
        }
        return strtolower($type);
    }

    /*
     * Function to convert to the correct child object type
     */
    public static function convertToChildQueryType(BiscuitQuery $query)
    {
        if ($query instanceof BiscuitQuery) {
            // Get the first value of the query
            $type = explode(' ', $query->getQuery())[0];
            switch (strtolower($type)) {
                case 'build':
                    return new BiscuitBuildQuery($query->getQuery());
                case 'insert':
                    return new BiscuitInsertQuery($query->getQuery());
                case 'fetch':
                    return new BiscuitFetchQuery($query->getQuery());
                case 'change':
                    return new BiscuitChangeQuery($query->getQuery());
                case 'update':
                    return new BiscuitUpdateQuery($query->getQuery());
                case 'destroy':
                    return new BiscuitDestroyQuery($query->getQuery());
                case 'remove':
                    return new BiscuitRemoveQuery($query->getQuery());
                default:
                    throw new BiscuitException('The provided query type does not match a valid type: ' . $type);
            }
        } else {
            return null;
        }
    }
}