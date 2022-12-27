<?php
namespace Caoson\SimpleQuery\Trait;

trait HasAttributes
{
    protected $original = [];
    protected $casts = [];
    private $attributes = [];
    protected static $default_casts = [];

    protected function getCasts(): array
    {
        return [...self::$default_casts, $this->casts];
    }

    protected function isCastAttribute($key)
    {
        return in_array($key, $this->getCasts());
    }

    protected function getCastType($key)
    {
        return $this->getCasts()[$key] ?? null;
    }

    protected function castAttribute($key, $value)
    {
        if ($this->isCastAttribute($key)) {
            $cast_type = $this->getCastType($key);
            if ($value == null) {
                return null;
            }
            switch ($cast_type) {
                case 'int':
                case 'integer':
                    return (int)$value;
                case 'real':
                case 'float':
                case 'double':
                    return (float)$value;
                case 'string':
                    return (string)$value;
                case 'bool':
                case 'boolean':
                    return (bool)$value;
                case 'array':
                case 'json':
                    return json_decode($value, true);
                case 'datetime':
                    $timestamp = strtotime($value);

                    $time = new Carbon();
                    $time->setTimestamp($timestamp);
                    return $time;
            }
        }
     return $value;
    }
    public  function getAttributes()
    {
        return $this->attributes;
    }
    public  function  getAttribute($name){
        return $this->getAttributes()[$name] ?? null;
    }
    public  function  __get($name){
       return $this->getAttribute($name);
    }
    public function setAttribute($key,$value)
    {
        if($this->isCastAttribute($key))
        {
            $value = $this->castAttribute($key,$value);
        }
        $this->attributes[$key] = $value;
        return $this;

    }
    public function setAttributes(array $array){
        foreach ($array as $key => $val){
            $this->setAttribute($key,$val);
        }
        return $this;
    }

    public  function  __set($key, $value){
        return $this->setAttribute($key,$value);
    }
    public  function  setOriginal(array $original){
        $this->original = $original;
    }
    public  function  getOriginal()
    {
        return $this->original;
    }


}