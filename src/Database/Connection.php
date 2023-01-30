<?php

namespace App\Database;


class Connection
{
    /**
     * Connect to database
     * @param ConnectionInterface $connection
     * @return mixed
     */
    public static function connect(ConnectionInterface $connection){
        return $connection->connect();
    }
}