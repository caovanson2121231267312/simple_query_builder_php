<?php
namespace Caoson\SimpleQuery;

use Caoson\SimpleQuery\Data;
use Caoson\SimpleQuery\QueryBuilder\QueryBuilder;

abstract class Model extends Data
{
    public $query;
    protected $table = '';

    public function __construct()
    {
        $this->query = (new QueryBuilder())->from($this->table);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->{"pre" . $name}(...$arguments);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return (new static)->{"pre" . $name}(...$arguments);
    }

    public function preselect(...$fields)
    {
        $this->query->select($fields);
        return $this;
    }

    public function prewhere(...$array)
    {
        $this->query->where(...$array);
        return $this;
    }

    public function prefind(array $search)
    {
        $result = $this->query->find($search);
        return $result === null ? $result : static::collection([$result])  ;
    }

    public function preget()
    {
        if(empty($this->query->getSelect())){
            $result = $this->query->select(["*"])->get();
        } else {
            $result = $this->query->get();
        }
        return $result === null ? $result : static::collection($result);
    }
    
    public function prefirst(){
        if(empty($this->query->getSelect())){
            $result = $this->query->select(["*"])->first();
        } else {
            $result = $this->query->first();
        }
        return  $result === null ? $result : static::collection([$result]);
    }
    
    public function prelimit(int $limit)
    {
        $this->query->limit($limit);
        return $this;
    }

    public function preorderBy(string $field, $type = 'asc'){
        $this->query->orderBy($field, $type);
        return $this;
    }
    
    public function precount(){
        if(empty($this->query->getSelect())){
            return $this->query->select(["count(*)"])->count();
        } else {
            return $this->query->count();
        }
    }

    public function preleftjoin(string $tableJoin, array $condition){
        $this->query->leftjoin($tableJoin,$condition);
        return $this;

    }
    public function prerightjoin(string $tableJoin, array $condition){
        $this->query->rightjoin($tableJoin,$condition);
        return $this;
    }

    public function getstr()
    {
        return $this->query->getPrepareString();
    }
    
    public function prejoin($tableJoin, array $condition){
        $this->query->join($tableJoin,$condition);
        return $this;
    }
    
    public function preinsert( array $data){
        $this->query->insert($data);

    }
    public function preupdate(array $data)
    {
        $this->query->update($data); 
    }

    public function predelete()
    {
       return $this->query->delete();
    }
    
    public function precreate(array $array ){
        return $this->query->insert($array);
    }

}