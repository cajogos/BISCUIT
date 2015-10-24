<?php

interface Parser
{
    public static function parseBuild(BiscuitBuildQuery $query);

    public static function parseFetch(BiscuitFetchQuery $query);

    public static function parseChange(BiscuitChangeQuery $query);

    public static function parseUpdate(BiscuitUpdateQuery $query);

    public static function parseInsert(BiscuitInsertQuery $query);

    public static function parseDestroy(BiscuitDestroyQuery $query);

    public static function parseRemove(BiscuitRemoveQuery $query);
}