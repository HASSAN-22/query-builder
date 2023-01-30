<?php

namespace App\Builder\SetterAndGetter;

class Values implements SetterAndGetterInterface
{
    protected static $data = [];

    /**
     * Get values
     * @return array
     */
    public static function get() {
        return static::$data;
    }

    /**
     * Set values
     * @param $data
     * @return void
     */
    public static function set($data) {
        static::$data = $data;
    }

    /**
     * Empty data
     * @return void
     */
    public static function empty()
    {
        static::$data = [];
    }

    /**
     * Merges the values together, then creates a new array
     * @param $values
     * @return void
     */
    public static function merge($values){
        static::set(array_merge(self::$data, is_array($values) ? $values : [$values]));
    }
}