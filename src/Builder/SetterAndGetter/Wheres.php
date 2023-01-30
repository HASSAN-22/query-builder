<?php

namespace App\Builder\SetterAndGetter;

class Wheres implements SetterAndGetterInterface
{
    protected static $data = [];

    /**
     * Get where data
     * @return array
     */
    public static function get() {
        if(!empty(static::$data)){
            static::sort('WHERE');
            static::sort('HAVING');
        }
        return static::$data;
    }

    /**
     * Set data bye default key where
     * @param $data
     * @param $type
     * @return void
     */
    public static function set($data,$type='WHERE') {
        static::$data[$type][] = $data;
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
     * Sort data
     * @param $type
     * @return void
     */
    private static function sort($type){
        if(!empty(static::$data[$type]))
            usort(static::$data[$type],fn($a,$b)=>strcmp($a['boolean'], $b['boolean']));
    }
}