<?php
namespace Caoson\SimpleQuery;

class Collection {
    private $elements = [];

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function __toString()
    {
        return self::class . "@" . spl_object_hash($this);
    }

    public function createFrom(array $element)
    {
        return new static($element);
    }

    public function filter($callBack)
    {
        return array_filter($this->elements, $callBack, ARRAY_FILTER_USE_BOTH);
    }

    public function add($element)
    {
        $this->elements[] = $element;
        return true;
    }

    public function last()
    {
        return end($this->elements);
    }

    public function isEmpty()
    {
        return empty($this->elements);
    }

    public function clear()
    {
        $this->elements = [];
    }

    public function all()
    {
        return $this->elements;
    }

    public function count()
    {
        return $this->count($this->elements);
    }

    public function toArray()
    {
        $result = [];
        foreach ($this->elements as $element) {
            $result[] = $element->getAttributes();
        }
        return $result;
    }

    public function map($callBack)
    {
        return array_map($callBack, $this->elements);
    }

    public function first()
    {
        return reset($this->elements);
    }

    public function getIterator()
    {
    }

    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->elements[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
    }
}
?>