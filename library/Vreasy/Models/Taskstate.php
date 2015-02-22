<?php

namespace Vreasy\Models;

use Vreasy\Query\Builder;

class Taskstate extends Base
{
    // Protected attributes should match table columns
    protected $id;
    protected $state;

    public function __construct()
    {
        // Validation is done run by Valitron library
        $this->validates(
            'required',
            ['state']
        );
    }

    public function save()
    {
        // Base class forward all static:: method calls directly to Zend_Db
        if ($this->isValid()) {
            if ($this->isNew()) {
                static::insert('taskstates', $this->attributesForDb());
                $this->id = static::lastInsertId();
            } else {
                static::update(
                    'taskstates',
                    $this->attributesForDb(),
                    ['id = ?' => $this->id]
                );
            }
            return $this->id;
        }
    }

    public static function findOrInit($id)
    {
        $taskstate = new Taskstate();
        if ($taskstatesFound = static::where(['id' => (int)$id])) {
            $taskstate = array_pop($taskstatesFound);
        }
        return $taskstate;
    }


    public static function where($params, $opts = [])
    {
        // Default options' values
        $limit = 0;
        $start = 0;
        $orderBy = ['id'];
        $orderDirection = ['asc'];
        extract($opts, EXTR_IF_EXISTS);
        $orderBy = array_flatten([$orderBy]);
        $orderDirection = array_flatten([$orderDirection]);

        // Return value
        $collection = [];
        // Build the query
        list($where, $values) = Builder::expandWhere(
            $params,
            ['wildcard' => true, 'prefix' => 't.']);

        // Select header
        $select = "SELECT t.* FROM taskstates AS t";

        // Build order by
        foreach ($orderBy as $i => $value) {
            $dir = isset($orderDirection[$i]) ? $orderDirection[$i] : 'ASC';
            $orderBy[$i] = "`$value` $dir";
        }
        $orderBy = implode(', ', $orderBy);

        $limitClause = '';
        if ($limit) {
            $limitClause = "LIMIT $start, $limit";
        }

        $orderByClause = '';
        if ($orderBy) {
            $orderByClause = "ORDER BY $orderBy";
        }
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "$select $where $orderByClause $limitClause";
        if ($res = static::fetchAll($sql, $values)) {
            foreach ($res as $row) {
                $collection[] = static::instanceWith($row);
            }
        }
        return $collection;
    }
}
