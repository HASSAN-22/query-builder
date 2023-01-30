## PDO QUERY BUILDER
____
#### PHP SQL query builder using PDO. It's a quick and light library featuring a smart join builder, which automatically creates table joins for you.
___
## Features

* Easy interface for creating robust queries
* Supports any database compatible with PDO
* Ability to build complex SELECT, INSERT, UPDATE & DELETE queries with little code
* Type hinting for magic functions with code completion in smart IDEs
___
## Install
```
composer require hasan-22/query-builder
```

## Driver:
First make the config file exactly like this
Config file is not necessary
config.php
```php 
<?php

return [

    'default'=>'mysql',

    'driver'=>[
        'mysql'=>[
            'host'=>'localhost',
            'username'=>'root',
            'password'=>'',
            'dbname'=>'php',
            'charset'=>'UTF8',
        ]
    ]
];
```
Then create a custom driver class for the connection like this

Mysql.php
```php 
<?php

class Mysql implements \App\Database\ConnectionInterface
{
    private static $instance;

    private function __construct(){}

    /**
     * This method prevents the creation of duplicate object
     * @return mixed
     */
    public static function setInstance(){
        if(!static::$instance instanceof Mysql){
            static::$instance = new Mysql();
        }
        return static::$instance;
    }

    /**
     * Get database connection information in configuration
     * @return array
     */
    public function driver(): array{
        /**
        * If you have set the database in the config file, use this code.
        */
        // Path of your config file
        $config = require __DIR__.'/Config/Database.php';
        $config = $config['driver'][$config['default']];
        return [
            'dns'=>"mysql:host=".$config['host'].";dbname=".$config['dbname'].";charset:".$config['charset'],  
            'password'=>$config['password'], 
            'username'=>$config['username']
        ];

        /**
        * Otherwise, you can send the database information directly like this
        *
        * return ['dns'=>'Your dns','username'=>'','password'=>''];
        *
        */ 
    }

    /**
     * Connect to database with PDO
     * @return \PDO|void
     */
    public function connect(){
        // The attribute parameter is optional
        $attributes = [
            \PDO::ATTR_CASE=>\PDO::CASE_NATURAL
        ];
        return \App\Database\PDO::connect($this->driver(),$attributes);
    }
}
```

Then create new instance of `DB`
```php
$db = new \App\Builder\DB("\App\Database\Mysql");
```
Set table and fetch all records
```php 
$db->table('users')->get()
```
In the `get()` function, you can specify which columns to get
```php
$db->table('users')->get('name','password','email')
```
Or you can use `all()` function to get all records
```php
$db->table('users')->all()
```
Execute queries for a single or many record by ID
```php
$db->table('users')->find()
// or
$db->table('users')->find(1,2,3)
```
---
If you want add condition follow my lead
### Some `Where` functions
```php
$db->table('users')->where('name','=','armia')->get();

// for `or where`
$db->table('users')->orWhere('name','=','armia')->get();

// for `where in`
$db->table('users')->whereIn('id',[1,2,3])->get();

// for `or wher in`
$db->table('users')->orWhereIn('id',[1,2,3])->get();

// for `where between`
$db->table('users')->whereBetween('crated_at',['2022-12-12','2023-01-29'])->get();

// for `or where between`
$db->table('users')->orWhereBetween('crated_at',['2022-12-12','2023-01-29'])->get();

// for `exists`
$db->table('brands')->exists('users','users.id = brands.user_id')->get();

// And you can run all the functions one after the other like the example below
$db->table('users')
->where('name','=','armia')
->orWhere('name','=','armia')
->whereIn('id',[1,2,3])
->orWhereIn('id',[1,2,3])
->whereBetween('crated_at',['2022-12-12','2023-01-29'])
->orWhereBetween('crated_at',['2022-12-12','2023-01-29'])
->get()
```
---
### Some `Having` functions
```php
$db->table('users')->havin('name','=','armia')->get();

// for `or where`
$db->table('users')->orHaving('name','=','armia')->get();

// for `where in`
$db->table('users')->havingIn('id',[1,2,3])->get();

// for `or wher in`
$db->table('users')->orHavingIn('id',[1,2,3])->get();

// for `where between`
$db->table('users')->havingBetween('crated_at',['2022-12-12','2023-01-29'])->get();

// for `or where between`
$db->table('users')->orHavingBetween('crated_at',['2022-12-12','2023-01-29'])->get();


// And you can run all the functions one after the other like the example below
$db->table('users')
->having('name','=','armia')
->orHaving('name','=','armia')
->havingIn('id',[1,2,3])
->orHavingIn('id',[1,2,3])
->havingBetween('crated_at',['2022-12-12','2023-01-29'])
->orHavingeBetween('crated_at',['2022-12-12','2023-01-29'])
->get()
```
---
### Join functions
```php
$db->table('users')->innerJoin('brands','brands.user_id','users.id')->get();

$db->table('users')->leftJoin('brands','brands.user_id','users.id')->get();

$db->table('users')->rightJoin('brands','brands.user_id','users.id')->get();

$db->table('users')->crossJoin('brands')->get();

```

