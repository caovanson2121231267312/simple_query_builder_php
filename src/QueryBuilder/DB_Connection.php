<?php
namespace Caoson\SimpleQuery\QueryBuilder;

use PDO;
class DB_Connection {
    public static function connection(){
        try {
            return new PDO(
                $_ENV["DB_CONNECTION"].
                ':host='.$_ENV["DB_HOST"].
                ';dbname='.$_ENV["DB_DATABASE"], 
                $_ENV["DB_USERNAME"], 
                $_ENV["DB_PASSWORD"]
            );
        } catch (Exception $exception) {
            echo "kết nỗi thất bại....";
            // die($exception->getMessage());
        }
    }
}
?>