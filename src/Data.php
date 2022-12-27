<?php
namespace Caoson\SimpleQuery;

use Caoson\SimpleQuery\Interface\Jsonable;
use Caoson\SimpleQuery\Interface\Arrayable;
use Caoson\SimpleQuery\Collection;
use Caoson\SimpleQuery\Trait\HasAttributes;

class Data implements Jsonable, Arrayable
{
    use HasAttributes;

    public function toJson($option = 0)
    {
        // TODO: Implement toJson() method.
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public static function from(array $attributes)
    {
        $instance = new static();
        return $instance->setAttributes($attributes);
    }

    public static function collection(array $items)
    {
        $items = array_map(function ($item) {
            if ($item instanceof static) {
                return $item;
            }
            return static::from(array($item));
        }, $items);

        return new Collection($items);
    }

}