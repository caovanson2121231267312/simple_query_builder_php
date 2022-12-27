<?php
namespace Caoson\SimpleQuery\QueryBuilder;

use Caoson\SimpleQuery\QueryBuilder\QueryBuilder;

class DB {
    public static function table($table){
        try {
            return (new QueryBuilder())->from($table);
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }
}
?>