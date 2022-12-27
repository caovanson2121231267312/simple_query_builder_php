<?php
namespace Caoson\SimpleQuery\QueryBuilder;

trait SqlClauses
{
    protected $select = [];

    protected $from = [];

    protected $where = [];

    protected $groupBy = [];

    protected $having = [];

    protected $orderBy;

    protected $limit;

    protected $offset;

    public function table()
    {
        return implode("", $this->from);
    }

    public function select($fields = ['*'])
    {
        $fields = is_array($fields) ? $fields : func_get_args();

        $this->addSelect($fields);

        return $this;
    }

    public function from($tables)
    {
        $tables = is_array($tables) ? $tables : func_get_args();
        $this->addFrom($tables);
        return $this;
    }

    public function limit(int $number)
    {
        $this->limit = "limit " . $number;
    }

    public function orderBy(string $field, string $type = 'asc')
    {
        $this->orderBy = "order by " . $field . " " . $type;
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function where(...$params)
    {
        return $this->andWhere(...$params);
    }

    public function andWhere(...$params)
    {
        return $this->whereLogicOperator('and', ...$params);
    }

    public function orWhere(...$params)
    {
        return $this->whereLogicOperator('or', ...$params);
    }

    public function insert(array $data)
    {
        $keys = [];$vals = [];
        foreach($data as $key => $value) {
            $keys[] = $key;$vals[] = $value;
        }
        $sql = "INSERT INTO ". $this->table() ."(" . implode(",", $keys) . ") VALUES ('" . implode("','",$vals) ."')";
        return $this->execute($sql);
    }

    public function update(array $data)
    {
        $sql = "UPDATE ". $this->table() ." SET ";
        $count = 0;
        $sizes = count($data);
        foreach($data as $key => $value) {
            $count++;
            $sql .= "`" . $key . "` = '" . $value . "' ";
            if($count < $sizes){
                $sql .= ", ";
            }
        }
        $sql .= implode(' ', array($this->getCompiledWhereClause()));
        return $this->execute($sql);
    }

    public function delete()
    {
        $sql = "DELETE FROM ". $this->table() . " ";
        $sql .= implode(' ', array($this->getCompiledWhereClause()));
        return $this->execute($sql);
    }
}