You can use `where` or `having` condition with `join` functions
```php
$db->table('users')
->innerJoin('brands','brands.user_id','users.id')
->where('users.id','=',14)->get();
```
---
### CRUD functions

### For insert data use `create` function
Insert single data
```php
$data = ['name'=>'armia','email'=>'armiaevil@gmail.com','password'=>'123456'];
$db->table('users')->create($data);
```
Insert multiple data
```php
$data = [
    ['name'=>'armia','email'=>'armiaevil@gmail.com','password'=>'123456'],
    ['name'=>'armia','email'=>'armiaevil@gmail.com','password'=>'123456'],
    ['name'=>'armia','email'=>'armiaevil@gmail.com','password'=>'123456'],
];
$db->table('users')->create($data);
```

Update record
```php
$db->table('users')->where('id','=',1)->update(['name'=>'update name','email'=>'email@gmail.com']);
// or
$db->table('users')->update(['name'=>'update name','email'=>'email@gmail.com'],['id'=>1]);
// or
$db->table('users')->update(['name'=>'update name','email'=>'email@gmail.com'],['id'=>1,'status'=>'activated']);
```
Delete record
```php
$db->table('users')->where('id',1)->delete();
// or
$db->table('users')->delete(['id',1]);
// or
$db->table('users')->delete(['id',1,'status'=>'activated']);

```
---
### Transaction
```php
$db->beginTransaction();
try{
    // TODO: code
    
    $db->commit();
}catch (Exception $e){
    $db->rollback();
    // TODO: code
}
```

---
### Debug

Only works on functions whose return value is Object of class `DB`.
```php
$db->table('users')
->innerJoin('brands','brands.user_id','users.id')
->where('users.id','=',14)->debug();

/**
Output:
SQL: [86] SELECT * FROM users INNER JOIN brands ON brands.user_id = users.id WHERE users.id = ?
Sent SQL: [89] SELECT * FROM users INNER JOIN brands ON brands.user_id = users.id WHERE users.id = '14'
Params:  1
Key: Position #0:
paramno=0
name=[0] ""
is_param=1
param_type=2
NULL
*/
```
---
## Functions
| Functions          | return               |
|--------------------|----------------------|
| table              | Object of class `DB` |
| get                | Array                |
| all                | Array                |
| find               | object \ array       |
| newQQuery          | Array                |
| count              | object               |
| latest             | Object of class `DB` |
| orderBy            | Object of class `DB` |
| rand               | Object of class `DB` |
| groupBy            | Object of class `DB` |
| limit              | Object of class `DB` |
| take               | Object of class `DB` |
| first              | object               |
| last               | object               |
| max                | object               |
| min                | object               |
| sum                | object               |
| avg                | object               |
| where              | Object of class `DB` |
| orWhere            | Object of class `DB` |
| whereNull          | Object of class `DB` |
| orWhereNull        | Object of class `DB` |
| whereNotNull       | Object of class `DB` |
| orWhereNotNull     | Object of class `DB` |
| whereIn            | Object of class `DB` |
| orWhereIn          | Object of class `DB` |
| whereNotIn         | Object of class `DB` |
| orWhereNotIn       | Object of class `DB` |
| whereBetween       | Object of class `DB` |
| orWhereBetween     | Object of class `DB` |
| whereNotBetween    | Object of class `DB` |
| orWhereNotBetween  | Object of class `DB` |
| exists             | Object of class `DB` |
| orExists           | Object of class `DB` |
| notExists          | Object of class `DB` |
| orNotExists        | Object of class `DB` |
| having             | Object of class `DB` |
| orHaving           | Object of class `DB` |
| havingNull         | Object of class `DB` |
| orHavingNull       | Object of class `DB` |
| havingNotNull      | Object of class `DB` |
| orHavingNotNull    | Object of class `DB` |
| havingIn           | Object of class `DB` |
| orHavingIn         | Object of class `DB` |
| havingNotIn        | Object of class `DB` |
| orHavingNotIn      | Object of class `DB` |
| havingBetween      | Object of class `DB` |
| orHavingBetween    | Object of class `DB` |
| havingNotBetween   | Object of class `DB` |
| orHavingNotBetween | Object of class `DB` |
| innerJoin          | Object of class `DB` |
| leftJoin           | Object of class `DB` |
| rightJoin          | Object of class `DB` |
| crossJoin          | Object of class `DB` |
| create             | boolean              |
| update             | boolean              |
| delete             | boolean              |
| beginTransaction   | void                 |
| rollback           | void                 |
| commit             | void                 |
| getDbName          | string               |
| currentId          | object               |
| lastInsertId       | object               |
| emptyQuery         | void                 |
| debug              | string               |
