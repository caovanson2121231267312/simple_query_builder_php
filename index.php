<?php
require_once 'vendor/autoload.php';

use Caoson\SimpleQuery\QueryBuilder\DB;
use Caoson\SimpleQuery\User;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// $user = DB::table('users')
//         ->select('*')
//         ->where('first_name',"like", "'%c%'")
//         ->get();
        // ->select('id', 'full_name')
        // ->where('first_name', NULL);
        // ->all();
        // ->getSqlStatement();
// dd($user);

//$users= User::count();
// $users= User::select('id', "email")->where('first_name',"like", "'%c%'")->get();
$data = [
    "email" => "abc@gmail.com",
    "password" => "12345678",
    "first_name" => "hhhhhhhhhhhhhh",
    "last_name" => "abcabcabcbac",
    "full_name" => "12313123",
    "phone" => "1231231313",
    "address" => "123",
    "user_name" => "123",
    "role" => "user",
];
$users = User::limit(3)->orderBy("id",'desc')->where('first_name',"like", "'%c%'")->get();
// $user = User::where("id", "=", 2)->first();
$user = User::where("id", "=", "2")->update($data);
$user1 = User::where("id", "=", "21")->delete();
// $user = User::create([
//     "email" => "abc@gmail.com",
//     "password" => "12345678",
//     "first_name" => "hhhhhhhhhhhhhh",
//     "last_name" => "abcabcabcbac",
//     "full_name" => "12313123",
//     "phone" => "1231231313",
//     "address" => "123",
//     "user_name" => "123",
//     "role" => "user",
// ]);

dd($user1);

?>