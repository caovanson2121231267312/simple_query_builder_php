# simple_query_builder_php

## composer require caoson/simple_query

* **Sử dụng Query builder**

```php
$user = DB::table('users')
        ->select('*')
        ->where('first_name',"like", "'%c%'")
        ->get();
dd($user);

```

```php
$user = DB::table('users')
        ->select('id','name')
        ->get();
dd($user);

```

* **Sử dụng Model**

* Truy vấn bảng users

```php
    $users = User::limit(3)->orderBy("id","desc")->where("first_name","like", "'%h%'")->get();
    dd($users);
```

* Thêm 1 bản ghi:

```php
$user = User::create([
    "email" => "abc@gmail.com",
    "password" => "12345678",
    "first_name" => "hhhhhhhhhhhhhh",
    "last_name" => "abcabcabcbac",
    "full_name" => "12313123",
    "phone" => "1231231313",
    "address" => "123",
    "user_name" => "123",
    "role" => "user",
]);
```

* Cập nhật 1 bản ghi:

```php
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

$user = User::where("id", "=", "2")->update($data);

```

* xóa 1 bản ghi:

```php
$user = User::where("id", "=", "21")->delete();

```