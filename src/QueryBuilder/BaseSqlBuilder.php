<?php
namespace Caoson\SimpleQuery\QueryBuilder;

use Caoson\SimpleQuery\QueryBuilder\DB_Connection;
use PDO;

abstract class BaseSqlBuilder
{
    use SqlClauses;
    
    protected $connection;

    protected $fetchType = PDO::FETCH_OBJ;
   
    protected $insensitive;

    protected $logicOperatorss = [
        'or',
        'and',
    ];

    protected $comparisons = [
        'equal' => '=',
        'not_equal' => '<>',
        'not_equal_other' => '!=',
        'less' => '<',
        'less_or_equal' => '<=',
        'greater' => '>',
        'greater_or_equal' => '>=',
        'like' => 'like',
        'in' => 'in',
        'not_in' => 'not in',
        'between' => 'between',
        'not_between' => 'not between',
    ];

    public function __construct()
    {
        $this->connection = DB_Connection::connection();
    }

    public function execute($sql)
    {
        if($this->connection->prepare($sql)->execute()){
            return true;
        }
        return false;
    }

    public function get()
    {
        $sql = $this->getSqlStatement();
        // dd($sql);
        return $this->connection->query($sql)->fetchAll($this->fetchType);
    }

    public function first()
    {
        $sql = $this->getSqlStatement();
        return $this->connection->query($sql)->fetch($this->fetchType);
    }

    public function count()
    {
        $sql = $this->getSqlStatement();
        // dd($sql);
        return $this->connection->query($sql)->fetch($this->fetchType);
    }

    protected function addSelect(array $fields)
    {
        foreach ($fields as $key => $field) {
            $fields[$key] = $this->identifierOf($field);
        }
        $this->select = array_merge($this->select, $fields);
    }

    protected function getCompiledSelectClause()
    {
        return 'select ' . implode(', ', $this->select);
    }

    protected function addFrom(array $tables)
    {
        foreach ($tables as $key => $table) {
            $tables[$key] = $this->identifierOf($table);
        }

        $this->from = array_merge($this->from, $tables);
    }

    protected function getCompiledFromClause()
    {
        return 'from ' . implode(', ', $this->from);
    }

    protected function addWhere($field, $operator, $value, $logicOperator = 'and')
    {
        if (! in_array($operator, $this->comparisons)) {
            die("$operator is invalid operator.");
        }

        switch ($operator) {
            case $this->comparisons['in']:
            case $this->comparisons['not_in']:
                $value = $this->getValueForInClause($value);
                break;
            case $this->comparisons['between']:
            case $this->comparisons['not_between']:
                $value = $this->getValueForBetweenClause($value);
                break;
            default:
        }

        $field = $this->identifierOf($field);

        $this->where[] = [
            'logic_operator' => $logicOperator,
            'params' => compact('field', 'operator', 'value'),
        ];
    }

    protected function whereLogicOperator($logicOperator, ...$params)
    {
        list($field, $operator, $value) = $this->getParseWhereParameters($params);

        $this->addWhere($field, $operator, $value, $logicOperator);

        return $this;
    }

    protected function getParseWhereParameters(array $params)
    {
        if (count($params) === 3) {
            return $params;
        }
        
        if (count($params) === 2) {
            return [$params[0], $this->comparisons['equal'], $params[1]];
        }

        die('Not valid where parameters.');
    }

    protected function getCompiledWhereClause()
    {
        if (empty($this->where)) {
            return '';
        }

        $conditions = '';

        foreach ($this->where as $key => $where) {
            $conditions .= $where['logic_operator'] . ' '
                . $where['params']['field'] . ' '
                . $where['params']['operator'] . ' '
                . $where['params']['value'] . ' ';
        }

        $conditions = '(' . ltrim(ltrim($conditions, 'or'), 'and') . ')';

        return 'where ' . trim($conditions);
    }

    protected function identifierOf($identifier)
    {
        $identifier = strtolower($identifier);

        if (preg_match('/^(.+) as (.+)$/', $identifier)) {
            return $this->identifierWithAsKeywordOf($identifier);
        }

        if (strpos($identifier, '.') !== false) {
            return $this->identifierWithDotOf($identifier);
        }

        return $this->insensitive . trim($identifier) . $this->insensitive;
    }

    protected function identifierWithAsKeywordOf($identifier)
    {
        $data = explode(' as ', $identifier);

        if (count($data) == 2) {
            $baseField = $this->identifierOf($data[0]);
            $aliasField = $this->identifierOf($data[1]);

            return $baseField . ' as ' . $aliasField;
        }

        die($identifier . ' is invalid.');
    }

    protected function identifierWithDotOf($identifier)
    {
        $data = explode('.', $identifier);

        if (count($data) == 2) {
            $table = $this->identifierOf($data[0]);
            $field = $this->identifierOf($data[1]);

            return $table . '.' . $field;
        }

        die($identifier . ' is invalid.');
    }

    public function getSqlStatement()
    {
        $clauses['select'] = $this->getCompiledSelectClause();
        $clauses['from'] = $this->getCompiledFromClause();
        $clauses['where'] = $this->getCompiledWhereClause();
        $clauses['orderBy'] = $this->orderBy;
        $clauses['limit'] = $this->limit;

        $this->clearAllClauses();

        return implode(' ', $clauses);
    }

    protected function getValueForInClause(array $values)
    {
        return '(' . implode(', ', $values) . ')';
    }

    protected function getValueForBetweenClause(array $values)
    {
        if (count($values) !== 2) {
            die('This is invalid for between clause.');
        }

        return $values[0] . ' and ' . $values[1];
    }

    protected function clearAllClauses()
    {
        $this->select = [];
        $this->from = [];
        $this->where = [];
    }
}