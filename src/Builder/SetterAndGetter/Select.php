<?php

namespace App\Builder\SetterAndGetter;

class Select implements SetterAndGetterInterface
{
    protected static array $data = [];

    /**
     * Get select data
     * @return array
     */
    public static function get() {

        static::sort();
        return static::$data;
    }

    /**
     * Set select data
     * @param $data
     * @return void
     */
    public static function set($data) {
        self::mergeColumnAndAddIFISNew($data);
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
     * @return void
     */
    private static function sort(){
        usort(static::$data,fn($a,$b)=>strcmp($a['position'], $b['position']));
    }

    /**
     * Remove duplicate query
     * @param $data
     * @return void
     */
    private static function mergeColumnAndAddIFISNew($data): void
    {
        $new = false;
        foreach (static::$data as $key => $item) {
            if ($data['type'] == 'select' and $item['type'] == 'select') {
                $new = true;
                self::mergeColumn($key, $data['column'],$data['query']);
            }
            if ($data['type'] == 'groupBy' and $item['type'] == 'groupBy') {
                $new = true;
                self::mergeColumn($key, $data['column'],$data['query']);
            }
            if ($data['type'] == 'orderBy' and $item['type'] == 'orderBy') {
                $new = true;
                self::mergeColumn($key, $data['column'],$data['query']);

            }
        }
        if (count(static::$data) <= 0 or !$new) {
            static::$data[] = $data;
        }
    }

    /**
     * It consolidates the data and sets the last query to use
     * @param $key
     * @param $column
     * @return void
     */
    private static function mergeColumn($key, $column,$query): void
    {
        static::$data[$key]['column'] = array_unique(array_merge(static::$data[$key]['column'], $column));
        static::$data[$key]['query'] = $query;
    }


}