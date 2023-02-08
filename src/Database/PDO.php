<?php

namespace App\Database;

class PDO
{
    /**
     * Connect to database
     * @param array $dns
     * @param array $attributes
     * @return \PDO|void
     */
    public static function connect(array $dns, array $attributes=[]){
        try {
            $conn = new \PDO($dns['dns'],$dns['username'],$dns['password']);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            foreach ($attributes as $key=>$attribute){
                $conn->setAttribute($key, $attribute);
            }
            return $conn;
        } catch(\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
