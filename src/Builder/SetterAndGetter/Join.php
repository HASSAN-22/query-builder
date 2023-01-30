<?php

namespace App\Builder\SetterAndGetter;

class Join implements SetterAndGetterInterface
{
    protected static $data = [];

    /**
     * Get join data
     * @return array
     */
    public static function get() {
        return static::$data;
    }

    /**
     * Set data for join
     * @param $data
     * @return void
     */
    public static function set($data) {
        static::$data[] = $data;
    }

    /**
     * Empty data
     * @return void
     */
    public static function empty()
    {
        static::$data = [];
    }
}