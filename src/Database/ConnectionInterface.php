<?php

namespace App\Database;

/**
 * @property $instance
 */
interface ConnectionInterface
{
    /**
     * This method prevents the creation of duplicate object
     * @return mixed
     */
    public static function setInstance();

    /**
     * Get database connection information in configuration
     * @return array
     */
    public function driver(): array;

    /**
     * Connect to database
     * @return \PDO|void
     */
    public function connect();
}