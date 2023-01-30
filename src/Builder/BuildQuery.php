<?php

namespace App\Builder;

use App\Builder\SetterAndGetter\Join;
use App\Builder\SetterAndGetter\Select;
use App\Builder\SetterAndGetter\Values;
use App\Builder\SetterAndGetter\Wheres;

class BuildQuery
{

    private static array $nullOperator = [
        'IS NULL',
        'IS NOT NULL',
    ];

    private static array $inOperator = [
        'IN',
        'NOT IN',
        'NOT EXISTS',
        'EXISTS'
    ];

    private static array $ExistsOperator = [
        'NOT EXISTS',
        'EXISTS'
    ];

    private static string $queryString='';

    /**
     * Building a query for the Where clause
     * @return void
     */
    private static function buildWhereQuery(){
        if(count(Wheres::get()) > 0){
            foreach (Wheres::get() as $k => $wheres){
                static::$queryString .= " $k ";
                foreach ($wheres as $key => $where) {
                    if ($key == 0) {
                        $where['boolean'] = '';
                    }
                    if (in_array($where['operator'], static::$ExistsOperator)) {
                        static::$queryString .= sprintf(" %s %s (SELECT * FROM %s WHERE  %s) ", $where['boolean'], $where['operator'], $where['column'], $where['value']);
                    } else {

                        Values::merge($where['value']);
                        $questionMark = in_array($where['operator'], static::$nullOperator) ? '' : '?';

                        if (is_array($where['value'])) {
                            $separator = in_array($where['operator'], static::$inOperator) ? ',' : ' AND ';
                            $placeholder = implode($separator, array_fill(0, count($where['value']), $questionMark));
                            static::$queryString .= sprintf(" %s %s %s %s%s%s ",
                                $where['boolean'],
                                $where['column'],
                                $where['operator'],
                                $where['wrapParentheses'] ? '(' : '',
                                $placeholder,
                                $where['wrapParentheses'] ? ')' : '',
                            );

                        } else {
                            static::$queryString .= sprintf(" %s %s %s %s ",
                                $where['boolean'],
                                $where['column'],
                                $where['operator'],
                                $questionMark
                            );
                        }
                    }
                }

            }
        }
    }

    /**
     * Building a query for the Join clause
     * @return void
     */
    private static function buildJoinQuery(){
        foreach (Join::get() as $join){
            $sprintf = [' %s JOIN %s ',$join['type'],$join['table'],$join['right'],$join['left']];
            if($join['type']!='CROSS'){
                $sprintf[0] = $sprintf[0] . 'ON %s = %s ';
            }
            static::$queryString .= sprintf(
                $sprintf[0],
                $sprintf[1],
                $sprintf[2],
                $sprintf[3],
                $sprintf[4],
            );
        }

    }

    /**
     * Building a query for the Select clause and call other queries
     * @return void
     */
    public static function BuildQuery(){
        $runQueries = false;
        foreach (Select::get() as $value){
            static::$queryString .= str_replace([':fillable','*,'],[implode(',',$value['column']),''], $value['query']);

            if(!$runQueries){
                static::buildJoinQuery();
                static::buildWhereQuery();
                $runQueries =  true;
            }

        }
    }

    /**
     * Get string query
     * @return string
     */
    public static function getQuery(){
        return preg_replace('/\s\s+/', ' ', static::$queryString);
    }

    public static function emptyQuery(){
        static::$queryString = '';
    }
}